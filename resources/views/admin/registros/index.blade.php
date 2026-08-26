@extends('admin.layout.app')

@section('content')

    @include('admin.plans._test_contrast')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Datos</h3>
                    </div>

                </div>
            </div>

            <div class="card h-100">
                <div class="card-inner">

                    <div class="row mb-3">
                                
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="id_area">
                                    Dependencia / Organismo
                                </label>

                                <div class="form-control-wrap">
                                    <select class="form-select" name="id_area" data-search="on" id="id_area"
                                        @if ( !Auth::user()->hasRole( "Super Administrador" ) ) disabled @endif>
                                        <option value=" " selected> Todas </option>
                                        @foreach ($directions as $area)
                                            <option value="{{$area->id}}" @if ( Auth::user()->id_area == $area->id ) selected @endif >
                                                {{$area->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Área</label>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="id_subarea" data-search="on" id="id_subarea">
                                        <option value="" selected>Todas</option>
                                        @foreach ($subdirections as $sub)
                                            <option value="{{$sub->id}}" class="subarea_option {{ $sub->area->id }}" hidden> 
                                                {{$sub->name}} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="form-group">
                                <button class="btn btn-primary mt-3" onclick="filterRows()">
                                    Filtrar
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-5">

                        <div class="table-responsive mt-3">
                            <table class="nowrap table" id="mobTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Inventario</th>
                                    <th>Dependencia / Organismo</th>
                                    <th>Área</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
            </div>
            
        </div>
    </div>

@endsection

@section('script')
    <script>
        window.Laravel = {
            routes: {
                'plans.create-from-csv' : "{{ route('plans.create-from-csv') }}",
                'plans.import-get-headers' : "{{ route('plans.import-get-headers') }}",
                'plans.create' : "{{ route('plans.create') }}",

                'register.filter-info' : "{{ route('register.filter-info') }}"
            }
        };
    </script>

    <script src="{{ asset('assets/js/geoconecta/registros/index.js') }}"></script>
@endsection