$(document).ready(function() {
    
    // Eventos para la tab de inventarios
    $(".plan-checkbox").on("change", function(){
        var id_plan = $(this).val();
        
        if( $(this).is(':checked') ){
            getInfoInventory( id_plan, markers, polylines );
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

    $("#plan-search-input").on("input", function(){
        var searchText = $(this).val().toLowerCase();
        
        filterOptions( searchText );
    });
});

// Filtra las direcciones, subdirecciones e inventarios
function filterOptions( searchText ){
    searchText = searchText.toLowerCase();

    $('.accordion-plan-area').each(function() {
        let area = $(this);
        let areaTitle  = area.find('.accordion-head .title').text().toLowerCase();
        
        if (areaTitle.includes(searchText)) {
            area.show();
            $("#accordion-plan-area-" + area.attr('id')).collapse('show');
        } else {
            $(this).hide();
            $("#accordion-plan-area-" + area.attr('id')).collapse('hide');
        }

        area.find('.accordion-plan-subarea').each( function() {
            let subarea = $(this);
            let subTitle  = subarea.find('.accordion-head .title').text().toLowerCase();

            if( subTitle.includes(searchText) ){
                area.show();
                $("#accordion-plan-area-" + area.attr('id')).collapse('show');
                subarea.show();
                $("#accordion-plan-subarea-" + subarea.attr('id')).collapse('show');
            } else {
                subarea.hide();
                $("#accordion-plan-subarea-" + subarea.attr('id')).collapse('hide');
            }

            subarea.find('.plan-checkbox-container').each(function() {
                let checkbox = $(this);
                let checkboxTitle = checkbox.find('.checkbox-row .custom-checkbox .custom-control-label').text().toLowerCase().trim();

                if( checkboxTitle.includes(searchText) ){
                    area.show();
                    $("#accordion-plan-area-" + area.attr('id')).collapse('show');
                    subarea.show();
                    $("#accordion-plan-subarea-" + subarea.attr('id')).collapse('show');
                    checkbox.show();
                    checkbox.addClass('d-flex');
                } else {
                    checkbox.hide();
                    checkbox.removeClass('d-flex');
                }
            });
        });
    });
}

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

// Muestra modal para crear mapa
function showModal(type, id_map = null){
    $loading.show();
    let _token = $token.val();
    let plan_ids = [ ...Object.keys(markers), ...Object.keys(polylines) ];
    
    $.ajax({
        url: window.Laravel.routes['map.modal'],
        type: 'GET',
        data: {
            token: _token,
            type: type,
            plan_ids: plan_ids,
            id_map: id_map,
        },
        success: function(data){
            $('#basicModal .modal-content').html(data);
            $('#basicModal .modal-dialog').addClass('modal-lg');
            $basicModal.modal('show');
        },
        error: function(xhr, status, error) {},
        complete: function(xhr, status) {
            $loading.hide();
        }
    });
}

// Emite el evento para crear un mapa
function createMap(){
    $loading.show();
    let _token = $token.val();
    let id_plans = [];
    let name_map = $("input[name=name_map]").val();

    $('input[name="id_plans[]"]').each(function() {
        id_plans.push($(this).val());
    });

    $.ajax({
        url: window.Laravel.routes['map.create'],
        type: 'POST',
        data: {
            '_token': _token,
            'name_map': name_map,
            'id_plans': id_plans,
        },
        success: function(){
            $basicModal.modal('hide');
            location.reload();
        },
        error: function(xhr, status, error) {},
        complete: function(xhr, status) {
            $loading.hide();
        }
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

// Remueve todas los elementos seleccionados del mapa
function cleanMap(){

    Object.keys(markers).forEach( id => {
        $(`#check-plan-${id}`).prop('checked', false).change();
    });

    Object.keys(polylines).forEach( id => {
        $(`#check-plan-${id}`).prop('checked', false).change();
    });
}