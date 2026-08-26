<script>

    var map;
    var marker;
    var iniLat = "{{ isset($person->lat) ? $person->lat : '19.543454812522057' }}";
    var iniLng = "{{ isset($person->lng) ? $person->lng : '-99.23502275276154' }}";
    var centerLocation = { lat: Number(iniLat), lng: Number(iniLng) }; 

    document.getElementById('addressInput').addEventListener('keydown', function(event) {
        if ( event.key === 'Enter' ) {
            showAddress();
            event.preventDefault(); 
        }
    });

    $('#mapModal').on('shown.bs.modal', function () {
        $('#addressInput').focus(); 
    });
        
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 20,
            center: centerLocation,
            draggableCursor: 'default'
        });

        marker = new google.maps.Marker({
            map: map,
            position: centerLocation,
            draggable: false
        });
        
        initAutocomplete();

        google.maps.event.addListener(map, 'click', function(event) {
            var clickedLocation = event.latLng;
            setMarkerPosition(clickedLocation);
        });

        google.maps.event.addListener(map, 'dragstart', function() {
            map.setOptions({ draggableCursor: 'grabbing' });
        });

        google.maps.event.addListener(map, 'dragend', function() {
            map.setOptions({ draggableCursor: 'default' }); 
        });

    
    }

    function initAutocomplete() {
        var addressInput = document.getElementById('addressInput');
        var autocomplete = new google.maps.places.Autocomplete(addressInput);
    }

    function showAddress() {
        
        var geocoder = new google.maps.Geocoder();
        var address = document.getElementById('addressInput').value;
            
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status === 'OK') {
                var location = results[0].geometry.location;
                setMarkerPosition(location);
            } else {
                alert('La dirección no se pudo encontrar: ' + status);
            }
        });
    }

    function setMarkerPosition(location) {
        marker.setPosition(location);

        $('#mapLat').val(location.lat());
        $('#mapLng').val(location.lng());
    }

    function saveCoords() {
        var lat = $('#mapLat').val();
        var lng = $('#mapLng').val();

        if ( lat === '' && lng === '' ) {
            alert('Selecciona la ubicación en el mapa para obtener las coordenadas.');
            return
        }

        $('input[name=lat]').val(lat);
        $('input[name=lng]').val(lng);
        $('#mapModal').modal('hide');
    }



</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAE429aQsSXhiDAbOdgYrBHe1od07uxDxo&libraries=places&callback=initMap"></script>