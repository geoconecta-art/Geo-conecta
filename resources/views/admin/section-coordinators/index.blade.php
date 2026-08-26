
@extends('admin.layout.app')

@section('style')
    <style>
        
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

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner py-5">

                            <div class="row">
                                
                                <div class="col-lg-4">
                                    @include('admin.people._sections-select')
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Coordinador de Zona</label>
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

                                <div class="col-lg-4">
                                    
                                </div>

                                <div class="col-lg-3">
                                    <div class="btn btn-sm btn-primary mt-3" onclick="getPeople()">
                                        Filtrar
                                    </div>
                                </div>


                            </div>

                            <div class="row mb-5">
                                <div class="col-12 text-right">
                                    @if ( !Auth::user()->hasRole('Consultor') )
                                    <a href="{{ route('s-coordinators.create') }}" class="btn btn-outline-light">
                                        <span>Agregar {{ $title }}</span><em class="icon ni ni-user-add-fill"></em>
                                    </a>
                                    @endif
                                    
                                    <div onclick="downloadReport(2)"
                                        class="btn btn-outline-light">
                                        <span>Reporte por Sección</span><em class="icon ni ni-printer"></em>
                                    </div>
                                    <div onclick="downloadReport(1)"
                                        class="btn btn-outline-light">
                                        <span>Reporte General de {{ $title }}</span><em class="icon ni ni-printer"></em>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="nowrap table" id="itemsTable">
                                    <thead>
                                    <tr>
                                        <th>Fecha de Captura</th>
                                        <th width="3%">#</th>
                                        <th width="15%">Imagen</th>
                                        <th>Nombre</th>
                                        <th>Domicilio</th>
                                        <th>Teléfono</th>
                                        <th>Tipo</th>
                                        <th>Región</th>
                                        <th>Zona</th>
                                        <th>Sección</th>
                                        <th>Coordinador de Zona</th>
                                        <th>Coordinador Regional</th>
                                        <th>Director Regional</th>
                                        <th>Dirección</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div><!-- .card-inner -->
                    </div><!-- .card-inner-group -->
                </div><!-- .card -->
            </div><!-- .nk-block -->
        </div>
    </div>

@endsection

@section('script')

    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->

    <script src="/plugins/datatables/datetime.js"></script>
    <script src="/plugins/datatables/date-euro.js"></script>

    <script>

        localStorage.setItem('storeSec', 0);

        var tablePath = '{{ route('s-coordinators.table') }}' + '?type=5';
        var generalReportPath = '{{ route('reports.s-coordinators') }}' + '?tipo=5';
        var sectionReportPath = '{{ route('reports.section') }}' + '?tipo=5';

        $(document).ready(function() {
            updateDataTable(tablePath);
        });

        function downloadReport(type) {
            var params = '';
            
            var sections = $('select[name*=sections]').val();
            var sectionsStr = sections.join(',');
            var coordId = $('select[name=person_id]').val();

            params += '&secciones=' + sectionsStr + '&coord_zona=' + coordId;

            if ( type == 1 ) {
                window.open(generalReportPath + params, '_blank');
            } else {

                if ( sections.length == 0 ) {
                    showWarningModal('Selecciona una sección para generar el reporte.');
                } else if ( sections.length > 1 ) {
                    showWarningModal('Selecciona sólo una sección para generar el reporte.');
                } else {
                    window.open(sectionReportPath + params, '_blank');
                }
            }
                
        }

        function onSubmitFormSuccess() {
            getPeople();
        }

        function getPeople() {
            var params = '';
            
            var sections = $('select[name*=sections]').val();
            var sectionsStr = sections.join(',');
            var personId = $('select[name=person_id]').val();

            params += '&sections=' + sectionsStr + '&person_id=' + personId;

            updateDataTable(tablePath + params);
        }

        function updateDataTable(path) {

            $loading.show();

            var dom_normal = '<"row justify-between m-0 gy-2 pb-3"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center px-3 m-0"<"col-12 col-md-7 col-lg-9"p><"col-12 col-md-5 col-lg-3"i>>';

            $('#itemsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    filename: 'Coordinador Seccional',
                    className: 'btn btn-sm btn-primary mb-3',
                    text: '<em class="icon ni ni-download"></em> Exportar Excel',
                }],
                ajax: path,
                columns: [
                    //{ data: 'id', name: 'id' },
                    { data: 'f_created_at', name: 'f_created_at', targets: 0, type: 'date-euro' },
                    { data: 'order', name: 'order' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'address', name: 'address' },
                    { data: 'phone', name: 'phone' },
                    { data: 'coord_type', name: 'coord_type' },
                    { data: 'region', name: 'region' },
                    { data: 'zone', name: 'zone' },
                    { data: 'section', name: 'section' },
                    { data: 'z_coordinator', name: 'z_coordinator' },
                    { data: 'r_coordinator', name: 'r_coordinator' },
                    { data: 'director', name: 'director' },
                    { data: 'dependence', name: 'dependence' },
                    { data: 'actions', name: 'actions', orderable: false, searchable : false }
                ],
                order: [[ 0, 'desc' ]],
                fnInitComplete: function () {
                    $loading.hide();
                },
                destroy: true,
                responsive: false,
                autoWidth: false,
                //dom: dom_normal,
                paginate: false,
                language: spanish
            });
        }

        function deleteItem(id) {

            /*
            $loading.show();
            var _token = $('[name="_token"]').val();
            var _method = 'DELETE';

            $.post('/directors/' + id, {'_token': _token, '_method':_method}, function (response) {
                $loading.hide();
                if (response.success) {
                    showSuccessModal(response.message);
                    updateDataTable();
                } else {
                    showErrorModal(response.error);
                }
            });
            */

        }


    </script>

@endsection
