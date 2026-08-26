
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
        
        .ine-c {
            display: none;
        }
    </style>
@endsection

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">

        <div class="components-preview wide-md mx-auto">

            <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Crear Director Regional</h4>
                </div>
            </div>

            @if ( Auth::user()->hasRole('Super Administrador') || ( Auth::user()->hasRole('Administrador') &&  !Auth::user()->hasRole('Consultor') ) )
            <form id="personForm" action="{{ route('directors.store-by-admin') }}" method="POST" enctype="multipart/form-data" >
            @else
            <form id="personForm" action="{{ route('directors.store') }}" method="POST" enctype="multipart/form-data" >
            @endif

                @csrf

                <div class="nk-block">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
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
                                                            @foreach ( $regions as $region )
                                                                <option value="{{ $region->region }}" dep="{{ $region->dependence }}"
                                                                    {{ old('region') == $region->region ? 'selected' : '' }} >
                                                                    {{ $region->region }}
                                                                </option>
                                                            @endforeach
                                                          
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label" for="dependence">Dirección *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="dependence"
                                                    required readonly value="{{ old('dependence') }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <div class="row">

                                        <div class="col-md-12 my-3">
                                            <span class="preview-title-lg overline-title">DIRECTOR REGIONAL</span>
                                        </div>

                                        @include('admin.people._form-1')
        
                                        <div class="col-md-12 mt-5 mb-3">
                                            <span class="preview-title-lg overline-title">ENLACE REGIONAL</span>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Nombre *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="link_first_name"
                                                    required 
                                                    id="linkFirstName" oninput="convertToUppercase('linkFirstName')" 
                                                    value="{{ old('link_first_name') }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Apellido Paterno *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="link_last_name_1"
                                                    required
                                                    id="linkLastName1" oninput="convertToUppercase('linkLastName1')" 
                                                    value="{{ old('link_last_name_1') }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Apellido Materno *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="link_last_name_2"
                                                    required
                                                    id="linkLastName2" oninput="convertToUppercase('linkLastName2')" 
                                                    value="{{ old('link_last_name_2') }}"
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Teléfono *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="link_phone" maxlength=10
                                                    required
                                                    value="{{ old('link_phone') }}"
                                                    >
                                                    <span class="help-block"><small></small></span>
                                                </div>
                                            </div>
                                        </div>

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
    @include('admin.directors._script')
@endsection

