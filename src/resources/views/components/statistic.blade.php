@props(['title', 'value', 'variant' => 'dark', 'description' => null, 'icon' => null ])
@php
    $classes = match ($variant) {
        'blue'
            => 'text-blue-800 dark:text-blue-500',
        'red'
            => 'text-red-800 dark:text-red-500',
        'green'
            => 'text-green-800 dark:text-green-500',
        'yellow'
            => 'text-yellow-600 dark:text-yellow-300', 
        'pink'
            => 'text-pink-500 dark:text-pink-300',        
        'light'
            => 'text-gray-500 dark:text-gray-300',
        'dark'
            => 'text-gray-900 dark:bg-gray-500 dark:text-gray-900"',
        default => throw new \Exception("No such button variant: $variant"),
    };
@endphp
<!-- inline-block w-auto h-auto take up only space needed -->
<div {{ $attributes->merge(["class" => "inline-block w-auto h-auto shadow-lg rounded-lg p-6 dark:bg-gray-800"]) }}>
    <div class="font-semibold text-gray-500 dark:text-gray-300 text-base mb-2">{{ $title }}</div>
    <div @class(["flex items-center gap-5 text-xl md:text-4xl font-bold py-3", $classes])>   
        {{ $value ?? $slot}}
        @isset($icon)
            <x-ui::svg size="md" :variant="$icon" @class=([$classes]) />
        @endisset    
    </div>
    @if($description)
        <div class="text-gray-500 dark:text-gray-400 text-sm mt-2">{{ $description }}</div>
    @endif
</div>