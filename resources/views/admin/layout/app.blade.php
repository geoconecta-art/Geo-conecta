<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('img/escudo.png') }}">
    <!-- Page Title  -->
    <title>Admin</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=2.4.0') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/css/autocomplete.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/geoconecta/dropify.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

    @yield('style')

    <style>
        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #ee781d;
            border-color: #ee781d;
        }
    </style>
</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- sidebar @s -->
        @include('admin.layout.sidebar')
        <!-- sidebar @e -->
        
        <!-- wrap @s -->
        <div class="nk-wrap ">
            @include('admin.layout.header')
            <div class="nk-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <div class="nk-footer">
                <div class="container-fluid">
                    <div class="nk-footer-copyright text-center">&copy; Todos los derechos reservados.</div>
                </div>
            </div>
        </div>
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->

{{--Loading--}}
<div id="loading">
    <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<!-- modal content -->
<div id="basicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel" 
    aria-hidden="true">
    <form id="modalForm"> 
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="basicModalLabel">Modal Heading</h4></div>
                    <div class="modal-body">
                        <h4>Overflowing text to show scroll behavior</h4>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </form>
</div>
<!-- /.modal -->


<!-- JavaScript -->
<script src="{{ asset('assets/js/bundle.js?ver=2.4.0') }}"></script>
<script src="{{ asset('assets/js/scripts.js?ver=2.4.0') }}"></script>
<script src="{{ asset('plugins/inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('js/basic.js') }}"></script>
<script src="/plugins/autocomplete/jquery.autocomplete.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
<script src="{{ asset('assets/js/geoconecta/layout/sidebar.js') }}"></script>

<script>
    function validateRequired($input) {

        var value = $input.val();

        if ( value === '' || value === null ) {
            success = false;
            var $formGroup = $input.parent();
            $formGroup.addClass('has-warning');
            $formGroup.find('.help-block small').html('Campo requerido.');
            return false;
        }

        return true;
    }

    function showEditLevelModal(personId) {
        $loading.show();
        $('#basicModal .modal-dialog').removeClass('modal-lg');
        var _token = $('[name="_token"]').val();

        $.post('/people/edit-modal', {
            'person_id':personId, 
            '_token':_token 
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $basicModal.modal('show');
        });
    }

    function updateLevel() {
        $basicModal.modal('hide');
        var form = $('#modalForm')[0];
        var param = {};
        param.action = '/people/update';
        param.method = 'post';
        submitFormModal(form, param, $('#basicModal'), onSubmitFormSuccess);
    }

    function submitFormModal(targetForm, parameters, $modal, onSubmitFormSuccess) {

        $loading.show();

        var formData = new FormData(targetForm);
        if (typeof (parameters.token ) != 'undefined')
            formData.append('_token', parameters.token);
        else {
            formData.append('_token', $('[name="_token"]').val());
        }

        $.ajax({
            url: parameters.action,
            type: parameters.method,
            data: formData,
            processData: false,
            contentType: false,

            success: function(response) {

                if (typeof(response) != 'object')
                    response = JSON.parse(response);

                if (response.success) {
                    showSuccessModal(response.message);

                    $modal.modal('hide');

                    if (typeof (response.redirect) != 'undefined' && response.redirect !== '')
                        window.location.href = response.redirect;
                }
                else {
                    var message = response.error || response.message;
                    showErrorModal('Error', message);
                }

                if (typeof onSubmitFormSuccess === 'function')
                    onSubmitFormSuccess(response);
            },

            error: function () {
                showErrorModal('Error', 'Error Interno del Servidor');
            },

            complete: function(data) {
                $loading.hide();
            }

        });
    }

    $(document).ready(function() {
        setAutocompleteEvent();
    });

    function setAutocompleteEvent() {
        var path = `{{ route('search.get-info') }}`; 

        $loading.show();

        $.get(path, function(data){
            $loading.hide();

            $('#autocomplete').autocomplete({
                lookup: data,
                groupBy: 'category',
                minChars: 2,
                formatResult: function(suggestion, currentValue){
                    return suggestion.value;
                },
                onSelect: function(suggestion) {
                    let id = suggestion.id;
                    let action = suggestion.route;
                    let _token = $token.val();

                    window.open(action,  '_blank');
                    $("#autocomplete").autocomplete("close");
                    $("#autocomplete").val('');
                }
            });

        }).fail( function( xhr, status, errors ) {
            $loading.hide();
        }).done( function() {
            $loading.hide();
        });
    }

</script>


@yield('script')

</body>

</html>
