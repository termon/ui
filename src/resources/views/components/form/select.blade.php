@props([
   'label' => null,
   'name',
   'value' => null,
   'options' => []   
])
 
<div>
    @isset($label)
        <label for="{{$name}}" class="mb-2 block text-gray-700 text-sm font-bold uppercase">
            {{ $label }}
        </label>
    @endisset
    <select id="{{ $name }}" name="{{$name}}" {{$attributes->merge(['class'=>'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'])}}>
        <option selected disabled>Choose option...</option>
        @foreach($options as $key => $val ) 
            <option value="{{ $key }}"  {{ $key == $value ? 'selected' : ''}}>{{ $val }}</option>
        @endforeach     
    </select>   
    @error($name)
        <div class="text-sm text-red-500 mt-2"> 
            {{ $message }} 
        </div>
    @enderror 
</div>