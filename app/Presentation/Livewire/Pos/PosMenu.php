<?php

declare(strict_types=1);

namespace App\Presentation\Livewire\Pos;

use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * POS Menu Grid + Customization Modal.
 * Dispatches `pos:add-item` when user confirms an item addition.
 */
class PosMenu extends Component
{
    // Props from parent PosScreen
    public string $activeTab = 'menu';
    public ?int $lastOrderId = null;

    public ?int $selectedCategory = null;
    public bool $showModal = false;
    public ?Menu $selectedMenu = null;
    public array $selectedToppings = [];
    public ?int $selectedSpiciness = null;
    public int $quantity = 1;

    public function selectCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
    }

    public function openAddModal(int $menuId): void
    {
        $menu = Menu::with(['toppings', 'spicinessLevels'])->findOrFail($menuId);

        // Stock check
        if ($menu->stock_quantity !== null && $menu->stock_quantity <= 0) {
            session()->flash('error', "Stok {$menu->name} habis.");
            return;
        }

        // Shortcut: no customizations needed
        if ($menu->toppings->isEmpty() && $menu->spicinessLevels->isEmpty()) {
            $item = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => 1,
                'toppings' => [],
                'spiciness_level' => null,
                'subtotal' => $menu->price,
            ];

            $this->dispatch('pos:add-item', item: $item);
            session()->flash('success', "{$menu->name} berhasil ditambahkan ke keranjang.");
            return;
        }

        $this->selectedMenu = $menu;
        $this->selectedToppings = [];
        $this->selectedSpiciness = null;
        $this->quantity = 1;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedMenu = null;
    }

    public function addToCart(): void
    {
        if (!$this->selectedMenu) {
            return;
        }

        // Stock check
        if ($this->selectedMenu->stock_quantity !== null && $this->quantity > $this->selectedMenu->stock_quantity) {
            $this->addError('quantity', "Stok tidak mencukupi. Tersedia: {$this->selectedMenu->stock_quantity}.");
            return;
        }

        $cartService = app(CartService::class);
        $toppings = Topping::whereIn('id', $this->selectedToppings)->get();

        $spiciness = null;
        if ($this->selectedSpiciness) {
            $spicinessModel = SpicinessLevel::find($this->selectedSpiciness);
            if ($spicinessModel) {
                $spiciness = ['id' => $spicinessModel->id, 'name' => $spicinessModel->name];
            }
        }

        $item = [
            'menu_id' => $this->selectedMenu->id,
            'name' => $this->selectedMenu->name,
            'price' => $this->selectedMenu->price,
            'quantity' => $this->quantity,
            'toppings' => $toppings->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'price' => $t->price])->toArray(),
            'spiciness_level' => $spiciness,
            'subtotal' => 0, // will be computed by CartService
        ];
        $item['subtotal'] = $cartService->calculateSubtotal($item);

        $this->dispatch('pos:add-item', item: $item);
        $this->closeModal();
        session()->flash('success', "{$item['name']} berhasil ditambahkan ke keranjang.");
    }

    public function render()
    {
        $categories = Category::orderBy('sort', 'asc')->get();
        $menusQuery = Menu::where('is_available', true)->with(['category', 'toppings', 'spicinessLevels']);

        if ($this->selectedCategory) {
            $menusQuery->where('category_id', $this->selectedCategory);
        }

        return view('livewire.pos.pos-menu', [
            'categories' => $categories,
            'menus' => $menusQuery->get(),
        ]);
    }
}
