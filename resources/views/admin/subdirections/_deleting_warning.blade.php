<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>

<div class="modal-header">
    <h5 class="modal-title text-danger">
        <em class="icon ni ni-caution"></em>
        <strong class="me-auto" id="title_deleting">Advertencia</strong>
    </h5>
</div>

<div class="modal-body" id="text_delete_info">
    <b>{{ $subarea->name }}</b> será eliminada, así como sus <b>Inventarios</b> asociados. Una vez que se confirme esta acción no se podrá deshacer. <br><br>
    ¿Está seguro de continuar?
</div>

<div class="modal-footer">
    <div class="btn btn-dim btn-light" data-dismiss="modal" id="delete_subdirection_cancel_btn">
        Cancelar
    </div>

    <div class="btn btn-dim btn-danger" onclick="deleteSubdirection('{{$subarea->id}}')" id="delete_subdirection_accept_btn">
        Eliminar
    </div>
</div>