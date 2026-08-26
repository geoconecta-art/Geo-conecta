<header>
    <table width="100%">
        <tbody>
        <tr>
            <td width="100%" style="text-align:center;font-size:1.5rem;">
                <b>PR</b>
            </td>
        </tr>
        <tr>
            <td width="100%" style="text-align:center;">
                {{ $title }}
            </td>
        </tr>
        <tr>
            <td style="text-align:center;font-size:11px;">
                SECCION: <b>{{ $section->section }}</b>
            </td>
        </tr>
        
        @foreach ( $coords as $coord )
        <tr>
            <td style="font-size:11px;">
                <b>COORDINADOR SECCIONAL {{ $coord->coord_type }}:</b> @if ( isset($coord->name) ) {{ Person::getFullName($coord) }} @endif
            </td>
        </tr>
        <tr>
            <td style="font-size:11px;">
                <b>TELEFONO:</b> @if ( isset($coord->phone) ) {{ $coord->phone }} @endif
            </td>
        </tr>
        @endforeach
       
        </tbody>
    </table>
</header>