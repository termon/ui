@props([
    'icon' => null,
])

<label {{ $attributes->merge(['class' => "mb-1.5 flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300"]) }}>
    @isset($icon)
            <x-ui::svg :icon="$icon" class="h-4 w-4 text-slate-400 dark:text-slate-500" />
    @endisset
    <span>{{ $slot }}</span>
</label>
