@props(['title' => null, 'icon' => null])

<div x-data="{ open: false }" class="space-y-0 w-full">
    <!-- trigger -->
    <div class="flex items-center justify-between hover:cursor-pointer hover:bg-gray-100 rounded-lg pe-1" @click="open = !open">
        
        <!-- display header slot if available otherwise default title/icon -->
        @if(isset($header))
            <div class="text-sm font-medium text-gray-700 flex items-center gap-2">{{ $header }}</div>
        @else
            <div class="flex items-center justify-between gap-2 px-3 py-2 text-sm font-medium text-gray-700">
                @isset($icon)
                    <x-ui::svg :variant="$icon" class="w-5 h-5" />
                @endisset
                <span>{{ $title }}</span>
            </div>
        @endif
       <!-- open close icon -->      
        <x-ui::svg variant="chevron-down" class="w-5 h-5" x-bind:class="{ 'rotate-180': open }" />
    </div>

    <!-- content -->
    <div x-show="open" x-transition:enter.duration.600ms x-transition:leave.duration.400ms class="pl-5 space-y-1">
        {{ $slot }}
    </div>
</div>
