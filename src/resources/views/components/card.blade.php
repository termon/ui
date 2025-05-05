<div {{ $attributes->merge(['class' => 'px-6 py-4 shadow-lg overflow-hidden rounded-lg border border-gray-200  dark:bg-gray-800 dark:border-gray-700 ']) }}>

    @isset($header)
        <div 
            {{-- {{ $header->attributes->except('class') }} --}}
            {{ $header->attributes->class([
                'pt-4 pb-2 mb-2 border-b border-gray-800 ',
                'text-xl',      // => !preg_match('/\btext-(xs|sm|base|lg|xl|\d+xl)\b/',$header->attributes->get('class', '')),
                'font-semibold' // => !preg_match('/\bfont-\w+\b/', $header->attributes->get('class', '')),
            ]) }}>
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div 
            {{-- {{ $footer->attributes->except('class') }} --}}
            {{ $footer->attributes->class([
                'py-2 mt-2 border-t border-gray-800',
                'font-normal',  // =>  !preg_match('/\btext-(xs|sm|base|lg|xl|\d+xl)\b/', $footer->attributes->get('class', '')),
                'text-md'       // => !preg_match('/\bfont-\w+\b/', $footer->attributes->get('class', '')),
            ]) }}>
            {{ $footer }}
        </div>
    @endisset

</div>
