<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>PROMOTORES POR SECCION</title>

    @include('admin.reports.pdf._style')

    <style>
        body {
            margin-top: {{ 85 + ( 35 * count($coords))  }}px;
        }
    </style>
</head>

<body>

    @include('admin.reports.pdf._sec-header')

    @include('admin.reports.pdf._footer')

    <div class="watermark">{{ $section->section }}</div>

    <main>
        <table width="100%" cellspacing="0" class="people-table" >
            <thead>
                <tr>
                    <th rowspan="2" width="5%">#</th>
                    <th rowspan="2" width="25%">NOMBRE DEL PROMOTOR</th>
                    <th rowspan="2" width="25%">DOMICILIO</th>
                    <th rowspan="2" width="15%">TELEFONO</th>
                    <th colspan="6" class="text-center" width="20%">PROMOVIDOS</th>
                    <th rowspan="2" width="10%">DF-DL-SEC-MZ</th>
                </tr>
                <tr>
                    <th style="padding-top:0px;font-size:9px;">S1</th>
                    <th style="padding-top:0px;font-size:9px;">S2</th>
                    <th style="padding-top:0px;font-size:9px;">S3</th>
                    <th style="padding-top:0px;font-size:9px;">S4</th>
                    <th style="padding-top:0px;font-size:9px;">S5</th>
                    <th style="padding-top:0px;font-size:9px;">TOTAL</th>
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
                        <td>{{ $p->promotedPerWeek[0] }}</td>
                        <td>{{ $p->promotedPerWeek[1] }}</td>
                        <td>{{ $p->promotedPerWeek[2] }}</td>
                        <td>{{ $p->promotedPerWeek[3] }}</td>
                        <td>{{ $p->promotedPerWeek[4] }}</td>
                        <td>{{ $p->promoted }}</td>
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
                    <td><b>{{ $people->promotedTotalPerWeek[0]}}</b></td>
                    <td><b>{{ $people->promotedTotalPerWeek[1] }}</b></td>
                    <td><b>{{ $people->promotedTotalPerWeek[2] }}</b></td>
                    <td><b>{{ $people->promotedTotalPerWeek[3] }}</b></td>
                    <td><b>{{ $people->promotedTotalPerWeek[4] }}</b></td>
                    <td><b>{{ $people->promotedTotal }}</b></td>
                    <td>  </td>
                </tr>
               
            </tfoot>
        </table>

    </main>

</body>
