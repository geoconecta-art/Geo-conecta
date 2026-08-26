<div class="form-group">
    <label class="form-label">Secciones</label>
    <div class="form-control-wrap">
        <select class="form-select" name="sections[]" multiple required >
            @foreach ( $sections as $section )
                <option value="{{ $section->section }}"
                    @if ( isset($p_sections) && in_array($section->section, $p_sections)) selected @endif >{{ $section->section }}</option>
            @endforeach
        </select>
    </div>
</div>