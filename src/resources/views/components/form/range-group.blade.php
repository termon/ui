@props([
    'name',
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => 0,
    'variant' => 'light',
    'label' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}" class="mb-1" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.range
        name="{{ $name }}"
        :min="$min"
        :max="$max"
        :step="$step"
        :value="$value"
        variant="{{ $variant }}"
        {{ $attributes->except(['class', 'name', 'min', 'max', 'step', 'value', 'variant', 'label', 'icon']) }}
    />

    <x-ui::form.error for="{{ $name }}" />
</div>
