<li>
    <a {{$attributes->merge(['class'=>'transition-colors hover:rounded duration-300 block hover:bg-gray-100 whitespace-no-wrap py-2 px-4'])}}">
        <div class="flex gap-2">
            {{ $slot }}
        </div>
    </a>    
</li>
      