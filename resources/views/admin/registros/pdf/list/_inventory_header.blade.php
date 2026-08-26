<header>
    <table width="95%">
        <tbody>

            <tr>
                <td style="width: 20%;">
                    {{-- <img src="{{ public_path('assets/images/geoconecta-logo.jpg') }}" height="56px"> --}}
                    <img src="{{ public_path('assets/images/Ordenamiento_logo.jpg') }}" alt="Ordenamiento Territorial y Desarrollo Urbano" height="56px">
                    {{-- <img src="{{ public_path('assets/images/logoTerritorio.png') }}" alt="Ordenamiento Territorial y Desarrollo Urbano" height="56px"> --}}
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

                <td style="width: 60%; text-align: right;">
                    <p style="color: #6E5AAF" class="title">
                        <b>INVENTARIO:</b> {{ $plan->name }}
                    </p>
                </td>
            </tr>

            <tr>
                
                <td colspan="3" >
                    <p class="subtitle">
                        Dependencia / Organismo: <b>{{ $plan->subarea->area->name }}</b> 
                        &nbsp; &nbsp; &nbsp;
                        Área: <b>{{ $plan->subarea->name }}</b> 
                    </p>
                </td>

            </tr>
        
        </tbody>
    </table>
</header>