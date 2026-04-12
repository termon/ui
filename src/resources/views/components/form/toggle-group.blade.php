@props([
    'name',
    'label' => null,
    'description' => null,
    'checked' => false,
    'variant' => 'plain',
])

@php
    $toggleClasses = match ($variant) {
        'plain' => 'flex items-start gap-3',
        'card' => 'flex items-start gap-3 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200',
        default => throw new InvalidArgumentException("Unsupported toggle-group variant [{$variant}]."),
    };
@endphp

<div {{ $attributes->only('class')->class(['space-y-2']) }}>
    <x-form.toggle
        :name="$name"
        :label="$label"
        :description="$description"
        :checked="$checked"
        class="{{ $toggleClasses }}"
        {{ $attributes->except(['class', 'name', 'label', 'description', 'checked', 'variant']) }}
    />

    <x-ui::form.error for="{{ $name }}" class="mt-1" />
</div>

