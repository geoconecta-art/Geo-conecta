
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
                            <div class="amount">
                                {{ $assistants }} 
                                @if ( $people == 0 )
                                <small>0%</small> 
                                @else 
                                <small>{{ round(( $assistants / $people) * 100, 2); }}%</small>
                                @endif  
                            </div>  
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#e85f24"></span><span>Faltantes</span></div>
                            <div class="amount">
                                {{ $people - $assistants }}
                                @if ( $people == 0 )
                                <small>0%</small> 
                                @else 
                                <small>{{ round(( ($people - $assistants) / $people) * 100, 2); }}%</small>
                                @endif  

                            </div>
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
                                <small>{{ round(( ($r_coords ) / $assistants) * 100, 2); }}%</small>
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
                                <small>{{ round(( ($z_coords ) / $assistants) * 100, 2); }}%</small>
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
                                <small>{{ round(( ($s_coords) / $assistants) * 100, 2); }}%</small>
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
                                <small>{{ round(( ($mob) / $assistants) * 100, 2); }}%</small>
                                @endif
                            </div>
                        </div>
                    </div><!-- .traffic-channel-group -->
                </div><!-- .traffic-channel -->
            </div>
        </div><!-- .card -->

    </div>

</div>

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

    analyticsDoughnut($('#peopleChart'), peopleData);
    analyticsDoughnut($('#structureChart'), structureData);

    
</script>
   

