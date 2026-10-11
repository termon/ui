@props([
    'name',
    'id' => null,
    'label' => null,
    'length' => 6,
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
        <x-ui::form.label for="{{ $inputId }}-1" class="mb-2" :icon="$icon">
            {{ $label }}
        </x-ui::form.label>
    @endisset

    <x-ui::form.otp
        :id="$inputId"
        name="{{ $name }}"
        :length="$length"
        {{ $attributes->except(['class', 'name', 'label', 'length', 'icon']) }}
    />

    <x-ui::form.error :id="$errorId" :for="$name" />
</div>
