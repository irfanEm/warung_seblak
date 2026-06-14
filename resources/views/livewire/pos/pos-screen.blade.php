<div class="h-full flex flex-col bg-gray-50">
    
    <!-- Top Mobile Tab Bar (Only visible on small screens) -->
    <div class="md:hidden flex shrink-0 border-b border-gray-200 bg-white">
        <button 
            wire:click="$set('activeTab', 'menu')" 
            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition {{ $activeTab === 'menu' ? 'border-amber-600 text-amber-600 bg-amber-50/20' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
        >
            🍔 Daftar Menu
        </button>
        <button 
            wire:click="$set('activeTab', 'cart')" 
            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition relative {{ $activeTab === 'cart' ? 'border-amber-600 text-amber-600 bg-amber-50/20' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
        >
            🛒 Keranjang
        </button>
    </div>

    <!-- Main Workspace Grid -->
    <div class="flex-1 grid grid-cols-1 md:grid-cols-10 overflow-hidden">
        
        <!-- Left Panel: Menu Grid (PosMenu sub-component) -->
        <livewire:pos.pos-menu 
            :active-tab="$activeTab" 
            :last-order-id="$lastOrderId" 
        />

        <!-- Right Panel: Cart + Payment (PosCart sub-component) -->
        <livewire:pos.pos-cart 
            :active-tab="$activeTab" 
        />
    </div>

</div>
