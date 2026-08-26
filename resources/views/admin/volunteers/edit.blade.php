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
                    <h4 class="nk-block-title">Incorporación Voluntaria</h4>
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
                        
                        <form id="personForm" 
                            action="{{ route('volunteers.update', $person->id) }}" 
                            method="POST" enctype="multipart/form-data">
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

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="v_type">Tipo *</label>
                                            <div class="form-control-wrap">
                                                <div class="form-control-select">
                                                    <select class="form-control" 
                                                            name="v_type" required>
                                                        <option selected disabled value="">Selecciona una opción</option>
                                                        <option value="1" @if ( $person->v_type == 1 ) selected @endif >SERVIDOR PROMOTOR</option>
                                                        <option value="2" @if ( $person->v_type == 2 ) selected @endif >LIDERAZGO SECCIONAL</option>
                                                        <option value="3" @if ( $person->v_type == 3 ) selected @endif >LIDERAZGO MILITANTE</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Notas</label>
                                            <div class="form-control-wrap">
                                                <textarea class="form-control" style="min-height:60px;"
                                                name="note" 
                                                id="note" 
                                                oninput="convertToUppercase('note')" >{{ $person->note }}</textarea>
                                            </div>
                                        </div>
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
                                                                @if ( $person->section == $s->section ) selected @endif >
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
                                    @include('admin.volunteers._section-info')
                                </div>
                                
                                <div class="row">

                                    <div class="col-md-12 my-3">
                                        <span class="preview-title-lg overline-title">Promotor</span>
                                    </div>
                                    
                                    @include('admin.people._form-2')
                                    
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
    @include('admin.people._map-script')
    @include('admin.volunteers._script')
    
    <script>
        $(document).ready(function() {
            $('select[name=section]').trigger('change');
            $('#anotherZipCode').trigger('change');
        });
    </script>

@endsection

