<div class="card-inner pt-1 px-0 h-100">
    <div class="nk-wg-action">
        <div>
            <input type="search" class="form-control form-control-sm my-3" placeholder="Buscar" 
            aria-controls="mobTable" name="plan-search-input" id="plan-search-input">
        </div>

        <div class="mb-3" style="height: 60vh; overflow-y: auto;">
            @foreach ($areas as $area)
                <div class="accordion" id="accordion-plan-{{ $area->name }}" >

                    <div class="accordion-item accordion-plan-area" id="{{ $area->id }}">
                        <a href="#" class="accordion-head p-1 collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-plan-area-{{$area->id}}">
                            <h6 class="title text-truncate" style="width: 90%;">
                                {{ $area->name }}
                            </h6>
                            <span class="accordion-icon"></span>
                        </a>

                        <div class="accordion-body collapse" id="accordion-plan-area-{{$area->id}}" data-bs-parent="#accordion-plan-{{ $area->name }}">
                            <div class="accordion-inner p-0 pl-2">
                
                                @foreach ($area->subareas as $subarea)
                                    <div class="accordion" id="accordion-plan-{{ $subarea->name }}">

                                        <div class="accordion-item accordion-plan-subarea" id="{{ $subarea->id }}">
                                            <a href="#" class="accordion-head p-2 collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-plan-subarea-{{$subarea->id}}">
                                                <h6 class="title text-truncate" style="width: 90%;">
                                                    {{ $subarea->name }}
                                                </h6>
                                                <span class="accordion-icon"></span>
                                            </a>

                                            <div class="accordion-body collapse" id="accordion-plan-subarea-{{$subarea->id}}" data-bs-parent="#accordion-plan-{{ $subarea->name }}">
                                                <div class="accordion-inner p-0 pt-2 pl-4 pr-3">

                                                    @foreach ($subarea->planeaciones as $plan)
                                                        <div class="d-flex justify-content-between plan-checkbox-container">
                                                            <div class="d-flex align-items-center p-0 checkbox-row w-75">

                                                                @if ( $plan->geometry == 'Point' )
                                                                    <em class="icon ni ni-bullet-fill" style="color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};"></em>
                                                                @elseif ( $plan->geometry == 'LineString' )
                                                                    <em class="icon ni ni-activity" style="color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};"></em>
                                                                @endif
                                                                &nbsp;&nbsp;
                                                                <div class="custom-control custom-control-sm custom-checkbox w-100">

                                                                    <input type="checkbox" class="custom-control-input plan-checkbox" id="check-plan-{{$plan->id}}" value="{{ $plan->id }}"
                                                                        geometry="{{ isset($plan->geometry) ? $plan->geometry : 'Point' }}">
                                                                    <label class="custom-control-label w-100" for="check-plan-{{$plan->id}}">
                                                                        <span class="text-truncate w-100" style="display: block !important;">
                                                                            {{ $plan->name }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle btn btn-icon btn-outline-light border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <em class="icon ni ni-more-h"></em>
                                                                </a>
                                                            
                                                                <div class="dropdown-menu">
                                                                    <ul class="link-list-opt">
                                                                        <li>
                                                                            <a href="{{route('register.inventory.export-excel', $plan->id)}}">
                                                                                <span>
                                                                                    Exportar Excel
                                                                                </span>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a class="hand-pointer" onclick="openExportPdfFileModal('{{$plan->id}}')">
                                                                                <span>
                                                                                    Exportar Listado PDF
                                                                                </span>
                                                                            </a>
                                                                        </li>

                                                                        {{-- <li>
                                                                            <a href="{{route('register.inventory.export-map-pdf', $plan->id)}}" target="_blank">
                                                                                <span>
                                                                                    Exportar Mapa PDF
                                                                                </span>
                                                                            </a>
                                                                        </li> --}}

                                                                        <li>
                                                                            <a href="{{ route('register.inventory.export-kml', $plan->id) }}">
                                                                                <span>
                                                                                    Exportar KML
                                                                                </span>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="{{ route('register.inventory.export-gejson', $plan->id) }}">
                                                                                <span>
                                                                                    Exportar GeoJSON
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
                </div>

            @endforeach
        </div>

        <div class="w-100 d-flex justify-content-end">
            <button href="" class="btn btn-dim btn-success" onclick="showModal('crear')">
                Generar Mapa
            </button>
        </div>

    </div>
</div><!-- .card-inner -->