
<div {{ $attributes->only('class') }}>
    @isset($attributes['label'])
        <x-ui::form.label for="{{ $attributes['name'] }}" class="mb-2">
            {{$attributes['label']}}
        </x-ui::form.label>        
    @endisset
    <x-ui::form.input {{ $attributes->except(['class', 'label']) }} /> 
    <x-ui::form.error name="{{$attributes['name']}}" class="mt-2"/>  
</div>
