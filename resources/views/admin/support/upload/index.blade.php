
@extends('admin.layout.app')


@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">

    <style>

        .dropify-wrapper {
            height: 364px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }

        .dropify-wrapper .dropify-message p {
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 20px;
        }
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Cargar Archivo .XLSX</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">

                        @if (session('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                        @endif


                        <form id="upload-form" method="post" action="{{route('support.upload.store')}}" enctype="multipart/form-data">
                    
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row mb-5 px-4">
                                <div class="col-lg-4">
                                    <a href="https://gpsadmin.s3.amazonaws.com/assets/electo/FORMATO+DE+APOYOS.xlsx" download class="btn btn-outline-light">
                                        <span>Ejemplo de archivo</span><em class="icon ni ni-download"></em>
                                    </a>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-5">
                                        <label class="form-label" for="image">Selección del archivo</label>
                                            <input type="file" name="supports" class="dropify" data-max-file-size="3M" data-height="100" id="supports" />
                                    </div>
                                
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div onclick="uploadFile()"
                                        class="btn btn-outline-light">
                                        <span>Cargar Archivo</span><em class="icon ni ni-upload"></em>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div><!-- .card-inner -->
                    </div><!-- .card-inner-group -->
                </div><!-- .card -->
            </div><!-- .nk-block -->
        </div>
    </div>

@endsection

@section('script')

<script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>

<script>

    $(document).ready(function() {

        var drCoverEventImage = $('#supports').dropify();

        //updateDataTable();
       

    });
        

        function uploadFile(){
            $loading.show();
            $('#upload-form').submit();
        }

       

        // function updateDataTable(path) {

        //     $loading.show();

        //     var dom_normal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

        //     $('#itemsTable').DataTable({
        //         ajax: path,
        //         columns: [
        //             { data: 'id', name: 'id' },
        //             { data: 'image', name: 'image' },
        //             { data: 'name', name: 'name' },
        //             { data: 'address', name: 'address' },
        //             { data: 'phone', name: 'phone' },
        //             { data: 'sections', name: 'sections' },
        //             { data: 'director', name: 'director' },
        //             { data: 'actions', name: 'actions', orderable: false, searchable : false }
        //         ],
        //         fnInitComplete: function () {
        //             $loading.hide();
        //         },
        //         destroy: true,
        //         responsive: false,
        //         autoWidth: false,
        //         dom: dom_normal,
        //         paginate: false,
        //         language: spanish
        //     });
        // }

        function deleteItem(id) {

            /*
            $loading.show();
            var _token = $('[name="_token"]').val();
            var _method = 'DELETE';

            $.post('/directors/' + id, {'_token': _token, '_method':_method}, function (response) {
                $loading.hide();
                if (response.success) {
                    showSuccessModal(response.message);
                    updateDataTable();
                } else {
                    showErrorModal(response.error);
                }
            });
            */

        }


    </script>

@endsection
