@extends('admin.layout.app')

@section('style')
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
                            
                            <a href="{{route('casillas.map', 'rapido')}}" target="_blank" class="btn btn-outline-light">
                                <span>Mapa</span><em class="icon ni ni-map"></em>
                            </a>
                            
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="nowrap table" id="mobTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Conteo Rápido</th>
                                    <th>Sección</th>
                                    <th>PAN</th>
                                    <th>PRI</th>
                                    <th>PRD</th>
                                    <th>VERDE</th>
                                    <th>PT</th>
                                    <th>MC</th>
                                    <th>MORENA</th>
                                    <th>NA</th>
                                    <th>NULOS</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $booths as $booth )
                                    <tr>
                                        <td >{{ $loop->index + 1 }}</td>
                        
                                        <td>
                                            <a class="btn btn-round btn-icon btn-light" onclick="showModal({{ $booth->id }}, 2);">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
                                        </td>
                        
                                        <td>{{ $booth->section }}</td>
                        
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

{{--SCRIPT PARA MOSTRAR EL MODAL--}}
<script>
   
    $(document).ready(function (){

        $('.table').DataTable({
            language: spanish,
            paginate: false,
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