@props([
    'variant' => 'minus',
    'size' => 'md'
])
@php
$classes = match ($size) {
    'xs' => 'w-4 h-4',
    'sm' => 'w-5 h-5',
    'md' => 'w-6 h-6',
    'lg' => 'w-7 h-7',
    default => 'w-6 h-6'    
};
@endphp

@if ($variant == 'minus')
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" {{ $attributes->merge(['class' => $classes]) }}>
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
        {{ $slot }}
    </svg>  
@elseif ($variant == 'user')
    <!-- userplus -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  {{ $attributes->merge(['class' => $classes]) }}>
        <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
        {{$slot}}
    </svg>
@endif
