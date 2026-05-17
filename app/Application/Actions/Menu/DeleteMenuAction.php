<?php
namespace App\Application\Actions\Menu;

use App\Domain\Menu\Models\Menu;
use Illuminate\Support\Facades\Storage;

class DeleteMenuAction
{
    public function execute(int $menuId): void
    {
        $menu = Menu::findOrFail($menuId);
        
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();
    }
}
