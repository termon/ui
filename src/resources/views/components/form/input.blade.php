
<input id="{{ $attributes['name'] }}"        
       {{ $attributes->merge(["class" => "border rounded-md w-full p-2.5 text-gray-700 leading-tight focus:ring-blue-500 focus:border-blue-500 "])
                     ->class(['border', 'border-red-200' => $errors->has($attributes['name']) ])}}
>