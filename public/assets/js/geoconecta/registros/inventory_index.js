let selectedValues = [];

$(document).ready(function () {
    $(".table").DataTable({
        language: spanish,
        paginate: false,
        scrollY: "48vh",
        scrollX: true,
    });
});

// Abre el modal para importar datos
function openModalImport(type) {
    $loading.show();

    $.ajax({
        url: window.Laravel.routes['register.inventory.import-modal'].replace(":id", id_plan).replace(":type", type),
        type: "GET",
        data: { id_plan: id_plan },
        success: function (data) {
            $("#basicModal .modal-content").html(data);
            $("#basicModal .modal-dialog").addClass("modal-xl");

            $basicModal.modal("show");
        },
        error: function (xhr, status, errors) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Emite la petición para extraer los valores que corresponden a los
function getExcelHeaders(id_plan) {
    let formData = new FormData($("#import_excel_form")[0]);
    let _token = $token.val();
    formData.append("_token", _token);

    $loading.show();

    $.ajax({
        url: window.Laravel.routes['register.inventory.import-get-headers'].replace( "id_plan", id_plan ),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $("#coors-headers-container").html(response);
        },
        error: function (xhr, status, errors) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Emite la petición para importar el archivo
function importFile(route) {
    let _token = $token.val();
    $loading.show();

    let formData = new FormData($("#import_excel_form")[0]);
    formData.append("_token", _token);

    $.ajax({
        url: route,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            location.reload();
            $basicModal.modal("hide");
        },
        error: function (xhr, status, errors) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Emite el evento para mostrar el modal de exportación de PDF
function openExportPdfFileModal(){
    $loading.show();

    $.ajax({
        url: window.Laravel.routes['register.export-open-modal'].replace('id_plan', id_plan),
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
function exportInventoryToPDF(){

    let formData = new FormData( $("#export_pdf_field_form")[0] );
    let type = $("#export_pdf_type_select").val();
    let _token = $token.val();

    formData.append('_token', _token);
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

// Emite el evento para mostrar el mensaje de confirmación para eliminar un registro
function confirmDelete(id_plan, id_row) {
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['register.inventory.delete-warning'].replace("id_plan", id_plan).replace("id_row", id_row),
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

// Emite el evento para eliminar el registro del inventario
function deletePlan(id_plan, id_row) {
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['register.inventory.delete'].replace("id_plan", id_plan).replace("id_row", id_row),
        type: "DELETE",
        data: {
            _token: _token,
        },
        success: function (data) {
            var response = `El registro seleccionado se ha eliminado éxitosamente del Inventario <b>${data.name}</b><br>`;

            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_plan_cancel_btn").hide();
            $("#delete_plan_accept_btn").hide();

            setTimeout(() => {
                location.reload();
            }, 2500);
        },
        error: function (xhr, status, error) {
            var response = `Lo sentimos hubo un error al intentar procesar su petición.`;

            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_plan_cancel_btn").hide();
            $("#delete_plan_accept_btn").hide();

            setTimeout(() => {
                $basicModal.modal("hide");
            }, 2500);
        },
        complete: function () {
            $loading.hide();
        },
    });
}
