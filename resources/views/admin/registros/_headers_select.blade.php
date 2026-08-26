<div class="col-md-12 p-0">
    
    <label for="" class="form-label col-12 pl-0">
        Coordenadas
    </label>

    <p style="font-size: 9pt;">
        Seleccione los campos que contienen la información de las coordenadas. 
    </p>

    <div class="col-md-12 row justify-content-between flex-wrap pr-0">
        @foreach ($georeferences as $georeference)
            <div class="mb-3 col-md-6 pr-0 {{ ($loop->index % 2) == 1 ? 'pl-4' : 'pl-0' }}">
                <label for="{{ str_replace(" ", "_", $georeference) }}" class="form-label">
                    {{ $georeference }}
                </label>

                <select class="form-control import-coors-select" id="{{ str_replace(' ', '_', $georeference) }}" 
                    name="{{ str_replace(' ', '_', $georeference) . ( $geometry == 'Point' ? '_Inicial' : '' ) }}">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($headers as $head)
                        <option value="{{ $loop->index }}">
                            {{ $head }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="" id="{{ str_replace(' ', '_', $georeference) }}-preview-value" >

                <div class="invalid-feedback" id="{{str_replace(" ", "_", $georeference)}}_error_msg">
                    Por favor seleccione un valor para {{ $georeference }}.
                </div>
            </div>
        @endforeach
    </div>
    
</div>

<script src="{{ asset('assets/js/geoconecta/registros/_headers_select.js') }}"></script>