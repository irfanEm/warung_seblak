<?php
namespace App\Application\Actions\Menu;

use App\Domain\Menu\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CreateMenuAction
{
    public function execute(array $data, $imageFile, array $toppingIds = [], array $spicinessIds = []): Menu
    {
        return DB::transaction(function () use ($data, $imageFile, $toppingIds, $spicinessIds) {
            $data['slug'] = Str::slug($data['name']) . '-' . time();
            
            if ($imageFile) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($imageFile);
                $image->resize(800, 600); // Resize untuk optimasi
                
                $filename = 'menus/' . $data['slug'] . '.jpg';
                Storage::disk('public')->put($filename, (string) $image->toJpeg());
                $data['image'] = $filename;
            }

            $menu = Menu::create($data);
            $menu->toppings()->sync($toppingIds);
            $menu->spicinessLevels()->sync($spicinessIds);

            return $menu;
        });
    }
}
