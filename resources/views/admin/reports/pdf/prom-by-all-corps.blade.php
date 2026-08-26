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
                    @foreach ( $corp->proms as $prom )
                    <tr>
                        <td>{{ $loop->index + 1}}</td>
                        <td>{{ Person::getFullName($prom) }}</td>
                        <td><b>{{ $prom->section }}/{{ $prom->id }}</b></td>
                        <td>{{ $prom->address }}</td>
                        <td>
                            @if ( !is_null($prom->phone) ) Hab: {{ $prom->phone }} <br> @endif
                            Cel: {{ $prom->promile }}
                        </td>
                        <td>{{ $prom->vote }}</td>
                    </tr>  
                    @endforeach
                </tbody>
                <tfoot>
                    <td></td>
                    <td style="text-align:right;">
                        <b>TOTAL POR CORPORACIÓN: </b>
                    </td>
                    <td>
                        <b>{{ count($corp->proms) }}</b>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tfoot>
            </table>

            <div class="page-break"></div>
        @endforeach
    </main>

</body>
