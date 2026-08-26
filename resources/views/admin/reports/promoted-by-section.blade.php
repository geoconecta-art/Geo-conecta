

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>{{ $title }}</title>

    @include('admin.reports._style')

</head>

<body>

@include('admin.reports._header')
@include('admin.reports._footer')


<main style="margin-top:0px;">
    <table width="100%" cellspacing="0" style="margin-bottom:15px;">
        <tbody>
        <tr>
            <td style="text-align:center;">SECCIÓN: <b>{{ $section }}</b></td>
        </tr>
        </tbody>
    </table>

    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
            <th>TIPO</th>
            <th>NOMBRE</th>
            <th>DOMICILIO</th>
            <th>TELÉFONO</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $people as $p )
            <tr>
                <td>PROMOVIDO</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->address }}</td>
                <td>{{ $p->phone }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align:right;">
                    <b>TOTAL POR SECCION: </b>
                </td>
                <td>
                    <b>{{ count($people) }}</b>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>TOTAL GENERAL: </b>
                </td>
                <td>
                    <b>{{ $total }}</b>
                </td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</main>

</body>
