
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

        .ine-c {
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
                    <h4 class="nk-block-title">Director Regional</h4>
                </div>
            </div>
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
                        
                        <form id="personForm" action="{{ route('directors.update', $person->id) }}" method="POST" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                                
                        <div class="row gy-4">
                            <div class="col-md-4">
                                @include('admin.people._image-edit')
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
                                                            name="region" required disabled>
                                                        <option selected disabled value="">Selecciona una región</option>
                                                        @foreach ( $regions as $region )
                                                            <option value="{{ $region->region }}" dep="{{ $region->dependence }}"
                                                                @if ( $person->region == $region->region ) selected @endif >
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
                                                required disabled value="old('dependence')"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12 my-3">
                                        <span class="preview-title-lg overline-title">Director Regional</span>
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
                                                value="{{ isset($person->link_first_name) ? $person->link_first_name : '' }}"
                                                required
                                                id="linkFirstName" oninput="convertToUppercase('linkFirstName')"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Apellido Paterno *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="link_last_name_1"
                                                value="{{ isset($person->link_last_name_1) ? $person->link_last_name_1 : '' }}"
                                                required
                                                id="linkLastName1" oninput="convertToUppercase('linkLastName1')" 
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Apellido Materno *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="link_last_name_2"
                                                value="{{ isset($person->link_last_name_2) ? $person->link_last_name_2 : '' }}"
                                                required
                                                id="linkLastName2" oninput="convertToUppercase('linkLastName2')" 
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Teléfono *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="link_phone" maxlength=10
                                                        required value="{{ $person->link_phone }}"
                                                        >
                                                <span class="help-block"><small></small></span>

                                            </div>
                                        </div>
                                    </div>

                            
                                    <input type="hidden" name="link_id" value="{{ $person->link_id }}">

                                    <div class="col-md-12 my-3">
                                        <p class="text-soft">Campos requeridos*</p>
                                        <button type="submit" class="btn btn-outline-light">Actualizar</button>

                                    </div>
    
                                </div>
                            </div>
                        </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('admin.people._dropify-script')
    @include('admin.directors._script')

    <script>
        $(document).ready(function() {
            $('select[name=region]').trigger('change');
        });
    </script>
@endsection

