@props(['hover' => false])
<tr {{$attributes->merge(['class' => $hover ? 'hover:bg-gray-50 dark:hover:bg-gray-700' : 'py-3'])}}>
    {{$slot}}
</tr>

