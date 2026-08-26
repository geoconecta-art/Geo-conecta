<div class="row g-gs">
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="card">

            <div class="invoice-action">
                <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                    onclick="downloadReport(1)" 
                    target="_blank"
                    title="Descargar"
                    >
                    <em class="icon ni ni-file-download"></em>
                </div>
            </div>

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Promotores</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            
                            @if ( $mob_limit == 0 ) 
                            <div class="amount">0</div>
                            @else
                            <div class="amount">{{ $mob }} / {{ $mob_limit }}</div>
                            @endif
                            
                            <div class="nk-ecwg6-ck"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas class="ecommerce-line-chart-s3 chartjs-render-monitor" id="todayOrders" style="display: block; width: 100px; height: 40px;" width="100" height="40"></canvas>
                            </div>
                        </div>
                        <div class="info">Siendo <span class="text-danger"><b>{{ $limit }}</b></span><span> el límite de promotores, aumentado un 20%</span></div>
                    </div>
                </div><!-- .card-inner -->
            </div><!-- .nk-ecwg -->
        </div><!-- .card -->
    </div>

    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="card">

            <div class="invoice-action">
                <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                    onclick="downloadReport(2)" 
                    target="_blank"
                    title="Descargar"
                    >
                    <em class="icon ni ni-file-download"></em>
                </div>
            </div>

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Coordinadores Seccionales</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">{{ $s_coord }} / {{ $s_coord_limit }}</div>
                        </div>
                        <div class="info">En un total de {{ count($sections) }} secciones</span></div>
                    </div>
                </div><!-- .card-inner -->
            </div><!-- .nk-ecwg -->
        </div><!-- .card -->
    </div>

    @if ( $z_coord_limit > 0 )
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="card">

            <div class="invoice-action">
                <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                    onclick="downloadReport(3)" 
                    target="_blank"
                    title="Descargar"
                    >
                    <em class="icon ni ni-file-download"></em>
                </div>
            </div>

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">Coordinadores de Zona</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">{{ $z_coord }} / {{ $z_coord_limit }}</div>
                        </div>
                        <div class="info">En un total de {{ $zones }} zonas</span></div>
                    </div>
                </div><!-- .card-inner -->
            </div><!-- .nk-ecwg -->
        </div><!-- .card -->
    </div>
    @endif

</div>

