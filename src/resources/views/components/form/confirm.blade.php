@props([
    'message' => 'Are you sure?',
    'variant' => 'red'
])

<div x-data="{ confirm: false }" class="inline-flex items-center gap-2">

    {{-- Default state --}}
    <x-ui::button x-show="!confirm" type="button"  @click="confirm=true" variant="{{ $variant }}" {{ $attributes }}>
        {{ $slot }}
    </x-ui::button>

    {{-- Confirmation state --}}
    <div x-show="confirm" class="flex items-center gap-2" role="alertdialog">
        
        <span class="text-sm text-gray-700">{{ $message }}</span>

        <x-ui::button type="button" @click="$el.closest('form').submit()" variant="{{ $variant }}">
            Yes
        </x-ui::button>

        <x-ui::button type="button" @click="confirm=false" variant="light">
            No
        </x-ui::button>
    </div>
</div>


