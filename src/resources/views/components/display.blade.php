@props([
    'label',
    'value' => null,
    'icon' => null,
    'labelWidth' => 'md', // accepts: sm, md, lg, xl
])

@php
    $labelWidthClass = match ($labelWidth) {
        'sm' => 'md:min-w-[6rem]',
        'md' => 'md:min-w-[10rem]',
        'lg' => 'md:min-w-[14rem]',
        'xl' => 'md:min-w-[18rem]',
        default => 'md:min-w-[10rem]',
    };
@endphp
{{-- Wrapper --}}
<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row md:items-start py-2 border-b border-gray-200 dark:border-gray-700']) }}>
    {{-- Icon + Label (always inline) --}}
    <div class="flex items-center gap-2 font-medium text-gray-500 dark:text-gray-400 {{ $labelWidthClass }}">
        @isset($icon)
            <x-ui::svg :icon="$icon" class="w-4 h-4 text-gray-400 dark:text-gray-500" />
        @endisset
        <span>{{ $label }}</span>
    </div>

    {{-- Value --}}
    <div class=" text-gray-900 dark:text-gray-100 md:flex-1 md:pl-4 mt-1 md:mt-0">
        @isset ($value)
            {{ $value }}
        @else
            <div {{ $slot->attributes }}>
                {{ $slot }}
            </div>
        @endisset
    </div>
</div>
