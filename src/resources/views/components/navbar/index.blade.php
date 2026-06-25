<div x-cloak
    x-data="{
        dark: localStorage.getItem('dark') === 'true',
        mobileOpen: false
    }"
    x-init="
        $watch('dark', val => {
            document.documentElement.classList.toggle('dark', val);
            localStorage.setItem('dark', val);
        });
    "
    @keydown.window.escape="mobileOpen = false"
    :class="{ 'dark': dark }"
    {{ $attributes->merge(['class' => 'min-w-0 overflow-x-hidden text-gray-900 dark:text-gray-100']) }}
>
    <!-- Top Nav -->
    <!-- removed fixed and z-40 and replaced with relative to allow display of dropdowns and allow navbar to scroll with content -->
    <header class="border-b bg-gray-50 border-gray-200 dark:border-gray-700 dark:bg-gray-800 w-full fixed z-40">
        <div class="mx-auto flex min-w-0 items-center justify-between gap-2 px-4 py-3 xl:py-4">
            
            <!-- Brand Icon/Title slots -->
            <div class='min-w-0 flex shrink items-center gap-2'>
                @isset($brandIcon)
                    <div {{ $brandIcon->attributes }}>
                        {{ $brandIcon }}
                    </div>
                @endisset                    
                @isset($brandTitle)
                    <div {{ $brandTitle->attributes }}>
                        {{ $brandTitle }}
                    </div>
                @endisset
            </div>

            <!-- Main navigation -->
            @isset($navigation)
                <nav {{ $navigation->attributes->merge(['class' => 'hidden min-w-0 xl:flex items-center gap-2']) }}>
                    {{ $navigation }}
                </nav>
            @endisset

            <!-- Right Nav -->
            @isset($right)
                <nav {{ $right->attributes->merge(['class' => 'hidden min-w-0 xl:flex items-center gap-2']) }}>
                    {{ $right }}
                </nav>
            @endisset

            <!-- Toolbar Nav -->
            @isset($toolbar)
                <footer {{ $toolbar->attributes->merge(['class' => 'flex items-center justify-center fixed bottom-0 inset-x-0 mt-2 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-40'])}} >
                    <div class="flex min-w-0 max-w-full justify-around items-center overflow-x-auto py-2">
                        {{ $toolbar }}
                    </div>
                </footer>
            @endisset

            <!-- Mobile menu button -->
            <button 
                @click="mobileOpen = !mobileOpen" 
                class="shrink-0 xl:hidden text-gray-600 dark:text-gray-200"
                :aria-expanded="mobileOpen">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile nav dropdown -->
        <!-- removed min-w-64 and w-max and replaced with w-full so that menu takes up full width in mobile mode -->
        <div 
            x-show="mobileOpen" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 scale-95" 
            x-transition:enter-end="opacity-100 scale-100" 
            x-transition:leave="transition ease-in duration-150" 
            x-transition:leave-start="opacity-100 scale-100" 
            x-transition:leave-end="opacity-0 scale-95"
            x-cloak
             @click.outside="mobileOpen = false"            
            class="absolute top-full right-0 w-full max-w-full overflow-x-hidden bg-gray-50 dark:bg-gray-800 border-t shadow-lg border-gray-200 dark:border-gray-700 xl:hidden">
            @isset($navigation)
                <div class="px-4 py-3 space-y-2">
                        {{ $navigation }}
                </div>
            @endisset
            @isset($right)
                <div class="px-4 py-3 space-y-2 border-t border-gray-200 dark:border-gray-700">
                    {{ $right }}
                </div>
            @endisset
        </div>
    </header>
    
</div>
