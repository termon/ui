@props([
    'position' => 'right',
    'title' => null,
    'icon' => null,
])

<div x-data="{ open: false }" class="relative text-left">

    <div x-on:click="open=true"
        class="flex items-center gap-1 px-3 py-2 transition-colors duration-300  hover:cursor-pointer text-gray-700 hover:bg-gray-100 rounded-lg whitespace-no-wrap">
       
        <!-- display title and optional title icon component classes only applied to title slot -->
        <div {{ $attributes->merge(["class" => "flex items-center justify-between gap-2"] )}} >
            @isset($icon)
                <x-ui::svg :variant="$icon" class="w-5 h-5" />
            @endisset
            {{ $title }}
        </div>
      
         <!-- open close icon -->          
        <x-ui::svg variant="chevron-down" class="w-3 h-3" x-bind:class="{ 'rotate-180': open }" />
                  
    </div>

    <div x-show="open" x-on:click.away="open=false "
        class="{{ $position == 'left' ? 'right-0' : 'left-0' }} absolute mt-1 w-60 min-w-max divide-y divide-gray-100 rounded-md bg-white px-1.5 py-1.5 text-gray-700 shadow-lg">
        {{ $slot }}
    </div>
</div>
