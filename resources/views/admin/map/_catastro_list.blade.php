<ul id="lotes-list">
    @foreach ($lotes as $lote)
    <li class="custom-control custom-control-sm custom-checkbox w-100 mb-2 {{ isset($avoiding_lotes) ? ( in_array( $lote->clave, $avoiding_lotes ) ? 'd-none' : '' ) : '' }} " id="li-lote-{{ $lote->clave }}">
                    
        <input type="checkbox" class="custom-control-input lote-checkbox" id="lote-{{ $lote->clave }}"
            value="{{ $lote->clave }}">

        <label class="custom-control-label w-100" for="lote-{{ $lote->clave }}">
            <span class="text-truncate w-100 catastro-title text-dark" style="display: block !important;">
                {{ $lote->clave }}
            </span>
        </label>
    </li>
    @endforeach
</ul>