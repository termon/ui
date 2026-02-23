@props([
    'size' => 'sm',
    'set' => 'icons',
    'icon' => null,
    'viewBox' => '0 0 24 24',
    'fill' => 'none',
    'stroke' => 'currentColor',
    'strokeWidth' => '1.5',
])
@php
    $class = match ($size) {
        'sm' => 'size-4',
        'md' => 'size-6',
        'lg' => 'size-8',
        'xl' => 'size-12',
        default => throw new \InvalidArgumentException("No such svg size: {$size}"),
    };

    $iconView = "components.ui.{$set}.{$icon}";
    if (!view()->exists($iconView)) {
        throw new \InvalidArgumentException("No such svg {$set}.{$icon}");
    }
@endphp

<svg xmlns="http://www.w3.org/2000/svg" fill="{{ $fill }}" stroke="{{ $stroke }}" 
     stroke-width="{{ $strokeWidth }}" viewBox="{{ $viewBox }}" {{ $attributes->merge(['class' => $class]) }}>
    @include($iconView)
</svg>
