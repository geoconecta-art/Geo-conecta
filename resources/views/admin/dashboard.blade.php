
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
                        <h3 class="nk-block-title page-title">Estadísticas del día {{ date('d/m/Y') }}</h3>
                    </div>
                    <div class="d-flex">
                        <div class="form-group mr-2">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" 
                                value="{{ date('Y-m-d') }}"
                                id="date" 
                                >
                        </div>
                        <div class="btn btn-sm btn-primary mr-3" onclick="getInfo()" style="height:30px;margin-top:32px;">
                            <span>Filtrar</span> <em class="icon ni ni-filter"></em>
                        </div>
                        <div class="btn btn-sm btn-primary" onclick="window.print()" style="height:30px;margin-top:32px;">
                            <span>Imprimir</span> <em class="icon ni ni-printer"></em>
                        </div>
                    </div>
                    
                </div>
               
            </div>

            <div class="nk-block">
                <div class="row g-gs">

                    <div class="col-xxl-4 col-sm-6">

                        <h6 class="dashboard-title">PROMOVIDOS POR REGIONES</h3>
                        <div class="mb-3">De un total de <b>{{ $total_prom }}</b> promovidos</div>
                        
                        @foreach ( $regions as $region )
                        <div class="card p-3">
                            <div class="row">
                                <div class="col-8 border-right">
                                    <div class="people-number"><small>Región</small> {{ $region->region }}</div>
                                    <div>{{ $region->dependence }}</div>
                                </div>
                                <div class="col-4">
                                    <h3 class="dashboard-title pt-1">{{ $region->prom }}</h3>
                                    <div>Promovidos</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="card p-3">
                            <div class="row">
                                <div class="col-8 border-right">
                                    <div class="people-number"><small>Otras</small></div>
                                    <div>Regiones</div>
                                </div>
                                <div class="col-4">
                                    <h3 class="dashboard-title pt-1">{{ $total_prom - $prom  }}</h3>
                                    <div>Promovidos</div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-xxl-4 col-sm-6">

                        <div class="card h-100">
                        	<div class="invoice-action" style="top:330px;">
                                <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                                    
                                    onclick="downloadReport(1)" 
                                    target="_blank"
                                    title="Descargar"
                                    >
                                    <em class="icon ni ni-file-download"></em>
                                </div>
                            </div>

                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title card-title-sm">
                                        <h6 class="dashboard-title">ASISTENTES</h3>
                                        <div class="mb-3">De un total de {{ $people }} personas de las regiones {{ implode(', ', $regions_in) }}</div>
                                    </div>
                                </div>
                                <div class="traffic-channel">
                                    <div class="traffic-channel-doughnut-ck">
                                        <canvas class="analytics-doughnut" id="peopleChart"></canvas>
                                    </div>
                                    <div class="traffic-channel-group g-2">
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#05a8ad"></span><span>Asistentes</span></div>
                                            <div class="amount">{{ $assistants }} <small>{{ round(($assistants / $people) * 100, 2); }}%</small></div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#e85f24"></span><span>Faltantes</span></div>
                                            <div class="amount">{{ $people - $assistants }} <small>{{ round(( ($people - $assistants) / $people) * 100, 2); }}%</small></div>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <div class="col-xxl-4 col-sm-6">
                        
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title card-title-sm">
                                        <h6 class="dashboard-title text-center">ASISTENTES POR NIVEL</h3>
                                        <div class="mb-3">De un total de {{ $assistants }} asistentes</div>
                                    </div>
                                </div>
                                <div class="traffic-channel">
                                    <div class="traffic-channel-doughnut-ck">
                                        <canvas class="analytics-doughnut" id="structureChart"></canvas>
                                    </div>
                                    <div class="traffic-channel-group g-2">
                                        
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#e85f24"></span><span>Coordinador Regional</span></div>
                                            <div class="amount">
                                                {{ $r_coords }} 
                                                @if ( $r_coords == 0 )
                                                <small>0%</small> 
                                                @else 
                                                <small>{{ round(( ($assistants - $r_coords ) / $assistants) * 100, 2); }}%</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#66b66a"></span><span>Coordinador de Zona</span></div>
                                            <div class="amount">
                                                {{ $z_coords }} 
                                                @if ( $z_coords == 0 )
                                                <small>0%</small> 
                                                @else 
                                                <small>{{ round(( ($assistants - $z_coords ) / $assistants) * 100, 2); }}%</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#f9c10b"></span><span>Coordinador Seccional</span></div>
                                            <div class="amount">
                                                {{ $s_coords }} 
                                                @if ( $s_coords == 0 )
                                                <small>0%</small> 
                                                @else 
                                                <small>{{ round(( ($assistants - $s_coords) / $assistants) * 100, 2); }}%</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq" data-bg="#05a8ad"></span><span>Promotor</span></div>
                                            <div class="amount">
                                                {{ $mob }} 
                                                @if ( $mob == 0 )
                                                <small>0%</small> 
                                                @else 
                                                <small>{{ round(( ($assistants - $mob) / $assistants) * 100, 2); }}%</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div><!-- .traffic-channel-group -->
                                </div><!-- .traffic-channel -->
                            </div>
                        </div><!-- .card -->

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')

