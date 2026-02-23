@props(['for'])

@error($for)
    <div {{ $attributes->merge(['class' => 'text-sm text-red-500']) }} aria-describedby="{{ $for }}-error">{{ $message }}</div>
@enderror
