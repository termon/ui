@props(['for' => null, 'id' => null])

@if(isset($for))
    @error($for)
        <div {{ $attributes->merge(['id' => $id ?? $for.'-error', 'class' => 'mt-1 text-sm font-medium text-rose-600 dark:text-rose-400']) }}>{{ $message }}</div>
    @enderror
@else
    <div {{ $attributes->merge(array_filter(['id' => $id, 'class' => 'mt-1 text-sm font-medium text-rose-600 dark:text-rose-400'], fn ($value) => $value !== null)) }}>{{ $slot }}</div>
@endif
