@props(['name'=>null])
<a {{ $attributes->merge(['class' => 'transition-colors duration-300 text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-black'])->class(['font-semibold' => Route::is($name)]) }}>  
   {{ $slot }}
</a>