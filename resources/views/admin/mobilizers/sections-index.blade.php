
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
                        <h3 class="nk-block-title page-title">Información completa de Secciones</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="row g-gs">
                    <div class="col-lg-4 col-sm-6 mb-3">
                        <div class="card">
                            <div class="nk-ecwg nk-ecwg6">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Promotores</h6>
                                        </div>
                                    </div>
                                    <div class="data">
                                        <div class="data-group">
                                            <div class="amount">{{ $mob }} / {{ $mob_limit }}</div>
                                            <div class="nk-ecwg6-ck"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                                <canvas class="ecommerce-line-chart-s3 chartjs-render-monitor" id="todayOrders" style="display: block; width: 100px; height: 40px;" width="100" height="40"></canvas>
                                            </div>
                                        </div>
                                        <div class="info">Siendo <span class="text-danger"><b>{{ $limit }}</b></span><span> el límite de promotores</span></div>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .nk-ecwg -->
                        </div><!-- .card -->
                    </div>
                </div>

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner py-5">

                            <div class="table-responsive">
                                <table class="nowrap table" id="sectionsTable">
                                    <thead>
                                    <tr>
                                        <th>Sección</th>
                                        <th>DF</th>
                                        <th>DL</th>
                                        <th>Región</th>
                                        <th>Zona</th>
                                        <th>Dirección</th>
                                        <th>LN</th>
                                        <th>Meta Sección</th>
                                        <th>Promotores</th>
                                        <th>Coord. Seccionales</th>
                                        <th>Coord. de Zona</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $sections as $section )
                                            <tr>
                                                <td>{{ $section->section }}</td>
                                                <td>{{ $section->df }}</td>
                                                <td>{{ $section->dl }}</td>
                                                <td>{{ $section->region }}</td>
                                                <td>{{ $section->zone }}</td>
                                                <td>{{ $section->dependence }}</td>
                                                <td>{{ $section->ln }}</td>
                                                <td>{{ $section->goal }}</td>
                                                <td>
                                                    @if ( $section->mob > $section->mob_limit )
                                                        <span class="text-danger"><b>{{ $section->mob }}</b></span>/<b>{{ $section->mob_limit }}</b>
                                                    @else 
                                                        <span class="text-success"><b>{{ $section->mob }}</b></span>/<b>{{ $section->mob_limit }}</b>
                                                    @endif  
                                                </td>
                                                <td>{{ $section->section_coords }}</td>
                                                <td>{{ $section->zone_coords }}</td>
                                            </tr>
                                        @endforeach
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

    <script>

        $(document).ready(function() {

            $('#sectionsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    filename: 'Secciones',
                    className: 'btn btn-sm btn-primary mb-3',
                    text: '<em class="icon ni ni-download"></em> Exportar Excel',
                }],
                language: spanish,
                paginate: false
            });
        });

    </script>

@endsection
