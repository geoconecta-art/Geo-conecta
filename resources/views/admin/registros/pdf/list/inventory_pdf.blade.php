<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title> Inventario: {{ $plan->name }} </title>
</head>

<body>

    @include('admin.registros.pdf.list._styles')
    @include('admin.registros.pdf.list._inventory_header')
    @include('admin.registros.pdf.list._inventory_footer')

<main>
    <div style="margin-bottom:8px;">
        <b style="font-size:11px; color: #6E5AAF"> {{ $plan->name }} </b>
    </div>

    <table class="nowrap table people-table" id="mobTable" width="95%" cellspacing="0">
        <thead>
            <tr style="background-color: #6E5AAF; color: white;">
                <th>#</th>

                @foreach ($headers as $key => $head)
                    <th>
                        {{ $head }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>

            @foreach ($rows as $row)
                <tr>
                    <td> {{ $loop->index + 1 }} </td>

                    @foreach ($headers as $key => $head)
                        @if ( array_key_exists($key, $row->attributes) )
                            <td>{{$row->attributes[ $key ]}}</td>

                        @elseif ( array_key_exists($key, $row->georeference['_Inicial']) )
                            <td>{{$row->georeference['_Inicial'][$key]}}</td>

                        @elseif ( isset( $row->address ) )
                            @if ( array_key_exists($key, $row->address) )
                                <td>{{$row->address[$key]}}</td>
                            @endif

                        @elseif ( isset( $row->georeference['_Final'] ) )
                            @if ( array_key_exists($key, $row->georeference['_Final']) )
                                <td>{{$row->georeference['_Final'][$key]}}</td>
                            @endif

                        @endif

                    @endforeach
                </tr>
            @endforeach

        </tbody>
    </table>
</main>

</body>
