
@extends('admin.layout.app')

@section('style')
    <style>
        .map {
            display: none
        }
        
    </style>
@endsection

@section('content')

    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">

                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Mensajes Políticos</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">

                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner p-5">

                            <div class="row">

                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Título del mensaje</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control form-control-lg" name="title"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Texto</label>
                                        <div class="form-control-wrap">
                                            <textarea type="text" class="form-control form-control-lg" name="message"
                                                   required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1"><em class="icon ni ni-mobile"></em> SMS</label>
                                    </div>
                                    <br>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                        <label class="custom-control-label" for="customCheck2"><em class="icon ni ni-whatsapp"></em> WhatsApp</label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="btn btn-outline-light rounded-pill btn-lg">
                                        <em class="icon ni ni-send"></em> ENVIAR
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

    <script>

        function showMap(op) {
            $('.map').hide();
            $('.map-' + op).show();
        }

    </script>

@endsection
