@props([
    'name', 
    'value' => false, 
    'variant' => 'oblue'
])

@php
    // get the wrapper classes or default to an empty string
    $wrapperClasses = $attributes->get('class','flex items-center gap-3');

    $variantClasses = match ($variant) {
        'light' => 'bg-gray-200 text-gray-900 dark:bg-gray-600 dark:text-gray-100',
        'oblue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200',
        'blue' => 'bg-blue-700 text-white dark:bg-blue-900 dark:text-gray-100',
        'gray' => 'bg-gray-500 text-white dark:bg-gray-700 dark:text-gray-100',
        'dark' => 'bg-gray-900 text-white dark:bg-gray-700 dark:text-gray-100',
        'green' => 'bg-green-500 text-white dark:bg-green-700 dark:text-gray-100',
        'red' => 'bg-red-500 text-white dark:bg-red-700 dark:text-gray-100',
        'yellow' => 'bg-yellow-400 text-gray-900 dark:bg-yellow-600 dark:text-gray-100',
        'purple' => 'bg-purple-700 text-white dark:bg-purple-900 dark:text-gray-100',
        default => throw new \Exception("No such input file variant: $variant"),
    };

    $offClasses = 'bg-gray-300 text-white dark:bg-gray-400 dark:text-white';
@endphp

<div x-data="{ on: @js((bool) $value) }" {{ $attributes->except('class')->merge() }} @class($wrapperClasses)>

    <button
        type="button"
        id="{{ $name }}"
        role="switch"
        :aria-checked="on"
        @click="on = !on"
        :class="on ? @js($variantClasses) : @js($offClasses)"
        class="relative inline-flex my-2 h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 dark:ring"
    >
        <span :class="on ? 'translate-x-6' : 'translate-x-1'"
            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
    </button>

    {{-- Hidden input to send the value with form submissions --}}
    <input type="hidden" name="{{ $name }}" :value="on ? 1 : 0">
</div>
   
