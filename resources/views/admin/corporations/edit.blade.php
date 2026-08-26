
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
                    <h4 class="nk-block-title">Corporación</h4>
                </div>
            </div>

            <form id="personForm" action="{{ route('corporations.update', $item->id) }}" 
                method="POST" enctype="multipart/form-data">
                @method('PATCH')
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

                            <div class="row">
                                
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


                            <div class="row mb-5">
                                <div class="col-md-12 mb-3">
                                    <p class="text-soft">Campos requeridos*</p>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-outline-light">Actualizar</button>
                                </div>
                            </div>

                            @include('admin.corporations.people._table')

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

    var tablePath = '{{ route('corporations.people.table') }}' + '?corporation_id={{ $item->id }}';

    $(document).ready(function() {
        updateDataTable(tablePath);
    });

    function updateDataTable(path) {

        $loading.show();

        var dom_normal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

        $('#itemsTable').DataTable({
            ajax: path,
            columns: [
                { data: 'order', name: 'order' },
                { data: 'name', name: 'name' },
                { data: 'actions', name: 'actions', orderable: false, searchable : false }
            ],
            fnInitComplete: function () {
                $loading.hide();
            },
            destroy: true,
            responsive: false,
            autoWidth: false,
            dom: dom_normal,
            paginate: false,
            language: spanish
        });
    }

    function create() {
        $loading.show();
        var _token = $('[name="_token"]').val();

        $.post('{{ route('corporations.people.create') }}', {
            'corporation_id':{{ $item->id }}, 
            '_token':_token 
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $basicModal.modal('show');
        });
    }

    function store() {
        $basicModal.modal('hide');
        var form = $('#modalForm')[0];
        var param = {};
        param.action = '/corporations/people/store';
        param.method = 'post';
        submitFormModal(form, param, $('#basicModal'), onSubmitFormSuccess);
    }

    function edit(id) {
        $loading.show();
        var _token = $('[name="_token"]').val();

        $.post('{{ route('corporations.people.edit') }}', {
            'id':id, 
            '_token':_token 
        }, function(data) {
            $loading.hide();
            $('#basicModal .modal-content').html(data);
            $basicModal.modal('show');
        });
    }

    function update() {
        $basicModal.modal('hide');
        var form = $('#modalForm')[0];
        var param = {};
        param.action = '/corporations/people/update';
        param.method = 'post';
        submitFormModal(form, param, $('#basicModal'), onSubmitFormSuccess);
    }

    function destroy(id) {
        $loading.show();
        var _token = $('[name="_token"]').val();

        $.post('{{ route('corporations.people.destroy') }}', {
            '_token': _token, 
            'id':id
        }, function (response) {
            $loading.hide();
            if (response.success) {
                updateDataTable(tablePath);
                showSuccessModal(response.message);
            } else {
                showErrorModal(response.error);
            }
        });
    }

    function onSubmitFormSuccess() {
        updateDataTable(tablePath);
    }


    </script>
@endsection

