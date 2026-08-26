
<a href="{{ route('corporations.edit', $id) }}" class="btn btn-round btn-icon btn-light"><em class="icon ni ni-edit"></em></a>


<div onclick="showDeleteModal('{{ $id }}', '{{ $name }}')" class="btn btn-round btn-icon btn-danger">
    <em class="icon ni ni-trash-alt"></em>
</div>

