
@extends('admin.layout.app')

@section('style')
    <style>
        .map {
            display: none
        }
        
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Mapa</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">

                            <div class="row mb-5 px-4">
                                <div class="col-12 text-center">
                                    <div onclick="showMap(1)" class="btn btn-outline-light">
                                        <span>COORD. REGIONALES</span>
                                    </div>
                                    <div onclick="showMap(2)" class="btn btn-outline-light">
                                        <span>COORD. ZONA</span>
                                    </div>
                                    <div onclick="showMap(3)" class="btn btn-outline-light">
                                        <span>COORD. SECCIÓN</span>
                                    </div>
                                    <div onclick="showMap(4)" class="btn btn-outline-light">
                                        <span>MOVILIZADORES</span>
                                    </div>
                                    <div onclick="showMap(5)" class="btn btn-outline-light">
                                        <span>APOYOS</span>
                                    </div>
                                </div>


                                <div class="col-12 text-center mt-5">
                                    <div class="card bg-white p-5">
                                        <img class="map map-1 img-fluid" src="/img/mapa-coord-region.png">
                                        <img class="map map-2 img-fluid" src="/img/mapa-coord-zona.png">
                                        <img class="map map-3 img-fluid" src="/img/mapa-coord-seccion.png">
                                        <img class="map map-4 img-fluid" src="/img/mapa-movilizadores.png">
                                        <img class="map map-5 img-fluid" src="/img/mapa-apoyos.png">
                                    </div>
                                    
                                </div>

                            </div>

                        

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')

    <script>

        function showMap(op) {
            $('.map').hide();
            $('.map-' + op).show();
        }

    </script>

@endsection
