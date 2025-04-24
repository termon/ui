@props(['label', 'value'=>null])

<div {{ $attributes->merge(['class' => 'flex border-b items-center border-slate-200 dark:border-slate-500 py-2 md:py-3 rounded-md ']) }}>
    @isset($label)
        <span class="font-semibold mr-5">
            {{ $label }}
        </span>
    @endisset

    @if (isset($value))
        <span class="text-slate-600 dark:text-slate-300">
            {{ $value }}
        </span>
    @else
        <div {{ $slot->attributes->merge(['class' => 'text-slate-600 dark:text-slate-300']) }}>
            {{ $slot }}
        </div>
    @endif
</div>