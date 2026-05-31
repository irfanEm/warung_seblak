<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

#[Layout('layouts.admin')]
class PosScreen extends Component
{
    public array $menus = [];
    public array $categories = [];
    public array $toppings = [];
    public array $spicinessLevels = [];
    public array $tables = [];
    
    public string $search = '';
    public ?string $selectedCategory = null;
    
    // Keranjang
    public array $cart = [];

    // Modal Item
    public bool $showItemModal = false;
    public ?array $selectedMenu = null;
    public int $qty = 1;
    public array $selectedToppings = [];
    public ?string $selectedSpiciness = null;

    // Modal Checkout
    public bool $showCheckoutModal = false;
    public string $orderType = 'takeaway';
    public ?int $selectedTableId = null;
    public $amountReceived = null;

    public function mount()
    {
        // Load data dari session
        $this->menus = session('admin.menus', $this->dummyMenus());
        
        // Ekstrak kategori unik dari menus
        $this->categories = collect($this->menus)
            ->pluck('category')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->toppings = session('admin.toppings', $this->dummyToppings());
        $this->spicinessLevels = session('admin.spiciness', $this->dummySpiciness());
        $this->tables = session('admin.tables', $this->dummyTables());
    }

    public function selectCategory(?string $category)
    {
        $this->selectedCategory = $category;
    }

    // --- Computed Properties ---

    #[Computed]
    public function filteredMenus()
    {
        $filtered = collect($this->menus);
        
        if ($this->selectedCategory) {
            $filtered = $filtered->where('category', $this->selectedCategory);
        }

        if (trim($this->search)) {
            $searchStr = strtolower(trim($this->search));
            $filtered = $filtered->filter(fn($m) => str_contains(strtolower($m['name']), $searchStr));
        }
        return $filtered->all();
    }

    #[Computed]
    public function cartTotal()
    {
        return collect($this->cart)->sum('subtotal');
    }

    #[Computed]
    public function changeAmount()
    {
        $received = (float) $this->amountReceived;
        $total = $this->cartTotal();
        return max(0, $received - $total);
    }

    // --- Item Modal Actions ---

    public function openItemModal(int $menuId)
    {
        $menu = collect($this->menus)->firstWhere('id', $menuId);
        if ($menu) {
            $this->selectedMenu = $menu;
            $this->qty = 1;
            $this->selectedToppings = [];
            $this->selectedSpiciness = null;
            $this->showItemModal = true;
        }
    }

    public function closeItemModal()
    {
        $this->showItemModal = false;
        $this->selectedMenu = null;
    }

    public function incrementQty()
    {
        $this->qty++;
    }

    public function decrementQty()
    {
        if ($this->qty > 1) {
            $this->qty--;
        }
    }

    public function addToCart()
    {
        if (!$this->selectedMenu) return;

        // Hitung total topping
        $toppingsPrice = 0;
        $toppingsList = [];
        foreach ($this->selectedToppings as $toppingId) {
            $topping = collect($this->toppings)->firstWhere('id', $toppingId);
            if ($topping) {
                $toppingsPrice += $topping['price'] ?? 0;
                $toppingsList[] = $topping['name'];
            }
        }

        $basePrice = $this->selectedMenu['price'];
        $unitPrice = $basePrice + $toppingsPrice;
        
        $spicinessName = null;
        if ($this->selectedSpiciness) {
            $spiciness = collect($this->spicinessLevels)->firstWhere('id', $this->selectedSpiciness);
            if ($spiciness) $spicinessName = $spiciness['name'];
        }

        $cartItem = [
            'id' => uniqid(),
            'menu_id' => $this->selectedMenu['id'],
            'name' => $this->selectedMenu['name'],
            'qty' => $this->qty,
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $this->qty,
            'toppings' => $toppingsList,
            'spiciness' => $spicinessName,
        ];

        $this->cart[] = $cartItem;
        
        $this->closeItemModal();
        $this->dispatch('notify', ['message' => 'Item ditambahkan ke keranjang']);
    }

    // --- Cart Actions ---

    public function removeFromCart(string $cartId)
    {
        $this->cart = collect($this->cart)->reject(fn($item) => $item['id'] === $cartId)->values()->all();
    }

    public function updateCartQty(string $cartId, int $delta)
    {
        foreach ($this->cart as &$item) {
            if ($item['id'] === $cartId) {
                $newQty = $item['qty'] + $delta;
                if ($newQty > 0) {
                    $item['qty'] = $newQty;
                    $item['subtotal'] = $item['qty'] * $item['unit_price'];
                }
                break;
            }
        }
    }

    // --- Checkout Actions ---

    public function openCheckoutModal()
    {
        if (count($this->cart) === 0) return;
        
        $this->orderType = 'takeaway';
        $this->selectedTableId = null;
        $this->amountReceived = null;
        $this->showCheckoutModal = true;
    }

