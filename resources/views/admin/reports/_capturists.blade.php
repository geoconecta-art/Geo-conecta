


<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Reporte de Capturistas</h5>
            <span>Hasta el día {{ date('d/m/Y h:m:i') }} hrs</span>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Registros realizados por Capturistas</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Capturista</th>
                                <th>Usuario</th>
                                <th>Promovidos</th>
                                <th>Promotores</th>
                                <th>Coord. Seccionales</th>
                                <th>Coord. Zona</th>
                                <th>Coord. Regionales</th>
                                <th>Pedro en tu Casa</th>
                                <th>Evaluación y Seguimiento</th>
                                <th>Casa Día D</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $users as $i => $u )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->promoted }}</td>
                                        <td>{{ $u->mob }}</td>
                                        <td>{{ $u->sec_coord }}</td>
                                        <td>{{ $u->zone_coord }}</td>
                                        <td>{{ $u->reg_coor }}</td>
                                        <td>{{ $u->peter }}</td>
                                        <td>{{ $u->inspector }}</td>
                                        <td>{{ $u->house }}</td>
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
    

</script>