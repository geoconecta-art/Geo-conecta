

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>COORDINADOR SECCIONAL</title>

    <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        header {
            position: fixed;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 1.2rem;
            text-transform: uppercase;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: #e30d0d;
            font-size: 1.5rem;
        }

        .people-table th {
            border: 1px solid #000;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .people-table td {
            border: 1px solid #000;
            text-align: left;
            padding: 5px;
        }

        .people-table tfoot td {
            border: none;
            text-align: left;
            padding: 5px;
        }

    </style>

</head>

<body>

<header>
    <table width="100%">
        <tbody>
        <tr>
            <td width="100%" style="text-align:center;">COORDINADOR SECCIONAL</b></td>
        </tr>
        <tr>
            <td width="100%" style="text-align:center;"></td>
        </tr>
        </tbody>
    </table>
</header>

<footer>
    <table width="100%">
        <tbody>
        <tr>
            <td style="text-align:center;">
                
            </td>
        </tr>
        </tbody>
    </table>
</footer>

<main style="margin-top:35px;">
    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
            <th>TIPO COORD.</th>
            <th>NOMBRE DEL COORDINADOR</th>
            <th>DOMICILIO</th>
            <th>TELEFONO</th>
            <th>MOBILIZADORES</th>
            <th>SECCIÓN</th>
        </tr>
        </thead>
        <tbody>
        @foreach($people as $person)
            <tr>
                <td>SECCIONALES</td>
                <td>{{ $person->name }}</td>
                <td>C VASCO DE QUIROGA #23, COL EMILIANO ZAPATA</td>
                <td>{{ $person->phone }}</td>
                <td><b>{{ $person->mobilizers }}</b></td>
                <td>{{ $person->section }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td>
                    <b>TOTAL: {{ count($people) }}</b>
                </td>
                <td></td>
                <td></td>
                <td><b>{{ $mobilizers }}</b></td>
                <td></td>

            </tr>
        </tfoot>
    </table>
</main>

</body>
