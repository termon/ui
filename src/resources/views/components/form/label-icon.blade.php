@props([
    'icon' => null,
])

<label {{ $attributes->merge(['class' => "flex items-center gap-2 font-medium text-gray-500 dark:text-gray-400"]) }}>
    @isset($icon)
        <x-ui::svg :icon="$icon" class="w-4 h-4 text-gray-400 dark:text-gray-500" />
    @endisset
    <span>{{ $slot }}</span>
</label>