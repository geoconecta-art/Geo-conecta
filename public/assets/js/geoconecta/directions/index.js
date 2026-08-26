$(document).ready(function (){
    updateTable();
});

// Actualiza la tabla de datos de direcciones
function updateTable(){
    $loading.show();

    var dom_normal = '<"row justify-between m-0 gy-2 px-3 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

    $('#area_table').DataTable({
        ajax: window.Laravel.routes['directions.get.areas'],// "{{ route('directions.get.areas') }}",
        columns: [
            { data: 'index', name: 'index' },
            { data: 'name', name: 'name' },
            { data: 'clave', name: 'clave' },
            { data: 'subareas', name:'subareas' },
            { data: 'planeaciones', name: 'planeaciones' },
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

// Abre el modal para editar o crear una dirección
function showModal(url) {

    $loading.show();
    let _token = $token.val();
    
    $.get( url, { 
            '_token':_token,
    }, function(data) {
        $('#basicModal .modal-content').html(data);
        $('#basicModal .modal-dialog').addClass('modal-lg');

        $basicModal.modal('show');
    }).always(function() {
        $loading.hide();
    });   
}

// Manda la petición para crear la dirección
function createDirection() {
    $loading.show();

    let _token = $token.val();
    let name = $('input[name=name]').val();
    let direction_key = $('input[name=direction_key]').val();
   
    $.ajax({
        url: window.Laravel.routes['directions.create'], // `{{ route('directions.create') }}`,
        type: 'POST',
        data: { 
            '_token': _token,
            'name' : name,
            'direction_key' : direction_key
        },
        success: function(data) {
            $basicModal.modal('hide');
            // location.reload();
            updateTable();
        },
        error: function (xhr, status, error) {
            $("#name_error_msg").addClass('d-block');
            $("#key_error_msg").addClass('d-block');
        },
        complete: function(){
            $loading.hide();
        }
    });
}

// Envía la petición para eactualizar la dirección
function editDirection(id){
    $loading.show();
    
    let _token = $token.val();
    let name = $('input[name=newName]').val();
    let direction_key = $('input[name=direction_key]').val();
   
    $.post({
        url: window.Laravel.routes['directions.update'].replace('id_area', id),
        data: { 
        '_token':_token,
        'name' : name,
        'direction_key' : direction_key
        }, 
        success: function(data) {
            $loading.hide();
            // location.reload();
            $basicModal.modal('hide');
            updateTable();
        },
        error: function(xhr, status, error){
            $loading.hide();
            $("#error_msg").addClass('d-block');
            console.log(xhr);
        },
        complete: function(){
            $loading.hide();
        }
    });
}

// Envía la petición para mostrar la tarjeta de confirmación de eliminación
function confirmDelete(id) {
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['directions.confirm-delete'].replace('id_area', id),
        type: 'GET',
        data: {
            '_token': _token,
        },
        success: function(response) {
            $('#basicModal .modal-content').html(response);
            $('#basicModal .modal-dialog').addClass('modal-lg');

            $basicModal.modal('show');
        },
        error: function(xhr) {
        },
        complete: function(){
            $loading.hide();
        }
    });
}

// Elimina la dirección
function deleteDirection(id){
    $loading.show();

    let _token = $token.val();
   
    $.ajax({
        url: window.Laravel.routes['directions.delete'].replace('id_area', id),
        type: 'DELETE',
        data: {
            '_token':_token,
        },
        success: function(data) {
            var response = `Dependencia / Organismo <b>${data.name}</b> elimada. <br> <b>${data.subdirections}</b> Áreas eliminadas.<br> <b>${data.plans}</b> inventarios eliminados.`;
            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_direction_cancel_btn").hide();
            $("#delete_direction_accept_btn").hide();

            setTimeout(() => {
                $basicModal.modal('hide');
                updateTable();
            }, 2500);
        },
        error: function (xhr, status, error) {
            var response = `Lo sentimos hubo un error al intentar procesar su petición.`;
            $("#text_delete_info").html(response);
            $("#delete_direction_cancel_btn").hide();
            $("#delete_direction_accept_btn").hide();

            setTimeout(() => {
                $basicModal.modal('hide');
            }, 2500);
        },
        complete: function(){
            $loading.hide();
        }
    });
}