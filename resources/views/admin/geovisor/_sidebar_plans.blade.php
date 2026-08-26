<div class="simplebar-content" style="width: calc(100% - 74px) !important;" >
                            
    <div class="nk-menu-content menu-active text-white p-2" data-content="Inventarios" style="background: #4D4383 !important; margin-top: -12px !important; height: 66px;">
        <h5 class="title my-3 mx-2">
            Inventarios
        </h5>
        <hr class="my-2 nk-menu-hr" />
    </div>

    <div class="d-flex justify-content-between p-2" id="buttons-container">
        <button class="ml-0 pl-0 btn btn-dim btn-primary" style="font-size: smaller;" onclick="cleanMap();">
            Limpiar Capas
        </button>

        <button class="ml-0 pl-0 btn btn-dim btn-primary" style="font-size: x-small;" onclick="fitAtizapanBounds(map)">
            <em class="icon ni ni-zoom-in"></em>
            Autozoom
        </button>
    </div>

    @if ( ! str_contains( url()->current(), "generar-mapa" ) )
        <div class="px-2 col-12">
            <button href="{{ route('geovisor.make-index') }}" class="w-100 btn btn-dim btn-success d-flex justify-content-center" id="generate_map_btn">
                <span>
                    Generar Mapa
                </span>
            </button>
        </div>
    @endif

    <div class="m-2">
        <input type="search" class="form-control form-control-sm my-3" placeholder="Buscar" 
        aria-controls="mobTable" name="plan-search-input" id="plan-search-input">
    </div>

    @if ( Auth::user()->hasRole('Super Administrador') )
    @include('admin.geovisor._inventories_admin')
    @else
        @include('admin.geovisor._inventories')
    @endif
    
</div>