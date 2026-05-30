<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

class CategoryList extends Component
{
    public array $categories = [];
    public bool $showForm = false;
    public ?int $editingCategoryId = null;

    // Form inputs
    public string $name = '';
    public string $slug = '';

    /**
     * Inisialisasi data dummy kategori saat pertama kali dimuat.
     * TODO: Ganti dengan query Eloquent model Category::all() di masa mendatang.
     */
    public function mount(): void
    {
        // Ambil dari session agar data bertahan selama session aktif untuk keperluan simulasi
        if (!session()->has('admin.categories')) {
            session()->put('admin.categories', [
                ['id' => 1, 'name' => 'Makanan', 'slug' => 'makanan', 'menu_count' => 4],
                ['id' => 2, 'name' => 'Minuman', 'slug' => 'minuman', 'menu_count' => 2],
                ['id' => 3, 'name' => 'Snack', 'slug' => 'snack', 'menu_count' => 2],
                ['id' => 4, 'name' => 'Cemilan', 'slug' => 'cemilan', 'menu_count' => 0],
            ]);
        }
        $this->categories = session()->get('admin.categories');
    }

    /**
     * Mengamati perubahan nama untuk otomatis membuat slug yang ramah-URL.
     */
    public function updatedName(string $value): void
    {
        $this->slug = str()->slug($value);
    }

    /**
     * Membuka modal form tambah kategori baru.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    /**
     * Membuka modal form untuk mengedit kategori.
     */
    public function edit(int $id): void
    {
        $this->resetForm();
        $this->editingCategoryId = $id;

        foreach ($this->categories as $category) {
            if ($category['id'] === $id) {
                $this->name = $category['name'];
                $this->slug = $category['slug'];
                break;
            }
        }

        $this->showForm = true;
    }

    /**
     * Menyimpan data kategori (tambah atau edit).
     */
    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|min:3|max:50',
            'slug' => 'required|string|min:3|max:50',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.min' => 'Nama kategori minimal 3 karakter.',
            'slug.required' => 'Slug wajib diisi.',
        ]);

        // Cek keunikan slug di array lokal
        foreach ($this->categories as $category) {
            if ($category['slug'] === $this->slug && $category['id'] !== $this->editingCategoryId) {
                $this->addError('slug', 'Slug ini sudah digunakan oleh kategori lain.');
                return;
            }
        }

        if ($this->editingCategoryId) {
            // Proses Update
            foreach ($this->categories as &$category) {
                if ($category['id'] === $this->editingCategoryId) {
                    $category['name'] = $this->name;
                    $category['slug'] = $this->slug;
                    break;
                }
            }
        } else {
            // Proses Create
            $newId = count($this->categories) > 0 ? max(array_column($this->categories, 'id')) + 1 : 1;
            $this->categories[] = [
                'id' => $newId,
                'name' => $this->name,
                'slug' => $this->slug,
                'menu_count' => 0
            ];
        }

        session()->put('admin.categories', $this->categories);
        $this->showForm = false;
        $this->resetForm();
    }

    /**
     * Menghapus kategori dari array lokal/session.
     */
    public function delete(int $id): void
    {
        $this->categories = array_filter($this->categories, fn($c) => $c['id'] !== $id);
        $this->categories = array_values($this->categories); // Reset indeks array agar terurut
        session()->put('admin.categories', $this->categories);
    }

    /**
     * Mereset isian formulir ke kondisi kosong.
     */
    protected function resetForm(): void
    {
        $this->name = '';
        $this->slug = '';
        $this->editingCategoryId = null;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.category-list');
    }
}
