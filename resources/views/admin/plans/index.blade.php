@extends('admin.layout.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.plans._test_contrast')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Inventarios</h3>
                    </div>

                </div>
            </div>

            <div class="card h-100">
                <div class="card-inner">

                    <div class="row mb-5">
                        
                        <div class="col-12 text-right">

                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-dim btn-secondary mr-md-2 mb-3 mb-md-0" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Crear Inventario</span>
                                    <em class="icon ni ni-caret-down-fill"></em>
                                </a>
                            
                                <div class="dropdown-menu">
                                    <ul class="link-list-opt">
    
                                        <li>
                                            <a href="#" onclick="createInventoryOpenModal(event, '{{ route('plans.modal') }}')">
                                                <span>
                                                    Nuevo Inventario
                                                </span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#" onclick="createInventoryOpenModal(event, '{{ route('plans.modal-csv') }}')">
                                                <span>
                                                    Nuevo Inventario de Tabla CSV
                                                </span>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>

                            {{-- <div class="btn btn-primary mr-md-2 mb-3 mb-md-0" onclick="showModal('open-modal')">
                                <span>Nuevo Inventario</span><em class="icon ni ni-plus"></em>
                            </div> --}}
                            
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="nowrap table" id="plans_table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Tipo de Inventario</th>
                                    <th>Fecha de Creación</th>
                                    <th>Dependencia / Organismo</th>
                                    <th>Área</th>
                                    <th>No. Registros</th>
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
    <script>
        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",
                // 'plans.create' : "{{ route('plans.create') }}",

                'plans.update.table' : "{{ route('plans.update.table') }}",
                'plan.confirm-delete' : "{{ route('plan.confirm-delete', 'id_plan') }}",
                'plan.delete' : "{{ route('plan.delete', 'id_plan') }}",

                'register.inventory.export-open-modal' : "{{ route('register.inventory.export-open-modal', 'id_plan') }}",
                'register.inventory.export-pdf' : "{{ route('register.inventory.export-pdf', 'id_plan') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/plans/index.js') }}"></script>
@endsection