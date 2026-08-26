<div class="row g-gs">
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="card">

            <div class="invoice-action">
                <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                    onclick="downloadReport(4)" 
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
                            <h6 class="title">Promovidos</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">{{ $prom }} / {{ $goal }}</div>
                            <div class="nk-ecwg6-ck"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas class="ecommerce-line-chart-s3 chartjs-render-monitor" id="todayOrders" style="display: block; width: 100px; height: 40px;" width="100" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                </div><!-- .card-inner -->
            </div><!-- .nk-ecwg -->
        </div><!-- .card -->
    </div>

</div>

<div class="row">
    <div class="col-lg-4 col-sm-4 mb-3">
        <div class="card h-100">

            <div class="invoice-action">
                <h3>{{ round(( ($prom * 100) / $goal ), 2) }}%</h3>
            </div>

            <div class="card-inner">
                <div class="card-title-group">
                    <div class="card-title card-title-sm">
                        <h6 class="dashboard-title">Promovidos</h6>
                        <div class="info"><b>Meta: {{ $goal }} Promovidos</b></div>
                        <div class="info"><b>Registrados: {{ $prom }} Promovidos</b></div>
                    </div>
                </div>
                <div class="traffic-channel">
                    <div class="traffic-channel-doughnut-ck">
                        <canvas class="analytics-doughnut" id="promotedChart"></canvas>
                    </div>
                    <div class="traffic-channel-group g-2">
                        <div class="traffic-channel-data">
                            <div class="title">
                                <span class="dot dot-lg sq" 
                                    data-bg="#05a8ad"
                                    style="background-color:#05a8ad;">
                                </span>
                                <span>Promovidos</span>
                            </div>
                            <div class="amount">{{ $vp_prom }} <small>{{ round(($vp_prom / $goal) * 100, 2); }}%</small></div>
                        </div>

                        <div class="traffic-channel-data">
                            <div class="title">
                                <span class="dot dot-lg sq" 
                                    data-bg="#66b66a"
                                    style="background-color:#66b66a;">
                                </span>
                                <span>Estructuras Paralelas</span>
                            </div>
                            <div class="amount">{{ $vc_prom }} <small>{{ round(($vc_prom / $goal) * 100, 2); }}%</small></div>
                        </div>

                        <div class="traffic-channel-data">
                            <div class="title">
                                <span class="dot dot-lg sq" 
                                    data-bg="#f9c10b"
                                    style="background-color:#f9c10b;">
                                </span>
                                <span>Afectivos</span>
                            </div>
                            <div class="amount">{{ $va_prom }} <small>{{ round(($va_prom / $goal) * 100, 2); }}%</small></div>
                        </div>

                        <div class="traffic-channel-data">
                            <div class="title">
                                <span class="dot dot-lg sq" 
                                    data-bg="#e85f24"
                                    style="background-color:#e85f24;">
                                </span>
                                <span>Pendientes</span>
                            </div>
                            <div class="amount">{{ $goal - $prom }} <small>{{ round(( ($goal - $prom) / $goal) * 100, 2); }}%</small></div>
                        </div>
                        

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
                        <th>Promotores</th>
                        <th>Promovidos</th>
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
                                <td>
                                    @if ( $section->mob > $section->mob_limit )
                                        <span class="text-danger"><b>{{ $section->mob }}</b></span>/<b>{{ $section->mob_limit }}</b>
                                    @else 
                                        <span class="text-success"><b>{{ $section->mob }}</b></span>/<b>{{ $section->mob_limit }}</b>
                                    @endif  
                                </td>
                                <td>
                                    @if ( $section->prom > $section->goal )
                                        <span class="text-danger"><b>{{ $section->prom }}</b></span>/<b>{{ $section->goal }}</b>
                                    @else 
                                        <span class="text-success"><b>{{ $section->prom }}</b></span>/<b>{{ $section->goal }}</b>
                                    @endif  
                                </td>
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
        labels: [ "Promovidos", "Corporativos", "Afectivos", "Pendientes"],
        dataUnit: 'Promovidos',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#05a8ad", "#66b66a", "#f9c10b", "#e85f24"],
            data: [ {{ $vp_prom }}, {{ $vc_prom }}, {{ $va_prom }}, {{ $goal - $prom }} ]
        }]
    };

    $(document).ready(function() {

        analyticsDoughnut($('#promotedChart'), promotedData);

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