<div class="form-group">
    <label class="form-label">Secciones</label>
    <div class="form-control-wrap">
        <select class="form-select" name="sections[]" multiple required>
            @foreach ($all_sections as $section)
                <option value="{{ $section }}" 
                    @if ( isset($sections) and in_array($section, $sections) ) selected @endif >{{ $section }}</option>
            @endforeach
        </select>
    </div>
</div>