

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>PROMOVIDOS POR PROMOTOR</title>

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
            margin-top: 85px;
            margin-bottom: 20px;
        }

        .page-break {
            page-break-before: always;
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
            top: 40px;
            left: 5px;
            font-size: 1rem;
            z-index: -1;
            font-weight: bold;
        }

    </style>
</head>

<body>

@include('admin.reports.pdf._header')

@include('admin.reports.pdf._footer')

<div class="watermark">{{ $section->section }}</div>

<div class="r-type">PROMOTOR</div>

<main>

    @foreach ( $coords as $coord )

        <div style="margin-bottom:30px;">
            <div style="text-align:center;font-size:1rem;">PROMOVIDOS</div>
            <table width="100%" cellspacing="0" class="people-table">
                <thead>
                    <tr>
                        <td colspan="4">
                            <div style="font-size:12px;">
                                <div><b>COORDINADOR SECCIONAL {{ $coord->coord_type }}:</b> {{ Person::getFullName($coord) }}</div>
                            </div>
                        </td>
                        <td colspan="3">
                            <div style="font-size:12px;">
                                <div><b>TELEFONO:</b> {{ $coord->phone }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="3%">#</th>
                        <th width="25%">Nombre del Promovido</th>
                        <th width="7%">ID</th>
                        <th width="30%">Domicilio</th>
                        <th width="15%">Teléfonos</th>
                        <th width="5%">Voto</th>
                        <th width="15%">DF-DL-SEC-MZ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $coord->vp_prom as $j => $p )
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td>{{ Person::getFullName($p) }}</td>
                        <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                        <td>{{ $p->address }}</td>
                        <td>
                            @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                            Cel: {{ $p->mobile }}
                        </td>
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
                            <b>TOTAL PROMOVIDOS: </b>
                        </td>
                        <td>
                            <b>{{ count($coord->vp_prom) }}</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                </tfoot>
                
            </table>
        </div>

        <div class="page-break"></div>

        <div>
            <div style="text-align:center;font-size:1rem;">AFECTIVOS</div>
            <table width="100%" cellspacing="0" class="people-table">
                <thead>
                    <tr>
                        <td colspan="4">
                            <div style="font-size:12px;">
                                <div><b>COORDINADOR SECCIONAL {{ $coord->coord_type }}:</b> {{ Person::getFullName($coord) }}</div>
                            </div>
                        </td>
                        <td colspan="3">
                            <div style="font-size:12px;">
                                <div><b>TELEFONO:</b> {{ $coord->phone }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="3%">#</th>
                        <th width="25%">Nombre del Promovido</th>
                        <th width="7%">ID</th>
                        <th width="30%">Domicilio</th>
                        <th width="15%">Teléfonos</th>
                        <th width="5%">Voto</th>
                        <th width="15%">DF-DL-SEC-MZ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $coord->va_prom as $j => $p )
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td>{{ Person::getFullName($p) }}</td>
                        <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                        <td>{{ $p->address }}</td>
                        <td>
                            @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                            Cel: {{ $p->mobile }}
                        </td>
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
                            <b>TOTAL AFECTIVOS: </b>
                        </td>
                        <td>
                            <b>{{ count($coord->va_prom) }}</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                </tfoot>
                
            </table>
        </div>

        <div class="page-break"></div>
    @endforeach

    @foreach ( $people as $person )

        <div style="margin-bottom:30px;">
            <div style="text-align:center;font-size:1rem;">PROMOVIDOS</div>
            <table width="100%" cellspacing="0" class="people-table">
                <thead>
                    <tr>
                        <td colspan="4">
                            <div style="font-size:12px;">
                                <div><b>PROMOTOR:</b> {{ Person::getFullName($person) }}</div>
                            </div>
                        </td>
                        <td colspan="3">
                            <div style="font-size:12px;">
                                <div><b>TELEFONO:</b> {{ $person->phone }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="3%">#</th>
                        <th width="25%">Nombre del Promovido</th>
                        <th width="7%">ID</th>
                        <th width="30%">Domicilio</th>
                        <th width="15%">Teléfonos</th>
                        <th width="5%">Voto</th>
                        <th width="15%">DF-DL-SEC-MZ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $person->vp_prom as $j => $p )
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td>{{ Person::getFullName($p) }}</td>
                        <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                        <td>{{ $p->address }}</td>
                        <td>
                            @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                            Cel: {{ $p->mobile }}
                        </td>
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
                            <b>TOTAL PROMOVIDOS: </b>
                        </td>
                        <td>
                            <b>{{ count($person->vp_prom) }}</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                </tfoot>
                
            </table>
        </div>

        <div class="page-break"></div>

        <div>
            <div style="text-align:center;font-size:1rem;">AFECTIVOS</div>
            <table width="100%" cellspacing="0" class="people-table">
                <thead>
                    <tr>
                        <td colspan="4s">
                            <div style="font-size:12px;">
                                <div><b>PROMOTOR:</b> {{ Person::getFullName($person) }}</div>
                            </div>
                        </td>
                        <td colspan="3">
                            <div style="font-size:12px;">
                                <div><b>TELEFONO:</b> {{ $person->phone }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="3%">#</th>
                        <th width="25%">Nombre del Promovido</th>
                        <th width="7%">ID</th>
                        <th width="30%">Domicilio</th>
                        <th width="15%">Teléfonos</th>
                        <th width="5%">Voto</th>
                        <th width="15%">DF-DL-SEC-MZ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $person->va_prom as $j => $p )
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td>{{ Person::getFullName($p) }}</td>
                        <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                        <td>{{ $p->address }}</td>
                        <td>
                            @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                            Cel: {{ $p->mobile }}
                        </td>
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
                            <b>TOTAL AFECTIVOS: </b>
                        </td>
                        <td>
                            <b>{{ count($person->va_prom) }}</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                </tfoot>
                
            </table>
        </div>

        <div class="page-break"></div>
    @endforeach
</main>

</body>
