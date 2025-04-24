@props([
    'icon' => 'folder', // Default icon
    'label' => '',       // Menu label
])

<div 
    x-data="{ open: false }" 
    @click.away="open = false"
    class="relative group"
>
    <button
        @click="open = !open; collapsed = false"       
        class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition text-left"
    >
        <div class="flex items-center gap-3">
            
            {{-- Heroicon --}}
            @isset($icon)
                {{-- <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" /> --}}
                <x-ui.icon :icon="$icon" class="w-5 h-5 shrink-0" />            
            @endisset

            {{-- Label --}}
            <span class="truncate">{{ $label }}</span>
        </div>

        {{-- Dropdown Indicator --}}
        <svg  :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
             stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Menu (for the items) -->
    <div
        x-show="open"
        x-transition:enter="transition-all duration-500 ease-in-out"
        x-transition:enter-start="opacity-0 max-h-0 overflow-hidden"
        x-transition:enter-end="opacity-100 max-h-screen"
        x-transition:leave="transition-all duration-500 ease-in-out"
        x-transition:leave-start="opacity-100 max-h-screen"
        x-transition:leave-end="opacity-0 max-h-0 overflow-hidden"
        x-cloak
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50 py-2"
    >
        {{ $slot }}
    </div>

</div>
