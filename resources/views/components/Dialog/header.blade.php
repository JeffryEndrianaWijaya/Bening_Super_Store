@props(['title'])

<div class="modal-header">
    <h5 class="modal-title" id="dialogLabel">{{ $title }}</h5>
    <button type="button" class="close" data-dismiss="alert" data-target="#"
        onclick="$(this).closest('.modal').modal('hide')" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
