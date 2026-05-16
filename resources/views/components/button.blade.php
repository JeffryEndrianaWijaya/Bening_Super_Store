<button {{ $attributes->merge(['class' => "btn $color $className"]) }}>
    {{ $label ?? '' }}
</button>
