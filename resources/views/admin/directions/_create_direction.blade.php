<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>

<div class="modal-header">
    <h5 class="modal-title">Nueva Dependencia / Organismo</h5>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-9 mb-3">
            <div class="mb-3">
                <label for="name_direction" class="form-label">Nombre *</label>
                <input type="text" class="form-control" id="name_direction" placeholder="Escriba el nombre de la Dependencia / Organismo..." name="name" required>
                <div class="invalid-feedback" id="name_error_msg">
                    Por favor ingrese el nombre de la Dependencia / Organismo.
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="mb-3">
                <label for="direction_key" class="form-label">Clave *</label>
                <input type="text" class="form-control" id="direction_key" placeholder="OTDU" name="direction_key" required>
                <div class="invalid-feedback" id="key_error_msg">
                    Por favor ingrese la clave de la Dependencia / Organismo.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="createDirection()">
        Crear
    </div>
</div>