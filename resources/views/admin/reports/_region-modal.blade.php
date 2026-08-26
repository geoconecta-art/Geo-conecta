
<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">Reporte por Región</h5>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="region">Región *</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" 
                                name="region" required>
                            <option selected disabled value="">Selecciona una región</option>
                            @foreach ( $regions as $region )
                                <option value="{{ $region->region }}">
                                    {{ $region->region }} - {{ $region->dependence }}
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
    <div class="btn btn-outline-light" onclick="generateReport(1)">
        Generar Reporte
    </div>
</div>

<script>

</script>