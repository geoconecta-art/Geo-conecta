let polylines = [];
let markers = [];
let infoWindow;

function loopPlans() {
    plans.forEach((plan) => {
        if (plan.geometry == "Point")
            setPointsInMap(plan.registros, plan, markers);
        else if (plan.geometry == "LineString")
            setLinesInMap(plan.registros, plan, polylines);
    });
}

// Coloca las líneas en el mapa
async function setLinesInMap(rows, plan, linesArray) {
    linesArray[plan._id] = [];
    infoWindow = infoWindow ?? new google.maps.InfoWindow();

    await $.each(rows, async function (index, row) {
        var linePath = [
            getCoors(row.georeference["_Inicial"], "Inicial"),
            getCoors(row.georeference["_Final"], "Final"),
        ];

        if (!linePath.includes(null)) {
            var planLine = await new google.maps.Polyline({
                id_line: row._id,
                path: linePath,
                geodesic: true,
                strokeColor: plan.color.includes("#")
                    ? plan.color
                    : `#${plan.color}`,
                strokeOpacity: 1.0,
                strokeWeight: 6,
                zIndex: 1000,
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
                    lng: event.latLng.lng(),
                };
                clickEventHandle(contentString, plan.name, position);
            });

            linesArray[plan._id][row._id] = planLine;
            planLine.setMap(map);

            let address_1 = await getInfoMarker(linePath[0], row._id);
            let address_2 = await getInfoMarker(linePath[1], row._id);
            let index = $("#label-row-" + row._id)
                .text()
                .trim();
            let address =
                address_1 && address_2
                    ? address_1 + " - " + address_2
                    : address_1 ?? address_2;

            $("#label-row-" + row._id).text(address ?? index);
        }
    });
}

// Coloca los puntos en el mapa
function setPointsInMap(rows, plan, pointArray) {
    pointArray[plan._id] = [];
    infoWindow = infoWindow ?? new google.maps.InfoWindow();

    $.each(rows, async function (index, row) {
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

            var contentString = getFieldsAndValues(
                plan.attributes,
                row.attributes,
                row,
                plan
            );

            marker.addListener("click", function (event) {
                clickEventHandle(contentString, plan.name, markerPosition);
            });

            pointArray[plan._id][row._id] = marker;
            marker.setMap(map);

            let address = await getInfoMarker(markerPosition, row._id);
            let index = $("#label-row-" + row._id)
                .text()
                .trim();

            $("#label-row-" + row._id).text(address ?? index);
        }
    });
}

// Obtiene información del marcador en base a las coordenadas
async function getInfoMarker(position, id_row) {
    let geocoder = new google.maps.Geocoder();
    let address = null;

    await geocoder.geocode({ location: position }, function (results, status) {
        if (status === "OK") {
            address = results[0].formatted_address;
        }
    });

    return address;
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

// Cierr la ventana de información tanto para líneas como para puntos
function closeInfoWindow() {
    if (infoWindow) infoWindow.close();
}

// Crea un botón para llevar a la dirección
function createRouteElement(lat, lng) {
    return `<a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving" target="_blank"
            class="btn btn-sm btn-secondary mb-3  d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-route"></i>&nbsp;&nbsp;<p>Cómo llegar</p>
        </a>`;
}
