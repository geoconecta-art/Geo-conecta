<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estadísticas - {{ $area_name }} </title>
</head>
<body>
    @include('admin.stats.pdf.direction._styles')
    @include('admin.stats.pdf.direction._header')
    @include('admin.stats.pdf.direction._footer')

    <main style="margin-top: 15px;">
        <div style="margin-bottom: 5px;" >
            @if ( $director )
                Director: <b style="color: #6E5AAF" >{{ $director->name }}</b>
            @endif
        </div>

        @include('admin.stats.pdf.direction._plans_card')
        
        <div class="page-break"></div>

        @if ( !$one_direction )
            @include('admin.stats.pdf.direction._plans_by_area')
            <div class="page-break"></div>
        @endif

        @include('admin.stats.pdf.direction._missing_geo')

    </main>

</body>
</html>