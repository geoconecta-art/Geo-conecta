$(document).ready(function (){
    updateTable();
});

// Emite el evento parta actualizar la tabla de datos de inventarios
function updateTable(){
    $loading.show();

    var dom_normal = '<"row justify-between m-0 gy-2 px-3 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

    $("#plans_table").DataTable({
        ajax: window.Laravel.routes['plans.update.table'],
        columns: [
            { data: 'index', name: 'index' },
            { data: 'name', name: 'name' },
            { data: 'type_plan', name: 'type_plan' },
            { data: 'created_at', name: 'created_at'},
            { data: 'area', name: 'area' },
            { data: 'subarea', name: 'subarea' },
            { data: 'rows', name: 'rows' },
            { data: 'actions', name: 'actions' },
        ],
        fnInitComplete: function() {
            $loading.hide();
        },
        destroy: true,
        responsive: false,
        autoWidth: false,
        dom: dom_normal,
        paginate: false,
        language: spanish,
    });
}

// Emite el evento para mostrar el mensaje de confirmación para eliminar un registro
function confirmDelete(id_plan) {
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['plan.confirm-delete'].replace("id_plan", id_plan),
        type: "GET",
        data: {
            _token: _token,
        },
        success: function (response) {
            $("#basicModal .modal-content").html(response);
            $("#basicModal .modal-dialog").addClass("modal-lg");

            $basicModal.modal("show");
        },
        error: function (xhr) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Emite la petición para eliminar el inventario
function deletePlan(id){
    $loading.show();
    let _token = $token.val();
   
    $.ajax({
        url: window.Laravel.routes['plan.delete'].replace('id_plan', id),
        // '/admin/inventarios/' + id + '/delete-form',
        type: 'DELETE',
        data: {
            '_token':_token,
        },
        success: function(data) {
            $loading.hide();
            var response = `Inventario <b>${data.name}</b> eliminado. <br>
            <b>${data.deletedRows}</b> datos eliminados.<br> 
            <b>${data.deletedMaps}</b> mapas eliminados.<br> 
            <b>${data.updatedMaps}</b> mapas actualizados.`;

            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_plan_cancel_btn").hide();
            $("#delete_plan_accept_btn").hide();

            setTimeout(() => {
                updateTable();
                $basicModal.modal('hide');
            }, 2500);
        },
        error: function (xhr, status, error){
            $loading.hide();
            var response = `Lo sentimos hubo un error al intentar procesar su petición.`;
            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_plan_cancel_btn").hide();
            $("#delete_plan_accept_btn").hide();

            setTimeout(() => {
                $basicModal.modal('hide');
            }, 2500);
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

// Pone en mayúsculas el nombre del inventario
function convertToUppercase( inputId ){
    let input = document.getElementById( inputId );

    input.value = normalize(input.value).toUpperCase();
}

// 
var normalize = (function() {
    var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÇç",
        to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuucc",
        mapping = {};

    for (var i = 0, j = from.length; i < j; i++ )
        mapping[ from.charAt( i ) ] = to.charAt( i );
    
    return function( str ) {
        var ret = [];
        
        for( var i = 0, j = str.length; i < j; i++ ) {
            var c = str.charAt( i );
            
            if( mapping.hasOwnProperty( str.charAt( i ) ) )
                ret.push( mapping[ c ] );
            else
                ret.push( c );
        }

        return ret.join( '' );
    }
})();