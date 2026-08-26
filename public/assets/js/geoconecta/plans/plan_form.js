$(document).ready(function () {
    $("#field-form").hide();
    $("#error-msg").hide();
    $("#options_form_container").hide();
    $(".dropify").dropify({
        height: 100,
    });

    avoidPropagationEvent();

    const index = getUrlParameter("index");
    if (index) setIndex(index);

    // Abre el modal o el formulario dependiendo del tamaño de la pantalla
    $("#btn-add-field").on("click", function (event) {
        event.stopPropagation();
        var width = $(window).width();

        formFieldState = true;

        if (width <= 1539) {
            openFormField("Agregar Campo", "", "");
            openFormFieldModal();
            $("#field-form").hide();
        } else if (width >= 1540) {
            openFormField("Agregar Campo", "", "");
        }
    });

    // Manda llamar el método para cerrar el formulario
    $("#close-field-form").on("click", function (event) {
        event.stopPropagation();
        closeForm("#options_form_container", "#msg_options_form");
    });

    // Botón para guardar mandar una petición al servidor para guardar los datos del formulario
    $("#btn-save-field").on("click", function (event) {
        event.stopPropagation();
        getFormValues();
    });

    // Evento para abrir el espacio de opciones
    $("#type_data").on("change", function (event) {
        event.stopPropagation();

        var type = $(this).val();
        openOptionsForm(type);
    });

    // Permite selecionar el campo apartir del cual se deben agregar nuevos campos
    $(".input_draggable").on("click", function (event) {
        event.stopPropagation();

        $(".input_draggable").removeClass("active");

        index_html = $(this).attr("id");
        $("#index_field").val(index_html);
        $(this).addClass("active");
    });

    // Deselecciona el campo
    $(document).on("click", function () {
        index_html = -1;
        $(".input_draggable").removeClass("active");
    });

    // Evento para detectar si la pantalla se hace más grande o más chica
    $(window).on("resize", function () {
        var width = $(window).width();

        if (formFieldState && width <= 1539) {
            if (!modalFormState) {
                openFormFieldModal();
            }

            $("#field-form").css("display", "none");
        }
    });

    // Detectar cuando un modal se cierre
    $("#basicModal").on("hidden.bs.modal", function () {
        closeForm();
    });

    $(".btn-add-quick-fields").on("click", function (event) {
        event.stopPropagation();
        var route = $(this).attr("route");

        addAddressFields(route);
    });

    $("#btn-add-address").on("click", function (event) {
        event.stopPropagation();
    });

    $("#btn-add-catastral-key").on("click", function (event) {
        event.stopPropagation();
    });

    // Filtrar las opciones de colonias en base al código postal
    $("#C_P__select_3_address_attr").on("change", function () {
        let selected_value = $(this).val();

        $(".suburb-option").hide();
        $("#Colonia_select_4_address_attr").val("");
        toggleNextField("#Colonia_select_4_address_attr", selected_value);
    });
});

// Escucha el evento que se encarga de mover y re-ubicar los elementos del formulario
document.addEventListener("DOMContentLoaded", function () {
    const formContainer = document.getElementById("form-container");

    new Sortable(formContainer, {
        onEnd: function (evt) {
            const fields = Array.from(formContainer.children);
            sendNewOrder(fields);
        },
    });
});

// Abre, o cierra, el espacio para agregar las opciones de un selector de múltiples opciones
function openOptionsForm(type, id_modal = "") {
    if (type == "radio" || type == "checkbox" || type == "select") {
        $(id_modal + " #options_form_container").fadeIn();
        $(id_modal + " #msg_options_form").html(
            "Ingrese las opciones separándolas por saltos de línea."
        );
    } else {
        $(id_modal + " #options_form_container").fadeOut();
        $(id_modal + "#options_form").val("");
    }
}

// Obtiene los valores del formulario
function getFormValues(modal_or_form = false) {
    var id_modal = modal_or_form ? "#basicModal .modal-content" : "";

    var name_field = $(id_modal + " #name_field").val();
    var type_data = $(id_modal + " #type_data").val();
    var size = $(id_modal + " #type_data")
        .find("option:selected")
        .attr("size"); // $(id_modal + " #size_field").val();
    var options = $(id_modal + " #options_form").val();
    var index = $(id_modal + " #index_field").val();
    var route =
        $(id_modal + " #btn-save-field").html() == "Guardar"
            ? "/create-field"
            : "/update-field";

    updateForm(route, name_field, type_data, size, options, index);
}

