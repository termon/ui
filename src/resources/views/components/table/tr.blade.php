@props(['hover' => false])
<tr {{$attributes->merge(['class' => $hover ? 'hover:bg-gray-50' : ''])}}>
    {{$slot}}
</tr>

