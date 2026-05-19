@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => "btn $color $className"]) }}>
        {!! !empty($label) ? $label : $slot !!}
    </a>
@else
    <button {{ $attributes->merge(['class' => "btn $color $className"]) }}>
        {!! !empty($label) ? $label : $slot !!}
    </button>
@endif
