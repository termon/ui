@props(['name', 'paginator' => null, 'defaultSort' => 'id', 'sortParameter' => null, 'directionParameter' => null])

@php
    // Share the paginator's query namespace when supplied.
    $pageName = $paginator?->getPageName() ?? 'page';
    $prefix = $pageName === 'page' ? '' : preg_replace('/_page$/', '', $pageName) . '_';
    $sortParameter ??= $prefix . 'sort';
    $directionParameter ??= $prefix . 'direction';

    // get sort and direction from query string or set default values
    $sort = request()->input($sortParameter) ?? $defaultSort;
    $direction = request()->input($directionParameter) ?? 'asc';

    // generate link url based on current sort and direction
    $url =
        $name == $sort && $direction == 'asc'
            ? request()->fullUrlWithQuery([$sortParameter => $name, $directionParameter => 'desc'])
            : request()->fullUrlWithQuery([$sortParameter => $name, $directionParameter => 'asc']);
@endphp

<div class="flex items-center">
    <span>{{ $slot }}</span>
    <a href="{{ $url }}">
        @if ($name == $sort && $direction == 'asc')
            <x-ui::svg icon="bars-up" size="sm" />
        @elseif ($name == $sort && $direction == 'desc')
            <x-ui::svg icon="bars-down" size="sm" />
        @else
            <x-ui::svg icon="bars" size="sm" />
        @endif
    </a>
</div>
