<div class="row ">
    <div class="col-7 px-0">
        <div class="mb-3">
            @if (isset($booth->image))
            <input type="hidden" name="has_image" value="1">
            <input type="file" class="dropify" name="image"
                data-max-file-size="3M" data-height="100" id="image" 
                data-default-file="{{ asset($booth->image) }}"
                accept="image/x-png,image/gif,image/jpeg"
            />
            @else 
            <input type="hidden" name="has_image" value="0">
            <input type="file" class="dropify" name="image"
                data-max-file-size="3M" data-height="100" id="image"
                accept="image/x-png,image/gif,image/jpeg"
            />
            @endif
            
        </div>
    </div>

    <div class="col-5 px-0">
        <div class="row px-0 mx-0 justify-content-between align-items-baseline">
            <label class="form-label mx-3" for="name">Sección </label>
            <div class="form-control-wrap w-50">
                <input type="text" class="form-control" name="section"
                    value="{{$booth->section}}" readonly
                    required>
            </div>
        </div>

        <div class="row px-0 mx-0 justify-content-between align-items-baseline">
            <label class="form-label mx-3" for="name">Tipo </label>
            <div class="form-control-wrap w-50">
                <input type="text" class="form-control" name="type"
                    value="{{$booth->type}}" readonly
                    required>
            </div>
        </div>

        <div class="row px-0 mx-0 justify-content-between align-items-baseline">
            <label class="form-label mx-3" for="name">Apellidos </label>
            <div class="form-control-wrap w-50">
                <input type="text" class="form-control" name="letters"
                    value="{{$booth->letters}}" readonly
                    required>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var drCoverEventImage = $('#image').dropify();
    
    drCoverEventImage.on('dropify.beforeClear', function (event, element) {
        $('input[name=has_image]').val(0);
    });
});
</script>