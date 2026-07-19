@props([
    'href' => '#',
    'label',
    'icon', // e.g. 'home', 'cog-6-tooth'
    'active' => request()->url() === url($href ?? '#'),
])

@php
    $baseClasses = 'group relative flex items-center gap-2 rounded-lg border border-transparent px-3 py-2 font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600/40';
    $activeClasses = 'border-blue-200/70 bg-blue-50 font-semibold text-blue-800 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-200';
    $inactiveClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white';
    $wrapperClasses = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['href' => $href]) }} class="{{ $wrapperClasses }}">
    @isset($icon)
        <x-ui::icon :icon="$icon" class="w-5 h-5 shrink-0" />
    @endisset

    @isset($label)
        <span class="truncate">{{ $label }}</span>
    @endisset
</a>
