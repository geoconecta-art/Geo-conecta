@extends('admin.layout.app')

@section('styles')
    
@endsection


@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Dependencias / Organismos</h3>
                    </div>

                </div>
            </div>

            <div class="card h-100">
                <div class="card-inner">

                    <div class="row mb-5">
                        
                        <div class="col-12 text-right">
                            <div class="btn btn-primary mr-md-2 mb-3 mb-md-0" onclick="showModal('{{ route('directions.open-modal') }}')">
                                <span>Nueva Dependencia / Organismo</span><em class="icon ni ni-plus"></em>
                            </div>                            
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="nowrap table" id="area_table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th> Nombre </th>
                                    <th> Clave </th>
                                    <th> No. Áreas </th>
                                    <th> No. Inventarios </th>
                                    <th>Acciones</th>
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

    <script>
        window.Laravel = {
            routes: {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'directions.get.areas': "{{ route('directions.get.areas') }}",
                'directions.create' : "{{ route('directions.create') }}",
                'directions.update' : "{{ route('directions.update', 'id_area') }}",
                'directions.confirm-delete' : "{{ route('directions.confirm-delete', 'id_area') }}",
                'directions.delete' : "{{ route('directions.delete', 'id_area') }}",
            }
        }
    </script>

    {{-- IMPORTA EL ARCHIVO JAVSCRIPT DE DIRECCIONES --}}
    <script src="{{ asset('assets/js/geoconecta/directions/index.js') }}"></script>

    {{--ACCIONES PARA CAMBIAR EL DISEÑO DEL INPUT DE IMAGEN--}}
    <script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
@endsection