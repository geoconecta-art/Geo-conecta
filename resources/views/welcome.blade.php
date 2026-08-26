<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Registro</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
        <link href="/css/public.css" rel="stylesheet">

    </head>
    <body>
        <header class="bg-gray">
            <div class="container py-3 text-center">
                <img src="/img/esconsonia.png" width="180" />
            </div>
        </header>
        
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-10 offset-1 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
                        <h1 class="mt-3 text-center">
                            ¡Regístrate!
                        </h1>
                        <h6 class="mb-3 text-center">Activa tu pulsera y continua al chat directo con la candidata</h6>
                        
                        <div id="errors"></div>
                        
                        @include('_form')
                        
                        <div class="wa-btn text-center">
                            @include('_wa-button')
                        </div

                    </div>
                </div>
            </div>
        </main>

        {{--Loading--}}
        <div id="loading">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        
            getLocation();

            document.getElementById('sendBtn').addEventListener("click", function(e) {
                
                e.preventDefault();
                document.getElementById('loading').style.display = 'block';

                //grecaptcha.ready(function() {
                //  grecaptcha.execute('6LfUoWIpAAAAALnMFRa-J_llqA5GlmowTstguBwO', { action: 'submit' }).then( function(token) {
                      if ( validateForm() ) {
                          sendForm('token');
                      } else {
                          document.getElementById('loading').style.display = 'none';
                      }
                      
                //  });
                //});
                
            });

    
            function sendForm(token) {
                
                document.getElementById('loading').style.display = 'block';

                let url = '/applications/store'
                data = getData(token);

                 // Configuración de la solicitud
                const requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                };

                fetch(url, requestOptions)
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        
                        console.log('Respuesta del servidor:', data);

                        document.getElementById('loading').style.display = 'none';

                        if ( data.success ) {
                            
                            //clearFields();

                            if ( data.chat ) {
                                document.getElementById('sendBtn').style.display = 'none';
                                document.getElementById('waBtn').style.display = 'inline-block';
                            }
                            
                            showSuccessMessage('Registro exitoso', data.message);
                            

                        } else {
                            //console.log( data.errors );
                            showErrors(data.errors);
                        }

                    })
                    .catch(error => {
                        document.getElementById('loading').style.display = 'none';
                        console.error('Error al realizar la solicitud:', error);
                    });
                
            }

            function getData(token) {
                return {
                    _token: document.querySelector('input[name="_token"]').value,
                    name: document.getElementById('name').value,
                    last_name: document.getElementById('lastName').value,
                    age: document.getElementById('age').value,
                    marital_status: document.getElementById('maritalStatus').value,
                    birthday: document.getElementById('birthday').value,
                    address: document.getElementById('address').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    latitude: document.getElementById('latitude').value,
                    longitude: document.getElementById('longitude').value,
                    code: document.getElementById('code').value,
                    recaptcha: token
                };
            }

            function clearFields() {
                document.getElementById('applicationForm').reset();
            }

            function showSuccessMessage(title, message) {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: "success"
                });
            }
            
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    alert("La geolocalización no es compatible en este navegador.");
                }
            }

            function showPosition(position) {
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
            }
            
            function validateForm() {
                
                var nameInput = document.getElementById('name');
                var lastNameInput = document.getElementById('lastName');
                var addressInput = document.getElementById('address');
                var birthdayInput = document.getElementById('birthday');
                var phoneInput = document.getElementById('phone');
                var emailInput = document.getElementById('email');
                var ageSelect = document.getElementById('age');
                var maritalStatusSelect = document.getElementById('maritalStatus');
                
                var validateName = validateRequired(nameInput, 'Nombre');
                var validateLastName = validateRequired(lastNameInput, 'Apellidos');
                var validateAddress = validateRequired(addressInput, 'Domicilio');
                var validateBirthday = validateRequired(birthdayInput, 'Fecha de Nacimiento');
                var validatePhone = validateRequired(phoneInput, 'Teléfono') && validateDigits(phoneInput, 'Teléfono', 10);
                var validateEmail = validateEmailFormat(emailInput, 'Email');
                var validateAge = validateRequired(ageSelect, 'Edad');
                var validateMaritalStatus = validateRequired(maritalStatusSelect, 'Estado Civil');
                
                setInputErrorKeyup();
                setSelectErrorChange();
                
                return validateName && validateLastName && validateAddress && validateBirthday && validatePhone && validateEmail && validateAge && validateMaritalStatus;
            }
            
            function validateRequired(input, fieldName) {
                var inputValue = input.value;
                
                if ( inputValue.trim() === '' ) {
                    var formGroup = input.parentNode;
                    formGroup.classList.add('has-error');
                    var errorElement = formGroup.querySelector('.error');
                    errorElement.innerHTML = 'El campo ' + fieldName + ' es obligatorio.';
                    return false;
                }
                return true;
            }
            
            function validateEmailFormat(input, fieldName) {
                var inputValue = input.value;
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                
                if ( inputValue !== '' && !regex.test(inputValue) ) {
                    var formGroup = input.parentNode;
                    formGroup.classList.add('has-error');
                    var errorElement = formGroup.querySelector('.error');
                    errorElement.innerHTML = 'El campo ' + fieldName + ' debe ser una dirección de correo válida.';
                    return false;
                }
                return true;
            }
            
            function validateDigits(input, fieldName, digits) {
                var inputValue = input.value;
                var regex = /^\d+$/;
                
                if ( inputValue !== '' && (!regex.test(inputValue) || inputValue.length !== digits) ) {
                    var formGroup = input.parentNode;
                    formGroup.classList.add('has-error');
                    var errorElement = formGroup.querySelector('.error');
                    errorElement.innerHTML = 'El campo ' + fieldName + ' debe ser un número de ' + digits + ' dígitos.';
                    return false;
                }
                return true;
            }

            function setInputErrorKeyup() {
                var inputsWithError = document.querySelectorAll('.has-error input');
                inputsWithError.forEach(function(input) {
                    input.addEventListener('keyup', function() {
                        var formGroup = input.parentNode;
                        formGroup.classList.remove('has-error');
                        var errorElement = formGroup.querySelector('.error');
                        errorElement.innerHTML = '';
                    });
                });
            }
            
            function setSelectErrorChange() {
                var selectsWithError = document.querySelectorAll('.has-error select');
                selectsWithError.forEach(function(select) {
                    select.addEventListener('change', function() {
                        var formGroup = select.parentNode;
                        formGroup.classList.remove('has-error');
                        var errorElement = formGroup.querySelector('.error');
                        errorElement.innerHTML = '';
                    });
                });
            }
            
            function showErrors(errors) {
                
                var html = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul>';
              
                if ( typeof errors['birthday'] != 'undefined' ) {
                    for (var i = 0; i < errors['birthday'].length; i++) {
                        html += '<li>' + errors['birthday'][i] + '</li>';
                    }
                }
                
                if ( typeof errors['phone'] != 'undefined' ) {
                    for (var i = 0; i < errors['phone'].length; i++) {
                        html += '<li>' + errors['phone'][i] + '</li>';
                    }
                }
                
                if ( typeof errors['email'] != 'undefined' ) {
                    for (var i = 0; i < errors['email'].length; i++) {
                        html += '<li>' + errors['email'][i] + '</li>';
                    }
                }
              
                html += '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button></div>';
                
                var item = document.getElementById('errors');
                item.innerHTML = html;
            }


        </script>
    </body>

    
</html>
