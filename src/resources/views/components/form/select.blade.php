@props([
    'label' => null,
    'name',
    'value' => null,
    'options' => [],
    'placeholder' => 'Choose option...',
])
@isset($label)
    <x-ui::form.label for="{{ $name }}">
        {{ $label }}
    </x-ui::form.label>
@endisset


<select id="{{ $name }}" name="{{ $name }}"
    {{-- classes appearance-none and bg-transparent are needed by safari --}}
    {{ $attributes->merge(['class' => 'appearance-none bg-transparent border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }}>
    <option value="" {{ $value ? '' : 'selected' }} disabled >{{ $placeholder }}</option>
    @foreach ($options as $key => $val)
        <option value="{{ $key }}" {{ $key == $value ? 'selected' : '' }}>{{ $val }}</option>
    @endforeach
</select>

@error($name)
    <x-ui::form.error>
        {{ $message }}
    </x-ui::form.error>
@enderror
