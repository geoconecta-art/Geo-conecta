// Abre el modal para crear un inventario desde un archivo CSV
function createInventoryOpenModal( event, route ){
    event.preventDefault();

    $loading.show();
    let _token = $token.val();

    $.get(route, { 
            '_token':_token,
    }, function(data) {
        $('#basicModal .modal-content').html(data);
        $('#basicModal .modal-dialog').addClass('modal-xl');

        $basicModal.modal('show');
    }).always(function () {
        $loading.hide();
    }); 
}

// Emite el evento para crear una planeación
function createPlaneacion() {
    hideErrorsMsg();

    $loading.show();

    let formData = new FormData($("#create_plan_form").get()[0]);
    let _token = $token.val();
    formData.append("_token", _token);
   
    $.post({
        url: window.Laravel.routes['plans.create'],
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            window.location.href = data.redirect;
        },
        error: function (xhr, status, error){
            if( xhr.status == 422 )
                showErrorsMsg(xhr);
        },
        complete: function () {
            $loading.hide();
        }
    });
}

// EMITE EL EVENTO PARA OBTENER LOS ENCABEZADOS DEL ARCHIVO CSV
function getHeadersFromCSV( formID ) {

    let formData = new FormData($(formID).get()[0]);
    let _token = $token.val();
    formData.append("_token", _token);

    $loading.show();

    $.ajax({
        url: window.Laravel.routes['plans.import-get-headers'],
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $("#crate_plan_coors_headers_container").html(response);
        },
        error: function (xhr, status, errors) {
            showErrorsMsg(xhr);
        },
        complete: function () {
            $loading.hide();
        },
    });
}


// EMITE EL EVENTO PARA CREAR EL INVENTARIO DESDE EL CSV
function createPlanFromCSV(){
    
    let formData = new FormData( $("#plan_from_csv_form").get()[0] );
    let _token = $token.val();

    formData.append( '_token', _token );

    $loading.show();

    $.ajax({
        url: window.Laravel.routes['plans.create-from-csv'],
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            closeModal();
            sendCSVFile(response.route);
        },
        error: function (xhr, status, errors){
            showErrorsMsg(xhr);
        },
        complete: function(){}
    });
}

// import_excel_plan
function sendCSVFile(route){
    let _token = $token.val();
    let formData = new FormData( $("#plan_from_csv_form").get()[0] );
    let fileInput = $("input[name=create_plan_from_csv_input]")[0];

    if( fileInput.files.length > 0 ){
        let file = fileInput.files[0];

        formData.delete('create_plan_from_csv_input');
        formData.append('import_excel_plan', file);
    }

    formData.append("_token", _token);

    $.ajax({
        url: route,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            location.reload();
        },
        error: function (xhr, status, errors) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Ocultar los mensajes de error
function hideErrorsMsg(){
    $("#error_msg_dir").addClass('d-none');
    $("#error_msg_dir").removeClass('d-block');

    $("#error_msg_sub").addClass('d-none');
    $("#error_msg_sub").removeClass('d-block');

    $("#error_msg_name").addClass('d-none');
    $("#error_msg_name").removeClass('d-block');

    $("#error_msg_type_plan").addClass('d-none');
    $("#error_msg_type_plan").removeClass('d-block');

    $("#error_msg_geo").addClass('d-none');
    $("#error_msg_geo").removeClass('d-block');

    $("#error_msg_all").addClass('d-none');
    $("#error_msg_all").removeClass('d-block');

    $("#error_msg_color").addClass('d-block');
    $("#error_msg_color").removeClass('d-block');
}

// Muestra los mensajes de error 
function showErrorsMsg(xhr){
    var id_area_error = xhr.responseJSON.errors.id_area ?? null;
    var id_subarea_error = xhr.responseJSON.errors.id_subarea ?? null;
    var name_error = xhr.responseJSON.errors.name ?? null;
    var type_plan = xhr.responseJSON.errors.type_plan ?? null;
    var geometry_error = xhr.responseJSON.errors.geometry ?? null;
    var color_error = xhr.responseJSON.errors.plan_color ?? null;

    // if(id_area_error && id_subarea_error && name_error && geometry_error){
    if( Object.keys(xhr.responseJSON.errors).length == 6 ){
        $("#error_msg_all").addClass('d-block');
    } else {
        $("#error_msg_dir").addClass( id_area_error ? 'd-block' : '');
        $("#error_msg_sub").addClass( id_subarea_error ? 'd-block' : '');
        $("#error_msg_name").addClass( name_error ? 'd-block' : '');
        $("#error_msg_geo").addClass( geometry_error ? 'd-block' : '');
        $("#error_msg_color").addClass( color_error ? 'd-block' : '');
        $("#error_msg_type_plan").addClass( type_plan ? 'd-block' : '');
    }
}


// Emite el evento para validar que la clave catastral se encuentre dentro de la base de datos
async function valitadateCatastralKey() {
    $loading.show();
    let _token = $token.val();
    let catastralKey = $("#ClaveCatastral_number_0_catastral_attr").val();
    let result = false;

    $("#ClaveCatastral_number_0_catastral_attr_label").removeClass('text-danger');
    $("#ClaveCatastral_number_0_catastral_error_msg").removeClass('valid-feedback');
    $("#ClaveCatastral_number_0_catastral_error_msg").removeClass('invalid-feedback');
    $("#ClaveCatastral_number_0_catastral_error_msg").hide();

    try {
        result = await new Promise((resolve, reject) => {
            $.ajax({
                url: window.Laravel.routes['plans.validate-catastral-key'],
                type: 'POST',
                data: {
                    _token: _token,
                    catastralKey: catastralKey,
                },
                success: function(response){
                    if (response) {
                        $("#ClaveCatastral_number_0_catastral_error_msg").addClass('valid-feedback');
                        $("#ClaveCatastral_number_0_catastral_error_msg").text("La clave ingresada es válida.");
                    } else {
                        $("#ClaveCatastral_number_0_catastral_error_msg").addClass('invalid-feedback');
                        $("#ClaveCatastral_number_0_catastral_error_msg").text("La clave ingresada no es válida.");
                    }

                    $("#ClaveCatastral_number_0_catastral_error_msg").show();
                    response = response != '' ? true : false;
                    resolve(response); // Resolviendo la promesa con la respuesta
                },
                error: function(xhr, status, errors){
                    if (xhr.status == 422) {
                        $("#castral_key_number_100_castral_attr_label").addClass('text-danger');
                        $("#ClaveCatastral_number_0_catastral_error_msg").addClass('invalid-feedback');
                        $("#ClaveCatastral_number_0_catastral_error_msg").text("Por favor, ingrese un valor para validar.");
                        $("#ClaveCatastral_number_0_catastral_error_msg").show();
                    }
                    resolve(true); // Resolviendo la promesa con `true` en caso de error
                },
                complete: function(data){
                    $loading.hide();
                }
            });
        });
    } catch (error) {
        console.error("Error en la validación de la clave catastral:", error);
        $loading.hide();
        result = true;
    }

    return result;
}


// Cierra el modal
function closeModal() {
    $basicModal.modal("hide");
}