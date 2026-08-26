
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=7&corporation=-1">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Reporte de Promovidos por Corporaciones</h5>
            <span>Hasta el día {{ date('d/m/Y h:m:i') }} hrs</span>
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

                    <h6 class="dashboard-title">Promovidos</h6>

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
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $corp->proms as $mob )
                                <tr>
                                    <td>{{ $loop->index + 1}}</td>
                                    <td>{{ Person::getFullName($mob) }}</td>
                                    <td><b>{{ $mob->section }}/{{ $mob->id }}</b></td>
                                    <td>{{ $mob->address }}</td>
                                    <td>
                                        @if ( !is_null($mob->phone) ) Hab: {{ $mob->phone }} <br> @endif
                                        Cel: {{ $mob->mobile }}
                                    </td>
                                    <td>{{ $mob->vote }}</td>
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