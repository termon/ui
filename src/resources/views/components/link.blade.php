@props(['variant' => 'link', 'href' => '#', 'label' => null, 'icon' => null])
@php
    $classes = match ($variant) {
        'blue'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-gray-100 bg-blue-700   border border-blue-800   hover:bg-blue-900   hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-blue-900   active:bg-blue-800  dark:text-gray-300  dark:bg-blue-900   dark:hover:bg-blue-800 focus:outline-none dark:focus:ring-blue-500 dark:border-gray-700 ',
        'red'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-gray-100 bg-red-700    border border-red-800    hover:bg-red-800    hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-red-900    active:bg-red-800   dark:text-gray-300  dark:bg-red-900    dark:hover:bg-red-800 focus:outline-none dark:focus:ring-red-500 dark:border-gray-700 ',
        'green'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-gray-100 bg-green-700  border border-green-800  hover:bg-green-800  hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-green-900  active:bg-green-800 dark:text-gray-300  dark:bg-green-900  dark:hover:bg-green-800 focus:outline-none dark:focus:ring-green-500 dark:border-gray-700 ',
        'yellow'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-gray-100 bg-yellow-600 border border-yellow-800 hover:bg-yellow-700 hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-yellow-900 active:bg-yellow-800 dark:text-gray-300 dark:bg-yellow-900 dark:hover:bg-yellow-800 focus:outline-none dark:focus:ring-yellow-500  dark:border-gray-700 ',
        'dark'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-gray-100 bg-gray-900   border border-gray-200   hover:bg-gray-700   hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-gray-900   active:bg-gray-200 dark:text-gray-300   dark:bg-gray-900   dark:hover:bg-gray-800 focus:outline-none dark:focus:ring-gray-700 dark:border-gray-700 ',
        'light'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-gray-900 bg-gray-50    border border-gray-200   hover:bg-gray-200   hover:text-black disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-gray-200   active:bg-gray-200 dark:bg-gray-600     dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 ',
        
        'oblue'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-blue-600 bg-gray-50    border border-gray-200   hover:bg-blue-600   hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-blue-600   active:bg-gray-200 dark:bg-gray-300 dark:hover:bg-blue-500 dark:focus:ring-blue-800 dark:border-gray-500  dark:hover:text-white ',
        'ored'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium rounded-lg text-red-600  bg-gray-50    border border-gray-200   hover:bg-red-600    hover:text-white disabled:opacity-50 focus:outline-none focus:ring-1 focus:ring-red-600    active:bg-gray-200 dark:bg-gray-300 dark:hover:bg-red-500  dark:focus:ring-red-800  dark:border-gray-500  dark:hover:text-white ',
        
        'link'
            => 'cursor-pointer py-1.5 px-4 transition-colors font-medium text-gray-900 hover:text-bold hover:text-black disabled:opacity-50 hover:underline dark:text-gray-100  dark:hover:text-white',

        'none' => '',
        default => throw new \Exception("No such button variant: $variant"),
    };
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => $classes])->class(['flex items-center justify-center gap-1' => isset($icon)]) }}>   
    @isset($icon)
        <x-ui::svg :icon="$icon"  />
        <span class="hidden md:inline">{{ $label ?? $slot }}</span>
    @else
        {{ $label ?? $slot }}
    @endisset
</a>
