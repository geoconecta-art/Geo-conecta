$(document).ready(function () {
    $('.dropify').dropify({
        height: 100,
    });
    
    $("#toast_notification").hide();

    $("#inventory_form_container").on("submit", async function (e) {
        e.preventDefault();
        let clave_field = $("#ClaveCatastral_number_0_catastral_attr").get();
        let result = clave_field.lenght > 0 ? await valitadateCatastralKey() : true;
        
        if( result ){
            storeFormInfo();
        }
    });

    $("#toast_close_btn").on("click", function () {
        $("#toast_header_title").html(``);
        $("#toast_body").html(``);
        $("#toast_notification").fadeOut();
    });

    $("#C_P__select_3_address_attr").on("change", function() {
        let selected_value = $(this).val();

        $(".suburb-option").hide();
        $("#Colonia_select_4_address_attr").val("");
        toggleNextField("#Colonia_select_4_address_attr", selected_value);
    });
});

// Emite el evento para almacenar la información del formulario
function storeFormInfo() {
    $loading.show();

        var _form = new FormData( $("#inventory_form_container").get()[0] );
        var id_lat_inicial = "Latitud_number_0_georeference_Inicial_attr";
        var id_lng_inicial = "Longitud_number_1_georeference_Inicial_attr";

        var id_lat_final = "Latitud_number_0_georeference_Final_attr";
        var id_lng_final = "Longitud_number_1_georeference_Final_attr";

        _form.append(id_lat_inicial, $(`#${id_lat_inicial}`).val());
        _form.append(id_lng_inicial, $(`#${id_lng_inicial}`).val());

        _form.append(id_lat_final, $(`#${id_lat_final}`).val());
        _form.append(id_lng_final, $(`#${id_lng_final}`).val());
        
        $.ajax({
            url: window.Laravel.routes['register.inventory.store'].replace('id', id_plan), //  '/admin/registros/inventario/' + id_plan + '/guardar-registro',
            type: 'POST',
            data: _form,
            contentType: false,
            processData: false,
            success: function(data){
                var textBody = "El registro se ha guardado correctamente.";

                showToastNotification('text-success', `<em class="icon ni ni-check-circle"></em> Éxito`, textBody, 'text-danger');
                resetForm();
            },
            error: function(data){
                var textBody = "";

                if(data.status == 500){
                    textBody = "Lo sentimos, hubo un error al momento de guardar el registro. Por favor, vuelva a intentarlo.";
                } else {
                    var errors = data.responseJSON.message;
                    getLostFields(errors);
                    textBody = "Por favor, asegúrse de ingresar todos los campos con la información correcta.";
                }

                showToastNotification('text-danger', `<em class="icon ni ni-caution"></em> Alerta`, textBody, 'text-success');
            },
            complete: function () {
                $loading.hide();
            }
        });
}

// Función que manda llamar el método que se encarga de mostrar el mapa en pantalla
function initializeMarkersToEdit( matches ){
    showMapModal(matches);
}

// Muestra la notificación toast 
function showToastNotification(textColor, header, bodyMessage, removeTextColor){

    $("#toast_header_title").addClass(textColor);
    $("#toast_header_title").removeClass(removeTextColor);
    $("#toast_header_title").html(header);
    $("#toast_body").html(bodyMessage);
    $("#toast_notification").fadeIn();

    setTimeout(() => {
        $("#toast_notification").fadeOut();
    }, 3000);
}

// Pone de color rojo los títulos de los campos faltantes
function getLostFields(errorArray){
    var lostFields = [];

    for(const key in errorArray){
        if( $(`#${key}_label`).length ) {
            lostFields.push(key);
            $(`#${key}_label`).addClass('text-danger');
        }
    }

    return lostFields;
}

// Resetea los valores del formulario
function resetForm(){
    $("#inventory_form_container")[0].reset();
    $('.dropify-clear').click();
    $("#inventory_form_container label").removeClass('text-danger');
    $('.dropify-clear').click();
}