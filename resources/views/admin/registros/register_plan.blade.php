@extends('admin.layout.app')

@section('style')
<style>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <div class="nk-content-inner">
        <div class="nk-block-head nk-block-head-sm pb-2 d-flex">
            <div class="mr-1">
                <a href="{{route('register.inventory.index', $plan->id)}}" class="btn btn-dim btn-primary p-1" title="Volver">
                    <em class="icon ni ni-curve-up-left"></em>
                </a>
            </div>
            <div class="nk-block-between">
                <div class="nk-block-head-content d-md-flex justify-content-between w-100">
                    <h3 class="nk-block-title page-title">
                        Agregar Datos a: <span style="font-weight: 400;" >{{ $plan->name }}</span>
                    </h3>
                </div>
            </div>
        </div>

        <div class="nk-content-body">
            <div class="content-form d-flex justify-content-center" id="preview_form_content">
                <div class="card w-100 h-100">
                    <form class="d-flex flex-wrap p-4 w-100" id="inventory_form_container" method="POST" action="{{ route('register.inventory.store', $plan->id) }}">
                        @csrf
                        @include('admin.registros._form_view')
                        <input type="hidden" name="type_form" value="{{$plan->geometry ?? 'Point'}}">

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dim btn-success mb-3">
                                Guardar
                            </button>
                        </div>
 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 200px; top: 0px; width: 96%;" id="toast_notification">
        <div class="toast-container position-absolute top-0 p-3" style="right: 0rem" >
            <div class="toast show">
                <div class="toast-header justify-content-between">
                    <strong class="me-auto" id="toast_header_title">
                        
                    </strong>
                    
                    <button type="button" class="close" data-dismiss="toast" aria-label="Close" id="toast_close_btn">
                        <em class="icon ni ni-cross-sm"></em>        
                    </button>
                </div>
                <div class="toast-body" id="toast_body">
                    
                </div>
            </div>
        </div>
    </div>

    @include('admin.plans._map-script')
    @include('admin.plans._map-modal')
    @include('admin.plans._test_contrast')
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
    <script src="{{ asset('assets/js/utils/filterSubareas.js') }}"></script>

    <script>
        var id_plan = @json($plan->id);
        
        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'register.inventory.store' : "{{ route('register.inventory.store', 'id') }}",
                'plans.validate-catastral-key' : "{{ route('plans.validate-catastral-key') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/registros/register_plan.js') }}"></script>
    
@endsection