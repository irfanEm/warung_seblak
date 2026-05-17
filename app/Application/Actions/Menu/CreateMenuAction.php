<?php

namespace App\Application\Actions\Menu;

use App\Application\DTOs\MenuData;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Repositories\MenuRepositoryInterface;
use Illuminate\Support\Str;

class CreateMenuAction
{
    public function __construct(private readonly MenuRepositoryInterface $menuRepo) {}

    public function execute(MenuData $data): Menu
    {
        $slug = Str::slug($data->name);
        
        // Aturan Bisnis: Pastikan slug unik
        $originalSlug = $slug;
        $counter = 1;
        while (!$this->menuRepo->isSlugUnique($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $payload = array_merge($data->toArray(), ['slug' => $slug]);

        return $this->menuRepo->store($payload);
    }
}
