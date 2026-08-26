
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
                        <h3 class="nk-block-title page-title">Beneficiados</h3>
                    </div>

                    <!--
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li><a onclick="showUploadFileModal()" class="btn btn-white btn-outline-light"><em class="icon ni ni-upload-cloud"></em><span>Importar</span></a></li>
                                    <li><a href="#" class="btn btn-primary"><em class="icon ni ni-plus"></em><span>Agregar</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    -->

                </div>
            </div>

            <div class="nk-block">
                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">

                            <div class="row mb-5 px-4">
                                <div class="col-12 text-right">
                                    <div class="btn btn-outline-light">
                                        <span>Imprimir Reporte</span><em class="icon ni ni-printer"></em>
                                    </div>
                                    <div href="" class="btn btn-outline-light">
                                        <span>Agregar Beneficiado</span><em class="icon ni ni-user-add-fill"></em>
                                    </div>
                                </div>
                            </div>

                            <table class="nowrap table table-responsive" id="applicationsTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Nombre(s)</th>
                                    <th>Apellidos</th>
                                    <th>Edad</th>
                                    <th>Estado Civil</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Domicilio</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Fecha</th>
                                    <th>Ubicación</th>
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

    <script>

        $(document).ready(function() {
            $loading.show();
            updateDataTable();
        });

        function updateDataTable(path) {

            var dom_normal = '<"row justify-between m-0 gy-2 px-3 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';


            $('#applicationsTable').DataTable({
                ajax: '/admin/beneficiados',
                columns: [
                    { data: 'id' },
                    { data: 'code' },
                    { data: 'name' },
                    { data: 'last_name' },
                    { data: 'age' },
                    { data: 'marital_status' },
                    { data: 'birthday' },
                    { data: 'address' },
                    { data: 'email' },
                    { data: 'phone' },
                    { data: 'date' },
                    { data: 'coordinates' },
                ],
                fnRowCallback: rowCallBack,
                fnInitComplete: function () {
                    $loading.hide();
                },
                destroy: true,
                responsive: false,
                autoWidth: false,
                dom: dom_normal,
                lengthMenu: [ [100, 200, 500, -1], [100, 200, 500, 'Todos'] ],
                language: spanish
            });
        }

        function rowCallBack(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            /*
            var html = '';

            if ( aData['slug'] != null ) {
                var html = '<a href="/pase-personal/' + aData['slug'] + '" target="_blank" class="btn btn-round btn-icon btn-primary mr-1  mb-1">' +
                    '<em class="icon ni ni-mail"></em>' +
                    '</a>';
            }

            var name = (aData['name'] == null) ? '' : aData['name'];
            var surname_1 = (aData['surname_1'] == null) ? '' : aData['surname_1'];
            var surname_2 = (aData['surname_2'] == null) ? '' : aData['surname_2'];

            html += '<a href="/admin/invitados/' + aData['id'] + '/modificar" class="btn btn-round btn-icon btn-light mr-1 mb-1">' +
                '<em class="icon ni ni-edit"></em>' +
                '</a>' +
                '<button class="btn btn-round btn-icon btn-danger mb-1" onclick="showDeleteModal(\'' + aData['id'] + '\', \'' + ' a ' + name + ' ' + surname_1 + ' ' + surname_2 + ' de la lista ' + '\')">' +
                '<em class="icon ni ni-trash-alt"></em>' +
                '</button>';

            $('td:eq(11)', nRow).html(html);
            */
        }

       /*
        function deleteItem(id) {
            $loading.show();
            var _token = $('[name="_token"]').val();
            $.post('/guests/destroy', {'_token': _token, 'id':id}, function (response) {
                $loading.hide();
                if (response.success) {
                    updateInfo();
                    updateDataTable();
                    showSuccessModal(response.message);
                } else {
                    showErrorModal(response.error);
                }
            });
        }
        */
   

    </script>

@endsection
