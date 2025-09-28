@props([
    'href' => "#",
    'label' => null,
    'icon', // e.g. 'home', 'cog-6-tooth'
    'collapsed' => false,
    'active' => request()->url() === url($href ?? '#'),
])

@php
    $baseClasses = 'group relative flex items-center font-medium px-4 py-2 rounded-md w-full text-left transition-colors duration-200';
    $activeClasses = 'bg-gray-200 dark:bg-gray-700 font-semibold text-gray-700 dark:text-white';
    $inactiveClasses = 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300';
    $wrapperClasses = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['href' => $href]) }}
   class="{{ $wrapperClasses }}"
   x-data="{ 
       get isInToolbar() {
           return !!this.$el.closest('header');
       }
   }"
   :class="collapsed ? 'justify-center' : 'gap-3'">
    @isset($icon)
        <x-ui::svg :icon="$icon" class="w-5 h-5 shrink-0" />
    @endisset

    @if($label)
        <span x-show="!collapsed" x-cloak class="truncate">
            {{ $label }}
        </span>

        <!-- Tooltip for collapsed state (right in sidebar, below in toolbar) -->
        <span x-show="collapsed" x-cloak
              :class="isInToolbar ? 'absolute top-full mt-2 left-1/2 transform -translate-x-1/2' : 'absolute left-full ml-2'"
              class="px-2 py-1 bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50 whitespace-nowrap pointer-events-none">
            {{ $label }}
        </span>
    @endif
</a>
