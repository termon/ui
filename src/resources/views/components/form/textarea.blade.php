@props([   
    'name',
    'value' => null,
])

<textarea id="{{ $name }}" name="{{ $name }}"
    {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-slate-300 bg-white p-2.5 leading-tight text-slate-800 shadow-xs transition placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-3 focus:ring-blue-600/15 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 dark:focus:border-blue-500 dark:focus:ring-blue-500/20 dark:disabled:bg-slate-900']) }}>
{{ $value ?? $slot }}
</textarea>
