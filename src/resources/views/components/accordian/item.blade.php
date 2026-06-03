@props([
    'name',
    'title',
    'summary' => null,
    'variant' => 'slate',
])

@php
    $titleBarClass = match ($variant) {
        'blue' => 'bg-blue-100',
        'green' => 'bg-green-100',
        'red' => 'bg-red-100',
        'sky' => 'bg-sky-100',
        'slate' => 'bg-slate-100',
        'yellow' => 'bg-yellow-100',
        default => throw new \InvalidArgumentException("No such accordion variant: {$variant}"),
    };
@endphp

<section {{ $attributes->class(['rounded-md border border-slate-200 bg-white']) }}>
    <button
        type="button"
        class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300"
        :class="open === @js($name) ? @js($titleBarClass) : 'hover:bg-slate-50'"
        :aria-expanded="open === @js($name)"
        @click="open = open === @js($name) ? null : @js($name)"
    >
        <span class="font-semibold text-slate-950">{{ $title }}</span>

        <span class="flex items-center gap-3">
            @isset($summary)
                <span class="text-sm font-medium text-slate-600">{{ $summary }}</span>
            @endisset

            <span class="text-slate-500 transition-transform" :class="open === @js($name) ? 'rotate-90' : ''">
                <x-ui::svg icon="chevron-right" size="sm" />
            </span>
        </span>
    </button>

    <div x-cloak x-show="open === @js($name)">
        {{ $slot }}
    </div>
</section>