// Cierra todo formulario, independientemente de si es modal o formulario en pantalla
function closeForm(id_modal = "") {
    formFieldState = false;
    modalFormState = false;
    $basicModal.modal("hide");

    openFormField("", "", "");

    var id_options_form_container = id_modal + "#options_form_container";
    $(id_options_form_container).hide();

    var id_msg_form = id_modal + "#msg_options_form";
    $(id_msg_form).text("");
}

// Abre el modal para editar la información básica del inventario
function showModal(modal) {
    $loading.show();
    let _token = $token.val();

    $.get(
        "/admin/inventarios/" + modal,
        {
            _token: _token,
        },
        function (data) {
            $loading.hide();
            initContrastMap();
            $("#basicModal .modal-content").html(data);
            $("#basicModal .modal-dialog").addClass("modal-xl");

            $basicModal.modal("show");
        }
    );
}

// Ejecuta la petición para editar la información básica del inventario
function editPlaneacion(id) {
    $loading.show();
    hideErrorsMsg();

    let formData = new FormData( $("#edit_plan_form").get()[0] );
    let _token = $token.val();

    formData.append( '_token', _token );

    $.post({
        url: window.Laravel.routes['plan.update'].replace('id_plan', id),
        // "/admin/inventarios/" + id + "/update-planeacion",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            location.reload();
        },
        error: function (xhr, status, error) {
            if( xhr.status == 422 )
                showErrorsMsg(xhr);
        },
        complete: function () {
            $loading.hide();
        },
    });
}

// Ejecuta la petición para actualizar el formulario
function updateForm(route, name_field, type_data, size, options, index) {
    $loading.show();
    let _token = $token.val();
    var sufix = index != "-1" ? `/${index}` : ``;

    $.post({
        url: "/admin/inventarios/" + id_plan + route + sufix,
        type: "POST",
        data: {
            _token: _token,
            type_data: type_data,
            name_field: name_field,
            options: options,
            size: size,
            index: index_html,
        },
        success: function (data) {
            $loading.hide();
            if (data.success) {
                $("#error-msg").hide();
                $("#field-form").fadeOut();
                $("#type_data").val("");
                $("#name_field").val("");
                location.reload();
                window.location.href =
                    window.location.pathname + "?index=" + index_html;
            } else {
                $("#error-msg").text(data.message);
                $("#error-msg").show();
            }
        },
        error: function () {
            $loading.hide();
        },
        complete: function () {
            $loading.hide();
        },
    });
}

function openFormEditField(name, type, size, options, index, editable) {
    formFieldState = true;

    var width = $(window).width();
    openFormField("Editar Campo", name, type, size, options, index, editable);

    if (width <= 1539) openFormFieldModal();
}

// Evitar la propagación del evento del click, para mantener seleccionado un elemento del formulario
function avoidPropagationEvent(id_modal = "") {
    $(id_modal + " #name_field").on("click", function (event) {
        event.stopPropagation();
    });

    $(id_modal + " #type_data").on("click", function (event) {
        event.stopPropagation();
    });

    $(id_modal + " #size_field").on("click", function (event) {
        event.stopPropagation();
    });

    $(id_modal + " #options_form").on("click", function (event) {
        event.stopPropagation();
    });
}

// Abre el formulario de creación de elementos
function openFormField(
    title,
    name,
    type,
    size = "",
    options = [],
    index = -1,
    editable = true
) {
    if (formFieldState) $("#field-form").fadeIn();
    else $("#field-form").fadeOut();

    if (editable) $("#type_data_input").show();
    else $("#type_data_input").hide();

    $("#field-form-title").text(title);
    $("#type_data").val(type);
    $("#name_field").val(name);
    // $("#size_field").val(size);
    $("#index_field").val(index);

    if (title == "Agregar Campo") $("#btn-save-field").html("Guardar");
    else if (title == "Editar Campo") $("#btn-save-field").html("Actualizar");

    if (options.length >= 1) {
        $("#options_form").val(options.join("\n"));
        $("#options_form_container").show();
        $("#msg_options_form").html(
            "Ingrese las opciones sepárandolas por saltos de línea."
        );
    } else {
        $("#options_form_container").fadeOut();
        $("#msg_options_form").html("");
    }
}

