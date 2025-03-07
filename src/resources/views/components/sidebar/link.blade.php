@props(['href' => '#', 'icon' => null ])

<a href="{{$href}}" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
    @if ($icon)
        <x-ui::svg :variant="$icon" class="w-5 h-5" />
    @endif
    {{$slot}}
</a>