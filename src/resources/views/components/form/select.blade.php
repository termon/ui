<select id="{{ $attributes['name'] }}" 
        {{$attributes->merge(['class'=>'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'])
                     ->class(['border', 'border-red-200' => $errors->has($name)])
                     ->except('options')}}
>
    <option value="">Choose option</option>
    @isset($attributes['options'])
        @foreach($attributes['options'] as $key => $name ) 
            <option value="{{ $key }}"  {{ $key == $attributes['value'] ? 'selected' : ''}}>{{ $name }}</option>
        @endforeach     
    @endisset
</select>

