<!-- Coordinadores Sección -->
@for ( $l = 0; $l < count($director->reg_coords[$j]->zone_coords[$k]->sec_coords); $l++ )
        
    @if ( $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->name !== $director->reg_coords[$j]->zone_coords[$k]->sec_coords[0]->name )
    <tr>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->section }} 
        </td>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->name }} 
        </td>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->phone }}
        </td>
        <td rowspan="{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->rowspan }}">
            {{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->ine }}
        </td>

        @if ( count($director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs) == 0 )
            <td></td>
            <td></td>
        @else
            <td>{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs[0]->name }}</td>
            <td>{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs[0]->phone }}</td>
        @endif


    </tr>
    @endif

    @for ( $m = 0; $m < count($director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs); $m++ )
        @if ( $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs[$m]->name !== $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs[0]->name )
        <tr>
            <td>{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs[$m]->name }}</td>
            <td>{{ $director->reg_coords[$j]->zone_coords[$k]->sec_coords[$l]->mobs[$m]->phone }}</td>
        </tr>
        @endif
    @endfor

@endfor
