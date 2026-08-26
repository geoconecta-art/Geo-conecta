@extends('admin.layout.app')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
   
<style>
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        display: none;
    }

    table.dataTable.nowrap th, table.dataTable.nowrap td {
        white-space: normal !important;
    }

    .col-xxl-3>.card {
        cursor: pointer;
    }

    #mobTable{
        font-size: 11px;
    }

    .mob-info {
        font-size: 1rem;
    }
</style>

<link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
<style>
    .dropify-wrapper {
        height: 364px;
        border: 1px solid #dbdfea;
        border-radius: 4px;
    }

    .dropify-wrapper .dropify-message p {
        font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        font-size: 20px;
    }
</style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">{{ $title }}</h3>
                    </div>

                </div>
            </div>

            <div class="card h-100">
                <div class="card-inner">

                    <div class="row mb-5">
                        
                        <div class="col-12 text-right">

                            <a href="{{ route('casillas.map', $type) }}" target="_blank" class="btn btn-outline-light">
                                <span>Mapa</span><em class="icon ni ni-map"></em>
                            </a>

                            <div onclick="downloadReport('{{ $type }}')"
                                class="btn btn-primary mr-md-2 mb-3 mb-md-0">
                                <span>Exportar Excel</span><em class="icon ni ni-download"></em>
                            </div>
                            
                            

                            
                            
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="nowrap table" id="mobTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    @if ($type == 'electoral')
                                        <th>Electoral</th>
                                        <th>Sección</th>
                                        <th>Tipo</th>
                                        <th>Apellidos</th>
                                        <th>Imagen</th>
                                    @else
                                        <th>Conteo Rápido</th>
                                        <th>Sección</th>
                                        <th>Tipo</th>
                                        <th>Apellidos</th>
                                    @endif
                                    
                                    @foreach ($fields as $field)
                                        @if ($type == 'electoral')
                                        <th>{{strtoupper(str_replace('_', '', $field ))}}</th>
                                        @else
                                        <th>{{strtoupper(str_replace('2', ' ', str_replace('_', ' ', $field )))}}</th>    
                                        @endif
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $booths as $booth )
                                    <tr>
                                        <td >{{ $loop->index + 1 }}</td>
                                        <td>
                                            <a class="btn btn-round btn-icon btn-light" onclick="showModal({{ $booth->id }}, '{{$type}}');">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
                                        </td>
                                        <td>{{ $booth->section }}</td>
                                        <td>{{ $booth->type }}</td>
                                        <td>{{ $booth->letters }}</td>
                                        @if ($type == 'electoral')
                                            <td>
                                                @if (isset($booth->image)) 
                                                <a data-fancybox="gallery" href="{{ $booth->image }}">
                                                    <img src="{{ $booth->image }}" alt="" 
                                                    style="max-width: 200px; width: 150px;"
                                                    >
                                                </a>
                                                @else No Image @endif
                                            </td>
                                        @endif

                                        @foreach ($fields as $field)
                                            <td>{{ $booth[$field] }}</td>
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
    </div>

@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

{{--SCRIPT PARA MOSTRAR EL MODAL--}}
<script>
   
    $(document).ready(function (){

        $('.table').DataTable({
            language: spanish,
            paginate: false,
        });

        // Inicializa FancyBox para los enlaces con el atributo data-fancybox
        $('[data-fancybox="gallery"]').fancybox({
            buttons: [
                "zoom",
                "close"
            ]
        });
    });

    function showModal(id, type) {
        
        $loading.show();
        let _token = $token.val();
            
        $.get('/admin/casillas/'+ id +'/editar', { 
                '_token':_token,
                'type': type,
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $('#basicModal .modal-dialog').addClass('modal-lg');

            $basicModal.modal('show');
        });   
    }

    function downloadReport(type) {
        window.location.href = '{{ route('reports.booths') }}?type=' + type + '';
    }
</script>

{{--ACCIONES PARA CAMBIAR EL DISEÑO DEL INPUT DE IMAGEN--}}
<script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>

{{--MODAL PARA CONTEO RÁPIDO--}}
<script>
    function modalQuickCount(id) {
        
        $loading.show();
        let _token = $token.val();
            
        $.get('/admin/casillas/'+ id +'/fast/editar', { 
                '_token':_token,
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $('#basicModal .modal-dialog').addClass('modal-lg');
    
            $basicModal.modal('show');
        });   
    }
</script>
@endsection

{{--
    
                                    <th>PAN</th>
                                    <th>PRI</th>
                                    <th>PRD</th>
                                    <th>VERDE</th>
                                    <th>PT</th>
                                    <th>MC</th>
                                    <th>MORENA</th>
                                    <th>NA</th>
                                    <th>PAN PRI PRD NA</th>
                                    <th>PAN PRI PRD</th>
                                    <th>PAN PRI NA</th>
                                    <th>PAN PRD NA</th>
                                    <th>PAN PRI</th>
                                    <th>PAN PRD</th>
                                    <th>PAN NA</th>
                                    <th>PRI PRD NA</th>
                                    <th>PRI PRD</th>
                                    <th>PRI NA</th>
                                    <th>PRD NA</th>
                                    <th>MORENA VERDE PT</th>
                                    <th>VERDE PT</th>
                                    <th>VERDE MORENA</th>
                                    <th>PT MORENA</th>
                                    <th>OTROS</th>
                                    <th>NULOS</th>
    --}}