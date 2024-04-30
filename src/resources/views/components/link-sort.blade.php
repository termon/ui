@props(['name'])

@php
    $sort = request()->input('sort') ?? 'id';
    $direction = request()->input('direction') ?? 'asc';

    // check if named field being sorted and display asc or desc icon otherwise display sort icon
    $url = ($name == $sort && $direction == 'asc') ? 
        request()->fullUrlWithQuery(['sort'=>$name, 'direction'=>'desc']) :
        request()->fullUrlWithQuery(['sort'=>$name, 'direction'=>'asc']);    
@endphp

<div class="flex items-center">
    {{$slot}}
    @if ($name == $sort)
        <a href="{{ $url }}"><x-ui::svg.sort direction="{{$direction}}"/></a>        
    @else
        <a href="{{ $url }}"><x-ui::svg.sort /></a>
    @endif
</div>
