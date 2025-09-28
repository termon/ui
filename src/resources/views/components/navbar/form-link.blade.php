@props([
    'action' => "#",
    'label' => null,
    'icon', // e.g. 'home', 'cog-6-tooth'
    'method' => 'post'
])

@php
    $baseClasses = 'group relative flex items-center gap-2 font-medium px-4 py-2 rounded-md transition';
    $inactiveClasses = 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300';
    $wrapperClasses = $baseClasses . ' ' . $inactiveClasses;
@endphp

<form method="POST" action="{{ $action }}" class="{{ $wrapperClasses }}">
    @csrf
    @method($method)
   
    <button type="submit" class="flex items-center gap-2 w-full">
        @isset($icon)
            <x-ui::icon :icon="$icon" class="w-5 h-5 shrink-0" />
        @endisset

        @isset($label)
            <span class="truncate">{{ $label }}</span>
        @endisset
    </button>
</form>
