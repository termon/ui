@props([
    'name',
    'value' => null,
    'icon' => null,
    'label' => null,   
])


<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.textarea name="{{ $name }}" {{ $attributes->except(['name', 'label', 'value', 'icon']) }}>
    {{ $value ?? $slot }}
    </x-ui::form.textarea>

    <x-ui::form.error for="{{ $name }}" />

</div>





