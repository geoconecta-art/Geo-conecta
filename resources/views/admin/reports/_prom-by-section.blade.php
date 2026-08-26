
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        href="/admin/reportes/descargar?type=3&section={{ $section->section }}&r_type=1">
        <span>Descargar CASA D</span> <em class="icon ni ni-printer"></em>
    </a>

    @if ( count($booths) > 4 )
    <div class="btn btn-sm btn-primary mt-3" 
        onclick="downloadRC()"
    >
        <span>Descargar RC</span> <em class="icon ni ni-printer"></em>
    </div>
    @else
    <a class="btn btn-sm btn-primary mt-3" 
        href="/admin/reportes/descargar?type=3&section={{ $section->section }}&r_type=2">
        <span>Descargar RC</span> <em class="icon ni ni-printer"></em>
    </a>
    @endif
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Promovidos de la Sección {{ $section->section }}</h5>
            @foreach ( $coords as $coord )
            <h6>Coordinador Seccional {{ $coord->coord_type }}: @if ( isset($coord->name) ) {{ Person::getFullName($coord) }} @endif</h6>
            <h6>Teléfono: @if ( isset($coord->phone) ) {{ $coord->phone }} @endif</h6>
            @endforeach
            <span>Hasta el día {{ date('d/m/Y h:m:i') }} hrs</span>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Promovidos</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table" id="promTable">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Nombre del Promovido</th>
                                <th width="5%">ID</th>
                                <th width="30%">Domicilio</th>
                                <th width="15%">Teléfonos</th>
                                <th width="5%">Voto</th>
                                <th width="15%">DF-DL-SEC-MZ</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $people as $i => $p )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($p) }}</td>
                                        <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                                        <td>{{ $p->address }}</td>
                                        <td>
                                            @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                                            Cel: {{ $p->mobile }}
                                        </td>
                                        <td>{{ $p->vote }}</td>
                                        <td>
                                            {{ $section->df }} - {{ $section->dl }} - {{ $p->section }} - {{ $p->block }}
                                        </td>
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

    var booths = {!! json_encode($booths) !!};

    $('.table').DataTable({
        language: spanish,
        paginate: false
    });

    function downloadRC() {

        booths.forEach(function(b, index, array) {
            const a = document.createElement('a');
            a.href = "/admin/reportes/descargar?type=3&section={{ $section->section }}&r_type=2&booth=" + b.id;
            a.download = ''; 
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });

    }
    

</script>