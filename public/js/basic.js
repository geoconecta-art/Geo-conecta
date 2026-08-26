
$token = $('input[name=_token]');
$loading = $('#loading');
$basicModal = $('#basicModal');

spanish = {
    search: "",
    searchPlaceholder: "Buscar",
    lengthMenu: "<span class='d-none d-sm-inline-block'>Mostrar</span><div class='form-control-select'> _MENU_ </div>",
    info: "_START_ -_END_ de _TOTAL_",
    infoEmpty: "No se encontraron resultados",
    zeroRecords: "No se encontraron resultados",
    emptyTable: "Ningún dato disponible en esta tabla",
    infoFiltered: "( Total _MAX_  )",
    paginate: {
        "first": "Primera",
        "last": "Última",
        "next": "Siguiente",
        "previous": "Anterior"
    }
};

function showDeleteModal(id, title, deleteFunction = deleteItem, message = '¿Deseas eliminar '+ title +'?') {
    Swal.fire({
        title: message,
        text: 'No podrás revertir los cambios',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            deleteFunction(id);
        }
    });
}

function showSuccessModal(message) {
    Swal.fire(
        '¡Buen trabajo!',
        message,
        'success'
    );
}

function showErrorModal(message) {
    Swal.fire(
        '¡Algo ha salido mal!',
        message,
        'error'
    );
}

function showWarningModal(message) {
    Swal.fire(
        '¡Atención!',
        message,
        'warning'
    );
}

function validateRequired($input) {

    var value = $input.val();

    if ( value === '' || value === null) {
        success = false;
        var $formGroup = $input.parent();
        $formGroup.addClass('has-warning');
        $formGroup.find('.help-block small').html('Campo requerido.');
        return false;
    }

    return true;
}

function validateINE($input) {

    var val = $input.val();
    var regex = /^([A-Z]{6})(\d{8})([A-Z]{1})(\d{3})$/;
    
    if (val !== '' && !regex.test(val)) {
        var $formGroup = $input.parent();
        $formGroup.addClass('has-error');
        $formGroup.find('.help-block small').html('El formato es incorrecto. Consulta la ventana de ayuda.');
        return false;
    }

    return true;
}

function validatePhone($input) {

    var val = $input.val();
    var regex = /^\d{10}$/;
    
    if (val !== '' && !regex.test(val)) {
        var $formGroup = $input.parent();
        $formGroup.addClass('has-error');
        $formGroup.find('.help-block small').html('El formato es incorrecto. Captura el número a 10 dígitos.');
        return false;
    }

    return true;
}

function setInputErrorKeyup() {
    $('.has-error input').on('keyup', function () {
        var $formGroup = $(this).parent();
        $formGroup.removeClass('has-error');
        $formGroup.find('.help-block small').html('');
    });

    $('.has-error textarea').on('keyup', function () {
        var $formGroup = $(this).parent();
        $formGroup.removeClass('has-error');
        $formGroup.find('.help-block small').html('');
    });
}

function convertToUppercase(inputId) {
    var input = document.getElementById(inputId);
    input.value = normalize(input.value).toUpperCase();
}

var normalize = (function() {
    var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÇç",
        to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuucc",
        mapping = {};

    for (var i = 0, j = from.length; i < j; i++ )
        mapping[ from.charAt( i ) ] = to.charAt( i );

    return function( str ) {
        var ret = [];
        for( var i = 0, j = str.length; i < j; i++ ) {
            var c = str.charAt( i );
            if( mapping.hasOwnProperty( str.charAt( i ) ) )
                ret.push( mapping[ c ] );
            else
                ret.push( c );
        }
        return ret.join( '' );
    }

})();

function getMapKey(inputIn, inputOut){
    let mapKey = document.getElementById(inputOut);
    
    if( mapKey.value == '' ){        
        let input = document.getElementById(inputIn);
        let words = input.value.split(' ');
        let key = words.map( word => word.charAt(0).toUpperCase() ).join('');

        mapKey.value = key;
    }
}





