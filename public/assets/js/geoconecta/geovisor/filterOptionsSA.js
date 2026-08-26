/**
 * Función que se encarga de filtrar las opciones de los inventarios
 * 
 * @param   string  searchTex representa la cadena de texto que será buscada entre las opciones
 */

function filterOptions( searchText ){
    searchText = searchText.toLowerCase();

    $('.accordion-plan-area').each(function() {
        let area = $(this);
        let areaTitle  = area.find('.accordion-head .title').text().toLowerCase();
        
        if (areaTitle.includes(searchText)) {
            area.show();
            $("#accordion-plan-area-" + area.attr('id')).collapse('show');
        } else {
            $(this).hide();
            $("#accordion-plan-area-" + area.attr('id')).collapse('hide');
        }

        area.find('.accordion-plan-subarea').each( function() {
            let subarea = $(this);
            let subTitle  = subarea.find('.accordion-head .title').text().toLowerCase();

            if( subTitle.includes(searchText) ){
                area.show();
                $("#accordion-plan-area-" + area.attr('id')).collapse('show');
                subarea.show();
                $("#accordion-plan-subarea-" + subarea.attr('id')).collapse('show');
            } else {
                subarea.hide();
                $("#accordion-plan-subarea-" + subarea.attr('id')).collapse('hide');
            }

            subarea.find('.plan-checkbox-container').each(function() {
                let checkbox = $(this);
                let checkboxTitle = checkbox.find('.checkbox-row .custom-checkbox .custom-control-label').text().toLowerCase().trim();

                if( checkboxTitle.includes(searchText) ){
                    area.show();
                    $("#accordion-plan-area-" + area.attr('id')).collapse('show');
                    subarea.show();
                    $("#accordion-plan-subarea-" + subarea.attr('id')).collapse('show');
                    checkbox.show();
                    checkbox.addClass('d-flex');
                } else {
                    checkbox.hide();
                    checkbox.removeClass('d-flex');
                }
            });
        });
    });
}