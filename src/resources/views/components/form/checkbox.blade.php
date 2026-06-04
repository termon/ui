@props([
    'name',
    'value' => '1',
    'label' => null,
    'description' => null,
    'checked' => false,
    'id' => null,
])

@php
    $inputId = $id ?? str($name.'-'.$value)->replaceMatches('/[^A-Za-z0-9\-_:.]/', '-')->trim('-')->value();
@endphp

<label for="{{ $inputId }}" {{ $attributes->only('class')->class(['flex items-start gap-3']) }}>
    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="checkbox"
        value="{{ $value }}"
        @checked($checked)
        {{ $attributes->except(['class', 'value', 'label', 'description', 'checked', 'id']) }}
        class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-400 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:focus:ring-zinc-500"
    />

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif (filled($label) || filled($description))
        <span>
            @if (filled($label))
                <span class="block font-medium text-zinc-900 dark:text-zinc-50">{{ $label }}</span>
            @endif

            @if (filled($description))
                <span class="mt-1 block text-xs text-zinc-500 dark:text-zinc-400">{{ $description }}</span>
            @endif
        </span>
    @endif
</label>
