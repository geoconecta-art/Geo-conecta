
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=1&region={{ $region->region }}">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Reporte de la Región {{ $region->region }}</h5>
            <h6>Dirección: {{ $region->dependence }}</h6>
            <h6>Coordinador Regional: @if ( isset($r_coord->name) ) {{ $r_coord->name }} @endif</h6>
            <h6>Teléfono: @if ( isset($r_coord->phone) ) {{ $r_coord->phone }} @endif</h6>
            <span>Hasta el día {{ date('d/m/Y h:m:i') }} hrs</span>
        </div>
    </div>

    <div class="row mt-3">
        
        <div class="col-lg-4 col-sm-4 mb-3">
            <div class="card">
    
                <!--
                <div class="invoice-action">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick="downloadReport(3)" 
                        target="_blank"
                        title="Descargar"
                        >
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>
                -->
    
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
                            <div class="info">En un total de {{ $zones }} zonas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-4 mb-3">
            <div class="card">
    
                <!--
                <div class="invoice-action">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick="downloadReport(2)" 
                        target="_blank"
                        title="Descargar"
                        >
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>
                -->
    
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
                            <div class="info">En un total de {{ count($sections) }} secciones</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-4 mb-3">
            <div class="card">
                
                <!--
                <div class="invoice-action">
                    <div class="btn btn-icon btn-lg btn-white btn-dim btn-outline-light" 
                        onclick="downloadReport(1)" 
                        target="_blank"
                        title="Descargar"
                        >
                        <em class="icon ni ni-file-download"></em>
                    </div>
                </div>
                -->
    
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Promotores</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">{{ $mob }} / {{ $mob_limit }}</div>
                            </div>
                            <div class="info">En un total de {{ count($sections) }} secciones</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-8 col-sm-8 mb-3">
            <div class="card card-full">
                <div class="nk-ecwg nk-ecwg8 h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h6 class="dashboard-title text-center">Promovidos por Temporalidad</h3>
                            </div>
                        </div>
                        <ul class="nk-ecwg8-legends">
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#06a4eb" style="background-color:#e85f24;"></span>
                                    <span>Meta</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span class="dot dot-lg sq" data-bg="#2ed908" style="background-color:#05a8ad;"></span>
                                    <span>Registrados</span>
                                </div>
                            </li>
                            
                        </ul>
                        <div class="nk-ecwg8-ck">
                            <canvas id="weeksChart"></canvas>
                        </div>
                        <div class="chart-label-group pl-5">
                            <div class="chart-label"></div>
                            <div class="chart-label text-center">
                                Primer Semana 30%
                                <div>
                                    <b>{{ $prom_1 }} / {{ $goal * 0.3 }}</b>
                                </div>
                            </div>
                            <div class="chart-label text-center">
                                Segunda Semana 30%
                                <div>
                                    <b>{{ $prom_2 }} / {{ $goal * 0.3 }}</b>
                                </div>
                            </div>
                            <div class="chart-label text-center">
                                Tercera Semana 20%
                                <div>
                                    <b>{{ $prom_3 }} / {{ $goal * 0.2 }}</b>
                                </div>
                            </div>
                            <div class="chart-label text-center">
                                Cuarta Semana 20%
                                <div>
                                    <b>{{ $prom_4 }} / {{ $goal * 0.2 }}</b>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-4 col-sm-4 mb-3">
                        
            <div class="card h-100">
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
                                    <span>Corporativos</span>
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

        <div class="col-lg-12 col-sm-12 mb-3">        
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Secciones</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table" id="sectionsTable">
                            <thead>
                            <tr>
                                <th>Sección</th>
                                <th>Región</th>
                                <th>Zona</th>
                                <th>Promovidos</th>
                                <th>Afectivos</th>
                                <th>Corporativos</th>
                                <th>Promotores</th>
                                <th>Promotores Activos</th>
                                <th>Total de Promoción</th>
                                <th>Meta 2024</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $sections as $i => $s )
                                    <tr>
                                        <td>{{ $s->section }}</td>
                                        <td>{{ $s->region }}</td>
                                        <td>{{ $s->zone }}</td>
                                        <td>{{ $s->vp_prom }}</td>
                                        <td>{{ $s->va_prom }}</td>
                                        <td>{{ $s->vc_prom }}</td>
                                        <td>
                                        @if ( $s->mob > $s->mob_limit )
                                            <span class="text-danger"><b>{{ $s->mob }}</b></span>/<b>{{ $s->mob_limit }}</b>
                                        @else 
                                            <span class="text-success"><b>{{ $s->mob }}</b></span>/<b>{{ $s->mob_limit }}</b>
                                        @endif 
                                        </td>
                                        <td>{{ $s->active_mob }}</td>
                                        <td>{{ $s->prom }}</td>
                                        <td>{{ $s->goal }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        

        <div class="col-lg-6 col-sm-12 mb-3">
                        
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Coordinadores de Zona</h3>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="nowrap table" id="zCoordsTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Zona</th>
                                <th>Promovidos</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $z_coords as $i => $coord )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($coord) }}</td>
                                        <td>{{ $coord->phone }}</td>
                                        <td>{{ $coord->coord_type }}</td>
                                        <td>{{ $coord->zone }}</td>
                                        <td>{{ $coord->promoted }}</td>
                                        <th>
                                            <div onclick="showPromotedModal({{ $coord->id }})"
                                                class="btn btn-round btn-icon btn-outline-light">
                                                <em class="icon ni ni-eye"></em>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 mb-3">
                        
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Coordinadores Seccionales</h3>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="nowrap table" id="sCoordsTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Zona</th>
                                <th>Sección</th>
                                <th>Promovidos</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $s_coords as $i => $coord )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($coord) }}</td>
                                        <td>{{ $coord->phone }}</td>
                                        <td>{{ $coord->coord_type }}</td>
                                        <td>{{ $coord->zone }}</td>
                                        <td>{{ $coord->section }}</td>
                                        <td>{{ $coord->promoted }}</td>
                                        <th>
                                            <div onclick="showPromotedModal({{ $coord->id }})"
                                                class="btn btn-round btn-icon btn-outline-light">
                                                <em class="icon ni ni-eye"></em>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-12 col-sm-12 mb-3">
                        
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Promotores</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table" id="mobsTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Zona</th>
                                <th>Sección</th>
                                <th>Promovidos</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $mobs as $i => $m )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($m) }}</td>
                                        <td>{{ $m->phone }}</td>
                                        <td>{{ $m->zone }}</td>
                                        <td>{{ $m->section }}</td>
                                        <td>{{ $m->promoted }}</td>
                                        <th>
                                            <div onclick="showPromotedModal({{ $m->id }})"
                                                class="btn btn-round btn-icon btn-outline-light">
                                                <em class="icon ni ni-eye"></em>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

