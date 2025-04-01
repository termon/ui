
@props(['items', 'size' => 10, 'options' => ['10' => 10, '25' => 25, '50' => 50, '100' => 100]])
@php
    $size = request()->get('size', $size);
@endphp
<div class="flex items-center gap-2 p-4">
    @if ($items instanceof \Illuminate\Pagination\AbstractPaginator )        
        {{ $items->appends(request()->query())->links('pagination::tailwind') }}       
        <form method="get" action="{{ request()->url() }}" class="flex items-center gap-2">
            @foreach(request()->except(['size', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <label for="size" class="text-sm text-nowrap">Items per page</label>
            <select id="size" name="size" onchange="this.form.submit()"
                {{ $attributes->merge(['class' => 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5']) }}>
                @foreach ($options as $key => $val)
                    <option value="{{ $key }}" {{ $key == $size ? 'selected' : '' }}>{{ $val }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif
</div>

