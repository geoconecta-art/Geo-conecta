<div class="row">
    <div class="col-md-12 my-3">
        <span class="preview-title-lg overline-title">ESTRUCTURA</span>
    </div>
</div>


<div class="row">
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label">Persona/Empresa *</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="corporation_id" required>
                        <option selected disabled value="">Búsqueda por Nombre</option>
                        @foreach ($corporations as $corp)
                            <option value="{{ $corp->id }}">
                                {{ $corp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label">Promotor</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="corporation_person_id">
                        <option selected disabled value="">Búsqueda por Nombre</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>