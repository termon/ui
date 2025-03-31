@props(['title', 'value' => null, 'description' => null, 'variant' => 'dark' ])
@php
    $classes = match ($variant) {
        'blue'
            => 'text-blue-900',
        'red'
            => 'text-red-900',
        'green'
            => 'text-green-900',
        'yellow'
            => 'text-yellow-500', 
        'pink'
            => 'text-pink-500',        
        'light'
            => 'text-gray-500',
        'dark'
            => 'text-gray-900',
        default => throw new \Exception("No such button variant: $variant"),
    };
@endphp

<div {{ $attributes->merge(["class" => "shadow-lg rounded-lg p-6"])}}>
    <div class="text-gray-500 text-sm mb-2">{{ $title }}</div>
    @if($value)
       <div class="text-4xl font-bold">{{$value}}</div>
    @else
        <div {{ $slot->attributes->merge(["class" => "text-4xl font-bold {$classes}"]) }}>{{$slot}}</div>
    @endif
       @if($description)
        <div class="text-gray-500 text-sm mt-2">{{ $description }}</div>
    @endif
</div>