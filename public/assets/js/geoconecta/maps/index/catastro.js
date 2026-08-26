let loteMarkers = []; // Almacena los marcadores relacionados a un lote
let loteLines = []; // Almacena las líneas relacionadas a un lote
let lotesLayer = []; // Almacena los polígonos correspondientes a capas de catastro
let lotesInfo = []; // Almacena la información de los lotes seleccionados
let page = 1; // Controla el número de página en la que se encuentra la extracciónd de claves catastrales
let loading = false;

$(document).ready(function () {
    addCheckHandle();
});

// Eventos para controlar las acciones de los checkbox
function addCheckHandle() {
    $(document).off("change", ".lote-checkbox");

    $(document).on("change", ".lote-checkbox", async function () {
        let clave = $(this).val();
        let checked = $(this).is(":checked");

        if (checked) {
            if( !(clave in lotesLayer) ){
                await getLotePolygon(clave);
            } else {
                lotesLayer[clave].setMap(map);
                lotesLayer[clave].setVisible(true);
                fitLotesView(map);
                await moveLoteToContainer( clave, "#lotes-list", "#lotes-selected-list", true );
                await getRowsInfo(clave); 
            }
        } else {
            await removeCatastroPolygon(map, clave);
            moveLoteToContainer( clave, "#lotes-selected-list", "#lotes-list", false );
        }
    });
}

// Realiza la búsqueda de datos, enviando peticiones ajax
$("#input-catastro-search").on("input", function () {
    let query = $(this).val();
    let avoidingLotes = [];

    Object.entries(lotesLayer).forEach(([key, lote]) => {
        if (lote.getMap()) {
            avoidingLotes.push(key);
        }
    });

    if (query.length >= 4 || query.length == 0) {
        page = 1;
        loading = false;
        $("#catastro-container").empty("");
        $loading.show();

        $.ajax({
            url: window.Laravel.routes["map.search.lote"],
            method: "GET",
            data: {
                query: query,
                page: page,
                avoidingLotes: avoidingLotes,
            },
            success: function (response) {
                $("#catastro-container").html(response);
                addCheckHandle();
            },
            error: function (xhr, status, errors) {},
            complete: function (xhr, status) {
                $loading.hide();
            },
        });
    }
});

// Función para manejar el scroll infinito
$("#catastro-container").scroll(function () {
    if (loading) return;

    let nearBottom =
        $("#catastro-container").scrollTop() +
            $("#catastro-container").height() >=
        $("#catastro-container")[0].scrollHeight - 100;

    if (nearBottom) {
        loading = true;
        $loading.show();

        $.ajax({
            url: window.Laravel.routes["map.catastro"],
            method: "GET",
            data: { page: ++page },
            success: function (response) {
                $("#catastro-container").append(response);
                loading = false;
                addCheckHandle();
            },
            error: function (xhr, status, error) {},
            complete: function () {
                $loading.hide();
            },
        });
    }
});

// Obtiene la información polígonal del lote selecionado
function getLotePolygon(clave){

    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes["map.get-lote"],
        type: "GET",
        data: { 
            _token: _token,
            clave: clave 
        },
        success: async function (response) {
            lotesLayer[clave] = new google.maps.Polygon({
                paths: response.coors,
                strokeColor: '#FF0000',
                strokeWeight: 2,
                fillColor: 'transparent',
                map: map,
            });

            fitLotesView(map);
            await moveLoteToContainer( clave, "#lotes-list", "#lotes-selected-list", true );
            await getRowsInfo(clave); 
        },
        error: function(chr, status, errors){

        },
        complete: function(){
            $loading.hide();
        }

    })

}

// Mueve un elemento de un contenedor a otro
function moveLoteToContainer(id_lote, containerFrom, containerTo, action) {
    if (action) {
        $(`${containerFrom} #li-lote-${id_lote}`).addClass("d-none");
        let loteLi = $(`<li id="li-lote-${id_lote}"></li>`).addClass(
            "custom-control custom-control-sm custom-checkbox w-100 mb-2"
        );
        let newAccordion = createLoteAccordion(id_lote);

        loteLi.appendTo(containerTo);
        loteLi.append(newAccordion);
    } else {
        $(`${containerFrom} #li-lote-${id_lote}`).remove();
        $(`${containerTo} #lote-${id_lote}`).prop("checked", false);
        $(`${containerTo} #li-lote-${id_lote}`).removeClass("d-none");
    }
}

