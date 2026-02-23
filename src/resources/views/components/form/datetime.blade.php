@props([
    'name', 
    'label', 
    'value' => ''
])

<!-- make sure $value is in 'YYYY-MM-DD HH:mm:ss' format for proper initialization -->
<div x-data="dateTimePicker(@js($value))" x-cloak>

    <div class="relative">
        <input x-ref="input" name="{{ $name }}" value="{{ $value }}" type="text" @click="open = !open"
            x-model="displayValue" x-on:keydown.escape="open = false" placeholder="Select date and time" readonly
            class="m-1 flex w-full h-10 px-3 py-2 text-sm border rounded-md
                    bg-white text-gray-600 border-gray-300
                    placeholder:text-gray-400 focus:border-gray-300
                    focus:outline-none focus:ring-1 focus:ring-gray-700
                    disabled:cursor-not-allowed disabled:opacity-50
                    dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600
                    dark:placeholder:text-gray-400 dark:focus:ring-gray-700" />

        <div @click="open = !open; if(open){ $refs.input.focus() }"
            class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>

        <div x-show="open" x-transition @click.away="open = false"
            class="absolute left-0 z-50 p-4 mt-2 bg-white border rounded-lg shadow w-[17rem]
                    border-gray-200/70 dark:bg-gray-900 dark:border-gray-700">
            <!-- Calendar Header -->
            <div class="flex items-center justify-between mb-2">
                <div>
                    <span x-text="monthNames[month]"
                        class="text-lg font-bold text-gray-800 dark:text-gray-200"></span>
                    <span x-text="year" class="ml-1 text-lg font-normal text-gray-600 dark:text-gray-400"></span>
                </div>
                <div>
                    <button @click="previousMonth()" type="button"
                        class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextMonth()" type="button"
                        class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7" />
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
    </div>

</div>

<script>
    function dateTimePicker(initialValue) {
        return {
            open: false,
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
                }
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
                this.minute = now.getMinutes().toString().padStart(2, '0');
                this.selectedDate = new Date(this.year, this.month, this.day, now.getHours(), now.getMinutes());
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
