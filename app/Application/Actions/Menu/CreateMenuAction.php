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
                $filename = 'menus/' . $data['slug'] . '.jpg';
                
                // Proses gambar menggunakan v2 API
                $img = \Intervention\Image\ImageManagerStatic::make($imageFile->getRealPath());
                
                // Resize maksimal lebar 800px, aspect ratio dijaga, jangan upscale jika gambar aslinya kecil
                $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // Encode ke JPG dan simpan ke Storage Public
                Storage::disk('public')->put($filename, (string) $img->encode('jpg', 80));
                
                $data['image'] = $filename;
            }

            $menu = Menu::create($data);
            $menu->toppings()->sync($toppingIds);
            $menu->spicinessLevels()->sync($spicinessIds);

            return $menu;
        });
    }
}
