@props([
    'name',
    'id' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => 0,
    'variant' => 'light',
    'label' => null,
    'icon' => null,
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
        <x-ui::form.label :for="$inputId" class="mb-1" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.range
        :id="$inputId"
        name="{{ $name }}"
        :min="$min"
        :max="$max"
        :step="$step"
        :value="$value"
        variant="{{ $variant }}"
        {{ $attributes->except(['class', 'name', 'min', 'max', 'step', 'value', 'variant', 'label', 'icon']) }}
    />

    <x-ui::form.error :id="$errorId" :for="$name" />
</div>
