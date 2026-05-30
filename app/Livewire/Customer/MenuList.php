<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class MenuList extends Component
{
    public array $menus = [];
    public array $categories = [];
    public ?string $selectedCategory = null;

    /**
     * Inisialisasi data menu dan kategori dummy (menunggu integrasi database).
     */
    public function mount(): void
    {
        $this->categories = ['Makanan', 'Minuman', 'Snack'];
        
        $this->menus = [
            [
                'id' => 1,
                'name' => 'Seblak Komplit Ceker',
                'category' => 'Makanan',
                'price' => 22000,
                'description' => 'Seblak kuah pedas gurih dengan ceker empuk, telur dadakan, sosis jumbo, bakso sapi, kerupuk oranye basah, dan makaroni kenyal.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 2,
                'name' => 'Seblak Original Classic',
                'category' => 'Makanan',
                'price' => 15000,
                'description' => 'Seblak resep legendaris dengan kuah kencur pekat, telur orak-arik, kerupuk kuning basah, cuanki lidah, dan makaroni gurih.',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 3,
                'name' => 'Seblak Seafood Premium',
                'category' => 'Makanan',
                'price' => 28000,
                'description' => 'Sensasi laut dalam seblak: udang segar, cumi kenyal, crab stick premium, fish cake, sosis, bakso, dan sayuran segar berkuah pedas.',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 4,
                'name' => 'Seblak Tulang Rangu',
                'category' => 'Makanan',
                'price' => 24000,
                'description' => 'Seblak bertekstur kriuk nikmat dengan topping tulang muda sapi rangu yang gurih, bakso mini, siomay kering, dan kuah rawit merah super pedas.',
                'image' => 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 5,
                'name' => 'Es Teh Manis Jumbo',
                'category' => 'Minuman',
                'price' => 5000,
                'description' => 'Seduhan daun teh melati pilihan segar yang disajikan dingin dengan es batu kristal dalam gelas ukuran jumbo.',
                'image' => 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 6,
                'name' => 'Es Jeruk Peras Segar',
                'category' => 'Minuman',
                'price' => 8000,
                'description' => 'Perasan murni buah jeruk nipis segar dikombinasikan dengan larutan gula tebu asli dan es batu dingin pelepas dahaga pedas.',
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 7,
                'name' => 'Basreng Jeruk Purut',
                'category' => 'Snack',
                'price' => 12000,
                'description' => 'Bakso goreng renyah kriuk yang digoreng dadakan, ditaburi bumbu cabai kering pedas dan aroma daun jeruk purut yang harum.',
                'image' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&auto=format&fit=crop&q=60'
            ],
            [
                'id' => 8,
                'name' => 'Cireng Bumbu Rujak',
                'category' => 'Snack',
                'price' => 10000,
                'description' => 'Adonan aci goreng hangat yang garing di luar dan kenyal di dalam, disajikan dengan cocolan saus bumbu rujak pedas manis asam.',
                'image' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&auto=format&fit=crop&q=60'
            ]
        ];
    }

    /**
     * Memfilter menu berdasarkan kategori.
     */
    public function selectCategory(?string $category = null): void
    {
        $this->selectedCategory = $category;
    }

    public function render()
    {
        // Filter menu berdasarkan kategori terpilih
        $filteredMenus = $this->selectedCategory
            ? array_filter($this->menus, fn($menu) => $menu['category'] === $this->selectedCategory)
            : $this->menus;

        return view('livewire.customer.menu-list', [
            'filteredMenus' => $filteredMenus
        ]);
    }
}
