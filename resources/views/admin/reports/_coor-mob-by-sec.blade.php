
<div class="text-right">
    <a class="btn btn-sm btn-primary mt-3" 
        target="_blank"
        href="/admin/reportes/descargar?type=9&section={{ $section->section }}">
        <span>Generar PDF</span> <em class="icon ni ni-printer"></em>
    </a>
</div>

<div class="nk-block mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Coordinadores y Promotores de la Sección {{ $section->section }}</h5>
            <h6>Coordinador Regional {{ $section->region }}: {{ Person::getFullName($coord_Reg) }}</h6>
            <h6>Teléfono: @if ( isset($coord_Reg->phone) ) {{ $coord_Reg->phone }} @endif</h6>
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
                                <th >#</th>
                                <th >Nombre del Promotor</th>
                                <th >Domicilio</th>
                                <th >Teléfono</th>
                                <th >Promovidos</th>
                                <th style="white-space: nowrap !important;">DF-DL-SEC</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ( $people as $i => $p )
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ Person::getFullName($p) }}</td>
                                        <td>{{ $p->address }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>{{ $p->promoted }}</td>
                                        <td style="white-space: nowrap;">
                                            {{ $section->df }} - {{ $section->dl }} - {{ $p->section }}
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

    <!--COORDINADORES SECCIONALES-->
    <div class="row mt-3">
        <div class="col-lg-12 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Coordinadores Seccionales</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table" id="mobTable" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NOMBRE DEL COORDINADOR</th>
                                    <th>DOMICILIO</th>
                                    <th>TELEFONO</th>
                                    <th>PROMOVIDOS</th>
                                    <th>DF-DL-SEC-MZ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n=1 ?>
                                @foreach ($coords as $coord)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Person::getFullName($coord) }}</td>
                                        <td>{{ $coord->address }}</td>
                                        <td>{{ $coord->phone }}</td>
                                        <td style="text-align: center">{{$coord->promoted}}</td>
                                        <td style="white-space: nowrap;">
                                            {{ $section->df }} - {{ $section->dl }} - {{ $p->section }} - {{ $p->block }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Totales</td>
                                    <td style="text-align: center"><b>{{ $coords->promotedTotal }}</b></td>
                                    <td>  </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
   
    <!--COORDINADORES DE ZONA-->
    <div class="row mt-3">
        <div class="col-lg-12 col-sm-12 mb-3">
            <div class="card h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3">
                        <div class="card-title card-title-sm">
                            <h6 class="dashboard-title text-center">Coordinadores de Zona</h3>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="nowrap table" id="mobTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NOMBRE DEL COORDINADOR</th>
                                    <th>DOMICILIO</th>
                                    <th>TELEFONO</th>
                                    <th>PROMOVIDOS</th>
                                    <th style="white-space: nowrap !important;">DF-DL-SEC-MZ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n=1 ?>
                                @foreach ($coordsZ as $coordz)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Person::getFullName($coordz) }}</td>
                                        <td>{{ $coordz->address }}</td>
                                        <td>{{ $coordz->phone }}</td>
                                        <td style="text-align: center">{{$coordz->promoted}}</td>
                                        <td style="white-space: nowrap;">
                                            {{ $section->df }} - {{ $section->dl }} - {{ $p->section }} - {{ $p->block }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Totales </td>
                                    <td style="text-align: center"><b>{{ $coordsZ->promotedTotal }}</b></td>
                                    <td>  </td>
                                </tr>
                            </tfoot>
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