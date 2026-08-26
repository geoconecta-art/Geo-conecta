
@extends('admin.layout.app')

@section('style')

<style>
    
    .card {
        box-shadow: 0 0 20px white;
    }
</style>
   
@endsection

@section('content')

<div class="nk-block nk-block-middle nk-auth-body  wide-xs">
    <div class="brand-logo pb-4 text-center">
        <img src="{{ $person->image }}">
    </div>
   
</div>
<div class="row">
    <div class="col-12 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
        <div class="card rounded-pill bg-white">
            <div class="card-inner card-inner-lg">
                
                <div class="row text-dark">

                    <div class="col-12">
                        <div class="fw-bolder fs-5 mb-3">{{ Person::getFullName($person); }} </div>
                    </div>

                    <div class="col-6 border-right border-light">
                        <div class="mb-2">
                            <div class="fw-bolder">Fecha de registro</div>
                            <div>{{ Tools::formatTimeYmdToDmy($person->created_at) }}</div>
                        </div>

                        <div class="mb-2">
                            <div class="fw-bolder">Estructura</div>
                            <div>{{ Person::getType($person->type); }}</div>
                        </div>
                        
                        <div class="mb-2">
                            <div class="fw-bolder">Región</div>
                            <div>{{ $person->region }}</div>
                        </div>

                        <div class="mb-2">
                            <div class="fw-bolder">Sección</div>
                            <div>{{ $person->section }}</div>
                        </div>
                        <div>
                            <div class="fw-bolder"># Promovidos</div>
                            <div>{{ count($person->promoted) }}</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div>
                            <div class="fw-bolder">Semana 1</div>
                            <div>{{ $countPromotedWeeks[0] }}</div>
                        </div>

                        <div>
                            <div class="fw-bolder">Semana 2</div>
                            <div>{{ $countPromotedWeeks[1] }}</div>
                        </div>

                        <div>
                            <div class="fw-bolder">Semana 3</div>
                            <div>{{ $countPromotedWeeks[2] }}</div>
                        </div>

                        <div>
                            <div class="fw-bolder">Semana 4</div>
                            <div>{{ $countPromotedWeeks[3] }}</div>
                        </div>

                        <div>
                            <div class="fw-bolder">Semana 5</div>
                            <div>{{ $countPromotedWeeks[4] }}</div>
                        </div>
                    </div>

                    <!--
                    <div class="col-4 text-center">
                        <img src="/img/alta-probabilidad.png" class="w-100px">
                        <div class="text-electo-green mb-3">Alta probabilidad</div>
                        <div class="fw-bolder lh-1">Estadística de avance con el partido</div>
                        <div class="fw-bolder fs-2">98%</div>
                        <div>Votación anterior: PARTIDO</div>
                    </div>
                    -->

                </div>

                <div class="text-center mt-2">
                    <a class="btn btn-outline-light rounded-pill btn-lg" 
                        href="/admin/{{ Person::getSlug($person->type) }}/{{ $person->id }}">
                        <em class="icon ni ni-user"></em> Ir al Perfil
                    </a>
                </div>

            </div>
        </div>

        @if ( count($person->promoted) > 0 )
        <div class="card rounded-pill bg-white">
            <div class="card-inner card-inner-lg">
                <h6>Comentarios</h6>
                <ul>
                    @foreach ( $person->promoted as $promoted )
                        @if ( $promoted->note )
                            <li>
                                {{ $promoted->note }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@section('script')

<script>
    
  </script>

@endsection
