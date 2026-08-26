
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">
    <title> {{ $map_name ?? 'Mapa'}} </title>

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=2.4.0') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite_v3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/css/autocomplete.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/geoconecta/dropify.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
</head>

<body class="p-0 m-0" style="background: #525659; min-width: 1123px !important;">

    <header class="text-white p-3 d-flex justify-content-between align-items-center" style="background: #323639">
        <span>
            {{ $map_name ?? 'Mapa'}}
        </span>

        <div class="d-flex me-4" id="btn-containers">
            {{-- <div class="text-white btn btn-outline-secondary p-1 rounded-circle border-0 me-2" onclick="window.print();">
                <em class="icon ni ni-download"></em>
            </div> --}}

            <div class="text-white btn btn-outline-secondary px-1 rounded-circle border-0" onclick="window.print();">
                <em class="icon ni ni-printer"></em>
            </div>
        </div>
    </header>

    <main class="w-100 d-flex justify-content-center overflow-hidden" id="printable_area">
        <div class="bg-white p-2" style="width: 1123px;">
            <div class="table col-12 d-flex justify-content-between">
                <div id="map-container" class="col-8 border border-black" style="aspect-ratio: 1; position: relative;">
                    <div id="map-protector w-100 h-100" style="width: 800px; position: absolute; top: 0px; left: 0px; height: 800px; z-index: 99;"></div>
                    <div class="h-100 w-100" id="map"></div>
                </div>
    
                <div class="col-4 px-4 d-flex flex-column justify-content-between" id="solapa-info">
                    <div class="col-12 d-flex justify-content-between" >
                        <div class="border border-black text-center p-2" style="width: calc( calc(calc(100% / 12)* 8 ) - 4px );">
                            <img src="{{ asset('assets/images/localización.jpg') }}" alt="Localización" style="height: 100px;">
                        </div>
    
                        <div class="border border-black p-2 text-center" style="height: 125px; width: calc(calc(100% / 12)* 4 );" >
                            <img src="{{ asset('assets/images/escudo-atizapan.png') }}" alt="Escudo de Atizapán" style="height: 100px;">
                        </div>
                    </div>
    
                    <div class="border border-black p-2">
                        {{ $dependency }}
                    </div>
    
                    <div class="border border-black px-2 py-1">
                        <div class="text-center border border-0 p-0" style="font-size: x-small;">
                            Clave de Mapa:
                        </div>
                        <div class="text-center border border-0 p-0" style="font-size: small;">
                            {{ $key_map }}
                        </div>
                    </div>
    
                    <div class="border border-black p-2">
                        {{-- {{ $mapa->name }} --}}
                        {{ $map_name }}
                    </div>
    
                    <div class="col-12 border border-black p-2" 
                    @if ( empty( $plans ) )
                        style="height: 22rem;"
                    @else
                        style="min-height: 16rem; max-height: 22rem;"
                    @endif>
                        <div class="text-center text-upercase w-100 border border-0 p-0">
                            SIMBOLOGÍA
                        </div>
                        @foreach ($plans as $plan)
                            <div class="d-flex justify-content-start align-items-center border border-0 p-0">
                                @if ( $plan->geometry == "Point" )
                                    <em class="fs-2 icon ni ni-dot" style="color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};" ></em>
                                @else
                                    <em class="ms-2 me-1 icon ni ni-activity" style="color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};" ></em>
                                @endif
                                <div class="border border-0 p-0">
                                    <span style="font-size: small;">
                                        {{ $plan->name }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
    
                    <div class="d-flex justify-content-between">
                        <div class="text-center border border-black p-2" style="width: calc(calc(100% / 12)* 4 );">
                            <img src="{{ asset('assets/images/cenit.jpg') }}" alt="CENIT">
                        </div>
        
                        <div class="border border-black p-2" style="width: calc( calc(calc(100% / 12)* 8 ) - 4px );">
                            <img src="{{ asset('assets/images/atizapan_logo.jpg')}}" alt="Logo Atizapán">
                        </div>
                    </div>
    
                    <div class="col-12 border border-black text-center">
                        Creado por GEO-CONECTA
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{--Loading--}}
    <div id="loading">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                {{-- <span class="sr-only">Loading...</span> --}}
            </div>
        </div>
    </div>

</body>
</html>

<style>
    @media print {
        header, header span, header div, header em {
            display: none;
        }

        @page {
            margin: 0;
            size: landscape;
        }

        body {
            margin-bottom: 0 !important;
        }
    }
</style>

<script src="{{ asset('assets/js/bundle.js?ver=2.4.0') }}"></script>
<script src="{{ asset('assets/js/scripts.js?ver=2.4.0') }}"></script>
<script src="{{ asset('plugins/inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('js/basic.js') }}"></script>

<script>
    let start_lat = @json($lat);
    let start_lng = @json($lng);
    let start_zoom = @json($zoom);
    let type_map = @json($typeMap);
    let start_id_plans = @json( $plans );

    window.Laravel = {
        routes : {
            'map.inventory-info' : "{{ route('map.inventory-info', 'id_plan') }}",
        }
    };
</script>

<script src="{{ asset('assets/js/geoconecta/map_pdf.js') }}"></script>

<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=geometry,places&callback=initMap">
</script>