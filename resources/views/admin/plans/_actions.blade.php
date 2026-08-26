<div class="d-flex mb-1">
    <a class="btn btn-dim btn-success rounded-circle p-1 mr-1" href="{{route('plans.form.create', $id)}}" 
        title="Editar Inventario">
        <em class="icon ni ni-edit"></em>
    </a>

    <button class="btn btn-dim btn-danger rounded-circle p-1 mr-1" onclick="confirmDelete('{{ $id }}')"
        title="Eliminar Inventario">
        <em class="icon ni ni-trash"></em>
    </button>

    <div class="dropdown mr-1">
        <a class="dropdown-toggle btn btn-dim btn-secondary rounded-circle p-1" data-bs-toggle="dropdown" aria-expanded="false"
            title="Descargar">
            <em class="icon ni ni-download"></em>
        </a>

        <div class="dropdown-menu">
            <ul class="link-list-opt">

                {{-- <li>
                    <a target="_blank">
                        <span>
                            Exportar SHP
                        </span>
                    </a>
                </li> --}}

                <li>
                    <a href="{{ route('register.inventory.export-kml', $id) }}">
                        <span>
                            Exportar KML
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('register.inventory.export-excel', $id)}}">
                        <span>
                            Exportar Excel
                        </span>
                    </a>
                </li>

                <li>
                    <a onclick="openExportPdfFileModal('{{$id}}')" style="cursor: pointer;">
                        <span>
                            Exportar PDF
                        </span>
                    </a>
                </li>
                    
            </ul>
        </div>
    </div>
</div>

<div class="d-flex">
    <a href="{{route('register.inventory.index', $id)}}" class="btn btn-dim btn-primary rounded-circle p-1 mr-1"
        title="Visualizar Información Tabular">
        <em class="icon ni ni-list-index"></em>
    </a>

    <a href="{{ route('geovisor.index') . '?inventario=' . $id}}" class="btn btn-dim btn-success rounded-circle p-1 mr-1"
        title="Visualizar Información en Mapa">
        <em class="icon ni ni-location"></em>
    </a>

    <a href="{{ route('register.inventory.plan-form', $id) }}" class="btn btn-dim btn-secondary rounded-circle p-1 mr-1"
        title="Agregar Datos">
        <em class="icon ni ni-plus-circle"></em>
    </a>
</div>

{{-- <a href="{{route('plans.preview', $id)}}" class="btn btn-dim btn-light rounded-circle p-1"
    title="Vista Previa">
    <em class="icon ni ni-eye"></em>
</a> --}}