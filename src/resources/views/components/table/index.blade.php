{{-- 
    overflow-hidden - clip any content within an element that overflows the bounds of that element
    overflow-x-auto - allow horizontal scrolling if needed 
    min-w-full      - element should take up full width of container
    align-middle    - align element in middle of container which is height of parent
--}}
<div {{$attributes->merge(['class' => 'p-1.5 overflow-hidden overflow-x-auto min-w-full align-middle border rounded-md border-gray-200 dark:border-gray-700'])}} >
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

