$(document).ready(function() {
    $(".btn-plan-delete").on("click", function() {
        var id_plan = $(this).attr("id");
        if( $("#list_map_options").children().length >= 2 ){
            $("#li-" + id_plan).remove();
        }
    });
});