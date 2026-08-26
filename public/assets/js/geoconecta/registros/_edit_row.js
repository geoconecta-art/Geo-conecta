$(document).ready(function(){
    $('#toast_notification').hide();

    setValues(attributes);
    setValues(address);
    setValues(georeference);
    // initializeMarkersToEdit( georeference );

    $("#inventory_form_container").on("submit", async function (event) {
        event.preventDefault();
        let clave_field = $("#ClaveCatastral_number_0_catastral_attr").get();

        let result = clave_field.lenght > 0 ? await valitadateCatastralKey() : true;
        
        if( result ){
            updateRowInfo();
        }

    });

    $("#toast_close_btn").on("click", function () {
        $("#toast_header_title").html(``);
        $("#toast_body").html(``);
        $("#toast_notification").fadeOut();
    });

    $('.dropify').dropify({
        height: 100,
    });

    $(".dropify").on("dropify.afterClear", function(event, element){
        var hiddenId = element.input[0].id + "_hidden";
        $("#" + hiddenId).val(0);
    });

    $(".dropify").on("change", function(event){
        var hiddenId = $(this).attr("id") + "_hidden";
        $("#" + hiddenId).val(2);
    });

    $("#C_P__select_3_address_attr").on("change", function() {
        let selected_value = $(this).val();

        $(".suburb-option").hide();
        $("#Colonia_select_4_address_attr").val("");
        toggleNextField("#Colonia_select_4_address_attr", selected_value);
    });
});

// Emite el evento para actualizar la información del registro
function updateRowInfo(){
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

    var url = $("#inventory_form_container").attr('action');

    $.ajax({
        url: url,
        type: 'POST',
        data: _form,
        contentType: false,
        processData: false,
        success: function (data) {
            var textBody = data.message;
            showToastNotification('text-success', `<em class="icon ni ni-check-circle"></em> Éxito`, textBody, 'text-danger');
        },
        error: function (data) {
            var textBody = "";

            if(data.status == 500){
                textBody = "Lo sentimos, hubo un error al momento de actualizar el registro. Por favor, vuelva a intentarlo.";
            } else if( data.status == 422 ){
                var errors = data.responseJSON.message;
                getLostFields(errors);
                textBody = "Por favor, asegúrse de ingresar todos los campos con la información correcta.";
            } else if( data.status == 400 ){
                var errors = data.responseJSON.message;
                textBody = "Por favor, asegúrse de ingresar todos los campos con la información correcta.";
                    
            }

            showToastNotification('text-danger', `<em class="icon ni ni-caution"></em> Alerta`, textBody, 'text-success');
        },
        complete: function () {
            $loading.hide();
        }
    });
}

// Coloca los valores del registro en los correspondientes inputs
function setValues(arrayValues){
    $.each(arrayValues, function(key, value) {
        if( typeof value == "object" ){
            $.each(value, function(geoKey, geoVal){
                $('#'+geoKey).val(geoVal);
            });
        } else {
            if( key.includes('checkbox') ){
                var options = value.split(",");

                // Marcar los checkboxes que coinciden con los valores en el arreglo
                $("input[name='"+ key +"[]']").each(function() {
                    const checkboxValue = $(this).val(); // Obtener el valor del checkbox
                    if (options.includes(checkboxValue)) {
                        $(this).prop("checked", true); // Marcar el checkbox si está en el arreglo
                    }
                });
            } else if( key.includes('radio') ){
                $("input[name='" + key + "'][value='" + value + "']").prop("checked", true);
            } else if( key.includes('image') || key.includes('file')) {
                $('#'+key).dropify({ 'defaultFile': `{{ asset('value') }}`.replace('value',value) });
                var hasFile = $(`<input type='hidden' name='${key}_hidden' id='${key}_hidden' value='1' />`)
                $("#inventory_form_container").append( hasFile );
            } else{
                var index = replaceSpecialCharacters(key);
                $('#'+index).val(value);
                if( value != '' ){
                    $('#'+index).prop('disabled', false);
                }
            } 
            
        }
    });
}

// Inicailiza los marcadores para la edición
function initializeMarkersToEdit( matches ){

    if( matches == "Inicial" ){
        let lat_val = $("#Latitud_number_0_georeference_Inicial_attr").val() != '' 
        ?   $("#Latitud_number_0_georeference_Inicial_attr").val()
        :   19.543454812522057;

        let lng_val = $("#Longitud_number_1_georeference_Inicial_attr").val() != '' 
            ?   $("#Longitud_number_1_georeference_Inicial_attr").val()
            :   -99.23502275276154;

        var ini_pos = {
            lat: parseFloat( lat_val ),
            lng: parseFloat( lng_val )
        };

        if( iniMarker == null ){
            iniMarker = initMarker();
        }
        setMarkerPosition( ini_pos, iniMarker );
    }

    if( matches == "Final" ){
    
        lat_val = $("#Latitud_number_0_georeference_Final_attr").val() != '' 
            ?   $("#Latitud_number_0_georeference_Final_attr").val()
            :   19.543454812522057;
    
        lng_val = $("#Longitud_number_1_georeference_Final_attr").val() != '' 
            ?   $("#Longitud_number_1_georeference_Final_attr").val()
            :   -99.23502275276154;
    
        var fin_pos = {
            lat: parseFloat( lat_val ),
            lng: parseFloat( lng_val ),
        };
    
        if( finMarker == null ){
            finMarker = initMarker();
        }
        setMarkerPosition( fin_pos, finMarker );
    }

    showMapModal(matches);
}

// Reemplaza los caracteres que no se pueden leer
function replaceSpecialCharacters( str ){
    str = str.replace(/@/g, '\\@');
    str = str.replace(/\?/g, '\\?');
    str = str.replace(/\!/g, '\\!');
    str = str.replace(/\#/g, '\\#');
    str = str.replace(/\$/g, '\\$');
    str = str.replace(/\=/g, '\\=');
    str = str.replace(/\&/g, '\\&');

    str = str.replace(/\(/g, '\\(');
    str = str.replace(/\)/g, '\\)');
    str = str.replace(/\=/g, '\\=');
    str = str.replace(/\*/g, '\\*');
    str = str.replace(/\|/g, '\\|');
    str = str.replace(/\//g, '\\/');

    return str;
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