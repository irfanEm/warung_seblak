<?php

namespace App\Presentation\Livewire\Admin\Menu;

use App\Application\Actions\Menu\CreateMenuAction;
use App\Application\Actions\Menu\UpdateMenuAction;
use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use Livewire\Component;
use Livewire\WithFileUploads;

class MenuForm extends Component
{
    use WithFileUploads;

    public ?int $menuId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public $price;
    public ?int $category_id = null;
    public array $toppings = [];
    public array $spicinessLevels = [];
    public $image; // Untuk file upload sementara
    public ?string $existingImage = null; // Path gambar lama
    public ?int $stock_quantity = null;
    public bool $is_available = true;

    public function mount(?int $menuId = null)
    {
        if ($menuId) {
            $menu = Menu::with(['toppings', 'spicinessLevels'])->findOrFail($menuId);
            $this->menuId = $menu->id;
            $this->name = $menu->name;
            $this->slug = $menu->slug;
            $this->description = $menu->description ?? '';
            $this->price = $menu->price;
            $this->category_id = $menu->category_id;
            $this->existingImage = $menu->image;
            $this->stock_quantity = $menu->stock_quantity;
            $this->is_available = $menu->is_available;
            
            $this->toppings = $menu->toppings->pluck('id')->toArray();
            $this->spicinessLevels = $menu->spicinessLevels->pluck('id')->toArray();
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:menu_categories,id',
            'image' => 'nullable|image|max:2048', // Maks 2MB
            'stock_quantity' => 'nullable|integer|min:0',
            'is_available' => 'boolean',
            'toppings' => 'array',
            'spicinessLevels' => 'array',
        ];
    }

    public function save(CreateMenuAction $createAction, UpdateMenuAction $updateAction)
    {
        $this->validate();

        $data = [
            'outlet_id' => auth()->user()->outlet_id ?? 1, // Parameterisasi outlet
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_available' => $this->is_available,
            'stock_quantity' => $this->stock_quantity === '' ? null : $this->stock_quantity,
        ];

        if ($this->menuId) {
            $menu = Menu::findOrFail($this->menuId);
            $updateAction->execute($menu, $data, $this->image, $this->toppings, $this->spicinessLevels);
            session()->flash('message', 'Menu berhasil diupdate!');
        } else {
            $createAction->execute($data, $this->image, $this->toppings, $this->spicinessLevels);
            session()->flash('message', 'Menu berhasil dibuat!');
        }

        session()->flash('message_type', 'success');
        return redirect()->route('admin.menu.index');
    }

    public function render()
    {
        return view('livewire.admin.menu.menu-form', [
            'categories' => Category::all(),
            'allToppings' => Topping::all(),
            'allSpicinessLevels' => SpicinessLevel::all(),
        ])->layout('layouts.admin');
    }
}
