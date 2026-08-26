<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">

    <div class="nk-sidebar-element">

        <div class="nk-sidebar-content">

            <div class="text-center mb-3 pt-3">
                <img src="{{asset('assets/images/Ordenamiento_logo.jpg')}}" alt="logo" style="width:160px;">
            </div>

            <hr class="w-100">

            {{-- Listar los inventarios asociados al mapa --}}
            <div class="nk-sidebar-menu" data-simplebar style="overflow:auto;">

                <ul class="nk-menu">
                    @foreach ($plans as $plan)
                        <li class="accordion nk-menu-item" id="accordion-plan-{{$plan->id}}">
                                    
                            <div class="accordion-item accordion-plan-map" id="{{ $plan->id }}">
                                <a href="#" class="accordion-head p-1 collapsed" data-bs-toggle="collapse" data-bs-target="#accordion-plan-map-{{$plan->id}}">
                                    @if ( $plan->geometry == 'Point' )
                                        <em class="icon ni ni-bullet-fill" style="color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};"></em>
                                    @elseif ( $plan->geometry == 'LineString' )
                                        <em class="icon ni ni-activity" style="color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};"></em>
                                    @endif
                                    &nbsp;&nbsp;
                                    
                                    <h6 class="title text-truncate" style="width: 80%;">
                                        {{ $plan->name }}
                                    </h6>
                                    <span class="accordion-icon"></span>
                                </a>
                        
                                <div class="accordion-body collapse" id="accordion-plan-map-{{$plan->id}}" data-bs-parent="#accordion-plan-{{$plan->id}}">
                                    <ul class="accordion-inner p-2">
                                        <li class="custom-control custom-control-sm custom-checkbox w-100">
                                            <input type="checkbox" id="check-all-{{ $plan->id }}" class="custom-control-input public-map-all-check" id_plan="{{ $plan->id }}" checked>
                                            <label for="check-all-{{ $plan->id }}" class="custom-control-label w-100">
                                                <span class="text-truncate w-100">
                                                    Todas
                                                </span>
                                            </label>
                                        </li>
                                        @foreach ($plan->registros as $row)
                                            <li class="custom-control custom-control-sm custom-checkbox w-100">
                                                <input type="checkbox" id="check-{{$row->id}}" class="custom-control-input public-map-check" value="{{ $row->id }}" 
                                                    geometry="{{ $plan->geometry }}" id_plan="{{ $plan->id }}" checked>
                                                <label for="check-{{$row->id}}" class="custom-control-label w-100 mb-1">
                                                    <span class="w-100" id="label-row-{{$row->id}}" {{-- style="display: block !important;" --}}>
                                                        {{ ($loop->index + 1) }}
                                                    </span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>