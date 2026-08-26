<option selected disabled value="">Selecciona una opción</option>
@foreach ($suburbs as $suburb)
    <option value="{{ $suburb->id }}">{{ $suburb->name }}</option>
@endforeach