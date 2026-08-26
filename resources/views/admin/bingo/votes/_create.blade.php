
<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">Registrar Votos</h5>
</div>
<div class="modal-body">

    <div class="row">

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label" for="section">Sección *</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" 
                                name="section" required>
                            <option selected disabled value="">Selecciona una sección</option>
                            @foreach ( $sections as $section )
                                <option value="{{ $section->section }}">
                                    {{ $section->section }}
                                </option>
                            @endforeach
                          
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label" for="votes">Votos *</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" 
                        name="votes" required>
                </div>
            </div>
        </div>
    </div>

    
</div>
<div class="modal-footer">
    <div class="btn btn-outline-light" onclick="store()">
        Guardar
    </div>
</div>

<script>
    $('select[name=section]').select2();
</script>