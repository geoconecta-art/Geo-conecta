<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/img/mono.png">

    <title> Inventario: {{ $plan->name }} </title>
</head>

<body>

    @include('admin.registros.pdf.details._styles')
    @include('admin.registros.pdf.details._header')

    <main>
        <div style="margin-bottom:20px;">
            <b style="font-size:14px; color: #6E5AAF"> {{ $plan->name }} </b>
        </div>

        @foreach ($rows as $row)
            <table class="nowrap table plan-table" style="width: 100%;" cellspacing="0">
                <tbody>
                    @foreach ($headers as $key => $head)
                        <tr>
                            @if ( array_key_exists($key, $row->attributes) )
                                <td style="width: 30%;"> <b>{{ $head }}</b> </td>
                                <td>{{$row->attributes[ $key ]}}</td>
                            @endif

                            @if ( array_key_exists($key, $row->georeference['_Inicial']) )
                                <td style="width: 30%;"> <b>{{ $head }}</b> </td>
                                <td>{{$row->georeference['_Inicial'][$key]}}</td>
                            @endif

                            @if ( isset( $row->address ) )
                                @if ( array_key_exists($key, $row->address) )
                                    <td style="width: 30%;"> <b>{{ $head }}</b> </td>
                                    <td>{{$row->address[$key]}}</td>
                                @endif
                            @endif

                            @if ( isset( $row->georeference['_Final'] ) )
                                @if ( array_key_exists($key, $row->georeference['_Final']) )
                                    <td style="width: 30%;"> <b>{{ $head }}</b> </td>
                                    <td>{{$row->georeference['_Final'][$key]}}</td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if ($loop->index < count($rows) - 1)
                <div class="page-break"></div>
            @endif
        @endforeach
        
    </main>

</body>
