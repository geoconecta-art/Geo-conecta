<style>
    .accordion-head-area{
        background: #4D4383;
    }

    .accordion-head-subarea{
        background: #5d5780;
    }
</style>

<div class="mx-2" style="height: calc(100vh - 224px);overflow-y: auto;">
    @foreach ($areas as $area)
        @foreach ($area->subareas as $subarea)
            <div class="accordion mb-2" id="accordion-plan-{{ $subarea->id }}">

                <div class="accordion-item border-0 accordion-plan-subarea" id="{{ $subarea->id }}">
                    <button href="#" class="d-flex align-item-center accordion-head accordion-head-subarea p-2 collapsed rounded-3 border-0 w-100" data-bs-toggle="collapse" data-bs-target="#accordion-plan-subarea-{{$subarea->id}}">
                        <h6 class=" my-0 text-truncate text-start text-white" style="width: 80%; font-size: x-small;">
                            {{ $subarea->name }}
                        </h6>
                        <span class="text-white accordion-icon"></span>
                    </button>

                    <div class="accordion-body collapse" id="accordion-plan-subarea-{{$subarea->id}}" data-bs-parent="#accordion-plan-{{ $subarea->id }}">
                        <div class="accordion-inner p-0">

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
                                                <span class="text-truncate w-100" style="display: block !important;" id="check-plan-{{ $plan->id }}-name">
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

    @endforeach
</div>