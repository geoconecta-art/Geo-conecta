$(document).ready(function () {
    // Eventos para la tab de inventarios
    $(".plan-checkbox").on("change", function(){
        let id_plan = $(this).val();
        let name = $(`#check-plan-${id_plan}-name`).text().trim();
        
        if( $(this).is(':checked') ){
            getInfoInventory( id_plan, markers, polylines );
            addPlanToSymbology(id_plan, name);
        } else {
            removePlanFromSymbology(id_plan);
            
            var geometry = $(this).attr('geometry');

            if( geometry == 'Point' )
                removeInventoryPoints(id_plan, markers);
            else
                removeInventoryLines(id_plan, polylines);
        }

        closeInfoWindow();
    });

    $("#print_map_btn").on("click", function (e) {
        e.preventDefault();
        let route = window.Laravel.routes['geovisor.print-view'];
        let zoom = map.getZoom();
        let lat = map.getCenter().lat();
        let lng = map.getCenter().lng();
        let type_map = $("input[name=typeMap]:checked").val();
        let name_dependency = $("#name_dependency").val();
        let key_map = $("#key_map").val();
        let name_map = $("#name_map").val();
        let id_plans = $("input[name='id_plans[]']:checked").map(function() {
            return $(this).val();
        }).get();

        route += `?zoom=${zoom}&lat=${lat}&lng=${lng}&type_map=${type_map}&dependency=${name_dependency}&key_map=${key_map}&name_map=${name_map}&id_plans=${id_plans}`;

        window.open( route, '_blank');
        // preLoadMap();
    });

    // Se encarga de cambiar el tipo de mapa que se visualiza
    $("input[name=typeMap]").on("change", function () {
        let typeMap = $(this).val();

        map.setMapTypeId(typeMap);
    });

    start_id_plans.forEach(id_plan => {               
        $(`#check-plan-${id_plan}`).prop('checked', true).change();
    });
});


//  Emite el evento para poder visualizar el PDF
function preLoadMap(){
    let formData = new FormData( $("#all_settings_map_form").get()[0] );
    let zoom = map.getZoom();
    let lat = map.getCenter().lat();
    let lng = map.getCenter().lng();
    
    let visibleMarkers = getVisibleMarkers();
    let visibleCity = getVisibleCityPolygon();
    let visibleLines = getVisiblePolylines();
    
    formData.append( 'visibleMarkers', JSON.stringify(visibleMarkers) );
    formData.append( 'visibleLines', JSON.stringify(visibleLines) );
    formData.append( 'visibleCity', JSON.stringify(visibleCity) );
    
    $loading.show();
    $.ajax({
        url: window.Laravel.routes['geovisor.pre-load'],
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function( data ) {
            let baseUrl = window.Laravel.routes['geovisor.print-index'];
            let params = new URLSearchParams();
            
            params.append('id_info', data.id_info);
            params.append('zoom', zoom);
            params.append('lat', lat);
            params.append('lng', lng);
            params.append('_token', data._token);

            let finalUrl = baseUrl + "?" + params.toString();
            window.open(finalUrl, "_blank");
        },
        error: function(xhr, status, errors){},
        complete: function(){ $loading.hide(); }
    });
}

//Obtiene el poligono correspondiente al municipio de atizapan para acoplar la vista
async function getAtizapanBounds(map) {
    map.data.loadGeoJson("/maps/1_LIMITE_ATIZAPÁN_DE_ZARAGOZA.geojson");

    map.data.setStyle({
        fillColor: "transparent",
        strokeColor: "red",
    });
}

// Remueve un elemento de la lista de simbología
function removePlanFromSymbology(planId){
    $(`#symbology-check-container li#li-${planId}`).remove();
}

// Agrega un elemento a la lista de simbología
function addPlanToSymbology(id_plan, name){
    let li = createLiElement(id_plan, name);
    $("#symbology-check-container").append(li);

    $(".btn-plan-delete").on("click", function () {
        let planId = $(this).attr('id');

        $(`#check-plan-${planId}`).prop('checked', false).change();
    });
}

