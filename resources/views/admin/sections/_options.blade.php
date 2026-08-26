<option value="0">Todas</option>
@foreach ( $sections as $s )
    <option value="{{ $s->section }}">{{ $s->section }}</option>
@endforeach