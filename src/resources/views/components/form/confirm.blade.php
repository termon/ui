@props([
    'mode' => 'form',
    'confirmingProperty' => null,
    'message' => 'Are you sure?',
    'prepareAction' => null,
    'confirmAction' => null,
    'cancelAction' => null,
    'variant' => 'red',
    'confirmVariant' => 'red',
    'icon' => null,
    'target' => null,
])

@php
    if (! in_array($mode, ['form', 'livewire'], true)) {
        throw new \InvalidArgumentException('Confirmation mode must be form or livewire.');
    }

    if ($mode === 'livewire' && (! $confirmingProperty || ! $prepareAction || ! $confirmAction || ! $cancelAction)) {
        throw new \InvalidArgumentException('Livewire confirmation requires a confirming property and prepare, confirm, and cancel action names.');
    }

    $confirmVariant ??= $variant;
    $target ??= implode(',', array_filter([$prepareAction, $confirmAction, $cancelAction]));
@endphp

<div
    x-data="{
        livewire: @js($mode === 'livewire'),
        property: @js($confirmingProperty),
        prepareAction: @js($prepareAction),
        confirmAction: @js($confirmAction),
        cancelAction: @js($cancelAction),
        busy: false,
        backdropPressed: false,
        open() {
            if (this.livewire) {
                return this.run(this.prepareAction);
            }
            this.sync(true);
        },
        submit() {
            if (this.livewire) {
                return this.run(this.confirmAction);
            }
            const form = this.$el.closest('form');
            if (!form || this.busy) return;
            this.sync(false);
            if (!form.reportValidity()) return;
            this.busy = true;
            let submission;
            const observe = event => { submission = event; };
            form.addEventListener('submit', observe, { once: true });
            try {
                form.requestSubmit();
            } finally {
                form.removeEventListener('submit', observe);
                if (!submission || submission.defaultPrevented) this.busy = false;
            }
        },
        sync(confirming) {
            const dialog = this.$refs.dialog;
            if (confirming && !dialog.open) {
                dialog.showModal();
                this.$refs.cancel.focus();
            }
            if (!confirming && dialog.open) {
                dialog.close();
                this.$nextTick(() => {
                    if (!this.busy) this.$refs.trigger.focus();
                });
            }
        },
        async run(action) {
            if (this.busy) return;
            this.busy = true;
            try {
                await this.$wire.$call(action);
            } finally {
                this.busy = false;
                this.sync(this.$wire.$get(this.property));
                if (this.$refs.dialog.open && action === this.prepareAction) {
                    this.$nextTick(() => this.$refs.cancel.focus());
                }
                if (!this.$refs.dialog.open) {
                    this.$nextTick(() => this.$refs.trigger.focus());
                }
            }
        },
        dismiss() {
            if (this.busy) return;
            if (this.livewire) {
                if (this.$wire.$get(this.property)) this.run(this.cancelAction);
            } else {
                this.sync(false);
            }
        },
        outside(event) {
            const bounds = this.$refs.dialog.getBoundingClientRect();
            return event.target === this.$refs.dialog && (
                event.clientX < bounds.left || event.clientX > bounds.right ||
                event.clientY < bounds.top || event.clientY > bounds.bottom
            );
        }
    }"
    x-effect="if (livewire) sync($wire.$get(property))"
    x-id="['confirmation-message']"
    data-confirmation="modal"
    class="inline-flex items-center"
>
    <style>
        body:has(dialog:modal) { overflow: hidden; }
    </style>

    <x-ui::button
        type="button"
        x-ref="trigger"
        x-on:click="open()"
        x-bind:disabled="busy || (livewire && $wire.$get(property))"
        aria-haspopup="dialog"
        :variant="$variant"
        :icon="$icon"
        {{ $attributes->merge($mode === 'livewire' ? ['wire:loading.attr' => 'disabled', 'wire:target' => $target] : []) }}
    >
        {{ $slot }}
    </x-ui::button>

    <dialog
        x-ref="dialog"
        @if ($mode === 'livewire')
            wire:ignore.self
        @endif
        role="alertdialog"
        x-bind:aria-labelledby="$id('confirmation-message')"
        x-bind:aria-busy="busy"
        x-on:cancel.prevent="dismiss()"
        x-on:close.self="dismiss()"
        x-on:pointerdown="backdropPressed = outside($event)"
        x-on:click="if (backdropPressed && outside($event)) dismiss(); backdropPressed = false"
        class="fixed inset-0 m-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-sm overflow-y-auto rounded-xl border border-slate-200 bg-white p-6 text-left text-slate-900 shadow-xl backdrop:bg-slate-950/40 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
    >
        <p x-bind:id="$id('confirmation-message')" class="text-sm leading-6">{{ $message }}</p>

        <div class="mt-6 flex justify-end gap-2">
            <x-ui::button type="button" :variant="$confirmVariant" x-on:click="submit()" x-bind:disabled="busy">
                Yes
            </x-ui::button>
            <x-ui::button type="button" variant="light" x-ref="cancel" x-on:click="dismiss()" x-bind:disabled="busy" autofocus>
                No
            </x-ui::button>
        </div>
    </dialog>
</div>
