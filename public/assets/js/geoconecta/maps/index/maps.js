let infoMaps = [];
let mapPoints = [];
let mapPolylines = [];

$(document).ready(function () {
    $("#map-search-input").on("input", function () {
        var searchText = $(this).val().toLowerCase();

        filterMapOptions(searchText);
    });

    $(".map-checkbox").on("click", function () {
        var id_map = $(this).val();

        if ($(this).is(":checked")) {
            getInventoriesId(id_map);
        } else {
            removeInfoMaps(id_map);
        }
    });
});

// Permite filtrar los mapas con ayuda del buscador
function filterMapOptions(searchText) {
    searchText = searchText.toLowerCase();

    $(".accordion-map-area").each(function () {
        let area = $(this);
        let areaTitle = area
            .find(".accordion-head .title")
            .text()
            .toLowerCase();

        if (areaTitle.includes(searchText)) {
            area.show();
            $("#accordion-map-area-" + area.attr("id")).collapse("show");
        } else {
            $(this).hide();
            $("#accordion-map-area-" + area.attr("id")).collapse("hide");
        }

        area.find(".map-checkbox-container").each(function () {
            let checkbox = $(this);
            let checkboxTitle = checkbox
                .find(".custom-checkbox .custom-control-label")
                .text()
                .toLowerCase()
                .trim();

            if (checkboxTitle.includes(searchText)) {
                area.show();
                $("#accordion-map-area-" + area.attr("id")).collapse("show");
                checkbox.show();
                checkbox.addClass("d-flex");
            } else {
                checkbox.hide();
                checkbox.removeClass("d-flex");
            }
        });
    });
}

// Obtiene la información del mapa almacenado
function getInventoriesId(id_map) {
    $loading.show();
    infoMaps[id_map] = [];

    $.ajax({
        type: "GET",
        url: window.Laravel.routes['map.info'].replace("id_map", id_map),
        success: function (data) {
            data.forEach((element) => {
                getInfoInventory(element, mapPoints, mapPolylines);

                infoMaps[id_map].push(element);
            });
        },
        error: function (data) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Remueve la información correspondiente a un mapa del mapa
function removeInfoMaps(id_map) {
    $.each(infoMaps[id_map], function (key, id_plan) {
        removeInventoryLines(id_plan, mapPolylines);
        removeInventoryPoints(id_plan, mapPoints);
    });
}

// Emite la petición para editar un mapa
function editMap(id_map) {
    $loading.show();
    let _token = $token.val();
    let id_plans = [];
    let name_map = $("input[name=name_map]").val();

    $('input[name="id_plans[]"]').each(function () {
        id_plans.push($(this).val());
    });

    $.ajax({
        url: window.Laravel.routes['map.edit'].replace("id_map", id_map),
        type: "POST",
        data: {
            _token: _token,
            name_map: name_map,
            id_plans: id_plans,
        },
        success: function () {
            $basicModal.modal("hide");
            location.reload();
        },
        error: function (xhr, status, error) {},
        complete: function (xhr, status) {
            $loading.hide();
        },
    });
}

// Evento para compartir la URL del mapa
function shareMap(id_map) {
    let url = window.Laravel.routes['public.view-map'].replace( "id_map", id_map );
    let textArea = document.createElement("textarea");

    textArea.value = url;
    document.body.appendChild(textArea);
    textArea.select();
    textArea.setSelectionRange(0, 99999);
    document.execCommand("copy");
    document.body.removeChild(textArea);

    $("#map_msg_toast").text("Se ha copiado la URL al portapapeles.");
    $("#map_toast").toggleClass("show");

    setTimeout(() => {
        $("#map_toast").toggleClass("show");
    }, 2000);
}
