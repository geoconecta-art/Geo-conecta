<header>
    <table width="100%">
        <tbody>
        <tr>
            <td width="100%" style="text-align:center;font-size:1.5rem;" colspan="3">
                <b>PR</b>
            </td>
        </tr>
        <tr>
            <td width="100%" style="text-align:center;" colspan="3">
                {{ $title }}
            </td>
        </tr>
        <tr>
            <td style="text-align:center;font-size:11px;" colspan="3">
                SECCION: <b>{{ $section->section }}</b>
            </td>
        </tr>
        
        @foreach ( $coords as $coord )
        <tr>
            <td style="font-size:11px;">
                <b>COORDINADOR SECCIONAL {{ $coord->coord_type }}:</b> @if ( isset($coord->name) ) {{ Person::getFullName($coord) }} @endif
            </td>
            <td style="width: 10%;"></td>
            @if ($loop->index == 0)
                <td style="font-size:11px;">
                    <b>COORDINADOR REGIONAL {{ $section->region }}:</b>  {{ Person::getFullName($coord_Reg) }}
                </td>
            @endif
        </tr>
        <tr>
            <td style="font-size:11px;">
                <b>TELEFONO:</b> @if ( isset($coord->phone) ) {{ $coord->phone }} @endif
            </td>
            <td style="width: 10%;"></td>
            @if ($loop->index == 0)
            <td style="font-size:11px;">
                <b>TELÉFONO: </b> @if ( isset($coord_Reg->phone) ) {{ $coord_Reg->phone }} @endif
            </td>
            @endif
        </tr>
        @endforeach
       
        </tbody>
    </table>
</header>