
@extends('admin.layout.app')

@section('style')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        table.dataTable.nowrap th, table.dataTable.nowrap td {
            white-space: normal !important;
        }

        .col-xxl-3>.card {
            cursor: pointer;
        }

        #mobTable{
            font-size: 11px;
        }

        .mob-info {
            font-size: 1rem;
        }

    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Reportes</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="type">Selecciona el reporte que deseas generar</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" 
                                            name="type" required>
                                        <option disabled selected value="">Selecciona una opción</option>
                                        <option value="1">Reporte por Región</option>
                                        <option value="2">Reporte de Asistencia</option>
                                        <option value="3">Reporte Promotores por Sección</option>
                                        <option value="4">Reporte Promovidos por Sección</option>
                                        <option value="5">Reporte Promovidos por Promotor</option>
                                        <option value="6">Reporte por Corporación</option>
                                        <option value="7">Reporte Promovidos por Corporación</option>
                                        <option value="8">Reporte Promovidos por Promotor de Corporación</option>
                                        <option value="9">Reporte Coordinadores y Promotores por Sección</option>
                                        <option value="10">Reporte de Capturistas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 pt-3">
                        <div class="btn btn-sm btn-primary mt-3" onclick="showCreateModal()">
                            Generar
                        </div>
                        <div class="btn btn-sm btn-primary mt-3" onclick="window.print()">
                            <span>Imprimir</span> <em class="icon ni ni-printer"></em>
                        </div>
                        
                    </div>

                </div>
            </div>
            
            <div class="report">

            </div>

        </div>
    </div>

@endsection

@section('script')

    <script>

        $('select[name=type]').select2();

        $(document).ready(function() {
        });

        function showCreateModal() {

            var type = $('select[name=type]').val();

            if ( type == 10 ) {
                generateReport(type);
            } else {
                $loading.show();
                let _token = $token.val();
                $.post('/reports/create', { 
                    '_token':_token, 
                    'type':type 
                }, function(data) {
                    $loading.hide();
                    $('#basicModal .modal-content').html(data);
                    $('#basicModal .modal-dialog').removeClass('modal-lg');

                    $basicModal.modal('show');
                });
            }

            
        } 

        function generateReport(type) {
            $basicModal.modal('hide');
            $loading.show();
            let _token = $token.val();
            let region = $('select[name=region]').val();
            let day = $('select[name=day]').val();
            let weeks = $('select[name=weeks]').val();
            let section = $('select[name=section]').val();
            let corporation = $('select[name=corporation]').val();
           
            $.post('/reports/generate', { 
                '_token':_token, 
                'type':type, 
                'region':region,
                'day':day,
                'weeks':weeks,
                'section':section,
                'corporation' : corporation
                }, function(data) {
                    $loading.hide();
                    $('.report').html(data);
            });
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

        function ecommerceLineS4(selector, set_data) {
            var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s4');
            $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                label: _get_data.datasets[i].label,
                tension: _get_data.lineTension,
                backgroundColor: _get_data.datasets[i].background,
                borderWidth: 2,
                borderDash: _get_data.datasets[i].dash,
                borderColor: _get_data.datasets[i].color,
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: _get_data.datasets[i].color,
                pointBorderWidth: 2,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 2,
                pointRadius: 4,
                pointHitRadius: 4,
                data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'line',
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
                maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    rtl: NioApp.State.isRTL,
                    callbacks: {
                    title: function title(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function label(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
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
                },
                scales: {
                    yAxes: [{
                    display: true,
                    stacked: _get_data.stacked ? _get_data.stacked : false,
                    position: NioApp.State.isRTL ? "right" : "left",
                    ticks: {
                        beginAtZero: true,
                        fontSize: 11,
                        fontColor: '#9eaecf',
                        padding: 10,
                        callback: function callback(value, index, values) {
                        return value;
                        },
                        min: 0,
                        stepSize: 3000
                    },
                    gridLines: {
                        color: NioApp.hexRGB("#526484", .2),
                        tickMarkLength: 0,
                        zeroLineColor: NioApp.hexRGB("#526484", .2)
                    }
                    }],
                    xAxes: [{
                    display: false,
                    stacked: _get_data.stacked ? _get_data.stacked : false,
                    ticks: {
                        fontSize: 9,
                        fontColor: '#9eaecf',
                        source: 'auto',
                        padding: 10,
                        reverse: NioApp.State.isRTL
                    },
                    gridLines: {
                        color: "transparent",
                        tickMarkLength: 0,
                        zeroLineColor: 'transparent'
                    }
                    }]
                }
                }
            });
            });
        } 
       

    </script>

@endsection
