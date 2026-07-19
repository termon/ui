@props([
    'title',
    'description' => null,
    'status' => null,
    'statusVariant' => 'slate',
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 border-b border-slate-200 pb-3 dark:border-slate-700 sm:flex-row sm:items-start sm:justify-between']) }}>
    <div class="min-w-0">
        <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-base font-semibold tracking-tight text-slate-950 dark:text-slate-50">{{ $title }}</h3>
            @if (filled($status))
                <x-ui::badge :variant="$statusVariant">{{ $status }}</x-ui::badge>
            @endif
        </div>
        @if (filled($description))
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
        <div {{ $actions->attributes->class(['flex shrink-0 flex-wrap items-center gap-2']) }}>
            {{ $actions }}
        </div>
    @endisset
</div>
