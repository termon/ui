@props(['href' => '#', 'active' => null])
       
<a href="{{ $href }}"
    {{ $attributes->merge(['class' => "py-2 px-4 transition-colors duration-300 font-medium text-gray-700 hover:text-gray-900 hover:rounded-md hover:font-semibold block hover:bg-gray-100 whitespace-no-wrap"])
                  ->class(['font-semibold' => Route::is($active)]) }}>
    {{ $slot }}
</a>
