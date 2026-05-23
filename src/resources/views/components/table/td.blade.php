{{-- whitespace-nowrap --}}
@props([
    'showOn' => null,
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

<td {{ $attributes->merge(['class' => 'px-3 py-2'])->class([$responsiveClass]) }}>
    {{ $slot }}
</td>
