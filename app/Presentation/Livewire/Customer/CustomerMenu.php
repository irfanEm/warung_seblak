<?php

declare(strict_types=1);

namespace App\Presentation\Livewire\Customer;

use App\Application\Actions\Menu\ListMenusAction;
use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use App\Services\CartService;
use Livewire\Component;

class CustomerMenu extends Component
{
    public ?int $selectedCategory = null;
    public array $cart = [];
    public bool $showModal = false;
    
    // Modal State
    public ?Menu $selectedMenu = null;
    public array $selectedToppings = [];
    public ?int $selectedSpiciness = null;
    public int $quantity = 1;

    public function mount()
    {
        $this->cart = session('cart', []);
    }

    public function filterByCategory(?int $categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function openAddModal($menuId)
    {
        $this->selectedMenu = Menu::with(['toppings', 'spicinessLevels'])->find($menuId);
        $this->selectedToppings = [];
        $this->selectedSpiciness = null;
        $this->quantity = 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function addToCart()
    {
        if (!$this->selectedMenu) {
            return;
        }

        // Stock check
        if ($this->selectedMenu->stock_quantity !== null && $this->quantity > $this->selectedMenu->stock_quantity) {
            session()->flash('error', "Stok tidak mencukupi. Tersedia: {$this->selectedMenu->stock_quantity}.");
            return;
        }

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
            'subtotal' => 0,
        ];

        $cartService = app(CartService::class);
        $item['subtotal'] = $cartService->calculateSubtotal($item);
        $this->cart = $cartService->addItem($this->cart, $item);

        session(['cart' => $this->cart]);

        $this->dispatch('cartUpdated');
        $this->closeModal();
    }

    public function render(ListMenusAction $listMenus)
    {
        $categories = Category::all();
        $menus = $listMenus->execute('', $this->selectedCategory);

        return view('livewire.customer.customer-menu', compact('categories', 'menus'))
            ->layout('layouts.customer');
    }
}
