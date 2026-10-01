@props(['name', 'dismissable' => false, 'show' => false, 'maxWidth' => '2xl'])

@php
    $widthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth];
    $titleId = isset($title) ? (($title instanceof \Illuminate\View\ComponentSlot ? $title->attributes->get('id') : null) ?? 'modal-title-'.\Illuminate\Support\Str::uuid()) : null;
@endphp

<dialog
    x-data="{
        name: @js($name),
        show: @js($show),
        dismissable: @js($dismissable),
        backdropPressed: false,
        init() {
            this.$watch('show', () => this.sync());
            this.$nextTick(() => this.sync());
        },
        sync() {
            if (this.show && !this.$el.open) { this.$el.showModal(); }
            if (!this.show && this.$el.open) { this.$el.close(); }
        },
        outside(event) {
            const bounds = this.$el.getBoundingClientRect();
            return event.target === this.$el && (
                event.clientX < bounds.left || event.clientX > bounds.right ||
                event.clientY < bounds.top || event.clientY > bounds.bottom
            );
        }
    }"
    x-on:open-modal.window="if ($event.detail === name) show = true"
    x-on:close-modal.window="if ($event.detail === name) show = false"
    x-on:cancel.self="if (!dismissable) $event.preventDefault()"
    x-on:close.self="show = $el.open"
    x-on:pointerdown="backdropPressed = outside($event)"
    x-on:click="if (dismissable && backdropPressed && outside($event)) show = false; backdropPressed = false"
    {{ $attributes->except(['focusable', 'dismissable'])->merge([
        'aria-labelledby' => $titleId,
        'class' => 'fixed inset-0 m-auto max-h-[calc(100dvh-3rem)] w-[calc(100%-3rem)] overflow-y-auto rounded-lg border-0 bg-white p-6 text-gray-900 shadow-xl sm:w-full '.$widthClass.' dark:bg-gray-800 dark:text-gray-100 backdrop:bg-gray-200/85 dark:backdrop:bg-gray-900/85',
    ]) }}
>
    <button x-on:click="show = false" type="button"
        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
        <span class="sr-only">Close modal</span>
    </button>

    @if (isset($title))
        @php
            $titleAttributes = $title instanceof \Illuminate\View\ComponentSlot
                ? $title->attributes
                : new \Illuminate\View\ComponentAttributeBag;
        @endphp

        <div {{ $titleAttributes->merge([
            'id' => $titleId,
            'class' => 'pb-4 mb-4 text-2xl font-bold border-b border-gray-400 dark:border-gray-600'
        ]) }}>
            {{ $title }}
        </div>
    @endif

    {{ $slot }}

    @isset($footer)
        <div class="mt-6 pt-4 border-t border-gray-400 dark:border-gray-400">
            {{ $footer }}
        </div>
    @endisset
</dialog>
