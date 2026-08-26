@extends('admin.layout.app')

@section('style')
    <style>
        .input_draggable:hover, .input_draggable.active {
            cursor: move;
            background-color: #ebeef2;
        }

        .dropify-wrapper {
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }

        .dropify-wrapper .dropify-message p {
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 20px;
        }
    </style>

<link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">

            <div class="nk-block-head nk-block-head-sm pb-2">
                
                <div class="nk-block-between">
                    <div class="nk-block-head-content w-100">
                        <h3 class="nk-block-title page-title col-12 w-100">
                            DISEÑO DE INVENTARIO
                        </h3>

                        <div class="d-md-flex justify-content-between w-100">
                            <div class="nk-block-title page-title col-12 col-md-8 d-flex">
                                <span class="fw-light text-truncate w-auto">
                                    {{ $plan->name }}
                                </span>
    
                                <button class="btn btn-dim btn-success rounded-circle border-0 w-fit p-1 ml-3" id="btn-edit-plan"
                                    onclick="showModal('{{ $plan->id }}/edit')">
                                    <em class="icon ni ni-edit"></em>
                                </button>
                            </div>
    
                            <div class="col-12 col-md-3 d-flex flex-wrap flex-md-nowrap mb-3 p-0 justify-content-end">
    
                                <div class="col-5 col-md-auto btn btn-sm btn-outline-light d-flex justify-content-center mr-2" id="btn-add-field">
                                    <em class="icon ni ni-plus"></em> 
                                    <span id="add_field_title_btn">
                                        Agregar Campo
                                    </span>
                                </div>
        
                                <a href="{{ route('plans.preview', $plan->id) }}" class="col-5 col-md-auto btn btn-sm btn-outline-light d-flex justify-content-center"
                                    target="_blank" rel="noopener noreferrer">
                                    <em class="icon ni ni-eye"></em> 
                                    <span id="preview_title_btn">Vista Previa</span>
                                </a>
        
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="content-form d-flex">
                <div class="card h-100 col-12 col-xxl-9 p-4">
                    @include('admin.plans._form_view')

                    <div class="col-12 mt-3 d-flex justify-content-end">
                        <a href="{{ route('register.inventory.index', $plan->id) }}" class="w-auto btn btn-sm btn-success mt-md-3 d-flex justify-content-center"
                            rel="noopener noreferrer">
                            <em class="icon ni ni-save"></em>
                            <span id="preview_title_btn">Guardar</span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 h-100 px-4">

                    <div class="card" id="field-form">
                        <div class="nk-block-head-content mr-3 ml-3 mt-3 d-flex justify-content-between">
                            <h5 class="col-11" id="field-form-title"></h5>
                            <div class="close" id="close-field-form">
                                <em class="icon ni ni-cross"></em>
                            </div>
                        </div>
                        <hr class="w-100">

                        <div class="invalid-feedback col-12" id="error-msg"></div>


                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label for="name_field" class="form-label">Título</label>
                                <input type="text" class="form-control" id="name_field" placeholder="Título del campo"
                                    name="name_field" value="">
                            </div>
                        </div>

                        <div class="mb-3 col-12" id="type_data_input">
                            <label for="type_data" class="form-label">Tipo</label>
                            <select class="form-control" id="type_data" name="type_data">
                                <option value="" selected>Seleccione una opción</option>
                                <option size="12" value="text"> Texto de una sola línea </option>
                                <option size="4" value="number">Número</option>
                                <option size="4" value="date">Fecha</option>
                                <option size="4" value="time">Hora</option>
                                <option size="12" value="email">Correo</option>
                                <option size="12" value="textarea">Texto varias líneas</option>
                                <option size="6" value="datetime-local">Tiempo</option>
                                <option size="12" value="image">Imagen</option>
                                <option size="12" value="file">Archivo</option>
                                <option size="4" value="radio">Selección Única</option>
                                <option size="4" value="checkbox">Selección Múltiple</option>
                                <option size="6" value="select">Menú Desplegable</option>
                            </select>
                        </div>

                        {{-- <div class="mb-3 col-12 hidden">
                            <label for="size_field" class="form-label">Tamaño</label>
                            <select class="form-control" id="size_field" name="field_size">
                                <option value="" selected>Seleccione una opción</option>
                                <option value="3"> 1/4 </option>
                                <option value="4"> 1/3 </option>
                                <option value="6"> 1/2 </option>
                                <option value="8"> 2/3 </option>
                                <option value="9"> 3/4 </option>
                                <option value="12"> 1 </option>
                            </select>
                        </div> --}}

                        <div class="mb-3 col-12" id="options_form_container">
                            <label for="options_form" class="form-label">Opciones</label>
                            <textarea class="form-control" id="options_form" rows="3"></textarea>
                            <div class="form-text" id="msg_options_form"></div>
                        </div>

                        <input type="hidden" name="index" id="index_field">

                        @if ( !in_array( "address", $plan->attributes ) )
                            <div class="d-flex align-items-center px-2">
                                <hr class="flex-fill">
                                <span class="mx-2">ó</span>
                                <hr class="flex-fill">
                            </div>

                            <div route="{{ route('plans.create.add-address', $plan->id) }}" id="btn-add-address"
                                class="btn btn-sm btn-outline-light mt-2 mt-md-0 d-flex justify-content-center mx-2 btn-add-quick-fields">
                                <em class="icon ni ni-map"></em>
                                <span>Agregar Dirección</span>
                            </div>
                        @endif

                        @if ( !in_array( "catastral", $plan->attributes ) )
                            <div class="d-flex align-items-center px-2">
                                <hr class="flex-fill">
                                <span class="mx-2">ó</span>
                                <hr class="flex-fill">
                            </div>

                            <div route="{{ route('plans.create.add-catastral-key', $plan->id) }}" id="btn-add-catastral-key"
                                class="btn btn-sm btn-outline-light mt-2 mt-md-0 d-flex justify-content-center mx-2 btn-add-quick-fields">
                                <em class="icon ni ni-home"></em>
                                <span>Agregar Clave Catastral</span>
                            </div>
                        @endif

                        <hr class="w-100">

                        <div class="w-full d-flex justify-content-end mb-3">
                            <div class="btn btn-primary mr-md-2 mb-3 mb-md-0" id="btn-save-field">
                                <span>Guardar</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


        </div>
    </div>
    
    @include('admin.plans._map-script')
    @include('admin.plans._map-modal')
    @include('admin.plans._test_contrast')
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="{{ asset('assets/js/utils/filterSubareas.js') }}"></script>

    <script>
        var id_plan = @json($plan->id);
        var formFieldState = false;
        var modalFormState = false;
        var index_html = @json($index);

        window.Laravel = {
            routes: {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'plan.update' : "{{ route('plan.update', 'id_plan') }}",
                'plans.form.field.delete' : "{{ route('plans.form.field.delete', ['id_plan', 'index']) }}",
                'plans.form.field.update-order' : "{{ route('plans.form.field.update-order', 'id_plan') }}",
                'plans.validate-catastral-key' : "{{ route('plans.validate-catastral-key') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/plans/plan_form.js') }}"></script>

    {{-- ACCIONES PARA CAMBIAR EL DISEÑO DEL INPUT DE IMAGEN --}}
    <script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
@endsection