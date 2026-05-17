<?php

namespace App\Domain\Menu\Repositories;

use App\Domain\Menu\Models\Menu;
use Illuminate\Support\Collection;

interface MenuRepositoryInterface
{
    public function getAllByOutlet(int $outletId): Collection;
    public function findById(int $id): ?Menu;
    public function store(array $data): Menu;
    public function isSlugUnique(string $slug): bool;
}
