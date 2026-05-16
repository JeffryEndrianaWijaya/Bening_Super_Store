@props(['title', 'theme' => 'primary', 'icon' => null])

<div {{ $attributes->merge(['class' => 'card card-' . $theme]) }}>
    <div class="card-header">
        <h3 class="card-title">
            @if ($icon)
                <i class="{{ $icon }} mr-1"></i>
            @endif
            {{ $title }}
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
