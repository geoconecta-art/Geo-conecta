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

<main style="margin-top:15px;">
    <div style="margin-bottom:5px;">
        <b style="font-size:11px; color: #6E5AAF"> {{ $plan->name }} </b>
    </div>

    <table class="nowrap table people-table" id="mobTable" width="95%" cellspacing="0">
        <thead>
            <tr style="background-color: #6E5AAF; color: white;">
                <th>#</th>

                @foreach ($plan->attributes as $item)
                    @if ( is_array( $item ) )
                        @if ( $item['type'] != 'file' && !isset($item['deleted_at']) )
                            <th> {{ $item['title'] }} </th>
                        @endif
                    @else
                        @php
                            $matches = explode("_", $item);
                            $geoItems = [];

                            if( count( $matches ) == 2 ){
                                $key = $matches[0];
                                $index = '_'.$matches[1];
                                $geoItems = $plan->$key[$index];
                            } else {
                                $geoItems = $plan->$item;
                            }
                        @endphp

                        @foreach ($geoItems as $geoItem)
                            @if ( is_array( $geoItem ) )
                                @if ( $geoItem['type'] != 'button' )
                                    <th> {{ $geoItem['title'] }} {{ $matches[1] ?? '' }} </th>
                                @endif
                            @endif
                        @endforeach

                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>

            @foreach ($rows as $row)
                <tr>
                    <td> {{ $loop->index + 1 }} </td>
                    @foreach ($keys as $k => $attrKey)
                        @if( is_array( $attrKey ) )
                            @php
                                $matches = explode("_", $k);
                                $geoValues = [];

                                if( count( $matches ) == 2 ){
                                    $key = $matches[0];
                                    $index = '_'.$matches[1];
                                    $geoValues = $row->$key[$index];
                                } else {
                                    $geoValues = $row->$k;
                                }
                            @endphp

                            @foreach ($geoValues as $value)
                                <td>{{ $value ?? ''}}</td>
                            @endforeach
                        @else
                            @if ( str_contains( strtolower($attrKey), "image" ) )
                                <td>
                                    @if ( $row->attributes[$attrKey] )
                                        <img src="{{ public_path($row->attributes[$attrKey]) }}" alt="{{ $attrKey }}" width="100px">
                                    @endif
                                </td>
                            @elseif ( str_contains( strtolower($attrKey), "file" ) )
                                {{-- <td>
                                    @if ( $row->attributes[$attrKey] )
                                        <a href="{{ asset($row->attributes[$attrKey]) }}" target="_blank">
                                            Descargar Archivo
                                        </a>
                                    @endif
                                </td> --}}
                            @else

                                <td> {{ $row->attributes[ $attrKey ] ?? '' }} </td>
                            @endif
                            
                        @endif

                    @endforeach
                </tr>
            @endforeach

        </tbody>
    </table>
</main>

</body>
