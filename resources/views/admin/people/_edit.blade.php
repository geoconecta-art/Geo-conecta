
<a href="#" class="close" data-dismiss="modal" aria-label="Close">
    <em class="icon ni ni-cross"></em>
</a>
<div class="modal-header">
    <h5 class="modal-title">Cambio de Nivel</h5>
</div>
<div class="modal-body">

    <input type="hidden" name="id" value="{{ $person->id }}">

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="type">Nivel *</label>
                <div class="form-control-wrap">
                    <div class="form-control-select">
                        <select class="form-control" 
                                name="type" required>
                            <option selected disabled value="">Selecciona una opción</option>
                            <option value="{{ Person::DIRECTOR }}">Director Regional</option>
                            <option value="{{ Person::ENLACE }}">Enlace Regional</option>
                            <option value="{{ Person::COORD_REGIONAL }}">Coordinador Regional</option>
                            <option value="{{ Person::COORD_ZONA }}">Coordinador de Zona</option>
                            <option value="{{ Person::COORD_SECCION }}">Coordinador Seccional</option>
                            <option value="{{ Person::MOVILIZADOR }}">Promotor</option>
                            <option value="{{ Person::PEDROENTUCASA }}">Pedro en tu Casa</option>
                            <option value="{{ Person::FISCALIZADOR }}">Fiscalizador</option>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="person-form">

    </div>

    
</div>
<div class="modal-footer">
    <div class="btn btn-outline-light" onclick="updateLevel()">
        Actualizar
    </div>
</div>

<script>


    $('select[name=type]').on('change', function () {
    
        let type = $(this).val();
        let personId = '{{ $person->id }}';
        let _token = $token.val();
        $loading.show();

        console.log('ID persona', personId);

        $.post('/people/get-form', { 
            '_token': _token, 
            'person_id': personId,
            'type': type 
        }, function(data) {
            $('.person-form').html(data);
            $loading.hide();
        });

    });

    $('select[name=type]').val({{ $person->type }}).trigger('change');

</script>