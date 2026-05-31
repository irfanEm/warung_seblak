<div x-data="{ show: false }" 
     x-on:show-detail-modal.window="show = true" 
     x-on:hide-detail-modal.window="show = false"
     x-show="show"
     class="fixed inset-0 z-50 overflow-hidden flex items-end justify-center"
     style="display: none;">
     
    <!-- Backdrop with premium blur (Z-index 50 wrapper guarantees on top of bottom-nav) -->
    <div x-show="show"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="show = false"
         class="absolute inset-0 bg-gray-900/60 backdrop-blur-xs"></div>

    <!-- Modal Content (Bottom Sheet Style on Mobile) -->
    <div x-show="show"
         x-transition:enter="transition-transform ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition-transform ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="relative bg-white w-full max-w-md rounded-t-3xl shadow-2xl z-10 flex flex-col max-h-[85vh] overflow-hidden">
         
         <!-- Grab bar -->
         <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto my-3 shrink-0 cursor-pointer" @click="show = false"></div>

         @if($menu)
             <!-- Scrollable detail body -->
             <div class="flex-grow overflow-y-auto px-6 pb-6 scrollbar-none">
                 <!-- Image and Header -->
                 <div class="relative w-full aspect-video rounded-2xl overflow-hidden mb-4 bg-gray-50">
                     <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}" class="w-full h-full object-cover" />
                 </div>
                 
                 <div class="flex justify-between items-start">
                     <div>
                         <h2 class="text-base font-black text-gray-900 leading-snug">{{ $menu['name'] }}</h2>
                         <p class="text-sm font-extrabold text-orange-500 mt-1">
                             Rp {{ number_format($menu['price'], 0, ',', '.') }}
                         </p>
                     </div>
                 </div>
                 
                 <p class="text-[11px] text-gray-400 mt-2.5 leading-relaxed">
                     {{ $menu['description'] }}
                 </p>
                 
                 <!-- Toppings Checkbox Section -->
                 <div class="mt-5 border-t border-gray-100 pt-4">
                     <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-wider mb-2.5">Pilihan Topping (Opsional)</h3>
                     <div class="space-y-2">
                         @foreach($toppingsList as $topping)
                             <label class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:bg-orange-50/20 active:scale-[0.99] transition-all duration-200">
                                 <div class="flex items-center space-x-3">
                                     <input type="checkbox" 
                                            wire:model="selectedToppings" 
                                            value="{{ $topping['id'] }}" 
                                            class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 focus:ring-offset-0" />
                                     <span class="text-xs font-semibold text-gray-700">{{ $topping['name'] }}</span>
                                 </div>
                                 <span class="text-xs font-bold text-gray-400">+Rp {{ number_format($topping['price'], 0, ',', '.') }}</span>
                             </label>
                         @endforeach
                     </div>
                 </div>
                 
                 <!-- Spiciness Levels Radio Section -->
                 <div class="mt-5 border-t border-gray-100 pt-4">
                     <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-wider mb-2.5">Tingkat Kepedasan</h3>
                     <div class="grid grid-cols-3 gap-2">
                         @foreach($spicinessList as $level)
                             <label class="relative flex items-center justify-center p-2 rounded-xl border text-[11px] font-bold cursor-pointer transition-all duration-200
                                 {{ $selectedSpiciness === $level['id'] 
                                     ? 'bg-orange-500 text-white border-orange-500 shadow-sm shadow-orange-500/20' 
                                     : 'bg-gray-50 text-gray-500 border-gray-100 hover:bg-gray-100' }}">
                                 <input type="radio" 
                                        wire:model="selectedSpiciness" 
                                        value="{{ $level['id'] }}" 
                                        class="sr-only" />
                                 <span>{{ $level['name'] }}</span>
                             </label>
                         @endforeach
                     </div>
                 </div>

             </div>

             <!-- Bottom Action Bar (Fixed at the bottom of sheet) -->
             <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between shrink-0">
                 <!-- Quantity Counter -->
                 <div class="flex items-center space-x-3 bg-white p-1 rounded-2xl border border-gray-150 shadow-xs">
                     <button wire:click="decrement" class="min-w-[44px] min-h-[44px] flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl transition-all active:scale-90 font-extrabold">-</button>
                     <span class="text-xs font-extrabold text-gray-800 w-6 text-center">{{ $quantity }}</span>
                     <button wire:click="increment" class="min-w-[44px] min-h-[44px] flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl transition-all active:scale-90 font-extrabold">+</button>
                 </div>

                 <!-- Add to Cart Button -->
                 <button wire:click="addToCart"
                         class="flex-grow ml-4 py-3 px-6 bg-orange-500 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-wider rounded-2xl shadow-lg shadow-orange-500/10 hover:shadow-orange-500/20 transition-all duration-200 active:scale-[0.98] text-center">
                     Tambah ke Keranjang
                 </button>
             </div>
         @endif

    </div>

</div>
