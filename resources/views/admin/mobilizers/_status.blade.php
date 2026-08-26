<div class="dropdown">
    
    @if ( $status == 1 )
    <a href="#" class="btn btn-round btn-success btn-sm" data-toggle="dropdown">
        <span>Verde</span><em class="icon ni ni-chevron-down"></em>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm mt-1">
        <ul class="link-list-plain">
            <li><div onclick="updateStatus(id, 2)" class="text-warning"><b>Amarillo</b></div></li>
            <li><div onclick="updateStatus(id, 3)" class="text-danger"><b>Rojo</b></div></li>
        </ul>
    </div>
    @elseif ( $status == 2 )
    <a href="#" class="btn btn-round btn-warning btn-sm" data-toggle="dropdown">
        <span>Amarillo</span><em class="icon ni ni-chevron-down"></em>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm mt-1">
        <ul class="link-list-plain">
            <li><div onclick="updateStatus(id, 1)">Verde</div></li>
            <li><div onclick="updateStatus(id, 3)">Rojo</a></li>
        </ul>
    </div>
    @elseif ( $status == 3 )
    <a href="#" class="btn btn-round btn-danger btn-sm" data-toggle="dropdown">
        <span>Rojo</span><em class="icon ni ni-chevron-down"></em>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm mt-1">
        <ul class="link-list-plain">
            <li><a onclick="updateStatus(id, 1)">Verde</a></li>
            <li><a onclick="updateStatus(id, 2)">Amarillo</a></li>
        </ul>
    </div>
    @endif

    
</div>

