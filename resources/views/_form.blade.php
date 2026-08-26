<form id="applicationForm" action="">
    @csrf
    <div class="row">

        <div class="col-12">
            <div class="mb-3">
                <label for="name" class="form-label">1. Nombre</label>
                <input type="text" class="form-control" id="name" >
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <label for="lastName" class="form-label">2. Apellidos</label>
                <input type="text" class="form-control" id="lastName" >
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <label for="age" class="form-label">3. Edad</label>
                <select class="form-select" id="age" required>
                    <option selected disabled value="">Selecciona una opción</option>
                    @for ($i = 3; $i < 101; $i++)
                        <option value="{{ $i }}">{{ $i }} AÑOS</option>
                    @endfor
                </select>
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <label for="maritalStatus" class="form-label">4. Estado Civil</label>
                <select class="form-select" id="maritalStatus" required>
                    <option selected disabled value="">Selecciona una opción</option>
                    <option value="SOLTERO">SOLTERO</option>
                    <option value="CASADO">CASADO</option>
                    <option value="UNION LIBRE">UNION LIBRE</option>
                </select>
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <label class="form-label" for="birthday">5. Fecha de Nacimiento</label>
                <input type="text" class="form-control" id="birthday" placeholder="dd/mm/yyyy" required maxlength="10" />
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <label class="form-label" for="address">6. Domicilio</label>
                <input type="text" class="form-control" id="address" required />
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group mb-3">
                <label for="email" class="form-label">7. Email (Opcional)</label>
                <input type="email" class="form-control" id="email" >
                <div class="error"></div>
            </div>
        </div>

        <div class="col-12">
            <div class="mb-3">
                <label class="form-label" for="phone">8. Teléfono</label>
                <input type="text" class="form-control" maxlength="10" id="phone" required  />
                <div class="error"></div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="mb-3">
                <label for="lastName" class="form-label">Introduce el código de tu pulsera</label>
                <input type="password" class="form-control" id="code" >
            </div>
        </div>
        
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <div class="col-12 text-center">
            
            <h4 class="mt-3">¡ Bienvenido !</h4>
        
            <div class="btn btn-pink mt-3 mb-5" id="sendBtn">Continuar</div>

        </div>
        

    </div>
    </form>