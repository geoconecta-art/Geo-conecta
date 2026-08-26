

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
            margin-top: {{ 85 + ( 35 * count($coords))  }}px;
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

    </style>
</head>

<body>

@include('admin.reports.pdf._sec-header')

@include('admin.reports.pdf._footer')

<div class="watermark">{{ $section->section }}</div>
<div class="r-type">CASA D</div>

<main>
    <div>
    <table width="100%" cellspacing="0" class="people-table">
            <thead>
            <tr>
            <th width="2%">#</th>
            <th width="25%">Nombre del Promovido</th>
            <th width="5%">ID</th>
            <th width="25%">Domicilio</th>
            <th width="10%">Teléfonos</th>
            <th width="18%">Promotor</th>
            <th width="5%">Voto</th>
            <th width="10%">DF-DL-SEC-MZ</th>
            </tr>
            </thead>
            <tbody>
                @foreach ( $people as $j => $p )
                <tr>
                    <td>{{ $j + 1 }}</td>
                    <td>{{ $p->full_name }}</td>
                    <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                    <td>{{ $p->address }}</td>
                    <td>
                        @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                        Cel: {{ $p->mobile }}
                    </td>
                    <td>{{ $p->mobilizer }}</td>
                    <td>{{ $p->vote }}</td>
                    <td>
                        {{ $section->df }} - {{ $section->dl }} - {{ $p->section }} - {{ $p->block }}
                    </td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td></td>
                    <td style="text-align:right;">
                        <b>TOTAL POR SECCION: </b>
                    </td>
                    <td>
                        <b>{{ count($people) }}</b>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
            
        </table>
    </div>

</main>

</body>
