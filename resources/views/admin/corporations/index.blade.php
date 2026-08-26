
@extends('admin.layout.app')

@section('style')
    <style>
        table.dataTable.nowrap th, table.dataTable.nowrap td {
            white-space: normal !important;
        }
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Corporaciones</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner py-5">

                            <div class="row mb-5">
                                <div class="col-12 text-end">

                                    <a href="{{ route('corporations.create') }}" 
                                        class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                        <span>Agregar Corporación</span><em class="icon ni ni-user-add-fill"></em>
                                    </a>

                                </div>
                            </div>

                            <table class="nowrap table" id="itemsTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Representante</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->

    <script>

        $(document).ready(function() {
            updateDataTable();
        });

        function updateDataTable(path) {

            $loading.show();

            $('#itemsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    filename: 'Corporaciones',
                    className: 'btn btn-sm btn-primary mb-3',
                    text: '<em class="icon ni ni-download"></em> Exportar Excel',
                }],
                ajax: '{{ route('corporations.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'manager', name: 'manager' },
                    { data: 'actions', name: 'actions', orderable: false, searchable : false }
                ],
                fnInitComplete: function () {
                    $loading.hide();
                },
                destroy: true,
                responsive: false,
                autoWidth: false,
                paginate: false,
                language: spanish
            });
        }

        function deleteItem(id) {

            $loading.show();
            var _token = $('[name="_token"]').val();
            var _method = 'DELETE';

            $.post('/corporations/' + id, {'_token': _token, '_method':_method}, function (response) {
                $loading.hide();
                if (response.success) {
                    showSuccessModal(response.message);
                    updateDataTable();
                } else {
                    showErrorModal(response.error);
                }
            });

        }


    </script>

@endsection
