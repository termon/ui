@props(['thead', 'tbody'])

<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="border rounded-lg overflow-hidden">
                <table {{$attributes->merge(['class' => "min-w-full divide-y divide-gray-200"])}}>

                    <thead {{$thead->attributes->merge(['class' => "bg-gray-200"])}}>
                        {{ $thead }}
                    </thead>

                    <tbody {{$tbody->attributes->merge(['class' => "divide-y divide-gray-200"])}}>
                        {{ $tbody }}
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

