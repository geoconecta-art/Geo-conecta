<div class="mb-5 image-c">
    <label class="form-label" for="image">Imagen</label>
    @if ( isset($person->image) and !is_null($person->image) )
        <input type="hidden" name="has_image" value="1">
        <input type="file" name="image" class="dropify" data-default-file="{{ asset($person->image) }}"
                data-max-file-size="3M" data-height="300" id="image" />
    @else
        <input type="hidden" name="has_image" value="0">
        <input type="file" name="image" class="dropify" data-max-file-size="3M" data-height="300" id="image" />
    @endif
</div>

<div class="mb-5 ine-c">
    <label class="form-label" for="ine_image">INE</label>
    @if ( isset($person->ine_image) and !is_null($person->ine_image) )
        <input type="hidden" name="has_ine_image" value="1">
        <input type="file" name="ine_image" class="dropify" data-default-file="{{ asset($person->ine_image) }}"
                data-max-file-size="3M" data-height="300" id="ineImage" />
    @else
        <input type="hidden" name="has_ine_image" value="0">
        <input type="file" name="ine_image" class="dropify" data-max-file-size="3M" data-height="300" id="ineImage" />
    @endif
</div>