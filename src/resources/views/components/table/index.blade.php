{{-- 
    overflow-x-auto - allow horizontal scrolling if needed 
    min-w-0         - allow the table wrapper to shrink in flex/grid layouts
    w-full          - element should take up full width of container
    align-middle    - align element in middle of container which is height of parent
--}}
<div {{ $attributes->merge(['class' => 'w-full min-w-0 max-w-full p-1.5 overflow-x-auto align-middle border rounded-md border-gray-200 dark:border-gray-700']) }}>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">

        @isset($thead)
            <thead {{ $thead->attributes->merge(['class' => 'bg-gray-50 text-xs dark:text-gray-200 dark:bg-gray-700']) }}>
                {{ $thead }}
            </thead>
        @endisset

        @isset($tbody)
            <tbody {{ $tbody->attributes->merge(['class' =>'divide-y divide-gray-200 dark:divide-gray-700 font-normal dark:text-gray-300 py-6'])}}>
                {{ $tbody }}
            </tbody>
        @endisset

    </table>

</div>
