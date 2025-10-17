<div x-cloak
    x-data="{ 
        dark: localStorage.getItem('dark') === 'true',
        mobileOpen: false,
        collapsed: localStorage.getItem('collapsed') === 'true'
    }"
    x-init="
        $watch('dark', val => {
            document.documentElement.classList.toggle('dark', val)
            localStorage.setItem('dark', val)
        });
        $watch('collapsed', val => {
            localStorage.setItem('collapsed', val)
        });"
    :class="{ 'dark': dark }"
    {{ $attributes->merge(['class' => 'min-h-screen flex']) }}
>
    <!-- Sidebar -->
    <aside
        :class="{
            'w-64': !collapsed,
            'w-22': collapsed,
            '-translate-x-full': !mobileOpen,
            'translate-x-0': mobileOpen
        }"
        class="fixed z-40 inset-y-0 left-0 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out lg:static lg:translate-x-0 lg:flex-shrink-0"
    >
        <!-- Header -->
        <div class="p-4 flex items-center bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 h-14">
            <div class="flex items-center gap-2">
                <!-- Brand icon slot - always visible -->
                @isset($brandIcon)
                    {{ $brandIcon }}
    
                @endisset
                
                <!-- Brand title slot - conditionally rendered when not collapsed -->
                @isset($brandTitle)
                    <template x-if="!collapsed">                      
                        {{ $brandTitle }}                       
                    </template>
                @endisset
            </div>
        </div>

        <!-- Primary Navigation slot -->
        @isset($navigation)
            <nav class="p-4 space-y-2">
                {{ $navigation }}
            </nav>
        @endisset

        <!-- Secondary Navigation slot -->
        @isset($secondary)
            <nav class="px-4 pb-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
                {{ $secondary }}
            </nav>
        @endisset

        <!-- User section (bottom of sidebar on desktop) -->
        @isset($user)
            <div class="p-4 w-full absolute bottom-0 left-0 border-t border-gray-200 dark:border-gray-700 hidden lg:block">
                {{ $user }}
            </div>
        @endisset

    </aside>

    <!-- Mobile overlay -->
    <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-opacity-30 z-30 lg:hidden"
         x-transition.opacity x-cloak></div>

    <!-- Main content Scrollable: added max-height-screen -->
    <div class="flex-1 flex flex-col max-h-screen transition-all duration-300 ease-in-out">

        <!-- Top bar (always visible) Scrollable: added flex-shrink-0 -->
        <header
            class="flex items-center justify-between flex-shrink-0 p-4 border-b bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 h-14">
            
            <!-- Left side buttons -->
            <div class="flex items-center gap-2">
                <!-- Mobile menu button (mobile only) -->
                <button @click="mobileOpen = !mobileOpen" class="text-gray-600 dark:text-gray-200 cursor-pointer focus:outline-none lg:hidden">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Desktop sidebar collapse button -->
                <button @click="collapsed = !collapsed"
                        class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hidden lg:block cursor-pointer focus:outline-none">
                    <!-- Collapse sidebar icon (show when expanded) -->
                    <svg x-show="!collapsed" class="w-5 h-5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M9 3v18"/>
                        <path d="m16 15-3-3 3-3"/>
                    </svg>
                    <!-- Expand sidebar icon (show when collapsed) -->
                    <svg x-show="collapsed" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M9 3v18"/>
                        <path d="m14 9 3 3-3 3"/>
                    </svg>
                </button>
            </div>

            <!-- Toolbar and User content (right side) -->
            <div class="flex justify-end items-center gap-2">
                <!-- Toolbar content (always in topbar) -->
                @isset($toolbar)
                    {{ $toolbar }}
                @endisset
                
                <!-- User content (mobile only - moves from sidebar bottom) -->
                @isset($user)
                    <div class="lg:hidden">
                        {{ $user }}
                    </div>
                @elseif(isset($bottom))
                    <!-- Backward compatibility -->
                    <div class="lg:hidden">
                        {{ $bottom }}
                    </div>
                @endisset
            </div>

        </header>

        <!-- Page content  Scrollable: added overflow-y-auto -->
        <main class="flex-1 text-left overflow-y-auto px-4 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            {{ $slot }}
        </main>
    </div>
</div>
