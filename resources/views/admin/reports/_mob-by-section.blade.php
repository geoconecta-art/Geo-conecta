
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=2&section={{ $section->section }}">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Promotores de la Sección {{ $section->section }}</h5>
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
                            <h6 class="dashboard-title text-center">Promotores</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table" id="mobTable">
                            <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Nombre del Promotor</th>
                                <th rowspan="2">Domicilio</th>
                                <th rowspan="2">Teléfono</th>
                                <th colspan="6" class="text-center">Promovidos</th>
                                <th rowspan="2" style="white-space: nowrap !important;">DF-DL-SEC-MZ</th>
                            </tr>
                            <tr>
                                <th>S1</th>
                                <th>S2</th>
                                <th>S3</th>
                                <th>S4</th>
                                <th>S5</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $people as $i => $p )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($p) }}</td>
                                        <td>{{ $p->address }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>{{ $p->promotedPerWeek[0]}}</td>
                                        <td>{{ $p->promotedPerWeek[1] }}</td>
                                        <td>{{ $p->promotedPerWeek[2] }}</td>
                                        <td>{{ $p->promotedPerWeek[3] }}</td>
                                        <td>{{ $p->promotedPerWeek[4] }}</td>
                                        <td>{{ $p->promoted }}</td>
                                        <td style="white-space: nowrap;">
                                            {{ $section->df }} - {{ $section->dl }} - {{ $p->section }} - {{ $p->block }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> Totales </td>
                                    <td>{{ $people->promotedTotalPerWeek[0]}}</td>
                                    <td>{{ $people->promotedTotalPerWeek[1] }}</td>
                                    <td>{{ $people->promotedTotalPerWeek[2] }}</td>
                                    <td>{{ $people->promotedTotalPerWeek[3] }}</td>
                                    <td>{{ $people->promotedTotalPerWeek[4] }}</td>
                                    <td>{{ $people->promotedTotal }}</td>
                                    <td>  </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--
    <div class="row mt-3">
        
        <div class="col-lg-6 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Afectivos</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre del Promotor</th>
                                <th>Domicilio</th>
                                <th>Teléfono</th>
                                <th>Promovidos</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                foreach ( $people as $i => $p )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($p) }}</td>
                                        <td>{{ $p->address }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>{{ $p->VA }}</td>
                                        <th>
                                            <div onclick="showPromotedModal({{ $p->id }},8)"
                                                class="btn btn-round btn-icon btn-outline-light">
                                                <em class="icon ni ni-eye"></em>
                                            </div>
                                        </th>
                                    </tr>
                                endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Promovidos</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre del Promotor</th>
                                <th>Domicilio</th>
                                <th>Teléfono</th>
                                <th>Promovidos</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                foreach ( $people as $i => $p )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($p) }}</td>
                                        <td>{{ $p->address }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>{{ $p->VP }}</td>
                                        <th>
                                            <div onclick="showPromotedModal({{ $p->id }}, 9)"
                                                class="btn btn-round btn-icon btn-outline-light">
                                                <em class="icon ni ni-eye"></em>
                                            </div>
                                        </th>
                                    </tr>
                                endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    -->

</div>

<script>

    $('.table').DataTable({
        language: spanish,
        paginate: false
    });

    /*
    function showPromotedModal(personId, type) {

        $loading.show();
        let _token = $token.val();

        $.post('/reports/promoted-modal', { 
            '_token':_token, 
            'person_id':personId,
            'type': type,
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $('#basicModal .modal-dialog').addClass('modal-lg');
            $basicModal.modal('show');
        });
    }
    */

    

</script>