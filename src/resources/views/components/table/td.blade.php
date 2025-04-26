{{-- whitespace-nowrap --}}
@props([
    'showOn' => null,
])

@php
    $validBreakpoints = ['sm', 'md', 'lg', 'xl', '2xl'];
    $responsiveClass = in_array($showOn, $validBreakpoints)
        ? "hidden {$showOn}:table-cell"
        : null;
@endphp

<td {{ $attributes->merge(['class' => 'px-3 py-2'])->class([$responsiveClass]) }}>
    {{ $slot }}
</td>

