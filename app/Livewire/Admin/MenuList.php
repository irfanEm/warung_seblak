<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

class MenuList extends Component
{
    public array $menus = [];
    public array $categories = [];
    public array $toppingsList = [];
    public array $spicinessLevels = [];

    // Filters
    public ?int $selectedCategory = null;
    public string $search = '';

    // Form states
    public bool $showForm = false;
    public ?int $editingMenuId = null;
    public array $form = [
        'name' => '',
        'category_id' => '',
        'description' => '',
        'price' => '',
        'image' => '',
        'is_available' => true,
        'selectedToppings' => [],
        'selectedSpicinessLevelId' => ''
    ];

    /**
     * Inisialisasi data dari session (dengan fallback data dummy) agar CRUD terintegrasi secara dinamis.
     * TODO: Ganti dengan pemanggilan database Eloquent model Menu::all()
     */
    public function mount(): void
    {
        // 1. Ambil Referensi Kategori
        if (!session()->has('admin.categories')) {
            session()->put('admin.categories', [
                ['id' => 1, 'name' => 'Makanan', 'slug' => 'makanan', 'menu_count' => 4],
                ['id' => 2, 'name' => 'Minuman', 'slug' => 'minuman', 'menu_count' => 2],
                ['id' => 3, 'name' => 'Snack', 'slug' => 'snack', 'menu_count' => 2],
                ['id' => 4, 'name' => 'Cemilan', 'slug' => 'cemilan', 'menu_count' => 0],
            ]);
        }
        $this->categories = session()->get('admin.categories');

        // 2. Ambil Referensi Topping
        if (!session()->has('admin.toppings')) {
            session()->put('admin.toppings', [
                ['id' => 1, 'name' => 'Telur', 'price' => 3000],
                ['id' => 2, 'name' => 'Keju', 'price' => 2000],
                ['id' => 3, 'name' => 'Sosis', 'price' => 5000],
                ['id' => 4, 'name' => 'Bakso', 'price' => 4000],
                ['id' => 5, 'name' => 'Mie', 'price' => 3000],
            ]);
        }
        $this->toppingsList = session()->get('admin.toppings');

        // 3. Ambil Referensi Level Pedas
        if (!session()->has('admin.spiciness')) {
            session()->put('admin.spiciness', [
                ['id' => 1, 'name' => 'Tidak Pedas', 'level' => 0],
                ['id' => 2, 'name' => 'Level 1 (Sedikit Pedas)', 'level' => 1],
                ['id' => 3, 'name' => 'Level 2 (Pedas Sedang)', 'level' => 2],
                ['id' => 4, 'name' => 'Level 3 (Pedas Mantap)', 'level' => 3],
                ['id' => 5, 'name' => 'Level 4 (Sangat Pedas)', 'level' => 4],
                ['id' => 6, 'name' => 'Level 5 (Pedas Gila)', 'level' => 5],
            ]);
        }
        $this->spicinessLevels = session()->get('admin.spiciness');
        usort($this->spicinessLevels, fn($a, $b) => $a['level'] <=> $b['level']);

        // 4. Ambil/Inisialisasi Data Menu (Minimal 8 Item default)
        if (!session()->has('admin.menus')) {
            session()->put('admin.menus', [
                [
                    'id' => 1,
                    'category_id' => 1,
                    'name' => 'Seblak Original Classic',
                    'price' => 15000,
                    'description' => 'Seblak resep legendaris dengan kuah kencur pekat, telur orak-arik, kerupuk kuning basah, cuanki lidah, dan makaroni gurih.',
                    'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [1],
                    'spiciness_level_id' => 2 // Level 1
                ],
                [
                    'id' => 2,
                    'category_id' => 1,
                    'name' => 'Seblak Seafood Premium',
                    'price' => 28000,
                    'description' => 'Sensasi laut dalam seblak: udang segar, cumi kenyal, crab stick premium, fish cake, sosis, bakso, dan sayuran segar berkuah pedas.',
                    'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [1, 3, 4],
                    'spiciness_level_id' => 3 // Level 2
                ],
                [
                    'id' => 3,
                    'category_id' => 1,
                    'name' => 'Seblak Komplit Ceker',
                    'price' => 22000,
                    'description' => 'Seblak kuah pedas gurih dengan ceker empuk, telur dadakan, sosis jumbo, bakso sapi, kerupuk oranye basah, dan makaroni kenyal.',
                    'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [1, 4],
                    'spiciness_level_id' => 4 // Level 3
                ],
                [
                    'id' => 4,
                    'category_id' => 1,
                    'name' => 'Seblak Tulang Rangu',
                    'price' => 24000,
                    'description' => 'Seblak bertekstur kriuk nikmat dengan topping tulang muda sapi rangu yang gurih, bakso mini, siomay kering, dan kuah rawit merah super pedas.',
                    'image' => 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [1, 5],
                    'spiciness_level_id' => 5 // Level 4
                ],
                [
                    'id' => 5,
                    'category_id' => 2,
                    'name' => 'Es Teh Manis Jumbo',
                    'price' => 5000,
                    'description' => 'Seduhan daun teh melati pilihan segar yang disajikan dingin dengan es batu kristal dalam gelas ukuran jumbo.',
                    'image' => 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [],
                    'spiciness_level_id' => 1 // Tidak Pedas
                ],
                [
                    'id' => 6,
                    'category_id' => 2,
                    'name' => 'Es Jeruk Peras Segar',
                    'price' => 8000,
                    'description' => 'Perasan murni buah jeruk nipis segar dikombinasikan dengan larutan gula tebu asli dan es batu dingin pelepas dahaga pedas.',
                    'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [],
                    'spiciness_level_id' => 1 // Tidak Pedas
                ],
                [
                    'id' => 7,
                    'category_id' => 3,
                    'name' => 'Basreng Jeruk Purut',
                    'price' => 12000,
                    'description' => 'Bakso goreng renyah kriuk yang digoreng dadakan, ditaburi bumbu cabai kering pedas dan aroma daun jeruk purut yang harum.',
                    'image' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [],
                    'spiciness_level_id' => 2 // Level 1
                ],
                [
                    'id' => 8,
                    'category_id' => 3,
                    'name' => 'Cireng Bumbu Rujak',
                    'price' => 10000,
                    'description' => 'Adonan aci goreng hangat yang garing di luar dan kenyal di dalam, disajikan dengan cocolan saus bumbu rujak pedas manis asam.',
                    'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=60',
                    'is_available' => true,
                    'toppings' => [],
                    'spiciness_level_id' => 1 // Tidak Pedas
                ]
            ]);
        }
        $this->menus = session()->get('admin.menus');
    }