<div class="row">
    <div class="col-lg-4 col-sm-6 mb-3">
                        
        <div class="card h-100">
            <div class="card-inner">
                <div class="card-title-group">
                    <div class="card-title card-title-sm">
                        <h6 class="dashboard-title text-center">Promotores</h3>
                    </div>
                </div>
                <div class="traffic-channel">
                    <div class="traffic-channel-doughnut-ck">
                        <canvas class="analytics-doughnut" id="promotedChart"></canvas>
                    </div>
                    <div class="traffic-channel-group g-2">
                        @if ( $mob_limit == 0 ) 
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Registrados</span></div>
                            <div class="amount">{{ $mob }} <small>0%</small></div>
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Pendientes</span></div>
                            <div class="amount">{{ $mob_limit - $mob }} <small>0%</small></div>
                        </div>
                        @else
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Registrados</span></div>
                            <div class="amount">{{ $mob }} <small>{{ round(($mob / $mob_limit) * 100, 2); }}%</small></div>
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Pendientes</span></div>
                            <div class="amount">{{ $mob_limit - $mob }} <small>{{ round(( ($mob_limit - $mob) / $mob_limit) * 100, 2); }}%</small></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-3">
                        
        <div class="card h-100">
            <div class="card-inner">
                <div class="card-title-group">
                    <div class="card-title card-title-sm">
                        <h6 class="dashboard-title text-center">Coordinadores Seccionales</h3>
                    </div>
                </div>
                <div class="traffic-channel">
                    <div class="traffic-channel-doughnut-ck">
                        <canvas class="analytics-doughnut" id="sectionChart"></canvas>
                    </div>
                    <div class="traffic-channel-group g-2">
                        @if ( $s_coord_limit == 0 ) 
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Registrados</span></div>
                            <div class="amount">{{ $s_coord }} <small>0%</small></div>
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Pendientes</span></div>
                            <div class="amount">{{ $s_coord_limit - $s_coord }} <small>0%</small></div>
                        </div>
                        @else
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Registrados</span></div>
                            <div class="amount">{{ $s_coord }} <small>{{ round(($s_coord / $s_coord_limit) * 100, 2); }}%</small></div>
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Pendientes</span></div>
                            <div class="amount">{{ $s_coord_limit - $s_coord }} <small>{{ round(( ($s_coord_limit - $s_coord) / $s_coord_limit) * 100, 2); }}%</small></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-4 col-sm-6 mb-3">
                        
        <div class="card h-100">
            <div class="card-inner">
                <div class="card-title-group">
                    <div class="card-title card-title-sm">
                        <h6 class="dashboard-title text-center">Coordinadores de Zona</h3>
                    </div>
                </div>
                <div class="traffic-channel">
                    <div class="traffic-channel-doughnut-ck">
                        <canvas class="analytics-doughnut" id="zoneChart"></canvas>
                    </div>
                    <div class="traffic-channel-group g-2">
                        @if ( $z_coord_limit == 0 ) 
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Registrados</span></div>
                            <div class="amount">{{ $z_coord }} <small>0%</small></div>
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Pendientes</span></div>
                            <div class="amount">{{ $z_coord_limit - $z_coord }} <small>0%</small></div>
                        </div>
                        @else
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#229ab1"></span><span>Registrados</span></div>
                            <div class="amount">{{ $z_coord }} <small>{{ round(($z_coord / $z_coord_limit) * 100, 2); }}%</small></div>
                        </div>
                        <div class="traffic-channel-data">
                            <div class="title"><span class="dot dot-lg sq" data-bg="#b3669f"></span><span>Pendientes</span></div>
                            <div class="amount">{{ $z_coord_limit - $z_coord }} <small>{{ round(( ($z_coord_limit - $z_coord) / $z_coord_limit) * 100, 2); }}%</small></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card card-stretch">
    <div class="card-inner-group">
        <div class="card-inner py-5">

            <div class="table-responsive">
                <table class="nowrap table" id="sectionsTable">
                    <thead>
                    <tr>
                        <th>Sección</th>
                        <th>DF</th>
                        <th>DL</th>
                        <th>Región</th>
                        <th>Zona</th>
                        <th>Dirección</th>
                        <th>LN</th>
                        <th>Meta Sección</th>
                        <th>Promotores</th>
                        <th>Coord. Seccionales</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ( $sections as $section )
                            <tr>
                                <td>{{ $section->section }}</td>
                                <td>{{ $section->df }}</td>
                                <td>{{ $section->dl }}</td>
                                <td>{{ $section->region }}</td>
                                <td>{{ $section->zone }}</td>
                                <td>{{ $section->dependence }}</td>
                                <td>{{ $section->ln }}</td>
                                <td>{{ $section->goal }}</td>
                                <td>
                                    @if ( $section->mob > $section->mob_limit )
                                        <span class="text-danger"><b>{{ $section->mob }}</b></span>/<b>{{ $section->mob_limit }}</b>
                                    @else 
                                        <span class="text-success"><b>{{ $section->mob }}</b></span>/<b>{{ $section->mob_limit }}</b>
                                    @endif  
                                </td>
                                <td>{{ $section->section_coords }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            

        </div>
    </div>
</div>

<script>

    var promotedData = {
        labels: ["Registrados", "Pendientes"],
        dataUnit: 'Promotores',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#229ab1", "#b3669f"],
            data: [{{ $mob }}, {{ $mob_limit - $mob }} ]
        }]
    };

    var sectionData = {
        labels: ["Registrados", "Pendientes"],
        dataUnit: 'Coordinadores Seccionales',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#229ab1", "#b3669f"],
            data: [{{ $s_coord }}, {{ $s_coord_limit - $s_coord }} ]
        }]
    };

    var zoneData = {
        labels: ["Registrados", "Pendientes"],
        dataUnit: 'Coordinadores de Zona',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#229ab1", "#b3669f"],
            data: [{{ $z_coord }}, {{ $z_coord_limit - $z_coord }} ]
        }]
    };

    $(document).ready(function() {

        analyticsDoughnut($('#promotedChart'), promotedData);
        analyticsDoughnut($('#sectionChart'), sectionData);
        analyticsDoughnut($('#zoneChart'), zoneData);

        $('#sectionsTable').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                filename: 'Secciones',
                className: 'btn btn-sm btn-primary mb-3',
                text: '<em class="icon ni ni-download"></em> Exportar Excel',
            }],
            language: spanish,
            paginate: false
        });
    });

</script>