<header>
    <table width="100%">
        <tbody>

            <tr>
                <td style="width: 90%;">
                    {{-- <img src="{{ public_path('assets/images/geoconecta-logo.jpg') }}" height="56px"> --}}
                    <img src="{{ public_path('assets/images/Ordenamiento_logo.jpg') }}" alt="Ordenamiento Territorial y Desarrollo Urbano" height="56px">
                    {{-- <img src="{{ public_path('assets/images/geoconecta-logo.jpg') }}" > --}}
                </td>

                <td style="width: 10%; color: #6E5AAF;">
                    <img src="{{ public_path('assets/images/geoconecta-logo.jpg') }}" height="56px">
                    {{-- <table style="border-spacing: 0px;font-size: 16pt; font-weight: bolder;">
                        <tr>
                            <td>
                                GEO
                            </td>
                        </tr>
                        <tr>
                            <td>
                                CONECTA
                            </td>
                        </tr>
                    </table> --}}
                </td>
            </tr>

            <tr style="margin-top: 10px;">
                <td colspan="6" >
                    Dependencia / Organismo: <b>{{ $plan->subarea->area->name }}</b> 
                </td>
            </tr>

            <tr>
                <td colspan="6" >
                    Área: <b>{{ $plan->subarea->name }}</b> 
                </td>
            </tr>
        
        </tbody>
    </table>
</header>