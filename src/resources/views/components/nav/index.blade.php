<nav {{ $attributes->merge(['class' => 'flex justify-between items-center gap-6 bg-gray-50 px-4 py-3 border-b']) }}>

    <div class="flex gap-3 lg:gap-6 items-center"> 
        {{ $slot }}
    </div>
       
    @isset($center)
        <div class="flex gap-4"> 
            {{ $center }}
        </div>
    @endisset
    
    @isset($right)
    <div class="flex gap-4">
        {{ $right }}
    </div>
    @endisset
  
</nav>
