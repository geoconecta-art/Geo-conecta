

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>PROMOVIDOS POR SECCION</title>

    <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 20px;
            font-size: 9px;
        }

        footer td {
            padding-top: 10px;
        }

        .people-table th {
            border: none;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #c0c0c0;
            text-align: left;
            padding-left: 5px;
        }

        .people-table td {
            border-bottom: 1px solid #c0c0c0;
            text-align: left;
            padding: 5px;
        }

        .people-table tfoot td {
            border: none;
            text-align: left;
            padding: 5px;
        }

        body {
            margin-top: {{ ( 28 * count($coords))  }}px;
            margin-bottom: 20px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.5;
            font-size: 12rem;
            color: #c0c0c0;
            z-index: -1;
        }

        .r-type {
            position: fixed;
            top: 30px;
            left: 5px;
            font-size: 1rem;
            z-index: -1;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        .booth-info {
            font-weight: bold;
            font-size: 14px;
        }

        .attendance-table {
            border-collapse: collapse; 
            width: 100%;
        }

        .attendance-table td {
            border: 1px solid black; 
            padding-top: 12px; 
            text-align: left; 
        }

        .attendance-table th {
            background-color: #fff;
        }

        .symbol {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 24px;
            text-align: center;
            vertical-align: middle;
        }

    </style>
</head>

<body>

@include('admin.reports.pdf._sec-header')

@include('admin.reports.pdf._footer')

<div class="watermark">{{ $section->section }}</div>
<div class="r-type">RC</div>

<main>
    <div>
    <table width="100%" cellspacing="0" class="people-table" style="margin-top: {{ 85 + ( 8 * count($coords))  }}px;">
            <thead>
            <tr>
            <th width="15%">Clave</th>
            <th width="65%">Tipo de Casilla</th>
            <th width="20%">Letra 1er Apellido</th>
            </tr>
            </thead>
            <tbody>
                @foreach ( $booths as $i => $b )
                <tr>
                    <td>{{ $b->type }}</td>
                    <td>{{ $b->type_name }}</td>
                    <td>{{ $b->letters }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @foreach ( $booths as $i => $b )
    @if ( count($b->people) > 0 )
    <div class="page-break"></div>
    <div>
    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
            <th colspan="5" style="text-align:right;padding-top:0px;background-color:#fff;">
                <div class="booth-info">CASILLA {{ $b->type_name }} ({{ $b->type }}) {{ $b->letters }}</div>
                <div>Marcar el símbolo según el horario</div>
            </th>
        </tr>

        <tr>
            <th colspan="4" style="background-color:#fff;"></th>
            <th style="background-color:#fff;">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th width="33%" style="text-align:center;">11am</th>
                            <th width="33%" style="text-align:center;">2pm</th>
                            <th width="34%" style="text-align:center;">4:30pm</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td style="padding:0px;">
                                <div class="symbol">&#10003;</div>
                            </td>
                            <td style="padding:0px;">
                                <div class="symbol">&#x2717;</div>
                            </td>
                            <td style="padding:0px;">
                                <div class="symbol">&#x25CB;</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </th>
        </tr>

        <tr>
            <th width="15%">#</th>
            <th width="50%">Nombre del Promovido</th>
            <th width="10%">ID</th>
            <th width="10%">Distintivo</th>
            <th width="15%">Asistencia</th>
        </tr>
        </thead>
        <tbody>
            @foreach ( $b->people as $j => $p )
            <tr>
                <td>{{ $j + 1 }}</td>
                <td>{{ $p->full_name }}</td>
                <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                <td>
                    @if ( $p->vote == 'VC' )
                        {{ $p->corporation }}
                    @else
                        {{ $p->vote }}
                    @endif
                </td>
                <td>
                    <table class="attendance-table">
                        <tbody>
                            <tr>
                                <td width="33%"></td>
                                <td width="33%"></td>
                                <td width="34%"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td></td>
                <td style="text-align:right;">
                    <b>TOTAL POR CASILLA: </b>
                </td>
                <td>
                    <b>{{ count($b->people) }}</b>
                </td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
            
        </table>
    </div>
    @endif
    @endforeach

</main>

</body>
