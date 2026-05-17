<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Repositories\MenuRepositoryInterface;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function getAllByOutlet(int $outletId): Collection
    {
        return Menu::with('category')->where('outlet_id', $outletId)->latest()->get();
    }

    public function findById(int $id): ?Menu
    {
        return Menu::find($id);
    }

    public function store(array $data): Menu
    {
        return Menu::create($data);
    }

    public function isSlugUnique(string $slug): bool
    {
        return !Menu::where('slug', $slug)->exists();
    }
}
