

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

<main>

    <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
        <thead>
        <tr>
            <th colspan="4" class="first last" style="text-align:center;">DIRECTOR REGIONAL</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%" class="bg-gray" >NOMBRE</td>
                <td style="width:25%">{{ $director->name }}</td>
                <td style="width:25%" class="bg-gray">TELÉFONO</td>
                <td style="width:25%">{{ $director->phone }}</td>
            </tr>
            <tr>
                <td class="bg-gray">ENLACE REGIONAL</td>
                <td>{{ $director->link_name }}</td>
                <td class="bg-gray">TELÉFONO</td>
                <td>{{ $director->link_phone }}</td>
            </tr>
        </tbody>
    </table>

    @foreach ( $director->reg_coords as $reg_coord )

        <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
            <thead>
            <tr>
                <th colspan="4" class="first last" style="text-align:center;">COORDINADOR REGIONAL</th>
            </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width:25%" class="bg-gray">NOMBRE</td>
                    <td style="width:25%">{{ $reg_coord->name }}</td>
                    <td style="width:25%" class="bg-gray">TELÉFONO</td>
                    <td style="width:25%">{{ $reg_coord->phone }}</td>
                </tr>
                <tr>
                    <td class="bg-gray">INE</td>
                    <td>{{ $reg_coord->ine }}</td>
                    <td class="bg-gray">SECCIONES</td>
                    <td>{{ $reg_coord->sections }}</td>
                </tr>
            </tbody>
        </table>

        @foreach ( $reg_coord->zone_coords as $i => $zone_coord )
            <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
                <thead>
                <tr>
                    <th colspan="4" class="first last" style="text-align:center;">COORDINADOR DE ZONA {{ ($i + 1) }}</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="width:25%" class="bg-gray">NOMBRE</td>
                        <td style="width:25%">{{ $zone_coord->name }}</td>
                        <td style="width:25%" class="bg-gray">TELÉFONO</td>
                        <td style="width:25%">{{ $zone_coord->phone }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray">INE</td>
                        <td>{{ $zone_coord->ine }}</td>
                        <td class="bg-gray">SECCIONES</td>
                        <td>{{ $zone_coord->sections }}</td>
                    </tr>
                </tbody>
            </table>


            @foreach ( $zone_coord->sec_coords as $j => $sec_coord )
            <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
                <thead>
                <tr>
                    <th colspan="4" class="first last" style="text-align:center;">COORDINADOR SECCIONAL</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="width:25%" class="bg-gray">NOMBRE</td>
                        <td style="width:25%">{{ $sec_coord->name }}</td>
                        <td style="width:25%" class="bg-gray">TELÉFONO</td>
                        <td style="width:25%">{{ $sec_coord->phone }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray">INE</td>
                        <td>{{ $sec_coord->ine }}</td>
                        <td class="bg-gray">SECCIONES</td>
                        <td>{{ $sec_coord->sections }}</td>
                    </tr>
                </tbody>
            </table>

            @foreach ( $sec_coord->mobs as $k => $mob )
                <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
                    <thead>
                    <tr>
                        <th colspan="4" class="first last" style="text-align:center;">PROMOTOR</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td style="width:25%" class="bg-gray">NOMBRE</td>
                            <td style="width:25%">{{ $mob->name }}</td>
                            <td style="width:25%" class="bg-gray">TELÉFONO</td>
                            <td style="width:25%">{{ $mob->phone }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray">INE</td>
                            <td>{{ $mob->ine }}</td>
                            <td class="bg-gray">SECCIONES</td>
                            <td>{{ $mob->sections }}</td>
                        </tr>
                    </tbody>
                </table>

               
                <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
                    <thead>
                    <tr>
                        <th colspan="3" class="first last" style="text-align:center;">PROMOVIDOS</th>
                    </tr>
                    </thead>

                    <tbody>
                        
                        <tr>
                            <td style="width:50%" class="bg-gray">NOMBRE</td>
                            <td style="width:25%" class="bg-gray">TELÉFONO</td>
                            <td style="width:25%" class="bg-gray">INE</td>
                        </tr>
                        @foreach ( $mob->promoted as $l => $prom )
                        <tr>
                            <td>{{ $prom->name }}</td>
                            <td>{{ $prom->phone }}</td>
                            <td>{{ $prom->ine }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                

            @endforeach

            @endforeach

        @endforeach
        
    @endforeach
    
</main>

</body>
