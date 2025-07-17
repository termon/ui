@props([
    'icon' => 'folder', // Default icon
    'label' => '',       // Menu label
    'collapsed' => false // Passed down from parent layout
])

<div 
    x-data="{ 
        open: false,
        get isInToolbar() {
            // Simple detection: check if we're inside a header element
            // The toolbar slot is rendered inside the header, while sidebar content is in nav/aside
            return !!this.$el.closest('header');
        }
    }" 
    @click.away="open = false"
    x-effect="if (collapsed) open = false" 
    class="relative group"
>
    <button
        x-ref="button"
        @click="open = !open; collapsed = false"
        class="w-full flex items-center justify-between gap-3 px-4 py-2 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition text-left cursor-pointer"
        :class="collapsed ? 'justify-center px-0' : 'justify-start gap-3'"
    >
        <div class="flex items-center gap-3">
            
            {{-- Heroicon --}}
            @isset($icon)
                {{-- <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" />             --}}
                <x-ui::svg :icon="$icon" class="w-5 h-5 shrink-0" />
            @endisset

            {{-- Label --}}
            <span x-show="!collapsed && !isInToolbar" class="truncate">{{ $label }}</span>
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
        x-transition:enter="transition-all duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition-all duration-150 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        :class="isInToolbar 
            ? 'absolute right-0 top-full mt-2 min-w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg py-1 z-50' 
            : 'mt-1 pl-4 pr-2 space-y-1'"
        style="display: none;"
    >
        <template x-if="isInToolbar">
            <div class="space-y-1 px-1">
                {{ $slot }}
            </div>
        </template>
        <template x-if="!isInToolbar">
            <div>
                {{ $slot }}
            </div>
        </template>
    </div>

    <!-- Tooltip for collapsed state (right in sidebar, below in toolbar) -->
    <span x-show="collapsed" x-cloak
            :class="isInToolbar ? 'absolute top-full mt-2 left-1/2 transform -translate-x-1/2' : 'absolute left-full ml-2 top-1/2 transform -translate-y-1/2'"
            class="px-2 py-1 bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50 whitespace-nowrap pointer-events-none">
        {{ $label }}
    </span>
</div>
