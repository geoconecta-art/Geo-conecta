<div class="close" data-dismiss="modal" aria-label="Close" onclick="cerrarModal()">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <h5 class="modal-title">Editar Colonia</h5>
</div>

<div class="modal-body">
    <div class="row">
        
        <div class="col-md-9 mb-1">
            <div class="mb-1">
                <label for="suburb_name" class="form-label fw-bold">Nombre *</label>
                <input type="text" class="form-control" id="suburb_name" placeholder="Escriba el nombre de la nueva colonia..." name="suburb_name" value="{{ $suburb->name }}" required>
                <div class="invalid-feedback" id="name_error_msg">
                    Por favor ingrese el nombre de la Colonia.
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-1">
            <div class="mb-1">
                <label for="suburb_cp" class="form-label fw-bold">Código Postal *</label>
                <input type="text" class="form-control" id="suburb_cp" placeholder="52975" name="suburb_cp" value="{{ $suburb->cp }}" required>
                <div class="invalid-feedback" id="cp_error_msg">
                    Por favor ingrese el Código Postal.
                </div>
            </div>
        </div>

        <div class="col-12 invalid-feedback" id="error_msg_all">
            Por favor ingrese los valores necesarios.
        </div>

    </div>
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal" onclick="cerrarModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="updateSuburb('{{ $suburb->id }}')">
        Actualizar
    </div>
</div>