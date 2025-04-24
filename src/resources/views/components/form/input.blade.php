@props(['name', 'label' => null, 'value' => null, 'variant' => 'light', 'type' => 'text'])

@php
    // default styles for an input
    $classes = 'border border-gray-300 rounded-lg w-full p-2.5 text-gray-700 leading-tight focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 ';

    // updated styles for file input
    if ($type === 'file') {
        $base =
            'bg-white block w-full rounded-lg border border-gray-300 cursor-pointer focus:outline-none file:mr-2 file:py-2 file:px-3 file:rounded-l-md file:border-0 file:font-semibold hover:file:cursor-pointer hover:file:opacity-80 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400';
        $classes = match ($variant) {
            'light' => "$base file:bg-gray-100   file:text-gray-900 dark:file:bg-gray-500 dark:file:text-gray-100",
            'oblue' => "$base file:bg-blue-100  file:text-blue-700 dark:file:bg-gray-500 dark:file:text-blue-900",
            'blue' => "$base file:bg-blue-700   file:text-white",
            'gray' => "$base file:bg-gray-500   file:text-white",
            'dark' => "$base file:bg-gray-900   file:text-white",
            'green' => "$base file:bg-green-500  file:text-white",
            'red' => "$base file:bg-red-500    file:text-white",
            'yellow' => "$base file:bg-yellow-400 file:text-white",
            'purple' => "$base file:bg-purple-700 file:text-white",
            default => throw new \Exception("No such input file variant: $variant"),
        };
    }
@endphp

@isset($label)
    <x-ui::form.label for="{{ $name }}">
        {{ $label }}
    </x-ui::form.label>
@endisset

<input type={{ $type }} id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
    {{ $attributes->merge(['class' => $classes]) }}>

@error($name)
    <x-ui::form.error>{{ $message }}</x-ui::form.error>
@enderror
