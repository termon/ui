@props([
    'for',
    'action' => 'open',
    'component' => 'ui::button'
])

<x-dynamic-component
    :component="$component"
    x-data
    x-on:click="$dispatch('{{ $action }}-modal', {{ \Illuminate\Support\Js::from($for) }})"
    {{ $attributes }}
>
    {{ $slot }}
</x-dynamic-component>
