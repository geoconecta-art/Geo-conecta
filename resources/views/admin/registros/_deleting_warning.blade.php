<div class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
    <em class="icon ni ni-cross"></em>
</div>

<div class="modal-header">
    <h5 class="modal-title text-danger">
        <em class="icon ni ni-caution"></em>
        <strong class="me-auto" id="title_deleting">Advertencia</strong>
    </h5>
</div>

<div class="modal-body" id="text_delete_info">
    <p>
        Se eliminarán los siguientes datos del <b> Inventario {{ $plan->name }}: </b>
    </p>
    <ul id="list_delete_info" class="list-group mb-1">

        @foreach ($fields as $fieldKey => $fieldName)

            @if ( strpos(  $fieldKey,"gen_attr" ) )
                <li class="pl-4 col-12 row">
                    <span class="col-3">
                        <b> {{ $fieldName }}: </b>
                    </span>
                    <span class="col-9">
                        @if ( strpos( $fieldKey, "image" ) )
                            <img src=" {{ asset( $row->attributes[$fieldKey] ) }} "  alt="{{ $row->attributes[$fieldKey] }}">
                        @elseif ( strpos( $fieldKey, "file" ) )
                            <a href=" {{ asset( $row->attributes[$fieldKey] ) }} " target="_blank">
                                <i class="icon ni ni-file"></i> Descargar {{ $fieldName }}
                            </a>
                        @else
                            {{ $row->attributes[$fieldKey] }}
                        @endif
                    </span>
                </li>
            @elseif ( strpos( $fieldKey, "address_attr" ) )
                <li class="pl-4 col-12 row">
                    <span class="col-3">
                        <b> {{ $fieldName }}: </b>
                    </span>
                    <span class="col-9">
                        {{ $row->address[$fieldKey] }} 
                    </span>
                </li>
            @elseif ( strpos( $fieldKey, "georeference_Inicial_attr" ) )
                <li class="pl-4 col-12 row">
                    <span class="col-3">
                        <b> {{ $fieldName }}: </b>
                    </span>
                    <span class="col-9">
                        {{ $row->georeference['_Inicial'][$fieldKey] }} 
                    </span>
                </li>
            @elseif ( strpos( $fieldKey, "georeference_Final_attr" ) )
                <li class="pl-4 col-12 row">
                    <span class="col-3">
                        <b> {{ $fieldName }}: </b>
                    </span>
                    <span class="col-9">
                        {{ $row->georeference['_Final'][$fieldKey] }} 
                    </span>
                </li>
            @endif

        @endforeach
    </ul>

    <p>
        Una vez que confirme esta acción no se podrá deshacer. <b>¿Está seguro de continuar?</b>
    </p>
</div>

<div class="modal-footer">
    <div class="btn btn-dim btn-light" data-dismiss="modal" id="delete_plan_cancel_btn" onclick="closeModal()">
        Cancelar
    </div>

    <div class="btn btn-dim btn-danger" onclick="deletePlan('{{$plan->id}}','{{$row->id}}')" id="delete_plan_accept_btn">
        Eliminar
    </div>
</div>