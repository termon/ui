@props(['active' => ''])

@php
  $base = 'md:border-b-2 text-base font-medium p-1 transition duration-200 ease-in-out text-gray-700 hover:text-indigo-900 hover:border-indigo-900 focus:outline-none focus:text-indigo-900 focus:border-black dark:text-gray-400 dark:hover:text-gray-100 dark:hover:border-gray-500 dark:focus:text-gray-300 dark:focus:border-gray-100'; 
  $classes = request()->routeIs($active) ? "border-gray-400 dark:border-gray-600" : "border-transparent";
@endphp

<a {{$attributes->merge(['class'=> "{$base} {$classes}"])}}>
  {{$slot}}
</a>
    
  