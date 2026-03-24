@props([
    'name',
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => 0,
    'variant' => 'light',
])

@php
    [$accentColor, $accentDarkColor] = match ($variant) {
        'light' => ['#9ca3af', '#d1d5db'],
        'oblue' => ['#2563eb', '#60a5fa'],
        'blue' => ['#1d4ed8', '#3b82f6'],
        'gray' => ['#6b7280', '#9ca3af'],
        'dark' => ['#111827', '#e5e7eb'],
        'green' => ['#16a34a', '#22c55e'],
        'red' => ['#dc2626', '#ef4444'],
        'yellow' => ['#eab308', '#facc15'],
        'purple' => ['#7e22ce', '#a855f7'],
        default => throw new \Exception("No such range variant: $variant"),
    };
@endphp

@once
    <style>
        .oui-range {
            -webkit-appearance: none;
            appearance: none;
            height: 0.5rem;
            cursor: pointer;
            border-radius: 9999px;
            background: rgb(229 229 229);
        }

        .oui-range::-webkit-slider-runnable-track {
            height: 0.5rem;
            border-radius: 9999px;
            background: var(--range-accent);
        }

        .oui-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 1rem;
            height: 1rem;
            margin-top: -0.25rem;
            border: 2px solid white;
            border-radius: 9999px;
            background: var(--range-accent);
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.18);
        }

        .oui-range::-moz-range-track {
            height: 0.5rem;
            border: 0;
            border-radius: 9999px;
            background: var(--range-accent);
        }

        .oui-range::-moz-range-thumb {
            width: 1rem;
            height: 1rem;
            border: 2px solid white;
            border-radius: 9999px;
            background: var(--range-accent);
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.18);
        }

        .dark .oui-range::-webkit-slider-runnable-track,
        .dark .oui-range::-webkit-slider-thumb,
        .dark .oui-range::-moz-range-track,
        .dark .oui-range::-moz-range-thumb {
            background: var(--range-accent-dark);
        }
    </style>
@endonce

<div {{ $attributes->only('class') }}>
    <div class="flex items-start space-x-4" x-data="{
        v: Number('{{ $value }}'),
        min: Number('{{ $min }}'),
        max: Number('{{ $max }}'),
    }">

        <!-- Left: slider + ticks as a column -->
        <div class="flex flex-col space-y-1 w-full">

            <!-- Slider -->
            <input
                class="oui-range w-full"
                id="{{ $name }}" name="{{ $name }}" type="range" min="{{ $min }}"
                max="{{ $max }}" step="{{ $step }}" x-model="v"
                style="--range-accent: {{ $accentColor }}; --range-accent-dark: {{ $accentDarkColor }};"
                {{ $attributes->class(['w-full']) }} />

            <!-- Ticks -->
            <div class="relative px-2 w-full flex justify-between text-xs text-neutral-500 select-none">
                @for ($i = $min; $i <= $max; $i++)
                    <div class="p-0 m-0 w-0.5 h-2 bg-neutral-400"></div>
                @endfor
            </div>
        </div>

        <!-- Right: value -->
        <span x-text="v" class="text-sm font-medium dark:text-gray-100 text-neutral-700"></span>

    </div>
</div>
