
@extends('admin.layout.app')

@section('style')
    <style>
        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            padding-left: 0.3rem !important;
        }
        
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Catálogo de apoyos</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">

                            <table class="nowrap table" id="itemsTable">
                                <thead>
                                <tr>
                                    <th width="3%">#</th>                                    
                                    <th>Nombre</th>
                                    <th>Tipo de apoyo</th>
                                    <th>Dependencia</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div><!-- .card-inner -->
                    </div><!-- .card-inner-group -->
                </div><!-- .card -->
            </div><!-- .nk-block -->
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function() {
            updateDataTable(tablePath);
        }); 

        var tablePath = '{{ route('support.catalog.table') }}';
        function updateDataTable() {

            $loading.show();

            var dom_normal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

            $('#itemsTable').DataTable({
                ajax: tablePath ,
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'support_type_name', name: 'support_type_name' },
                    { data: 'dependence_name', name: 'dependence_name' },
                ],
                fnInitComplete: function () {
                    $loading.hide();
                },
                destroy: true,
                responsive: false,
                autoWidth: false,
                dom: dom_normal,
                paginate: false,
                language: spanish
            });
        }

    </script>

@endsection
