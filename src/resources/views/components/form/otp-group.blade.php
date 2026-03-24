@props([
    'name',
    'label' => null,
    'length' => 6,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}-1" class="mb-2" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.otp
        name="{{ $name }}"
        :length="$length"
        {{ $attributes->except(['class', 'name', 'label', 'length', 'icon']) }}
    />

    <x-ui::form.error for="{{ $name }}" />
</div>
