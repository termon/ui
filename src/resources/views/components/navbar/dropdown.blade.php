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
        class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-md font-medium hover:bg-gray-100 text-gray-500 dark:text-gray-300 dark:hover:bg-gray-700 transition text-left"
    >
        <div class="flex items-center gap-3">
            
            {{-- Heroicon --}}
            @isset($icon)
                {{-- <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" /> --}}
                <x-ui::icon :icon="$icon" class="w-5 h-5 shrink-0" />            
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
        x-transition:enter="transition-all duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition-all duration-150 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        class="mt-1 pl-4 space-y-1 lg:absolute lg:right-0 lg:top-full lg:mt-2 lg:pl-1 lg:py-1 lg:bg-white lg:dark:bg-gray-800 lg:border lg:border-gray-200 lg:dark:border-gray-700 lg:rounded-md lg:shadow-lg lg:z-50 lg:min-w-48 lg:w-max"
    >
        {{ $slot }}
    </div>
    
</div>
