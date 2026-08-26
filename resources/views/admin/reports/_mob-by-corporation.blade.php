
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=6&corporation={{ $corporation->id }}">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Reporte por Corporación </h5>
            <h6>Corporación: {{ $corporation->name }}</h6>
            <h6>Representante: {{ $corporation->manager }}</h6>
            <span>Hasta el día {{ date('d/m/Y h:m:i') }} hrs</span>
        </div>
    </div>

    <div class="row mt-3">
        
        <div class="col-lg-6 col-sm-6 mb-3">
            <div class="card">
    
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Promotores</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">{{ $promotoresCount }}</div>
                            </div>
                            <div class="info">De un total de {{$promotoresVC}} promotores corporativos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6 mb-3">
            <div class="card">
    
                <div class="nk-ecwg nk-ecwg6">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Promovidos</h6>
                            </div>
                        </div>
                        <div class="data">
                            <div class="data-group">
                                <div class="amount">{{ $promotedCount }}</div>
                            </div>
                            <div class="info">De un total de {{$countVC}} promovidos corporativos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--Tabla de promotores-->
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
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Promovidos</th>
                                <th>Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ( $promotores as $promotor )
                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{ $promotor->name}}</td>
                                        <td>{{count(Person::getPromotedByCorporationPerson($promotor->id))}}</td>
                                        <th>
                                            <div onclick="showPromotedModal({{ $promotor->id }}, 5)"
                                                class="btn btn-round btn-icon btn-outline-light">
                                                <em class="icon ni ni-eye"></em>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>{{count($promotores) + 1}}</td>
                                    <td>SIN PROMOTOR ASIGNADO</td>
                                    <td>{{count(Person::getPromotedByCorporationPersonNull($corporation->id))}}</td>
                                    <th>
                                        <div onclick="showPromotedModal({{$corporation->id}}, 6)"
                                            class="btn btn-round btn-icon btn-outline-light">
                                            <em class="icon ni ni-eye"></em>
                                        </div>
                                    </th>
                                </tr>
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

</script>