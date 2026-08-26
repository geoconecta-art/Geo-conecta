$(document).ready(function () {
    restChecks = 10;

    $("#export_pdf_header_counter_container").hide();

    $("#export_pdf_type_select").on("change", function() {
        let selectedValue = $(this).val();

        if( selectedValue == 1 ){
            $("#export_pdf_header_counter_container").show();
            // $("#check_all").hide();
            checkLimit();
        } else {
            $("#export_pdf_header_counter_container").hide();
            // $("#check_all").show();
            changeCheckBox(false)
        }

        changeCounter();
    });

    // Detecta el evento de cambio de los checkbox
    $(".export_pdf_field_check").on("change", function() {
        let checked = $(this).is(":checked");
        let typePDF = $("#export_pdf_type_select").val();

        if( checked )
            restChecks--;
        else
            restChecks++;

        if( typePDF == 1 ){
            changeCounter();
            blockChecks();
        }     
    });

    // Seleccionar todas las checkbox
    $("#check_all").on("click", function (){
        let checked = $(this).is(":checked");
        let typePDF = $("#export_pdf_type_select").val();

        $(".export_pdf_field_check").each( function() {
            if( checked && typePDF == 1 && restChecks == 0 ){
                return false;
            }

            if( $(this).is(":checked") != checked ){
                $(this).prop("checked", checked).change();
            }
        });
    });
});

// Cambia el valor del contador 
function changeCounter(){
    let newClass = restChecks > 6 
        ? 'text-success'
        : restChecks > 3 && restChecks <= 6
            ? 'text-warning' 
            : 'text-danger';

    $("#export_pdf_header_counter").text( restChecks );
    $("#export_pdf_header_counter").removeClass();
    $("#export_pdf_header_counter").addClass(newClass);
}

// Bloquea o activa los checkbox 
function blockChecks(){
    let isDisabled = (restChecks == 0) ? true : false;
    changeCheckBox( isDisabled );
}

// Activa o desactiva los checkbox
function changeCheckBox(isDisabled){
    let checks = $(".export_pdf_field_check");

    checks.each(function() {
        let check = $(this);
        
        if( !check.is(":checked") ){
            check.prop("disabled", isDisabled);
        }
    });
}

// Desactiva los últimos checkbox si se pasa de la cuenta de 10
function checkLimit(){
    let checks = $(".export_pdf_field_check");

    $(checks.get().reverse()).each( function () {
        let check = $(this);

        if( restChecks >= 0 ){
            blockChecks();
            return false;
        }

        if( check.is(":checked") ){
            check.prop("checked", false);
            restChecks++;
        }
    });
}