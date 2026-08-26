
@extends('admin.layout.app')


@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">

    <style>

        #map {
            height: 400px;
            width: 100%;
        }

        .dropify-wrapper {
            height: 364px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }

        .dropify-wrapper .dropify-message p {
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 20px;
        }
    </style>
@endsection

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">

        <div class="components-preview mx-auto">

            <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Registrar entrega de apoyo</h4>
                </div>
            </div>

            

            <form action="{{ route('support.delive.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="nk-block">

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card card-preview">
                        <div class="card-inner">

                            <div class="row gy-4">

                                <div class="col-md-4">

                                    <span class="preview-title-lg overline-title">APOYO</span>

                                    <div class="form-group">
                                        <label class="form-label" for="name">Descripción</label>
                                        <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="name">Costo</label>
                                        <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="cost">
                                        </div>
                                    </div>
                    
                                </div>



                                <div class="col-md-4">  
                                    <span class="preview-title-lg overline-title">BENEFICIADO</span>
                                
                                    <div class="form-group">
                                        <label class="form-label" for="name">Apelllido Paterno</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="beneficiary_father_last_name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="name">Apelllido Materno</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="beneficiary_mother_last_name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="name">Nombre(s)</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="beneficiary_first_name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="beneficiary_quantity">No. de Beneficiados</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" name="beneficiary_quantity">
                                        </div>
                                    </div>


                                </div>

                                <div class="col-md-4">

                                <div class="mb-5 ine-c">
                                    <label class="form-label" for="image">Imagen</label>
                                    <input type="hidden" name="has_ine_image" value="0">
                                    <input type="file" name="image" class="dropify" 
                                        data-max-file-size="3M" data-height="300" id="image" />
                                </div>
                                   

                                </div>
                              
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-light">Registrar</button>
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
    <script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>

    <script>

        var map;
        
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: {lat: 22.1509678, lng: -101.001761} 
            });
            
            var addressInput = document.getElementById('addressInput');
            var autocomplete = new google.maps.places.Autocomplete(addressInput);
        }

        $(document).ready(function() {

            var drCoverEventImage = $('#image').dropify();

            drCoverEventImage.on('dropify.beforeClear', function (event, element) {
                $('input[name=has_image]').val(0);
            });

           

        });

        function showAddress() {
            var geocoder = new google.maps.Geocoder();
              var address = document.getElementById('addressInput').value;
            
              geocoder.geocode({ 'address': address }, function(results, status) {
                
                if (status === 'OK') {
                  var location = results[0].geometry.location;
                  map.setCenter(location);
                  var marker = new google.maps.Marker({
                    map: map,
                    position: location
                  });
                  
                  $('input[name=address]').val(results[0].formatted_address);
                  
                  // Aquí puedes guardar la ubicación en una base de datos
                  // Puedes usar AJAX para enviar los datos al servidor
                  // Ejemplo: $.post('/guardar_ubicacion', {lat: location.lat(), lng: location.lng()});

                } else {
                  alert('La dirección no se pudo encontrar: ' + status);
                }
              });
        }

    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAE429aQsSXhiDAbOdgYrBHe1od07uxDxo&libraries=places&callback=initMap"></script>

@endsection