// Envía la petición poara eliminar un campo del formulario
function deleteField(index) {
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['plans.form.field.delete'].replace('id_plan', id_plan).replace('index', index),
        // "/admin/inventarios/" + id_plan + "/delete-field/" + index,
        type: "DELETE",
        data: {
            _token: _token,
        },
        success: function (data) {
            $loading.hide();
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        },
    });
}

// Envía la petición para cambiar el orden de los campos
function sendNewOrder(fields) {
    var new_order = [];

    fields.forEach((element) => {
        new_order.push($(element).attr("id"));
    });

    $loading.show();
    let _token = $token.val();

    $.ajax({
        type: "POST",
        url: window.Laravel.routes['plans.form.field.update-order'].replace('id_plan', id_plan),
        // `{{ route('plans.form.field.update-order', 'id_plan') }}`.replace(
        //     "id_plan",
        //     id_plan
        // ),
        data: {
            fields: new_order,
            _token: _token,
        },
        success: function (data) {
            $loading.hide();
            location.reload();
        },
        error: function (xhr, status, error) {
            $loading.hide();
        },
        complete: function () {
            $loading.hide();
        },
    });
}

// Obtiene el indice que es enviado cuando la página se recarga, dicho índice será de utilidad
// para seguir colocando elementos después de este
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Coloca el índice, el cual indica la posición en la que se deberán colocar los siguientes elementos
function setIndex(index) {
    index_html = index;

    var id = `#${index_html}`;
    $(id).addClass("active");
}

// Abre el modal para crear o editar algún campo del formulario
function openFormFieldModal() {
    modalFormState = true;

    var formContent = $("#field-form").html();
    $("#basicModal .modal-content").html(formContent);
    $("#basicModal .modal-dialog").addClass("modal-lg");
    swapValues("", "#basicModal .modal-content");

    // Manda llamar el método para cerrar el formulario
    $("#basicModal .modal-content #close-field-form").on(
        "click",
        function (event) {
            event.stopPropagation();
            closeForm("#basicModal .modal-content");
        }
    );

    $("#basicModal .modal-content #btn-save-field").on(
        "click",
        function (event) {
            event.stopPropagation();
            getFormValues(true);
        }
    );

    $("#basicModal .modal-content #type_data").on("change", function () {
        var type = $(this).val();
        openOptionsForm(type, "#basicModal .modal-content");
    });

    $(window).on("resize", function () {
        var width = $(window).width();

        if (formFieldState && width >= 1540) {
            swapValues("#basicModal .modal-content", "");
            $("#field-form").show();

            $basicModal.modal("hide");
            $("#basicModal .modal-content").empty();
            modalFormState = false;
        }
    });

    $("#basicModal .modal-content #btn-add-address").on(
        "click",
        function (event) {
            event.stopPropagation();
            var route = $(this).attr("route");

            addAddressFields(route);
        }
    );

    avoidPropagationEvent("#basicModal .modal-content");

    $basicModal.modal("show");
}

// Cambia los valores del formulario modal al formulario en pantalla y visceversa
function swapValues(id_from, id_to) {
    //Extraer los valores del formulario
    var name_field = $(id_from + " #name_field").val();
    var type_data = $(id_from + " #type_data").val();
    var size = $(id_from + " #size_field").val();
    var options = $(id_from + " #options_form").val();
    var index = $(id_from + " #index_field").val();

    // Colocar los valores al nuevo formulario
    $(id_to + " #name_field").val(name_field);
    $(id_to + " #type_data").val(type_data);
    $(id_to + " #size_field").val(size);
    $(id_to + " #options_form").val(options);
    $(id_to + " #index_field").val(index);
}

// Emite el evento para agregar los campos de dirección al formulario
function addAddressFields(route) {
    var index = $("#index_field").val();
    var _token = $token.val();

    $loading.show();

    $.ajax({
        type: "POST",
        url: route,
        data: {
            _token: _token,
            index: index,
        },
        success: function (data) {
            location.reload();
        },
        error: function (xhr, status, errors) {},
        complete: function () {
            $loading.hide();
        },
    });
}