@props(['title', 'value', 'variant' => 'dark', 'description' => null, 'icon' => null ])
@php
    $variantStyles = match ($variant) {
        'blue' => [
            'wrapper' => 'border border-blue-200 bg-blue-50 text-blue-900 hover:border-blue-300 hover:bg-blue-100/80 dark:border-blue-500/40 dark:bg-blue-500/10 dark:text-blue-100 dark:hover:border-blue-400/60 dark:hover:bg-blue-500/15',
            'title' => 'text-blue-700 dark:text-blue-300',
            'value' => 'text-blue-900 dark:text-blue-100',
            'description' => 'text-blue-700/80 dark:text-blue-200/80',
        ],
        'red', 'rose' => [
            'wrapper' => 'border border-rose-200 bg-rose-50 text-rose-900 hover:border-rose-300 hover:bg-rose-100/80 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-100 dark:hover:border-rose-400/60 dark:hover:bg-rose-500/15',
            'title' => 'text-rose-700 dark:text-rose-300',
            'value' => 'text-rose-900 dark:text-rose-100',
            'description' => 'text-rose-700/80 dark:text-rose-200/80',
        ],
        'green', 'emerald' => [
            'wrapper' => 'border border-emerald-200 bg-emerald-50 text-emerald-900 hover:border-emerald-300 hover:bg-emerald-100/80 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-100 dark:hover:border-emerald-400/60 dark:hover:bg-emerald-500/15',
            'title' => 'text-emerald-700 dark:text-emerald-300',
            'value' => 'text-emerald-900 dark:text-emerald-100',
            'description' => 'text-emerald-700/80 dark:text-emerald-200/80',
        ],
        'yellow' => [
            'wrapper' => 'border border-amber-200 bg-amber-50 text-amber-900 hover:border-amber-300 hover:bg-amber-100/80 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100 dark:hover:border-amber-400/60 dark:hover:bg-amber-500/15',
            'title' => 'text-amber-700 dark:text-amber-300',
            'value' => 'text-amber-900 dark:text-amber-100',
            'description' => 'text-amber-700/80 dark:text-amber-200/80',
        ],
        'pink' => [
            'wrapper' => 'border border-pink-200 bg-pink-50 text-pink-900 hover:border-pink-300 hover:bg-pink-100/80 dark:border-pink-500/40 dark:bg-pink-500/10 dark:text-pink-100 dark:hover:border-pink-400/60 dark:hover:bg-pink-500/15',
            'title' => 'text-pink-700 dark:text-pink-300',
            'value' => 'text-pink-900 dark:text-pink-100',
            'description' => 'text-pink-700/80 dark:text-pink-200/80',
        ],
        'sky' => [
            'wrapper' => 'border border-sky-200 bg-sky-50 text-sky-900 hover:border-sky-300 hover:bg-sky-100/80 dark:border-sky-500/40 dark:bg-sky-500/10 dark:text-sky-100 dark:hover:border-sky-400/60 dark:hover:bg-sky-500/15',
            'title' => 'text-sky-700 dark:text-sky-300',
            'value' => 'text-sky-900 dark:text-sky-100',
            'description' => 'text-sky-700/80 dark:text-sky-200/80',
        ],
        'indigo' => [
            'wrapper' => 'border border-indigo-200 bg-indigo-50 text-indigo-900 hover:border-indigo-300 hover:bg-indigo-100/80 dark:border-indigo-500/40 dark:bg-indigo-500/10 dark:text-indigo-100 dark:hover:border-indigo-400/60 dark:hover:bg-indigo-500/15',
            'title' => 'text-indigo-700 dark:text-indigo-300',
            'value' => 'text-indigo-900 dark:text-indigo-100',
            'description' => 'text-indigo-700/80 dark:text-indigo-200/80',
        ],
        'gray' => [
            'wrapper' => 'border border-gray-200 bg-gray-50 text-gray-900 hover:border-gray-300 hover:bg-gray-100/80 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:hover:border-gray-500 dark:hover:bg-gray-700/80',
            'title' => 'text-gray-600 dark:text-gray-300',
            'value' => 'text-gray-900 dark:text-gray-100',
            'description' => 'text-gray-600/80 dark:text-gray-300/80',
        ],
        'light', 'slate' => [
            'wrapper' => 'border border-slate-200 bg-slate-50 text-slate-900 hover:border-slate-300 hover:bg-slate-100/80 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:border-slate-600 dark:hover:bg-slate-800/80',
            'title' => 'text-slate-600 dark:text-slate-300',
            'value' => 'text-slate-900 dark:text-slate-100',
            'description' => 'text-slate-600/80 dark:text-slate-300/80',
        ],
        'dark', 'neutral' => [
            'wrapper' => 'border border-slate-200 bg-white text-slate-900 hover:border-slate-300 hover:bg-slate-50/80 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:border-slate-500 dark:hover:bg-slate-700/80',
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
