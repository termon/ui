@props(['href' => '#', 'icon' => null, 'active' => null, 'method' => null])

@if (isset($method))
    <form method="post" action="{{ $href }}" class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
        @csrf
        @method($method)
        @if ($icon)
            <x-ui::svg :variant="$icon" class="w-5 h-5" />
        @endif
        <button type="submit" class="hover:cursor-pointer">{{ $slot }}</button>
    </form>
@else
    <a href="{{$href}}" {{ $attributes->merge(['class' => "flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"])->class(['font-semibold' => Route::is($active)]) }}>
        @if ($icon)
            <x-ui::svg :variant="$icon" class="w-5 h-5" />
        @endif
        {{$slot}}
    </a>
@endif