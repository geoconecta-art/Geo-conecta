
<button class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
    <em class="icon ni ni-cross"></em>
</button>

<div class="modal-header">
    <div class="nk-block-head-content d-md-flex justify-content-between w-100">
        <h4 >
            Importar {{ $title }}
        </h4>
    </div>
</div>

<div class="modal-body">
    <div class="col-12">
        <h5 class="d-flex ">
            Inventario: &nbsp; <span style="font-weight: 400;" > {{ $plan->name }} </span>
        </h5>
    </div>
    <br>
    <div class="col-12" id="instructions-plan">
        <p>
            El archivo seleccionado deberá ser de tipo <b>CSV</b> separado por comas (,).
        </p>
        <p style="font-size: 9pt;">
            Se recomienda que el archivo posea los siguientes campos:
            <b>

                @foreach ($fieldNames as $fieldName)
                    @if ( $loop->index == ( count($fieldNames) - 1 ) )
                        y {{ $fieldName }}
                    @elseif ( $loop->index == ( count($fieldNames) - 1 ) )
                        {{ $fieldName }}
                    @else
                        {{ $fieldName }}, 
                    @endif
                @endforeach

            </b>
        </p>
    </div>

    <form action="POST" id="import_excel_form">
        <div class="col-12 my-3">
            <label class="form-label" for="import_excel_plan" id="import_excel_plan_label" >
                Archivo *
            </label>
            <input 
                type="file" 
                class="form-control dropify"
                accept="{{ $files }}"
                id="import_excel_plan"
                name="import_excel_plan"
                required />
    
            <div class="invalid-feedback" id="error_msg_sub"></div>
        </div>
    
        <input type="hidden" name="id_plan" value="{{ $plan->id }}" id="id_plan_input">
    
        <div class="col-12 my-3" id="coors-headers-container"></div>
    </form>

    <div class="my-3">
        <div class="col-12">
            <div class="invalid-feedback text-dark" id="notes_msg_file_import">
                <p>
                    <br>
                    Aquellos nuevos campos que se encuenten en el archivo CSV, se crearán como tipo texto. Si quiere evitar que esto ocurra, por favor, 
                    asegúrese de que los nombres de los campos coincidan con los campos listados arriba.
                    <br>
                    Si el invetario posee alguna fecha, asegurese de que el formato corresponda al siguiente: <b>AAAA-MM-DD</b>.
                    <br>
                    Si no selecciona ningún campo para las coordenadas, se asumirá que no el archivo CSV no posee ningún campo para coordenadas.
                </p>
            </div>
        </div>
    </div>
    
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal" onclick="closeModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="importFile('{{ route($route, [$plan->id, $typeF]) }}')">
        Importar
    </div>
</div>

<script src="{{ asset('assets/js/geoconecta/registros/_import_rows.js') }}"></script>