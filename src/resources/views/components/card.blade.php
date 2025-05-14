<div {{ $attributes->merge(['class' => 'px-6 py-4 shadow-lg overflow-hidden rounded-lg border border-gray-200  dark:bg-gray-800 dark:border-gray-700 ']) }}>

    @isset($header)
        <div            
            {{ $header->attributes->class([
                'pt-4 pb-2 mb-2 border-b border-gray-800 '              
            ]) }}>
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div 
            {{ $footer->attributes->class([
                'py-2 mt-2 border-t border-gray-800'                
            ]) }}>
            {{ $footer }}
        </div>
    @endisset

</div>
