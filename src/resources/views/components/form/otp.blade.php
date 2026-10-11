@props([
    'name',
    'id' => null,
    'length' => 6,
])

@php
    $inputId = $id ?? $name;
    $modelAttribute = collect(array_keys($attributes->getAttributes()))->first(fn ($key) => str_starts_with($key, 'wire:model'));
    $model = $modelAttribute ? $attributes->get($modelAttribute) : null;
    $inputAttributes = $attributes->except(array_filter(['class', 'id', 'name', $modelAttribute]));
@endphp

<div {{ $attributes->only('class') }}>
    <div x-data class="flex items-center gap-2">
        @for ($index = 0; $index < $length; $index++)
            @php
                $digitAttributes = $model
                    ? $inputAttributes->merge([$modelAttribute => $model.'.'.$index])
                    : $inputAttributes;
            @endphp
            <x-ui::form.input
                id="{{ $inputId }}-{{ $index + 1 }}"
                name="{{ $name }}[]"
                class="w-8"
                maxlength="1"
                minlength="1"
                x-ref="i{{ $index + 1 }}"
                x-on:input="$refs['i{{ $index + 2 }}']?.focus()"
                :attributes="$digitAttributes"
            />
        @endfor
    </div>
</div>
