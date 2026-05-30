<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

class ToppingList extends Component
{
    public array $toppings = [];
    public bool $showForm = false;
    public ?int $editingToppingId = null;

    // Form inputs
    public string $name = '';
    public int|string $price = '';

    /**
     * Inisialisasi data dummy topping saat pertama kali dimuat.
     * TODO: Ganti dengan query Eloquent model Topping::all() di masa mendatang.
     */
    public function mount(): void
    {
        // Ambil dari session agar data bertahan selama session aktif untuk keperluan simulasi
        if (!session()->has('admin.toppings')) {
            session()->put('admin.toppings', [
                ['id' => 1, 'name' => 'Telur', 'price' => 3000],
                ['id' => 2, 'name' => 'Keju', 'price' => 2000],
                ['id' => 3, 'name' => 'Sosis', 'price' => 5000],
                ['id' => 4, 'name' => 'Bakso', 'price' => 4000],
                ['id' => 5, 'name' => 'Mie', 'price' => 3000],
            ]);
        }
        $this->toppings = session()->get('admin.toppings');
    }

    /**
     * Membuka modal form tambah topping baru.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    /**
     * Membuka modal form untuk mengedit topping.
     */
    public function edit(int $id): void
    {
        $this->resetForm();
        $this->editingToppingId = $id;

        foreach ($this->toppings as $topping) {
            if ($topping['id'] === $id) {
                $this->name = $topping['name'];
                $this->price = $topping['price'];
                break;
            }
        }

        $this->showForm = true;
    }

    /**
     * Menyimpan data topping (tambah atau edit).
     * TODO: Ganti dengan penyimpanan database model Topping::updateOrCreate()
     */
    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:50',
            'price' => 'required|integer|min:0',
        ], [
            'name.required' => 'Nama topping wajib diisi.',
            'name.min' => 'Nama topping minimal 2 karakter.',
            'price.required' => 'Harga topping wajib diisi.',
            'price.integer' => 'Harga harus berupa angka bulat positif.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
        ]);

        if ($this->editingToppingId) {
            // Proses Update
            foreach ($this->toppings as &$topping) {
                if ($topping['id'] === $this->editingToppingId) {
                    $topping['name'] = $this->name;
                    $topping['price'] = (int) $this->price;
                    break;
                }
            }
        } else {
            // Proses Create
            $newId = count($this->toppings) > 0 ? max(array_column($this->toppings, 'id')) + 1 : 1;
            $this->toppings[] = [
                'id' => $newId,
                'name' => $this->name,
                'price' => (int) $this->price,
            ];
        }

        session()->put('admin.toppings', $this->toppings);
        $this->showForm = false;
        $this->resetForm();
    }

    /**
     * Menghapus topping dari array lokal/session.
     * TODO: Ganti dengan Topping::destroy()
     */
    public function delete(int $id): void
    {
        $this->toppings = array_filter($this->toppings, fn($t) => $t['id'] !== $id);
        $this->toppings = array_values($this->toppings); // Reset indeks array agar terurut
        session()->put('admin.toppings', $this->toppings);
    }

    /**
     * Mereset isian formulir ke kondisi kosong.
     */
    protected function resetForm(): void
    {
        $this->name = '';
        $this->price = '';
        $this->editingToppingId = null;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.topping-list');
    }
}
