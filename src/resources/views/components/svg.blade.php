@props([
    'size' => 'sm',
    'set' => 'icons',
    'icon' => null,
    'variant' => null,
    'viewBox' => '0 0 24 24',
    'fill' => 'none',
    'stroke' => 'currentColor',
    'strokeWidth' => '1.5',
])
@php
    // Backward compatibility: some package views pass `variant` instead of `icon`.
    $icon = $icon ?? $variant;

    $class = match ($size) {
        'sm' => 'size-4',
        'md' => 'size-6',
        'lg' => 'size-8',
        'xl' => 'size-12',
        default => throw new \InvalidArgumentException("No such svg size: {$size}"),
    };

    if (!$icon) {
        throw new \InvalidArgumentException('No svg icon provided.');
    }

    $iconViews = [
        "components.ui.{$set}.{$icon}",
        "ui::components.{$set}.{$icon}",
    ];

    $iconView = collect($iconViews)->first(
        fn (string $candidate) => view()->exists($candidate)
    );

    if (!$iconView) {
        throw new \InvalidArgumentException(
            "No such svg {$set}.{$icon}. Tried: ".implode(', ', $iconViews)
        );
    }
@endphp

<svg xmlns="http://www.w3.org/2000/svg" fill="{{ $fill }}" stroke="{{ $stroke }}" 
     stroke-width="{{ $strokeWidth }}" viewBox="{{ $viewBox }}" {{ $attributes->merge(['class' => $class]) }}>
    @include($iconView)
</svg>
