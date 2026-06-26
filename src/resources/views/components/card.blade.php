<div {{ $attributes->merge(['class' => 'px-6 py-4 shadow-lg overflow-hidden rounded-lg border border-gray-200  dark:bg-gray-800 dark:border-gray-700 ']) }}>

    @isset($header)
        @php
            $headerAttributes = $header instanceof \Illuminate\View\ComponentSlot
                ? $header->attributes
                : new \Illuminate\View\ComponentAttributeBag;
        @endphp

        <div            
            {{ $headerAttributes->class([
                'pt-4 pb-2 mb-2 border-b border-gray-800 '              
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
                'py-2 mt-2 border-t border-gray-800'                
            ]) }}>
            {{ $footer }}
        </div>
    @endisset

</div>
