@props([
    'type' => 'top', // 'top' or 'bottom'
])
@php
    if ($type != 'top' && $type != 'bottom') throw new \Exception("Invalid divider {$type}");
@endphp
<div {{ $attributes->class(['flex justify-between items-baseline border-gray-700 dark:border-gray-500 my-5', 'border-b pb-2' => $type === 'top', 'border-t pt-2' => $type === 'bottom']) }}>
    {{ $slot }}
</div>
