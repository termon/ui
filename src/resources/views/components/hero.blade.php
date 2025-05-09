@props(['heading', 'subheading' => null])

<section class="relative bg-gradient-to-r from-slate-100 to-slate-50 dark:from-slate-800 dark:to-slate-700 py-8 px-6 mx-auto text-center rounded-lg dark:bg-gray-800 dark:text-gray-400">
    <div class="max-w-4xl mx-auto">

        <x-ui::heading level="1">{{$heading}} </x-ui::heading> 

        @isset($subheading)
            <x-ui::heading level="3" class="mt-4 opacity-90">
                {{$subheading}}
            </x-ui::heading>
        @endisset     

    </div>

    <div {{$attributes->merge(['class' => "mt-6"])}}>
        {{$slot}}
    </div>  

</section>
