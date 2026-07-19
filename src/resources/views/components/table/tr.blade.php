@props(['hover' => true])
<tr {{ $attributes->class(['transition-colors hover:bg-sky-50/60 dark:hover:bg-slate-700/70' => $hover]) }}>
    {{$slot}}
</tr>
