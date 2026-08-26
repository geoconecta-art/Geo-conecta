$(document).ready( function () {
    $(".public-map-check").on("change", function() {
        let id_plan = $(this).attr("id_plan");
        let geometry = $(this).attr("geometry");
        let id_row = $(this).val();
        let isChecked = $(this).is(":checked");

        if( geometry == "Point" )
            togglePointLine( id_plan, id_row, markers, isChecked );
        else
            togglePointLine( id_plan, id_row, polylines, isChecked );
    });

    $(".public-map-all-check").on("change", function() {
        let id_plan = $(this).attr("id_plan");
        let isChecked = $(this).is(":checked");

        $(".public-map-check").each( function() {
            let id_plan_row = $(this).attr("id_plan");    

            if( id_plan_row == id_plan ){
                $(this).prop("checked", isChecked).change();
            }
        });
    });
});

// Cambia el estado de la línea o punto
function togglePointLine(id_plan, id_row, arrayMap, toggle){
    if( toggle ){
        arrayMap[id_plan][id_row].setMap(map);
    } else {
        arrayMap[id_plan][id_row].setMap(null);
    }
}