@props([
    'name' => 'Name',
    'value' => ''
])

<div {{$attributes->merge(['class' => 'flex border-b border-slate-200 sm:py-2 md:py-3 rounded-md'])}}>
    <span class="font-bold text-black dark:text-white mr-5">
        {{$name}}
    </span>
    <span class="text-slate-500 dark:text-slate-100 mr-5">
        {{$value}}
    </span>
</div>

