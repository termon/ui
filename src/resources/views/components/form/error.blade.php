@props(['for'])

@if(isset($for))
    @error($for)
        <div {{ $attributes->merge(['class' => 'mt-1 text-sm font-medium text-rose-600 dark:text-rose-400']) }} aria-describedby="{{ $for }}-error">{{ $message }}</div>
    @enderror
@else
    <div {{ $attributes->merge(['class' => 'mt-1 text-sm font-medium text-rose-600 dark:text-rose-400']) }}>{{ $slot }}</div>
@endif
