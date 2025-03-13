<nav {{ $attributes->merge(['class' => 'flex justify-between bg-white items-center px-4 py-2 border-b border-gray-200 transition-all duration-300']) }}>

    @isset($title)
    <div class="flex gap-1 lg:gap-2 items-center">
        {{ $title }}
    </div>
    @endisset
    
    <div class="flex gap-1 lg:gap-2 items-center">
        {{ $slot }}
    </div>

    @isset($end)
        <div class="flex gap-1 lg:gap-2 items-center">
            {{ $end }}
        </div>
    @endisset

</nav>
