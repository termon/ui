@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'icon' => null,
])

@php
    $inputId = $id ?? $name;
    $errorId = $inputId.'-error';
    $errorKey = str_replace([']', '['], ['', '.'], $name);
    if ($errors->has($errorKey)) {
        $describedBy = trim($attributes->get('aria-describedby', '').' '.$errorId);
        $attributes = $attributes->except(['aria-describedby', 'aria-invalid'])->merge([
            'aria-describedby' => $describedBy,
            'aria-invalid' => 'true',
        ]);
    }
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}>
    @isset($label)
        <x-ui::form.label :for="$inputId" class="mb-1" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.datetime
        :id="$inputId"
        name="{{ $name }}"
        :value="$value"
        {{ $attributes->except(['class', 'name', 'label', 'value', 'icon']) }}
    />

    <x-ui::form.error :id="$errorId" :for="$errorKey" />
</div>
