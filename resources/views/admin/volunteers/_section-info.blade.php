
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

</div>

