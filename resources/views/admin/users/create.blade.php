
@extends('admin.layout.app')


@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">

    <style>

        .dropify-wrapper {
            height: 364px;
            border: 1px solid #dbdfea;
            border-radius: 4px;
        }

        .dropify-wrapper .dropify-message p {
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 20px;
        }
    </style>
@endsection

@section('content')

@include('admin.plans._test_contrast')

<div class="nk-content-inner">
    <div class="nk-content-body">

        <div class="components-preview wide-md mx-auto">

            <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Crear Usuario</h4>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="nk-block">

                    <div class="card card-preview">
                        <div class="card-inner">

                            <div class="row gy-4">
                                <div class="col-md-4">
                                    <div class="mb-5">
                                        <label class="form-label" for="image">Imagen de Perfil</label>
                                        <input type="hidden" name="has_image" value="0">
                                        <input type="file" name="image" class="dropify" data-max-file-size="3M" id="image" />
                                    </div>

                                    {{-- <div>
                                        <label class="form-label" for="logo">Logotipo</label>
                                        <input type="hidden" name="has_logo" value="0">
                                        <input type="file" name="logo" class="dropify" data-max-file-size="3M" data-height="200" id="logo"/>
                                    </div> --}}
                                </div>

                                <div class="col-md-8">
                                    
                                    <div class="row">

                                        <div class="col-md-10 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Nombre *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="name" id="name"
                                                           value="{{ old('name', $user->name) }}" required>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-10 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Correo Electrónico *</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" name="email" id="email"
                                                           value="{{ old('email', $user->email) }}" required>
                                                </div>
                                            </div>
                                        </div>

                                       
                                        <div class="col-md-10 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="role_id">Privilegios *</label>
                                                <select class="form-control" name="role_id" id="role_id" required>
                                                    <option value="" selected disabled>--Selecciona una opción-</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-10 mb-3 d-none" id="area_container_id">
                                            <div class="form-group">
                                                <label class="form-label" for="area_id">Dependencia / Organismo *</label>
                                                <select class="form-control" name="area_id" id="area_id">
                                                    <option value="" selected disabled>--Selecciona una opción-</option>
                                                    @foreach ($typeDependencies as $type)
                                                        <optgroup label="{{ $type->name }}">
                                                            @foreach ($type->dependencies as $dependency)
                                                                <option value="{{ $dependency->id }}">
                                                                    {{ $dependency->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-10 mb-3 d-none" id="subarea_container_id">
                                            <div class="form-group">
                                                <label class="form-label" for="subarea_id">Subirección *</label>
                                                <select class="form-control" name="subarea_id" id="subarea_id" required>
                                                    <option value="" selected disabled>--Selecciona una opción-</option>
                                                    @foreach($subareas as $subarea)
                                                        <option value="{{ $subarea->id }}" class="subarea_option {{ $subarea->id_area }}" >
                                                            {{ $subarea->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
        
                                        <div class="col-md-5 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="password">Contraseña *</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" name="password" id="password" required />
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-5 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="password_confirmation">Confirmar Contraseña *</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <p class="text-soft">Campos requeridos*</p>
                                        </div>
        
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-light">Crear</button>
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
    <script src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/utils/filterSubareas.js') }}"></script>
    <script src="{{ asset('assets/js/geoconecta/users/create.js') }}"></script>

    <script>
        window.Laravel = {
            routes: {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",
                'plans.create' : "{{ route('plans.create') }}",
            }
        };
    </script>
@endsection

