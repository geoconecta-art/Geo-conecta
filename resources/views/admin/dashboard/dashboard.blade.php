
@extends('admin.layout.app')

@section('style')
    <style>
      
        
    </style>
@endsection

@section('content')

    <div class="nk-content-inner dashboard">
        
        <div class="nk-content-body">
            
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">
                            Estadísticas del día 
                            <span class="date">{{ date('d/m/Y') }}</span>
                        </h3>
                    </div>
                    <div class="d-flex">
                        <div class="form-group mr-2">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" 
                                value="{{ date('Y-m-d') }}"
                                id="date" 
                                >
                        </div>
                        <div class="btn btn-sm btn-primary mr-3" onclick="getInfo()" style="height:30px;margin-top:32px;">
                            <span>Filtrar</span> <em class="icon ni ni-filter"></em>
                        </div>
                        <div class="btn btn-sm btn-primary" onclick="window.print()" style="height:30px;margin-top:32px;">
                            <span>Imprimir</span> <em class="icon ni ni-printer"></em>
                        </div>
                    </div>
                    
                </div>
               
            </div>

            <div class="nk-block">
                <div class="info">
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
<script>

    $(document).ready(function() {
        getInfo();
    });

    function convertDateFormat(dateInYYYYMMDD) {
        var parts = dateInYYYYMMDD.split("-");
        var date = new Date(parts[0], parts[1] - 1, parts[2]);

        var month = date.getMonth() + 1; 
        var day = date.getDate();
        var year = date.getFullYear();

        var formattedDate = (day < 10 ? "0" : "") + day + "/" + (month < 10 ? "0" : "") + month + "/" + year;

        return formattedDate;
    }

    function downloadReport(type) {

        var date = $('#date').val();

        let params = `?type=${type}&date=${date}`;

        window.location.href = '{{ route('reports.people') }}' + params;
    }

    function getInfo() {
        var date = $('#date').val();
        let _token = $token.val();
        $loading.show();

        var formattedDate = convertDateFormat(date);
        $('.date').html(formattedDate);

        $.post('/dashboard/get-info', { 
            '_token':_token, 
            'date':date,
        }, function(data) {
            $('.info').html(data);
            $loading.hide();
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

</script>
@endsection