// Genera un nuevo elemento LI para la lista de simbología
function createLiElement(id_plan, name){
    return `<li class="d-flex justify-content-between align-items-center w-100" id="li-${id_plan}">
        <span class="text-truncate">
            ${name}
        </span>
        <input type="checkbox" name="id_plans[]" id="check-plan-print-map-${id_plan}" value="${id_plan}" checked hidden>
        <div class="btn btn-plan-delete" id="${id_plan}">
            <em class="icon ni ni-cross"></em>
            </div>
    </li>`;
}

// Coloca los errores en caso que no se haya ingresado la información para generar el mapa
function setInputErrors(id_plans, dependency, key_map, map_name){
    if( dependency == '' )
        $("#error_msg_dependency").show();
    else 
        $("#error_msg_dependency").hide();

    if( key_map == '' )
        $("#error_msg_key_map").show();
    else
        $("#error_msg_key_map").hide();
    
    if( map_name == '' )
        $("#error_msg_map_name").show();
    else
        $("#error_msg_map_name").hide();

    if( id_plans == '' )
        $("#error_msg_symbology").show();
    else
        $("#error_msg_symbology").hide();
}

// Obtiene la información de los marcadores visibles en el mapa
function getVisibleMarkers() {
    let bounds = map.getBounds();
    let visibleMarkers = [];

    Object.values( markers ).forEach( plan => { 
        Object.values(plan).forEach( marker => {
            if( isPointInsideBounds( marker.getPosition() , bounds) ){
                let aux_pos = {
                    lat: marker.getPosition().lat(),
                    lng: marker.getPosition().lng(),
                    color: marker.icon.fillColor,
                };

                visibleMarkers.push(aux_pos);
            }
        });
    });

    return visibleMarkers;
}

// Obtiene la información de las líneas visibles en la vista del mapa
function getVisiblePolylines(){
    let bounds = map.getBounds();
    let visibleLines = [];

    Object.values( polylines ).forEach( plan => { 
        Object.values(plan).forEach( line => {
            let path = line.getPath();

            for( let i = 0; i < path.getLength(); i++){
                if( isPointInsideBounds(path.getAt(i), bounds) ){
                    let line_path = [
                        [
                            { 'lat' : path.getAt(0).lat(), 'lng' : path.getAt(0).lng() },
                            { 'lat' : path.getAt(1).lat(), 'lng' : path.getAt(1).lng()}
                        ],
                        line.strokeColor,
                    ];
                    
                    visibleLines.push( line_path );
                }
            }
            
        });
    });

    return visibleLines;
}

// Obtiene la información del área visible del polígono de la ciudad
function getVisibleCityPolygon(){
    let zoom = map.getZoom();
    map.setZoom(zoom - 1);
    let bounds = map.getBounds();
    let visibleCoords = [];

    map.data.forEach(function (feature) {

        if (feature.getGeometry().getType() === 'MultiPolygon') {
            let polygons = feature.getGeometry().getArray();

            polygons.forEach(function (polygon) {
                let coords = polygon.getArray();

                coords.forEach(function (preRing) {
                    let visibleRing = [];

                    Object.values(preRing).forEach( ring => {
                        ring.forEach(function (latLng) {
                            if (isPointInsideBounds(latLng, bounds)) {
                                let aux_pos = {
                                    lat: latLng.lat(),
                                    lng: latLng.lng(),
                                };

                                visibleRing.push(aux_pos);
                            }
                        }); 
                    });

                    // Si el anillo tiene coordenadas visibles, lo agregamos a los resultados
                    if (visibleRing.length > 0) {
                        visibleCoords.push(visibleRing);
                    }
                });
            });
        }
    });
    map.setZoom(zoom);

    return visibleCoords;
}

// Determina si un punto está dentro de los límites de la vista del mapa
function  isPointInsideBounds(point, bounds){
    return bounds.contains(point);
}
