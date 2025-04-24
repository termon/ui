@props([
    'href' => "#",
    'label',
    'icon', // e.g. 'home', 'cog-6-tooth'
    'collapsed' => false,
    'active' => request()->url() === url($href ?? '#'),
])

<a {{ $attributes->merge(['href' => $href]) }}" 
   class="group relative flex items-center px-4 py-2 rounded-md hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition "
   :class="collapsed ? 'justify-center' : 'gap-3'"
   @class([
       'bg-gray-200 dark:bg-gray-700 font-semibold text-gray-900 dark:text-white' => $active,
       'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' => !$active,
   ])
>
    @isset($icon)
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 shrink-0" />
    @endisset

    {{-- Label (visible only when sidebar is expanded) --}}
    <span x-show="!collapsed" x-transition x-cloak class="truncate">
        {{ $label }}
    </span>

    {{-- Tooltip when collapsed --}}
    <span x-show="collapsed" x-transition x-cloak
          class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
        {{ $label }}
    </span>
</a>
