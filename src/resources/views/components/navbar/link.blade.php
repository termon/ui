@props([
    'href' => '#',
    'label',
    'icon', // e.g. 'home', 'cog-6-tooth'
    'active' => request()->url() === url($href ?? '#'),
])

@php
    $baseClasses = 'group relative flex items-center gap-2 px-4 py-2 rounded-md transition';
    $activeClasses = 'bg-gray-200 dark:bg-gray-700 font-semibold text-gray-900 dark:text-white';
    $inactiveClasses = 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300';
    $wrapperClasses = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['href' => $href]) }} class="{{ $wrapperClasses }}">
    @isset($icon)
        <x-ui::icon :icon="$icon" class="w-5 h-5 shrink-0" />
    @endisset

    @isset($label)
        <span class="truncate">{{ $label }}</span>
    @endisset
</a>
