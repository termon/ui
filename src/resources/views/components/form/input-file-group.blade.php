<div {{ $attributes->only('class') }} }}>
    @isset($attributes['label'])
        <x-ui.form.label for="{{$attributes['name']}}" class="mb-2">{{$attributes['label']}}</x-ui.form.label>    
    @endisset
    <x-ui.form.input-file {{$attributes->except('class')}} /> 
    <x-ui.form.error name="{{$attributes['name']}}" class="mt-2"/>  
</div>
