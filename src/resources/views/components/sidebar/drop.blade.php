@props(['title' => null, 'icon' => null])

<div x-data="{ open: false }" class="space-y-0 w-full">
    <!-- trigger -->
    <div class="flex items-center justify-between px-3 py-2 transition-colors duration-300 hover:cursor-pointer text-gray-700 hover:bg-gray-100 rounded-lg pe-1" @click="open = !open">
        
        <!-- display title and optional title icon component classes only applied to title slot -->
        <div  {{ $attributes->merge(["class" => "flex items-center justify-between gap-2"] )}} >
            @isset($icon)
                <x-ui::svg :variant="$icon" class="w-5 h-5" />
            @endisset
            {{ $title }}
        </div>      

       <!-- open close icon -->      
        <x-ui::svg variant="chevron-down" class="w-3 h-3" x-bind:class="{ 'rotate-180': open }" />
    </div>

    <!-- content -->
    <div x-show="open" x-transition:enter.duration.600ms x-transition:leave.duration.400ms class="pl-5 space-y-1">
        {{ $slot }}
    </div>
</div>
