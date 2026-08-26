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
        <div class="nk-content-body">

            <ul class="nav nav-tabs nav-tabs-s2 mb-3">
                <li class="nk-menu-item" onclick="toggleView(1)" id="desktop_view_link">
                    <a href="javascript:void(0);" class="nk-menu-link">
                        <em class="icon ni ni-laptop"></em> &nbsp; Escritorio 
                    </a>
                </li>

                <li class="nk-menu-item" onclick="toggleView(-1)" id="mobile_view_link">
                    <a href="javascript:void(0);" class="nk-menu-link">
                        <em class="icon ni ni-mobile"></em> &nbsp; Móvil
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                
                <div class="tab-pane active" id="tabDesktop">
                    <div class="content-form d-flex justify-content-center" id="preview_form_content">
                        <div class="card w-100 h-100" id="preview_form_card">
                            <form class="d-flex flex-wrap p-4 w-100" id="inventory_form_container" >
                                @csrf
                                @include('admin.plans._form_view')
        
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

        </div>
    </div>

    <div aria-live="polite" aria-atomic="true" style="position: fixed; min-height: 200px; top: 60px; right: 0px; width: 96%;" id="toast_notification">
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
    <script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
    <script src="{{ asset('assets/js/utils/filterSubareas.js') }}"></script>
    
    <script>
        var id_plan = @json($plan->id);
        
        window.Laravel = {
            routes: {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'plans.preview.validation' : "{{ route('plans.preview.validation', 'id_plan') }}",
                'plans.validate-catastral-key' : "{{ route('plans.validate-catastral-key') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/plans/preview.js') }}"></script>
@endsection