// Crea el elemento HTML para el acordión del lote
function createLoteAccordion(clave) {
    return `
        <div class="accordion w-100" id="accordion-${clave}">
            <div class="accordion-item w-100" id="${clave}">
                <div class="d-flex accordion-head p-1 collapsed w-100">

                    <div class="custom-control custom-control-sm custom-checkbox mb-2 px-0">
                        <input type="checkbox" class="custom-control-input lote-checkbox" value="${clave}" id="check-selected-lote-${clave}" checked>
                        <label class="custom-control-label w-auto" for="check-selected-lote-${clave}"></label>
                    </div>

                    <a href="" class="text-dark" data-bs-toggle="collapse" data-bs-target="#accordion-lote-${clave}">
                        <label class="custom-contro-label w-100 mb-0">
                            ${clave}
                        </label>
                        <span class="accordion-icon"></span>
                    </a>
                </div>
                
                <div class="accordion-body collapse" data-bs-parent="#accordion-${clave}" id="accordion-lote-${clave}">
                    <ul id="selected-${clave}-list-plans">
                    </ul>
                </div>
            </div>
        </div>`;
}

// Emite el evento para obtener la información de los registros
async function getRowsInfo(clave) {
    $loading.show();

    await $.ajax({
        url: window.Laravel.routes["map.rowsByLote"],
        type: "GET",
        data: { clave: clave },
        success: async function (response) {
            await filterRowsByLote(response[0], clave);
            addPlanChecksToLote(clave);
        },
        error: function (xhr, status, error) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Filtrar registros por polígono
async function filterRowsByLote(rows, clave) {
    let polygon = lotesLayer[clave];
    let points = [];
    let lines = [];

    await rows.forEach((row) => {
        let pos_1 = getCoors(row.georeference["_Inicial"], "Inicial");
        let pos_2 = getCoors(row.georeference["_Final"], "Final");
        let status_1 = (status_2 = false);

        if (pos_1 && polygon) {
            status_1 = google.maps.geometry.poly.containsLocation(
                pos_1,
                polygon
            );
        }

        if (pos_2 && polygon) {
            status_2 = google.maps.geometry.poly.containsLocation(
                pos_2,
                polygon
            );
        }

        if ((status_1 || status_2) && pos_2 != null) {
            lines[row.id_plan] = lines[row.id_plan] || [];
            lines[row.id_plan].push(row);
            lines[row.id_plan]["planeacion"] =
                lines[row.id_plan]["planeacion"] || row.planeacion;
        } else if (status_1 && pos_2 == null) {
            points[row.id_plan] = points[row.id_plan] || [];
            points[row.id_plan].push(row);
            points[row.id_plan]["planeacion"] =
                points[row.id_plan]["planeacion"] || row.planeacion;
        }
    });

    Object.entries(points).forEach(([key, plan]) => {
        setPointsInMap(plan, plan["planeacion"], loteMarkers);
    });

    Object.entries(lines).forEach(([key, plan]) => {
        setLinesInMap(plan, plan["planeacion"], loteLines);
    });

    lotesInfo[clave] = [];
    lotesInfo[clave]["points"] = points;
    lotesInfo[clave]["lines"] = lines;
}

// Agrega los checkbox asociados a un lote a su correspondiente accordión
function addPlanChecksToLote(clave) {
    let plans = lotesInfo[clave];
    let htmlCheckPlans =
        createPlanCheckByLote(plans, "points", clave) +
        createPlanCheckByLote(plans, "lines", clave);

    $(`#selected-${clave}-list-plans`).append(htmlCheckPlans);
    addSelectedLotePlanHandle();
}

// Regresa una cadena de texto que representa los checkbox relacionados a un lote
function createPlanCheckByLote(plans, arrayLote, clave) {
    let htmlCheckPlans = "";

    Object.entries(plans[arrayLote]).forEach(([key, itemPlan]) => {
        let plan = itemPlan.planeacion;

        htmlCheckPlans += `<li class="custom-control custom-control-sm custom-checkbox w-100 mb-2">
                <input type="checkbox" class="custom-control-input check-selected-lote-plan" id="check-selected-lote-plan-${plan._id}" clave_lote="${clave}" id_plan="${plan._id}" value="${plan._id}" checked>
                <label class="custom-control-label w-100" for="check-selected-lote-plan-${plan._id}">
                    <span class="text-truncate w-100 catastro-title text-dark" style="display: block !important;">
                        ${plan.name}
                    </span>
                </label>
            </li>`;
    });

    return htmlCheckPlans;
}

// Agrega el evento de cambio para los checkbox asociados a los lotes
function addSelectedLotePlanHandle() {
    $(document).off("change", ".check-selected-lote-plan");

    $(".check-selected-lote-plan").on("change", function () {
        let clave = $(this).attr("clave_lote");
        let id_plan = $(this).attr("id_plan");
        let status = $(this).is(":checked");

        swapLote(clave, id_plan, status, "points", loteMarkers);
        swapLote(clave, id_plan, status, "lines", loteLines);
    });
}

// Muestra el polígono del lote seleccionado
// async function fitMapViewByLote(clave, map) {
    
//     if( dataLayer ){
//         dataLayer.forEach(feature => {
//             let clve = feature.getProperty('CLVE_CAT');

//             if (  clve == clave ) {
//                 let path = [];
                
//                 feature.getGeometry().forEachLatLng( latLng => {
//                     path.push(latLng);
//                 });
                
//                 lotesLayer[clve] = new google.maps.Polygon({
//                     paths: path,
//                     strokeColor: '#FF0000',
//                     strokeWeight: 2,
//                     fillColor: '#FF0000',
//                     map: map,
//                 });
//             }
//         });
//     }

//     fitLotesView(map);
// }


// Ajusta la vista del mapa a la del lote seleccionado
async function fitLotesView(map) {
    let bounds = await new google.maps.LatLngBounds();

    Object.values(lotesLayer).forEach((lote) => {
        if (lote.getMap()) {
            lote.getPath().forEach(function (latLng) {
                bounds.extend(latLng);
            });
        }
    });

    map.fitBounds(bounds);
}

// Muestra u oculta las líneas o puntos asociadas a una clave catastral
// arrayLote indica si se trata del arreglo de líneas o de marcadores
// arrayMap indica si se trata del arreglo de marcadores o líneas
function swapLote(clave, id_plan, swap, arrayLote, arrayMap) {
    if (lotesInfo[clave][arrayLote][id_plan]) {
        lotesInfo[clave][arrayLote][id_plan].forEach((item) => {
            if (arrayMap[id_plan][item._id]) {
                arrayMap[id_plan][item._id].setMap(swap ? map : null);
            }
        });
    }
}

// Remueve los polígonos de los lotes
function removeLotes() {
    $("#lotes-selected-list li input").each(function () {
        $(this).prop("checked", false).change();
    });
}
// Remueve el polígono correspondiente a catastro con los
async function removeCatastroPolygon(map, clave) {
    if (lotesLayer[clave]) {
        lotesLayer[clave].setVisible(false);
        await lotesLayer[clave].setMap(null);
        
        removeLoteInfoFromMap(clave, map, "points", loteMarkers);
        removeLoteInfoFromMap(clave, map, "lines", loteLines);

        // delete lotesLayer[clave];
        let areActiveLote = Object.values(lotesLayer).some(
            (lote) => lote.getMap() != null
        );

        if (areActiveLote) await fitLotesView(map);
        else fitAtizapanBounds(map);
    }
}

// Remueve los puntos y/o líneas asociados a una clave catastral del mapa
function removeLoteInfoFromMap(clave, map, arrayLote, arrayMap) {
    if (lotesInfo[clave][arrayLote]) {
        Object.entries(lotesInfo[clave][arrayLote]).forEach(([key, plan]) => {
            plan.forEach((item) => {
                if (arrayMap[key][item._id]) {
                    arrayMap[key][item._id].setMap(null);
                    delete arrayMap[key][item._id];
                }
            });
        });
    }
}

// let response = await fetch('/maps/CATASTRO_ATIZAPAN.geojson');
// let data = await response.json();
// let features = data.features;

// await features.forEach( feature => {
//     let properties = feature.properties;
//     let clave = properties.CLVE_CAT;
//     let geometry = feature.geometry;
//     let coors = geometry.coordinates[0];
//     let paths = coors[0].map(coor => {
//         return { lat: coor[1], lng: coor[0] };
//     });

//     lotesLayer[clave] = new google.maps.Polygon({
//         paths: paths,
//         strokeColor: '#FF0000',
//         strokeWeight: 2,
//         fillColor: 'transparent',
//     });
// });
