<style>
    .nk-sidebar .nk-menu-link {
        font-size: 14px;
    }
</style>
<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">

 
    <div class="nk-sidebar-element">

       

        <div class="nk-sidebar-content">

            <div class="text-center mb-3 pt-3">
                {{-- <img src="{{asset('assets/images/Ordenamiento_logo.jpg')}}" alt="logo" style="width:160px;"> --}}
                <img src="{{asset('assets/images/geoconecta-logo.jpg')}}" alt="logo" style="width:130px;">
                <hr class="my-2 nk-menu-hr" />
            </div>
            
            <div class="nk-sidebar-menu" data-simplebar >
                
                <ul class="nk-menu">

                    @can('inventarios')
                    <li class="nk-menu-item has-menu">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">
                                <em class="icon ni ni-folders"></em>
                                INVENTARIOS
                            </span>
                        </a>
                        <ul class="nk-menu-sub">
                            
                            <li class="nk-menu-item has-menu">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">
                                        <em class="icon ni ni-folder-plus"></em>
                                        Crear Inventario
                                    </span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="#" onclick="createInventoryOpenModal(event, '{{ route('plans.modal') }}')" class="nk-menu-link">
                                            <span class="nk-menu-text">
                                                <em class="icon ni ni-file"></em>
                                                Nuevo Inventario
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nk-menu-item">
                                        <a href="#" onclick="createInventoryOpenModal(event, '{{ route('plans.modal-csv') }}')" class="nk-menu-link">
                                            <span class="nk-menu-text">
                                                <em class="icon ni ni-file-xls"></em>
                                                Nuevo Inventario de Tabla CSV
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nk-menu-item">
                                <a href="{{route('plans.index')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">
                                        <em class="icon ni ni-files"></em>
                                        Registro de Inventarios
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endcan

                    @can('usuarios')
                    <li class="nk-menu-item">
                        <a href="{{route('users.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">
                                <em class="icon ni ni-account-setting-alt"></em>
                                USUARIOS
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('estadisticas')
                    <li class="nk-menu-item">
                        <a href="{{route('stats.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">
                                <em class="icon ni ni-bar-chart-alt"></em>
                                ESTADÍSTICAS
                            </span>
                        </a>
                    </li>
                    @endcan

                    <li class="nk-menu-hr"></li>

                    @can('mapa')
                    <li class="nk-menu-item">
                        <a href="{{route('geovisor.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">
                                <em class="icon ni ni-map"></em>
                                GEOVISOR
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('mapa')
                    <li class="nk-menu-item">
                        <a href="{{route('map.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">
                                <em class="icon ni ni-location"></em>
                                CATASTRO
                            </span>
                        </a>
                    </li>
                    @endcan

                    {{-- @can('colonias')
                    <li class="nk-menu-item">
                        <a href="{{route('suburb.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">Colonias</span>
                        </a>
                    </li>
                    @endcan

                    @can('areas')
                    <li class="nk-menu-item">
                        <a href="{{route('directions.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">Direcciones</span>
                        </a>
                    </li>
                    @endcan

                    @can('subareas')
                    <li class="nk-menu-item">
                        <a href="{{route('subdirections.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">Subdirecciones / Áreas</span>
                        </a>
                    </li>
                    @endcan

                    <hr>

                    @can('registros')
                    <li class="nk-menu-item">
                        <a href="{{route('register.index')}}" class="nk-menu-link">
                            <span class="nk-menu-text">Datos</span>
                        </a>
                    </li>
                    @endcan --}}
                    
                </ul>

            </div>
        </div>
    </div>
</div>