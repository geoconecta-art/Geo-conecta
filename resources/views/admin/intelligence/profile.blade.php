
@extends('admin.layout.app')

@section('style')

<style>
    body {
        background: url(/img/degradado.png) no-repeat center center / cover !important;
    }
    
    .card {
        box-shadow: 0 0 20px white;
    }
</style>
   
@endsection

@section('content')

<div class="nk-block nk-block-middle nk-auth-body  wide-xs">
    <div class="brand-logo pb-4 text-center">
        <img src="/img/electo.png" alt="logo">
    </div>
   
</div>
<div class="row">
    <div class="col-12 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
        <div class="card rounded-pill bg-white">
            <div class="card-inner card-inner-lg">
                
                <div class="row text-dark">

                    <div class="col-3">
                        <img src="/img/juan-lopez.png">
                    </div>

                    <div class="col-5 border-right border-dark">
                        <div class="fw-bolder fs-5 mb-3">{{ $item->name }} {{ $item->last_name }}</div>
                        <div class="mb-2">
                            <div class="fw-bolder">Dirección</div>
                            <div>{{ $item->address }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="fw-bolder">Estado Civil</div>
                            <div>{{ $item->marital_status }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="fw-bolder">Nivel Socio-Económico:</div>
                            <div>Media Baja</div>
                        </div>
                        <div>
                            <div class="fw-bolder">Edad</div>
                            <div>{{ $item->age }}</div>
                        </div>
                    </div>

                    <div class="col-4 text-center">
                        <img src="/img/alta-probabilidad.png" class="w-100px">
                        <div class="text-electo-green mb-3">Alta probabilidad</div>
                        <div class="fw-bolder lh-1">Estadística de avance con el partido</div>
                        <div class="fw-bolder fs-2">98%</div>
                        <div>Votación anterior: PARTIDO</div>
                    </div>

                </div>

                <div class="text-center mt-2">
                    <div class="btn btn-outline-light rounded-pill btn-lg">
                        <em class="icon ni ni-location"></em> VER EN EL MAPA
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    
  </script>

@endsection
