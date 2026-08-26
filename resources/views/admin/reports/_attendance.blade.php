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
                    <p>{{ $coincidences }} de {{ $people }} personas han asistido durante cada semana seleccionada</p>
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
                        onclick=" window.location.href = '{{ route('reports.people') }}?type=2&day={{ $day }}&weeks={{ implode(',', $weeks) }}'; "
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

    </div>

    <div class="row mt-3">
    @foreach ( $weeks as $i => $week )
        <div class="col-md-3 col-sm-6 mb-3">

            <div class="card h-100">
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
    </div>

</div>

<script>

    @foreach ( $weeks as $i => $week )
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

  
   
    
</script>

