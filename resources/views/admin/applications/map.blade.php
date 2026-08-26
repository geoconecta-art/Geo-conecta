
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
                        <h3 class="nk-block-title page-title">Mapa</h3>
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
            map.data.loadGeoJson('/maps/15_mexico.json');  // Edo mex
            //map.data.loadGeoJson('/maps/capas/2_SECCIONES_ELECTORALES_2024.geojson');  // Atizapan secciones electorales

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
    </script>


    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&callback=initMap"></script>


@endsection
