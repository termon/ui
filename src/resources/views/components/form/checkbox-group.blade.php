@props([
    'name',
    'label' => null,
    'description' => null,
    'value' => [],
    'options' => [],
    'variant' => 'plain',
    'icon' => null,
])

@php
    $selectedValues = collect(is_array($value) ? $value : [$value])->map(fn ($selectedValue) => (string) $selectedValue)->all();
    $inputName = str_ends_with($name, '[]') ? $name : $name.'[]';
    $errorName = str_ends_with($name, '[]') ? substr($name, 0, -2) : $name;

    $optionWrapperClasses = match ($variant) {
        'plain' => 'space-y-3',
        'card' => 'space-y-2',
        default => throw new InvalidArgumentException("Unsupported checkbox-group variant [{$variant}]."),
    };

    $checkboxClasses = match ($variant) {
        'plain' => 'flex items-start gap-3',
        'card' => 'flex items-start gap-3 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200',
    };
@endphp

<div {{ $attributes->only('class')->class(['w-full space-y-2']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $errorName }}" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    @if (filled($description))
        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    @endif

    <div class="{{ $optionWrapperClasses }}">
        @foreach ($options as $optionValue => $optionLabel)
            @php
                $option = is_array($optionLabel) ? $optionLabel : ['label' => $optionLabel];
                $resolvedValue = (string) ($option['value'] ?? $optionValue);
                $resolvedLabel = $option['label'] ?? $resolvedValue;
                $resolvedDescription = $option['description'] ?? null;
                $isDisabled = (bool) ($option['disabled'] ?? false);
            @endphp

            <x-ui::form.checkbox
                :name="$inputName"
                :value="$resolvedValue"
                :label="$resolvedLabel"
                :description="$resolvedDescription"
                :checked="in_array($resolvedValue, $selectedValues, true)"
                :disabled="$isDisabled"
                class="{{ $checkboxClasses }}"
                {{ $attributes->except(['class', 'name', 'label', 'description', 'value', 'options', 'variant', 'icon']) }}
            />
        @endforeach
    </div>

    <x-ui::form.error for="{{ $errorName }}" />
</div>
