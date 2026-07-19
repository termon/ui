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
    {{ $attributes->merge(['class' => 'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300'])->class([$responsiveClass]) }}>
    {{ $slot }}
</th>
