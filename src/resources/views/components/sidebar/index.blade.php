<aside x-cloak x-data="{ collapsed: false, showUserMenu: false }"
        :class="{ 'w-12': collapsed, 'w-64': !collapsed }"
        class="container w-64 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 h-screen p-4  flex flex-col justify-between transition-all duration-300">
    <div>
        <!-- Sidebar Header -->
        @isset($header)
            <div class="flex items-center justify-between mb-5">
                <div x-show="!collapsed" class="flex items-center gap-2">
                    {{ $header }}
                </div>

                <x-ui::svg  variant='chevron-left' 
                            class="p-0 m-0 w-5 h-5 hover:cursor-pointer"
                            x-show="!collapsed" 
                            @click="collapsed = !collapsed" />
                <x-ui::svg  variant='bars' 
                            class="p-0 m-0 w-6 h-6 hover:cursor-pointer"
                            x-show="collapsed"
                            @click="collapsed = !collapsed" />
            </div>
        @endisset

        <!-- Sidebar Navigation -->
        <nav x-show="!collapsed" class="">
            {{ $slot }}
        </nav>
    </div>

    <!-- Sidebar footer -->
    @isset($footer)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-2" x-show="!collapsed">
            {{ $footer }}
        </div>
    @endisset
    
</aside>