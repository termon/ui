@props([
   'name',
   'label' => null,
   'value' => null,
   'variant' => 'light',
   'type' => 'text',
])

@php
// default styles for an input
$classes = 'border rounded-md w-full p-2.5 text-gray-700 leading-tight focus:ring-blue-500 focus:border-blue-500 ';

// updated styles for file input
if ($type === 'file') {
    $base = "bg-white block w-full text-sm rounded-lg border border-gray-300 cursor-pointer focus:outline-none file:mr-2 file:py-2 file:px-3 file:rounded-l-md file:border-0 file:text-sm file:font-semibold hover:file:cursor-pointer hover:file:opacity-80";
    $classes = match ($variant) {
        'light'  => "$base file:bg-gray-100   file:text-gray-900",
        'blue'   => "$base file:bg-blue-700   file:text-white",
        'gray'   => "$base file:bg-gray-500   file:text-white",
        'dark'   => "$base file:bg-gray-900   file:text-white",
        'green'  => "$base file:bg-green-500  file:text-white",
        'red'    => "$base file:bg-red-500    file:text-white",
        'yellow' => "$base file:bg-yellow-400 file:text-white",
        'purple' => "$base file:bg-purple-700 file:text-white",
        default => throw new \Exception("No such input file variant: $variant" ),
    };
}
@endphp

@isset($label)
    <label for="{{$name}}" class="mb-2 block text-gray-700 text-sm font-bold uppercase">
        {{ $label }}
    </label>
@endisset

<input type={{$type}} id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['class'=> $classes])}}>

@error($name)
    <div class="text-sm text-red-500 mt-2"> 
        {{ $message }} 
    </div>
@enderror 

