@props(['variant' => 'default'])

@php
    $variantClasses = match ($variant) {
        'default' => 'border-slate-200/80 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800',
        'soft' => 'border-slate-200/80 bg-slate-50 shadow-sm dark:border-slate-700 dark:bg-slate-800/70',
        'elevated' => 'border-slate-200/70 bg-white shadow-lg shadow-slate-900/8 dark:border-slate-700 dark:bg-slate-800 dark:shadow-black/20',
        'flat' => 'border-slate-200/80 bg-white shadow-none dark:border-slate-700 dark:bg-slate-800',
        default => throw new \Exception("No such card variant: {$variant}"),
    };
@endphp

<div {{ $attributes->class(["overflow-hidden rounded-xl border px-6 py-5 {$variantClasses}"]) }}>

    @isset($header)
        @php
            $headerAttributes = $header instanceof \Illuminate\View\ComponentSlot
                ? $header->attributes
                : new \Illuminate\View\ComponentAttributeBag;
        @endphp

        <div            
            {{ $headerAttributes->class([
                'mb-4 border-b border-slate-200 pb-3 dark:border-slate-700'
            ]) }}>
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        @php
            $footerAttributes = $footer instanceof \Illuminate\View\ComponentSlot
                ? $footer->attributes
                : new \Illuminate\View\ComponentAttributeBag;
        @endphp

        <div 
            {{ $footerAttributes->class([
                'mt-4 border-t border-slate-200 pt-3 dark:border-slate-700'
            ]) }}>
            {{ $footer }}
        </div>
    @endisset

</div>
