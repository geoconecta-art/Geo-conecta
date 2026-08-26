<script>

    var map;
    var marker;
    var fullAddress = '';
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
            if ( status === 'OK' ) {
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

    function showMapModal() {

        if ( validateFullAddress() ) {
            fullAddress = getFullAddress();   

            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({ 'address': fullAddress }, function(results, status) {
                if ( status === 'OK' ) {
                    var location = results[0].geometry.location;
                    setMarkerPosition(location);
                    map.setCenter(location);
                } else {
                    alert('La dirección no se pudo encontrar: ' + status);
                }
            });

            $('#mapModal').modal('show');
        }
        
    }

    function validateFullAddress() {
        var street = $('input[name=street]').val();
        var extNumber = $('input[name=ext_number]').val();
        var intNumber = $('input[name=int_number]').val();

        var $anotherZipCode = $('#anotherZipCode');

        if ( $anotherZipCode.length > 0 && $anotherZipCode[0].checked ) {
            
            var suburb =  $('input[name=suburb]').val();
            var zipCode = $('input[name=outer_zip_code]').val();

        } else {

            var suburb =  $('select[name=suburb_id] option:selected').text();
            var zipCode = $('select[name=zip_code]').val();
        
        }
        
        if ( street === '' || 
            extNumber === '' || 
            suburb === '' || 
            suburb === 'Selecciona una colonia' || 
            zipCode == null || zipCode.length < 5 ) {
            
            showWarningModal('Llena correctamente los campos de dirección para continuar');
            return false;
        }

        return true;
        
    }

    function getFullAddress() {
        var street = $('input[name=street]').val();
        var extNumber = $('input[name=ext_number]').val();
        var intNumber = $('input[name=int_number]').val();

        var $anotherZipCode = $('#anotherZipCode');

        if ( $anotherZipCode.length > 0 && $anotherZipCode[0].checked ) {
            
            var suburb =  $('input[name=suburb]').val();
            var zipCode = $('input[name=outer_zip_code]').val();
            var location = '';

        } else {

            var suburb =  $('select[name=suburb_id] option:selected').text();
            var zipCode = $('select[name=zip_code]').val();
            var location =  'Atizapán de Zaragoza Estado de México';
        }

        //var suburb =  $('select[name=suburb_id] option:selected').text();
        //var zipCode = $('select[name=zip_code]').val();

        return street + ' '  
            + extNumber + ' ' 
            + intNumber  + ' ' 
            + suburb + ' ' 
            + zipCode + ' ' 
            + location;
            //' Atizapán de Zaragoza Estado de México';
    }



</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=places&callback=initMap"></script>