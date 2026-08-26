<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>{{$title}}</title>
    @include('admin.reports.pdf._style')

    <style>
        body {
        margin-top: 110px;
    }
    </style>

</head>

<body>

    <!--PDF QUE CONTIENE LA INFORMACIÓN DE LOS PROMOVIDOS POR CORPORACIÓN-->

    @include('admin.reports.pdf._corp-sec-header')

    @include('admin.reports.pdf._footer')

    <main>
        <table width="95%" cellspacing="0" class="people-table">
            <thead>
            <tr>
            <th width="5%">#</th>
            <th width="25%">Nombre del Promovido</th>
            <th width="5%">ID</th>
            <th width="35%">Domicilio</th>
            <th width="15%">Teléfonos</th>
            <th width="5%">Voto</th>
            </tr>
            </thead>
            <tbody>
                @foreach ( $proms as $i => $p )
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ Person::getFullName($p) }}</td>
                    <td><b>{{ $p->section }}/{{ $p->id }}</b></td>
                    <td>{{ $p->address }}</td>
                    <td>
                        @if ( !is_null($p->phone) ) Hab: {{ $p->phone }} <br> @endif
                            Cel: {{ $p->mobile }}
                    </td>
                    <td>{{ $p->vote }}</td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td></td>
                    <td style="text-align:right;">
                        <b>TOTAL POR CORPORACIÓN: </b>
                    </td>
                    <td>
                        <b>{{ count($proms) }}</b>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
            
        </table>
    </main>

</body>
