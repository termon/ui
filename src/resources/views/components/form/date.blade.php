@props([
    'name', 
    'value' => '' 
])

<!-- make sure $value is in 'YYYY-MM-DD' format for proper initialization -->
<div x-data="datePicker(@js($value))" x-cloak>
        <div class="relative">
            <input x-ref="input" name="{{ $name }}" value="{{ $value }}" type="text" @click.stop="toggle()"
                x-model="displayValue" x-on:keydown.escape="open = false" placeholder="Select date" readonly
                class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-gray-700 leading-tight cursor-pointer
                     focus:ring-blue-500 focus:border-blue-500
                     dark:focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                     dark:text-white dark:focus:ring-gray-500" />

            <div @click.stop="toggle(); if(open){ $refs.input.focus() }"
                class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>

            <template x-teleport="body">
            <div x-show="open" x-ref="panel" x-transition @click.away="open = false" x-on:keydown.escape.window="open = false" x-bind:style="panelStyle"
                class="fixed left-0 top-0 z-[1000] p-4 bg-white border rounded-lg shadow w-[17rem] max-h-[min(80vh,32rem)] overflow-y-auto
                     border-gray-200/70 dark:bg-gray-700 dark:border-gray-600">
                <!-- Calendar Header -->
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span x-text="monthNames[month]"
                            class="text-lg font-bold text-gray-800 dark:text-white"></span>
                        <span x-text="year" class="ml-1 text-lg font-normal text-gray-600 dark:text-gray-300"></span>
                    </div>
                    <div>
                        <button @click="previousMonth()" type="button"
                            class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="nextMonth()" type="button"
                            class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 mb-2">
                    <template x-for="(d, idx) in days" :key="idx">
                        <div class="text-xs font-medium text-center text-gray-800 dark:text-white" x-text="d">
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-7">
                    <template x-for="blank in blankDays">
                        <div class="p-1 text-sm text-center border border-transparent"></div>
                    </template>

                    <template x-for="(d, idx) in daysInMonth" :key="idx">
                        <div class="px-0.5 mb-1 aspect-square">
                            <div x-text="d" @click="selectDate(d)"
                                :class="{
                                    'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white': isToday(d),
                                    'text-gray-600 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-600': !
                                        isToday(d) && !isSelectedDate(d),
                                    'bg-gray-800 text-white dark:bg-gray-900 dark:text-white': isSelectedDate(d)
                                }"
                                class="flex items-center justify-center text-sm rounded-full cursor-pointer h-7 w-7">
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Actions -->
                <div class="mt-3 flex justify-between">
                    <button type="button" @click="setToday"
                        class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200
                               dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                        Today
                    </button>

                    <button type="button" @click="apply"
                        class="px-3 py-1 text-sm font-medium text-white bg-gray-800 rounded hover:bg-gray-700
                               dark:bg-gray-900 dark:text-white dark:hover:bg-black">
                        OK
                    </button>
                </div>
            </div>
            </template>
        </div>       
</div>

<script>
    function datePicker(initialValue) {
        return {
            open: false,
            panelStyle: '',
            displayValue: '',
            selectedDate: null,

            year: null,
            month: null,
            day: null,

            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

            blankDays: [],
            daysInMonth: [],

            init() {
                let date = null;

                if (initialValue) {
                    const v = String(initialValue).trim();

                    // 1. Match YYYY-MM-DD
                    const re1 = /^(\d{4})-(\d{2})-(\d{2})$/;
                    // 2. Match DD-MM-YYYY
                    const re2 = /^(\d{2})-(\d{2})-(\d{4})$/;

                    let m;
                    if ((m = v.match(re1))) {
                        const [, y, mo, d] = m;
                        date = new Date(Number(y), Number(mo) - 1, Number(d));
                    } else if ((m = v.match(re2))) {
                        const [, d, mo, y] = m;
                        date = new Date(Number(y), Number(mo) - 1, Number(d));
                    } else {
                        const parsed = new Date(v);
                        if (!isNaN(parsed)) date = parsed;
                    }
                }

                if (!date || isNaN(date)) date = new Date();
                this.year = date.getFullYear();
                this.month = date.getMonth();
                this.day = date.getDate();

                this.selectedDate = new Date(this.year, this.month, this.day);
                this.displayValue = this.formatDate(this.selectedDate);
                this.calculateDays();

                const reposition = () => {
                    if (this.open) this.positionPanel();
                };

                this._repositionPanel = reposition;
                window.addEventListener('resize', reposition);
                document.addEventListener('scroll', reposition, true);

                if (this.$watch) {
                    this.$watch('open', (isOpen) => {
                        if (isOpen) {
                            this.$nextTick(() => this.positionPanel());
                        }
                    });
                }
            },

            destroy() {
                if (this._repositionPanel) {
                    window.removeEventListener('resize', this._repositionPanel);
                    document.removeEventListener('scroll', this._repositionPanel, true);
                }
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.$nextTick(() => this.positionPanel());
                }
            },

            positionPanel() {
                if (!this.$refs.input) return;

                const rect = this.$refs.input.getBoundingClientRect();
                const gap = 8;
                const widthPx = this.$refs.panel?.offsetWidth || 272; // w-[17rem] fallback
                let left = rect.left;
                left = Math.max(gap, Math.min(left, window.innerWidth - widthPx - gap));

                let top = rect.bottom + gap;

                if (this.$refs.panel) {
                    const panelHeight = this.$refs.panel.offsetHeight || 0;
                    const aboveTop = rect.top - panelHeight - gap;
                    const belowBottom = top + panelHeight;
                    const viewportBottom = window.innerHeight - gap;

                    if (belowBottom > viewportBottom) {
                        top = aboveTop >= gap
                            ? aboveTop
                            : Math.max(gap, viewportBottom - panelHeight);
                    }
                }

                this.panelStyle = `top:${Math.max(gap, top)}px;left:${left}px;`;
            },

            calculateDays() {
                const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                const firstDayOfWeek = new Date(this.year, this.month, 1).getDay();
                this.blankDays = Array.from({
                    length: firstDayOfWeek
                });
                this.daysInMonth = Array.from({
                    length: daysInMonth
                }, (_, i) => i + 1);
            },

            previousMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.calculateDays();
            },

            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.calculateDays();
            },

            isToday(d) {
                const t = new Date();
                return d === t.getDate() && this.month === t.getMonth() && this.year === t.getFullYear();
            },

            isSelectedDate(d) {
                return this.selectedDate &&
                    d === this.day &&
                    this.month === this.selectedDate.getMonth() &&
                    this.year === this.selectedDate.getFullYear();
            },

            selectDate(d) {
                this.day = d;
                this.selectedDate = new Date(this.year, this.month, this.day);
                this.displayValue = this.formatDate(this.selectedDate);
                this.calculateDays();
            },

            setToday() {
                const now = new Date();
                this.year = now.getFullYear();
                this.month = now.getMonth();
                this.day = now.getDate();
                this.selectedDate = new Date(this.year, this.month, this.day);
                this.displayValue = this.formatDate(this.selectedDate);
                this.calculateDays();
            },

            apply() {
                this.open = false;
            },

            formatDate(date) {
                const monthName = this.monthNames[date.getMonth()].substring(0, 3);
                const day = String(date.getDate()).padStart(2, '0');
                const year = date.getFullYear();
                return `${monthName} ${day}, ${year}`;
            }
        };
    }
</script>
