

<div onclick="edit('{{ $id }}')" class="btn btn-round btn-icon btn-outline-light">
    <em class="icon ni ni-edit"></em>
</div>

<div onclick="showDeleteModal('{{ $id }}', ' {{ $votes }} votos de la sección {{ $section }}', destroy, )" 
    class="btn btn-round btn-icon btn-outline-light">
    <em class="icon ni ni-trash-alt"></em>
</div>

