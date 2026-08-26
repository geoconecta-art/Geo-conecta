<script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>

<script>
    var drCoverEventImage;
    var drCoverEventINE;

    $(document).ready(function() {
        drCoverEventImage = $('#image').dropify({
            messages: {
                default: 'Arrastra y suelta un archivo aquí o haz clic',
                replace: 'Arrastra y suelta o haz clic para reemplazar',
                remove: 'Eliminar',
                error: 'Ooops, algo salió mal.'
            },
            showRemove: true,
            showErrors: true,
            errorsPosition: 'outside',
            showCamera: true
        });

        drCoverEventImage.on('dropify.beforeClear', function (event, element) {
            $('input[name=has_image]').val(0);
        });

        drCoverEventINE = $('#ineImage').dropify({
            messages: {
                default: 'Arrastra y suelta un archivo aquí o haz clic',
                replace: 'Arrastra y suelta o haz clic para reemplazar',
                remove: 'Eliminar',
                error: 'Ooops, algo salió mal.'
            },
            showRemove: true,
            showErrors: true,
            errorsPosition: 'outside',
            showCamera: true
        });

        drCoverEventINE.on('dropify.beforeClear', function (event, element) {
            $('input[name=has_ine_image]').val(0);
        });
    });
</script>