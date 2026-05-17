<?php

namespace App\Presentation\Livewire\Admin\Menu;

use App\Application\Actions\Menu\DeleteMenuAction;
use App\Application\Actions\Menu\ListMenusAction;
use App\Domain\Menu\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ListMenu extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryFilter = null;

    protected $queryString = ['search', 'categoryFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function delete($menuId, DeleteMenuAction $action)
    {
        $action->execute($menuId);
        session()->flash('message', 'Menu berhasil dihapus!');
        session()->flash('message_type', 'success');
        
        // Memaksa Livewire untuk merender ulang halaman
        $this->resetPage();
    }

    public function render(ListMenusAction $action)
    {
        return view('livewire.admin.menu.list-menu', [
            'menus' => $action->execute($this->search, $this->categoryFilter),
            'categories' => Category::all(),
        ])->layout('layouts.admin');
    }
}
