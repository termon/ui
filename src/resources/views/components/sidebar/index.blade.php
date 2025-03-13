<aside x-cloak x-data="{ collapsed: false, showUserMenu: false }"
        :class="{ 'w-12': collapsed, 'w-64': !collapsed }"
        {{ $attributes->merge(['class' => "container w-64 border-r border-gray-200 bg-white h-screen p-4 flex flex-col justify-between transition-all duration-300"])}} >
    <div>
        <!-- Sidebar Title -->
        <div class="flex items-center justify-between mb-5 border-b border-gray-200 pb-3">
            @if(isset($title))
                <div x-show="!collapsed"  @click="collapsed = !collapsed" {{ $title->attributes->merge(['class' => "flex flex-col items-center gap-2 cursor-pointer"])}}>
                    {{ $title }}
                </div>
            @else
                <x-ui::svg  variant='chevron-left'  class="p-0 m-0 w-5 h-5 hover:cursor-pointer" x-show="!collapsed"  @click="collapsed = !collapsed" />   
            @endif
        
            <x-ui::svg  variant='bars' 
                        class="p-0 m-0 w-6 h-6 hover:cursor-pointer"
                        x-show="collapsed"
                        @click="collapsed = !collapsed" />
        </div>
       
        <!-- Sidebar Navigation -->
        <nav x-show="!collapsed">
            {{ $slot }}
        </nav>
    
        <!-- Sidebar end -->
        @isset($end)
            <div  {{ $attributes->merge(['class' =>"w-64 fixed bottom-0 left-0 border-t border-gray-200 p-2"]) }} x-show="!collapsed">
                {{ $end }}
            </div>
        @endisset
    </div>
</aside>