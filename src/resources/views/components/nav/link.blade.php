@props(['active'=>null])
{{-- {{Route::currentRouteName()}} {{Route::is($name) ? "Yes" : "No"}} --}}
<a {{ $attributes->merge(['class' => 'transition-colors duration-300 text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-black'])->class(['font-semibold' => Route::is($active)]) }}>  
   {{ $slot }}
</a>

