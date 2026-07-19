@props([
    'active' => false,
    'disabled' => false,
    'href' => '#',
    'variant' => 'primary',
])

@php
    $baseClasses = 'inline-flex shrink-0 items-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600/40';

    $variantClasses = match ($variant) {
        'primary' => $active
            ? 'rounded-t-md border-b-2 border-blue-700 bg-blue-50 px-3 py-1.5 font-semibold text-blue-800 dark:border-blue-400 dark:bg-blue-500/10 dark:text-blue-200'
            : 'rounded-t-md border-b-2 border-transparent px-3 py-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white',
        'secondary' => $active
            ? 'rounded-md bg-white px-2.5 py-1.5 font-semibold text-blue-800 shadow-xs ring-1 ring-slate-200 dark:bg-slate-700 dark:text-blue-200 dark:ring-slate-600'
            : 'rounded-md px-2.5 py-1.5 text-slate-600 hover:bg-white/70 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-700/70 dark:hover:text-white',
        default => throw new \Exception("No such nav tabs link variant: {$variant}"),
    };

    $disabledClasses = $disabled ? 'cursor-not-allowed opacity-45' : '';
@endphp

@if ($disabled)
    <span aria-disabled="true" {{ $attributes->class(["{$baseClasses} {$variantClasses} {$disabledClasses}"]) }}>
        {{ $slot }}
    </span>
@else
    <a href="{{ $href }}" @if ($active) aria-current="page" @endif {{ $attributes->class(["{$baseClasses} {$variantClasses}"]) }}>
        {{ $slot }}
    </a>
@endif
