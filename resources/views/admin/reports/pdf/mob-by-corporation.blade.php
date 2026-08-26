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
        margin-top: 110px;
    }
    </style>

</head>
<body>

    <!--PDF QUE CONTIENE LA INFORMACIÓN DE LOS PROMOTORES DE UNA DETERMINADA CORPORACIÓN-->

    @include('admin.reports.pdf._corp-sec-header')

    @include('admin.reports.pdf._footer')
    
    <main>
        <table width="95%" cellspacing="0" class="people-table" >
            <thead>
                <tr>
                    <th>#</th>
                    <th colspan="2">Nombre</th>
                    <th>Promovidos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $promotores as $promotor )
                <tr>
                    <td>{{$loop->index + 1}}</td>
                    <td colspan="2">{{ $promotor->name}}</td>
                    <td>{{count(Person::getPromotedByCorporationPerson($promotor->id))}}</td>
                </tr>
                @endforeach
                <tr>
                    <td>{{count($promotores) + 1}}</td>
                    <td colspan="2">SIN PROMOTOR ASIGNADO</td>
                    <td>{{count(Person::getPromotedByCorporationPersonNull($corporation->id))}}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td style="text-align:right;">
                        <b>TOTAL POR CORPORACIÓN: </b>
                    </td>
                    <td>
                        <b>{{ count($promotores) }}</b>
                    </td>
                    <td style="text-align:right;">
                        <b>TOTAL PROMOVIDOS </b>
                    </td>
                    <td><b>{{$promotedCount}}</b></td>
                </tr>
            </tfoot>
        </table>
    </main>

</body>
</html>