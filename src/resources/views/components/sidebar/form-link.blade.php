@props([
    'action' => "#",
    'label' => null,
    'icon', // e.g. 'home', 'cog-6-tooth'
    'collapsed' => false,
    'method' => 'post'
])

@php
    $baseClasses = 'group relative flex w-full items-center rounded-lg border border-transparent px-3 py-2 text-left font-medium transition-colors duration-200';
    $inactiveClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white';
    $wrapperClasses = $baseClasses . ' ' . $inactiveClasses;
@endphp

<form method="POST" action="{{ $action }}" class="{{ $wrapperClasses }}"
      x-data="{ 
          get isInToolbar() {
              return !!this.$el.closest('header');
          }
      }">
    @csrf
    @method($method)
   
    <button type="submit" class="flex items-center gap-2 w-full cursor-pointer">
        @isset($icon)
            <x-ui::icon :icon="$icon" class="w-5 h-5 shrink-0" />
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
    </button>
</form>
