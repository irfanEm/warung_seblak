<?php

namespace App\Presentation\Livewire\Admin\Menu;

use App\Domain\Menu\Repositories\MenuRepositoryInterface;
use Livewire\Component;

class ListMenu extends Component
{
    public function render(MenuRepositoryInterface $menuRepo)
    {
        return view('livewire.admin.menu.list-menu', [
            'menus' => $menuRepo->getAllByOutlet(1) // Hardcode outlet_id 1
        ]);
    }
}
