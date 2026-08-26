
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
                                df="{{ $s->df }}"
                                dl="{{ $s->dl }}"
                                region="{{ $s->region }}"
                                zone="{{ $s->zone }}"
                                @if ( $person->section == $s->section ) selected @endif >
                                {{ $s->section }}
                            </option>
                        @endforeach
                        
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">DF</label>
            <input type="text" class="form-control" name="df"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">DL</label>
            <input type="text" class="form-control" name="dl"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Región</label>
            <input type="text" class="form-control" name="region"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Zona</label>
            <input type="text" class="form-control" name="zone"
                readonly
                >
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">
        @include('admin.people._training')
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="vote">Tipo *</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" 
                            name="coord_type" required>
                        <option selected disabled value="">Selecciona una opción</option>
                        <option value="A" @if ( $person->coord_type == 'A' ) selected @endif >A</option>
                        <option value="B" @if ( $person->coord_type == 'B' ) selected @endif>B</option>
                        <option value="C" @if ( $person->coord_type == 'C' ) selected @endif>C</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
     $('select[name=section]').on('change', function () {
        var df =  $('select[name=section] option:selected').attr('df');
        var dl =  $('select[name=section] option:selected').attr('dl');
        var region =  $('select[name=section] option:selected').attr('region');
        var zone =  $('select[name=section] option:selected').attr('zone');
        
        $('input[name=df]').val(df);
        $('input[name=dl]').val(dl);
        $('input[name=region]').val(region);
        $('input[name=zone]').val(zone);
    });

    $('select[name=section]').trigger('change');

</script>
