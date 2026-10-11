@props([
    'icon',
    'variant' => '',
])

@php
    $sizeClasses = match ($variant) {
        'mini' => 'w-4 h-4',
        'micro' => 'w-3 h-3',
        default => 'w-5 h-5',
    };
@endphp

<x-ui::svg :icon="$icon" {{ $attributes->class(['shrink-0', $sizeClasses]) }} />
