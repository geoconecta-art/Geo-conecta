<div id="missing_geo_container">

    <div class="table_title">
        <h2>Inventarios con Georreferencias Faltantes</h2>
    </div>

    <table id="missing_geo_table">
        <thead>
            @if ( !$one_direction )
                <th>Dependencia / Organismo</th>
            @endif
            <th>Área</th>
            <th>Inventario</th>
            <th>No. Registros</th>
            <th>No. Datos con Georreferencias Faltantes</th>
        </thead>
        <tbody>
            @foreach ($info_inventaries_no_georreferencia as $plan)
            <tr>
                @if ( !$one_direction )
                    <td> {{ $plan['area_name'] }} </td>
                @endif
                <td> {{ $plan['subarea_name'] }} </td>
                <td> {{ $plan['name'] }} </td>
                <td> {{ $plan['countRow'] }} </td>
                <td> {{ $plan['countRowNoGeo'] }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>