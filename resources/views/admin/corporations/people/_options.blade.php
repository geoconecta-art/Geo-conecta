<option selected disabled value="">Selecciona una opción</option>
@foreach ( $items as $item )
    <option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach