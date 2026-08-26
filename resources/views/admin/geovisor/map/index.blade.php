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
</style>
@endsection


@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@include('admin.plans._test_contrast')

<div class="card h-100">
    <div class="card-inner p-0" id="map-container" style="height: 80vh !important;">
        <div id="map" style="height: 100%;"></div>
    </div><!-- .card-inner -->
</div>
@endsection


@section('script')
    @if ( Auth::user()->hasRole('Super Administrador') )
        <script src="{{ asset('assets/js/geoconecta/geovisor/filterOptionsSA.js') }}"></script>
    @else
        <script src="{{ asset('assets/js/geoconecta/geovisor/filterOptions.js') }}"></script>
    @endif
    
    <script>
        let id_init_plan = @json( $id_plan );

        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",
                
                // RUTAS DE INVENTARIOS
                'map.inventory-info' : "{{ route('map.inventory-info', 'id_plan') }}",
                'register.inventory.export-open-modal' : "{{ route('register.inventory.export-open-modal', 'id_plan') }}",
                'register.inventory.export-pdf' : "{{ route('register.inventory.export-pdf', 'id_plan') }}",
            }
        };
    </script>
    <script src="{{ asset('assets/js/geoconecta/geovisor/map/index.js') }}"></script>
    <script async defer 
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=geometry,places&callback=initMap">
    </script>

@endsection