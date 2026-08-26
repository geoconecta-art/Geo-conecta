<div class="card-inner pt-1 px-0 h-100">
    <div class="nk-wg-action">
        <div>
            <input type="search" class="form-control form-control-sm my-3" placeholder="Buscar" name="map-search-input" id="map-search-input">
        </div>

        <div class="mb-3" style="height: 65vh; overflow-y: auto;">
            @foreach ($areas as $area)
                <div class="accordion" id="accordion-map-{{ $area->id }}">
                    <div class="accordion-item accordion-map-area" id="{{ $area->id }}">
                        <a href="" class="accordion-head p-1 collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-map-area-{{$area->id}}">
                            <h6 class="title text-truncate" style="width: 90%;">
                                {{ $area->name }}
                            </h6>
                            <span class="accordion-icon"></span>
                        </a>

                        <div class="accordion-body collapse" data-bs-parent="#accordion-map-{{ $area->id }}" id="accordion-map-area-{{$area->id}}">
                            <div class="accordion-inner px-2 py-2 ">

                                @foreach ($area->mapas as $mapa)
                                    <div class="d-flex justify-content-between map-checkbox-container">
                                        <div class="custom-control custom-control-sm custom-checkbox" style="width: 85%;">
                                            <input type="checkbox" class="custom-control-input map-checkbox" id="check-map-{{$mapa->id}}" value="{{ $mapa->id }}">
                                            <label class="custom-control-label w-100" for="check-map-{{$mapa->id}}">
                                                <span class="text-truncate w-100" style="display: block !important;">
                                                    {{ "$area->area_key-$mapa->system_key" }} | {{ $mapa->name }}
                                                </span>
                                            </label>
                                        </div>
                
                                        <div class="dropdown">
                                            <a class="dropdown-toggle btn btn-icon btn-outline-light border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                <em class="icon ni ni-more-h"></em>
                                            </a>
                                        
                                            <div class="dropdown-menu">
                                                <ul class="link-list-opt">
                
                                                    <li>
                                                        <a onclick="showModal('editar', '{{ $mapa->id }}')" class="hand-pointer">
                                                            <span>
                                                                Editar Mapa
                                                            </span>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a onclick="shareMap('{{ $mapa->id }}')" class="hand-pointer">
                                                            <span>
                                                                Compartir Mapa
                                                            </span>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="{{route('map.export', $mapa->id)}}" target="_blank">
                                                            <span>
                                                                Exportar Mapa PDF
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>