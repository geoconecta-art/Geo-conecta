
@extends('admin.layout.app')

@section('style')
    <style>
      
        
    </style>
@endsection

@section('content')

    <div class="nk-content-inner dashboard">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Estadísticas</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">
                <div class="row g-gs">

                    <div class="col-xxl-3 col-sm-6">

                        <h6 class="dashboard-title">Probabilidad de votantes</h3>
                        <div class="mb-3">Estadística de avance con el partido</div>
                        
                        <div class="card p-3">
                            <div class="row">
                                <div class="col-8 border-right">
                                    <div class="people-number">0</div>
                                    <div>Habitantes</div>
                                </div>
                                <div class="col-4">
                                    <div class="percentage pt-1">20%</div>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3">
                            <div class="row">
                                <div class="col-8 border-right">
                                    <div class="people-number">0</div>
                                    <div>Habitantes</div>
                                </div>
                                <div class="col-4">
                                    <div class="percentage pt-1">50%</div>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3">
                            <div class="row">
                                <div class="col-8 border-right">
                                    <div class="people-number">0</div>
                                    <div>Habitantes</div>
                                </div>
                                <div class="col-4">
                                    <div class="percentage pt-1">75%</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xxl-9 col-sm-6">
                        <div class="card card-full">
                            <div class="nk-ecwg nk-ecwg8 h-100">
                                <div class="card-inner">
                                    <div class="card-title-group mb-3">
                                        <div class="card-title">
                                            <h6 class="dashboard-title text-center">Movilizadores y beneficiados</h3>
                                        </div>
                                        <div class="card-tools">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle link link-light link-sm dropdown-indicator" data-toggle="dropdown">Mensual</a>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="#"><span>Semanal</span></a></li>
                                                        <li><a href="#" class="active"><span>Mensual</span></a></li>
                                                        <li><a href="#"><span>Anual</span></a></li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nk-ecwg8-legends">
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" data-bg="#2ed908"></span>
                                                <span>Movilizadores</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" data-bg="#06a4eb"></span>
                                                <span>Beneficiados</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="nk-ecwg8-ck">
                                        <canvas class="ecommerce-line-chart-s4" id="salesStatistics"></canvas>
                                    </div>
                                    <div class="chart-label-group pl-5">
                                        <div class="chart-label">01 Mar, 2024</div>
                                        <div class="chart-label">31 Mar, 2024</div>
                                    </div>
                                </div><!-- .card-inner -->
                            </div>
                        </div><!-- .card -->

                    </div>

                    <div class="col-xxl-4 col-sm-6">
                        
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title card-title-sm">
                                        <h6 class="dashboard-title text-center">Estructura</h3>
                                    </div>
                                </div>
                                <div class="traffic-channel">
                                    <div class="traffic-channel-doughnut-ck">
                                        <canvas class="analytics-doughnut" id="TrafficChannelDoughnutData"></canvas>
                                    </div>
                                    <div class="traffic-channel-group g-2">
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Directores</span></div>
                                            <div class="amount">{{ $directors }} <small>{{ $p_directors }}%</small></div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Regionales</span></div>
                                            <div class="amount">{{ $r_coords }} <small>{{ $p_r_coords }}%</small></div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#0f3a69"></span><span>Zonales</span></div>
                                            <div class="amount">{{ $z_coords }} <small>{{ $p_z_coords }}%</small></div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#06a4eb"></span><span>Seccionales</span></div>
                                            <div class="amount">{{ $s_coords }} <small>{{ $p_s_coords }}%</small></div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#2ed908"></span><span>Movilizadores</span></div>
                                            <div class="amount">{{ $mobilizers }} <small>{{ $p_mobilizers }}%</small></div>
                                        </div>
                                    </div><!-- .traffic-channel-group -->
                                </div><!-- .traffic-channel -->
                            </div>
                        </div><!-- .card -->

                    </div>

                    <div class="col-xxl-4 col-sm-6">
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group mb-2">
                                    <div class="card-title">
                                        <h6 class="dashboard-title">Encuestas y Problemáticas</h3>
                                    </div>
                                </div>
                                <ul class="nk-store-statistics">
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Encuestas realizadas</div>
                                            <div class="count">0</div>
                                        </div>
                                        <em class="icon bg-info-dim ni ni-users"></em>
                                        
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Problemáticas recibidas</div>
                                            <div class="count">0</div>
                                        </div>
                                        <em class="icon bg-pink-dim ni ni-box"></em>
                                    </li>
                                </ul>
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div>


                    <div class="col-xxl-4 col-sm-6">
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group mb-2">
                                    <div class="card-title">
                                        <h6 class="dashboard-title">Apoyos otorgados</h3>
                                    </div>
                                </div>
                                <ul class="nk-store-statistics">
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Despensas</div>
                                            <div class="count">0</div>
                                        </div>
                                        <em class="icon bg-warning-dim ni ni-bag"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Tarjetas</div>
                                            <div class="count">0</div>
                                        </div>
                                        <em class="icon bg-success-dim ni ni-cc"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Descuentos</div>
                                            <div class="count">0</div>
                                        </div>
                                        <em class="icon bg-pink-dim ni ni-percent"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Pláticas y conferencias</div>
                                            <div class="count">0</div>
                                        </div>
                                        <em class="icon bg-purple-dim ni ni-vol-half"></em>
                                    </li>
                                </ul>
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div>
                

                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')

    <script>

      

    </script>
    <script src="{{ asset('assets/js/charts/chart-ecommerce.js?ver=2.4.0') }}"></script>
    <script src="{{ asset('assets/js/charts/chart-analytics.js?ver=2.4.0') }}"></script>
    <script src="{{ asset('assets/js/libs/jqvmap.js?ver=2.4.0') }}"></script>

@endsection
