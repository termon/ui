@props([
    'name',
    'id' => null,
    'label' => null,
    'description' => null,
    'value' => [],
    'options' => [],
    'variant' => 'plain',
    'icon' => null,
])

@php
    $selectedValues = collect(is_array($value) ? $value : [$value])->map(fn ($selectedValue) => (string) $selectedValue)->all();
    $inputName = str_ends_with($name, '[]') ? $name : $name.'[]';
    $usedIds = [];
    $errorName = str_ends_with($name, '[]') ? substr($name, 0, -2) : $name;
    $errorId = ($id ?? $errorName).'-error';
    if ($errors->has($errorName)) {
        $describedBy = trim($attributes->get('aria-describedby', '').' '.$errorId);
        $attributes = $attributes->except(['aria-describedby', 'aria-invalid'])->merge([
            'aria-describedby' => $describedBy,
            'aria-invalid' => 'true',
        ]);
    }

    $optionWrapperClasses = match ($variant) {
        'plain' => 'space-y-3',
        'card' => 'space-y-2',
        default => throw new InvalidArgumentException("Unsupported checkbox-group variant [{$variant}]."),
    };

    $checkboxClasses = match ($variant) {
        'plain' => 'flex items-start gap-3',
        'card' => 'flex items-start gap-3 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200',
    };
@endphp

<fieldset {{ $attributes->only('class')->class(['w-full space-y-2']) }}>
    @isset($label)
        <legend class="mb-1.5 flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300">
            @isset($icon)
                <x-ui::svg :icon="$icon" class="h-4 w-4 text-slate-400 dark:text-slate-500" />
            @endisset
            {{ $label }}
        </legend>
    @endisset

    @if (filled($description))
        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    @endif

    <div class="{{ $optionWrapperClasses }}">
        @foreach ($options as $optionValue => $optionLabel)
            @php
                $option = is_array($optionLabel) ? $optionLabel : ['label' => $optionLabel];
                $resolvedValue = (string) ($option['value'] ?? $optionValue);
                $resolvedLabel = $option['label'] ?? $resolvedValue;
                $resolvedDescription = $option['description'] ?? null;
                $baseId = $id !== null ? $id.'-'.$loop->iteration : str($inputName.'-'.$resolvedValue)->replaceMatches('/[^A-Za-z0-9\-_:.]/', '-')->trim('-')->value();
                $optionId = $baseId;
                $suffix = 2;
                while (in_array($optionId, $usedIds, true)) {
                    $optionId = $baseId.'-'.$suffix++;
                }
                $usedIds[] = $optionId;
                $isDisabled = (bool) ($option['disabled'] ?? false);
            @endphp

            <x-ui::form.checkbox
                :id="$optionId"
                :name="$inputName"
                :value="$resolvedValue"
                :label="$resolvedLabel"
                :description="$resolvedDescription"
                :checked="in_array($resolvedValue, $selectedValues, true)"
                :disabled="$isDisabled"
                class="{{ $checkboxClasses }}"
                {{ $attributes->except(['class', 'name', 'label', 'description', 'value', 'options', 'variant', 'icon']) }}
            />
        @endforeach
    </div>

    <x-ui::form.error :id="$errorId" for="{{ $errorName }}" />
</fieldset>
