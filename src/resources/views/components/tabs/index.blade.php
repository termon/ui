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
    class="flex flex-col gap-2 overflow-x-auto border-b border-neutral-300 dark:border-neutral-700">
    <div class="mb-3" role="tablist">
        <template x-for="(tab, index) in tabHeadings" :key="index">
            <button x-text="tab" @click="switchTab(tab);"
                :class="tab === activeTab ?
                    'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' :
                    'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900 hover:cursor-pointer'"
                class="h-min px-4 py-2" type="button" role="tab" role="tab"
                :aria-selected="(tab === activeTab).toString()" :aria-controls="`tab-panel-${index + 1}`"></button>
        </template>
    </div>

    <div x-ref="tabs" {{ $attributes }}>
        {{ $slot }}
    </div>
</div>
