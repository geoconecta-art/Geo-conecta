<a href="{{ route('peter.edit', $id) }}" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>
<div onclick="showDeleteModal('{{ $id }}', 'al promotor {{ $name }}', destroy, )" 
    class="btn btn-round btn-icon btn-outline-light">
    <em class="icon ni ni-trash-alt"></em>
</div>
<div onclick="showEditLevelModal('{{ $id }}')" class="btn btn-round btn-icon btn-outline-light">
    <em class="icon ni ni-network"></em>
</div>