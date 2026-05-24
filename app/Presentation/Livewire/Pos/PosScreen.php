<?php

namespace App\Presentation\Livewire\Pos;

use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use App\Domain\Table\Models\Table;
use App\Application\Actions\Order\PlaceOrderAction;
use Livewire\Component;

class PosScreen extends Component
{
    // Filter & Menu properties
    public ?int $selectedCategory = null;

    // Mobile tabs: 'menu' (shows product grid) or 'cart' (shows active cart)
    public string $activeTab = 'menu';

    // Shopping Cart stored locally as property
    public array $cart = [];

    // Order Details State
    public string $orderType = 'dine_in'; // dine_in, takeaway
    public ?string $tableId = null;
    public string $customerName = '';
    public string $customerPhone = '';
    public string $notes = '';

    // Modal - Product Customizations State
    public bool $showModal = false;
    public ?Menu $selectedMenu = null;
    public array $selectedToppings = [];
    public ?int $selectedSpiciness = null;
    public int $quantity = 1;

    // Modal - Payment Checkout State
    public bool $showPaymentModal = false;
    public string $paymentMethod = 'tunai'; // tunai, non_tunai
    public $cashAmount = 0;

    /**
     * Initalize component states.
     */
    public function mount(): void
    {
        $this->cart = [];
    }

