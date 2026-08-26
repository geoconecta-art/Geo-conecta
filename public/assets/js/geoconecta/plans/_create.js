$(document).ready(function() {
    toggleNextField("#subdirection", $("#direction").val());

    $("#direction").on("change", function () {
        var id = $(this).val();
        $(".subarea_option").hide();
        toggleNextField("#subdirection", id);
        $("#subdirection").val('').change();
    });

    $("#subdirection").on("change", function () {
        var id = $(this).val();

        toggleNextField( "#name_planeacion", id );
    });

    $("#name_planeacion").on("change", function() {
        var plan = $(this).val();

        toggleNextField( "#geometry", plan );
    });
});

// Muestra el modal de contraste
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

// Cierra el modal
function closeContrastModal() {
    $("#contrastModal").modal("hide");
}