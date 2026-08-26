$(document).ready(function() {
    updateDataTable();
});

function updateDataTable(path) {

    $loading.show();

    var dom_normal = '<"row justify-between m-0 gy-2 px-3 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

    $('#usersTable').DataTable({
        ajax: window.Laravel.routes['users.index'],
        columns: [
            { data: 'index', name: 'index' },
            { data: 'Imagen', name:'Imagen' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'area', name: 'area'},
            { data: 'actions', name: 'actions', orderable: false, searchable : false }
        ],
        fnInitComplete: function () {
            $loading.hide();
        },
        destroy: true,
        responsive: false,
        autoWidth: false,
        dom: dom_normal,
        paginate: false,
        language: spanish
    });
}

function deleteItem(id) {

    $loading.show();
    var _token = $('[name="_token"]').val();
    var _method = 'DELETE';

    $.ajax({
        type: _method,
        url: window.Laravel.routes['users.destroy'].replace('id_user', id),
        data: {_token: _token},
        success: function (response) {
            showSuccessModal(response.message);
            updateDataTable();
        },
        error: function (xhr, status, error) {
            showErrorModal(error);
        },
        complete: function () {
            $loading.hide();
        }
    });

}