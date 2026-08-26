$(document).ready(function () {
    $(".subarea_option").hide();
    $('.' + id_sub_origin ).show();

    $("#direction").on("change", function () {
        var id = $(this).val();
        $(".subarea_option").hide();

        if(id != ""){
            $("#subdirection").removeAttr('disabled');
            $("#subdirection").val("");
            $('.'+id).show();
        } else {
            $("#subdirection").attr('disabled', 'disabled');
        }
    });
});

// Muestra el mapa de contraste 
function showContrastModal(){
    let color = $("input[name=plan_color]").val();

    if( contrastMarker ){
        contrastMarker.setIcon({
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: color,
            fillOpacity: 1,
            scale: 4,
            strokeColor: 'white',
            strokeWeight: 2
        });
    }

    if( contrastLine ){
        contrastLine.setOptions({
            strokeColor: color,
        });
    }

    $("#contrastModal").modal("show");
    $basicModal.modal('hide');
}