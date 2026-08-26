

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>DIRECTOR REGIONAL</title>

    @include('admin.reports._style')

</head>

<body>

@include('admin.reports._header')

@include('admin.reports._footer')

<main style="margin-top:15px;">
    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
            <th>NOMBRE</th>
            <th>TELÉFONO</th>
            <th>ENLACE REGIONAL</th>
            <th>TELÉFONO</th>
            <th>REGION</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $directors as $director )
            <tr>
                <td>{{ $director->name }}</td>
                <td>{{ $director->phone }}</td>
                <td>{{ $director->link_name }}</td>
                <td>{{ $director->link_phone }}</td>
                <td>{{ $director->region }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align:right;">
                    <b>TOTAL: </b>
                </td>
                <td>
                    <b>{{ count($directors) }}</b>
                </td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
        </tfoot>
    </table>
</main>

</body>
