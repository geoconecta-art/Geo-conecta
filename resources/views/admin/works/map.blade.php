
@extends('admin.layout.app')

@section('style')
    <style>
        table.dataTable.nowrap th, table.dataTable.nowrap td {
            white-space: normal !important;
        }
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Mapa de obras</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">
                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">
                            <div id="google-map" style="width: 100%; height: 600px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

<script>

        var freezers = {!! json_encode($markers) !!};

        function initMap() {
            
            // Inicializar el mapa
            var map = new google.maps.Map(document.getElementById('google-map'), {
                zoom: 8,
                center: { lat: 0, lng: 0 } // Centrar el mapa en el océano Atlántico (coordenadas 0, 0)
            });

            map.data.loadGeoJson('/maps/15_mexico.json');  // Edo mex

            var geocoder = new google.maps.Geocoder();

            freezers.forEach(function(freezer) {

                if ( freezer.lat !== '' && freezer.lng !== '' ) {

                    let lat = parseFloat(freezer.lat);
                    let lng = parseFloat(freezer.lng);

                    if ( lat != 'NaN' && lng != 'NaN' ) {

                        var location = {
                            lat: lat,
                            lng: lng
                        };

                        map.setCenter(location);
                        var marker = createMarker(map, location);
                        addMouseoverListener(map, marker, freezer);
                    } else {
                        addAddress(freezer);
                        console.log('La dirección no se pudo encontrar: ', freezer.address);
                    }


                } else {
                    geocoder.geocode({ 'address': freezer.gm_address }, function(results, status) {
                
                        if (status === 'OK') {
                            var location = results[0].geometry.location;
                            map.setCenter(location);
                            var marker = createMarker(map, location);
                            addMouseoverListener(map, marker, freezer);
                          
                        } else {

                            addAddress(freezer);
                            console.log('La dirección no se pudo encontrar: ', freezer.address);
                        
                        }
                    });
                }
                

              
            });
            

        }

        function createMarker(map, location) {

            // var iconImage = '/img/mr-frio.png';
            // var blueMarker = '/img/blue-marker.png';
            var iconImage = '/img/icon-wa.png';

            return new google.maps.Marker({
                                map: map,
                                position: location,
                                icon: iconImage
                            });
        }

        function addMouseoverListener(map, marker, freezer) {
            
            var infoWindow = new google.maps.InfoWindow();

            marker.addListener('click', function() {

                infoWindow.setContent('<div>' +
                    '<div style="margin-bottom:5px; color:var(--color-electo-pink)"><b>' + freezer.name +  '</b></div>' +
                    '<div style="margin-bottom:5px;"><b>Colonia: </b>' + freezer.suburb + '</div>' +
                    '<div style="margin-bottom:5px;"><b>Calle: </b>' + freezer.street + '</div>' +
                    '<div style="margin-bottom:5px;"><b>Inversión: </b>' + freezer.investment + '</div>' +
                    '<div style="margin-bottom:5px;"><b>Beneficiados: </b>' + freezer.benefit + '</div>' +
                    '<div style="margin-bottom:5px;"><img src="' + freezer.img1 + '"></img></div>' +
                    '<div style="margin-bottom:5px;"><img src="' + freezer.img2 + '"></img></div>' +
                    '</div>');

                infoWindow.open(map, marker);
            });

        }


        function addAddress(freezer) {
            let html = '<tr>' +
                '<td class="txt-oflo">' + freezer.address + '</td>' +
                '<td class="txt-oflo">' + freezer.customer + '</td>' +
                '<td><a type="button" class="btn btn-warning btn-circle m-r-5" href="/admin/clientes/' + freezer.customer_id + '/editar" target="_blank"><i class="fas fa-external-link-alt"></i></a></td>' +
                '</tr>'
                ;

            $('#addressesTable tbody').append(html);
        }


    </script>

    
    <!-- <script>
        
        $loading.hide();
    
        var map;
        var markers = {!! json_encode($markers) !!};
        
        function initMap() {
            
            map = new google.maps.Map(document.getElementById('google-map'), {
                zoom: 8,
                center: {lat: 19.5893548, lng: -99.2612626} 
            });
            
            // Cargar el archivo GeoJSON
            //map.data.loadGeoJson('/maps/sec.geojson'); // SLP
            //map.data.loadGeoJson('/maps/15-sec.geojson');  // Edo mex
            //map.data.loadGeoJson('/maps/15_mexico.json');  // Edo mex
            map.data.loadGeoJson('/maps/capas/2_SECCIONES_ELECTORALES_2024.geojson');  // Atizapan secciones electorales

            map.data.setStyle({
              fillColor: '#6c5c5c',
              fillOpacity: 0.35,
              strokeWeight: 2,
              strokeColor: '#6c5c5c'
            });
            
            map.data.addListener('mouseover', function(event) {
                map.data.overrideStyle(event.feature, {fillOpacity: 0.7});
            });
            
            map.data.addListener('mouseout', function(event) {
                map.data.revertStyle();
            });
            
            map.data.addListener('click', function(event) {
                debugger;

                var polygonObject = event.feature.getGeometry();

                // Crear un polígono de Google Maps 
                var polygon = new google.maps.Polygon({
                    paths: polygonObject.Fg[0].Fg[0].Fg.map(function(point) {
                        return { lat: point.lat(), lng: point.lng() };
                    })
                });

                var markersNumber = 0;
            
                markers.forEach(function(marker) {
                    var markerPosition = new google.maps.LatLng(marker.lat, marker.lng);
                    if ( google.maps.geometry.poly.containsLocation(markerPosition, polygon) )
                        markersNumber++;
                });
            
                var infoWindow = new google.maps.InfoWindow({
                    content: markersNumber + ' registros'
                });
            
                infoWindow.setPosition(event.latLng);
                infoWindow.open(map);
                
            });
            
            // Recorrer el array y agregar marcadores
            markers.forEach(function(marker) {
                
                var iconImage = '/img/icon-wa.png';

                var newMarker = new google.maps.Marker({
                    position: { lat: marker.lat, lng: marker.lng },
                    map: map,
                    title: marker.title,
                    icon: iconImage
                });

                // Puedes agregar información adicional al marcador, por ejemplo, un cuadro de información
                var infoWindow = new google.maps.InfoWindow({
                    content: marker.title
                });

                newMarker.addListener('click', function() {
                    infoWindow.open(map, newMarker);
                });
                
              
                
            });

            

            
        }
    </script> -->


    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjnFkAKIPzt-1d0lQsu-SuInc4jqFYRYM&callback=initMap"></script>


@endsection
