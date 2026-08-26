@extends('admin.layout.app')

@section('style')
<style>
    .gm-style-iw-c{
        padding: 0px !important;
    }

    .gm-style-iw-chr{
        background: #6E5AAF;
        color: white;
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

    /* XS */
    @media screen and (width < 576px){
        #catastro-container{ height: 33vh; }
        #catastro-selected-container{ height: 27vh; }
    }

    /* S */
    @media screen and (width >= 576px) and (width < 768px ){
        #catastro-container{ height: 33vh; }
        #catastro-selected-container{ height: 33vh; }
    }

    /* M */
    @media screen and (width >= 768px) and (width < 992px ){
        #catastro-container{ height: 33vh; }
        #catastro-selected-container{ height: 33vh; }
    }

    /* G */
    @media screen and (width >= 992px) and (width < 1200px ){
        #panel_catastr_menu{ height: 80vh;}
        #catastro-container{ height: 30vh; }
        #catastro-selected-container{ height: 25vh; }
    }

    /* XL */
    @media screen and (width >= 1200px) and (width < 1400px ){
        #panel_catastr_menu{ height: 80vh;}
        #catastro-container{ height: 30vh; }
        #catastro-selected-container{ height: 25vh; }
    }

    /* XXL */
    @media screen and (width >= 1400px) {
        #panel_catastr_menu{ height: 80vh;}
        #catastro-container{ aspect-ratio: 1; }
        #catastro-selected-container{ height: 25vh; }
    }
</style>
@endsection


@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <div class="toast-container position-fixed mr-4" style="z-index: 99; right: 0px;">
        <div class="toast btn-dim bg-success text-white align-items-center fade mr-2" id="map_toast" role="alert" aria-live="assertive" aria-atomic="true" >
            <div class="d-flex">
                <div class="toast-body" id="map_msg_toast">
                    
                </div>
                <button type="button" class="close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">
                    <em class="icon ni ni-cross-sm"></em>
                </button>
            </div>
        </div>
    </div>

    <div class="card h-100">
        <div class="card-inner p-0">

            <div class="nk-content p-0 d-flex flex-wrap">

                {{-- FILTRO --}}
                <div class="col-12 col-lg-3 p-1">
                    <div class="card card-bordered" id="panel_catastr_menu">
                        <div class="card-inner-group">
                            
                            <div class="card-inner py-0 px-3 h-100">
                                <ul class="nav nav-tabs" id="option_tabs">
                                    {{-- <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-inventaries" id="nav-inventaries">
                                            Inventarios
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#tab-maps" id="nav-maps">
                                            Mapas
                                        </a>
                                    </li> --}}

                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-catastro" id="nav-catastro">
                                            Catastro
                                        </a>
                                    </li>
                                </ul>
                                
                                <div class="tab-content m-0">
                                    {{-- <div class="tab-pane active" id="tab-inventaries">
                                        @include('admin.map._inventaries')
                                    </div>
                                    
                                    <div class="tab-pane m-0" id="tab-maps">
                                        @include('admin.map._maps')
                                    </div> --}}

                                    <div class="tab-pane m-0 active" id="tab-catastro">
                                        @include('admin.map._catastro')
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- MAPA --}}
                <div class="col-12 col-lg-9 p-1">
                    <div class="card card-bordered h-100">
                        <div class="card-inner p-0" id="map-container" style="height: 80vh !important;">
                            <div id="map" style="height: 100%;"></div>
                        </div><!-- .card-inner -->
                    </div><!-- .card -->
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/utils/calculateDistance.js') }}"></script>

    {{-- SCRIPTS DE LA VISTA MAPA --}}
    <script src="{{ asset('assets/js/geoconecta/maps/index/index.js') }}"></script>

    <script>

        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",
                
                // RUTAS DE INVENTARIOS
                'map.inventory-info' : "{{ route('map.inventory-info', 'id_plan') }}",
                'map.modal' : "{{ route('map.modal') }}",
                'map.create' : "{{ route('map.create') }}",
                'register.inventory.export-open-modal' : "{{ route('register.inventory.export-open-modal', 'id_plan') }}",
                'register.inventory.export-pdf' : "{{ route('register.inventory.export-pdf', 'id_plan') }}",
                // RUTAS DE MAPAS
                'map.info' : "{{ route('map.info', 'id_map') }}",
                'map.edit' : "{{ route('map.edit', 'id_map') }}",
                'public.view-map' : "{{ route('public.view-map', 'id_map') }}",
                // RUTAS DE CATASTRO
                'map.search.lote' : "{{ route('map.search.lote') }}",
                'map.catastro' : "{{ route('map.catastro') }}",
                'map.rowsByLote' : "{{ route('map.rowsByLote') }}",
                'map.get-lote' : "{{ route('map.get-lote') }}",
            }
        };
    </script>
    <script src="{{ asset('assets/js/geoconecta/maps/index/inventories.js') }}"></script>
    <script src="{{ asset('assets/js/geoconecta/maps/index/maps.js') }}"></script>
    <script src="{{ asset('assets/js/geoconecta/maps/index/catastro.js') }}"></script>

    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=geometry,places&callback=initMap">
    </script>

@endsection