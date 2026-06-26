@props([
    'name',
    'label' => null,
    'value' => '',
])

<div
    x-data="dateTimePicker(@js($value))"
    x-modelable="value"
    x-cloak
    {{ $attributes }}
>
    <div class="relative">
        <input
            type="hidden"
            name="{{ $name }}"
            value="{{ $value }}"
            x-bind:value="value"
        />

        <input
            x-ref="input"
            id="{{ $name }}"
            type="text"
            x-model="displayValue"
            x-on:click.stop="toggle()"
            x-on:keydown.escape="open = false"
            placeholder="Select date and time"
            readonly
            class="w-full cursor-pointer rounded-lg border border-gray-300 p-2.5 pr-10 leading-tight text-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-gray-500"
        />

        <div
            x-on:click.stop="toggle(); if(open){ $refs.input.focus() }"
            class="absolute top-0 right-0 cursor-pointer px-3 py-2 text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
            </svg>
        </div>

        <template x-teleport="body">
            <div
                x-show="open"
                x-cloak
                x-ref="panel"
                x-transition
                x-on:click.away="open = false"
                x-on:keydown.escape.window="open = false"
                x-bind:style="panelStyle"
                class="fixed left-0 top-0 z-[1000] max-h-[min(80vh,36rem)] w-[17rem] overflow-y-auto rounded-lg border border-gray-200/70 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="mb-2 flex items-center justify-between gap-1">
                    <div class="min-w-0 flex-1 whitespace-nowrap">
                        <span x-text="monthNames[month]" class="text-lg font-bold text-gray-800 dark:text-gray-200"></span>
                        <span x-text="year" class="ml-1 text-lg font-normal text-gray-600 dark:text-gray-400"></span>
                    </div>
                    <div class="flex shrink-0 items-center gap-px">
                        <button x-on:click="previousYear()" type="button" title="Previous year" class="rounded-full p-0 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 19l-7-7 7-7M11 19L4 12l7-7" />
                            </svg>
                        </button>
                        <button x-on:click="previousMonth()" type="button" title="Previous month" class="rounded-full p-0 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button x-on:click="nextMonth()" type="button" title="Next month" class="rounded-full p-0 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button x-on:click="nextYear()" type="button" title="Next year" class="rounded-full p-0 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5l7 7-7 7m7-14l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-2 grid grid-cols-7">
                    <template x-for="(d, idx) in days" :key="idx">
                        <div class="text-center text-xs font-medium text-gray-800 dark:text-gray-300" x-text="d"></div>
                    </template>
                </div>

                <div class="grid grid-cols-7">
                    <template x-for="blank in blankDays">
                        <div class="border border-transparent p-1 text-center text-sm"></div>
                    </template>

                    <template x-for="(d, idx) in daysInMonth" :key="idx">
                        <div class="mb-1 aspect-square px-0.5">
                            <div
                                x-text="d"
                                x-on:click="selectDate(d)"
                                x-bind:class="{
                                    'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white': isToday(d),
                                    'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700': !isToday(d) && !isSelectedDate(d),
                                    'bg-gray-800 text-white dark:bg-gray-100 dark:text-gray-900': isSelectedDate(d)
                                }"
                                class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-full text-sm"
                            ></div>
                        </div>
                    </template>
                </div>

                <div class="mt-3 flex items-center space-x-2">
                    <select x-model="hour" x-on:change="updateSelectedTime()" class="rounded-md border-gray-300 text-sm dark:bg-gray-800 dark:text-gray-200">
                        <template x-for="h in hours" :key="h">
                            <option x-bind:value="h" x-text="h"></option>
                        </template>
                    </select>
                    <span>:</span>
                    <select x-model="minute" x-on:change="updateSelectedTime()" class="rounded-md border-gray-300 text-sm dark:bg-gray-800 dark:text-gray-200">
                        <template x-for="m in minutes" :key="m">
                            <option x-bind:value="m" x-text="m"></option>
                        </template>
                    </select>
                </div>

                <div class="mt-3 flex justify-between gap-2">
                    <div class="flex gap-2">
                        <button type="button" x-on:click="setNow" class="rounded bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Now
                        </button>
                        <button type="button" x-on:click="clear" class="rounded bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Clear
                        </button>
                    </div>

                    <button type="button" x-on:click="apply" class="rounded bg-gray-800 px-3 py-1 text-sm font-medium text-white hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">
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
            value: '',
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
                const parsedDate = this.parseDate(initialValue);
                const viewportDate = parsedDate || new Date();
                const roundedDate = this.roundToNearestFiveMinutes(viewportDate);

                this.setCalendarState(roundedDate);

                if (parsedDate) {
                    this.selectedDate = roundedDate;
                    this.value = this.formatValue(this.selectedDate);
                    this.displayValue = this.formatDateTime(this.selectedDate);
                }

                this.calculateDays();

                if (this.$watch) {
                    this.$watch('hour', () => this.updateSelectedTime());
                    this.$watch('minute', () => this.updateSelectedTime());
                    this.$watch('open', (isOpen) => {
                        if (isOpen) {
                            this.$nextTick(() => this.positionPanel());
                        }
                    });
                    this.$watch('value', (newValue) => this.syncFromValue(newValue));
                }

                const reposition = () => {
                    if (this.open) {
                        this.positionPanel();
                    }
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
                if (!this.$refs.input) {
                    return;
                }

                const rect = this.$refs.input.getBoundingClientRect();
                const gap = 8;
                const widthPx = this.$refs.panel?.offsetWidth || 272;
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
                this.commitSelectedDate();
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
                return this.selectedDate &&
                    d === this.selectedDate.getDate() &&
                    this.month === this.selectedDate.getMonth() &&
                    this.year === this.selectedDate.getFullYear();
            },

            selectDate(d) {
                this.day = d;
                this.selectedDate = new Date(this.year, this.month, this.day, parseInt(this.hour), parseInt(this.minute));
                this.commitSelectedDate();
                this.calculateDays();
            },

            setNow() {
                const now = this.roundToNearestFiveMinutes(new Date());
                this.setCalendarState(now);
                this.selectedDate = now;
                this.commitSelectedDate();
                this.calculateDays();
            },

            clear() {
                this.value = '';
                this.displayValue = '';
                this.selectedDate = null;
                this.open = false;
            },

            apply() {
                this.open = false;
            },

            commitSelectedDate() {
                this.value = this.formatValue(this.selectedDate);
                this.displayValue = this.formatDateTime(this.selectedDate);
            },

            syncFromValue(newValue) {
                const parsedDate = this.parseDate(newValue);

                if (!parsedDate) {
                    this.displayValue = '';
                    this.selectedDate = null;
                    return;
                }

                const roundedDate = this.roundToNearestFiveMinutes(parsedDate);
                this.setCalendarState(roundedDate);
                this.selectedDate = roundedDate;
                this.value = this.formatValue(roundedDate);
                this.displayValue = this.formatDateTime(roundedDate);
                this.calculateDays();
            },

            setCalendarState(date) {
                this.year = date.getFullYear();
                this.month = date.getMonth();
                this.day = date.getDate();
                this.hour = date.getHours().toString().padStart(2, '0');
                this.minute = date.getMinutes().toString().padStart(2, '0');
            },

            parseDate(rawValue) {
                if (!rawValue) {
                    return null;
                }

                const v = String(rawValue).trim();

                if (!v) {
                    return null;
                }

                const re1 = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})(?::(\d{2}))?$/;
                const re2 = /^(\d{2})-(\d{2})-(\d{4})[ T](\d{2}):(\d{2})(?::(\d{2}))?$/;

                let m;
                if ((m = v.match(re1))) {
                    const [, y, mo, d, hh, mm, ss] = m;
                    return new Date(`${y}-${mo}-${d}T${hh}:${mm}:${ss ?? '00'}`);
                }

                if ((m = v.match(re2))) {
                    const [, d, mo, y, hh, mm, ss] = m;
                    return new Date(`${y}-${mo}-${d}T${hh}:${mm}:${ss ?? '00'}`);
                }

                const parsed = new Date(v);

                return Number.isNaN(parsed.getTime()) ? null : parsed;
            },

            roundToNearestFiveMinutes(date) {
                const roundedDate = new Date(date);
                const roundedMinute = Math.round(roundedDate.getMinutes() / 5) * 5;
                roundedDate.setMinutes(roundedMinute, 0, 0);

                return roundedDate;
            },

            formatValue(date) {
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const year = date.getFullYear();
                const h = String(date.getHours()).padStart(2, '0');
                const m = String(date.getMinutes()).padStart(2, '0');

                return `${year}-${month}-${day} ${h}:${m}:00`;
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
