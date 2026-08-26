<div id="plans_by_area_container">

    <div class="table_title">
        <h2>Inventarios por Dependencia / Organismo</h2>
    </div>

    <table id="plans_by_area_table">
        <thead>
            <th>Dependencia / Organismo</th>
            <th>No. Inventarios</th>
            <th>No. Inventarios con Georreferencias Completas</th>
            <th>No. Inventarios con Georreferencias Faltantes</th>
            <th>No. Inventarios sin Datos</th>
        </thead>
        <tbody>
            @foreach ($area_inventories as $area_inventory)
                <tr>
                    <td> {{ $area_inventory['area_name'] }} </td>
                    <td> {{ $area_inventory['inventories'] }} </td>
                    <td> {{ $area_inventory['inventories_with_georreferencia'] }} </td>
                    <td> {{ $area_inventory['inventories_no_georreferencia'] }} </td>
                    <td> {{ $area_inventory['inventories_no_data'] }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
