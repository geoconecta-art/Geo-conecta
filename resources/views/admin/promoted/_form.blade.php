<input type="hidden" name="name" id="name">
<input type="hidden" name="curp" id="curp">

<div class="col-md-12 row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="ine">
                <span class="mr-1">Clave de Elector * </span>
                <span data-toggle="modal" data-target="#ineModal" class="btn btn-round btn-icon btn-sm btn-light">
                    <em class="icon ni ni-question"></em>
                </span>
            </label>
            <div class="input-group">
                <input type="text" class="form-control" name="ine"
                    value="{{ isset($person->ine) ? $person->ine : old('ine') }}" maxlength="18" required
                    id="ine">
            </div>
            
        </div>
    </div>
</div>

<div class="col-md-12 row">
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="ine">
                <span class="mr-1">¿No se tiene la clave de elector?</span>
            </label>
            <div class="form-group"> 
                <div data-toggle="modal" data-target="#searchModal" class="btn btn-outline-light mb-3 mb-md-0">
                    <span>Buscar clave por nombre</span>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="first_name">Nombre (s) *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="first_name"
                value="{{ isset($person->first_name) ? $person->first_name : old('first_name') }}" required
                id="firstName" oninput="convertToUppercase('firstName')">
        </div>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="name">Apellido Paterno *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="last_name_1"
                value="{{ isset($person->last_name_1) ? $person->last_name_1 : old('last_name_1') }}" required
                id="lastName1" oninput="convertToUppercase('lastName1')">
        </div>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="name">Apellido Materno *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="last_name_2"
                value="{{ isset($person->last_name_2) ? $person->last_name_2 : old('last_name_2') }}" required
                id="lastName2" oninput="convertToUppercase('lastName2')">
        </div>
    </div>
</div>


<div class="col-md-12 my-3">
    <span class="preview-title-lg overline-title">Teléfonos</span>
</div>

<div class="col-md-6 mb-3">
    <div class="form-group">
        <label class="form-label" for="phone">Particular</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="phone" maxlength=10
                value="{{ isset($person->phone) ? $person->phone : old('phone') }}">
            <span class="help-block"><small></small></span>
        </div>
    </div>
</div>

<div class="col-md-6 mb-3">
    <div class="form-group">
        <label class="form-label" for="mobile">Celular/WhatsApp *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="mobile" maxlength=10
                value="{{ isset($person->mobile) ? $person->mobile : old('mobile') }}" required>
            <span class="help-block"><small></small></span>
        </div>
    </div>
</div>



<div class="col-md-12 my-3">
    <span class="preview-title-lg overline-title">Domicilio</span>
</div>

<div class="col-md-12 mb-3">
    <div class="form-group">
        <label class="form-label">Calle *</label>
        <input type="text" class="form-control" name="street"
            value="{{ isset($person->street) ? $person->street : old('street') }}" required id="street"
            oninput="convertToUppercase('street')">
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">No. Ext. *</label>
        <input type="text" class="form-control" name="ext_number"
            value="{{ isset($person->ext_number) ? $person->ext_number : old('ext_number') }}" required id="extNumber"
            oninput="convertToUppercase('extNumber')">
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">No. Int.</label>
        <input type="text" class="form-control" name="int_number"
            value="{{ isset($person->int_number) ? $person->int_number : old('int_number') }}" id="intNumber"
            oninput="convertToUppercase('intNumber')">
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">Lote</label>
        <input type="text" class="form-control" name="lote"
            value="{{ isset($person->lote) ? $person->lote : old('lote') }}" id="lote"
            oninput="convertToUppercase('lote')">
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">Mza</label>
        <input type="text" class="form-control" name="mza"
            value="{{ isset($person->mza) ? $person->mza : old('mza') }}" id="mza"
            oninput="convertToUppercase('mza')">
    </div>
</div>



<div class="col-md-5 mb-3" id="zipCode">
    <div class="form-group">
        <label class="form-label">C.P. *</label>
        <div class="form-control-wrap">
            <div class="form-control-select">
                <select class="form-control" name="zip_code" required>
                    <option selected disabled value="">Selecciona una opción</option>
                    @foreach ($zip_codes as $zip_code)
                        <option value="{{ $zip_code }}" @if (isset($person) and $person->zip_code == $zip_code) selected @endif>
                            {{ $zip_code }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="col-md-7 mb-3" id="suburbId">
    <div class="form-group">
        <label class="form-label" for="suburb_id">Colonia *</label>
        <div class="form-control-wrap ">
            <div class="form-control-select">
                <select class="form-control" name="suburb_id" required>
                    <option selected disabled value="">Selecciona una colonia</option>
                    @if (isset($person) && isset($suburbs))
                        @foreach ($suburbs as $suburb)
                            <option value="{{ $suburb->id }}" @if ($person->suburb_id == $suburb->id) selected @endif>
                                {{ $suburb->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="fullAddress">

<input type="hidden" name="lat" value="{{ isset($person->lat) ? $person->lat : '' }}" id="lat">

<input type="hidden" name="lng" value="{{ isset($person->lng) ? $person->lng : '' }}" id="lng">


@include('admin.people._ine-modal')
@include('admin.people._search-modal')
@include('admin.people._map-modal')
