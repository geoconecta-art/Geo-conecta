

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>DIRECTORES</title>

    <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            position: fixed;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 1.2rem;
            text-transform: uppercase;
            font-size: 10px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 0.8rem;
        }

        footer td {
            padding-top: 10px;
        }


        .people-table th {
            border: 1px solid #000;
            font-size: 6px;
        }

        .people-table td {
            border: 1px solid #000;
            text-align: left;
            font-size: 6px;
            vertical-align: middle;
            text-align: center;
        }

        /*        
        .people-table tfoot td {
            border: none;
            text-align: left;
            padding: 5px;
        }
        */

    </style>

</head>

<body>

<header>
    <table width="100%">
        <tbody>
        <tr>
            <td width="100%" style="text-align:center;">REPORTE INDIVIDUAL DE DIRECTORES</b></td>
        </tr>
        </tbody>
    </table>
</header>

<footer>
    <table width="100%" style="border-top:1px solid #000;">
        <tbody>
        <tr>
            <td>{{ date('d/m/Y') }}</td>
            <td style="text-align:center;">{{ date('H:m:i') }} hrs. </td>
            <td style="text-align:right;">Página 1 de 1</td>
        </tr>
        </tbody>
    </table>
</footer>

<main>
    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
            <th>DIRECTOR</th>
            <th>TELÉFONO</th>
            <th>ENLACE DIRECCIÓN</th>
            <th>TELÉFONO</th>
            <th>CLAVE DE<br> ELECTOR</th>
            <th>REGIONAL</th>
            <th>TELÉFONO</th>
            <th>CLAVE DE<br> ELECTOR</th>
            <th>ZONA</th>
            <th>ZONAL</th>
            <th>TELÉFONO</th>
            <th>CLAVE DE<br> ELECTOR</th>
            <th>SECCIÓN</th>
            <th>SECCIONAL</th>
            <th>TELÉFONO</th>
            <th>CLAVE DE<br> ELECTOR</th>
            <th>MOBILIZADOR</th>
            <th>TELÉFONO</th>
        </tr>
        </thead>

        <tbody>
            <tr>
                <td @if ( $director->rowspan > 1 ) ) rowspan="{{ $director->rowspan }}" @endif >
                    {{ $director->name }} 
                </td>
                <td @if ( $director->rowspan > 1 ) ) rowspan="{{ $director->rowspan }}" @endif >
                    {{ $director->phone }}
                </td>
                <td @if ( $director->rowspan > 1 ) ) rowspan="{{ $director->rowspan }}" @endif >
                    {{ $director->link_name }}
                </td>
                <td @if ( $director->rowspan > 1 ) ) rowspan="{{ $director->rowspan }}" @endif >
                    {{ $director->link_phone }}
                </td>
                <td @if ( $director->rowspan > 1 ) ) rowspan="{{ $director->rowspan }}" @endif >
                    {{ $director->ine }}
                </td>
                
                <!--Primer Coordinador Regional-->
                <td 
                    @if ( isset($director->reg_coords[0]->rowspan) && $director->reg_coords[0]->rowspan > 1 ) ) rowspan="{{ $director->reg_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->name) ) {{ $director->reg_coords[0]->name }} @endif
                </td>
                
                <td 
                    @if ( isset($director->reg_coords[0]->rowspan) && $director->reg_coords[0]->rowspan > 1 ) ) rowspan="{{ $director->reg_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->phone) ) {{ $director->reg_coords[0]->phone }} @endif
                </td>
                <td 
                    @if ( isset($director->reg_coords[0]->rowspan) && $director->reg_coords[0]->rowspan > 1 ) ) rowspan="{{ $director->reg_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->ine) ) {{ $director->reg_coords[0]->ine }} @endif
                </td>

                <!--Primer Coordinador Zonal-->
                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0] ) ) 1 @endif
                </td>

                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->name) ) {{ $director->reg_coords[0]->zone_coords[0]->name }} @endif
                </td>
            
                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->phone) ) {{ $director->reg_coords[0]->zone_coords[0]->phone }} @endif
                </td>

                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->ine) ) {{ $director->reg_coords[0]->zone_coords[0]->ine }} @endif
                </td>

                <!--Primer Coordinador Seccional-->
                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->section) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->section }} @endif
                </td>
                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->name) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->name }} @endif
                </td>
                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->phone) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->phone }} @endif
                </td>
                <td 
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan) ) rowspan="{{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->ine) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->ine }} @endif
                </td>

                <!--Primer mobilizador-->
                <td>
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]->name }} @endif
                </td>
                <td>
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]->phone }} @endif
                </td>
            

            </tr>

            @include('admin.reports._reg-coords')

        </tbody>
       
      
    </table>
</main>

</body>
