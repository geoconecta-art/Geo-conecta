let start_lat = 19.543454812522057;
let start_lng = -99.23502275276154;
let start_zoom = 20;

$(document).ready(function () {
    // Eventos para la tab de inventarios
    $(".plan-checkbox").on("change", function(){
        var id_plan = $(this).val();
        
        if( $(this).is(':checked') ){
            getInfoInventory( id_plan, markers, polylines );
        } else {
            // removePlanFromSymbology(id_plan);
            var geometry = $(this).attr('geometry');

            if( geometry == 'Point' )
                removeInventoryPoints(id_plan, markers);
            else
                removeInventoryLines(id_plan, polylines);
        }

        closeInfoWindow();
    });

    $(`#check-plan-${id_init_plan}`).prop('checked', true).change();
});

//Obtiene el poligono correspondiente al municipio de atizapan para acoplar la vista
async function getAtizapanBounds(map) {
    map.data.loadGeoJson("/maps/1_LIMITE_ATIZAPÁN_DE_ZARAGOZA.geojson");

    map.data.addListener("addfeature", async function (event) {
        fitAtizapanBounds(map);
    });

    map.data.setStyle({
        fillColor: "transparent",
        strokeColor: "red",
    });
}

// Emite el evento para mostrar el modal de exportación de PDF
function openExportPdfFileModal(id_plan){
    $loading.show();

    $.ajax({
        url: window.Laravel.routes['register.inventory.export-open-modal'].replace('id_plan', id_plan),
        type: 'GET',
        success: function (data) {
            $("#basicModal .modal-content").html(data);
            $("#basicModal .modal-dialog").addClass("modal-xl");

            $basicModal.modal("show");
        },
        error: function( xhr, status, errors){},
        complete: function(){
            $loading.hide();
        }
    });
}

// Emite el evento para generar el pedf con los encabezados seleccionados
function exportInventoryToPDF(id_plan){
    let formData = new FormData( $("#export_pdf_field_form")[0] );
    let type = $("#export_pdf_type_select").val();
    // let _token = $token.val();


    // formData.append('_token', _token);
    formData.append('type', type);

    $loading.hide();

    $.ajax({
        url : window.Laravel.routes['register.inventory.export-pdf'].replace('id_plan', id_plan),
        type : 'POST',
        data : formData,
        processData : false,
        contentType: false, 
        success: function(response){
            window.open(response.url, '_blank');
        },
        error: function(xhr, status, errors){},
        complete: function(){
            $loading.hide();
        }
    });
}