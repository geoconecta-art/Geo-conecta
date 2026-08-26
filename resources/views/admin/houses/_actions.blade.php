<a href="{{ route('houses.edit', $id) }}" 
    class="btn btn-round btn-icon btn-outline-light m-1">
    <em class="icon ni ni-home"></em>
</a>
<div onclick="showDeleteModal('{{ $id }}', 'la casa de {{ $name }}', destroy, )" 
    class="btn btn-round btn-icon btn-outline-light m-1">
    <em class="icon ni ni-trash-alt"></em>
</div>