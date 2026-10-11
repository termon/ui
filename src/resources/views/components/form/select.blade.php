@props([
    'name',
    'id' => null,
    'value' => null,
    'options' => [],
    'placeholder' => 'Choose option...',
])

@php
    $inputId = $id ?? $name;
    $selectedValues = is_array($value) ? array_map('strval', $value) : null;
@endphp

<select id="{{ $inputId }}" name="{{ $name }}"
    {{ $attributes->except(['id', 'icon', 'label'])->merge([
        'class' => 'block w-full appearance-none rounded-lg border border-slate-300 bg-white p-2.5 text-slate-900 shadow-xs transition focus:border-blue-600 focus:outline-none focus:ring-3 focus:ring-blue-600/15 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20 dark:disabled:bg-slate-900'
    ]) }}>

    <option value="" @selected(blank($value) && !$attributes->has('multiple')) disabled>
        {{ $placeholder }}
    </option>

    @foreach ($options as $key => $label)
        <option value="{{ $key }}" @selected($selectedValues !== null ? in_array((string) $key, $selectedValues, true) : (filled($value) && $key == $value))>
            {{ $label }}
        </option>
    @endforeach
</select>
