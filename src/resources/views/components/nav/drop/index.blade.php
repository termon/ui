@props([
    'title' => 'Undefined',
])

<div x-data="{ open: false }" class="relative">

    <a x-on:click="open=true"
        class="flex items-center gap-1 border-b-2 border-transparent text-gray-700 hover:border-black hover:text-gray-900"
        href="#">
        <span class="">{{ $title }}</span>
        {{-- Chevron --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-4 w-4 fill-current">
            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
        </svg>
    </a>

    <ul x-show="open" x-on:click.away="open=false"
        class="absolute mt-1 w-60 min-w-max divide-y divide-gray-100 rounded-md bg-white px-2 py-2 text-gray-700 shadow-lg">
        {{ $slot }}
    </ul>
</div>
