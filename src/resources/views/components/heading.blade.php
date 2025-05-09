@props([
    'level' => '1',
])
@php
    $tag = 'h' . $level;
    $classes = match ($level) {
        '1' => 'text-3xl  md:text-4xl lg:text-5xl font-extrabold dark:text-gray-100',
        '2' => 'text-2xl  md:text-3xl lg:text-4xl font-bold      dark:text-gray-100',
        '3' => 'text-xl   md:text-2xl lg:text-3xl font-bold      dark:text-gray-100',
        '4' => 'text-base md:text-xl  lg:text-2xl font-semibold  dark:text-gray-100',
        '5' => 'text-sm   md:text-md  lg:text-xl font-semibold   dark:text-gray-100',
        default => throw new \Exception("heading: No such level {$level}"),
    };
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</{{ $tag }}>