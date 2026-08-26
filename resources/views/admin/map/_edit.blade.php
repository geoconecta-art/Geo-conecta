<div class="close" data-dismiss="modal" aria-label="Close" onclick="cerrarModal()">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <h5 class="modal-title">Editar Mapa</h5>
</div>

<div class="modal-body">
    <div class="row">
        
        <div class="col-md-12 mb-1">
            <div class="mb-1">
                <label for="name_map" class="form-label fw-bold">Nombre *</label>
                <input type="text" class="form-control" id="name_map" placeholder="Escriba el nombre de la Dirección..." name="name_map" value="{{ $map->name }}" required>
                <div class="invalid-feedback" id="name_error_msg">
                    Por favor ingrese el nombre del mapa.
                </div>
            </div>
        </div>

        <div class="col-12" id="plans_container">
            <span class="fw-bold">
                Inventarios
            </span>

            <ul id="list_map_options">
                @foreach ($plans as $plan)
                    <li class="d-flex justify-content-between w-100" id="li-{{ $plan->id }}">
                        <span>
                            {{ $plan->name }}
                        </span>
                        <input type="checkbox" name="id_plans[]" value="{{ $plan->id }}" checked hidden>

                        <div class="btn btn-plan-delete" id="{{ $plan->id }}">
                            <em class="icon ni ni-cross"></em>
                        </div>
                    </li>
                @endforeach   
            </ul>
        </div>

    </div>
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal" onclick="cerrarModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="editMap('{{$map->id}}')">
        Crear
    </div>
</div>

<script src="{{ asset('assets/js/geoconecta/maps/create_map.js') }}"></script>