@extends('admin.geovisor.app')

@section('style')
<style>
    .gm-style-iw-c{
        padding: 0px !important;
    }

    .gm-style-iw-chr{
        background: #6E5AAF !important;
        color: white !important;
        font-family: "Geologica", sans-serif;
        font-size: large;
        font-weight: 400;
        margin-bottom: 0.75rem;
        padding-left: 1rem;
    }

    .gm-ui-hover-effect>span {
        background-color: #fff;
    }

    .gm-style-iw-d{
        max-height: 400px !important;
        padding: 1rem;
        max-width: 400px;
        min-width: 100% !important;
        color: #6E5AAF;
        padding-top: 0px;
    }

    @media screen and (width > 640px){
        .gm-style-iw-d{
            min-width: 350px;
        }
    }

    .info-card-title{
        color: #6E5AAF;
        font-family: 'Geologica';
        font-weight: 300;
    }

    .info-card-data{
        color: black;
        font-family: 'Geologica';
        font-weight: 200;
    }

    a.info-card-data{
        font-style: italic;
        color: #6E5AAF;
        text-decoration: underline #6E5AAF;
    }

    a.info-card-data:hover{
        font-style: italic;
        color: #928aad;
        text-decoration: underline #928aad;
    }

    .btn-plan-delete:hover{
        color: red;
    }

    .hand-pointer{
        cursor: pointer;
    }

    @media screen and (width >= 992px ){
        #info_print_map_container{
            height: 100%;
        }
    }

</style>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@include('admin.plans._test_contrast')

