@props(['name', 'id' => null, 'value' => '', 'datetime' => false, 'min' => null, 'max' => null, 'locale' => 'en-GB'])
@php
    $inputId = $id ?? $name;
    $factory = \Termon\Ui\DatePicker::source();
    $options = compact('value', 'datetime', 'min', 'max', 'locale');
    $wrapper = $attributes->filter(fn ($v, $k) => $k === 'class' || str_starts_with($k, 'wire:') || str_starts_with($k, 'x-'));
    $field = $attributes->except(array_merge(array_keys($wrapper->getAttributes()), ['required', 'pattern', 'minlength', 'maxlength']));
@endphp
<div x-data="({{ $factory }})(@js($options))" x-modelable="value" x-id="['calendar']" {{ $wrapper->merge(['class' => 'relative']) }}>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" :value="value" {{ $attributes->only(['disabled', 'form']) }} />
    <div class="relative">
        <input x-ref="input" id="{{ $inputId }}" type="text" value="{{ $value }}" x-model="text"
            @input="edit()" @keydown.escape.prevent="close(false)"
            aria-required="{{ $attributes->get('required', false) ? 'true' : 'false' }}"
            aria-haspopup="dialog" :aria-expanded="open" :aria-controls="$id('calendar')"
            {{ $field->merge(['placeholder' => $datetime ? 'YYYY-MM-DD HH:MM:SS' : 'YYYY-MM-DD']) }}
            class="w-full rounded-lg border border-gray-300 p-2.5 pr-10 dark:bg-gray-700 dark:text-white" />
        <button type="button" @click.stop="open ? close() : show()" :disabled="$refs.input?.disabled || $refs.input?.readOnly"
            aria-label="Open calendar" :aria-controls="$id('calendar')" :aria-expanded="open"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-500 disabled:opacity-50 dark:text-gray-300 dark:hover:text-white">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
            </svg>
        </button>
    </div>
    <template x-teleport="body">
        <div x-ref="panel" hidden :hidden="!open" :id="$id('calendar')" role="dialog" aria-label="Choose date"
            :style="panelStyle" @click.outside="close(false)" @keydown.escape.stop.prevent="close()"
            @keydown.tab="$nextTick(() => { if (!$refs.panel.contains(document.activeElement)) close(false); })"
            class="fixed z-1000 w-80 max-w-[calc(100vw-1rem)] max-h-[80vh] overflow-auto rounded-lg border border-gray-200/70 bg-white p-4 shadow-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <div class="flex items-center justify-between gap-2">
                <button type="button" @click="shiftMonth(-12)" aria-label="Previous year" title="Previous year" class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 19l-7-7 7-7M11 19L4 12l7-7" />
                            </svg>
                        </button>
                <button type="button" @click="shiftMonth(-1)" aria-label="Previous month" title="Previous month" class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                <span aria-live="polite" x-text="title"></span>
                <button type="button" @click="shiftMonth(1)" aria-label="Next month" title="Next month" class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                <button type="button" @click="shiftMonth(12)" aria-label="Next year" title="Next year" class="p-0 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 5l7 7-7 7m7-14l7 7-7 7" />
                            </svg>
                        </button>
            </div>
            <div class="grid grid-cols-7 text-center" role="group" aria-label="Calendar days" @keydown="navigate($event)">
                <template x-for="(day, index) in weekdays" :key="index"><span class="py-2 text-xs" x-text="day"></span></template>
                <template x-for="cell in cells" :key="cell.key">
                    <div>
                        <template x-if="!cell.blank">
                            <button type="button" :data-day="cell.key" :tabindex="cursor === cell.key ? 0 : -1"
                                :aria-label="cell.key" :aria-pressed="value?.slice(0, 10) === cell.key"
                                :aria-disabled="!dayAllowed(cell.key)" @focus="cursor = cell.key" @click="choose(cell.key)"
                                :class="{ 'bg-blue-600 text-white': value?.slice(0, 10) === cell.key, 'opacity-40': !dayAllowed(cell.key) }"
                                class="h-9 w-9 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500" x-text="cell.day"></button>
                        </template>
                    </div>
                </template>
            </div>
            @if($datetime)
                <label class="mt-3 block">Time
                    <input type="time" step="1" :value="value?.slice(11) || '00:00:00'" @change="changeTime($event.target.value)"
                        class="rounded border p-1 dark:bg-gray-700" />
                </label>
            @endif
            <div class="mt-3 flex justify-between gap-2">
                <button type="button" @click="today()">{{ $datetime ? 'Now' : 'Today' }}</button>
                <button type="button" @click="clear()">Clear</button>
                <button type="button" @click="close()">Close</button>
            </div>
        </div>
    </template>
</div>
