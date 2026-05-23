@props([
    'showOn' => null,
    'scope' => 'col',
])

@php
    $responsiveClass = match ($showOn) {
        'sm' => 'hidden sm:table-cell',
        'md' => 'hidden md:table-cell',
        'lg' => 'hidden lg:table-cell',
        'xl' => 'hidden xl:table-cell',
        '2xl' => 'hidden 2xl:table-cell',
        default => null,
    };
@endphp

<th scope="{{ $scope }}"
    {{ $attributes->merge(['class' => 'px-3 py-2 text-left text-sm font-semibold text-gray-900 dark:text-white'])->class([$responsiveClass]) }}>
    {{ $slot }}
</th>
