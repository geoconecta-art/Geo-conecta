<a href="{{ route('users.edit', $id) }}" class="btn btn-dim btn-round btn-icon btn-success">
    <em class="icon ni ni-edit"></em>
</a>

@if ($id > 1)
<div onclick="showDeleteModal('{{ $id }}', '{{ $name }}')" class="btn btn-dim btn-round btn-icon btn-danger">
    <em class="icon ni ni-trash"></em>
</div>
@endif