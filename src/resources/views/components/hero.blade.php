@props(['heading', 'subheading' => null])
<section {{$attributes->merge(['class' => "relative bg-gradient-to-r from-slate-100 to-slate-50  py-8 px-6 mx-auto text-center rounded-lg" ])}}>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-extrabold sm:text-3xl">{{ $heading }}</h1>
        @if(isset($subheading))
            <p class="mt-4 text-lg sm:text-xl opacity-90">{{ $subheading }}</p>
        @endif            
    </div>
    <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 mt-6">
        {{$slot}}
    </div>   
</section>
