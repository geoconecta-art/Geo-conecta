
<div class="row">

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="section">Sección *</label>
            <div class="form-control-wrap ">
                <div class="form-control-select">
                    <select class="form-control" 
                            name="section" required>
                        <option selected disabled value="">Búsqueda por sección</option>
                        @foreach ( $sections as $s )
                            <option value="{{ $s->section }}" 
                                @if ( $person->section == $s->section ) selected @endif >
                                {{ $s->section }}
                            </option>
                        @endforeach
                        
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        @include('admin.people._training')
    </div>

</div>

<div class="section-info">
</div>

<script>

    $('select[name=section]').on('change', function () {
        let id = $('input[name=id]').val();
        let section = $(this).val();
        let _token = $token.val();
        $loading.show();

        $.post('/people/get-section-info', { 
            '_token':_token, 
            'section':section,
            'id':id, 
        }, function(data) {
            $('.section-info').html(data);
            $loading.hide();
        });

    });
</script>


    

