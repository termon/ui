@props([
   'label' => null,
   'name',
   'value' => null  
])
 
<div>
    @isset($label)
        <label for="{{$name}}" class="mb-2 block text-gray-700 text-sm font-bold uppercase">
            {{ $label }}
        </label>
    @endisset

    <input id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" 
    {{ $attributes->merge(['class'=>'border rounded-md w-full p-2.5 text-gray-700 leading-tight focus:ring-blue-500 focus:border-blue-500 '])}}>

    @error($name)
        <div class="text-sm text-red-500 mt-2"> 
            {{ $message }} 
        </div>
    @enderror 
</div> 
