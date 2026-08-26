let map;

$(document).ready(function() {

    $("#plan-search-input").on("input", function(){
        var searchText = $(this).val().toLowerCase();
        
        filterOptions( searchText );
    });

    $("#generate_map_btn").on("click", function () {
        let route = $(this).attr('href');
        let zoom = map.getZoom();
        let lat = map.getCenter().lat();
        let lng = map.getCenter().lng();
        let id_plans = getActivePlans();

        route += `?zoom=${zoom}&lat=${lat}&lng=${lng}&id_plans=${id_plans}`;
        window.open(route, "_blank");
    });

});

// Obtiene los ID's de los inventarios activos en el mapa
function getActivePlans(){
    let id_plans = [];

    $(".plan-checkbox").each( function () {
        if( $(this).is(":checked") ){
            id_plans.push( $(this).val() );
        }
    });

    return id_plans.join(',');
}

// Inicializar mapa
async function initMap() {
    let centerLocation = { lat: Number(start_lat), lng: Number(start_lng) };

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: Number(start_zoom),
        center: centerLocation,
        draggableCursor: "default",
        mapTypeId: "satellite",
        disableDefaultUI: window.location.href.includes('generar-mapa'),
        styles: setMapStyles(),
    });

    await getAtizapanBounds(map);
}

// Coloca los estilos en el mapa para eliminar las landmarks
function setMapStyles() {
    return [
        {
            featureType: "administrative",
            elementType: "geometry",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "poi",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "road",
            elementType: "labels.icon",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "transit",
            stylers: [{ visibility: "off" }],
        },
    ];
}

// Ajusta la vista del mapa a los límitesd de Atizapán
async function fitAtizapanBounds(map) {
    var northEast = new google.maps.LatLng(
        19.612226459442805,
        -99.21042012814358
    );

    var southWest = new google.maps.LatLng(
        19.515617135661422,
        -99.35653201255865
    );

    cityBounds = await new google.maps.LatLngBounds(southWest, northEast);
    map.fitBounds(cityBounds);
}

// Remueve todas los elementos seleccionados del mapa
async function cleanMap(){

    Object.keys(markers).forEach( id => {
        $(`#check-plan-${id}`).prop('checked', false).change();
    });

    Object.keys(polylines).forEach( id => {
        $(`#check-plan-${id}`).prop('checked', false).change();
    });
}

/****************** EVENTOS PARA CONTROLAR LA COLOCACIÓN Y REMOCIÓN DE MARADORES Y LÍNEAS EN EL MAPA *****************/

let markers = [];
let polylines = [];
let infoWindow;

// Envía la petición al servidor para obtener la información del inentario
function getInfoInventory(id_plan, arrayMarkers, arrayLines){
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['map.inventory-info'].replace('id_plan', id_plan),
        type: 'GET',
        data: {
            '_token' : _token,
        },
        success: function(response){
            closeInfoWindow();
            if( response.geometry == 'Point' )
                setPointsInMap( response.rows, response.plan, arrayMarkers );
            else if( response.geometry == 'LineString' )
                setLinesInMap( response.rows, response.plan, arrayLines );
        },
        error: function(xhr, status, errors){

        },
        complete: function(){
            $loading.hide();
        }
    });
}

