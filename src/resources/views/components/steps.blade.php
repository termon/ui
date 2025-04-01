@props([ 
    'numbered' => false, 
    'steps' => [],
    'variant' => null
])

@php
    [$cfg, $cbg, $ofg, $obg] = match ($variant) {        
        'green' => ['text-green-600', 'bg-green-600 text-white', 'text-red-600', 'bg-red-600 text-white'],
        'blue' => ['text-blue-600', 'bg-blue-600 text-white', 'text-slate-600', 'bg-slate-600 text-white'],
        default => ['text-indigo-600', 'bg-indigo-600 text-white', 'text-slate-600', 'bg-slate-600 text-white'],
    };
@endphp

<ol {{ $attributes->merge(['class' => 'flex flex-col gap-3 lg:gap-1 lg:flex-row lg:justify-between items-left w-full p-3 text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base dark:bg-gray-800 dark:border-gray-700 sm:p-4 rtl:space-x-reverse' ]) }}>
    @foreach($steps as $step => [$label, $complete])
        <li @class(['flex items-center', $cfg => $complete, $ofg=> !$complete])>

            @if($numbered)
                <span @class(['flex items-center justify-center w-5 h-5 me-2 text-xs border rounded-full shrink-0', $cbg => $complete, $obg => !$complete])>            
                    {{ $step }}
                </span>
            @endif
            
            <span class="text-nowrap">
                {{ $label }}
            </span>

            @if($complete)
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 lg:w-6 lg:h-6 ms-2">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>          
            @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 lg:w-6 lg:h-6 ms-2">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            @endif
          
        </li>
    @endforeach
</ol>

