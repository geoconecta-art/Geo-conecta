<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ asset('assets/js/utils/calculateDistance.js') }}"></script>
<script>

    var map;
    var iniMarker, finMarker, line;
    var fullAddress = '';

    var iniLat = 19.543454812522057;
    var iniLng = -99.23502275276154;

    var id_sufix = "";

    var centerLocation = { lat: iniLat, lng: iniLng };

    // document.getElementById('addressInput').addEventListener('keydown', function(event) {
    //     if ( event.key === 'Enter' || event.which == 13) {
    //         event.preventDefault();
    //         console.log("HOLA");
    //         var address = document.getElementById('addressInput').value;
    //         showAddress(address);
    //     }
    // });

    $('#mapModal').on('shown.bs.modal', function () {
        $('#addressInput').focus(); 
    });

    $("#addressInput").keydown( function() {
        if (event.key === "Enter" || event.which === 13) {
            var address = document.getElementById('addressInput').value;
            showAddress(address);
        }
    })

    // Inicializa un marcador
    function initMarker(){
        return new google.maps.Marker({
            position: centerLocation,
            draggable: false,
            map: map,
        });
    }
    
    // Inicializa el mapa
    async function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 20,
            center: centerLocation,
            draggableCursor: 'default',
            // mapTypeId: google.maps.MapTypeId.SATELLITE,
        });
        
        initAutocomplete();

        google.maps.event.addListener(map, 'click', function(event) {
            var clickedLocation = event.latLng;
            setMarkerPosition(clickedLocation, id_sufix == "Inicial" ? iniMarker : finMarker);
        });

        google.maps.event.addListener(map, 'dragstart', function() {
            map.setOptions({ draggableCursor: 'grabbing' });
        });

        google.maps.event.addListener(map, 'dragend', function() {
            map.setOptions({ draggableCursor: 'default' }); 
        });
    }

    // Inicializa el componente de autocomplete
    function initAutocomplete() {
        var addressInput = document.getElementById('addressInput');
        var autocomplete = new google.maps.places.Autocomplete(addressInput);
    }

    // Manda llamar el método showAddress al hacer click en el botón
    function showMapAddress(){
        var address = document.getElementById('addressInput').value;
        showAddress(address);
    }

    // Coloca el marcador según la dirección que se pase como parametro
    function showAddress( address ) {
        var geocoder = new google.maps.Geocoder();
            
        geocoder.geocode({ 'address': address }, function(results, status) {
            if ( status === 'OK' ) {
                var location = results[0].geometry.location;
                var position = {
                    lat: location.lat(),
                    lng: location.lng()
                };  
                
                map.setCenter(location);
                setMarkerPosition(position, id_sufix == "Inicial" ? iniMarker : finMarker);
            } else {
                alert('La dirección no se pudo encontrar: ' + status);
            }
        });
    }

    // Coloca el marcador según la ubicación y marcador que se envíe
    function setMarkerPosition(location, marker) {
        marker.setPosition(location);

        $('#mapLat').val(location.lat);
        $('#mapLng').val(location.lng);

        drawLine();

        if( iniMarker && finMarker ){
            let ini_pos = { lat: iniMarker.position.lat(), lng : iniMarker.position.lng() };
            let fin_pos = { lat: finMarker.position.lat(), lng : finMarker.position.lng() };
            let distance = calculateDistance( ini_pos, fin_pos );

            $("#line_distance").val( distance );
        }
    }

    // Almacena las coordenadas en los respectivos campos
    function saveCoords() {
        var lat = $('#mapLat').val();
        var lng = $('#mapLng').val();

        if ( lat === '' && lng === '' ) {
            alert('Selecciona la ubicación en el mapa para obtener las coordenadas.');
            return
        }

        $('input[name=Latitud_number_0_georeference_'+id_sufix+'_attr]').val(lat);
        $('input[name=Longitud_number_1_georeference_'+id_sufix+'_attr]').val(lng);
        $('#mapModal').modal('hide');

    }

    function showMapModal(sufix) {
        id_sufix = sufix;
        
        if( map == null )
            initMap();

        if( id_sufix == 'Inicial' ){    
            if( iniMarker == null )
                iniMarker = initMarker();

        } else if( id_sufix == 'Final') {
            console.log( "INICIAR SEGUNDO MARCADOR" );
            if( finMarker == null )
                finMarker = initMarker();
        }
        
        if ( validateFullAddress() ) {
            fullAddress = getFullAddress();
            console.log( fullAddress );
            showAddress( fullAddress );

        } else {
            var position = {
                lat: parseFloat(iniLat),
                lng: parseFloat(iniLng),
            };

            if( id_sufix == "Inicial" && iniMarker == null ){
                setMarkerPosition(position, iniMarker );
            } else if( id_sufix == "Final" && finMarker == null ){
                setMarkerPosition(position, finMarker );
            }

            // setMarkerPosition(positikon, id_sufix == "Inicial" ? iniMarker : finMarker);
            map.setCenter(position);
        }

        $('#mapModal').modal('show');
    }

    // Valida que la dirección esté completa
    function validateFullAddress() {
        var street = $('input[name=Calle_text_0_address_attr]').val();
        var extNumber = $('input[name=No_Ext_number_1_address_attr]').val();
        var intNumber = $('input[name=No_Int_number_2_address_attr]').val();
        var zipCode = $('select[name=C_P__select_3_address_attr]').val();
        var suburb =  $('select[name=Colonia_select_4_address_attr]').val();

        if ( (street === '' || street === null || street === undefined ) &&
            ( extNumber === '' || extNumber === null || extNumber === undefined ) &&
            ( suburb === '' || suburb === null || suburb === undefined ) &&
            ( zipCode == null || zipCode.length < 5 || zipCode === undefined) ) {
            // showWarningModal('Llena correctamente los campos de dirección para continuar');
            return false;
        }

        return true;
        
    }

    // Obtiene la dirección completa
    function getFullAddress() {
        var street = $('input[name=Calle_text_0_address_attr]').val();
        var extNumber = $('input[name=No_Ext_number_1_address_attr]').val();
        var intNumber = $('input[name=No_Int_number_2_address_attr]').val();
        var zipCode = $('select[name=C_P__select_3_address_attr]').val();
        var suburb =  $('select[name=Colonia_select_4_address_attr]').val();
        var location =  'Atizapán de Zaragoza Estado de México';

        return street + ' '  
            + extNumber + ' ' 
            + intNumber  + ' ' 
            + suburb + ' ' 
            + zipCode + ' ' 
            + location;
            //' Atizapán de Zaragoza Estado de México';
    }

    function drawLine(){
        if( iniMarker && finMarker ){
            if( line == null ){
                line = new google.maps.Polyline({
                    path: [iniMarker.position, finMarker.position],
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                });

                line.setMap(map);
            } else{
                line.setPath( [iniMarker.position, finMarker.position] );
            }
        }
    }

</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=places"></script>
