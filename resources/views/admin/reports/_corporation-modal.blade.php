
<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">{{$title}}</h5>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="region">Corporación *</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" 
                                name="corporation" required>
                            <option selected disabled value="">Selecciona una corporación</option>
                            <option value="-1">Todas</option>
                            @foreach ( $corporations as $corp )
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

    
</div>
<div class="modal-footer">
    <div class="btn btn-outline-light" onclick="generateReport({{$type}})">
        Generar Reporte
    </div>
</div>

<script>
    $('select[name=corporation]').select2();
</script>