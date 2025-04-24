@props([
    'href' => "#",
    'label',
    'icon', // e.g. 'home', 'cog-6-tooth'
    'active' => request()->url() === url($href ?? '#'),
    'method' => null
])

@if (isset($method))
    <form method="post" action="{{ $href }}" class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:text-gray-300' rounded-lg">
        @csrf
        @method($method)
        @isset($icon)
            {{-- <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" /> --}}
            <x-ui.icon :icon="$icon" class="w-5 h-5 shrink-0" />
        @endisset
        <button type="submit" class="hover:cursor-pointer">
            <span class="truncate">
                {{ $label ?? '' }}
            </span>
        </button>
    </form>
@else
    <a {{ $attributes->merge(['href' => $href]) }}" 
        class="group relative flex items-center gap-2 px-4 py-2 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition "
        @class([
            'bg-gray-200 dark:bg-gray-700 font-semibold text-gray-900 dark:text-white' => $active,
            'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' => !$active,
            ])
    >
    @isset($icon)
        {{-- <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" /> --}}
        <x-ui.icon :icon="$icon" class="w-5 h-5 shrink-0" />
    @endisset

    @isset($label)
    <span class="truncate">
        {{ $label }}
    </span>
    @endisset

    </a>
@endif
