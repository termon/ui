@props([
    'name', 
    'label', 
    'value' => ''
])

<!-- make sure $value is in 'YYYY-MM-DD HH:mm:ss' format for proper initialization -->
<div x-data="dateTimePicker(@js($value))" x-cloak>

    <div class="relative">
        <input x-ref="input" name="{{ $name }}" value="{{ $value }}" type="text" @click.stop="toggle()"
            x-model="displayValue" x-on:keydown.escape="open = false" placeholder="Select date and time" readonly
            class="w-full border border-gray-300 rounded-lg p-2.5 pr-10 text-gray-700 leading-tight cursor-pointer
                    focus:ring-blue-500 focus:border-blue-500
                    dark:focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                    dark:text-white dark:focus:ring-gray-500" />

        <div @click.stop="toggle(); if(open){ $refs.input.focus() }"
            class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>

        <template x-teleport="body">
        <div x-show="open" x-ref="panel" x-transition @click.away="open = false" x-on:keydown.escape.window="open = false" x-bind:style="panelStyle"
            class="fixed left-0 top-0 z-[1000] p-4 bg-white border rounded-lg shadow w-[17rem] max-h-[min(80vh,36rem)] overflow-y-auto
                    border-gray-200/70 dark:bg-gray-900 dark:border-gray-700">
            <!-- Calendar Header -->
            <div class="flex items-center justify-between gap-1 mb-2">
                <div class="min-w-0 flex-1 whitespace-nowrap">
                    <span x-text="monthNames[month]"
                        class="text-lg font-bold text-gray-800 dark:text-gray-200"></span>
                    <span x-text="year" class="ml-1 text-lg font-normal text-gray-600 dark:text-gray-400"></span>
                </div>
                <div class="flex items-center shrink-0 gap-px">
                    <button @click="previousYear()" type="button" title="Previous year"
                        class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 19l-7-7 7-7M11 19L4 12l7-7" />
                        </svg>
                    </button>
                    <button @click="previousMonth()" type="button"
                        title="Previous month"
                        class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextMonth()" type="button"
                        title="Next month"
                        class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button @click="nextYear()" type="button" title="Next year"
                        class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 5l7 7-7 7m7-14l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 mb-2">
                <template x-for="(d, idx) in days" :key="idx">
                    <div class="text-xs font-medium text-center text-gray-800 dark:text-gray-300" x-text="d">
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
                                'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700': !
                                    isToday(d) && !isSelectedDate(d),
                                'bg-gray-800 text-white dark:bg-gray-100 dark:text-gray-900': isSelectedDate(d)
                            }"
                            class="flex items-center justify-center text-sm rounded-full cursor-pointer h-7 w-7">
                        </div>
                    </div>
                </template>
            </div>

            <!-- Time Picker (24-hour) -->
            <div class="mt-3 flex space-x-2 items-center">
                <select x-model="hour" @change="updateSelectedTime()"
                    class="border-gray-300 dark:bg-gray-800 dark:text-gray-200 rounded-md text-sm">
                    <template x-for="h in hours" :key="h">
                        <option :value="h" x-text="h" :selected="h === hour"></option>
                    </template>
                </select>
                <span>:</span>
                <select x-model="minute" @change="updateSelectedTime()"
                    class="border-gray-300 dark:bg-gray-800 dark:text-gray-200 rounded-md text-sm">
                    <template x-for="m in minutes" :key="m">
                        <option :value="m" x-text="m" :selected="m === minute"></option>
                    </template>
                </select>
            </div>

            <!-- Actions -->
            <div class="mt-3 flex justify-between">
                <button type="button" @click="setNow"
                    class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200
                            dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Now
                </button>

                <button type="button" @click="apply"
                    class="px-3 py-1 text-sm font-medium text-white bg-gray-800 rounded hover:bg-gray-700
                            dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
                    OK
                </button>
            </div>
        </div>
        </template>
    </div>

</div>

