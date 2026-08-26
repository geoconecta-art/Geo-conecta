
<div class="form-group">
    <label class="form-label" for="training">¿Recibió Capacitación? *</label>
    <div class="form-control-wrap">
        <div class="form-control-select">
            <select class="form-control" 
                    name="training" required>
                <option selected disabled value="">Selecciona una opción</option>
                <option value="1" @if ( isset($person) and $person->training == 1 ) selected @endif >SI</option>
                <option value="0" @if ( isset($person) and $person->training == 0 ) selected @endif >NO</option>
            </select>
        </div>
    </div>
</div>