<script>

    $('.table').DataTable({
        language: spanish,
        paginate: false
    });

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

    
    var weeksData = {
        labels: [ '', 'Primer Semana', 'Segunda Semana', 'Tercer Semana', 'Cuarta Semana' ],
        
        dataUnit: 'Promovidos',
        lineTension: .4,
        datasets: [
            {
                label: "Meta",
                color: "#e85f24",
                dash: 0,
                background: NioApp.hexRGB('#e85f24', .15),
                data: [ 0, {{ $goal * 0.3 }}, {{ $goal * 0.3 }}, {{ $goal * 0.2 }}, {{ $goal * 0.2 }} ]
            }, 
            {
                label: "Registrados",
                color: "#05a8ad",
                dash: 0,
                background: NioApp.hexRGB('#05a8ad', .15),
                data: [ 0, {{ $prom_1 }}, {{ $prom_2 }}, {{ $prom_3 }}, {{ $prom_4 }} ]
            }
        ]
    };

    function showPromotedModal(personId) {

        $loading.show();
        let _token = $token.val();

        $.post('/reports/promoted-modal', { 
            '_token':_token, 
            'person_id':personId,
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $('#basicModal .modal-dialog').addClass('modal-lg');
            $basicModal.modal('show');
        });
    } 

    analyticsDoughnut($('#promotedChart'), promotedData);
    ecommerceLineS4($('#weeksChart'), weeksData);

</script>