    public function closeCheckoutModal()
    {
        $this->showCheckoutModal = false;
    }

    public function processCheckout()
    {
        $this->validate([
            'amountReceived' => 'required|numeric|min:' . $this->cartTotal(),
            'orderType' => 'required|in:takeaway,dine_in',
            'selectedTableId' => 'required_if:orderType,dine_in'
        ], [
            'amountReceived.min' => 'Uang diterima kurang dari total belanja.',
            'selectedTableId.required_if' => 'Pilih meja untuk pesanan dine-in.'
        ]);

        $orders = session('admin.orders', []);
        $newId = count($orders) > 0 ? max(array_column($orders, 'id')) + 1 : 1;
        $date = Carbon::now();
        
        $tableName = null;
        if ($this->orderType === 'dine_in' && $this->selectedTableId) {
            $table = collect($this->tables)->firstWhere('id', (int) $this->selectedTableId);
            if ($table) $tableName = $table['table_number'];
        }

        // Mapping format items
        $orderItems = array_map(function($item) {
            return [
                'name' => $item['name'],
                'quantity' => $item['qty'],
                'price' => $item['unit_price'],
                'toppings' => $item['toppings'],
                'spiciness' => $item['spiciness']
            ];
        }, $this->cart);

        $total = $this->cartTotal();

        $newOrder = [
            'id' => $newId,
            'order_number' => 'INV-' . $date->format('Ymd') . '-' . str_pad($newId, 4, '0', STR_PAD_LEFT),
            'customer_name' => 'Walk-in Customer',
            'customer_phone' => '-',
            'type' => $this->orderType === 'dine_in' ? 'dine_in' : 'pos',
            'table_number' => $tableName,
            'delivery_address' => null,
            'status' => 'paid', // Langsung paid karena POS tunai
            'subtotal' => $total, // Anggap sudah termasuk pajak atau tanpa pajak
            'tax' => 0,
            'total' => $total,
            'created_at' => $date->format('Y-m-d H:i:s'),
            'items' => $orderItems,
            'notes' => 'Pesanan dari POS Kasir'
        ];

        $orders[] = $newOrder;
        session(['admin.orders' => $orders]);

        // Reset
        $this->cart = [];
        $this->closeCheckoutModal();
        
        // TODO: Memicu cetak struk (biasanya buka tab print baru)
        $this->dispatch('notify', ['message' => 'Pembayaran berhasil! Order: ' . $newOrder['order_number']]);
    }

    // --- Dummy Fallbacks ---

    private function dummyMenus()
    {
        return [
            ['id' => 1, 'name' => 'Seblak Original', 'price' => 15000, 'category' => 'Makanan', 'image' => null],
            ['id' => 2, 'name' => 'Seblak Spesial', 'price' => 25000, 'category' => 'Makanan', 'image' => null],
            ['id' => 3, 'name' => 'Seblak Ceker', 'price' => 20000, 'category' => 'Makanan', 'image' => null],
            ['id' => 4, 'name' => 'Seblak Tulang', 'price' => 22000, 'category' => 'Makanan', 'image' => null],
            ['id' => 5, 'name' => 'Es Teh Manis', 'price' => 5000, 'category' => 'Minuman', 'image' => null],
            ['id' => 6, 'name' => 'Jeruk Hangat', 'price' => 6000, 'category' => 'Minuman', 'image' => null],
            ['id' => 7, 'name' => 'Tahu Goreng', 'price' => 2000, 'category' => 'Snack', 'image' => null],
            ['id' => 8, 'name' => 'Tempe Mendoan', 'price' => 2500, 'category' => 'Snack', 'image' => null],
        ];
    }

    private function dummyToppings()
    {
        return [
            ['id' => 1, 'name' => 'Sosis', 'price' => 3000],
            ['id' => 2, 'name' => 'Bakso', 'price' => 4000],
            ['id' => 3, 'name' => 'Keju', 'price' => 5000],
            ['id' => 4, 'name' => 'Kikil', 'price' => 5000],
        ];
    }

    private function dummySpiciness()
    {
        return [
            ['id' => 1, 'name' => 'Level 1 (Biasa)'],
            ['id' => 2, 'name' => 'Level 2 (Sedang)'],
            ['id' => 3, 'name' => 'Level 3 (Pedas)'],
            ['id' => 4, 'name' => 'Level 4 (Sangat Pedas)'],
        ];
    }

    private function dummyTables()
    {
        return [
            ['id' => 1, 'table_number' => 'Meja 1'],
            ['id' => 2, 'table_number' => 'Meja 2'],
            ['id' => 3, 'table_number' => 'Meja 3'],
            ['id' => 4, 'table_number' => 'Meja VIP'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.pos-screen');
    }
}
