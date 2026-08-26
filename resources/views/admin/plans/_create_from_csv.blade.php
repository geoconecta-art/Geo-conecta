<div class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()" style="cursor: pointer;">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <h5 class="modal-title">CREAR INVENTARIO DE TABLA CSV</h5>
</div>

<form class="modal-body" id="plan_from_csv_form">
    <div class="row pr-0">
        <div class="mb-3 col-12">
            <label for="direction" class="form-label">Dependencia / Organismo</label>
            <select class="form-control" id="direction" name="id_area" @if (!Auth::user()->hasRole("Super Administrador")) disabled @endif >
                <option value="" selected>Seleccione una opción</option>
                @foreach ($typeDependecies as $type)
                <optgroup label="{{ $type->name }}">
                    @foreach ($type->dependencies as $dependency)
                        <option value="{{ $dependency->id }}" @if ( Auth::user()->id_area == $dependency->id ) selected @endif >
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
            <select class="form-control" id="subdirection" name="id_subarea" disabled>
                <option value="" selected>Seleccione una opción</option>
                @foreach ($subareas as $subarea)
                    <option value="{{$subarea->id}}" class="subarea_option {{ $subarea->area->id }}">
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
                oninput="convertToUppercase('name_planeacion')" disabled>
            <div class="invalid-feedback" id="error_msg_name">
                Por favor ingrese el nombre del Inventario.
            </div>
        </div>

        <div class="col-12 row pr-0" >
            <div class="col-12 col-md-4">
                <label for="type_plan" class="form-label">Tipo de Inventario</label>
                <select class="form-control" id="type_plan" name="type_plan">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($type_plans as $type)
                        <option value="{{ $type }}"> 
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
                    <option value="" selected>Seleccione una opción</option>
                    <option value="Point"> Punto </option>
                    <option value="LineString"> Línea </option>
                    <option value="MultyPolygon" disabled> Polígono </option>
                </select>
                <div class="invalid-feedback" id="error_msg_geo">
                    Por favor seleccione una geometría.
                </div>
            </div>

            <div class="col-md-4 col-12">
                <label for="color" class="form-label">Color</label>
                <input type="color" class="form-control" id="color" name="plan_color">
                <div class="invalid-feedback" id="error_msg_color">
                    Por favor seleccione un color.
                </div>
            </div>
        </div>

        <div class="col-12">
            <label for="color" class="mt-0 mt-md-3 form-label"></label>
            <button type="button" class="col-12 btn btn-dim btn-secondary" onclick="showContrastModal()" 
                data-bs-toggle="tooltip" data-bs-placement="top" title="Revise el contraste del color que ha seleccionado con el mapa.">
                <span class="text-center">
                    Visualizar Color de Puntos y Líneas en Mapa
                </span>
            </button>
        </div>

        

        {{-- Espacio para el input de archivo --}}
        <div class="col-12 my-3">
            <label class="form-label" for="create_plan_from_csv_input" id="create_plan_from_csv_label" >
                Archivo *
            </label>
            <input type="file" class="form-control dropify" accept=".csv" id="create_plan_from_csv_input"
                name="create_plan_from_csv_input" required />
        </div>
    
        <input type="hidden" name="id_plan" value="" id="id_plan_input">
    
        <div class="col-12 my-3" id="crate_plan_coors_headers_container"></div>

        <div class="invalid-feedback ml-3" id="error_msg_all">
            Por favor ingrese todos los datos.
        </div>

    </div>
    
</form>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal" onclick="closeModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="createPlanFromCSV()">
        Crear
    </div>
</div>

<script src="{{ asset('assets/js/utils/filterSubareas.js') }}"></script>
<script src="{{ asset('assets/js/geoconecta/plans/_create.js') }}"></script>

<script>
    $(document).ready( function (){
        $('.dropify').dropify({
            height: 100,
        });

        $("#geometry").on("change", function () {
            if( $(this).val() != '' ){
                let fileInput = $("#create_plan_from_csv_input")[0];
                if( fileInput.files.length > 0 ){
                    getHeadersFromCSV("#plan_from_csv_form");
                }
            }
        });

        $("#create_plan_from_csv_input").on("change", function () {
            // Accedemos al elemento DOM real desde el objeto jQuery
            if (this.files.length > 0) {
                getHeadersFromCSV("#plan_from_csv_form");
            }
        });

    });
</script>