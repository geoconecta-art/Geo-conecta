<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>

<div class="modal-header">
    <h5 class="modal-title">Editar Inventario</h5>
</div>

<form class="modal-body" id="edit_plan_form">
    <div class="row">
        <div class="mb-3 col-12">
            <label for="direction" class="form-label">Dependencia / Organismo</label>
            <select class="form-control" id="direction" name="id_area" @if (!Auth::user()->hasRole("Super Administrador")) disabled @endif >
                <option value="" selected>Seleccione una opción</option>
                    
                @foreach ($typeDependencies as $type)
                <optgroup label="{{ $type->name }}">
                    @foreach ($type->dependencies as $dependency)
                        <option value="{{ $dependency->id }}" @if ($dependency->id == $plan->id_area) selected @endif @if ( Auth::user()->id_area == $dependency->id ) selected @endif >
                            {{$dependency['name']}}
                        </option>    
                    @endforeach
                    {{-- <option value="{{ $area->id }}" @if ( Auth::user()->id_area == $area->id ) selected @endif >
                        {{$area['name']}}
                    </option> --}}
                </optgroup>
                @endforeach
            </select>
            <div class="invalid-feedback" id="error_msg_dir">
                Por favor seleccione una Dependencia / Organismo.
            </div>
        </div>

        <div class="mb-3 col-12">
            <label for="subdirection" class="form-label">Área</label>
            <select class="form-control" id="subdirection" name="id_subarea">
                <option value="">Seleccione una opción</option>
                @foreach ($subareas as $subarea)
                    <option value="{{$subarea->id}}" class="subarea_option {{ $subarea->id_dependency }} " @if ($subarea->id == $plan->subarea->id) selected @endif> 
                        {{$subarea->name}} 
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback" id="error_msg_sub">
                Por favor seleccione una Área.
            </div>
        </div>

        <div class="col-12 mb-3">
            <label for="name_planeacion" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name_planeacion" placeholder="Escriba el nombre del Inventario..." name="name"
                oninput="convertToUppercase('name_planeacion')" value="{{$plan->name}}">
            <div class="invalid-feedback" id="error_msg_name">
                Por favor ingrese el nombre del Inventario.
            </div>
        </div>
        
        <div class="col-12 row pr-0">
            <div class="col-12 col-md-4">
                <label for="type_plan" class="form-label">Tipo de Inventario</label>
                <select class="form-control" id="type_plan" name="type_plan">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($type_plans as $type)
                        <option value="{{ $type }}" @if ( isset( $plan->type_plan ) ) @if ( $plan->type_plan == $type) selected @endif @endif> 
                            {{ $type }} 
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="error_msg_type_plan">
                    Por favor seleccione un Tipo de Inventario.
                </div>
            </div>
    
            <div class="col-12 col-md-4">
                <label for="geometry" class="form-label">Geometría</label>
                <select class="form-control" id="geometry" name="geometry" disabled>
                    @if ( $plan->geometry == 'LineString' )
                        <option value="LineString" selected> Línea </option>
                    @else
                        <option value="Point" selected> Punto </option>
                    @endif
                </select>
                <div class="invalid-feedback" id="error_msg_geo">
                    Por favor seleccione una geometría.
                </div>
            </div>

            <div class="col-md-4 col-12">
                <label for="color" class="form-label">Color</label>
                <input type="color" class="form-control" id="color" name="plan_color" value="{{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }}">
                <div class="invalid-feedback" id="error_msg_color">
                    Por favor seleccione un color.
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <label for="color" class="mt-0 mt-md-3 form-label"></label>
            <button type="button" class="col-12 btn btn-dim btn-secondary" onclick="showContrastModal()" 
                data-bs-toggle="tooltip" data-bs-placement="top" title="Revise el contraste del color que ha seleccionado con el mapa.">
                <span class="text-center">
                    Visualizar Color de Puntos y Líneas en Mapa
                </span>
            </button>
        </div>

        <div class="invalid-feedback ml-3" id="error_msg_all">
            Por favor ingrese todos los datos.
        </div>
    </div>
</form>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="editPlaneacion('{{$plan->id}}')">
        Actualizar
    </div>
</div>

<script>
    var id_sub_origin = @json($plan->subarea->area->id);    
</script>
<script src="{{ asset('assets/js/geoconecta/plans/_edit.js') }}"></script>