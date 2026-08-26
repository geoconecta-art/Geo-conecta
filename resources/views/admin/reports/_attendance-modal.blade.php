

<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">Reporte de Asistencia</h5>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="day">Regiones *</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" 
                                name="day" required>
                            <option selected disabled value="">Selecciona un grupo de regiones</option>
                            <option value="1">Lunes (1, 3, 8, 9)</option>
                            <option value="2">Martes (2, 4, 9.1, 10)</option>
                            <option value="3">Miércoles (5, 7, 13, 14)</option>
                            <option value="4">Jueves (8.1, 11, 12, 13.1)</option>
                            <option value="5">Viernes (6, 15, 16)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="weeks">Semanas *</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select class="form-control" 
                                name="weeks" required multiple>
                            <option value="1">Primera semana</option>
                            <option value="2">Segunda semana</option>
                            <option value="3">Tercera semana</option>
                            <option value="4">Cuarta semana</option>
                            <option value="5">Quinta semana</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
<div class="modal-footer">
    <div class="btn btn-outline-light" onclick="generateReport(2)">
        Generar Reporte
    </div>
</div>

<script>

    $('select[name=weeks]').select2();

</script>