<script>

    var peopleData = {
        labels: ["Asistentes", "Faltantes"],
        dataUnit: 'Personas',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#05a8ad", "#e85f24"],
            data: [{{ $assistants }}, {{ $people - $assistants }} ]
        }]
    };

    var structureData = {
        labels: ["Coordinador Regional", "Coordinador de Zona", "Coordinador Seccional", "Promotor"],
        dataUnit: 'Personas',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#e85f24", "#66b66a", "#f9c10b", "#05a8ad"],
            data: [{{ $r_coords }}, {{ $z_coords }}, {{ $s_coords }}, {{ $mob }} ]
        }]
    };

    $(document).ready(function() {

        analyticsDoughnut($('#peopleChart'), peopleData);
        analyticsDoughnut($('#structureChart'), structureData);
    });

    function downloadReport(type) {
        let params = `?type=${type}`;

        window.location.href = '{{ route('reports.people') }}' + params;
    }


    function analyticsDoughnut(selector, set_data) {
        var $selector = selector ? $(selector) : $('.analytics-doughnut');
        $selector.each(function () {
        var $self = $(this),
            _self_id = $self.attr('id'),
            _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

        var selectCanvas = document.getElementById(_self_id).getContext("2d");
        var chart_data = [];

        for (var i = 0; i < _get_data.datasets.length; i++) {
            chart_data.push({
            backgroundColor: _get_data.datasets[i].background,
            borderWidth: 2,
            borderColor: _get_data.datasets[i].borderColor,
            hoverBorderColor: _get_data.datasets[i].borderColor,
            data: _get_data.datasets[i].data
            });
        }

        var chart = new Chart(selectCanvas, {
            type: 'doughnut',
            data: {
            labels: _get_data.labels,
            datasets: chart_data
            },
            options: {
            legend: {
                display: _get_data.legend ? _get_data.legend : false,
                rtl: NioApp.State.isRTL,
                labels: {
                boxWidth: 12,
                padding: 20,
                fontColor: '#6783b8'
                }
            },
            rotation: -1.5,
            cutoutPercentage: 70,
            maintainAspectRatio: false,
            tooltips: {
                enabled: true,
                rtl: NioApp.State.isRTL,
                callbacks: {
                title: function title(tooltipItem, data) {
                    return data['labels'][tooltipItem[0]['index']];
                },
                label: function label(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                }
                },
                backgroundColor: '#1c2b46',
                titleFontSize: 13,
                titleFontColor: '#fff',
                titleMarginBottom: 6,
                bodyFontColor: '#fff',
                bodyFontSize: 12,
                bodySpacing: 4,
                yPadding: 10,
                xPadding: 10,
                footerMarginTop: 0,
                displayColors: false
            }
            }
        });
        });
    } 

</script>
   
@endsection
