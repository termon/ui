@props([
    'variant' => 'primary',
    'label' => 'Page sections',
])

@php
    $classes = match ($variant) {
        'primary' => 'flex min-w-0 max-w-full flex-wrap gap-1 border-b border-slate-200 dark:border-slate-700',
        'secondary' => 'flex min-w-0 max-w-full flex-wrap gap-1 rounded-lg bg-slate-100/80 p-1 dark:bg-slate-800/80',
        default => throw new \Exception("No such nav tabs variant: {$variant}"),
    };
@endphp

<nav aria-label="{{ $label }}" {{ $attributes->class([$classes]) }}>
    {{ $slot }}
</nav>
