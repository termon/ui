@props(['name', 'value' => null, 'variant' => 'light', 'type' => 'text'])

@php
    // get the wrapper classes or default to an empty string
    $wrapperClasses = $attributes->get('class','');

    $baseClasses = $type === 'file'
        ? 'block w-full cursor-pointer rounded-lg border border-slate-300 bg-white text-slate-700 shadow-xs transition file:mr-2 file:rounded-l-md file:border-0 file:px-3 file:py-2 file:font-semibold hover:file:cursor-pointer hover:file:opacity-80 focus:border-blue-600 focus:outline-none focus:ring-3 focus:ring-blue-600/15 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:placeholder-slate-400'
        : 'w-full rounded-lg border border-slate-300 bg-white p-2.5 leading-tight text-slate-800 shadow-xs transition placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-3 focus:ring-blue-600/15 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 dark:focus:border-blue-500 dark:focus:ring-blue-500/20 dark:disabled:bg-slate-900';

    $variantClasses = $type === 'file' ? match ($variant) {
        'light' => 'file:bg-gray-100 file:text-gray-900',
        'oblue' => 'file:bg-blue-100 file:text-blue-700',
        'blue' => 'file:bg-blue-700 file:text-white',
        'gray' => 'file:bg-gray-500 file:text-white',
        'dark' => 'file:bg-gray-900 file:text-white',
        'green' => 'file:bg-green-500 file:text-white',
        'red' => 'file:bg-red-500 file:text-white',
        'yellow' => 'file:bg-yellow-400 file:text-white',
        'purple' => 'file:bg-purple-700 file:text-white',
        default => throw new \Exception("No such input file variant: $variant"),
    } : '';
@endphp

<input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $value }}"        
        {{ $attributes->except('class')->merge() }}
        @class([
            $baseClasses,
            $variantClasses,
            $wrapperClasses,
        ])
>
