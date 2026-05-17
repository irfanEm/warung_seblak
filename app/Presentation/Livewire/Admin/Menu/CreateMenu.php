<?php

namespace App\Presentation\Livewire\Admin\Menu;

use App\Application\Actions\Menu\CreateMenuAction;
use App\Application\DTOs\MenuData;
use App\Domain\Menu\Models\Category;
use Livewire\Component;

class CreateMenu extends Component
{
    public string $name = '';
    public int $category_id;
    public string $description = '';
    public int $price;
    public bool $is_available = true;
    public ?int $stock_quantity = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:menu_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
        ];
    }

    public function save(CreateMenuAction $action)
    {
        $this->validate();

        $dto = new MenuData(
            outlet_id: 1, // Hardcode untuk keperluan demo/MVP
            category_id: $this->category_id,
            name: $this->name,
            description: $this->description,
            price: $this->price,
            is_available: $this->is_available,
            stock_quantity: $this->stock_quantity === '' ? null : $this->stock_quantity,
        );

        $action->execute($dto);

        session()->flash('success', 'Menu berhasil ditambahkan!');
        return redirect()->route('admin.menu.index'); 
    }

    public function render()
    {
        return view('livewire.admin.menu.create-menu', [
            'categories' => Category::all()
        ]);
    }
}
