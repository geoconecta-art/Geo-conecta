
<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">Promovidos</h5>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha de Captura</th>
                            <th>Nombre</th>
                            <th>Celular</th>
                            <th>Domicilio</th>
                            <th>Región</th>
                            <th>Zona</th>
                            <th>Sección</th>
                            <th>Voto</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach ( $promoted as $i => $p )
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ Tools::formatTimeYmdToDmy($p->created_at) }}</td>
                            <td>{{ Person::getFullName($p) }}</td>
                            <td>{{ $p->mobile }}</td>
                            <td>{{ $p->address }}</td>
                            <td>{{ $p->region }}</td>
                            <td>{{ $p->zone }}</td>
                            <td>{{ $p->section }}</td>
                            <td>{{ $p->vote }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
    
</div>

<div class="modal-footer">
    <div class="btn btn-outline-light" data-dismiss="modal">
        Cerrar
    </div>
</div>

<script>

</script>