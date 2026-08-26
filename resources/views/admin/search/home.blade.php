
@extends('admin.layout.app')

@section('style')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        .autocomplete-suggestion {
            cursor: pointer;
        }

        .autocomplete-suggestions {
            background-color: #fff;
            border: 1px solid #dbdfea;
            border-radius: 1.5rem;
            padding: 2rem;
            overflow-y: scroll;
        }

        .autocomplete-group {
            padding-top: 10px;
        }

    </style>
@endsection

@section('content')

<div class="nk-block nk-block-middle nk-auth-body  wide-xs">
    <div class="brand-logo pb-4 text-center">
        <img src="/img/nuevo-logo-bg.png">
    </div>
   
</div>
<div class="row">
    <div class="col-12 col-md-10 offset-md-1 col-lg-6 offset-lg-3">
        <div class="form-group">
            <div class="form-control-wrap">
                <div class="form-icon form-icon-left">
                    <em class="icon ni ni-search"></em>
                </div>
                <input type="text" 
                    class="form-control form-control-xl rounded-pill white-shadow" 
                    id="autocomplete" 
                    placeholder="Búsqueda por nombre o clave de elector" 
                    autocomplete="off">

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script src="/plugins/autocomplete/jquery.autocomplete.js"></script>

<script>

    var people = [];

    $(document).ready(function() {
        setAutocompleteEvent();
    });

    function setAutocompleteEvent() {
        var path = '/search'; 

        $loading.show();

        $.get(path, function(data){
            people = data;

            tam = data.length;
            for (var i = 0; i < tam; i++) {
                switch (data[i].data) {

                    case 1:
                        data[i].data = { category: 'Director' };
                        break;
                    case 3:
                        data[i].data = { category: 'Coordinador Regional' };
                        break;
                    case 4:
                        data[i].data = { category: 'Coordinador de Zona' };
                        break;
                    case 5:
                        data[i].data = { category: 'Coordinador Seccional' };
                        break;
                    case 6:
                        data[i].data = { category: 'Promotor' };
                        break;
                    case 8:
                        data[i].data = { category: 'Pedro en tu Casa' };
                        break;
                    case 9:
                        data[i].data = { category: 'Evaluación y Seguimiento' };
                        break;

                    case 10:
                        data[i].data = { category: 'Incorporación Voluntaria' };
                        break;
                   
                }
            }
            $loading.hide();

            $('#autocomplete').autocomplete({
                lookup: data,
                groupBy: 'category',
                minChars: 5,
                onSelect: function(suggestion) {

                    $loading.show();
                    let name = $('#autocomplete').val();
                    let _token = $token.val();

                    $.post('/search/get-id', { 
                        '_token':_token, 
                        'name':name 
                    }, function(data) {
                       
                        $loading.hide();
                        $('#autocomplete').val('');
                        window.location.href = '/admin/busqueda/' + data.id;

                    });
                }
                
            });

        });

    }

  </script>

@endsection
