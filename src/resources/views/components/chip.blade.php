@props([
    'variant' => 'slate',
    'href' => null,
    'icon' => null,
    'size' => 'default',
])

@php
    $variantClasses = match ($variant) {
        'blue', 'sky' => 'border-sky-200 bg-sky-50 text-sky-800 hover:border-sky-300 hover:bg-sky-100 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-200 dark:hover:bg-sky-500/20',
        'green', 'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:border-emerald-300 hover:bg-emerald-100 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200 dark:hover:bg-emerald-500/20',
        'slate', 'gray' => 'border-slate-200 bg-slate-100 text-slate-700 hover:border-slate-300 hover:bg-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600',
        default => throw new \Exception("No such chip variant: {$variant}"),
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'default' => 'px-3 py-1.5 text-sm',
        default => throw new \Exception("No such chip size: {$size}"),
    };

    $classes = "inline-flex items-center gap-1.5 rounded-full border font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600/40 {$sizeClasses} {$variantClasses}";
@endphp

@if (filled($href))
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>
        @isset($icon)
            <x-ui::svg :icon="$icon" class="size-3.5" />
        @endisset
        {{ $slot }}
    </a>
@else
    <span {{ $attributes->class([$classes]) }}>
        @isset($icon)
            <x-ui::svg :icon="$icon" class="size-3.5" />
        @endisset
        {{ $slot }}
    </span>
@endif
