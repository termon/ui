@props(['href' => '#', 'icon' => null, 'as' => null])

@if($as === 'button')
    <button {{ $attributes->merge(['class' => "flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg"]) }}>
        @if ($icon)
            <x-ui::svg :variant="$icon" class="w-5 h-5" />
        @endif
        {{ $slot }}
    </button>
@else
    <a href="{{$href}}" {{ $attributes->merge(['class' => "flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg"]) }}>
        @if ($icon)
            <x-ui::svg :variant="$icon" class="w-5 h-5" />
        @endif
        {{$slot}}
    </a>
@endif