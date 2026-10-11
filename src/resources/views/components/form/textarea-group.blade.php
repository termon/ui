@props([
    'name',
    'id' => null,
    'value' => null,
    'icon' => null,
    'label' => null,   
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

    <x-ui::form.textarea :id="$inputId" name="{{ $name }}" :value="$value" {{ $attributes->except(['class', 'name', 'label', 'value', 'icon']) }}>
        {{ $slot }}
    </x-ui::form.textarea>

    <x-ui::form.error :id="$errorId" :for="$name" />

</div>
