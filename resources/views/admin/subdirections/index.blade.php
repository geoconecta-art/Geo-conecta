@extends('admin.layout.app')

@section('styles')
    
@endsection


@section('content')
    @include('admin.plans._test_contrast')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Áreas</h3>
                    </div>

                </div>
            </div>

            <div class="card h-100">
                <div class="card-inner">

                    <div class="row mb-5">
                        
                        <div class="col-12 text-right">

                            <div class="btn btn-primary mr-md-2 mb-3 mb-md-0" onclick="showModal('{{ route('subdirections.open-modal') }}')">
                                <span>Nueva Área</span><em class="icon ni ni-plus"></em>
                            </div>                            
                        </div>

                        <div class="table-responsive mt-3 w-100">
                            <table class="nowrap table" id="subarea_table">
                                <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="" >Nombre</th>
                                    <th style="" >Dependencia / Organismo</th>
                                    <th style=""> No. Inventarios </th>
                                    <th style="" >Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                    
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
        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'subdirections.update.table' : "{{ route('subdirections.update.table') }}",
                'subdirections.create' : "{{ route('subdirections.create') }}",
                'subdirections.update' : "{{ route('subdirections.update', 'id_sub') }}",
                'subdirections.confirm-delete' : "{{ route('subdirections.confirm-delete', 'id_sub') }}",
                'subdirections.delete' : "{{ route('subdirections.delete', 'id_sub') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/subdirections/index.js') }}"></script>

    {{--ACCIONES PARA CAMBIAR EL DISEÑO DEL INPUT DE IMAGEN--}}
    <script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
@endsection