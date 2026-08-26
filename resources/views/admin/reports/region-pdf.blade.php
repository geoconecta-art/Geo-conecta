

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>REGION </title>

    @include('admin.reports._style')
</head>

<body>

@include('admin.reports._header')

@include('admin.reports._footer')

<main>

    <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
        <thead>
        <tr>
            <th colspan="4" class="first last" style="text-align:center;">REGION {{ $region->region }}</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%" class="bg-gray">REGION</td>
                <td style="width:25%"><b>{{ $region->region }}</b></td>
                <td style="width:25%" class="bg-gray">DIRECCIÓN</td>
                <td style="width:25%"><b>{{ $region->dependence }}</b></td>
            </tr>
            <tr>
                <td class="bg-gray">COORDINADORES DE ZONA</td>
                <td><b>{{ $region->z_coord }} / {{ $region->z_coord_limit }}</b></td>
                <td class="bg-gray">COORDINADORES SECCIONALES</td>
                <td><b>{{ $region->s_coord }} / {{ $region->s_coord_limit }}</b></td>
            </tr>
            <tr>
                <td class="bg-gray">PROMOTORES</td>
                <td><b>{{ $region->mob }} / {{ $region->mob_limit }}</b></td>
                <td class="bg-gray">PROMOVIDOS</td>
                <td><b>{{ $region->prom }} / {{ $region->goal  }}</b></td>
            </tr>
        </tbody>
    </table>

    <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
        <thead>
        <tr>
            <th colspan="4" class="first last" style="text-align:center;">COORDINADOR REGIONAL</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%" class="bg-gray">NOMBRE</td>
                <td style="width:25%">
                    {{ ( isset($region->r_coord) ) ? Person::getFullName($region->r_coord) : '' }}
                </td>
                <td style="width:25%" class="bg-gray">TELEFONO</td>
                <td style="width:25%">{{ ( isset($region->r_coord->ine) ) ? $region->r_coord->ine : '' }}</td>
            </tr>
            <tr>
                <td class="bg-gray">CLAVE DE ELECTOR</td>
                <td>{{ ( isset($region->r_coord->ine) ) ? $region->r_coord->ine : '' }}</td>
                <td class="bg-gray">SECCIONES</td>
                <td><b>{{ $region->sections_str }}</b></td>
            </tr>
        </tbody>
    </table>

    @foreach ( $region->zones as $zone )

        <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
            <thead>
            <tr>
                <th colspan="4" class="first last" style="text-align:center;">ZONA {{ $zone->zone }}</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25%" class="bg-gray">SECCIONES</td>
                    <td style="width:25%"><b>{{ $zone->sections_str }}</b></td>
                    <td style="width:25%" class="bg-gray">PROMOVIDOS</td>
                    <td style="width:25%"><b>{{ $zone->prom  }}</b></td>
                </tr>
            </tbody>
        </table>

        <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
            <thead>
            <tr>
                <th colspan="4" class="first last" style="text-align:center;">COORDINADOR DE ZONA</th>
            </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td style="width:40%" class="bg-gray">NOMBRE</td>
                    <td style="width:20%" class="bg-gray">TIPO</td>
                    <td style="width:20%" class="bg-gray">TELÉFONO</td>
                    <td style="width:20%" class="bg-gray">CLAVE DE ELECTOR</td>
                </tr>
                @foreach ( $zone->coords as $i => $z_coord )
                <tr>
                    <td>{{ Person::getFullName($z_coord) }}</td>
                    <td>{{ $z_coord->coord_type }}</td>
                    <td>{{ $z_coord->phone }}</td>
                    <td>{{ $z_coord->ine }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>

        @foreach ( $zone->sections as $section )

        <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
            <thead>
            <tr>
                <th colspan="4" class="first last" style="text-align:center;">SECCION {{ $section->section }}</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25%" class="bg-gray">PROMOVIDOS</td>
                    <td style="width:75%"><b>{{ $section->prom }} / {{ $section->goal }}</b></td>
                </tr>
            </tbody>
        </table>

        <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
            <thead>
            <tr>
                <th colspan="4" class="first last" style="text-align:center;">COORDINADOR SECCIONAL</th>
            </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width:40%" class="bg-gray">NOMBRE</td>
                    <td style="width:20%" class="bg-gray">TIPO</td>
                    <td style="width:20%" class="bg-gray">TELÉFONO</td>
                    <td style="width:20%" class="bg-gray">CLAVE DE ELECTOR</td>
                </tr>
                @foreach ( $section->coords as $j => $s_coord )
                <tr>
                    <td>{{ Person::getFullName($s_coord) }}</td>
                    <td>{{ $s_coord->coord_type }}</td>
                    <td>{{ $s_coord->phone }}</td>
                    <td>{{ $s_coord->ine }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <table width="100%" cellspacing="0" class="people-table" style="margin-bottom:15px;">
            <thead>
            <tr>
                <th colspan="3" class="first last" style="text-align:center;">PROMOTOR</th>
            </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width:40%" class="bg-gray">NOMBRE</td>
                    <td style="width:20%" class="bg-gray">TELÉFONO</td>
                    <td style="width:20%" class="bg-gray">CLAVE DE ELECTOR</td>
                </tr>
                @foreach ( $section->mobs as $j => $mob )
                <tr>
                    <td>{{ Person::getFullName($mob) }}</td>
                    <td>{{ $mob->phone }}</td>
                    <td>{{ $mob->ine }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
 
        @endforeach

      
        



    @endforeach

    
</main>

</body>
