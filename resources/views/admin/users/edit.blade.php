
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
                    <h4 class="nk-block-title">Editar Usuario</h4>
                </div>
            </div>

            <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" id="form_update_user">
                @method('PUT')
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
                                    
                                    <div class="mb-5">
                                        <label class="form-label" for="image">Imagen de Perfil</label>
                                        @if ( isset($user->image) and !is_null($user->image) )
                                            <input type="hidden" name="has_image" value="1">
                                            <input type="file" name="image" class="dropify" data-default-file="{{ asset($user->image) }}"
                                                    data-max-file-size="3M" id="image" />
                                        @else
                                            <input type="hidden" name="has_image" value="0">
                                            <input type="file" name="image" class="dropify" data-max-file-size="3M" id="image" />
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-8">
                                    <div class="row">

                                        <div class="col-md-10 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Nombre *</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="name"
                                                           value="{{ $user->name }}" required>
                                                </div>
                                            </div>
                                        </div>
        
                                        
                                        <div class="col-md-10 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Correo Electrónico *</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" name="email"
                                                           value="{{ $user->email }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-10 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="role_id">Privilegios *</label>
                                                <select class="form-control" name="role_id" id="role_id" required>
                                                    <option value="" selected disabled>--Selecciona una opción--</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" @if ($user->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-10 mb-3 {{ $user->id_area ? 'd-block' : 'd-none' }}" id="area_container_id">
                                            <div class="form-group">
                                                <label class="form-label" for="area_id">Dependencia / Organismo *</label>
                                                <select class="form-control" name="area_id" id="area_id">
                                                    <option value="" selected disabled>--Selecciona una opción-</option>
                                                    @foreach ($typeDependencies as $type)
                                                        <optgroup label="{{ $type->name }}">
                                                            @foreach ($type->dependencies as $dependency)
                                                                <option value="{{ $dependency->id }}" @if ($user->id_area == $dependency->id) selected @endif >
                                                                    {{ $dependency->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                    
                                                    {{-- @foreach($areas as $area)
                                                        <option value="{{ $area->id }}" @if ($user->id_area == $area->id) selected @endif >
                                                            {{ $area->name }}
                                                        </option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-10 mb-3 {{ $user->id_subarea ? 'd-block' : 'd-none' }}" id="area_container_id">
                                            <div class="form-group">
                                                <label class="form-label" for="area_id">Subdirección *</label>
                                                <select class="form-control" name="area_id" id="area_id" required>
                                                    <option value="" selected disabled>--Selecciona una opción-</option>
                                                    @foreach($subareas as $subarea)
                                                        <option value="{{ $subarea->id }}" @if ($user->id_subarea == $subarea->id) selected @endif >
                                                            {{ $subarea->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
        
                                        <div class="col-md-12 mb-3">
                                            <label class="mb-0"><strong>Cambiar Contraseña</strong></label>
                                            <div class="text-muted"><small>Llena estos campos sólo si deseas cambiar la contraseña del usuario.</small></div>
                                        </div>
        
                                        <div class="col-md-5 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="password">Contraseña</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" name="password" />
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-5 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" name="password_confirmation" />
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
                                    <button type="submit" class="btn btn-success" id="btn-update-user">Actualizar</button>
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

    <script>
        window.Laravel = {
            routes : {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'users.index' : "{{ route('users.index') }}",
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/users/edit.js') }}"></script>
@endsection

