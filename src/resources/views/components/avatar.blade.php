@props([ 'size' => 'xs'])

@php 
$size = match($size) {
   'xs' => "w-6 h-6",
   'sm' => "w-8 h-8",
   'md' => "w-10 h-10",
   'lg' => "w-12 h-12",
   default => throw new \Exception("No such avatar size: $size" ),
}
@endphp

<div {{ $attributes->merge(['class' => "overflow-hidden bg-gray-100 rounded-full text-gray-700 inline-flex items-center justify-center  $size"])}} >
    <svg class="{{'text-gray-300 ' . $size}}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
</div>
