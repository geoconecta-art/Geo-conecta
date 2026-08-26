<div class="mb-5 image-c">
    <label class="form-label" for="image">{{ isset($image_title) ? $image_title : 'Imagen' }}</label>
    <input type="hidden" name="has_image" value="0">
    <input type="file" name="image" class="dropify" data-max-file-size="3M" data-height="300" id="image" />
</div>

<div class="mb-5 ine-c">
    <label class="form-label" for="ine_image">INE</label>
    <input type="hidden" name="has_ine_image" value="0">
    <input type="file" name="ine_image" class="dropify" data-max-file-size="3M" data-height="300" id="ineImage" />
</div>