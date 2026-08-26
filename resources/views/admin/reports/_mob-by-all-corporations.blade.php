<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=6&corporation=-1">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Reporte de Promotores por Corporaciones </h5>
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
                            <div class="info">Total de promotores corporativos</div>
                            <div class="data-group">
                                <div class="amount">{{ $promotoresVC }}</div>
                            </div>
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
                            <div class="info">Total de promovidos corporativos</div>
                            <div class="data-group">
                                <div class="amount">{{ $promotedCount }}</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($corporations as $corp)
    <div class="row mt-3">
        <div class="col-lg-12 col-sm-12 mb-3">

            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <div class="card-title-group mb-5">
                                <div class="card-title card-title-sm mob-info">
                                    <div>
                                        <b>Corporación:</b>
                                        <span>{{ $corp->name }}</span>
                                    </div>
                                    <div>
                                        <b>Representante:</b>
                                        <span>{{ $corp->manager }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="dashboard-title">Promotores</h3>

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
                                @foreach ( $corp->mobs as $mob )
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $mob->name }}</td>
                                    <td>{{ count(Person::getPromotedByCorporationPerson($mob->id))}}</td>
                                    <th>
                                        <div onclick="showPromotedModal({{ $mob->id }}, 5)"
                                            class="btn btn-round btn-icon btn-outline-light">
                                            <em class="icon ni ni-eye"></em>
                                        </div>
                                    </th>
                                </tr>
                                @endforeach
                                <tr>
                                    <td>{{count($corp->mobs) + 1}}</td>
                                    <td>SIN PROMOTOR ASIGNADO</td>
                                    <td>{{count(Person::getPromotedByCorporationPersonNull($corp->id))}}</td>
                                    <th>
                                        <div onclick="showPromotedModal({{$corp->id}}, 6)"
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
        
    @endforeach
    
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