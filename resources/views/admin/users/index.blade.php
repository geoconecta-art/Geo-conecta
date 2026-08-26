
@extends('admin.layout.app')

@section('style')
    <style>
        table.dataTable.nowrap th, table.dataTable.nowrap td {
            white-space: normal !important;
        }
    </style>
@endsection

@section('content')

    @include('admin.plans._test_contrast')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Usuarios</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">

                            <div class="row mb-5 px-4">
                                <div class="col-12 text-end">
                                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                                        <span>Crear Usuario</span><em class="icon ni ni-user-add-fill"></em>
                                    </a>
                                </div>
                            </div>

                            <table class="nowrap table" id="usersTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Correo Electrónico</th>
                                    <th>Rol</th>
                                    <th>Dependencia / Organismo</th>
                                    <th>Acciones</th>
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
        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'users.index' : "{{ route('users.index') }}",
                'users.destroy' : "{{ route('users.destroy', 'id_user') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/users/index.js') }}"></script>

@endsection
