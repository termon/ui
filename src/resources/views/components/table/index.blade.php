@props([
    'tableClass' => null,
    'striped' => false,
    'compact' => false,
])

{{-- 
    overflow-x-auto - allow horizontal scrolling if needed 
    min-w-0         - allow the table wrapper to shrink in flex/grid layouts
    w-full          - element should take up full width of container
    align-middle    - align element in middle of container which is height of parent
--}}
<div {{ $attributes->merge(['class' => 'w-full min-w-0 max-w-full overflow-x-auto rounded-xl border border-slate-200/80 bg-white align-middle shadow-sm dark:border-slate-700 dark:bg-slate-800']) }}>
    <table @class(['min-w-full divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800', '[&_th]:py-2 [&_td]:py-2' => $compact, $tableClass])>

        @isset($thead)
            <thead {{ $thead->attributes->merge(['class' => 'bg-slate-50/90 text-xs text-slate-600 dark:bg-slate-900/60 dark:text-slate-300']) }}>
                {{ $thead }}
            </thead>
        @endisset

        @isset($tbody)
            <tbody {{ $tbody->attributes->class([
                'divide-y divide-slate-100 bg-white font-normal text-slate-700 dark:divide-slate-700 dark:bg-slate-800 dark:text-slate-200',
                '[&>tr:nth-child(even)]:bg-slate-50/60 dark:[&>tr:nth-child(even)]:bg-slate-900/30' => $striped,
            ]) }}>
                {{ $tbody }}
            </tbody>
        @endisset

        @isset($tfoot)
            <tfoot {{ $tfoot->attributes->merge(['class' => 'bg-slate-50 dark:bg-slate-900/60']) }}>
                {{ $tfoot }}
            </tfoot>
        @endisset

    </table>

</div>
