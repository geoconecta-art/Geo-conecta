<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-2">Reporte de Asistencia</h5>
                    <h6>Regiones: {{ implode(', ', $regions) }}</h6>
                    <h6>Semanas: {{ implode(', ', $weeks) }}</h6>
                </div>
                <div class="col-md-6 text-right">
                    <p>{{ $coincidences }} de {{ $people }} personas han asistido durante el periodo</p>
                    <h1>{{ round( (($coincidences / $people) * 100), 2 ) }}%</h1>
                </div>
            </div>
            
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3 col-sm-6 mb-3">
            
            <div class="card">

                <div class="invoice-action">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick=" window.location.href = '{{ route('reports.people') }}?type=2&day={{ $day }}'; "
                        target="_blank" title="Descargar">
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>
    
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Promotores inactivos</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">{{ $inactives }} / {{ $people }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!--
        <div class="col-md-3 col-sm-6 mb-3">
            
            <div class="card">

                <div class="invoice-action">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick="downloadReport(1, 1)" 
                        target="_blank" title="Descargar">
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>
    
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Promotores activos</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">15 / { $people }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            
            <div class="card">

                <div class="invoice-action">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick="downloadReport(1, 1)" 
                        target="_blank" title="Descargar">
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>
    
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Coindidencias entre semanas</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">15 / { $people }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    -->

    </div>

    <div class="row mt-3">
        
        @foreach ( $weeks as $i => $week )
        <div class="col-md-3 col-sm-6 mb-3">

            <div class="card h-100">

                <div class="invoice-action" style="top:316px;">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick=" window.location.href = '{{ route('reports.people') }}?type=3&date={{ $dates[$i] }}&regions={{ implode(',', $regions) }}'; "
                        target="_blank"
                        title="Descargar listado de asistentes"
                        >
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>

                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title">Semana {{ $week }}</h3>
                            <div class="mb-3">De un total de {{ $people }} personas</div>
                        </div>
                    </div>
                    <div class="traffic-channel">
                        <div class="traffic-channel-doughnut-ck">
                            <canvas class="analytics-doughnut" id="peopleChart{{ $i }}"></canvas>
                        </div>
                        <div class="traffic-channel-group g-2">
                            <div class="traffic-channel-data">
                                <div class="title">
                                    <span class="dot dot-lg sq" 
                                        data-bg="#05a8ad"
                                        style="background-color:#05a8ad;"></span>
                                    <span>Asistentes</span>
                                </div>
                                <div class="amount">
                                    {{ $assistants[$i] }} 
                                    @if ( $assistants[$i] == 0 )
                                    <small>0%</small> 
                                    @else 
                                    <small>{{ round(( $assistants[$i] / $people) * 100, 2); }}%</small>
                                    @endif  
                                </div>  
                            </div>
                            <div class="traffic-channel-data">
                                <div class="title">
                                    <span class="dot dot-lg sq" 
                                        data-bg="#e85f24"
                                        style="background-color:#e85f24;"></span>
                                    <span>Faltantes</span>
                                </div>
                                <div class="amount">
                                    {{ $people - $assistants[$i] }}
                                    @if ( ($people - $assistants[$i]) == 0 )
                                    <small>0%</small> 
                                    @else 
                                    <small>{{ round(( ($people - $assistants[$i]) / $people) * 100, 2); }}%</small>
                                    @endif  

                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
        @endforeach

        <div class="col-md-9 mb-3">
            <div class="card card-full">
                <div class="nk-ecwg nk-ecwg8 h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h6 class="dashboard-title text-center">Asistencia por Temporalidad</h3>
                            </div>
                        </div>
                        <ul class="nk-ecwg8-legends">
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#06a4eb" style="background-color:#e85f24;"></span>
                                    <span>Registrados</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#2ed908" style="background-color:#05a8ad;"></span>
                                    <span>Asistentes</span>
                                </div>
                            </li>
                            
                        </ul>
                        <div class="nk-ecwg8-ck">
                            <canvas id="weeksChart"></canvas>
                        </div>
                        <div class="chart-label-group pl-5">
                            <div class="chart-label"></div>
                            <div class="chart-label text-center">
                                Primer Semana
                                <div>
                                    <b>{{ $assistants[0] }} / {{ $people }}</b>
                                </div>
                            </div>
                            <div class="chart-label text-center">
                                Segunda Semana
                                <div>
                                    <b>{{ $assistants[1] }} / {{ $people }}</b>
                                </div>
                            </div>
                            <div class="chart-label text-center">
                                Tercera Semana
                                <div>
                                    <b>{{ $assistants[2] }} / {{ $people }}</b>
                                </div>
                            </div>
                            <div class="chart-label text-center">
                                Cuarta Semana
                                <div>
                                    <b>{{ $assistants[3] }} / {{ $people }}</b>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

       
    </div>

</div>

<script>

    @foreach ( $dates as $i => $date )
    var peopleData{{ $i }} = {
        labels: ["Asistentes", "Faltantes"],
        dataUnit: 'Personas',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#05a8ad", "#e85f24"],
            data: [{{ $assistants[$i] }}, {{ $people - $assistants[$i] }} ]
        }]
    };
    analyticsDoughnut($('#peopleChart{{ $i }}'), peopleData{{ $i }});
    @endforeach

    var weeksData = {
        
        labels: [ '', 'Primer Semana', 'Segunda Semana', 'Tercer Semana', 'Cuarta Semana' ],
        
        dataUnit: 'Asistentes',
        lineTension: .4,
        datasets: [
            {
                label: "Registrados",
                color: "#e85f24",
                dash: 0,
                background: NioApp.hexRGB('#e85f24', .15),
                data: [ 0, {{ $people }}, {{ $people }}, {{ $people }}, {{ $people }}, {{ $people }} ]
            }, 
            {
                label: "Asistentes",
                color: "#05a8ad",
                dash: 0,
                background: NioApp.hexRGB('#05a8ad', .15),
                data: [ 0, {{ $assistants[0] }}, {{ $assistants[1] }}, {{ $assistants[2] }}, {{ $assistants[3] }}, {{ $assistants[4] }} ]
            }
        ]
    };

    ecommerceLineS4($('#weeksChart'), weeksData);

   
    
</script>

