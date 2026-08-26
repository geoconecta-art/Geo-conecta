
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=4&section={{ $section->section }}">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Promovidos por Promotor de la Sección {{ $section->section }}</h5>
            @foreach ( $coords as $coord )
            <h6>Coordinador Seccional {{ $coord->coord_type }}: @if ( isset($coord->name) ) {{ Person::getFullName($coord) }} @endif</h6>
            <h6>Teléfono: @if ( isset($coord->phone) ) {{ $coord->phone }} @endif</h6>
            @endforeach
            <span>Hasta el día {{ date('d/m/Y h:m:i') }} hrs</span>
        </div>
    </div>

    @foreach ( $people as $person )
    <div class="row mt-3">
        <div class="col-lg-12 col-sm-12 mb-3">
            <div class="card h-100">
                
                <div class="card-inner">


                    <div class="card-title-group mb-5">
                        <div class="card-title card-title-sm mob-info">
                            <div>
                                <b>Promotor:</b>
                                <span>{{ Person::getFullName($person) }}</span>
                            </div>
                            <div>
                                <b>Teléfono:</b>
                                <span>{{ $person->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="dashboard-title">Promovidos</h3>

                    <div class="table-responsive mb-5">
                        <table class="nowrap table">
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
                                @foreach ( $person->vp_prom as $i => $p )
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

                    <h6 class="dashboard-title">Afectivos</h3>

                    <div class="table-responsive">
                        <table class="nowrap table">
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
                                @foreach ( $person->va_prom as $i => $p )
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
    @endforeach
    
            

</div>

<script>

    $('.table').DataTable({
        language: spanish,
        paginate: false
    });

    

</script>