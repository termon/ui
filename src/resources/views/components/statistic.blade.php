@props(['title', 'value', 'variant' => 'dark', 'description' => null, 'icon' => null ])
@php
    $variantClasses = match ($variant) {
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
<!-- inline-block w-auto h-auto mx-auto take up only space needed OR leave out and let user pass down specific size -->
<div {{ $attributes->merge(["class" => "shadow-lg rounded-lg p-6 dark:bg-gray-800"]) }}>

    <!-- title is required -->
    <div class="font-semibold text-gray-500 dark:text-gray-300 text-base mb-2">{{ $title }}</div>
    
    <!-- display value or slot icon is optional -->
    <div @class([
        "flex items-center justify-between" => isset($icon),      
        "text-xl md:text-4xl font-bold py-3",
        $variantClasses
    ])>   
        {{ $value ?? $slot}}
        @isset($icon)
            <x-ui::svg size="md" :icon="$icon" @class=([$classes]) />
        @endisset    
    </div>

    <!-- description is optional -->
    @if($description)
        <div class="text-gray-500 dark:text-gray-400 text-sm mt-2">{{ $description }}</div>
    @endif
</div>