// Coloca las polilíneas en el mapa
function setLinesInMap(rows, plan, linesArray) {
    linesArray[plan.id] = [];

    infoWindow = new google.maps.InfoWindow();

    $.each(rows, function (index, row) {
        var linePath = [
            getCoors(row.georeference["_Inicial"], "Inicial"),
            getCoors(row.georeference["_Final"], "Final"),
        ];

        if (!linePath.includes(null)) {
            var planLine = new google.maps.Polyline({
                id_line: row.id,
                path: linePath,
                geodesic: true,
                strokeColor: plan.color.includes("#")
                    ? plan.color
                    : `#${plan.color}`,
                strokeOpacity: 1.0,
                strokeWeight: 6,
            });

            var contentString = getFieldsAndValues(
                plan.attributes,
                row.attributes,
                row,
                plan
            );
            var distance = `<p class="fs-5 m-0 p-0 info-card-title" > <b> Longitud: </b>
                    ${calculateDistance(linePath[0], linePath[1])} metros </p>
                    <hr class="border border-secondary border-bottom-2 my-2">`;

            contentString = distance + contentString;

            planLine.addListener("click", function (event) {
                let position = {
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                };

                clickEventHandle(contentString, plan.name, position);
            });

            linesArray[plan.id][row.id] = planLine;
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

// Obtiene una cadena de texto que contiene tanto el nombre como valor del campo
function getFieldsAndValues(attrs, attrValues, row, plan, sufix = "gen") {
    var fieldsHTML = "";

    if (Array.isArray(attrs)) {
        $.each(attrs, function (key, attr) {
            if (typeof attr === "object" && attr !== null) {
                if (attr.type != "button" && attr.deleted_at == null) {
                    var attrKey = `${attr.title
                        .replace(/\./g, "_")
                        .replace(/ /g, "")}_${attr.type}_${key}_${sufix}_attr`;

                    if (attrValues[attrKey]) {
                        var value = attrValues[attrKey];
                        fieldsHTML += `<p class="fs-5 m-0 p-0 info-card-title" > <b> ${attr["title"]}: </b>`;

                        if (attr.type.toLowerCase().includes("image")) {
                            fieldsHTML += `<img src="${value}" class="w-100 rounded-2" width="100%" alt="${attr["title"]}" /> </p> `;
                        } else if (attr.type.toLowerCase().includes("file")) {
                            fieldsHTML += `<a href="${value}" target="_blank" class="fs-5 fst-italic m-0 p-0 info-card-data" target="_blank" download> 
                                            <i class="bi bi-file-earmark-text"></i> ${attr["title"]}
                                        </a>
                                    </p>`;
                        } else if (sufix.includes("georeference")) {
                            fieldsHTML += `<span class="text-dark"> ${value} </span></p>`;
                        } else {
                            fieldsHTML += `</p> 
                                    <p class="fs-5 m-0 p-0 info-card-data"> 
                                        ${value} 
                                    </p>`;
                        }
                    }
                }
            } else {
                if (typeof attr == "string") {
                    var splittedArray = attr.split("_");
                    var geoSufix = attr;
                    var geoValues;
                    var geoAttrs;

                    if (splittedArray.length >= 2) {
                        geoAttrs =
                            plan[splittedArray[0]]["_" + splittedArray[1]];
                        geoValues =
                            row[splittedArray[0]]["_" + splittedArray[1]];
                    } else {
                        geoAttrs = Object.values(plan[attr]); // plan[ attr ]; //
                        geoValues = row[attr];
                    }

                    var hr =
                        '<hr class="border border-secondary border-bottom-2 my-2">';

                    fieldsHTML +=
                        hr +
                        getFieldsAndValues(
                            geoAttrs,
                            geoValues,
                            row,
                            plan,
                            geoSufix
                        ) +
                        hr +
                        "<br>";
                }
            }
        });
    }

    return fieldsHTML;
}

// Método que muestra la ventana de información
function clickEventHandle(contentString, headerTitle, position) {
    if (infoWindow) {
        let route = createRouteElement(position.lat, position.lng);
        contentString = route + contentString;

        infoWindow.setHeaderContent(headerTitle);
        infoWindow.setContent(contentString);
        infoWindow.setPosition(position);
        infoWindow.open(map);
    }
}

// Coloca los puntos en el mapa
function setPointsInMap(rows, plan, pointArray) {
    console.log(plan);
    pointArray[plan.id] = [];
    infoWindow = new google.maps.InfoWindow();

    $.each(rows, function (index, row) {
        var markerPosition = getCoors(row.georeference["_Inicial"], "Inicial");
        console.log(row);

        if (markerPosition != null) {
            var marker = new google.maps.Marker({
                position: markerPosition,
                id_point: row.id,
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

            var contentString = getFieldsAndValues(
                plan.attributes,
                row.attributes,
                row,
                plan
            );

            marker.addListener("click", function (event) {
                clickEventHandle(contentString, plan.name, markerPosition);
            });

            pointArray[plan.id][row.id] = marker;
            marker.setMap(map);
        }
    });
}

// Remueve las polilíneas del mapa
function removeInventoryLines(id_plan, arrayLines ) {
    let planLines = arrayLines[id_plan];

    $loading.show();

    const linesToRemove = Object.values(planLines);
    const chunkSize = 250; // ajusta según tu rendimiento
    let index = 0;

    function removeChunk() {
        const end = Math.min(index + chunkSize, linesToRemove.length);

        for (let i = index; i < end; i++) {
            linesToRemove[i].setMap(null);
        }

        index = end;

        if (index < linesToRemove.length) {
            // deja respirar al navegador
            setTimeout(removeChunk, 0);
        } else {
            // limpieza final
            arrayPoints[id_plan] = [];
            delete arrayPoints[id_plan];

            $loading.hide();
        }
    }

    setTimeout( removeChunk, 0 );

    // if (planLines) {
    //     Object.values(planLines).forEach((line) => {
    //         if (line) {
    //             line.setMap(null);
    //         }
    //     });

    //     arrayLines[id_plan] = [];
    //     delete arrayLines[id_plan];
    // }
    
    $loading.hide();
}

// Remueve los puntos de los inventarios del mapa
function removeInventoryPoints(id_plan, arrayPoints) {
    let planMarks = arrayPoints[id_plan];

    $loading.show();

    const markersToRemove = Object.values(planMarks);
    const chunkSize = 250; // ajusta según tu rendimiento
    let index = 0;

    function removeChunk() {
        const end = Math.min(index + chunkSize, markersToRemove.length);

        for (let i = index; i < end; i++) {
            markersToRemove[i].setMap(null);
        }

        index = end;

        if (index < markersToRemove.length) {
            // deja respirar al navegador
            setTimeout(removeChunk, 0);
        } else {
            // limpieza final
            arrayPoints[id_plan] = [];
            delete arrayPoints[id_plan];

            $loading.hide();
        }
    }

    setTimeout( removeChunk, 0 );

    // if (planMarks) {
    //     Object.values(planMarks).forEach((marker) => {
    //         if (marker) {
    //             marker.setMap(null);
    //         }
    //     });

    //     arrayPoints[id_plan] = [];
    //     delete arrayPoints[id_plan];
    // }

    // $loading.hide();
}

// Crea un botón para llevar a la dirección
function createRouteElement(lat, lng) {
    return `<a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving" target="_blank"
            class="btn btn-sm btn-secondary mb-3  d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-route"></i>&nbsp;&nbsp;<p>Cómo llegar</p>
        </a>`;
}

// Cierr la ventana de información tanto para líneas como para puntos
function closeInfoWindow() {
    if (infoWindow) infoWindow.close();
}
