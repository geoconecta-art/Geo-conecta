
@extends('admin.layout.app')

@section('style')
    <style>
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
                        <h3 class="nk-block-title page-title">Información completa de Secciones</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="region">Región</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" 
                                            name="region" required>
                                        <option value="0">Todas</option>
                                        @foreach ( $regions as $region )
                                            <option value="{{ $region->region }}">
                                                {{ $region->region }}
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="section">Sección</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" 
                                            name="section" required>
                                        <option value="0">Todas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="person_type">Tipo</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" 
                                            name="person_type" required>
                                        <option value="6">Promotores</option>
                                        <option value="7">Promovidos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 pt-3 mb-3">
                        <div class="btn btn-sm btn-primary mt-3" onclick="getSections()">
                            Obtener Información
                        </div>
                        <div class="btn btn-sm btn-primary mt-3" onclick="window.print()">
                            <span>Imprimir</span> <em class="icon ni ni-printer"></em>
                        </div>
                    </div>

                </div>

                <div class="sections">
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

    <script src="{{ asset('assets/js/libs/jqvmap.js?ver=2.4.0') }}"></script>

    <script>

        $('select[name=region]').select2();
        $('select[name=section]').select2();

        /*
        $('select[name=region]').on('change', function () {
            let region = $(this).val();
            let _token = $token.val();
            $loading.show();

            $.post('/sections/get-options', { 
                '_token':_token, 
                'region':region 
            }, function(data) {

                $('select[name=section]').html(data);
                $loading.hide();

            });
        });
        */

        function getSections() {
            let region = $('select[name=region]').val();
            let section = $('select[name=section]').val();
            let type = $('select[name=person_type]').val();
            let _token = $token.val();
            $loading.show();

            $.post('/sections/get', { 
                '_token':_token, 
                'region':region,
                'section':section,
                'type': type 
            }, function(data) {
                $('.sections').html(data);
                $loading.hide();
            });
        }

        function downloadReport(type) {
            let region = $('select[name=region]').val();
            let params = `?type=${type}&region=${region}`;

            window.location.href = '{{ route('reports.region') }}' + params;
        }

        function analyticsDoughnut(selector, set_data) {
            var $selector = selector ? $(selector) : $('.analytics-doughnut');
            $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                backgroundColor: _get_data.datasets[i].background,
                borderWidth: 2,
                borderColor: _get_data.datasets[i].borderColor,
                hoverBorderColor: _get_data.datasets[i].borderColor,
                data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'doughnut',
                data: {
                labels: _get_data.labels,
                datasets: chart_data
                },
                options: {
                legend: {
                    display: _get_data.legend ? _get_data.legend : false,
                    rtl: NioApp.State.isRTL,
                    labels: {
                    boxWidth: 12,
                    padding: 20,
                    fontColor: '#6783b8'
                    }
                },
                rotation: -1.5,
                cutoutPercentage: 70,
                maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    rtl: NioApp.State.isRTL,
                    callbacks: {
                    title: function title(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function label(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                    }
                    },
                    backgroundColor: '#1c2b46',
                    titleFontSize: 13,
                    titleFontColor: '#fff',
                    titleMarginBottom: 6,
                    bodyFontColor: '#fff',
                    bodyFontSize: 12,
                    bodySpacing: 4,
                    yPadding: 10,
                    xPadding: 10,
                    footerMarginTop: 0,
                    displayColors: false
                }
                }
            });
            });
        } 
       

    </script>

@endsection
