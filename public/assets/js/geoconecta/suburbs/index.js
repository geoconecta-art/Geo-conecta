$(document).ready(function() {
    updateDataTable();
});

// Actualiza los datos de la tabla
function updateDataTable() {
    $loading.show();

    var dom_normal = '<"row justify-between m-0 gy-2 px-3 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

    $('#suburbsTable').DataTable({
        ajax: window.Laravel.routes['suburb.update-table'],
        columns: [
            { data: 'index', name: 'index' },
            { data: 'name', name: 'name' },
            { data: 'cp', name: 'cp' },
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

// Muestra el modal 
function showModal(type, id_suburb = null){
    $loading.show();
    let _token = $token.val();
    
    $.ajax({
        url: window.Laravel.routes['suburb.open-modal'],
        type: 'GET',
        data: {
            _token: _token,
            type: type,
            id_suburb: id_suburb,
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

// Emite la petición para crear una nueva colonia
function createSuburb(){
    $loading.show();
    let _token = $token.val();
    let _name = $("input[name=suburb_name]").val();
    let _cp = $("input[name=suburb_cp]").val();

    $("#name_error_msg").addClass( 'd-none' );
    $("#name_error_msg").removeClass( 'd-block' );

    $("#cp_error_msg").removeClass( 'd-block' );
    $("#cp_error_msg").addClass( 'd-none' );

    $.ajax({
        url: window.Laravel.routes['suburb.create'],
        type: 'POST',
        data: {
            _token: _token,
            name: _name,
            cp: _cp,
        },
        success: function(data){
            updateDataTable();
            cerrarModal();
        },
        error: function(xhr, status, error) {
            let error_name = xhr.responseJSON.errors.name ?? null;
            let error_cp = xhr.responseJSON.errors.cp ?? null;

            if( error_name && error_cp ){
                $("#error_msg_all").addClass('d-block');
            } else {
                console.log( error_name );
                $("#name_error_msg").addClass( error_name ? 'd-block' : '' );
                $("#cp_error_msg").addClass( error_cp ? 'd-block' : '' );
            }
        },
        complete: function(xhr, status) {
            $loading.hide();
        }
    })
}

// Emite la petición para actualizar la información de una colonia
function updateSuburb(id_suburb){
    $loading.show();

    let _name = $("input[name=suburb_name]").val();
    let _cp = $("input[name=suburb_cp]").val();
    let _token = $token.val();

    $("#error_msg_all").addClass('d-none');
    $("#error_msg_all").removeClass('d-block');

    $("#name_error_msg").addClass( 'd-none' );
    $("#name_error_msg").removeClass( 'd-block' );

    $("#cp_error_msg").removeClass( 'd-block' );
    $("#cp_error_msg").addClass( 'd-none' );

    $.ajax({
        url: window.Laravel.routes['suburb.update'].replace("id_suburb", id_suburb),
        type: "POST",
        data: {
            _token: _token,
            name: _name,
            cp: _cp,
        },
        success: function(data) {
            updateDataTable();
            cerrarModal();
        },
        error: function(xhr, status, errors){
            let error_name = xhr.responseJSON.errors.name ?? null;
            let error_cp = xhr.responseJSON.errors.cp ?? null;

            if( Object.keys(xhr.responseJSON.errors).length == 2 ){
                $("#error_msg_all").addClass('d-block');
            } else {
                $("#name_error_msg").addClass( error_name ? 'd-block' : '' );
                $("#cp_error_msg").addClass( error_cp ? 'd-block' : '' );
            }
        },
        complete: function(xhr, status) {
            $loading.hide();
        }
    });
}

// Emite el evento para eliminar una colonia
function deleteSuburb(id_suburb) {
    $loading.show();

    var _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['suburb.delete'].replace('id_suburb', id_suburb),
        type: "DELETE",
        data: {
            _token: _token
        },
        success: function(data) {
            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(data.message);
            $("#suburb-cancel-btn").hide();
            $("#suburb-delete-btn").hide();

            setTimeout(() => {
                updateDataTable();
                cerrarModal();
            }, 2500);

        },
        error: function(xhr, status, errors){},
        complete: function(xhr, status){
            $loading.hide();
        }
    });

}

// Cierra el modal de creación y edición de modal
function cerrarModal(){
    $basicModal.modal('hide');
}