{{-- TARJETAS CON INFORMACIÓN DE INVENTARIOS --}}
<div class="row mt-3">

    <input type="hidden" name="name-area" value="{{ $area_name }}">
    <div class="col-lg-3 col-sm-3 mb-3 h-100">
        <div class="card">

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">No. Inventarios</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">
                                {{ $inventaries }}
                            </div>
                        </div>
                        <div class="info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-3 mb-3">
        <div class="card">

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">No. Inventarios con Georreferencias Completas</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">
                                {{ $inventaries_with_georreferencia }}
                            </div>
                        </div>
                        <div class="info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-3 mb-3">
        <div class="card">

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">No. Inventarios con Georreferencias Faltantes</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">
                                {{ $inventaries_no_georreferencia }}
                            </div>
                        </div>
                        <div class="info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-3 mb-3 h-100">
        <div class="card">

            <div class="nk-ecwg nk-ecwg6">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">No. Inventarios sin Datos</h6>
                        </div>
                    </div>
                    <div class="data">
                        <div class="data-group">
                            <div class="amount">
                                {{ $inventaries_no_data }}
                            </div>
                        </div>
                        <div class="info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-inner">

        {{-- GRÁFICAS --}}
        <div class="mb-4 row">
            {{-- GRÁFICA DE DONA --}}
            <div class="col-md-4 col-xxl-3 mb-4 mb-sm-0">
                <div class="card card-bordered h-100">
                    <div class="card-inner">

                        <div class="card-title-group">
                            <div class="card-title card-title-sm">
                                <h6 class="title">Inventarios</h6>
                            </div>
                        </div>

                        <div class="traffic-channel">
                            <div class="traffic-channel-doughnut-ck">
                                <canvas class="analytics-doughnut" width="900" height="900" id="planChart"
                                    style="width: 900px; height: 900px;"></canvas>
                                </canvas>
                            </div>
                        </div><!-- .traffic-channel -->
                    </div>
                </div><!-- .card -->
            </div>

            {{-- GRÁFICA DE BARRAS --}}
            <div class="col-md-8 col-xxl-9">
                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <div class="card-head">
                            <h6 class="title">
                                Inventarios por {{ $one_direction ? 'Área' : 'Dependencia / Organismo' }}
                            </h6>
                        </div>
                        
                        <div class="nk-ck-sm">
                            <canvas class="bar-chart" id="barChartStacked" width="424" height="180" style="display: block; box-sizing: border-box; height: 180px; width: 424px;"></canvas>
                        </div>
                    </div>

                    
                </div><!-- .card-preview -->
            </div>

        </div>

        {{-- TABLA COMPARATIVA DE INVENTARIOS PARA TODAS LAS Dependencia / Organismo --}}
        @if ( !$one_direction )

            <div class="col-12 p-0 mb-4">
                <div class="card card-bordered h-100">
                    <div class="card-inner-group  ">

                        <div class="card-inner border-bottom-0 pb-2">
                            <div class="nk-wg-action">

                                <div class="nk-wg-action-content">
                                    <div class="title">
                                        <b>Inventarios por Dependencia / Organismo</b>
                                    </div>
                                    <p>
                                        Listado de inventarios por Dependencia / Organismo.
                                    </p>
                                </div>

                            </div>
                        </div><!-- .card-inner -->

                        <div class="card card-preview">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">
                                            Dependencia / Organismo
                                        </th>
                                        <th scope="col">
                                            No. Inventarios
                                        </th>
                                        <th scope="col">
                                            No. Inventarios con Georreferencias Completas
                                        </th>
                                        <th scope="col">
                                            No. Inventarios con Georreferencias Faltantes
                                        </th>
                                        <th>
                                            No. Inventarios Sin Datos
                                        </th>
                                    </tr>
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

                    </div><!-- .card-inner-group -->
                </div><!-- .card -->
            </div>

        @endif

        {{-- INVENTARIOS SIN GEOREFERENCIA --}}
        <div class="col-12 p-0">
            <div class="card card-bordered h-100">
                <div class="card-inner-group  ">

                    <div class="card-inner border-bottom-0 pb-2">
                        <div class="nk-wg-action">

                            <div class="nk-wg-action-content">

                                <div class="title">
                                    <b>Inventarios Con Georreferencia Faltantes</b>
                                </div>
                                <p>Listado de inventarios que poseen datos cuyas georreferencias están incompletas</p>
                            </div>

                        </div>
                    </div><!-- .card-inner -->

                    <div class="card card-preview">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    @if ( !$one_direction )
                                        <th scope="col">
                                            Dependencia / Organismo
                                        </th>
                                    @endif
                                    
                                    <th scope="col">
                                        Área
                                    </th>
                                    <th scope="col">
                                        Inventario
                                    </th>
                                    <th scope="col">
                                        No. Datos
                                    </th>
                                    <th scope="col">
                                        Datos Sin Georreferencia
                                    </th>
                                    <th>
                                        Ir al Inventario
                                    </th>
                                </tr>
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
                                        <td>
                                            <a href="{{ route('register.inventory.index', $plan['id']) }}" target="_blank" class="btn btn-icon btn-sm btn-outline-primary">
                                                <em class="icon ni ni-arrow-right"></em>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div><!-- .card-inner-group -->
            </div><!-- .card -->
        </div>

        <input type="hidden" name="planGeo" value="{{ $inventaries_with_georreferencia }}">
        <input type="hidden" name="planNoGeo" value="{{ $inventaries_no_georreferencia }}">
        <input type="hidden" name="planNoRows" value="{{ $inventaries_no_data }}">

    </div>
</div>