    /**
     * Membuka form modal tambah menu baru.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    /**
     * Membuka form modal untuk mengedit data menu yang ada.
     */
    public function edit(int $id): void
    {
        $this->resetForm();
        $this->editingMenuId = $id;

        foreach ($this->menus as $menu) {
            if ($menu['id'] === $id) {
                $this->form = [
                    'name' => $menu['name'],
                    'category_id' => $menu['category_id'],
                    'description' => $menu['description'] ?? '',
                    'price' => $menu['price'],
                    'image' => $menu['image'] ?? '',
                    'is_available' => (bool) $menu['is_available'],
                    'selectedToppings' => $menu['toppings'] ?? [],
                    'selectedSpicinessLevelId' => $menu['spiciness_level_id'] ?? ''
                ];
                break;
            }
        }

        $this->showForm = true;
    }

    /**
     * Menyimpan data menu (tambah atau edit).
     * TODO: Ganti dengan model Menu::updateOrCreate() ke database
     */
    public function save(): void
    {
        $this->validate([
            'form.name' => 'required|string|min:3|max:100',
            'form.category_id' => 'required',
            'form.price' => 'required|integer|min:0',
            'form.description' => 'nullable|string|max:500',
            'form.image' => 'nullable|url',
            'form.selectedSpicinessLevelId' => 'required',
        ], [
            'form.name.required' => 'Nama menu wajib diisi.',
            'form.name.min' => 'Nama menu minimal 3 karakter.',
            'form.category_id.required' => 'Pilih kategori terlebih dahulu.',
            'form.price.required' => 'Harga wajib diisi.',
            'form.price.integer' => 'Harga harus berupa angka bulat positif.',
            'form.price.min' => 'Harga tidak boleh kurang dari 0.',
            'form.image.url' => 'Format link URL gambar tidak valid.',
            'form.selectedSpicinessLevelId.required' => 'Pilih tingkat kepedasan default menu.',
        ]);

        // Default image jika kosong
        $imageUrl = !empty($this->form['image']) 
            ? $this->form['image'] 
            : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=60';

        // Konversi tipe data agar konsisten
        $menuData = [
            'name' => $this->form['name'],
            'category_id' => (int) $this->form['category_id'],
            'price' => (int) $this->form['price'],
            'description' => $this->form['description'],
            'image' => $imageUrl,
            'is_available' => (bool) $this->form['is_available'],
            'toppings' => array_map('intval', $this->form['selectedToppings']),
            'spiciness_level_id' => (int) $this->form['selectedSpicinessLevelId']
        ];

        if ($this->editingMenuId) {
            // Update Menu
            foreach ($this->menus as &$menu) {
                if ($menu['id'] === $this->editingMenuId) {
                    $menu = array_merge($menu, $menuData);
                    break;
                }
            }
        } else {
            // Create Menu
            $newId = count($this->menus) > 0 ? max(array_column($this->menus, 'id')) + 1 : 1;
            $menuData['id'] = $newId;
            $this->menus[] = $menuData;
        }

        $this->updateCategoryCount();
        session()->put('admin.menus', $this->menus);
        $this->showForm = false;
        $this->resetForm();
    }

