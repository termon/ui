@props([
    'name',
    'label' => null,
    'value' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}" class="mb-1" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.datetime
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $attributes->except(['class', 'name', 'label', 'value', 'icon']) }}
    />

    <x-ui::form.error for="{{ $name }}" />
</div>
