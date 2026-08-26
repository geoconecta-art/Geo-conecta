@extends('public.index')

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
</style>
@endsection

@section('content')
    <div class="card h-100">
        <div class="card-inner p-0">

            <div class="nk-content p-0 d-block d-md-flex">

                {{-- MAPA --}}
                <div class="col-lg-12 p-1">
                    <div class="card card-bordered h-100">
                        <div class="card-inner p-0" id="map-container" style="height: 82vh !important;">
                            <div id="map" style="height: 100%;"></div>
                        </div><!-- .card-inner -->
                    </div><!-- .card -->
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=geometry,places&callback=initMap"></script>
    <script src="{{ asset('assets/js/utils/calculateDistance.js') }}"></script>

    <script>
        let plans = @json( $plans );
    </script>

    <script src="{{ asset('assets/js/geoconecta/public_map/map.js') }}"></script>
    <script src="{{ asset('assets/js/geoconecta/public_map/markers.js') }}"></script>
    <script src="{{ asset('assets/js/geoconecta/public_map/checkbox.js') }}"></script>
@endsection