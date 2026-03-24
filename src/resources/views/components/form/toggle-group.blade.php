@props([
    'name',
    'label' => null,
    'value' => false,
    'variant' => 'dark',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}" class="mb-1" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.toggle
        name="{{ $name }}"
        :value="$value"
        variant="{{ $variant }}"
        {{ $attributes->except(['class', 'name', 'label', 'value', 'variant', 'icon']) }}
    />

    <x-ui::form.error for="{{ $name }}" />
</div>