<script>
    function dateTimePicker(initialValue) {
        return {
            open: false,
            panelStyle: '',
            displayValue: '',
            selectedDate: null,

            year: null,
            month: null,
            day: null,
            hour: '00',
            minute: '00',

            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

            blankDays: [],
            daysInMonth: [],

            hours: Array.from({
                length: 24
            }, (_, i) => i.toString().padStart(2, '0')),            
            minutes: Array.from({
                length: 12
            }, (_, i) => (i * 5).toString().padStart(2, '0')),

            init() {
                let date = null;
                
                if (initialValue) {
                    const v = String(initialValue).trim();

                    // 1. Match YYYY-MM-DD HH:mm:ss
                    const re1 = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})(?::(\d{2}))?$/;
                    // 2. Match DD-MM-YYYY HH:mm:ss
                    const re2 = /^(\d{2})-(\d{2})-(\d{4})[ T](\d{2}):(\d{2})(?::(\d{2}))?$/;

                    let m;
                    if ((m = v.match(re1))) {
                        const [, y, mo, d, hh, mm, ss] = m;
                        date = new Date(`${y}-${mo}-${d}T${hh}:${mm}:${ss ?? '00'}`);
                    } else if ((m = v.match(re2))) {
                        const [, d, mo, y, hh, mm, ss] = m;
                        date = new Date(`${y}-${mo}-${d}T${hh}:${mm}:${ss ?? '00'}`);
                    } else {
                        const parsed = new Date(v);
                        if (!isNaN(parsed)) date = parsed;
                    }
                }
               
                if (!date || isNaN(date)) date = new Date();
                this.year = date.getFullYear();
                this.month = date.getMonth();
                this.day = date.getDate();
                this.hour = date.getHours().toString().padStart(2, '0');
               // round minutes to nearest 5 given we only allow selection of 5 min intervals
                const rawMinute = date.getMinutes();
                const roundedMinute = Math.round(rawMinute / 5) * 5;
                this.minute = (roundedMinute % 60).toString().padStart(2, '0');

                this.selectedDate = new Date(this.year, this.month, this.day, parseInt(this.hour), parseInt(this
                    .minute));
                this.displayValue = this.formatDateTime(this.selectedDate);
                this.calculateDays();

                if (this.$watch) {
                    this.$watch('hour', () => this.updateSelectedTime());
                    this.$watch('minute', () => this.updateSelectedTime());
                    this.$watch('open', (isOpen) => {
                        if (isOpen) {
                            this.$nextTick(() => this.positionPanel());
                        }
                    });
                }

                const reposition = () => {
                    if (this.open) this.positionPanel();
                };

                this._repositionPanel = reposition;
                window.addEventListener('resize', reposition);
                document.addEventListener('scroll', reposition, true);
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

            updateSelectedTime() {
                if (!this.selectedDate) {
                    this.selectedDate = new Date(this.year, this.month, this.day);
                }
                this.selectedDate.setHours(parseInt(this.hour) || 0, parseInt(this.minute) || 0, 0, 0);
                this.displayValue = this.formatDateTime(this.selectedDate);
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

            previousYear() {
                this.year--;
                this.calculateDays();
            },

            nextYear() {
                this.year++;
                this.calculateDays();
            },

            isToday(d) {
                const t = new Date();
                return d === t.getDate() && this.month === t.getMonth() && this.year === t.getFullYear();
            },

            isSelectedDate(d) {
                return d === this.day && this.month === this.selectedDate.getMonth() && this.year === this.selectedDate
                    .getFullYear();
            },

            selectDate(d) {
                this.day = d;
                this.selectedDate = new Date(this.year, this.month, this.day, parseInt(this.hour), parseInt(this
                    .minute));
                this.displayValue = this.formatDateTime(this.selectedDate);
                this.calculateDays();
            },

            setNow() {
                const now = new Date();
                this.year = now.getFullYear();
                this.month = now.getMonth();
                this.day = now.getDate();
                this.hour = now.getHours().toString().padStart(2, '0');
                const roundedMinute = Math.round(now.getMinutes() / 5) * 5;
                this.minute = (roundedMinute % 60).toString().padStart(2, '0');
                const carryHour = roundedMinute >= 60 ? 1 : 0;
                const selectedHour = (now.getHours() + carryHour) % 24;
                this.hour = selectedHour.toString().padStart(2, '0');
                this.selectedDate = new Date(this.year, this.month, this.day, selectedHour, roundedMinute % 60);
                this.displayValue = this.formatDateTime(this.selectedDate);
                this.calculateDays();
            },

            apply() {
                this.open = false;
            },

            formatDateTime(date) {
                const monthName = this.monthNames[date.getMonth()].substring(0, 3);
                const day = String(date.getDate()).padStart(2, '0');
                const year = date.getFullYear();
                const h = String(date.getHours()).padStart(2, '0');
                const m = String(date.getMinutes()).padStart(2, '0');
                return `${monthName} ${day}, ${year} ${h}:${m}`;
            }
        };
    }
</script>
