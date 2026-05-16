@props(['type' => 'info', 'dismissible' => true, 'title' => null, 'icon' => null])

@php
    $icons = [
        'info' => 'fas fa-info-circle',
        'success' => 'fas fa-check-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'danger' => 'fas fa-ban',
    ];
    $iconClass = $icon ?? ($icons[$type] ?? 'fas fa-info-circle');
@endphp

<div {{ $attributes->merge(['class' => 'alert alert-' . $type . ($dismissible ? ' alert-dismissible' : '')]) }}>
    @if ($dismissible)
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @endif

    @if ($title)
        <h5><i class="icon {{ $iconClass }}"></i> {{ $title }}</h5>
    @endif

    {{ $slot }}
</div>
