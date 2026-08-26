<div class="row">
    <div class="col-md-8 mb-3">
        
        <div class="form-group">
            <label class="form-label">Coordinador Seccional *</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="person_id" @if ( isset($edit) && !$edit ) disabled @endif >
                        <option selected disabled value="">Selecciona una opción</option>
                        @if ( isset($people) )
                            @foreach ( $people as $p )
                                <option value="{{ $p->id }}">
                                    {{ $p->name }} 
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-4 mb-3">
        
        <div class="form-group">
            <label class="form-label">Microregión *</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="mr" required @if ( isset($edit) && !$edit ) disabled @endif >
                        <option selected disabled value="">Selecciona una opción</option>
                        @if ( isset($microregions) )
                            @foreach ( $microregions as $mr )
                                <option value="{{ $mr }}" >
                                    {{ $mr }} 
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">DF</label>
            <input type="text" class="form-control" name="df"
                value="{{ isset($section->df) ? $section->df : old('df') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">DL</label>
            <input type="text" class="form-control" name="dl"
                value="{{ isset($section->dl) ? $section->dl : old('dl') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">Región</label>
            <input type="text" class="form-control" name="region"
                value="{{ isset($section->region) ? $section->region : old('region') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="form-label">Zona</label>
            <input type="text" class="form-control" name="zone"
                value="{{ isset($section->zone) ? $section->zone : old('zone') }}"
                readonly
                >
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Dirección</label>
            <input type="text" class="form-control" name="dependence"
                value="{{ isset($section->dependence) ? $section->dependence : old('dependence') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Director Regional</label>
            <input type="text" class="form-control" name="director"
                value="{{ isset($section->director) ? $section->director : old('director') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Coordinador Regional</label>
            <input type="text" class="form-control" name="r_coordinator"
                value="{{ isset($section->r_coord) ? $section->r_coord : old('r_coord') }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label">Coordinador de Zona</label>
            <input type="text" class="form-control" name="z_coordinator"
                value="{{ isset($section->z_coord) ? $section->z_coord : old('z_coord') }}"
                readonly
                >
        </div>
    </div>

</div>