    /**
     * Filter menu items by category selection.
     *
     * @param int|null $categoryId
     */
    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
    }

    /**
     * Open product customization modal.
     * Shortcut: If the menu has no toppings or spiciness levels, add immediately to cart.
     *
     * @param int $menuId
     */
    public function openAddModal(int $menuId): void
    {
        $menu = Menu::with(['toppings', 'spicinessLevels'])->findOrFail($menuId);

        // Shortcut: No customizations required
        if ($menu->toppings->isEmpty() && $menu->spicinessLevels->isEmpty()) {
            $subtotal = $menu->price;
            $item = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => 1,
                'toppings' => [],
                'spiciness_level' => null,
                'subtotal' => $subtotal,
            ];

            // Check if already in cart to increment or append new
            $existsKey = $this->findCartItemKey($menu->id, [], null);
            if ($existsKey) {
                $this->cart[$existsKey]['quantity']++;
                $this->recalculateItemSubtotal($existsKey);
            } else {
                $this->cart[uniqid()] = $item;
            }

            session()->flash('success', "{$menu->name} berhasil ditambahkan ke keranjang.");
            return;
        }

        // Open custom additions modal
        $this->selectedMenu = $menu;
        $this->selectedToppings = [];
        $this->selectedSpiciness = null;
        $this->quantity = 1;
        $this->showModal = true;
    }

    /**
     * Close customization modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedMenu = null;
    }

    /**
     * Add customized item to shopping cart.
     */
    public function addToCart(): void
    {
        if (!$this->selectedMenu) {
            return;
        }

        $subtotal = $this->selectedMenu->price;

        // Fetch toppings and compute subtotal
        $toppings = Topping::whereIn('id', $this->selectedToppings)->get();
        foreach ($toppings as $topping) {
            $subtotal += $topping->price;
        }

        // Spiciness level configuration
        $spiciness = null;
        if ($this->selectedSpiciness) {
            $spicinessModel = SpicinessLevel::find($this->selectedSpiciness);
            if ($spicinessModel) {
                $spiciness = [
                    'id' => $spicinessModel->id,
                    'name' => $spicinessModel->name,
                ];
            }
        }

        $item = [
            'menu_id' => $this->selectedMenu->id,
            'name' => $this->selectedMenu->name,
            'price' => $this->selectedMenu->price,
            'quantity' => $this->quantity,
            'toppings' => $toppings->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'price' => $t->price])->toArray(),
            'spiciness_level' => $spiciness,
            'subtotal' => $subtotal * $this->quantity,
        ];

        // Check if item with identical options is in cart
        $existsKey = $this->findCartItemKey($this->selectedMenu->id, $item['toppings'], $spiciness);
        if ($existsKey) {
            $this->cart[$existsKey]['quantity'] += $this->quantity;
            $this->recalculateItemSubtotal($existsKey);
        } else {
            $this->cart[uniqid()] = $item;
        }

        $this->closeModal();
        session()->flash('success', "{$item['name']} berhasil ditambahkan ke keranjang.");
    }

    /**
     * Remove item from shopping cart.
     *
     * @param string $key
     */
    public function removeFromCart(string $key): void
    {
        unset($this->cart[$key]);
    }

    /**
     * Increment cart item quantity.
     *
     * @param string $key
     */
    public function incrementQuantity(string $key): void
    {
        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity']++;
            $this->recalculateItemSubtotal($key);
        }
    }

    /**
     * Decrement cart item quantity.
     *
     * @param string $key
     */
    public function decrementQuantity(string $key): void
    {
        if (isset($this->cart[$key])) {
            if ($this->cart[$key]['quantity'] > 1) {
                $this->cart[$key]['quantity']--;
                $this->recalculateItemSubtotal($key);
            } else {
                $this->removeFromCart($key);
            }
        }
    }

    /**
     * Set direct explicit quantity.
     *
     * @param string $key
     * @param int $qty
     */
    public function updateQuantity(string $key, int $qty): void
    {
        if (isset($this->cart[$key])) {
            if ($qty >= 1) {
                $this->cart[$key]['quantity'] = $qty;
                $this->recalculateItemSubtotal($key);
            } else {
                $this->removeFromCart($key);
            }
        }
    }

    /**
     * Retrieve aggregate price of the entire cart.
     *
     * @return float
     */
    public function getCartTotal(): float
    {
        return collect($this->cart)->sum('subtotal');
    }

    /**
     * Open checkout payment modal with full safety validation.
     */
    public function openPaymentModal(): void
    {
        $this->resetErrorBag();

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }

        if ($this->orderType === 'dine_in' && !$this->tableId) {
            $this->addError('tableId', 'Meja harus dipilih untuk tipe Dine-in.');
            return;
        }

        $this->cashAmount = $this->getCartTotal(); // Autofill exact money as starting helper
        $this->showPaymentModal = true;
    }

    /**
     * Close checkout payment modal.
     */
    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    /**
     * Real-time calculation of change owed to the customer.
     *
     * @return float
     */
    public function calculateChange(): float
    {
        return max(0, floatval($this->cashAmount) - $this->getCartTotal());
    }

    /**
     * Commit order creation via PlaceOrderAction.
     */
    public function placeOrder(): void
    {
        $this->resetErrorBag();

        if (empty($this->cart)) {
            return;
        }

        if ($this->orderType === 'dine_in' && !$this->tableId) {
            $this->addError('tableId', 'Meja harus dipilih untuk tipe Dine-in.');
            return;
        }

        $total = $this->getCartTotal();

        // Validate cashier cash input
        if ($this->paymentMethod === 'tunai') {
            if (floatval($this->cashAmount) < $total) {
                $this->addError('cashAmount', 'Uang tunai pembayaran tidak mencukupi.');
                return;
            }
            $status = 'paid';
        } else {
            $status = 'payment_pending';
        }

        // Execute core place order process
        $placeOrderAction = app(PlaceOrderAction::class);
        $tableParam = ($this->orderType === 'dine_in') ? intval($this->tableId) : null;

        $order = $placeOrderAction->execute(
            cart: $this->cart,
            tableId: $tableParam,
            customerName: $this->customerName ?: null,
            customerPhone: $this->customerPhone ?: null,
            notes: $this->notes ?: null,
            status: $status
        );

        // Format premium outcome notice
        $successMsg = "Transaksi {$order->order_number} berhasil disimpan!";
        if ($this->paymentMethod === 'tunai') {
            $change = floatval($this->cashAmount) - $total;
            $successMsg .= " Kembalian: Rp " . number_format($change, 0, ',', '.');
        }

        // Reset POS Terminal state
        $this->cart = [];
        $this->customerName = '';
        $this->customerPhone = '';
        $this->notes = '';
        $this->tableId = null;
        $this->cashAmount = 0;
        $this->showPaymentModal = false;
        $this->activeTab = 'menu';

        session()->flash('success', $successMsg);
    }

    /**
     * Check if a cart item with exact toppings & spiciness exists.
     */
    private function findCartItemKey(int $menuId, array $toppings, ?array $spiciness): ?string
    {
        $topIds = collect($toppings)->pluck('id')->sort()->values()->toArray();
        $spiceId = $spiciness['id'] ?? null;

        foreach ($this->cart as $key => $item) {
            if ($item['menu_id'] !== $menuId) {
                continue;
            }

            $itemTopIds = collect($item['toppings'])->pluck('id')->sort()->values()->toArray();
            $itemSpiceId = $item['spiciness_level']['id'] ?? null;

            if ($topIds === $itemTopIds && $spiceId === $itemSpiceId) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Utility method to recalculate item totals inside the cart.
     */
    private function recalculateItemSubtotal(string $key): void
    {
        $item = $this->cart[$key];
        $unitPrice = $item['price'];
        foreach ($item['toppings'] as $topping) {
            $unitPrice += $topping['price'];
        }
        $this->cart[$key]['subtotal'] = $unitPrice * $item['quantity'];
    }

    /**
     * Render the Livewire component.
     */
    public function render()
    {
        // Query lists of categories and dining tables
        $categories = Category::orderBy('sort', 'asc')->get();
        $tables = Table::orderBy('table_number', 'asc')->get();

        // Retrieve menu items
        $menusQuery = Menu::where('is_available', true)
            ->with(['category', 'toppings', 'spicinessLevels']);

        if ($this->selectedCategory) {
            $menusQuery->where('category_id', $this->selectedCategory);
        }

        $menus = $menusQuery->get();

        return view('livewire.pos.pos-screen', [
            'categories' => $categories,
            'tables' => $tables,
            'menus' => $menus,
        ])->layout('layouts.pos');
    }
}
