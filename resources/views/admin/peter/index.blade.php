
@extends('admin.layout.app')

@section('style')
    <style>
        .link-list-plain div {
            display: flex;
            align-items: center;
            padding: 0.5rem 1.25rem;
            color: #526484;
            transition: all .4s;
            line-height: 1.4rem;
            position: relative;
        }
        
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">{{ $title }}</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner py-5">

                            <div class="row mb-5">
                                <div class="col-12 text-right">

                                    @if ( !Auth::user()->hasRole('Consultor') )
                                    <a href="{{ route('peter.create') }}" 
                                        class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                        <span>Agregar {{ $title }}</span><em class="icon ni ni-user-add-fill"></em>
                                    </a>
                                    @endif

                                   
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="nowrap table" id="itemsTable">
                                    <thead>
                                    <tr>
                                        <th>Fecha de Captura</th>
                                        <th width="3%">#</th>
                                        <th width="15%">Imagen</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Domicilio</th>
                                        <th>Región</th>
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

    <script src="/plugins/datatables/datetime.js"></script>
    <script src="/plugins/datatables/date-euro.js"></script>

    <script>

        localStorage.setItem('storePeter', 0);

        var tablePath = '{{ route('peter.table') }}' + '?type=8';

        $(document).ready(function() {
            updateDataTable();
        });

        function onSubmitFormSuccess() {
            updateDataTable();
        }

        function updateDataTable() {

            $loading.show();

            var dom_normal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

            $('#itemsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    filename: 'Pedro en tu Casa',
                    className: 'btn btn-sm btn-primary mb-3',
                    text: '<em class="icon ni ni-download"></em> Exportar Excel',
                }],
                ajax: tablePath,
                columns: [
                    { data: 'f_created_at', name: 'f_created_at', targets: 0, type: 'date-euro' },
                    { data: 'order', name: 'order' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address' },
                    { data: 'region', name: 'region' },
                    { data: 'actions', name: 'actions', orderable: false, searchable : false }
                ],
                fnInitComplete: function () {
                    $loading.hide();
                },
                order: [[ 0, 'desc' ]],
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'Todos']],
                destroy: true,
                responsive: false,
                autoWidth: false,
                language: spanish
            });
        }

        function destroy(id) {

            $loading.show();
            var _token = $('[name="_token"]').val();
            var _method = 'DELETE';

            $.post('/peter/' + id, {
                '_token': _token, 
                '_method':_method
            }, function (response) {
                $loading.hide();
                if ( response.success ) {
                    showSuccessModal(response.message);
                    setTimeout(updateDataTable, 2000);
                } else {
                    showWarningModal(response.error);
                }
            });

        }

    </script>

@endsection
