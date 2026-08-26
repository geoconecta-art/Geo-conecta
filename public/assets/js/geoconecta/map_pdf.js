let markers = [];
let polylines = [];

async function initMap(){
    let centerLocation = { lat: Number(start_lat), lng: Number(start_lng) };

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: Number(start_zoom),
        center: centerLocation,
        draggableCursor: "default",
        mapTypeId: type_map,
        disableDefaultUI: true,
        gestureHandling: 'none',
        draggable: false,
        scrollwheel: false,
        zoomControl: false,
        disableDoubleClickZoom: true,
    });

    $loading.show();
    await getAtizapanBounds(map);
    await Promise.all(start_id_plans.map(plan => getInfoInventory(plan.id, markers, polylines)));
    $loading.hide();

}

//Obtiene el poligono correspondiente al municipio de atizapan para acoplar la vista
async function getAtizapanBounds(map) {
    map.data.loadGeoJson("/maps/1_LIMITE_ATIZAPÁN_DE_ZARAGOZA.geojson");

    map.data.setStyle({
        fillColor: "transparent",
        strokeColor: "red",
    });
}

// Envía la petición al servidor para obtener la información del inentario
async function getInfoInventory(id_plan, arrayMarkers, arrayLines){
    let _token = $token.val();

    await $.ajax({
        url: window.Laravel.routes['map.inventory-info'].replace('id_plan', id_plan),
        type: 'GET',
        data: {
            '_token' : _token,
        },
        success: async function(response){
            if( response.geometry == 'Point' )
                await setPointsInMap( response.rows, response.plan, arrayMarkers );
            else if( response.geometry == 'LineString' )
                await setLinesInMap( response.rows, response.plan, arrayLines );
        },
        error: function(xhr, status, errors){

        },
        complete: function(){
        }
    });
}

// Coloca las polilíneas en el mapa
function setLinesInMap(rows, plan, linesArray) {
    linesArray[plan._id] = [];

    $.each(rows, function (index, row) {
        var linePath = [
            getCoors(row.georeference["_Inicial"], "Inicial"),
            getCoors(row.georeference["_Final"], "Final"),
        ];

        if (!linePath.includes(null)) {
            var planLine = new google.maps.Polyline({
                id_line: row._id,
                path: linePath,
                geodesic: true,
                strokeColor: plan.color.includes("#")
                    ? plan.color
                    : `#${plan.color}`,
                strokeOpacity: 1.0,
                strokeWeight: 6,
            });

            linesArray[plan._id][row._id] = planLine;
            planLine.setMap(map);
        }
    });
}

// Obtiene las coordenadas de un arreglo en base a un sufix
function getCoors(values, sufix) {
    if (values) {
        var position = {
            lat: parseFloat(
                values["Latitud_number_0_georeference_" + sufix + "_attr"]
            ),
            lng: parseFloat(
                values["Longitud_number_1_georeference_" + sufix + "_attr"]
            ),
        };

        var hasNull = Object.values(position).some(function (valor) {
            return valor === null || valor === undefined || isNaN(valor);
        });

        return hasNull ? null : position;
    } else {
        return null;
    }
}

// Coloca los puntos en el mapa
function setPointsInMap(rows, plan, pointArray) {
    pointArray[plan._id] = [];

    $.each(rows, function (index, row) {
        var markerPosition = getCoors(row.georeference["_Inicial"], "Inicial");

        if (markerPosition != null) {
            var marker = new google.maps.Marker({
                position: markerPosition,
                id_point: row._id,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: plan.color.includes("#")
                        ? plan.color
                        : `#${plan.color}`,
                    fillOpacity: 1,
                    scale: 6,
                    strokeColor: "white",
                    strokeWeight: 2,
                },
            });

            pointArray[plan._id][row._id] = marker;
            marker.setMap(map);
        }
    });
}