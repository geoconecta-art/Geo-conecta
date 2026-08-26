<ul class="d-flex flex-wrap p-0 w-100" id="form-container">
    @php $is_there_lote = false; @endphp
    @if ($plan->attributes)
        @foreach ($plan->attributes as $key => $item)
            @if ( !is_array($item) )
            
                <li class="col-12 d-flex flex-wrap p-0 input_draggable " id="{{ $key }}" draggable="{{ $edit }}">

                    @php
                        $matches = explode('_', $item);
                        $geoItems = [];

                        if( count($matches) > 0 && count($matches) == 2 ){
                            $key = $matches[0];
                            $index = '_' . $matches[1];
                            $geoItems = $plan->$key[$index];
                        } else {
                            $geoItems = $plan->$item;
                        }
                    @endphp

                    @foreach ($geoItems as $geoKey => $geoItem)
                        @if ( is_array($geoItem) )
                            @if ($geoItem['type'] != 'button')
                                <div class="col-12 col-md-{{ $geoItem['size'] }} my-2 form_geo_item"
                                    elementsize="{{ $geoItem['size'] }}">
                                    <div class="">

                                        <label class="form-label"
                                            for="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_' . 'attr' }}"
                                            id="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_attr_label' }}"
                                        >
                                            {{ $geoItem['title'] != 'No. Int' ? $geoItem['title'] . ' ' . ( $plan->geometry == 'LineString' ? ( $matches[1] ?? '' ) : '' ) . '' : $geoItem['title'] }}
                                        </label>

                                        @if ($geoItem['type'] == 'select')
                                            <select class="form-control"
                                                id="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_' . 'attr' }}"
                                                name="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_' . 'attr' }}"
                                                {{ $geoItem['title'] == "Colonia" ? "disabled" : ""}}>
                                                <option value="" selected>
                                                    Seleccione una opción
                                                </option>

                                                @if ( $geoItem['title'] == "C.P." )
                                                    @foreach ($cps as $cp)
                                                        <option value="{{ $cp }}">
                                                            {{ $cp }}
                                                        </option>
                                                    @endforeach
                                                @elseif( $geoItem['title'] == "Colonia" )
                                                    @foreach ($suburbs as $suburb)
                                                        <option value="{{ $suburb->name }}" class="{{ $suburb->cp }} suburb-option">
                                                            {{ $suburb->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                        @else
                                            <input 
                                                type="{{ $geoItem['type'] }}"
                                                class="form-control "
                                                id="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_' . 'attr' }}"
                                                name="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_' . 'attr' }}"
                                                min="0"
                                                value="{{ in_array($geoItem['type'], ['checkbox', 'radio']) ? $geoItem['title'] : ''}}"
                                                {{ $geoItem['title'] != 'No. Int' ? '' : '' }}
                                                {{ $geoItem['title'] == 'Longitud' || $geoItem['title'] == 'Latitud' ? 'disabled' : '' }} 
                                                />
                                                <div class="invalid-feedback col-12" id="{{ str_replace(' ', '', str_replace(".","_", $geoItem['title'])) . '_' . $geoItem['type'] . '_' . $geoKey . '_' . $item . '_error_msg'}}"></div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="col-12 col-md-{{ $geoItem['size'] }} mb-3 form_geo_item"
                                    elementsize="{{ $geoItem['size'] }}">
                                    <div class="btn btn-light btn-form col-12 justify-content-center mt-4 mt-md-5"
                                        onclick="initializeMarkersToEdit('{{$matches[1] ?? ''}}')">
                                        <span> {{ $geoItem['title'] }} {{ $matches[1] ?? '' }} </span>
                                        <em class="icon ni ni-location"></em>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach

                    @if ( $item == "catastral" )
                        <div class="col-12 col-md-6 form_geo_item pt-0" elementsize="3">
                            <div class="btn btn-light btn-form col-12 justify-content-center mt-4 mt-md-5" onclick="valitadateCatastralKey();"> 
                                <span> Validar Clave Catastral </span>
                                <em class="icon ni ni-location"></em>
                            </div>
                        </div>
                    @endif
                </li>

            @elseif(!array_key_exists('deleted_at', $item))
                <li class="col-12 col-md-{{ $item['size'] }} py-1 form_dynamic_item input_draggable" id="{{ $key }}"
                    elementsize="{{ $item['size'] }}" draggable="{{ $edit }}">

                    <div class="mb-3">

                        <div class="d-flex justify-between">
                            <label class="form-label"
                                {{-- for="{{ str_replace(' ', '', str_replace(".", "_", $item['title']) ) . '_' . $item['type'] . '_' . $key . '_attr' }}" --}}
                                for="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}"
                                id="{{ str_replace(' ', '', str_replace(".", "_", $item['title']) ) . '_' . $item['type'] . '_' . $key . '_gen_attr_label' }}"
                            >
                                {{ $item['title'] }}
                            </label>
                        </div>

                        @if ($item['type'] == 'textarea')
                            <textarea class="form-control"
                                id="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}"
                                name="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}"></textarea>
                        @elseif($item['type'] == 'radio' || $item['type'] == 'checkbox')
                            <div
                                id="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}">
                                @foreach ($item['options'] as $option)
                                    <div class="form-check">
                                        <div class="custom-control custom-control-sm custom-{{ $item['type'] }}">
                                            <input type="{{ $item['type'] }}" class="custom-control-input"
                                                id="{{ $item['title'] . '_' . $item['type'] . '_' . $option . $key . '_attr' }}"
                                                name="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' . ($item['type'] == 'checkbox' ? '[]' : '' )}}" 
                                                value="{{ $option }}"
                                                />
                                            <label class="custom-control-label"
                                                for="{{ $item['title'] . '_' . $item['type'] . '_' . $option . $key . '_attr' }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($item['type'] == 'select')
                            <select class="form-control"
                                id="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}"
                                name="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}">
                                <option value="" selected>Seleccione una opción</option>

                                @foreach ($item['options'] as $option)
                                    <option value="{{ $option }}">
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input 
                                type="{{ $item['type'] == 'image' ? 'file' : $item['type']}}" 
                                class="form-control {{ $item['type'] == 'file' || $item['type'] == 'image' ? 'dropify' : ''}}"
                                accept=" {{$item['type_file'] ?? '' }} "
                                id="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}"
                                name="{{ str_replace(' ', '', str_replace(".","_", $item['title'])) . '_' . $item['type'] . '_' . $key . '_gen_attr' }}"
                                >
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    @endif
</ul>
