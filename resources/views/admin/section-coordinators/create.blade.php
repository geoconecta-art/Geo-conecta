
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
                    <h4 class="nk-block-title">Crear Coordinador Seccional</h4>
                </div>
            </div>

                @if ( Auth::user()->hasRole('Super Administrador') || ( Auth::user()->hasRole('Administrador') &&  !Auth::user()->hasRole('Consultor') ) )
                <form id="personForm" action="{{ route('s-coordinators.store-by-admin') }}" method="POST" enctype="multipart/form-data" >
                @else
                <form id="personForm" action="{{ route('s-coordinators.store') }}" method="POST" enctype="multipart/form-data" >
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

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="section">Sección *</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control" 
                                                                name="section" required>
                                                            <option selected disabled value="">Búsqueda por sección</option>
                                                            @foreach ( $sections as $s )
                                                                <option value="{{ $s->section }}" 
                                                                    {{ old('section') == $s->section ? 'selected' : '' }} >
                                                                    {{ $s->section }}
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
                                    </div>

                                    <div class="section-info">
                                        @include('admin.section-coordinators._section-info')
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12 my-3">
                                            <span class="preview-title-lg overline-title">Coordinador Seccional</span>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="col-md-4 mb-3 pl-0">
                                                @if ( App\Models\User::find(Auth::user()->id)->hasRole('Capturista') )
                                                    <div class="form-group">
                                                        <label class="form-label" for="coord_type">Tipo *</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="coord_type"
                                                            value="A" readonly required>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label class="form-label" for="coord_type">Tipo *</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-control-select">
                                                                <select class="form-control" 
                                                                        name="coord_type" required>
                                                                    <option selected disabled value="">Selecciona una opción</option>
                                                                    <option value="A">A</option>
                                                                    <option value="B">B</option>
                                                                    <option value="C">C</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
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
    @include('admin.section-coordinators._script')

    <script>
        $(document).ready(function() {
            $('#anotherZipCode').trigger('change');
        });

        @if ( !$errors->any() )

            var storeSec = localStorage.getItem('storeSec');

            if ( storeSec == 1 ) {

                localStorage.setItem('storeSec', 0);

                Swal.fire(
                    '¡Alta exitosa!',
                    'Coordinador Seccional registrado correctamente',
                    'success'
                );
            }
        @endif
    </script>

    <script>
    </script>

@endsection

