@extends('admin.layout.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }

        .dropify-wrapper {
            height: 364px !important;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }

        .dropify-wrapper .dropify-message p {
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 20px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="nk-content-inner">
        
    <div class="nk-content-body">

            <div class="components-preview">

                <div class="nk-block-head">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">Promovido</h4>
                    </div>
                </div>

                <form id="personForm" 
                    action="{{ route('promoted.update', $person->id) }}" 
                    method="POST"
                    enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf

                    <div class="nk-block">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-preview">
                                    <div class="card-inner p-3">
                                        <label class="form-label mb-3">Fotografía en Estructura</label>
                                        <div class="img-c"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9">

                                <div class="card card-preview">

                                    <div class="card-inner">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="vote">Tipo de Voto *</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-control-select">
                                                                    <select class="form-control" name="vote" required>
                                                                        <option selected disabled value="">Selecciona
                                                                            una
                                                                            opción</option>
                                                                        <option value="VP"
                                                                            @if ($person->vote == 'VP') selected @endif>
                                                                            Voto
                                                                            Promovido (VP)</option>
                                                                        <option value="VA"
                                                                            @if ($person->vote == 'VA') selected @endif>
                                                                            Voto
                                                                            Afectivo (VA)</option>
                                                                        <option value="VAE"
                                                                            @if ($person->vote == 'VAE') selected @endif>
                                                                            Voto
                                                                            Afectivo Externo (VAE)</option>
                                                                        <option value="VC"
                                                                            @if ($person->vote == 'VC') selected @endif>
                                                                            Voto
                                                                            Corporativo (VC)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="vote-form"></div>

                                                <div class="row">
                                                    <div class="col-md-12 my-3">
                                                        <span class="preview-title-lg overline-title">PROMOVIDO</span>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="structure"></div>
                                                    </div>

                                                    @include('admin.promoted._form')
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 my-3">
                                                        <p class="text-soft">Campos requeridos*</p>
                                                        <button type="submit"
                                                            class="btn btn-outline-light">Guardar</button>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@section('script')
    @include('admin.people._map-script')
    @include('admin.promoted._script')

    <script>

        localStorage.setItem('storeProm', 0);
        localStorage.setItem('vote', '{{ $person->vote }}');
        localStorage.setItem('personId', '{{ $person->person_id }}');
        localStorage.setItem('block', '{{ $person->block }}');
        localStorage.setItem('section', '{{ $person->section }}');
        localStorage.setItem('corporationId', '{{ $person->corporation_id }}');

        $('select[name=vote]').trigger('change');

        //console.log(localStorage.getItem('storeProm'));

        document.getElementById('personForm')
            .addEventListener('submit', function(event) {
            event.preventDefault();
            showSubmitModal();
        });

        function showSubmitModal() {
            Swal.fire({
                title: '¿Estás seguro de enviar este formulario?',
                text: 'Los datos serán guardados en la base de datos',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if ( result.isConfirmed ) {
                    $loading.show();
                    submitForm();
                }
            });
        }

        function submitForm() {

            if ( !validateForm() ) {

                setInputErrorKeyup();

            } else {

                saveInLocalStorage();
                setName();
                let address = getFullAddress();

                $loading.show();

                var formData = new FormData($('#personForm')[0]);

                $.ajax({
                    url: $('#personForm').attr('action'), // Usa la URL de acción del formulario
                    method: $('#personForm').attr('method'), // Usa el método del formulario
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Aquí puedes manejar la respuesta del servidor
                        console.log(response); // Imprime la respuesta en la consola
                        $loading.hide();
                        showSuccessModal('El registro se ha editado correctamente');
                        //$('#response').html(response); // Muestra la respuesta en un elemento con id="response"
                    },
                    error: function(xhr, status, error) {
                        // Aquí puedes manejar cualquier error que ocurra
                        console.error(error); // Imprime el error en la consola
                        //$('#response').html('Error: ' + error); // Muestra el error en un elemento con id="response"
                    }
                });

                //$('#personForm').unbind('submit').submit();

                /*
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'address': address }, function(results, status) {
                    
                    if ( status === 'OK' ) {
                        var location = results[0].geometry.location;
                        $('input[name=lat]').val(location.lat());
                        $('input[name=lng]').val(location.lng());
                        $('#personForm').unbind('submit').submit(); 

                    } else {
                        $('input[name=lat]').val('');
                        $('input[name=lng]').val('');
                        $('#personForm').unbind('submit').submit(); 
                        
                    }
                });*/


            }

        }
    </script>
@endsection
