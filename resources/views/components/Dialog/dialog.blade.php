@props(['id', 'size' => ''])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size ? 'modal-' . $size : '' }}" role="document">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
