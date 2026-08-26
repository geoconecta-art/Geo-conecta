
<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">Crear Promotor</h5>
</div>
<div class="modal-body">

    <input type="hidden" name="corporation_id" value="{{ $corporation_id }}">

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Nombre *</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" 
                        name="name" required>
                </div>
            </div>
        </div>
    </div>

    
</div>
<div class="modal-footer">
    <div class="btn btn-outline-light" onclick="store()">
        Crear
    </div>
</div>

<script>

</script>