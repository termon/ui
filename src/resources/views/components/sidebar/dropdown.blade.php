@props([
    'icon' => 'folder', // Default icon
    'label' => '',       // Menu label
    'collapsed' => false // Passed down from parent layout
])

<div 
    x-data="{ open: false }" 
    @click.away="open = false"
    x-effect="if (collapsed) open = false" 
    class="relative group"
>
    <button
        @click="open = !open; collapsed = false"
        {{-- :class="collapsed ? 'justify-center' : 'justify-between'" --}}
        class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition text-left"
        {{-- :class="collapsed ? 'justify-center' : 'justify-between'" --}}
        :class="collapsed ? 'justify-center px-0' : 'justify-start gap-3'"
    >
        <div class="flex items-center gap-3">
            
            {{-- Heroicon --}}
            @isset($icon)
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" />            
            @endisset

            {{-- Label --}}
            <span x-show="!collapsed" class="truncate">{{ $label }}</span>
        </div>

        {{-- Dropdown Indicator --}}
        <svg x-show="!collapsed" :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
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
        class="mt-1 pl-4 pr-2 space-y-1"
    >
        {{ $slot }}
    </div>

    <!-- Tooltip when sidebar is collapsed -->
    <span x-show="collapsed" x-cloak
          class="absolute left-full top-2 ml-2 px-2 py-1 bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
        {{ $label }}
    </span>
</div>
