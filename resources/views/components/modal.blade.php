@props([
    'show' => false,
    'maxWidth' => 'lg',
    'closeAction' => null,
])

@php
    $maxWidthClasses = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-lg',
    };
@endphp

@if($show)
    <div class="relative z-50 flex items-center justify-center">
        <!-- Overlay Backdrop -->
        <div
            @if($closeAction) wire:click="{{ $closeAction }}" @endif
            class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
        ></div>

        <!-- Modal Panel Window -->
        <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full {{ $maxWidthClasses }} overflow-hidden relative flex flex-col max-h-[90vh]" {{ $attributes }}>
                
                <!-- Header -->
                @isset($header)
                    <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between shrink-0">
                        {{ $header }}
                        @if($closeAction)
                            <button wire:click="{{ $closeAction }}" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-150 rounded-full shrink-0 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        @endif
                    </div>
                @endisset

                <!-- Body -->
                @isset($body)
                    <div class="p-6 overflow-y-auto flex-1 space-y-6">
                        {{ $body }}
                    </div>
                @endisset

                <!-- Footer -->
                @isset($footer)
                    <div class="p-5 border-t border-gray-150 bg-gray-50 shrink-0">
                        {{ $footer }}
                    </div>
                @endisset

            </div>
        </div>
    </div>
@endif
