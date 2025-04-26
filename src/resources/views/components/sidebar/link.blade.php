@props([
    'href' => "#",
    'label',
    'icon', // e.g. 'home', 'cog-6-tooth'
    'collapsed' => false,
    'active' => request()->url() === url($href ?? '#'),
    'method' => null
])

@php
    $baseClasses = 'group relative flex items-center px-4 py-2 rounded-md transition w-full text-left';
    $activeClasses = 'bg-gray-200 dark:bg-gray-700 font-semibold text-gray-900 dark:text-white';
    $inactiveClasses = 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300';
    $wrapperClasses = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

@if ($method)
<form method="POST" action="{{ $href }}" class="{{ $wrapperClasses }}">
    @csrf
    @method($method)
    @isset($icon)
        <x-ui::icon :icon="$icon" class="w-5 h-5 shrink-0" />
    @endisset
    <button type="submit" class="flex items-center gap-2 w-full">
        <span x-show="!collapsed" x-transition x-cloak class="truncate">
            {{ $label }}
        </span>

        <span x-show="collapsed" x-transition x-cloak
              class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
            {{ $label }}
        </span>
    </button>
</form>
@else
<a {{ $attributes->merge(['href' => $href]) }}
   class="{{ $wrapperClasses }}"
   :class="collapsed ? 'justify-center' : 'gap-3'">
    @isset($icon)
        <x-ui::svg :icon="$icon" class="w-5 h-5 shrink-0" />
    @endisset

    <span x-show="!collapsed" x-transition x-cloak class="truncate">
        {{ $label }}
    </span>

    <span x-show="collapsed" x-transition x-cloak
          class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
        {{ $label }}
    </span>
</a>
@endif
