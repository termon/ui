@props([
    'icon',
    'variant' => ''])
@php
    $baseClass = 'shrink-0';

    // Determine variant prefix
    // $variantPrefix = match($variant) {
    //     'mini'  => 'heroicon-m-',
    //     'micro' => 'heroicon-s-',
    //     default => 'heroicon-o-', // standard
    // };
    $baseClass .= match($variant) {
        'mini'  => 'w-4 h-4',
        'micro' => 'w-3 h-3',
        default => 'w-5 h-5', // standard
    };

    // $componentName = $variantPrefix . $icon;
@endphp

<x-ui::svg 
    :icon="$icon" 
    :class="$baseClass" 
    {{ $attributes->merge(['class' => $baseClass]) }}
/>

{{-- <x-dynamic-component 
    :component="$componentName" 
    {{ $attributes->merge(['class' => $baseClass]) }} 
/> --}}
