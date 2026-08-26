<div class="row col-12 px-0">

    <div class="mb-3 col-4">
        <label for="formGroupExampleInput" class="form-label">Sección</label>
        <input type="text" class="form-control" name="section"
                value="{{$booth->section}}" readonly
                >
    </div>

    <div class="mb-3 col-4">
        <label for="formGroupExampleInput" class="form-label">Tipo</label>
        <input type="text" class="form-control" name="type"
                value="{{$booth->type}}" readonly
                >
    </div>

    <div class="mb-3 col-4">
        <label for="formGroupExampleInput" class="form-label">Apellidos</label>
        <input type="text" class="form-control" name="letters"
                value="{{$booth->letters}}" readonly
                >
    </div>
</div>