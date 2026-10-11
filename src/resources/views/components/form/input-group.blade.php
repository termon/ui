@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'variant' => 'light',
    'type' => 'text',
    'icon' => null
])

@php
    $inputId = $id ?? $name;
    $errorId = $inputId.'-error';
    if ($errors->has($name)) {
        $describedBy = trim($attributes->get('aria-describedby', '').' '.$errorId);
        $attributes = $attributes->except(['aria-describedby', 'aria-invalid'])->merge([
            'aria-describedby' => $describedBy,
            'aria-invalid' => 'true',
        ]);
    }
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label :for="$inputId" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.input
        :id="$inputId"
        type="{{ $type }}"
        name="{{ $name }}"
        :value="$value"
        variant="{{ $variant }}"
        {{ $attributes->except(['class', 'label', 'value', 'variant', 'type', 'icon']) }}
    />

    <x-ui::form.error :id="$errorId" :for="$name" />

</div>
