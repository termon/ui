@props(['for'])

@if(isset($for))
    @error($for)
        <div {{ $attributes->merge(['class' => 'text-sm text-red-500']) }} aria-describedby="{{ $for }}-error">{{ $message }}</div>
    @enderror
@else
    <div {{ $attributes->merge(['class' => 'text-sm text-red-500']) }}>{{ $slot }}</div>
@endif
