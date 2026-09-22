@props([
    'name',
    'icon' => null,
    'label' => null,
    'value' => null,
    'options' => [],
    'placeholder' => 'Choose option...',
])

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.select 
        name="{{ $name }}" 
        :options="$options"
        :value="$value"
        placeholder="{{ $placeholder }}"
        {{ $attributes->except(['class', 'name', 'options', 'value', 'icon', 'label', 'placeholder']) }}
    />

    <x-ui::form.error for="{{ $name }}" />
</div>


