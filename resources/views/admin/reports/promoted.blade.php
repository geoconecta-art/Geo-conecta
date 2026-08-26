

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>Promovidos</title>

    <style>

        body {
            /*font-family: Arial, Helvetica, sans-serif;*/
            font-size: 11px;
        }

        header {
            position: fixed;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 30px;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 0.8rem;
        }

        footer td {
            padding-top: 5px;
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
            border-bottom: 1px solid #eaeaea;
            text-align: left;
            padding: 5px;
        }

        .people-table tfoot td {
            border: none;
            text-align: left;
            padding: 5px;
        }

        .pagenum:before {
            content: counter(page);
        }

    </style>

</head>

<body>

<header>
    <table width="100%">
        <tbody>
        <tr>
            <td width="100%" style="text-align:center;">PROMOVIDOS</b></td>
        </tr>
        <tr>
            <td width="100%" style="text-align:center;"></td>
        </tr>
        </tbody>
    </table>
</header>


<footer>
    <table width="100%" style="/*border-top:1px solid #c0c0c0;*/">
        <tbody>
        <tr>
            <td>{{ $date }}</td>
            <td style="text-align:center;">{{ date('h:i:s A') }}</td>
            <td style="text-align:right;">Página <span class="pagenum"></span> de 1</td>
        </tr>
        </tbody>
    </table>
</footer>


<main style="margin-top:15px;">
    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
            <th>NOMBRE</th>
            <th>DOMICILIO</th>
            <th>TELÉFONO</th>
            <th>SECCIONES</th>
            <th>MOVILIZADOR</th>
            <th>SECCIONAL</th>
            <th>ZONAL</th>
            <th>REGIONAL</th>
            <th>DIRECTOR</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $persons as $person )
            <tr>
                <td>{{ $person->name }}</td>
                <td>{{ $person->address }}</td>
                <td>{{ $person->phone }}</td>
                <td>{{ $person->sections }}</td>
                <td>{{ $person->mobilizer }}</td>
                <td>{{ $person->s_coordinator }}</td>
                <td>{{ $person->z_coordinator }}</td>
                <td>{{ $person->r_coordinator }}</td>
                <td>{{ $person->director }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align:right;">
                    <b>TOTAL: </b>
                </td>
                <td>
                    <b>{{ count($persons) }}</b>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</main>

</body>