<div class="nk-content-inner my-4 mx-4">
    <div class="nk-content-body">
        
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">

                <div class="nk-block-head-content d-flex">
                    <a href="#" onclick="{{ ROUTE('geovisor.index') }}"  class="btn btn-dim btn-primary p-1 me-2" title="Volver">
                        <em class="icon ni ni-curve-up-left"></em>
                    </a>

                    <h3 class="nk-block-title page-title">Generar Mapa</h3>
                </div>

            </div>
        </div>

        <div class="card h-100">
            <div class="card-inner">
                <form class="nk-content p-0 d-block d-md-flex flex-wrap" id="all_settings_map_form">
                    
                    {{-- AJUSTES MAPA --}}
                    <div class="p-1 col-12 col-xxl-3 ">
                        <div class="card card-bordered col-12 p-2 flex-row flex-wrap justify-content-between align-items-end flex-xxl-column h-100">

                            <div id="settings_print_map_container">
                                <div class="nk-block-head-content">
                                    <h5 class="title nk-block-title">Ajustes Mapa</h5>
                                </div>
    
                                <div class="d-flex flex-wrap mt-1" id="settings_container">
                                    <div class="mx-1 my-1" id="setting_print_map_type_container">
                                        <div class="title fw-bold mb-1">
                                            Tipo de Mapa
                                        </div>
        
                                        <div id="settings_print_map_type_checkbox_container">
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" id="roadmap" name="typeMap" value="roadmap" class="custom-control-input">
                                                <label class="custom-control-label" for="roadmap">Default</label>
                                            </div>
            
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" id="satellite" name="typeMap" value="satellite" class="custom-control-input" checked>
                                                <label class="custom-control-label" for="satellite">Sátelite</label>
                                            </div>
            
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" id="terrain" name="typeMap" value="terrain" class="custom-control-input">
                                                <label class="custom-control-label" for="terrain">Relieve</label>
                                            </div>
            
                                            <div class="custom-control custom-control-sm custom-radio">
                                                <input type="radio" id="hybrid" name="typeMap" value="hybrid" class="custom-control-input">
                                                <label class="custom-control-label" for="hybrid">Híbrido</label>
                                            </div>
                                        </div>
                                    </div>
        
                                    {{-- <div class="mx-1 my-1" id="setting_print_map_type_container">
                                        <div class="title fw-bold mb-1">
                                            Etiqueas
                                        </div>
        
                                        <div id="settings_print_map_type_checkbox_container">
                                            <div class="custom-control custom-control-sm custom-checkbox">
                                                <input type="checkbox" id="acTags" name="acTags" class="custom-control-input">
                                                <label class="custom-control-label" for="acTags">Habilitadas</label>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
    
                            </div>
    
                            <div class="mt-3 d-flex justify-content-end">
                                <button class="btn btn-dim btn-primary" id="print_map_btn">
                                    Imprimir Mapa
                                </button>
                            </div>
    
                        </div>
                    </div>

                    {{-- MAPA --}}
                    <div class="col-12 col-md-12 col-lg-7 col-xl-8 col-xxl-6 p-1" style="aspect-ratio: 1;">
                        <div class="card card-bordered h-100">
                            <div class="card-inner p-0 h-100" id="map-container">
                                <div id="map" style="height: 100%;"></div>
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div>
        
                    {{-- INFORMACIÓN MAPA --}}
                    <div class="col-12 col-md-12 col-lg-5 col-xl-4 col-xxl-3 p-1">
                        <div class="card card-bordered" id="info_print_map_container">
                            <div class="card-inner-group">
                                
                                <div class="card-inner h-100">

                                    <div class="nk-block-head-content d-flex">
                                        <h5 class="title nk-block-title">Información Mapa</h5>
                                    </div>
                                    
                                    <div class="card-inner px-0">
                                        <div class="col-12 mb-3">
                                            <label for="name_dependency" class="form-label">Dependencia/Organismo</label>
                                            <input type="text" class="form-control" id="name_dependency" placeholder="Escriba el nombre de la Dependencia..." name="name_dependency"
                                                oninput="convertToUppercase('name_dependency');" value="{{ $plan->area->name ?? '' }}">
    
                                            <div class="invalid-feedback" id="error_msg_dependency">
                                                Por favor ingrese el nombre de la Dependencia / Organismo.
                                            </div>
                                        </div>
    
                                        <div class="col-12 mb-3">
                                            <label for="key_map" class="form-label">Clave del Mapa</label>
                                            <input type="text" class="form-control" id="key_map" placeholder="Escriba el nombre de la Clave del Mapa..." name="key_map" 
                                                oninput="convertToUppercase('name_dependency');" value="{{ $map_key ?? '' }}">
                                            <div class="invalid-feedback" id="error_msg_key_map">
                                                Por favor ingrese la Clave del Mapa.
                                            </div>
                                        </div>
    
                                        <div class="col-12 mb-3">
                                            <label for="name_map" class="form-label">Nombre del Mapa</label>
                                            <input type="text" class="form-control" id="name_map" placeholder="Escriba el nombre del Mapa..." name="name_map"
                                                oninput="convertToUppercase('name_map')" value="{{ $plan->name ?? '' }}">
    
                                            <div class="invalid-feedback" id="error_msg_map_name">
                                                Por favor ingrese el nombre del Mapa.
                                            </div>
                                        </div>
    
                                        <div id="symbology-container">
                                            <div class="title fw-bold mb-1">
                                                Símbología
                                            </div>
    
                                            <ul class="card-inner py-0 ps-2 pe-0 border-1 rounded-2" id="symbology-check-container">
                                                
                                            </ul>
                                            <div class="invalid-feedback" id="error_msg_symbology">
                                                Por favor seleccione al menos 1 Inventario.
                                            </div>
                                        </div>
                                        @csrf
                                    </div>

                                </div>
        
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection


@section('script')
    @if ( Auth::user()->hasRole('Super Administrador') )
        <script src="{{ asset('assets/js/geoconecta/geovisor/filterOptionsSA.js') }}"></script>
    @else
        <script src="{{ asset('assets/js/geoconecta/geovisor/filterOptions.js') }}"></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>


    <script>
        let start_zoom = @json($zoom);
        let start_lat = @json($lat);
        let start_lng = @json($lng);
        let start_id_plans = @json($id_plans);
    </script>

    <script>

        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                // RUTAS DE INVENTARIOS
                'map.inventory-info' : "{{ route('map.inventory-info', 'id_plan') }}",
                'register.inventory.export-open-modal' : "{{ route('register.inventory.export-open-modal', 'id_plan') }}",
                'register.inventory.export-pdf' : "{{ route('register.inventory.export-pdf', 'id_plan') }}",

                // RUTAS PARA EXPORTAR EL PDF
                'geovisor.pre-load' : "{{ route('geovisor.pre-load') }}",
                'geovisor.print-index' : "{{ route('geovisor.print-index') }}",
                'geovisor.print-view' : "{{ route('geovisor.print-view') }}",
            }
        };
    </script>
    <script src="{{ asset('assets/js/geoconecta/geovisor/print/index.js') }}"></script>

    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=geometry,places&callback=initMap">
    </script>
@endsection