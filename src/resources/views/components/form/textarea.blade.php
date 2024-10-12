@props([
    'label' => null,
    'name',
    'value' => null,
])

@isset($label)
    <x-ui.form.label for="{{ $name }}">
        {{ $label }}
    </x-ui.form.label>
@endisset

<textarea id="{{ $name }}" name="{{ $name }}"
    {{ $attributes->merge(['class' => 'border rounded-md w-full p-2.5 text-gray-700 leading-tight focus:ring-blue-500 focus:border-blue-500']) }}>{{ $value }}</textarea>

@error($name)
    <x-ui.form.error>
        {{ $message }}
    </x-ui.form.error>
@enderror
