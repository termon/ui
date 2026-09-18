@props([
    'variant' => 'link',
    'href' => '#',
    'label' => null,
    'icon' => null,
])

@php
    $base = 'inline-flex items-center transition-colors font-medium focus:outline-none';
    $variantBase = 'cursor-pointer py-1.5 px-4 rounded-lg border focus:ring-1';
    $variantClasses = match ($variant) {
        'primary', 'blue' => 'text-white bg-blue-700 border-blue-800
             hover:bg-blue-800 focus:ring-blue-900 active:bg-blue-900',

        'danger', 'red' => 'text-white bg-red-700 border-red-800
             hover:bg-red-800 focus:ring-red-900 active:bg-red-900',

        'success', 'green' => 'text-white bg-green-700 border-green-800
             hover:bg-green-800 focus:ring-green-900 active:bg-green-900',

        'warning', 'yellow' => 'text-white bg-yellow-600 border-yellow-700
             hover:bg-yellow-700 focus:ring-yellow-900 active:bg-yellow-800',

        'dark' => 'text-white bg-gray-900 border-gray-900
             hover:bg-gray-700 focus:ring-gray-900 active:bg-gray-800',

        'light' => 'text-gray-900 bg-gray-50 border-gray-200
             hover:bg-gray-200 focus:ring-gray-300 active:bg-gray-200',

        'outline-primary', 'oblue' => 'text-blue-700 bg-transparent border-blue-700
             hover:bg-blue-700 hover:text-white focus:ring-blue-600',

        'outline-danger', 'ored' => 'text-red-700 bg-transparent border-red-700
             hover:bg-red-700 hover:text-white focus:ring-red-600',

        'outline-success', 'ogreen' => 'text-green-700 bg-transparent border-green-700
             hover:bg-green-700 hover:text-white focus:ring-green-600',

        'outline-warning', 'oyellow' => 'text-yellow-700 bg-transparent border-yellow-700
             hover:bg-yellow-600 hover:text-white focus:ring-yellow-600',

        'link' => 'cursor-pointer px-1.5 py-1.5 text-gray-700
             hover:text-gray-900 hover:underline',

        'none' => '',

        default => throw new InvalidArgumentException("Unknown link variant: {$variant}"),
    };

    $classes = $base . ' ' . ($variant !== 'link' && $variant !== 'none' ? $variantBase : '') . ' ' . $variantClasses;
@endphp

<a href="{{ $href }}" {{ $attributes->class([$classes, 'gap-1' => $icon]) }}>

    @if ($icon)
        <x-ui::svg :icon="$icon" class="shrink-0" />

        {{-- Accessible label when visible text is hidden on small screens --}}
        <span class="sr-only md:hidden">
            {{ $label ?? $slot }}
        </span>

        {{-- Visible label from md upwards --}}
        <span class="hidden md:inline">
            {{ $label ?? $slot }}
        </span>
    @else
        {{ $label ?? $slot }}
    @endif
</a>
