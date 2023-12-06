
<div {{ $attributes->merge(['class' => "w-full px-6 py-4 shadow-lg overflow-hidden sm:rounded-lg border border-gray-200"])}}>

    @if (isset($title))
        <div {{ $title->attributes->merge(['class'=>"pt-4 pb-2 mb-2 text-2xl font-bold border-b border-gray-800"])}}>
            {{ $title }}
        </div>
    @endif

    {{ $slot }}

    @if (isset($footer))
        <div {{ $footer->attributes->merge(['class'=>"py-4 mt-2 border-t border-gray-800"])}}>
            {{ $footer }}
        </div>
    @endif
</div>


