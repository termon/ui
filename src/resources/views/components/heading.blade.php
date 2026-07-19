@props([
    'level' => '1',
])
@php
    $tag = 'h' . $level;
    $classes = match ($level) {
        '1' => 'text-3xl md:text-4xl font-bold tracking-tight text-slate-950 dark:text-slate-50',
        '2' => 'text-2xl md:text-3xl font-bold tracking-tight text-slate-950 dark:text-slate-50',
        '3' => 'text-xl md:text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-50',
        '4' => 'text-lg md:text-xl font-semibold tracking-tight text-slate-900 dark:text-slate-100',
        '5' => 'text-base md:text-lg font-semibold text-slate-900 dark:text-slate-100',
        '6' => 'text-sm md:text-base font-semibold text-slate-900 dark:text-slate-100',
        default => throw new \Exception("heading: No such level {$level}"),
    };
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</{{ $tag }}>
