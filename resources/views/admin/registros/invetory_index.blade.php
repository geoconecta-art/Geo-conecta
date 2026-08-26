@extends('admin.layout.app')

@section('style')
    <style>
        div.container { max-width: 1200px }
    </style>

    
@endsection

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.plans._test_contrast')

    <div class="nk-content-inner">
        <div class="nk-content-body">

            <div class="nk-block-head nk-block-head-sm pb-2 d-flex">
                <div class="mr-1">
                    <a href="{{route('plans.index')}}" class="btn btn-dim btn-primary p-1" title="Volver">
                        <em class="icon ni ni-curve-up-left"></em>
                    </a>
                    {{-- <a href="{{route('register.index')}}" class="btn btn-dim btn-primary p-1" title="Volver">
                        <em class="icon ni ni-curve-up-left"></em>
                    </a> --}}
                </div>
                <div class="nk-block-between">
                    <div class="nk-block-head-content d-md-flex justify-content-between w-100">
                        <h3 class="nk-block-title page-title">
                            Datos: <span style="font-weight: 400;" >{{ $plan->name }}</span>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="card h-100">
                <div class="card-inner">
                    <div class="col-12 d-flex text-right">

                        <div class="col-12 col-md-6 d-flex justify-content-start">
                            <a href="{{ route('register.inventory.plan-form', $plan->id) }}" class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                <span> Nuevo Dato (Formulario) </span> <em class="icon ni ni-plus"></em>
                            </a>
                            <div class="btn btn-outline-light mr-md-2 mb-3 mb-md-0" onclick="openModalImport('csv')">
                                <span> Agregar Datos (CSV) </span> <em class="icon ni ni-property-add"></em>
                            </div>

                            {{-- <div class="btn btn-outline-light mr-md-2 mb-3 mb-md-0" onclick="openModalImport('excel')">
                                <span> Importar Excel </span><em class="icon ni ni-upload"></em>
                            </div> --}}

                        </div>

                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <a href="{{route('register.inventory.export-excel', $plan->id)}}" class="btn btn-outline-light mr-md-2 mb-3 mb-md-0"
                                title="Exportar Excel" id="export-excel-file">
                                <span> Exportar Excel </span> <em class="icon ni ni-upload"></em>
                            </a>
                            <div class="btn btn-outline-light mr-md-2 mb-3 mb-md-0" onclick="openExportPdfFileModal()" title="Exportar PDF" id="export-pdf-file">
                                <span> Exportar PDF </span> <em class="icon ni ni-upload"></em>
                            </div>
                            {{-- <a href="{{route('register.inventory.export-list-pdf', $plan->id)}}" target="_blank" class="btn btn-outline-light mr-md-2 mb-3 mb-md-0"
                                title="Exportar PDF" id="export-pdf-file">
                                <span> Exportar PDF </span> <em class="icon ni ni-upload"></em>
                            </a> --}}
                        </div>
                        
                    </div>

                    <div class="table-responsive mt-3 w-100">
                        <table class="nowrap table w-100" id="mobTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Acciones</th>
                                    @foreach ($plan->attributes as $item)
                                        @if ( is_array( $item ) )
                                            @if ( !isset($item['deleted_at']) )
                                                <th> {{ $item['title'] }} </th>
                                            @endif
                                        @else
                                            @php
                                                $matches = explode("_", $item);
                                                $geoItems = [];

                                                if( count( $matches ) == 2 ){
                                                    $key = $matches[0];
                                                    $index = '_'.$matches[1];
                                                    $geoItems = $plan->$key[$index];
                                                } else {
                                                    $geoItems = $plan->$item;
                                                }
                                            @endphp

                                            @foreach ($geoItems as $geoItem)
                                                @if ( is_array( $geoItem ) )
                                                    @if ( $geoItem['type'] != 'button' )
                                                        <th> {{ $geoItem['title'] }} {{ $matches[1] ?? '' }} </th>
                                                    @endif
                                                @endif
                                            @endforeach

                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($rows as $row)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td>
                                            <a href="{{ route('register.inventory.edit-row', [$row->id_plan, $row->id]) }}" class="btn btn-dim btn-success rounded-circle p-1">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
                                            <div class="btn btn-dim btn-danger rounded-circle p-1" onclick="confirmDelete('{{ $plan->id }}','{{ $row->id }}')">
                                                <em class="icon ni ni-trash"></em>
                                            </div>
                                        </td>
                                        @foreach ($keys as $k => $attrKey)
                                            @if( is_array( $attrKey ) )
                                                @php
                                                    $matches = explode("_", $k);
                                                    $geoValues = [];

                                                    if( count( $matches ) == 2 ){
                                                        $key = $matches[0];
                                                        $index = '_'.$matches[1];
                                                        $geoValues = $row->$key[$index];
                                                    } else {
                                                        $geoValues = $row->$k;
                                                    }
                                                @endphp

                                                @foreach ($geoValues as $key => $value)
                                                    <td>{{ $value ?? ''}}</td>
                                                @endforeach
                                            @else
                                                @if ( str_contains( strtolower($attrKey), "image" ) )
                                                    <td>
                                                        @if ( $row->attributes[$attrKey] )
                                                            <img src="{{ asset($row->attributes[$attrKey]) }}" alt="{{ $attrKey }}" width="100px">
                                                        @endif
                                                    </td>
                                                @elseif ( str_contains( strtolower($attrKey), "file" ) )
                                                    <td>
                                                        @if ( $row->attributes[$attrKey] )
                                                            <a href="{{ asset($row->attributes[$attrKey]) }}" target="_blank" download>
                                                                Descargar Archivo
                                                            </a>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td> {{ $row->attributes[ $attrKey ] ?? ' ' }} </td>
                                                @endif
                                                
                                            @endif

                                        @endforeach
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

@endsection

@section('script')
    
    <script>
        var id_plan = @json( $plan->id );
        let restChecks = 10;
        
        window.Laravel = {
            routes: {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'register.inventory.import-modal': "{{ route('register.inventory.import-modal', [':id', ':type']) }}",
                'register.export-open-modal' : "{{ route('register.inventory.export-open-modal', 'id_plan') }}",
                'register.inventory.export-pdf' : "{{ route('register.inventory.export-pdf', 'id_plan') }}",
                'register.inventory.import-get-headers' : "{{ route('register.inventory.import-get-headers', 'id_plan') }}",
                'register.inventory.delete-warning' : "{{ route('register.inventory.delete-warning', ['id_plan', 'id_row']) }}",
                'register.inventory.delete' : "{{ route('register.inventory.delete', ['id_plan', 'id_row']) }}"
            }
        }
    </script>

    <script src="{{ asset('assets/js/geoconecta/registros/inventory_index.js') }}"></script>

    
@endsection
