@extends('admin.layout.app')

@section('style')
<style>
    .row:has( .dataTables_info ) :nth-child(1){
        margin-left: 0.5rem;
    }

    .dataTables_filter{
        margin-bottom: 1rem;
    }

    @media screen and (width < 560px){
        .row:has( .dataTables_info ) :nth-child(1){
            margin-right: 0.5rem;
        }

        .dataTables_filter{
            margin: 0.5rem;
        }
    }

    @media screen and (width < 768px){
        .table{
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection

@section('content')

@include('admin.plans._test_contrast')

<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">
                    Estadísticas
                </h3>
            </div>
            
            <div class="nk-block-head-content">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                    <div class="toggle-expand-content" data-content="pageMenu">
                        <ul class="d-block d-sm-flex nk-block-tools g-3 pr-2">
                            <li class="col-12 col-sm-auto">
                                <div class="dropdown col-12 col-sm-auto px-0">
                                    <div href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light col-12 col-sm-auto d-flex d-sm-inline-flex justify-content-between" data-toggle="dropdown">
                                        @if ( Auth::user()->hasRole('Super Administrador'))
                                            <span class="text-truncate" id="option-link-selected" >
                                                Todas las Dependencia / Organismo
                                            </span>
                                        @else
                                            <span class="text-truncate" id="option-link-selected" >
                                                {{ $areas[0]->name ?? 'Dirección no seleccionada'}}
                                            </span>
                                        @endif
                                        <em class="dd-indc icon ni ni-chevron-right"></em>
                                    </div>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            @if ( Auth::user()->hasRole('Super Administrador') )
                                                <li>
                                                    <a href="#" class="link-invetory-stats" val="-1" >
                                                        <span> Todas las Dependencia / Organismo </span>
                                                    </a>
                                                </li>
                                            @endif
                                            @foreach ($areas as $area)
                                                <li>
                                                    <a href="#" class="link-invetory-stats" val="{{ $area->id }}" >
                                                        <span> {{ $area->name }} </span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="nk-block-tools-opt col-12 col-sm-auto">
                                <div href="#" target="_blank" class="btn btn-outline-primary w-100 d-flex d-sm-inline-flex justify-content-center" id="export_stats_btn" onclick="exportPDF()">
                                    <input type="hidden" name="id_area_export">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    {{-- onclick="window.print()" --}}
                                    <em class="icon ni ni-reports"></em>
                                    <span>
                                        Reporte
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- .nk-block-head-content -->

        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->

    <div class="nk-block">
        <div class="info">
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    let id_plan = @json( Auth::user()->hasRole('Super Administrador') ? -1 : $areas[0]->id );
    
    window.Laravel = {
        routes : {
            'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

            'stats.area.export' : "{{ route('stats.area.export', 'id_area') }}",
            'stats.direction-info' : "{{ route('stats.direction-info', 'id_direction') }}",
            'stats.area.export' : "{{ route('stats.area.export', 'id_area') }}",
            'stats.error' : "{{ route('stats.error') }}",
        }
    };
</script>

<script src="{{ asset('assets/js/geoconecta/stats/index.js') }}"></script>

@endsection