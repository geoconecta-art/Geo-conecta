<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title>{{$title}}</title>
    @include('admin.reports.pdf._style')

    <style>
        body {
        margin-top: 90px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>

</head>
<body>
    <!--PDF QUE CONTIENE LA INFORMACIÓN DE TODOS LOS PROMOTORES, DIVIDIDOS POR CORPORACIÓN-->

    @include('admin.reports.pdf._all-corps-header')

    @include('admin.reports.pdf._footer')
    
    <main>
        @foreach ($corporations as $corp)
            @include('admin.reports.pdf._all-corps-sec-header')

            <table width="95%" cellspacing="0" class="people-table" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th colspan="2">Nombre</th>
                        <th>Promovidos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $corp->mobs as $mob )
                    <tr>
                        <td >{{ $loop->index + 1 }}</td>
                        <td colspan="2">{{ $mob->name }}</td>
                        <td>{{ count(Person::getPromotedByCorporationPerson($mob->id))}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>{{count($corp->mobs) + 1}}</td>
                        <td colspan="2">SIN PROMOTOR ASIGNADO</td>
                        <td>{{count(Person::getPromotedByCorporationPersonNull($corp->id))}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <td style="text-align: right"><b>TOTAL PROMOTORES</b></td>
                    <td><b>{{count($corp->mobs)}}</b></td>
                    <td style="text-align: right"><b>TOTAL PROMOVIDOS</b></td>
                    <td><b>{{count(Person::getPromotedByCorporation($corp->id))}}</b></td>
                </tfoot>
            </table>
            <div class="page-break"></div>
        @endforeach
    </main>

</body>
</html>