{{-- tabs.blade.php --}}
@props(['active'])

<div x-data="{
    activeTab: '{{ $active }}',
    tabs: [],
    tabHeadings: [],
    toggleTabs() {
        this.tabs.forEach(
            tab => tab.data.showIfActive(this.activeTab)
        );
    },
    switchTab(tab) {
        this.activeTab = tab;
        this.toggleTabs();
        
        // Save the active tab in localStorage
        localStorage.setItem('activeTab', tab);

        // Reset query parameters when switching tabs, but retain the tab parameter
        const url = new URL(window.location.href);
        
        // Clear all query parameters
        url.search = '';
        
        // Add the tab parameter back
        url.searchParams.set('tab', tab);
        
        // Update the URL without reloading the page
        window.history.replaceState({}, '', url);
    },
    focusNextTab(index) {
        const nextIndex = (index + 1) % this.tabHeadings.length;
        this.$refs.tabButtons[nextIndex]?.focus();
    },
    focusPreviousTab(index) {
        const previousIndex = (index - 1 + this.tabHeadings.length) % this.tabHeadings.length;
        this.$refs.tabButtons[previousIndex]?.focus();
    },
    activateFocusedTab(tab) {
        this.switchTab(tab);
    }
}" x-init="() => {
    tabHeadings = [...$refs.tabs.children].map((tab) => tab.dataset.name);
    
    // Check localStorage for saved active tab
    const savedTab = localStorage.getItem('activeTab');
    if (savedTab && tabHeadings.includes(savedTab)) {
        activeTab = savedTab;
    } else {
        // Check URL parameters for active tab if no saved tab is found
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        
        // If tab parameter exists and matches one of our tabs, activate it
        if (tabParam && tabHeadings.includes(tabParam)) {
            activeTab = tabParam;
        }
    }
    
    // Activate the correct tab when the page is initialized
    toggleTabs();
}"
    class="flex flex-col gap-2 overflow-x-auto">
    <div class="mb-3 border-b border-neutral-300 dark:border-neutral-700" role="tablist">
        <template x-for="(tab, index) in tabHeadings" :key="index">
            <button @click="switchTab(tab);"
                @focus="activateFocusedTab(tab)"
                @keydown.right.prevent="focusNextTab(index)"
                @keydown.left.prevent="focusPreviousTab(index)"
                @keydown.home.prevent="$refs.tabButtons[0]?.focus()"
                @keydown.end.prevent="$refs.tabButtons[tabHeadings.length - 1]?.focus()"
                x-ref="tabButtons"
                class="h-min px-1 pt-1 focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0" type="button" role="tab" role="tab"
                :aria-selected="(tab === activeTab).toString()" :aria-controls="`tab-panel-${index + 1}`">
                <span x-text="tab"
                    :class="tab === activeTab ?
                        'font-semibold text-neutral-900 border-b-2 border-neutral-600 dark:border-white dark:text-white' :
                        'border-b-2 border-transparent text-neutral-500 font-light hover:border-neutral-300 hover:text-neutral-600 dark:text-neutral-300 dark:hover:border-neutral-200 dark:hover:text-white'"
                    class="block px-3 pb-0.5 transition-colors"></span>
            </button>
        </template>
    </div>

    <div x-ref="tabs" {{ $attributes }}>
        {{ $slot }}
    </div>
</div>
