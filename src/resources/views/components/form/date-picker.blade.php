@props(['name', 'label', 'value' => ''])

<div x-data="{
  datePickerOpen: false,
  datePickerValue: '',
  datePickerFormat: 'M d, Y',
  datePickerMonth: '',
  datePickerYear: '',
  datePickerDay: '',
  datePickerDaysInMonth: [],
  datePickerBlankDaysInMonth: [],
  datePickerMonthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
  datePickerDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

  datePickerDayClicked(day) {
    let selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day);
    this.datePickerDay = day;
    this.datePickerValue = this.datePickerFormatDate(selectedDate);
    this.datePickerIsSelectedDate(day);
    this.datePickerOpen = false;
  },

  datePickerPreviousMonth(){
    if (this.datePickerMonth == 0) {
        this.datePickerYear--;
        this.datePickerMonth = 12;
    }
    this.datePickerMonth--;
    this.datePickerCalculateDays();
  },

  datePickerNextMonth(){
    if (this.datePickerMonth == 11) {
        this.datePickerMonth = 0;
        this.datePickerYear++;
    } else {
        this.datePickerMonth++;
    }
    this.datePickerCalculateDays();
  },

  datePickerIsSelectedDate(day) {
    const d = new Date(this.datePickerYear, this.datePickerMonth, day);
    return this.datePickerValue === this.datePickerFormatDate(d);
  },

  datePickerIsToday(day) {
    const today = new Date();
    const d = new Date(this.datePickerYear, this.datePickerMonth, day);
    return today.toDateString() === d.toDateString();
  },

  datePickerCalculateDays() {
    let daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
    let dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
    let blankdaysArray = [];
    for (var i = 1; i <= dayOfWeek; i++) {
        blankdaysArray.push(i);
    }
    let daysArray = [];
    for (var i = 1; i <= daysInMonth; i++) {
        daysArray.push(i);
    }
    this.datePickerBlankDaysInMonth = blankdaysArray;
    this.datePickerDaysInMonth = daysArray;
  },

  datePickerFormatDate(date) {
    let formattedDay = this.datePickerDays[date.getDay()];
    let formattedDate = ('0' + date.getDate()).slice(-2);
    let formattedMonth = this.datePickerMonthNames[date.getMonth()];
    let formattedMonthShortName = formattedMonth.substring(0, 3);
    let formattedMonthInNumber = ('0' + (parseInt(date.getMonth()) + 1)).slice(-2);
    let formattedYear = date.getFullYear();

    if (this.datePickerFormat === 'M d, Y') return `${formattedMonthShortName} ${formattedDate}, ${formattedYear}`;
    if (this.datePickerFormat === 'MM-DD-YYYY') return `${formattedMonthInNumber}-${formattedDate}-${formattedYear}`;
    if (this.datePickerFormat === 'DD-MM-YYYY') return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`;
    if (this.datePickerFormat === 'YYYY-MM-DD') return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`;
    if (this.datePickerFormat === 'D d M, Y') return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`;
    
    return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
  },

  datePickerSetToday() {
    const today = new Date('{{ $value }}');
    this.datePickerYear = today.getFullYear();
    this.datePickerMonth = today.getMonth();
    this.datePickerDay = today.getDate();
    this.datePickerValue = this.datePickerFormatDate(today);
    this.datePickerCalculateDays();
    this.datePickerOpen = false;
  }
}" 
x-init="
    currentDate = new Date('{{ $value }}');
    if (datePickerValue) {
        currentDate = new Date(Date.parse(datePickerValue));
    }
    datePickerMonth = currentDate.getMonth();
    datePickerYear = currentDate.getFullYear();
    datePickerDay = currentDate.getDay();
    datePickerValue = datePickerFormatDate(currentDate);
    datePickerCalculateDays();
" x-cloak>

    <div class="w-full">
        @isset($label)
          <label for="datepicker" class="block m-1 text-sm font-medium text-gray-500 dark:text-gray-300">{{$label}}</label>
        @endisset

        <div class="relative">
            <input
              x-ref="datePickerInput"
              name="{{ $name }}"
              value="{{ $value }}"
              type="text"
              @click="datePickerOpen = !datePickerOpen"
              x-model="datePickerValue"
              x-on:keydown.escape="datePickerOpen = false"
              placeholder="Select date"
              readonly
              class="m-1 flex w-full h-10 px-3 py-2 text-sm border rounded-md
                     bg-white text-gray-600 border-gray-300
                     placeholder:text-gray-400 focus:border-gray-300
                     focus:outline-none focus:ring-1 focus:ring-gray-700
                     disabled:cursor-not-allowed disabled:opacity-50
                     dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600
                     dark:placeholder:text-gray-400 dark:focus:ring-gray-700"
            />

            <div
              @click="datePickerOpen = !datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }"
              class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>

            <div
              x-show="datePickerOpen"
              x-transition
              @click.away="datePickerOpen = false"
              class="z-50 p-4 mt-2 bg-white border rounded-lg shadow w-[17rem]
                     border-gray-200/70 dark:bg-gray-900 dark:border-gray-700"
            >
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span x-text="datePickerMonthNames[datePickerMonth]" class="text-lg font-bold text-gray-800 dark:text-gray-200"></span>
                        <span x-text="datePickerYear" class="ml-1 text-lg font-normal text-gray-600 dark:text-gray-400"></span>
                    </div>
                    <div>
                        <button @click="datePickerPreviousMonth()" type="button"
                          class="inline-flex p-1 transition duration-100 ease-in-out rounded-full
                                 cursor-pointer focus:outline-none focus:shadow-outline
                                 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click="datePickerNextMonth()" type="button"
                          class="inline-flex p-1 transition duration-100 ease-in-out rounded-full
                                 cursor-pointer focus:outline-none focus:shadow-outline
                                 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 mb-3">
                    <template x-for="(day, index) in datePickerDays" :key="index">
                        <div class="px-0.5">
                            <div x-text="day" class="text-xs font-medium text-center text-gray-800 dark:text-gray-300"></div>
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-7">
                    <template x-for="blankDay in datePickerBlankDaysInMonth">
                        <div class="p-1 text-sm text-center border border-transparent"></div>
                    </template>

                    <template x-for="(day, dayIndex) in datePickerDaysInMonth" :key="dayIndex">
                        <div class="px-0.5 mb-1 aspect-square">
                            <div
                                x-text="day"
                                @click="datePickerDayClicked(day)"
                                :class="{
                                    'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white': datePickerIsToday(day),
                                    'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700':
                                        !datePickerIsToday(day) && !datePickerIsSelectedDate(day),
                                    'bg-gray-800 text-white hover:bg-opacity-75 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200':
                                        datePickerIsSelectedDate(day)
                                }"
                                class="flex items-center justify-center text-sm leading-none text-center
                                       rounded-full cursor-pointer h-7 w-7 transition-colors"
                            ></div>
                        </div>
                    </template>
                </div>

                <!-- "Today" button -->
                <div class="mt-3 text-right">
                    <button
                        type="button"
                        @click="datePickerSetToday"
                        class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200
                               dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                    >
                        Today
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
