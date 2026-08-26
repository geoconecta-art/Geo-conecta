$(document).ready(function () {
    $("#desktop_view_link").addClass("active");
    defineView();

    $(window).on("resize", function () {
        defineView();
    });

    $(".dropify").dropify({
        height: 100,
    });

    $("#C_P__select_3_address_attr").on("change", function () {
        let selected_value = $(this).val();

        $(".suburb-option").hide();
        $("#Colonia_select_4_address_attr").val("");
        toggleNextField("#Colonia_select_4_address_attr", selected_value);
    });

    /******** EVENTOS PARA VALIDAR FORMULARIO ********/
    $("#toast_notification").hide();

    $("#inventory_form_container").on("submit", function (e) {
        e.preventDefault();
        validateData();
    });

    $("#toast_close_btn").on("click", function () {
        $("#toast_header_title").html(``);
        $("#toast_body").html(``);
        $("#toast_notification").fadeOut();
    });
});

// Define la vista en base al ancho de la pantalla
function defineView() {
    var width = $(window).width();

    if (width <= 768) {
        toggleView(1);
        $("#mobile_view_link").addClass("active");
        $("#desktop_view_link").hide();
    } else if (width >= 769) {
        $("#mobile_view_link").removeClass("active");
        $("#desktop_view_link").show();
    }
}

// Cambia la vista
function toggleView(view) {
    activeButton(view);

    var geo_items = $(".form_geo_item");
    resizeItems(view, geo_items);

    var dynamic_items = $(".form_dynamic_item");
    resizeItems(view, dynamic_items);

    resizeCardContainer(view);

    resizeModal(view);
    resizeModalInputs(view);
}

// Activa el botón que indica la vista actual
function activeButton(view) {
    var activeView = view == -1 ? "mobile_view_link" : "desktop_view_link";
    var inactiveView = view == -1 ? "desktop_view_link" : "mobile_view_link";

    $("#" + activeView).addClass("active");
    $("#" + inactiveView).removeClass("active");
}

// Cambia el tamaño de los elementos del formulario
function resizeItems(view, items) {
    for (var i = 0; i < items.length; i++) {
        var elementsize = $(items[i]).attr("elementsize");

        if (view == 1) $(items[i]).addClass("col-md-" + elementsize);
        else $(items[i]).removeClass("col-md-" + elementsize);
    }
}

// Cambia el tamaño del contenedor de los elementos según la vista
function resizeCardContainer(view) {
    if (view == 1)
        $("#preview_form_card").addClass("w-100").removeClass("w-25");
    else $("#preview_form_card").addClass("w-25").removeClass("w-100");
}

// Cambia el tamaño del modal del mapa según  la vista
function resizeModal(view) {
    $("#modal_dialog_size")
        .removeClass("modal-sm modal-xl")
        .addClass(view === 1 ? "modal-xl" : "modal-sm");
}

// Cambia el tamaño de los inputs dentro del modal del mapa
function resizeModalInputs(view) {
    $("#lat_modal_input")
        .removeClass(view === 1 ? "col-12" : "col-md-4")
        .addClass(view === 1 ? "col-md-4" : "col-12");

    $("#lng_modal_input")
        .removeClass(view === 1 ? "col-12" : "col-md-4")
        .addClass(view === 1 ? "col-md-4" : "col-12");

    $("#distance_modal_input")
        .removeClass(view === 1 ? "col-12" : "col-md-4")
        .addClass(view === 1 ? "col-md-4" : "col-12");

    $("#btn-save-coor")
        .removeClass(view === 1 ? "col-12" : "col-md-4")
        .addClass(view === 1 ? "col-md-4" : "col-12");
}


/******************************** Validar datos del formulario *******************************/

// Emite el evento para validar la información del formulario
function validateData() {
    $loading.show();

    // var _form = new FormData(this);
    var _form = new FormData($("#inventory_form_container")[0]);

    // var formData = $(this).serialize();
    var id_lat_inicial = "Latitud_number_0_georeference_Inicial_attr";
    var id_lng_inicial = "Longitud_number_1_georeference_Inicial_attr";

    var id_lat_final = "Latitud_number_0_georeference_Final_attr";
    var id_lng_final = "Longitud_number_1_georeference_Final_attr";

    _form.append(id_lat_inicial, $(`#${id_lat_inicial}`).val());
    _form.append(id_lng_inicial, $(`#${id_lng_inicial}`).val());

    _form.append(id_lat_final, $(`#${id_lat_final}`).val());
    _form.append(id_lng_final, $(`#${id_lng_final}`).val());

    $.ajax({
        url: window.Laravel.routes['plans.preview.validation'].replace('id_plan', id_plan),
        // `{{ route('plans.preview.validation', 'id_plan') }}`.replace(
        //     "id_plan",
        //     id_plan
        // ),
        type: "POST",
        data: _form,
        contentType: false,
        processData: false,
        success: function (data) {
            var textBody = data.message;

            showToastNotification(
                "text-success",
                `<em class="icon ni ni-check-circle"></em> Éxito`,
                textBody,
                "text-danger"
            );
            resetForm();
        },
        error: function (data) {
            var textBody = "";

            if (data.status == 500) {
                textBody =
                    "Lo sentimos, hubo un error al momento de guardar el registro. Por favor, vuelva a intentarlo.";
            } else {
                var errors = data.responseJSON.message;
                getLostFields(errors);
                var textBody =
                    "Por favor, asegúrse de ingresar todos los campos con la información correcta.";
            }

            showToastNotification(
                "text-danger",
                `<em class="icon ni ni-caution"></em> Alerta`,
                textBody,
                "text-success"
            );
        },
        complete: function () {
            $loading.hide();
        },
    });
}

// Muestra la notificación toast
function showToastNotification(
    textColor,
    header,
    bodyMessage,
    removeTextColor
) {
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
function getLostFields(errorArray) {
    var lostFields = [];

    for (const key in errorArray) {
        if ($(`#${key}_label`).length) {
            lostFields.push(key);
            $(`#${key}_label`).addClass("text-danger");
        }
    }

    return lostFields;
}

// Resetea los valores del formulario
function resetForm() {
    $("#inventory_form_container")[0].reset();
    $("#inventory_form_container label").removeClass("text-danger");
}
