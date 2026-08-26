<!-- Coordinadores Zona -->
@for ( $k = 0; $k < count($director->reg_coords[$j]->zone_coords); $k++ )
        
    @if ( $director->reg_coords[$j]->zone_coords[$k]->name !== $director->reg_coords[$j]->zone_coords[0]->name )
    <tr>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->rowspan }}">
            {{ $k + 1 }}
        </td>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->name }} 
        </td>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->phone }}
        </td>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->ine }}
        </td>


        @if ( count($director->reg_coords[$j]->zone_coords[$k]->sec_coords) == 0 )
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        @else
            <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->rowspan }}">
                {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->section }}
            </td>
            <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->rowspan }}">
                {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->name }} 
            </td>
            <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->rowspan }}">
                {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->phone }}
            </td>
            <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->rowspan }}">
                {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->ine }}
            </td>
            <td>
                @if ( isset($director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->mobs[0]) ) {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->mobs[0]->name }} @endif
            </td>
            <td>
                @if ( isset($director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->mobs[0]) ) {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->mobs[0]->phone }} @endif
            </td>
        @endif

    </tr>
    @endif

    @include('admin.reports._sec-coords')
    

@endfor