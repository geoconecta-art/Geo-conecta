
<div class="row">
    
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="region">Región *</label>
            <div class="form-control-wrap ">
                <div class="form-control-select">
                    <select class="form-control" 
                            name="region" required>
                        <option selected disabled value="">Selecciona una región</option>
                        @foreach ( $regions as $r )
                            <option value="{{ $r->region }}" 
                                    zones="{{ $r->zones }}"
                                @if ( $person->region == $r->region ) selected @endif >
                                {{ $r->region }}
                            </option>
                        @endforeach
                        
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="zone">Zona *</label>
            <div class="form-control-wrap ">
                <div class="form-control-select">
                    <select class="form-control" 
                            name="zone" required>
                        <option selected disabled value="">Selecciona una zona</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

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

    var zone = '{{ $person->zone }}';

     $('select[name=region]').on('change', function () {
        var zonesStr =  $('select[name=region] option:selected').attr('zones');
        var zones = zonesStr.split(',');

        setZonesOptions(zones);
    });

    function setZonesOptions(zones) {
        var htmlOptions = '<option selected disabled value="">Selecciona una zona</option>';

        $.each(zones, function(index, zone) {
            htmlOptions += '<option value="' + zone + '">' + zone + '</option>';
        });

        $('select[name=zone]').html(htmlOptions);
    }

    $('select[name=region]').trigger('change');
    $('select[name=zone]').val(zone);


</script>
