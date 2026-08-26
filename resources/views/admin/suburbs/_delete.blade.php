<div class="close" data-dismiss="modal" aria-label="Close" onclick="cerrarModal()">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <h5 class="modal-title text-danger">
        <em class="icon ni ni-caution"></em>
        <strong class="me-auto" id="title_deleting">Advertencia</strong>
    </h5>
</div>

<div class="modal-body">
    <div class="row">

        <div class="modal-body" id="text_delete_info">
            La colonia<b> {{ $suburb->name }} </b>será eliminada. Una vez que se confirme está acción, no se podrán deshacer los cambios.<br>
            ¿Está seguro de continuar?
        </div>

    </div>
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-light" id="suburb-cancel-btn" data-dismiss="modal" onclick="cerrarModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-danger" id="suburb-delete-btn" onclick="deleteSuburb('{{ $suburb->id }}')">
        Eliminar
    </div>
</div>