
<div class="btn close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <div class="nk-block-head-content d-md-flex justify-content-between w-100">
        <h4 >
            Exportar PDF
        </h4>
    </div>
</div>

<div class="modal-body px-2">
    <div class="col-12 mt-0">
        <h5 class="d-flex ">
            Inventario: &nbsp; <span style="font-weight: 400;" > {{ $plan->name }} </span>
        </h5>
    </div>
    
    <br>

    <div class="col-12" id="instructions-plan">
        <p>
            Por favor, seleccione los encabezados que desea visualizar en el archivo PDF.
        </p>
    </div>

    <div class="row px-2 align-items-end ">
        <div class="mb-3 col-12 col-sm-6 form-group">
            <label for="export_pdf_type_select" class="form-label">
                Tipo de archivo PDF
            </label>
    
            <select class="form-control" id="export_pdf_type_select" name="export_pdf_type_select">
                <option value="" selected> Seleccione una opción </option>
                <option value="1"> Tabla </option>
                <option value="2"> Detalle </option>
            </select>
        </div>

        <div class="col-12 col-sm-6 mb-3" id="export_pdf_header_counter_container">
            <p class="text-right">
                Encabezados restantes <span class="text-success" id="export_pdf_header_counter"> </span>
            </p>
        </div>
    </div>

    <div class="col-12 px-2">
        <form class="w-100 d-flex flex-wrap border border-1 rounded rounded-2 p-2" id="export_pdf_field_form">
            <div class="custom-control custom-control-sm custom-checkbox col-12 my-1">
                <input type="checkbox" id="check_all" class="custom-control-input" value="">
                <label for="check_all" class="custom-control-label">
                    Seleccionar Todas
                </label>
            </div>

            @foreach ($fieldNames as $key => $fieldName)
                <div class="custom-control custom-control-sm custom-checkbox col-4 my-1">
                    <input type="checkbox" id="{{ $key }}" class="custom-control-input export_pdf_field_check" name="export_pdf_field_check[{{$key}}]" value="{{ $fieldName }}">
                    <label for="{{ $key }}" class="custom-control-label">
                        {{ $fieldName }}
                    </label>
                </div>
            @endforeach

            @csrf
        </form>
    </div>

    <div class="col-12 mt-3 text-dark" id="notes_msg_file_import" style="font-size: 8pt;">
        <p>
            La exportación en <b>tabla</b> soporta un máximo de <b>10 encabezados</b> seleccionados, si se excede dicha cantidad se exportará en formato
            completo.
            <br>
            Si no selecciona ningún elemento, se exportará la información de los registros incluyendo todos los encabezados en formato detalles.
        </p>
    </div>
    
</div>

<div class="modal-footer">

    <div class="btn btn-dim btn-danger" data-dismiss="modal" onclick="closeModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-success" onclick="exportInventoryToPDF('{{ $plan->id }}')">
        Exportar
    </div>
</div>

<script src="{{ asset('assets/js/geoconecta/registros/_export_pdf_modal.js') }}"></script>