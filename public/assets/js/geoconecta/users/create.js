$(document).ready(function() {

    var drCoverEventImage = $('#image').dropify();

    drCoverEventImage.on('dropify.beforeClear', function (event, element) {
        $('input[name=has_image]').val(0);
    });

    var drCoverEventLogo = $('#logo').dropify();

    drCoverEventLogo.on('dropify.beforeClear', function (event, element) {
        $('input[name=has_logo]').val(0);
    });

    $("#role_id").on("change", function () {
        var selectedIndex = $(this).prop('selectedIndex');

        var showArea = (selectedIndex === 2 || selectedIndex === 3 || selectedIndex === 4);
        var showSubarea = (selectedIndex === 3 || selectedIndex === 4);

        $("#area_id").val("");
        $("#subarea_id").val("");

        $("#area_container_id").toggleClass('d-none', !showArea);
        $("#subarea_container_id").toggleClass('d-none', !showSubarea);
    });

    $("#area_id").on("change", function() {
        var id = $(this).val();
        $(".subarea_option").hide();
        toggleNextField( "#subarea_id", id );
    })

});