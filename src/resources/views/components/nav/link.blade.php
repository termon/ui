@props(['active'=>null])

{{-- transition-colors duration-300 text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-black'])->class(['font-semibold border-green-400' => Route::is($active)]) --}}
<a {{ $attributes->merge(['class' => 'transition-colors duration-300 text-gray-700 hover:text-gray-900 hover:rounded-md hover:font-semibold block hover:bg-gray-200 whitespace-no-wrap py-2 px-4'])->class(['font-semibold' => Route::is($active)]) }}>  
   {{ $slot }}
</a>

