@props([   
    'name',
    'value' => null,
    'options' => [],
    'placeholder' => 'Choose option...',
])

 {{-- classes appearance-none and bg-transparent are needed by safari --}}
<select id="{{ $name }}" name="{{ $name }}"
    {{ $attributes->except(['icon', 'label'])->merge(['class' => 'block w-full appearance-none rounded-lg border border-slate-300 bg-white p-2.5 text-slate-900 shadow-xs transition focus:border-blue-600 focus:outline-none focus:ring-3 focus:ring-blue-600/15 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500/20 dark:disabled:bg-slate-900']) }}>
    <option value="" {{ $value ? '' : 'selected' }} disabled >{{ $placeholder }}</option>
    @foreach ($options as $key => $val)
        <option value="{{ $key }}" {{ $key == $value ? 'selected' : '' }}>{{ $val }}</option>
    @endforeach
</select>

