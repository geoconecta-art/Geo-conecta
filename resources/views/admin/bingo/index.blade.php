
@extends('admin.layout.app')

@section('style')
    <style>
        .nav-tabs .nav-link.active {
            color: #ee781d;
        }

        .nav-tabs .nav-link:after {
            background: #ee781d;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">{{ $title }}</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch mb-3">
                    <div class="card-inner-group">
                        <div class="card-inner py-3">

                            <div class="row">
                                <div class="col-6">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tabItem1">
                                                Promovidos
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tabItem2">
                                                Votos por Sección
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6 text-right pt-2">
                                    <h3>Votos: <span class="total_votes">{{ $votes }}</span></h3>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>

                <div class="tab-content">
                    
                    <div class="tab-pane active" id="tabItem1">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner py-5">

                                    <div class="row px-4">

                                        <div class="col-lg-8">
                                            
                                            <div class="row">

                                                <div class="col-lg-4 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Promotor</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="person_id" required data-search="on">
                                                                <option value="0">TODOS</option>
                                                                @foreach ($people as $p)
                                                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Región</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="regions[]" multiple required >
                                                                @foreach ( $regions as $region )
                                                                    <option value="{{ $region->region }}">{{ $region->region }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Sección</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="sections[]" multiple required >
                                                                @foreach ( $sections as $section )
                                                                    <option value="{{ $section->section }}"
                                                                        @if ( isset($p_sections) && in_array($section->section, $p_sections)) selected @endif >{{ $section->section }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Apellido Paterno</label>
                                                        <div class="form-control-wrap">
                                                            <input class="form-control" type="text" name="last_name_1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Apellido Materno</label>
                                                        <div class="form-control-wrap">
                                                            <input class="form-control" type="text" name="last_name_2">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Nombre(s)</label>
                                                        <div class="form-control-wrap">
                                                            <input class="form-control" type="text" name="first_name">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">ID</label>
                                                        <div class="form-control-wrap">
                                                            <input class="form-control" type="text" name="id">
                                                        </div>
                                                        <small>Introduce el código despues de la diagonal Ej: 250/<b>105</b></small>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-lg-8" style="padding-top:35px;">
                                                    <div class="btn btn-sm btn-primary" onclick="getPeople()">
                                                        Buscar
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner py-5">
                                <div class="table-responsive pt-5">
                                        <table class="nowrap table" id="itemsTable">
                                            <thead>
                                            <tr>
                                                <th>Votación</th>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Celular/WhatsApp</th>
                                                <th>Dirección</th>
                                                <th>Región</th>
                                                <th>Zona</th>
                                                <th>Sección</th>
                                                <th>Tipo de Voto</th>
                                                <th>Promotor</th>
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

                    <div class="tab-pane" id="tabItem2">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner py-5">

                                    <div class="text-right">
                                        <div onclick="create()"
                                            class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                            <span>Registrar Votos</span>
                                            <em class="icon ni ni-plus"></em>
                                        </div>
                                    </div>

                                    <div class="table-responsive pt-5">
                                        <table class="nowrap table" id="votesTable">
                                            <thead>
                                            <tr>
                                                <th width="20%">Fecha de Registro</th>
                                                <th>Sección</th>
                                                <th>Región</th>
                                                <th>Votos</th>
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

            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="/plugins/datatables/datetime.js"></script>
    <script src="/plugins/datatables/date-euro.js"></script>

    <script>

        var tablePath = '{{ route('bingo.table') }}' + '?type=7';
        
        $.fn.datepicker.dates['es'] = {
            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            daysShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            daysMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            months:  ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            today: 'Hoy',
            clear: 'Limpiar',
            format: "mm/dd/yyyy",
            weekStart: 0
        };

        $(document).ready(function() {

            updateVotesDataTable();

            $('#dates').datepicker({
                todayHighlight: false,
                autoclose: false,
                format: {
                    toDisplay: function (date, format, language) {
                        var d = new Date(date);
                        d.setDate(d.getDate() + 1);
                        return ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth()+1)).slice(-2) + '/' +
                            d.getFullYear();
                    },
                    toValue: function (date, format, language) {
                        var d = new Date(date);
                        return d.toISOString().slice(0, 10);
                    }
                },
                language: 'es'
            });

        });

        function updateVotesDataTable() {

            var domNormal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

            $('#votesTable').DataTable({
                dom: domNormal,
                ajax: '{{ route('votes.table') }}',
                columns: [
                    { data: 'f_created_at', name: 'f_created_at', targets: 0, type: 'date-euro' },
                    { data: 'section', name: 'section' },
                    { data: 'region', name: 'region' },
                    { data: 'votes', name: 'votes' },
                    { data: 'actions', name: 'actions' },
                ],
                searching: true,
                destroy: true,
                responsive: false,
                autoWidth: false,
                language: spanish,
                order: [[ 0, 'desc' ]],
                fnInitComplete: function () {
                    $loading.hide();
                },
            });
        }


        function updateDataTable(path) {

            var domNormal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

            $('#itemsTable').DataTable({

                dom: domNormal,
                processing: true,
                serverSide: true,
                ajax: {
                    url: path,
                    beforeSend: function() {
                        $loading.show(); 
                    },
                    complete: function() {
                        $loading.hide(); 
                        setCheckboxEvent();
                    }
                },
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable : false, className: 'text-center' },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'address', name: 'address' },
                    { data: 'region', name: 'region' },
                    { data: 'zone', name: 'zone' },
                    { data: 'section', name: 'section' },
                    { data: 'vote', name: 'vote' },
                    { data: 'mobilizer', name: 'mobilizer' },
                ],
                fnRowCallback: rowCallBack,
                pagingType: 'full_numbers', 
                pageLength: 50, 
                searching: false,
                destroy: true,
                responsive: false,
                autoWidth: false,
                language: spanish
            });
        }

        function rowCallBack( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            let pChecked = ( aData['voting_date'] == null ) ? '' : 'checked';

            $('td:eq(0)', nRow).html(
                `<div class="custom-control custom-control-lg custom-checkbox">` + 
                `<input type="checkbox" class="custom-control-input" id="customCheck${ aData['id'] }" ${ pChecked } >` +
                `<label class="custom-control-label" for="customCheck${ aData['id'] }"></label>` + 
                `</div>`);

            $('td:eq(1)', nRow).html(`${ aData['section'] }/${ aData['id'] }`);
            
        }

        function getPeople() {
            var params = '';
        
            var regions = $('select[name*=regions]').val();
            var regionsStr = regions.join(',');

            var sections = $('select[name*=sections]').val();
            var sectionsStr = sections.join(',');

            var personId = $('select[name=person_id]').val();

            var lastName1 = $('input[name=last_name_1]').val();
            var lastName2 = $('input[name=last_name_2]').val();
            var firstName = $('input[name=first_name]').val();
            var id = $('input[name=id]').val();

            params += '&regions=' + regionsStr + 
                '&sections=' + sectionsStr + 
                '&person_id=' + personId + 
                '&id=' + id + 
                '&last_name_1=' + lastName1.trim() +
                '&last_name_2=' + lastName2.trim() +
                '&first_name=' + firstName.trim();

            updateDataTable(tablePath + params);
        }

        function setCheckboxEvent() {
            $('.custom-control-input').on('change', function() {
                
                let id = $(this).attr('id');
                id = id.replace('customCheck', '');
                let vote = ( $(this).is(':checked') ) ? 1 : 0;
                setVotingDate(id, vote);

            });
        }

        function setVotingDate(id, vote) {
            let _token = $token.val();
            $loading.show();

            $.post('/bingo/set-voting-date', {
                '_token': _token,
                'id': id,
                'vote': vote
            }, function(response) {
            
                $('.total_votes').html(response.votes);
                $loading.hide();

            });
        }

        function create() {

            $loading.show();
            let _token = $token.val();
            $.post('/votes/create', { 
                '_token':_token, 
            }, function(data) {
                $loading.hide();
                $('#basicModal .modal-content').html(data);
                $('#basicModal .modal-dialog').removeClass('modal-lg');

                $basicModal.modal('show');
            });
        }

        function store() {
            let _token = $token.val();
            let section = $('select[name=section]').val();
            let votes = $('input[name=votes]').val();

            $loading.show();

            $.post('/votes/store', {
                '_token': _token,
                'section': section,
                'votes': votes
            }, function(response) {
            
                $basicModal.modal('hide');
                $('.total_votes').html(response.votes);
                $loading.hide();
                updateVotesDataTable();
                showSuccessModal(response.message);
            });
        }

        function edit(id) {

            $loading.show();
            let _token = $token.val();
            $.post('/votes/edit', { 
                '_token':_token, 
                'id':id,
            }, function(data) {
                $loading.hide();
                $('#basicModal .modal-content').html(data);
                $('#basicModal .modal-dialog').removeClass('modal-lg');

                $basicModal.modal('show');
            });
        }

        function update(id) {
            let _token = $token.val();
            let section = $('select[name=section]').val();
            let votes = $('input[name=votes]').val();

            $loading.show();

            $.post('/votes/update', {
                '_token': _token,
                'id':id,
                'section': section,
                'votes': votes
            }, function(response) {

                $basicModal.modal('hide');
                $('.total_votes').html(response.votes);
                $loading.hide();
                updateVotesDataTable();
                showSuccessModal(response.message);
            });
        }

        function destroy(id) {

            $loading.show();
            var _token = $('[name="_token"]').val();

            $.post('/votes/destroy', {
                '_token': _token, 
                'id': id,
            }, function (response) {
                
                $loading.hide();
                if ( response.success ) {
                    $('.total_votes').html(response.votes);
                    updateVotesDataTable();
                    showSuccessModal(response.message);
                } else {
                    showWarningModal(response.error);
                }
            });

        }


    </script>

@endsection
