<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>{{$title}}</title>
    @include('admin.reports.pdf._style')

    <style>
        body {
        margin-top: 80px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>

</head>

<body>
    @include('admin.reports.pdf._all-corps-header')

    @include('admin.reports.pdf._footer')

    <main>
        @foreach ($corporations as $corp)
            @include('admin.reports.pdf._all-corps-sec-header')

            @foreach ($corp->mobs as $mob)

            <div class="row mt-3">
                <div class="col-lg-12 col-sm-12 mb-3">
    
                    <div class="card h-100">
                        <div class="card-inner">
                            <div class="card-title-group mb-3">
                                <div class="card-title card-title-sm">
                                    <div class="card-title-group mb-5">
                                        <div class="card-title card-title-sm mob-info">
                                            <div>
                                                <b>Promotor:</b>
                                                <span>{{ $mob->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <table width="95%" cellspacing="0" class="people-table">
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
                                    @foreach ( $mob->proms as $prom )
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Person::getFullName($prom) }}</td>
                                        <td><b>{{ $prom->section }}/{{ $prom->id }}</b></td>
                                        <td>{{ $prom->address }}</td>
                                        <td>
                                            @if ( !is_null($prom->phone) ) Hab: {{ $prom->phone }} <br> @endif
                                            Cel: {{ $prom->mobile }}
                                        </td>
                                        <td>{{ $prom->vote }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td style="text-align: right;"><b>TOTAL POR PROMOTOR</b></td>
                                    <td style="text-align: left;"><b>{{count($mob->proms)}}</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
            @endforeach
            <div class="row mt-3">
                <div class="col-lg-12 col-sm-12 mb-3">
    
                    <div class="card h-100">
                        <div class="card-inner">
                            <div class="card-title-group mb-3">
                                <div class="card-title card-title-sm">
                                    <div class="card-title-group mb-5">
                                        <div class="card-title card-title-sm mob-info">
                                            <div>
                                                <b>SIN PROMOTOR ASIGNADO</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <table width="95%" cellspacing="0" class="people-table">
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
                                    @foreach ( $noProms[$loop->index] as $prom )
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ Person::getFullName($prom) }}</td>
                                        <td><b>{{ $prom->section }}/{{ $prom->id }}</b></td>
                                        <td>{{ $prom->address }}</td>
                                        <td>
                                            @if ( !is_null($prom->phone) ) Hab: {{ $prom->phone }} <br> @endif
                                            Cel: {{ $prom->mobile }}
                                        </td>
                                        <td>{{ $prom->vote }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td style="text-align: right;"><b>TOTAL POR PROMOTOR</b></td>
                                    <td style="text-align: left;"><b>{{count($noProms[$loop->index])}}</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
        @endforeach
    </main>

</body>
