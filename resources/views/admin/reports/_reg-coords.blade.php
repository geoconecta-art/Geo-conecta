<!-- Coordinadores Región -->
@for ( $j = 0; $j < count($director->reg_coords); $j++ )
                    
    @if ( $director->reg_coords[$j]->name !== $first_reg_coord )
    <tr>
        <td @if ($director->reg_coords[$j]->rowspan > 0) rowspan="{{ $director->reg_coords[$j]->rowspan }}" @endif >
            {{ $director->reg_coords[$j]->name }}
        </td>
        <td @if ($director->reg_coords[$j]->rowspan > 0) rowspan="{{ $director->reg_coords[$j]->rowspan }}" @endif >
            {{ $director->reg_coords[$j]->phone }}
        </td>
        <td @if ($director->reg_coords[$j]->rowspan > 0) rowspan="{{ $director->reg_coords[$j]->rowspan }}" @endif >
            {{ $director->reg_coords[$j]->ine }}
        </td>

        
        @if ( count ($director->reg_coords[$j]->zone_coords) == 0 )

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="last"></td>

        @else
    
        <td>1</td>
        <td>{{ $director->reg_coords[$j]->zone_coords[0]->name }}</td>
        <td>{{ $director->reg_coords[$j]->zone_coords[0]->phone }}</td>
        <td>{{ $director->reg_coords[$j]->zone_coords[0]->ine }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="last"></td>

        @endif

    </tr>
    @endif

    @include('admin.reports._zone-coords')
    

@endfor