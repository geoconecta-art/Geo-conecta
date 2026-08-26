$(document).ready(function () {
    $('.dropify').dropify({
        height: 100,
    });

    $("#notes_msg_file_import").show();

    $("#import_excel_plan").on("change", function () {
        let id_plan = $("#id_plan_input").val();
        getExcelHeaders( id_plan );
    });
});