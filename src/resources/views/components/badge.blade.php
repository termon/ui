@props(['variant' => 'blue'])

@php
    $classes = match ($variant) {
        'blue-subtle' => 'text-xs font-medium px-2 py-0.5 rounded-md border border-blue-200/60 bg-blue-50/60 text-blue-700 dark:border-blue-400/20 dark:bg-blue-400/5 dark:text-blue-300',
        'pink-subtle' => 'text-xs font-medium px-2 py-0.5 rounded-md border border-pink-200/60 bg-pink-50/60 text-pink-700 dark:border-pink-400/20 dark:bg-pink-400/5 dark:text-pink-300',
        'amber-subtle' => 'text-xs font-medium px-2 py-0.5 rounded-md border border-amber-200/60 bg-amber-50/60 text-amber-700 dark:border-amber-400/20 dark:bg-amber-400/5 dark:text-amber-300',
        'emerald-subtle' => 'text-xs font-medium px-2 py-0.5 rounded-md border border-emerald-200/60 bg-emerald-50/60 text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-400/5 dark:text-emerald-300',
        'purple-subtle' => 'text-xs font-medium px-2 py-0.5 rounded-md border border-purple-200/60 bg-purple-50/60 text-purple-700 dark:border-purple-400/20 dark:bg-purple-400/5 dark:text-purple-300',
        'blue' => 'text-sm font-medium px-2.5 py-0.5 rounded-md border border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300',
        'gray', 'slate' => 'text-sm font-medium px-2.5 py-0.5 rounded-md border border-slate-200 bg-slate-100 text-slate-700 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200',
        'light' => 'text-sm font-medium px-2.5 py-0.5 rounded-md border border-slate-200/70 bg-slate-50 text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300',
        'red' => 'text-sm font-medium px-2.5 py-0.5 rounded border border-red-400       bg-red-100    text-red-800 dark:bg-red-900 dark:text-red-300 dark:border-gray-600',
        'green', 'emerald' => 'text-sm font-medium px-2.5 py-0.5 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300',
        'yellow', 'amber' => 'text-sm font-medium px-2.5 py-0.5 rounded-md border border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300',
        'sky' => 'text-sm font-medium px-2.5 py-0.5 rounded-md border border-sky-200 bg-sky-50 text-sky-800 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-300',
        'indigo' => 'text-sm font-medium px-2.5 py-0.5 rounded border border-indigo-400 bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300 dark:border-gray-600',
        'purple' => 'text-sm font-medium px-2.5 py-0.5 rounded border border-purple-400 bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 dark:border-gray-600',
        'pink' => 'text-sm font-medium px-2.5 py-0.5 rounded border border-pink-400     bg-pink-100   text-pink-800 dark:bg-pink-900 dark:text-pink-300 dark:border-gray-600',
        default => throw new \Exception("No such badge variant: $variant"),
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
