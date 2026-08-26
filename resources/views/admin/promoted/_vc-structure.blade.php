<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="section">Sección *</label>
            <div class="form-control-wrap ">
                <div class="form-control-select">
                    <select class="form-control" 
                            name="section" required>
                        <option selected disabled value="">Búsqueda por sección</option>
                        @foreach ( $sections as $s )
                            <option value="{{ $s->section }}" 
                                {{ old('section') == $s->section ? 'selected' : '' }} >
                                {{ $s->section }}
                            </option>
                        @endforeach
                      
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">DF</label>
            <input type="text" class="form-control" name="df"
                value="{{ isset($section->df) ? $section->df : old('df') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">DL</label>
            <input type="text" class="form-control" name="dl"
                value="{{ isset($section->dl) ? $section->dl : old('dl') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">Región</label>
            <input type="text" class="form-control" name="region"
                value="{{ isset($section->region) ? $section->region : old('region') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">Zona</label>
            <input type="text" class="form-control" name="zone"
                value="{{ isset($section->zone) ? $section->zone : old('zone') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Dirección</label>
            <input type="text" class="form-control" name="dependence"
                value="{{ isset($section->dependence) ? $section->dependence : old('dependence') }}"
                readonly
                >
        </div>
    </div>

</div>