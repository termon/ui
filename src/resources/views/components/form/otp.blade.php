@props([
    'name',
    'length' => 6,
])

<div {{ $attributes->only('class') }}>
    <div x-data class="flex items-center gap-2">
        @foreach (range(1, $length) as $index)
            <x-ui::form.input
                id="{{ $name }}-{{ $index }}"
                name="{{ $name }}[]"
                class="w-8"
                maxlength="1"
                minlength="1"
                x-ref="i{{ $index }}"
                x-on:input="$refs['i{{ $index + 1 }}']?.focus()"
            />
        @endforeach
    </div>
</div>
