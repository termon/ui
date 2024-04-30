
<div {{ $attributes->merge(['class' => "w-full px-6 py-4 shadow-lg overflow-hidden sm:rounded-lg border border-gray-200"])}}>

    @isset($header)
        <div {{ $header->attributes->merge(['class'=>"pt-4 pb-2 mb-2 border-b border-gray-800"])}}>
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div {{ $footer->attributes->merge(['class'=>"py-4 mt-2 border-t border-gray-800"])}}>
            {{ $footer }}
        </div>
    @endisset

</div>


