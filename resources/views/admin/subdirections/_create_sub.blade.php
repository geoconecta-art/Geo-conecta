<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>

<div class="modal-header">
    <h5 class="modal-title">Nueva Área</h5>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="mb-3">
                <label for="direction" class="form-label">Dependencia / Organismo</label>
                <select class="form-control" id="direction" name="id_direction" @if (!Auth::user()->hasRole("Super Administrador")) disabled @endif>
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($areas as $area)
                        <option value="{{$area['_id']}}" @if ( $area->id == Auth::user()->id_area ) selected @endif >
                            {{$area['name']}} 
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="error_msg_dir">
                    Por favor seleccione una Dependencia / Organismo.
                </div>
            </div>
        </div>
        
        <div class="col-md-12 mb-3">
            <div class="mb-3">
                <label for="name_subdirection" class="form-label">Nombre (Área)</label>
                <input type="text" class="form-control" id="name_subdirection" placeholder="Escriba el nombre de la Subdirección/Área..." name="name">
                <div class="invalid-feedback" id="error_msg_sub">
                    Por favor ingrese el nombre de la Área.
                </div>
            </div>
        </div>

        <div class="invalid-feedback ml-3" id="error_msg_all">
            Por favor ingrese todos los datos.
        </div>
    </div>    
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="createSubdirection()">
        Crear
    </div>
</div>