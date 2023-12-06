@props([
    'title' => 'Undefined'
])
<div class="">
    <div x-data="{ open: false }" class="relative">

      <a x-on:click="open=true" class="flex gap-1 items-center text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-black" href="#">
        <span class="">{{ $title }}</span>
        <x-ui.svg.chevron />        
      </a>
     
      <ul x-show="open" x-on:click.away="open=false" 
          class="bg-white text-gray-700 rounded shadow-lg absolute py-2 mt-1 w-60 min-w-max divide-y divide-gray-100">
          {{ $slot }}   
      </ul>
    </div>
</div>