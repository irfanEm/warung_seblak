<?php
namespace App\Application\Actions\Menu;

use App\Domain\Menu\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UpdateMenuAction
{
    public function execute(Menu $menu, array $data, $imageFile, array $toppingIds = [], array $spicinessIds = []): Menu
    {
        return DB::transaction(function () use ($menu, $data, $imageFile, $toppingIds, $spicinessIds) {
            
            if ($imageFile) {
                // Hapus gambar lama jika ada
                if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                    Storage::disk('public')->delete($menu->image);
                }

                $manager = new ImageManager(new Driver());
                $image = $manager->read($imageFile);
                $image->resize(800, 600);
                
                // Gunakan slug baru jika nama berubah, atau slug lama
                $slug = Str::slug($data['name']) . '-' . time();
                $filename = 'menus/' . $slug . '.jpg';
                Storage::disk('public')->put($filename, (string) $image->toJpeg());
                $data['image'] = $filename;
                $data['slug'] = $slug;
            } else {
                // Pastikan slug up to date jika nama diubah
                if ($menu->name !== $data['name']) {
                     $data['slug'] = Str::slug($data['name']) . '-' . time();
                }
            }

            $menu->update($data);
            $menu->toppings()->sync($toppingIds);
            $menu->spicinessLevels()->sync($spicinessIds);

            return $menu;
        });
    }
}
