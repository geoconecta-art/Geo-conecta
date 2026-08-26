
<input type="hidden" name="name" id="name" >

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="name">Nombre *</label>
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
            id="lastName1" oninput="convertToUppercase('lastName1')" 
            required>
        </div>
    </div>
</div>

<div class="col-md-4 mb-3">
    <div class="form-group">
        <label class="form-label" for="name">Apellido Materno *</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" name="last_name_2"
            value="{{ isset($person->last_name_2) ? $person->last_name_2 : old('last_name_2') }}"
            id="lastName2" oninput="convertToUppercase('lastName2')" 
            required
            >
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

<!--
<div class="col-md-12 my-3">
    <span class="preview-title-lg overline-title">Dirección</span>
</div>

<div class="col-md-6 mb-3">
    <div class="form-group">
        <label class="form-label">Calle</label>
        <input type="text" class="form-control" 
            name="street" 
            value="{ isset($person->street) ? $person->street : '' }}"
            id="street" oninput="convertToUppercase('street')" 
            >
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">No. Ext.</label>
        <input type="text" class="form-control" 
            name="ext_number" 
            value="{ isset($person->ext_number) ? $person->ext_number : '' }}"
            id="extNumber" oninput="convertToUppercase('extNumber')"
            >
    </div>
</div>

<div class="col-md-3 mb-3">
    <div class="form-group">
        <label class="form-label">No. Int.</label>
        <input type="text" class="form-control" 
            name="int_number" 
            value="{ isset($person->int_number) ? $person->int_number : '' }}"
            id="intNumber" oninput="convertToUppercase('intNumber')"
            >
    </div>
</div>

<div class="col-md-6 mb-3">
    <div class="form-group">
        <label class="form-label" for="suburb_id">Colonia</label>
        <div class="form-control-wrap ">
            <select class="form-select" 
                    name="suburb_id" 
                    data-search="on" >
                <option selected disabled value="">Selecciona una colonia</option>
                foreach($suburbs as $suburb)
                    <option value="{ $suburb->id }}" if ( isset($person) and $person->suburb_id == $suburb->id) selected endif>
                        { $suburb->name }}
                    </option>
                endforeach
            </select>
        </div>
    </div>
</div>
-->
