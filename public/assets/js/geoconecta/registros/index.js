var subarea_options = [];

$(document).ready(function () {
    updateTable(null, null);

    $("#id_area").on("change", function () {
        var id_area = $(this).val();
        restoreOptions();

        $("#id_subarea").val("").trigger("change");

        $(".subarea_option").each(function () {
            if (!$(this).hasClass(id_area)) {
                subarea_options.push($(this));
                $(this).remove();
            }
        });

        $("#id_subarea").select2();
    });
});

// Restablece las opciones
function restoreOptions() {
    var options = subarea_options;

    $.each(options, function (index, option) {
        $("#id_subarea").append(option);
    });
}

// Filtra los inventarios mediante los ID de las áreas o subáreas
function filterRows() {
    var id_area = $("#id_area").val();
    var id_sub = $("#id_subarea").val();

    updateTable(id_area, id_sub);
}

// Actualiza la tabla de datos
function updateTable(id_area, id_subarea) {
    $loading.show();
    var _token = $token.val();

    $("#mobTable").DataTable({
        dom: "Bfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                filename: "Director",
                className: "btn btn-sm btn-primary mb-3",
                text: '<em class="icon ni ni-download"></em> Exportar Excel',
            },
        ],
        ajax: {
            url: window.Laravel.routes['register.filter-info'],
            data: {
                id_area: id_area,
                id_subarea: id_subarea,
                _token: _token,
            },
            complete: function () {
                $loading.hide();
            },
        },
        columns: [
            { data: "index", name: "index" },
            { data: "name", name: "name" },
            { data: "area_name", name: "area_name" },
            { data: "subarea_name", name: "subarea_name" },
            {
                data: "actions",
                name: "actions",
                orderable: false,
                searchable: false,
            },
        ],
        order: [[0, "asc"]],
        fnInitComplete: function () {
            $loading.hide();
        },
        destroy: true,
        responsive: false,
        autoWidth: false,
        paginate: false,
        language: spanish,
    });
}