    /**
     * Menghapus menu dari data dummy session.
     * TODO: Ganti dengan Menu::destroy()
     */
    public function delete(int $id): void
    {
        $this->menus = array_filter($this->menus, fn($m) => $m['id'] !== $id);
        $this->menus = array_values($this->menus); // Reset index
        $this->updateCategoryCount();
        session()->put('admin.menus', $this->menus);
    }

    /**
     * Sinkronisasi jumlah menu per kategori (menu_count) dalam session kategori.
     */
    protected function updateCategoryCount(): void
    {
        foreach ($this->categories as &$cat) {
            $cat['menu_count'] = collect($this->menus)->filter(fn($m) => (int)$m['category_id'] === (int)$cat['id'])->count();
        }
        session()->put('admin.categories', $this->categories);
    }

    /**
     * Mereset form input ke nilai default.
     */
    protected function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'category_id' => '',
            'description' => '',
            'price' => '',
            'image' => '',
            'is_available' => true,
            'selectedToppings' => [],
            'selectedSpicinessLevelId' => ''
        ];
        $this->editingMenuId = null;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        // 1. Terapkan Pencarian Nama
        $filtered = $this->menus;
        if (!empty($this->search)) {
            $query = strtolower($this->search);
            $filtered = array_filter($filtered, fn($m) => str_contains(strtolower($m['name']), $query));
        }

        // 2. Terapkan Filter Kategori
        if ($this->selectedCategory) {
            $filtered = array_filter($filtered, fn($m) => (int)$m['category_id'] === (int)$this->selectedCategory);
        }

        // Reset index agar bisa di-loop blade dengan aman
        $filtered = array_values($filtered);

        return view('livewire.admin.menu-list', [
            'filteredMenus' => $filtered
        ]);
    }
}
