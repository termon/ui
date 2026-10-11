@props(['active'])

@php
    $tabsId = 'tabs-'.\Illuminate\Support\Str::uuid();
@endphp

<div x-data="{
    activeTab: @js($active),
    tabsId: @js($tabsId),
    tabs: [],
    tabHeadings: [],
    panelIds: [],
    init() {
        this.$nextTick(() => this.initializeTabs());
    },
    initializeTabs() {
        this.tabs = [...this.$refs.tabs.children].filter(panel => panel.getAttribute('role') === 'tabpanel');
        this.tabHeadings = this.tabs.map(panel => panel.dataset.name);
        this.panelIds = this.tabs.map((panel, index) => {
            panel.id ||= `${this.tabsId}-panel-${index + 1}`;
            panel.setAttribute('aria-labelledby', this.buttonId(index));
            return panel.id;
        });
        const savedTab = localStorage.getItem('activeTab');
        const tabParam = new URLSearchParams(window.location.search).get('tab');
        if (savedTab && this.tabHeadings.includes(savedTab)) {
            this.activeTab = savedTab;
        } else if (tabParam && this.tabHeadings.includes(tabParam)) {
            this.activeTab = tabParam;
        } else if (!this.tabHeadings.includes(this.activeTab)) {
            this.activeTab = this.tabHeadings[0] ?? this.activeTab;
        }
    },
    buttonId(index) {
        return `${this.tabsId}-button-${index + 1}`;
    },
    switchTab(tab) {
        this.activeTab = tab;
        localStorage.setItem('activeTab', tab);
        const url = new URL(window.location.href);
        url.search = '';
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    },
    focusTab(index) {
        this.$refs.tabList.querySelectorAll('[role=tab]')[index]?.focus();
    },
    focusNextTab(index) {
        this.focusTab((index + 1) % this.tabHeadings.length);
    },
    focusPreviousTab(index) {
        this.focusTab((index - 1 + this.tabHeadings.length) % this.tabHeadings.length);
    }
}" class="flex flex-col gap-3 overflow-x-auto">
    <div x-ref="tabList" class="mb-2 flex gap-1 border-b border-slate-200 dark:border-slate-700" role="tablist">
        <template x-for="(tab, index) in tabHeadings" :key="index">
            <button @click="switchTab(tab)"
                @focus="switchTab(tab)"
                @keydown.right.prevent="focusNextTab(index)"
                @keydown.left.prevent="focusPreviousTab(index)"
                @keydown.home.prevent="focusTab(0)"
                @keydown.end.prevent="focusTab(tabHeadings.length - 1)"
                :id="buttonId(index)"
                :tabindex="tab === activeTab ? 0 : -1"
                class="h-min rounded-t-lg px-1 pt-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600/40" type="button" role="tab"
                :aria-selected="(tab === activeTab).toString()" :aria-controls="panelIds[index]">
                <span x-text="tab"
                    :class="tab === activeTab ?
                        'border-b-2 border-blue-700 bg-blue-50 font-semibold text-blue-800 dark:border-blue-400 dark:bg-blue-500/10 dark:text-blue-200' :
                        'border-b-2 border-transparent font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'"
                    class="block rounded-t-md px-3 py-2 transition-colors"></span>
            </button>
        </template>
    </div>

    <div x-ref="tabs" {{ $attributes }}>
        {{ $slot }}
    </div>
</div>
