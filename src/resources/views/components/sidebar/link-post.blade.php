@props(['href' => '#', 'icon' => null])

<form method="post" action="{{ $href }}"
    class="inset-0 flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg hover:cursor-pointer">
    @csrf
    @if ($icon)
        <x-ui::svg :variant="$icon" class="w-5 h-5" />
    @endif

    <button type="submit" class="hover:cursor-pointer">{{ $slot }}</button>
</form>

