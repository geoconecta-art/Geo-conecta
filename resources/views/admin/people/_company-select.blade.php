<div class="form-group">
    <label class="form-label" for="company">Compañía *</label>
    <div class="form-control-wrap">
        <div class="form-control-select">
            <select class="form-control" 
                    name="company" required>
                <option selected disabled value="">Selecciona una opción</option>
                <option value="1" @if ( isset($person) and $person->company == 1 ) selected @endif >Telcel</option>
                <option value="2" @if ( isset($person) and $person->company == 2 ) selected @endif >AT&T</option>
                <option value="3" @if ( isset($person) and $person->company == 3 ) selected @endif >Movistar</option>
                <option value="4" @if ( isset($person) and $person->company == 4 ) selected @endif >Unefon (Red AT&T)</option>
                <option value="5" @if ( isset($person) and $person->company == 5 ) selected @endif >Virgin Mobile (Red Movistar)</option>
                <option value="6" @if ( isset($person) and $person->company == 6 ) selected @endif >CFE</option>
                <option value="7" @if ( isset($person) and $person->company == 7 ) selected @endif >Bait</option>
            </select>
        </div>
    </div>
</div>