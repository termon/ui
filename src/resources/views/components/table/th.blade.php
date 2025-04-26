@props([
    'showOn' => null,
    'scope' => 'col',
])

@php
    $validBreakpoints = ['sm', 'md', 'lg', 'xl', '2xl'];
    $responsiveClass = in_array($showOn, $validBreakpoints)
        ? "hidden {$showOn}:table-cell"
        : null;
@endphp

<th scope="{{ $scope }}"
    {{ $attributes->merge(['class' => 'px-3 py-2 text-left text-sm font-semibold text-gray-900 dark:text-white'])->class([$responsiveClass]) }}>
    {{ $slot }}
</th>
