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
                CORPORACIÓN: <b>{{ $corporation->name }}</b>
            </td>
        </tr>
        <tr>
            <td style="font-size:11px;">
                <b>REPRESENTANTE:</b> {{$corporation->manager}}
            </td>
        </tr>
        </tbody>
    </table>
</header>