<table class="w-100">
                            
    <tr>
        <td colspan="7">
            <div id="location_img_container" class="text-center border-black">
                <img src="{{ asset('assets/images/localización.jpg') }}" alt="Localización">
            </div>
        </td>

        <td colspan="5">
            <div id="escudo_img_container" class="text-center border-black">
                <img src="{{ asset('assets/images/escudo-atizapan.png') }}" alt="Escudo de Atizapán">
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="12">
            <div class=" text-center border-black" id="area_name">
                {{ $dependency }}
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="12">
            <div class="border-black" id="key_container">
                <div class="text-upercase fs-xxs text-center">
                    Clave de Mapa:
                </div>
                <div class="text-center  fs-small">
                    {{-- {{$mapa->map_key }} --}}
                    {{-- {{ "$area_key-$mapa->system_key" }} --}}
                    {{ $key_map }}
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="12">
            <div class=" fs-small text-center border-black" id="plan_name">
                {{-- {{ $mapa->name }} --}}
                {{ $map_name }}
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="12" class="border-black">
            @include('admin.registros.pdf.map._symbology')
        </td>
    </tr>

    <tr>
        <td colspan="5">
            <div id="cenit_img_container" class="text-center border-black">
                <img src="{{ asset('assets/images/cenit.jpg') }}" alt="CENIT">
            </div>
        </td>

        <td colspan="7">
            <div id="logo_img_container" class="border-black">
                <img src="{{ asset('assets/images/atizapan_logo.jpg')}}" alt="Logo Atizapán">
                {{-- <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo Atizapán"> --}}
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="12">
            <div id="created_by_geo">
                Creado por GEO-CONECTA
            </div>
        </td>
    </tr>

</table>