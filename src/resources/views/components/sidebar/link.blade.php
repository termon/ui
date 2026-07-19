@props([
    'href' => "#",
    'label' => null,
    'icon', // e.g. 'home', 'cog-6-tooth'
    'collapsed' => false,
    'active' => request()->url() === url($href ?? '#'),
])

@php
    $baseClasses = 'group relative flex items-center border border-transparent px-3 py-2 font-medium rounded-lg text-left transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600/40';
    $activeClasses = 'border-blue-200/70 bg-blue-50 font-semibold text-blue-800 shadow-xs dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-200';
    $inactiveClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white';
    $wrapperClasses = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['href' => $href]) }}
   class="{{ $wrapperClasses }}"
   x-data="{ 
       get isInToolbar() {
           return !!this.$el.closest('header');
       }
   }"
   :class="isInToolbar ? 'w-auto shrink-0 justify-center' : (collapsed ? 'w-full justify-center' : 'w-full gap-3')">
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
