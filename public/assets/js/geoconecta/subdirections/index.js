$(document).ready(function (){
    updateTable();
});

// Actualiza los datos de la tabla mediante una petición Ajax
function updateTable(){
    $loading.show();

    var dom_normal = '<"row justify-between m-0 gy-2 px-3 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

    $("#subarea_table").DataTable({
        ajax: window.Laravel.routes['subdirections.update.table'],
        columns: [
            { data: 'index', name: 'index' },
            { data: 'name', name: 'name' },
            { data: 'area', name: 'area' },
            { data: 'plans', name: 'plans' },
            { data: 'actions', name: 'actions' }
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

function showModal(url) {

    $loading.show();
    let _token = $token.val();

    $.get( url, { 
            '_token':_token,
    }, function(data) {
        $loading.hide();
        $('#basicModal .modal-content').html(data);
        $('#basicModal .modal-dialog').addClass('modal-lg');

        $basicModal.modal('show');
    });   
}

function createSubdirection() {
    $loading.show();
    $("#error_msg_dir").addClass('d-none');
    $("#error_msg_dir").removeClass('d-block');
    $("#error_msg_sub").addClass('d-block');
    $("#error_msg_sub").removeClass('d-block');
    $("#error_msg_all").addClass('d-none');
    $("#error_msg_all").removeClass('d-block');

    let _token = $token.val();
    let name = $('input[name=name]').val();
    let id_direction = $('select[name=id_direction]').val();
   
    $.post({
        url:  window.Laravel.routes['subdirections.create'], 
        data: {
            '_token':_token,
            'name' : name,
            'id_area' : id_direction,
        }, 
        success: function(data) {
            $basicModal.modal('hide');
            updateTable();
        },
        error: function (xhr, status, error) {
            if( xhr.responseJSON.errors.id_area && xhr.responseJSON.errors.name){
                $("#error_msg_all").addClass('d-block');
            } else if(xhr.responseJSON.errors.id_area && !xhr.responseJSON.errors.name){
                $("#error_msg_dir").addClass('d-block');
            } else if(!xhr.responseJSON.errors.id_area && xhr.responseJSON.errors.name){
                $("#error_msg_sub").addClass('d-block');
            }
        },
        complete: function() {
            $loading.hide();
        }
    });
}

function editSubdirection(id){
    $loading.show();

    $("#error_msg_dir").addClass('d-none');
    $("#error_msg_dir").removeClass('d-block');
    $("#error_msg_sub").addClass('d-block');
    $("#error_msg_sub").removeClass('d-block');
    $("#error_msg_all").addClass('d-none');
    $("#error_msg_all").removeClass('d-block');

    let _token = $token.val();
    let name = $('input[name=newName]').val();
    let id_direction = $('select[name=id_direction]').val();
   
    $.post({
        url: window.Laravel.routes['subdirections.update'].replace('id_sub', id),
        data: { 
            '_token':_token,
            'name' : name,
            'id_area' : id_direction,
        }, 
        success: function(data) {
            $basicModal.modal('hide');
            updateTable();
        },
        error: function(xhr, status, error){
            if( xhr.responseJSON.errors.id_area && xhr.responseJSON.errors.name){
                $("#error_msg_all").addClass('d-block');
            } else if(xhr.responseJSON.errors.id_area && !xhr.responseJSON.errors.name){
                $("#error_msg_dir").addClass('d-block');
            } else if(!xhr.responseJSON.errors.id_area && xhr.responseJSON.errors.name){
                $("#error_msg_sub").addClass('d-block');
            }
        },
        complete: function() {
            $loading.hide();
        }
    });
}

function confirmDelete(id) {
    $loading.show();
    let _token = $token.val();

    $.ajax({
        url: window.Laravel.routes['subdirections.confirm-delete'].replace('id_sub', id),
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
        complete: function (){
            $loading.hide();
        }
    });
}

function deleteSubdirection(id){
    
    $loading.show();
    let _token = $token.val();
   
    $.ajax({
        url: window.Laravel.routes['subdirections.delete'].replace('id_sub', id),
        type: 'DELETE',
        data: {
            '_token':_token,
        },
        success: function(data) {
            var response = `Área <b>${data.name}</b> elimada. <br><b>${data.plans}</b> Inventarios eliminados.`;
            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_subdirection_cancel_btn").hide();
            $("#delete_subdirection_accept_btn").hide();

            setTimeout(() => {
                $basicModal.modal('hide');
                updateTable();
            }, 2500);
        }, 
        error: function(xhr, status, error){
            var response = `Lo sentimos hubo un error al intentar procesar su petición.`;
            $("#title_deleting").html("Atención");
            $("#text_delete_info").html(response);
            $("#delete_subdirection_cancel_btn").hide();
            $("#delete_subdirection_accept_btn").hide();

            setTimeout(() => {
                $basicModal.modal('hide');
            }, 2500);
        }, 
        complete: function() {
            $loading.hide();
        }
    });
}