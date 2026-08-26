$(document).ready(function(){
    selectedValues = [];
    
    $(".import-coors-select").on("change", function(){
        let select = $(this);
        let selectID = select.attr('id');
        let selectValue = select.val();
        let preValue = $(`#${selectID}-preview-value`).val();

        if( preValue != ''){
            let index = selectedValues.indexOf( preValue );
            selectedValues.splice( index, 1 );
        }

        selectedValues.push( selectValue );
        $(`#${selectID}-preview-value`).val(selectValue);

        $(".import-coors-select").each(function() {
            let selectItem = $(this);

            if( selectItem.attr('id') != selectID ){
                let auxSelectVal = selectItem.val();

                selectItem.find("option").each(function () {
                    let option = $(this).val();

                    if( selectedValues.includes( option ) && $(this).val() != "" && auxSelectVal != option ){
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

            }
        });
    });
});