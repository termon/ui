@props([
   'label' => null,
   'name',
   'value'   
])

<div {{$attributes->only(['class'])}}>
    @isset($label)
        <label for="{{$name}}" class="mb-2 block text-gray-700 text-sm font-bold uppercase">
            {{ $label }}
        </label>
    @endisset

    <textarea id="{{$name}}" name="{{$name}}" {{ $attributes->merge(['class'=>'border rounded-md w-full p-2.5 text-gray-700 leading-tight focus:ring-blue-500 focus:border-blue-500']) }}>{{ $value }}</textarea>
    
    @error($name)
        <div class="text-sm text-red-500 mt-2"> 
            {{ $message }} 
        </div>
    @enderror 
</div> 
