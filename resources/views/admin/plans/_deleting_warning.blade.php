<div class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()" style="cursor: pointer;">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <h5 class="modal-title text-danger">
        <em class="icon ni ni-caution"></em>
        <strong class="me-auto" id="title_deleting">Advertencia</strong>
    </h5>
</div>

<div class="modal-body" id="text_delete_info">
    El inventario<b> {{ $plan->name }} </b>será eliminado, así como los <b>Datos</b> y <b>Mapas</b> asociados.  Una vez que se confirme está acción, no se podrán deshacer los cambios.<br>
    ¿Está seguro de continuar?
</div>

<div class="modal-footer">
    <div class="btn btn-dim btn-light" data-dismiss="modal" id="delete_plan_cancel_btn" onclick="closeModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-danger" onclick="deletePlan('{{$plan->id}}')" id="delete_plan_accept_btn">
        Eliminar
    </div>
</div>