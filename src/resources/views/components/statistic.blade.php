@props(['title', 'value', 'variant' => 'dark', 'description' => null, 'icon' => null ])
@php
    $variantStyles = match ($variant) {
        'blue' => [
            'wrapper' => 'border border-blue-200 bg-blue-50 text-blue-900 hover:border-blue-300 hover:bg-blue-100/80',
            'title' => 'text-blue-700 dark:text-blue-300',
            'value' => 'text-blue-900 dark:text-blue-100',
            'description' => 'text-blue-700/80 dark:text-blue-200/80',
        ],
        'red' => [
            'wrapper' => 'border border-rose-200 bg-rose-50 text-rose-900 hover:border-rose-300 hover:bg-rose-100/80',
            'title' => 'text-rose-700 dark:text-rose-300',
            'value' => 'text-rose-900 dark:text-rose-100',
            'description' => 'text-rose-700/80 dark:text-rose-200/80',
        ],
        'green' => [
            'wrapper' => 'border border-emerald-200 bg-emerald-50 text-emerald-900 hover:border-emerald-300 hover:bg-emerald-100/80',
            'title' => 'text-emerald-700 dark:text-emerald-300',
            'value' => 'text-emerald-900 dark:text-emerald-100',
            'description' => 'text-emerald-700/80 dark:text-emerald-200/80',
        ],
        'yellow' => [
            'wrapper' => 'border border-amber-200 bg-amber-50 text-amber-900 hover:border-amber-300 hover:bg-amber-100/80',
            'title' => 'text-amber-700 dark:text-amber-300',
            'value' => 'text-amber-900 dark:text-amber-100',
            'description' => 'text-amber-700/80 dark:text-amber-200/80',
        ],
        'pink' => [
            'wrapper' => 'border border-pink-200 bg-pink-50 text-pink-900 hover:border-pink-300 hover:bg-pink-100/80',
            'title' => 'text-pink-700 dark:text-pink-300',
            'value' => 'text-pink-900 dark:text-pink-100',
            'description' => 'text-pink-700/80 dark:text-pink-200/80',
        ],
        'light' => [
            'wrapper' => 'border border-slate-200 bg-slate-50 text-slate-900 hover:border-slate-300 hover:bg-slate-100/80',
            'title' => 'text-slate-600 dark:text-slate-300',
            'value' => 'text-slate-900 dark:text-slate-100',
            'description' => 'text-slate-600/80 dark:text-slate-300/80',
        ],
        'dark' => [
            'wrapper' => 'border border-slate-200 bg-white text-slate-900 hover:border-slate-300 hover:bg-slate-50/80',
            'title' => 'text-slate-500 dark:text-slate-300',
            'value' => 'text-slate-900 dark:text-slate-100',
            'description' => 'text-slate-500/80 dark:text-slate-300/80',
        ],
        default => throw new \Exception("No such button variant: $variant"),
    };
@endphp
<div {{ $attributes->merge(['class' => "rounded-xl px-4 py-3 shadow-sm transition-colors {$variantStyles['wrapper']}"]) }}>

    <div @class([
        'text-[11px] font-semibold uppercase tracking-wide',
        $variantStyles['title'],
    ])>{{ $title }}</div>
    
    <div @class([
        'mt-2 py-1 text-3xl font-semibold leading-none',
        'flex items-center justify-between gap-3' => isset($icon),
        $variantStyles['value'],
    ])>
        {{ $value ?? $slot}}
        @isset($icon)
            <x-ui::svg size="md" :icon="$icon" />
        @endisset
    </div>

    @if($description)
        <div @class([
            'mt-2 text-sm',
            $variantStyles['description'],
        ])>{{ $description }}</div>
    @endif
</div>
