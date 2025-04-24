<div
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
            'w-16': collapsed,
            '-translate-x-full': !mobileOpen,
            'translate-x-0': mobileOpen
        }"
        class="fixed z-40 inset-y-0 left-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out lg:static lg:translate-x-0 lg:flex-shrink-0"
    >
        <!-- Header -->
        <div class="p-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700 h-18">
 
            <div x-show="!collapsed" >
                @isset($title) 
                    {{ $title }} 
                @endisset
            </div>
 
            <button @click="collapsed = !collapsed"
                    class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hidden lg:block">
                <svg x-show="!collapsed" class="w-5 h-5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 5l-7 7 7 7" />
                </svg>
                <svg x-show="collapsed" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 19l7-7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Navigation slot -->
        <nav class="p-4 space-y-2">
            {{ $navigation ?? '' }}
        </nav>

        <!-- Fixed Bottom Nav slot (Navlink at the bottom) -->
        <div class="p-4 w-full absolute bottom-0 left-0 border-t border-gray-200 dark:border-gray-700 ">
            {{ $bottom ?? '' }}
        </div>

    </aside>

    <!-- Mobile overlay -->
    <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-opacity-30 z-30 lg:hidden"
         x-transition.opacity x-cloak></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out">

        <!-- Top bar (mobile only) -->
        <header
            class="flex items-center  justify-between p-4 border-b bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 lg:hidden">
            <button @click="mobileOpen = !mobileOpen" class="text-gray-600 dark:text-gray-200 focus:outline-none">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Topbar slot -->
            <div class="flex justify-end items-center gap-2">
                {{ $top ?? '' }}
            </div>

        </header>

        <!-- Page content -->
        <main class="flex-1 text-left p-6 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            {{ $header ?? '' }}
            {{ $slot }}
        </main>
    </div>
</div>
