@props([
    'name',
    'id' => null,
    'icon' => null,
    'label' => null,
    'value' => null,
    'options' => [],
    'placeholder' => 'Choose option...',
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

    <x-ui::form.select
        :id="$inputId"
        :name="$name"
        :value="$value"
        :options="$options"
        :placeholder="$placeholder"
        {{ $attributes->except(['class', 'id']) }}
    />

    <x-ui::form.error :id="$errorId" :for="$name" />
</div>
