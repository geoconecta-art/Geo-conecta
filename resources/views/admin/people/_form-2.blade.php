
<input type="hidden" name="name" id="name" >

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="first_name">Nombre *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="first_name"
            value="{{ isset($person->first_name) ? $person->first_name : old('first_name') }}"
            required
            id="firstName" oninput="convertToUppercase('firstName')" 
            >
        </div>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="name">Apellido Paterno *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="last_name_1"
            value="{{ isset($person->last_name_1) ? $person->last_name_1 : old('last_name_1') }}"
            required
            id="lastName1" oninput="convertToUppercase('lastName1')" 
            >
        </div>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="name">Apellido Materno *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="last_name_2"
            value="{{ isset($person->last_name_2) ? $person->last_name_2 : old('last_name_2') }}"
            required
            id="lastName2" oninput="convertToUppercase('lastName2')" 
            >
        </div>
    </div>
</div>

<div class="col-md-8 mb-3">
    <div class="form-group">
        <label class="form-label" for="ine">
            <span class="mr-1">Clave de Elector * </span>
            <span data-toggle="modal" data-target="#ineModal" 
                class="btn btn-round btn-icon btn-sm btn-light">
                <em class="icon ni ni-question"></em>
            </span>
        </label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="ine" 
            value="{{ isset($person->ine) ? $person->ine : old('ine') }}"
            maxlength="18"
            required
            id="ine" 
            >
            <span class="help-block"><small></small></span>
        </div>
    </div>
</div>

<div class="col-md-6 mb-3">
    <div class="form-group">
        <label class="form-label" for="email">Teléfono *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="phone" maxlength=10
            value="{{ isset($person->phone) ? $person->phone : old('phone') }}"
            required
            >
            <span class="help-block"><small></small></span>
        </div>
    </div>
</div>

<div class="col-md-6 mb-3">
    @include('admin.people._company-select')
</div>



<div class="col-md-12 my-3">
    <span class="preview-title-lg overline-title">Dirección</span>
</div>

<div class="col-md-6 mb-3">
    <div class="form-group">
        <label class="form-label">Calle *</label>
        <input type="text" class="form-control" 
            name="street" 
            value="{{ isset($person->street) ? $person->street : old('street') }}"
            required
            id="street" oninput="convertToUppercase('street')" 
            >
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">No. Ext. *</label>
        <input type="text" class="form-control" 
            name="ext_number" 
            value="{{ isset($person->ext_number) ? $person->ext_number : old('ext_number') }}"
            required
            id="extNumber" oninput="convertToUppercase('extNumber')"
            >
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">No. Int.</label>
        <input type="text" class="form-control" 
            name="int_number" 
            value="{{ isset($person->int_number) ? $person->int_number : old('int_number') }}"
            id="intNumber" oninput="convertToUppercase('intNumber')"
            >
    </div>
</div>

@if ( !isset($is_promoted) or !$is_promoted )
<div class="col-md-12 mb-3">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="anotherZipCode"
            @if ( isset($person->outer_zip_code) && !is_null($person->outer_zip_code) && $person->outer_zip_code != '' ) checked @endif >
        <label class="custom-control-label" for="anotherZipCode"><b>Otro Código Postal</b></label>
    </div>
</div>

<div class="col-md-5 mb-3" id="outerZipCode">
    <div class="form-group">
        <label class="form-label">C.P. *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" 
            name="outer_zip_code" required
            value="{{ isset($person->outer_zip_code) ? $person->outer_zip_code : old('outer_zip_code') }}"
            >
        </div>
    </div>
</div>

<div class="col-md-7 mb-3" id="suburb">
    <div class="form-group">
        <label class="form-label" for="suburb">Colonia *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" 
            name="suburb" required
            value="{{ isset($person->suburb) ? $person->suburb : old('suburb') }}"
            id="suburbInput" oninput="convertToUppercase('suburbInput')"
            >
        </div>
    </div>
</div>

@endif

<div class="col-md-5 mb-3" id="zipCode">
    <div class="form-group">
        <label class="form-label">C.P. *</label>
        <div class="form-control-wrap">
            <div class="form-control-select">
                <select class="form-control" name="zip_code" required>
                    <option selected disabled value="">Selecciona una opción</option>
                    @foreach ($zip_codes as $zip_code)
                        <option value="{{ $zip_code }}"
                            @if ( isset($person) and $person->zip_code == $zip_code ) selected @endif>
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
                <select class="form-control" 
                        name="suburb_id" required>
                    <option selected disabled value="">Selecciona una colonia</option>
                    @if ( isset($person) && isset($suburbs) )
                        @foreach ( $suburbs as $suburb )
                            <option value="{{ $suburb->id }}"
                                @if ( $person->suburb_id == $suburb->id ) selected @endif>
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

<div class="col-md-12 my-3">
    <span class="preview-title-lg overline-title">Georreferencia</span>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label">Latitud *</label>
        <input type="text" class="form-control" 
            name="lat" 
            value="{{ isset($person->lat) ? $person->lat : '' }}"
            id="lat" 
            readonly required
            >
        <span class="help-block"><small></small></span>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label">Longitud *</label>
        <input type="text" class="form-control" 
            name="lng" 
            value="{{ isset($person->lng) ? $person->lng : '' }}"
            id="lng" 
            readonly required
            >
        <span class="help-block"><small></small></span>
    </div>
</div>

<div class="col-md-4 mb-5">
    <div onclick="showMapModal()"
        class="btn btn-light btn-form">
        <span>Ubicar en el mapa</span><em class="icon ni ni-location"></em>
    </div>
</div>


@include('admin.people._ine-modal')
@include('admin.people._map-modal')


