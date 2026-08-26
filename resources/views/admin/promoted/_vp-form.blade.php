<div class="row">
    <div class="col-md-12 my-3">
        <span class="preview-title-lg overline-title">ESTRUCTURA</span>
    </div>
</div>

<div class="row">

    <div class="col-lg-12 mb-3">
        <div class="form-group">
            <label class="form-label">Persona *</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="person_id" required>
                        <option selected disabled value="">Búsqueda por Nombre</option>
                        @foreach ($people as $p)
                            <option value="{{ $p->id }}" section="{{ $p->section }}"
                                img="{{ $p->image }}">
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Promovidos</label>
            <input type="text" class="form-control" name="promotedNumber"
                value="{{ isset($person->promotedNumber) ? $person->promotedNumber : '' }}"
                readonly
                >
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Semana 1</label>
            <input type="text" class="form-control" id="week1"
                readonly
                >
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Semana 2</label>
            <input type="text" class="form-control" id="week2"
                readonly
                >
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Semana 3</label>
            <input type="text" class="form-control" id="week3"
                readonly
                >
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Semana 4</label>
            <input type="text" class="form-control" id="week4"
                readonly
                >
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="form-label">Semana 5</label>
            <input type="text" class="form-control" id="week5"
                readonly
                >
        </div>
    </div>

    <div class="col-lg-12 mb-3">
        <div class="form-group">
            <label class="form-label">Notas</label>
            <div class="form-control-wrap">
                <textarea class="form-control" name="note" 
                id="note" oninput="convertToUppercase('note')" ></textarea>
            </div>
        </div>
    </div>

</div>

<div class="row">
    
    <div class="col-md-4 mb-3">

        <div class="form-group">
            <label class="form-label">Sección *</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="section" required>
                        <option selected disabled value="">Búsqueda por Nombre</option>
                        @foreach ( $sections as $s )
                            <option value="{{ $s->section }}" section={{ $s->section }}>
                                {{ $s->section }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label">Manzana Electoral</label>
            <div class="form-control-wrap">
                <div class="form-control-select">
                    <select class="form-control" name="block">
                        <option selected disabled value="">Selecciona una opción</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="form-label">Microregión</label>
            <input type="text" class="form-control" name="mr"
                value="" 
                readonly
                >
        </div>
    </div>

</div>

<div class="section-info">
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label class="form-label">DF</label>
                <input type="text" class="form-control" name="df"
                    value="{{ isset($person->df) ? $person->df : '' }}"
                    readonly
                    >
            </div>
        </div>
    
        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label class="form-label">DL</label>
                <input type="text" class="form-control" name="dl"
                    value="{{ isset($person->dl) ? $person->dl : '' }}"
                    readonly
                    >
            </div>
        </div>
    
        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label class="form-label">Región</label>
                <input type="text" class="form-control" name="region"
                    value="{{ isset($person->region) ? $person->region : '' }}" 
                    readonly
                    >
            </div>
        </div>
    
        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label class="form-label">Zona</label>
                <input type="text" class="form-control" name="zone"
                    value="{{ isset($person->zone) ? $person->zone : '' }}" 
                    readonly
                    >
            </div>
        </div>
    
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="dependence"
                    value="{{ isset($person->dependence) ? $person->dependence : '' }}" 
                    readonly
                    >
            </div>
        </div>
    
    </div>
</div>