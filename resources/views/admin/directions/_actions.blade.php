<button class="btn btn-dim btn-success rounded-circle p-1" onclick="showModal('{{ route('directions.edit-modal', $id) }}')">
    <em class="icon ni ni-edit"></em>
</button>
<button class="btn btn-dim btn-danger rounded-circle p-1" onclick="confirmDelete('{{$id}}')">
    <em class="icon ni ni-trash"></em>
</button>