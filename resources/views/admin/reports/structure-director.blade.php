

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>DIRECTORES</title>

    <style>

        body {
            /*font-family: Arial, Helvetica, sans-serif;*/
        }

        header {
            position: fixed;
            top: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 1.2rem;
            text-transform: uppercase;
            font-size: 11px;
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
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-left: none;
            border-right: none;
            font-size: 6px;
            text-align: left;
            padding-left: 3px;
        }
        
        .people-table th.first {
            border-left: 1px solid #000;
        }
        
        .people-table th.last {
            border-right: 1px solid #000;
        }

        .people-table td {
            border: 1px solid #eaeaea;
            text-align: left;
            font-size: 6px;
            vertical-align: middle;
            padding-left: 3px;
        }
        
        .people-table td.first {
            border-left: none;
        }
        
        .people-table td.last {
            border-right: none;
        }

        .info-table td {
            font-size: 10px;
            padding-bottom: 0px;
            padding-top: 0px;
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
            <td width="100%" style="text-align:center;">REPORTE INDIVIDUAL</td>
        </tr>
        </tbody>
    </table>
</header>

<footer>
    <table width="100%" style="border-top:1px solid #000;">
        <tbody>
        <tr>
            <td>{{ $date }}</td>
            <td style="text-align:center;">{{ date('h:i:s A') }}</td>
            <td style="text-align:right;">Página 1 de 1</td>
        </tr>
        </tbody>
    </table>
</footer>

<main>
    <table class="info-table" style="margin-bottom:15px;">
        <tbody>
        <tr>
            <td><b>TIPO:</b></td>
            <td><b>DIRECTOR</b></td>
        </tr>
        <tr>
            <td><b>NOMBRE:</b></td>
            <td>{{ $director->name }}</td>
        </tr>
        <tr>
            <td><b>DOMICILIO:</b></td>
            <td>{{ $director->address }}</td>
        </tr>
        <tr>
            <td><b>TELÉFONO:</b></td>
            <td>{{ $director->phone }}</td>
        </tr>
        <tr>
            <td><b>ENLACE:</b></td>
            <td>{{ $director->link_name }}</td>
        </tr>
        <tr>
            <td><b>TELÉFONO:</b></td>
            <td>{{ $director->link_phone }}</td>
        </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0" class="people-table">
        <thead>
        <tr>
           
            <th class="first">REGIONAL</th>
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
            <th>MOVILIZADOR</th>
            <th class="last">TELÉFONO</th>
        </tr>
        </thead>

        <tbody>
            <tr>
               
                
                <!--Primer Coordinador Regional-->
                <td class="first"
                    @if ( isset($director->reg_coords[0]->rowspan) && $director->reg_coords[0]->rowspan > 1  ) rowspan="{{ $director->reg_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->name) ) {{ $director->reg_coords[0]->name }} @endif
                </td>
                
                <td 
                    @if ( isset($director->reg_coords[0]->rowspan) && $director->reg_coords[0]->rowspan > 1  ) rowspan="{{ $director->reg_coords[0]->rowspan }}" @endif
                >
                    @if ( isset($director->reg_coords[0]->phone) ) {{ $director->reg_coords[0]->phone }} @endif
                </td>
                <td 
                    @if ( isset($director->reg_coords[0]->rowspan) && $director->reg_coords[0]->rowspan > 1  ) rowspan="{{ $director->reg_coords[0]->rowspan }}" @endif
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
                <td class="last">
                    @if ( isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]) ) {{ $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]->phone }} @endif
                </td>
            

            </tr>

            @include('admin.reports._reg-coords')

        </tbody>
        
        <tfoot>
            <tr>
                <td><b>TOTAL: {{ count($director->reg_coords) }}</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>TOTAL: {{ $zone_coords }}</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>TOTAL: {{ $sec_coords }}</b></td>
                <td></td>
                <td></td>
                <td><b>TOTAL: {{ $mobs }}</b></td>
                <td></td>
            </tr>
        </tfoot>
       
      
    </table>
</main>

</body>
