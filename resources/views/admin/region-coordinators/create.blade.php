
@extends('admin.layout.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">

    <style>
        #map {
            height: 400px;
            width: 100%;
        }

        .dropify-wrapper {
            height: 364px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }

        .dropify-wrapper .dropify-message p {
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 20px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }
    </style>
@endsection

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">

        <div class="components-preview wide-md mx-auto">

            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Crear Coordinador Regional</h4>
                </div>
            </div>

            @if ( Auth::user()->hasRole('Super Administrador') || ( Auth::user()->hasRole('Administrador') &&  !Auth::user()->hasRole('Consultor') ) )
            <form id="personForm" action="{{ route('r-coordinators.store-by-admin') }}" method="POST" enctype="multipart/form-data" >
            @else
            <form id="personForm" action="{{ route('r-coordinators.store') }}" method="POST" enctype="multipart/form-data" >
            @endif
               
                @csrf

                <div class="nk-block">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card card-preview">
                        <div class="card-inner">

                            <div class="row gy-4">

                                <div class="col-md-4">
                                    @include('admin.people._image-create')
                                </div>

                                <div class="col-md-8">

                                    <div class="row">

                                        <div class="col-md-12 my-3">
                                            <span class="preview-title-lg overline-title">Estructura</span>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="region">Región *</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" 
                                                                name="region" required>
                                                            <option selected disabled value="">Selecciona una región</option>
                                                            @foreach ( $regions as $r )
                                                                <option value="{{ $r->region }}" 
                                                                        dep="{{ $r->dependence }}"
                                                                        dir="{{ $r->director }}"

                                                                    {{ old('region') == $r->region ? 'selected' : '' }} >
                                                                    {{ $r->region }}
                                                                </option>
                                                            @endforeach
                                                          
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            @include('admin.people._training')
                                        </div>

                                        <!--
                                        <div class="col-md-8 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Director Regional *</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-control-select">
                                                        <select class="form-control" name="person_id" required>
                                                            <option selected disabled value="">Búsqueda por Nombre o ID</option>
                                                            foreach ($people as $p)
                                                                <option value="{ $p->id }}">
                                                                    { $p->name }} 
                                                                </option>
                                                            endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->

                                    </div>

                                    <div class="region-info">
                                        @include('admin.region-coordinators._region-info')
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12 my-3">
                                            <span class="preview-title-lg overline-title">Coordinador Regional</span>
                                        </div>

                                        @include('admin.people._form-2')

                                        <div class="col-md-12 my-3">
                                            <p class="text-soft">Campos requeridos*</p>
                                            <button type="submit" class="btn btn-outline-light">Crear</button>
                                        </div>
        
                                    </div>
                                
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@section('script')
    @include('admin.people._dropify-script')
    @include('admin.people._map-script')
    @include('admin.region-coordinators._script')

    <script>
        $(document).ready(function() {
            $('#anotherZipCode').trigger('change');
        });
    </script>
@endsection




