@props([
    'label',
    'value' => null,
    'icon' => null,
    'labelWidth' => 'md', // accepts: sm, md, lg, xl
    'variant' => 'default',
])

@php
    $labelWidthClass = match ($labelWidth) {
        'sm' => 'md:min-w-[6rem]',
        'md' => 'md:min-w-[10rem]',
        'lg' => 'md:min-w-[14rem]',
        'xl' => 'md:min-w-[18rem]',
        default => 'md:min-w-[10rem]',
    };

    $wrapperClasses = match ($variant) {
        'default' => 'flex flex-col border-b border-slate-100 py-2.5 last:border-b-0 dark:border-slate-700 md:flex-row md:items-start',
        'compact' => 'flex flex-col border-b border-slate-100 py-1.5 last:border-b-0 dark:border-slate-700 sm:flex-row sm:items-start',
        'tile' => 'flex min-w-0 flex-col rounded-lg border border-slate-200/80 bg-slate-50/70 px-3 py-2.5 dark:border-slate-700 dark:bg-slate-900/50',
        'stacked' => 'flex min-w-0 flex-col gap-1',
        default => throw new \Exception("No such display variant: {$variant}"),
    };

    $valueClasses = match ($variant) {
        'default' => 'mt-1 text-slate-900 dark:text-slate-100 md:mt-0 md:flex-1 md:pl-4',
        'compact' => 'mt-0.5 text-slate-900 dark:text-slate-100 sm:mt-0 sm:flex-1 sm:pl-4',
        'tile' => 'mt-1.5 truncate text-base font-semibold text-slate-950 dark:text-slate-50',
        'stacked' => 'text-slate-900 dark:text-slate-100',
    };
@endphp
{{-- Wrapper --}}
<div {{ $attributes->class([$wrapperClasses]) }}>
    {{-- Icon + Label (always inline) --}}
    <div class="flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 {{ $labelWidthClass }}">
        @isset($icon)
            <x-ui::svg :icon="$icon" class="w-4 h-4 text-gray-400 dark:text-gray-500" />
        @endisset
        <span>{{ $label }}</span>
    </div>

    {{-- Value --}}
    <div class="{{ $valueClasses }}">
        @isset ($value)
            {{ $value }}
        @else
            <div {{ $slot->attributes }}>
                {{ $slot }}
            </div>
        @endisset
    </div>
</div>
