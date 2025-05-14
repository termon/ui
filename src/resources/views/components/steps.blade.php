@props([
    'percentage' => false,
    'numbered' => false,
    'steps' => [],
    'variant' => null
])

@php
    [$textColor, $bgClass] = match ($variant) {
        'green' => ['text-green-500', 'bg-green-600 text-white'],
        'blue' => ['text-blue-500', 'bg-blue-600 text-white'],
        default => ['text-indigo-500', 'bg-indigo-600 text-white'],
    };

    $completedSteps = count(array_filter($steps, fn($step) => $step[1]));
    $totalSteps = count($steps);
    $completedPercentage = ($completedSteps / $totalSteps) * 100;
@endphp

<div {{ $attributes->merge([
    'class' => 'font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 rounded-lg'
]) }}>
    <ol class="flex flex-col items-start gap-4 md:flex-row md:justify-between">
        @foreach($steps as $index => [$label, $complete])
            <li class="flex items-center {{ $complete ? $textColor : '' }}">
                @if($numbered)
                    <span class="flex items-center justify-center w-6 h-6 mr-2 text-xs font-semibold rounded-full shrink-0
                        {{ $complete ? $bgClass : 'bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-white' }}">
                        {{ $index }}
                    </span>
                @endif

                <span class="whitespace-nowrap">
                    {{ $label }}
                </span>

                <svg class="w-5 h-5 ml-2 lg:w-6 lg:h-6" fill="currentColor" viewBox="0 0 24 24">
                    @if($complete)
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    @else
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                    @endif
                </svg>
            </li>
        @endforeach
    </ol>

    @if($percentage)
        <div class="relative w-full h-2 rounded-full bg-gray-200 dark:bg-gray-700 my-2">
            <div class="h-2 rounded-full transition-all duration-300 {{ $bgClass }}" style="width: {{ $completedPercentage }}%;"></div>
            <div class="absolute top-5 right-0 mt-[-0.75rem] text-xs text-gray-600 dark:text-gray-300">
                {{ round($completedPercentage) }}%
            </div>
        </div>
    @endif
</div>
