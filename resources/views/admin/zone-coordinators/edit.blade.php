
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
                    <h4 class="nk-block-title">Coordinador de Zona</h4>
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
                        <form id="personForm" action="{{ route('z-coordinators.update', $person->id) }}" 
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

                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="region">Región *</label>
                                            <div class="form-control-wrap ">
                                                <div class="form-control-select">
                                                    <select class="form-control" 
                                                            name="region" required disabled>
                                                        <option selected disabled value="">Selecciona una región</option>
                                                        @foreach ( $regions as $r )
                                                            <option value="{{ $r->region }}" 
                                                                    dep="{{ $r->dependence }}"
                                                                    dir="{{ $r->director }}"
                                                                    r-coord="{{ $r->r_coord }}"
                                                                    zones="{{ $r->zones }}"
                                                                @if ( $person->region == $r->region ) selected @endif >
                                                                {{ $r->region }}
                                                            </option>
                                                        @endforeach
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="zone">Zona *</label>
                                            <div class="form-control-wrap ">
                                                <div class="form-control-select">
                                                    <select class="form-control" 
                                                            name="zone" required disabled>
                                                        <option selected disabled value="">Selecciona una zona</option>
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
                                            <label class="form-label">Coordinador Regional *</label>
                                            <div class="form-control-wrap">
                                                <div class="form-control-select">
                                                    <select class="form-control" name="person_id" required>
                                                        <option selected disabled value="">Búsqueda por Nombre o ID</option>
                                                        foreach ($people as $p)
                                                            <option value="{ $p->id }}"
                                                                if ( $person->person_id == $p->id ) selected endif >
                                                                { $p->name }} (ID: { $p->id }})
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
                                    @include('admin.zone-coordinators._region-info')
                                </div>
                                
                                <div class="row">

                                    <div class="col-md-12 my-3">
                                        <span class="preview-title-lg overline-title">Coordinador de Zona</span>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-4 mb-3 pl-0">
                                            <div class="form-group">
                                                <label class="form-label" for="vote">Tipo *</label>
                                                <div class="form-control-wrap">
                                                    <div class="form-control-select">
                                                        <select class="form-control" 
                                                                name="coord_type" required>
                                                            <option selected disabled value="">Selecciona una opción</option>
                                                            <option value="A" @if ( $person->coord_type == 'A' ) selected @endif >A</option>
                                                            <option value="B" @if ( $person->coord_type == 'B' ) selected @endif>B</option>
                                                            <option value="C" @if ( $person->coord_type == 'C' ) selected @endif>C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @include('admin.people._form-2')

                                    <!--
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            include('admin.people._p-sections-select')
                                        </div>
                                    </div>
                                    -->

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
    @include('admin.zone-coordinators._script')

    <script>

        var zone = '{{ $person->zone }}';

        $(document).ready(function() {
            $('select[name=region]').trigger('change');
            $('select[name=zone]').val(zone);
            $('#anotherZipCode').trigger('change');
        });

        /*
        $(document).ready(function() {
            $('select[name=person_id]').on('change', function () {
                let personId = $(this).val();
                let _token = $token.val();
                $loading.show();

                $.post('/people/get-sections-options', { '_token':_token, 'person_id':personId }, function(data) {
                    $('select[name*=sections]').val(null).trigger('change');
                    $('select[name*=sections]').html(data);
                    $loading.hide();
                });
            });
        });
        */
    </script>

@endsection

