@props(['skip' => null])

@php
    $filterableParams = ['category', 'min', 'max', 'sort', 'list', 'q'];
@endphp

@foreach($filterableParams as $param)
    @if(request()->has($param))
        @if(is_array($skip))
            @foreach($skip as $value)
                @if($param === $value)
                    @continue
                @endif
            @endforeach
        @else
            @if($param === $skip)
                @continue
            @endif
        @endif

        @if($param === 'list' && request()->query('list') === 'list')
            @continue
        @endif

        @if(is_array(request()->query($param)))
            @foreach(request()->query($param) as $value)
                <input type="hidden" name="{{ $param }}[]" value="{{ $value }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $param }}" value="{{ request()->query($param) }}">
        @endif
    @endif
@endforeach
