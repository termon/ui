@props([
    'title',
    'description' => null,
    'status' => null,
    'statusVariant' => 'slate',
    'icon' => 'document',
])

<div {{ $attributes->merge(['class' => 'group flex flex-col gap-3 px-3 py-3 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/60 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div class="flex min-w-0 items-start gap-3">
        <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
            <x-ui::svg :icon="$icon" class="size-4" />
        </span>
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</p>
                @if (filled($status))
                    <x-ui::badge :variant="$statusVariant">{{ $status }}</x-ui::badge>
                @endif
            </div>
            @if (filled($description))
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
            @endif
        </div>
    </div>

    @isset($actions)
        <div {{ $actions->attributes->class(['shrink-0 sm:pl-3']) }}>
            {{ $actions }}
        </div>
    @endisset
</div>
