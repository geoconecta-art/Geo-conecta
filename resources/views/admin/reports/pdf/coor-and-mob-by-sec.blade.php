<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>COORDINADORES Y PROMOTORES POR SECCION</title>

    @include('admin.reports.pdf._style')

    <style>
        body {
            margin-top: {{ 85 + ( 35 * count($coords))  }}px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    @include('admin.reports.pdf._coor-mob-sec-header')

    @include('admin.reports.pdf._footer')

    <div class="watermark">{{ $section->section }}</div>

    <main>
        <div style="margin-bottom:5px;">
            <b style="font-size:11px;">PROMOTORES</b>
        </div>
        <table width="95%" cellspacing="0" class="people-table" >
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="30%">NOMBRE DEL PROMOTOR</th>
                    <th width="30%">DOMICILIO</th>
                    <th width="15%">TELEFONO</th>
                    <th width="10%">PROMOVIDOS</th>
                    <th width="10%">DF-DL-SEC</th>
                </tr>
            </thead>
            <tbody>
                <?php $n=1 ?>
                @foreach ($people as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ Person::getFullName($p) }}</td>
                        <td>{{ $p->address }}</td>
                        <td>{{ $p->phone }}</td>
                        <td style="text-align: center">{{ $p->promoted }}</td>
                        <td style="white-space: nowrap;">
                            {{ $section->df }} - {{ $section->dl }} - {{ $p->section }} - {{ $p->block }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td> </td>
                    <td style="text-align:right;">
                        <b>TOTAL POR SECCION: </b>
                    </td>
                    <td>
                        <b>{{ count($people) }}</b>
                    </td>
                    <td><b>TOTAL PROMOVIDOS </td>
                    <td style="text-align: center"><b>{{ $people->promotedTotal }}</b></td>
                    <td>  </td>
                </tr>
               
            </tfoot>
        </table>
        <div class="page-break"></div>
    </main>

    <div style="margin-bottom:5px;">
        <b style="font-size:11px;">COORDINADORES SECCIONALES</b>
    </div>
    <table width="95%" cellspacing="0" class="people-table" >
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="30%">NOMBRE DEL COORDINADOR</th>
                <th width="30%">DOMICILIO</th>
                <th width="15%">TELEFONO</th>
                <th width="10%">PROMOVIDOS</th>
                <th width="10%">DF-DL-SEC-MZ</th>
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
                <td style="text-align:right;">
                    <b>TOTAL POR SECCION: </b>
                </td>
                <td>
                    <b>{{ count($coords) }}</b>
                </td>
                <td><b>TOTAL PROMOVIDOS </td>
                <td style="text-align: center"><b>{{ $coords->promotedTotal }}</b></td>
                <td>  </td>
            </tr>
        </tfoot>
    </table>
    <div class="page-break"></div>

    <div style="margin-bottom:5px;">
        <b style="font-size:11px;">COORDINADORES DE ZONA</b>
    </div>
    <table width="95%" cellspacing="0" class="people-table" >
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="30%">NOMBRE DEL COORDINADOR</th>
                <th width="30%">DOMICILIO</th>
                <th width="15%">TELEFONO</th>
                <th width="10%">PROMOVIDOS</th>
                <th width="10%">DF-DL-SEC-MZ</th>
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
                <td style="text-align:right;">
                    <b>TOTAL POR SECCION: </b>
                </td>
                <td>
                    <b>{{ count($coordsZ) }}</b>
                </td>
                <td><b>TOTAL PROMOVIDOS </td>
                <td style="text-align: center"><b>{{ $coordsZ->promotedTotal }}</b></td>
                <td>  </td>
            </tr>
        </tfoot>
    </table>

</body>
