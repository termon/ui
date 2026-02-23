@props([
    'name', 
    'label' => null, 
    'value' => null, 
    'variant' => 'light', 
    'type' => 'text', 
    'icon' => null
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label for="{{ $name }}" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"       
        variant="{{ $variant }}" 
        {{ $attributes->except(['class', 'label', 'value', 'variant', 'type', 'icon']) }} 
    />
        
    <x-ui::form.error for="{{ $name }}" />

</div>
