
@if ( Auth::user()->hasRole('Consultor') )

    @switch( $type )
        @case( 1 )

            <a href="{{ route('reports.director', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
        
            @break
            
        @case( 3 )
            <a href="{{ route('reports.r-coordinator', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            @break

        @case( 4 )
            <a href="{{ route('reports.z-coordinator', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            @break

        @case( 5 )
            <a href="{{ route('reports.s-coordinator', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            @break

        @case( 6 )
            <a href="{{ route('reports.mobilizer', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            @break

        @default
        
    @endswitch
@else 

    @switch( $type )
        @case( 1 )

            <a href="{{ route('reports.director', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            <a href="{{ route('directors.edit', $id) }}" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>
            <!--<div onclick="showDeleteModal('{ $id }}', '{ $name }}')" class="btn btn-round btn-icon btn-outline-light">
                <em class="icon ni ni-trash-alt"></em>
            </div>-->
            @break
            
        @case( 3 )
            <a href="{{ route('reports.r-coordinator', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            <a href="{{ route('r-coordinators.edit', $id) }}" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>
            @break

        @case( 4 )
            <a href="{{ route('reports.z-coordinator', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            <a href="{{ route('z-coordinators.edit', $id) }}" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>
            @break

        @case( 5 )
            <a href="{{ route('reports.s-coordinator', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            <a href="{{ route('s-coordinators.edit', $id) }}" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>
            @break

        @case( 6 )
            <a href="{{ route('reports.mobilizer', $id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-printer"></em></a>
            <a href="{{ route('mobilizers.edit', $id) }}" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>
            <div onclick="showDeleteModal('{{ $id }}', 'al promotor {{ $name }}', destroy, )" class="btn btn-round btn-icon btn-outline-light">
                <em class="icon ni ni-trash-alt"></em>
            </div>

            
            @break

        @default
        
    @endswitch

    <div onclick="showEditLevelModal('{{ $id }}')" class="btn btn-round btn-icon btn-outline-light">
        <em class="icon ni ni-network"></em>
    </div>
@endif


