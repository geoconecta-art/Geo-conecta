
@extends('admin.layout.app')


@section('style')
    <style>
    </style>
@endsection

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">

        <div class="components-preview wide-md mx-auto">

            <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Crear Corporación</h4>
                </div>
            </div>

            <form action="{{ route('corporations.store') }}" method="POST" enctype="multipart/form-data">
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
                                
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Nombre *</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" 
                                                name="name"
                                                value="{{ old('name', $item->name) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="manager">Representante *</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" 
                                                name="manager"
                                                value="{{ old('manager', $item->manager) }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <p class="text-soft">Campos requeridos*</p>
                                </div>

                                <div class="col-md-12">
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
    <script>
      

    </script>
@endsection

