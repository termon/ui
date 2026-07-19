@props([
    'type' => 'top', // 'top' or 'bottom'
])
@php
    if ($type != 'top' && $type != 'bottom') throw new \Exception("Invalid divider {$type}");
@endphp
<div {{ $attributes->class(['my-5 flex items-end justify-between gap-4 border-slate-200 dark:border-slate-700', 'border-b pb-3' => $type === 'top', 'border-t pt-3' => $type === 'bottom']) }}>
    {{ $slot }}
</div>
