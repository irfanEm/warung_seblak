<?php
namespace App\Application\Actions\Menu;

use App\Domain\Menu\Models\Menu;

class ListMenusAction
{
    public function execute(string $search = '', ?int $categoryId = null)
    {
        return Menu::with(['category', 'toppings'])
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->latest()
            ->paginate(12);
    }
}
