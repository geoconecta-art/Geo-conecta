
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
                        <h3 class="nk-block-title page-title">Colonias</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">

                            <div class="row mb-5 px-4">
                                <div class="col-12 text-end">
                                    <div class="btn btn-primary" onclick="showModal('crear')">
                                        <span>Crear Colonia</span>
                                        <em class="icon ni ni-home-new"></em>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="nowrap table" id="suburbsTable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Código Postal</th>
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
    </div>

@endsection

@section('script')

    <script>
        window.Laravel = {
            routes : {
                'suburb.update-table' : "{{ route('suburb.update-table') }}",
                'suburb.open-modal' : "{{ route('suburb.open-modal') }}",
                'suburb.create' : "{{ route('suburb.create') }}",
                'suburb.update' : "{{ route('suburb.update', 'id_suburb') }}",
                'suburb.delete' : "{{ route('suburb.delete', 'id_suburb') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/suburbs/index.js') }}"></script>

@endsection
