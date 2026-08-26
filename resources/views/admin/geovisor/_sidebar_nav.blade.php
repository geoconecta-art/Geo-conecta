<style>
    .nk-sidebar .nk-menu-link {
        font-size: 14px;
    }
</style>
<ul class="nk-menu border-end">
    {{-- INVENTARIOS --}}
    @can('inventarios')
    <li class="nk-menu-item has-sub">
        <a href="#" class="nk-menu-link nk-menu-toggle" title="Inventarios">
            <span class="nk-menu-icon">
                <em class="icon ni ni-folders"></em>
            </span>
        </a>

        <ul class="nk-menu-sub m-0">
            {{-- CREAR INVENTARIOS --}}
            <li class="nk-menu-item my-1">
                <a href="#" class="nk-menu-link nk-menu-toggle" title="Crear Inventario">
                    <span class="nk-menu-icon">
                        <em class="icon ni ni-folder-plus"></em>
                    </span>
                </a>

                <ul class="nk-menu-sub ms-4">
                    {{-- NUEVO INVENTARIO --}}
                    <li class="nk-menu-item my-1">
                        <a href="#" onclick="createInventoryOpenModal(event, '{{ route('plans.modal') }}')" class="nk-menu-link pe-0" title="Nuevo Inventario">
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-file"></em>
                            </span>
                        </a>
                    </li>

                    {{-- INVENTARIO DE TABLA CSV --}}
                    <li class="nk-menu-item my-1">
                        <a href="#" onclick="createInventoryOpenModal(event, '{{ route('plans.modal-csv') }}')" class="nk-menu-link pe-0" title="Nuevo Inventario de Tabla CSV">
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-file-xls"></em>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Registros de Inventarios --}}
            <li class="nk-menu-item p-0">
                <a href="{{route('plans.index')}}" class="nk-menu-link pe-0" title="Registros de Inventarios">
                    <span class="nk-menu-icon">
                        <em class="icon ni ni-files"></em>
                    </span>
                </a>
            </li>

        </ul>
    </li>
    @endcan

    <li class="nk-menu-hr"></li>

    @can('usuarios')
    <li class="nk-menu-item">
        <a href="{{ route('users.index') }}" class="nk-menu-link"
            aria-label="CMS Panel" data-bs-original-title="CMS Panel"
            title="Usuarios">
            <span class="nk-menu-icon">
                <em class="icon ni ni-account-setting-alt"></em>
            </span>
        </a>
    </li>
    @endcan

    @can('estadisticas')
    <li class="nk-menu-item">
        <a href="{{ route('stats.index') }}" class="nk-menu-link"
            aria-label="CMS Panel" data-bs-original-title="CMS Panel"
            title="Estadísticas">
            <span class="nk-menu-icon">
                <em class="icon ni ni-bar-chart-alt"></em>
            </span>
        </a>
    </li>
    @endcan

    <li class="nk-menu-hr"></li>

    @can('mapa')
    <li class="nk-menu-item">
        <a href="{{ route('geovisor.index') }}" class="nk-menu-link"
            aria-label="CMS Panel" data-bs-original-title="CMS Panel"
            title="Geovisor">
            <span class="nk-menu-icon">
                <em class="icon ni ni-map"></em>
            </span>
        </a>
    </li>
    @endcan

    @can('mapa')
    <li class="nk-menu-item">
        <a href="{{route('map.index')}}" class="nk-menu-link">
            <span class="nk-menu-icon">
                <em class="icon ni ni-location"></em>
            </span>
        </a>
    </li>
    @endcan

</ul>