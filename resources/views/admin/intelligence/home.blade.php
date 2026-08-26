
@extends('admin.layout.app')

@section('style')
    <style>
        body {
            background: url(/img/degradado.png) no-repeat center center / cover !important;
        }
    </style>
@endsection

@section('content')

<div class="nk-block nk-block-middle nk-auth-body  wide-xs">
    <div class="brand-logo pb-4 text-center">
        <img src="/img/electo.png" alt="logo">
    </div>
   
</div>
<div class="row">
    <div class="col-12 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
        <div class="form-group">
            <div class="form-control-wrap">
                <div class="form-icon form-icon-left">
                    <em class="icon ni ni-search"></em>
                </div>
                <input type="text" class="form-control form-control-xl rounded-pill white-shadow" id="searchInput" placeholder="Buscar">
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    var searchInput = document.getElementById('searchInput');
  
    searchInput.addEventListener( 'keypress', function(event) {
      if ( event.keyCode === 13 ) {
        search(this.value);
      }
    });

    function search(searchWords) {

        $loading.show();
        var _token = $('[name="_token"]').val();
        
        $.post('/search', { 
            '_token': _token, 
            'search_words':searchWords }, function (response) {
            if (response.success) {
                window.location.href = '/admin/inteligencia/perfil/' + response.id;
            } else {
                $loading.hide();
                showWarningModal(response.error);
            }
        });

    }

  </script>

@endsection
