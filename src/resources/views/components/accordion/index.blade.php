@props(['open' => null])

<div
    x-data="{ open: @js($open) }"
    {{ $attributes->class(['space-y-3']) }}
>
    {{ $slot }}
</div>
