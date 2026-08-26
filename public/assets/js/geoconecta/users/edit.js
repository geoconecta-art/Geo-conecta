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

    $("#form_update_user").on("submit", function(event) {
        event.preventDefault();

        $loading.show();

        var _form = new FormData(this);
        var action = $(this).attr('action');
        var method = $(this).attr("method");

        $.ajax({
            type: method,
            url: action,
            data: _form,
            processData: false,
            contentType: false,
            success: function(data) {
                window.location.href = window.Laravel.routes['users.index'];
            },
            error: function(xhr, status, error) {
            },
            complete: function() {
                $loading.hide();
            },
        })
    });

});