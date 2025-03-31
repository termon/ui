@props(['label', 'value'=>null])

<div {{ $attributes->merge(['class' => 'flex border-b items-center border-slate-200 sm:py-2 md:py-3 rounded-md']) }}>
    @isset($label)
        <span class="font-bold mr-5">
            {{ $label }}
        </span>
    @endisset

    @if (isset($value))
        <span class="text-slate-600 dark:text-slate-100">
            {{ $value }}
        </span>
    @else
        <div {{ $slot->attributes->merge(['class' => 'text-slate-600 dark:text-slate-100']) }}>
            {{ $slot }}
        </div>
    @endif
</div>
