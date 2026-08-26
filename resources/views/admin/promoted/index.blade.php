
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

                            <div class="row px-4">

                                <div class="col-lg-8">
                                    
                                    <div class="row">

                                        <div class="col-lg-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Fecha de Captura</label>
                                                <div class="form-control-wrap">
                                                    <div class="input-daterange input-group" id="dates">
                                                        <input type="text" class="form-control" />
                                                        <div class="input-group-addon">A</div>
                                                        <input type="text" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
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

                                        <div class="col-lg-6 mb-3">
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

                                        <div class="col-lg-6 mb-3">
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

                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Búsqueda por palabras</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="text" name="words">
                                                </div>
                                            </div>
                                        </div>

                                        


                                        <div class="col-lg-12">
                                            <div class="btn btn-sm btn-primary mt-3" onclick="getPeople()">
                                                Filtrar
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                
                            
                                
                            </div>

                            <div class="row mb-5 px-4 mt-3 mt-lg-0">

                                <div class="col-12 text-right">

                                    @if ( !Auth::user()->hasRole('Consultor') )
                                    <a href="{{ route('promoted.create') }}" 
                                        class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                        <span>Agregar {{ $title }}</span><em class="icon ni ni-user-add-fill"></em>
                                    </a>
                                    @endif

                                    <div onclick="getPeople()"
                                        class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                        <span>Promovidos Agregados</span><em class="icon ni ni-user-list-fill"></em> 
                                    </div>

                                    <!--
                                    <div onclick="downloadReport(2)"
                                        class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                        <span>Reporte por Sección</span><em class="icon ni ni-printer"></em>
                                    </div>
                                    <div onclick="downloadReport(1)"
                                        class="btn btn-outline-light mb-3 mb-md-0">
                                        <span>Reporte General de { $title }}</span><em class="icon ni ni-printer"></em>
                                    </div>
                                    -->
                                    
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="nowrap table" id="itemsTable">
                                    <thead>
                                    <tr>
                                        <th>Fecha de Captura</th>
                                        <!--<th width="3%">#</th>-->
                                        <th>Nombre</th>
                                        <th>Celular/WhatsApp</th>
                                        <th>Dirección</th>
                                        <th>Región</th>
                                        <th>Zona</th>
                                        <th>Sección</th>
                                        <th>Manzana</th>
                                        <th>MR</th>
                                        <th>Tipo de Voto</th>
                                        <th>Promotor</th>
                                        <!--
                                        <th>Coordinador Seccional</th>
                                        <th>Coordinador de Zona</th>
                                        <th>Coordinador Regional</th>
                                        <th>Director Regional</th>
                                        -->
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

        localStorage.setItem('storeProm', 0);

        var tablePath = '{{ route('promoted.table') }}' + '?type=7';
        
        var generalReportPath = '{{ route('reports.promoted') }}' + '?tipo=7';
        var sectionReportPath = '{{ route('reports.promoted.section') }}' + '?tipo=7';

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
            initDataTable();

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

        function initDataTable() {

            $('#itemsTable').DataTable({
                paginate: false,
                destroy: true,
                responsive: false,
                autoWidth: false,
                searching: false,
                language: spanish
            });
        }

        function updateDataTable(path) {

            $('#itemsTable').DataTable({

                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    filename: 'Promovido',
                    className: 'btn btn-sm btn-primary mb-3',
                    text: '<em class="icon ni ni-download"></em> Exportar Excel',
                }],

                processing: true,
                serverSide: true,
                ajax: {
                    url: path,
                    beforeSend: function() {
                        $loading.show(); 
                    },
                    complete: function() {
                        $loading.hide(); 
                    }
                },
                columns: [
                    { data: 'created_at', name: 'created_at' },
                   // { data: 'order', name: 'order' },
                    { data: 'name', name: 'name' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'address', name: 'address' },
                    { data: 'region', name: 'region' },
                    { data: 'zone', name: 'zone' },
                    { data: 'section', name: 'section' },
                    { data: 'block', name: 'block' },
                    { data: 'mr', name: 'mr' },
                    { data: 'vote', name: 'vote' },
                    { data: 'mobilizer', name: 'mobilizer' },
                    /*
                    { data: 's_coordinator', name: 's_coordinator' },
                    { data: 'z_coordinator', name: 'z_coordinator' },
                    { data: 'r_coordinator', name: 'r_coordinator' },
                    { data: 'director', name: 'director' },
                    */
                    { data: 'id', name: 'id', orderable: false, searchable : false }
                ],
                fnRowCallback: rowCallBack,
                pagingType: 'full_numbers', 
                pageLength: 50, 
                searching: false,
               
                //order: [[ 0, 'asc' ]],
                destroy: true,
                responsive: false,
                autoWidth: false,
                language: spanish
            });
        }

        function rowCallBack( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            
            @if ( Auth::user()->hasRole('Consultor') )
            $('td:eq(11)', nRow).html('');
            @else
            $('td:eq(11)', nRow).html(
                '<a href="/admin/promovido/' + aData['id'] + '" class="btn btn-round btn-icon btn-outline-light"><em class="icon ni ni-user"></em></a>'
            );
            @endif
        }

        function downloadReport(type) {
            var params = '';
            
            var sections = $('select[name*=sections]').val();
            var sectionsStr = sections.join(',');
            var mobId = $('select[name=person_id]').val();

            params += '&secciones=' + sectionsStr + '&mov=' + mobId;

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

        function getPeople() {
            var params = '';
        
            var regions = $('select[name*=regions]').val();
            var regionsStr = regions.join(',');

            var sections = $('select[name*=sections]').val();
            var sectionsStr = sections.join(',');

            var personId = $('select[name=person_id]').val();

            var words = $('input[name=words]').val();

            params += '&regions=' + regionsStr + 
                '&sections=' + sectionsStr + 
                '&person_id=' + personId + 
                '&words=' + words.trim();

            var startDate = $($('#dates input')[0]).val();
            var endDate = $($('#dates input')[1]).val();

            if ( startDate != '' && endDate != '' ) {
                startDate = DMYtoYMD(startDate);
                endDate = DMYtoYMD(endDate);
                params += '&dates=' + startDate + '_' + endDate;
            }

            updateDataTable(tablePath + params);
        }

        function destroy(id) {
            
            $loading.show();
            var _token = $('[name="_token"]').val();
            var _method = 'DELETE';

            $.post('/promoted/' + id, {
                '_token': _token, 
                '_method':_method
            }, function (response) {
                $loading.hide();
                if ( response.success ) {
                    showSuccessModal(response.message);
                    setTimeout(getPeople, 2000);
                } else {
                    showWarningModal(response.error);
                }
            });

        }

        function DMYtoYMD(dateStr) {

            var res = dateStr.split("/");

            var d = res[0];
            var m = res[1];
            var y = res[2];

            return y + '-' + m + '-' + d;
        }


    </script>

@endsection
