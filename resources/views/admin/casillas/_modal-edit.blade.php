<form method="POST" action="{{route('casillas.store', $booth->id)}}" enctype="multipart/form-data" id="formBooth">
    
    
    <div class="modal-header">
        <h5 class="modal-title">{{ $title }}</h5>

        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <em class="icon ni ni-cross"></em>
        </a>
    </div>
    @csrf

    <div class="modal-body">
        <div class="nk-block">

            <div class="card card-preview">
                <div class="card-inner py-0">

                    @if ($type == 'electoral')  @include('admin.casillas._info-count')
                    @else @include('admin.casillas._info-quick-count') @endif
                    <input type="hidden" name="type" value="{{$type}}">
    
                    <div class="row px-0 py-1">
                        <!--ESPACIO PARA COLOCAR LOS PARTIDOS-->
                        @foreach ($fields as $field)
                        <div class="col-6 px-0">
    
                            <div class="row px-0 mx-0 justify-content-between align-items-baseline">
                                <label class="form-label mx-3" for="name" id="lbl-fields">
                                    @if ($type == 1) {{strtoupper(str_replace("_", " ", $field))}}
                                    @else {{strtoupper(str_replace('2', ' ', str_replace("_", " ", $field)))}} @endif
                                </label>
                                <div class="form-control-wrap w-50">
                                    <input type="text" class="form-control" name="{{$field}}"
                                        value="{{$booth[$field]}}"
                                    >
                                    <input type="hidden" name="fields[]" value="{{$field}}" >
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <div class="btn btn-outline-danger" data-dismiss="modal" aria-label="Close">
            Cancelar
        </div>
        <button class="btn btn-outline-light" type="submit" id="btnSave">
            Guardar
        </button>
    </div>

</form>

<script>
$("#formBooth").on('submit', function (e) {
    e.preventDefault();

    $basicModal.modal('hide');
    $loading.show();

    let _token = $token.val();
    var url = $(this).attr('action');

    var formData = new FormData(this);

    $.ajax({
        url: url,
        data: formData,
        type: $(this).attr("method"),
        contentType: false,
        processData: false,
        success: function (data) {
            $loading.hide();
            $('#mobTable > tbody').empty();
            if(data[2] == 'electoral')
                fillElectoralTable(data);
            else
                fillQuickTable(data);
        },
        error: function (er) {
            console.log(er);
        }
    });
});

function fillElectoralTable(data){
    console.log(data);
    $.each(data[0], function (i, item) {
        
        var rows = "<tr>" +
            "<td>" + (i+1) + "</td>" +

            "<td>" +
                "<a class='btn btn-round btn-icon btn-light' onclick=\"showModal(" + item.id + ", \'electoral\')\";>" +
                "<em class='icon ni ni-edit'></em> </a>" +
            "</td>" +

            "<td>" + item.section + "</td>" +
            "<td>" + item.type + "</td>" +
            "<td>" + item.letters + "</td>" +

            checkImage(item.image) +
            getPoliticalFields(data[1], item) +
                    
        "</tr>";
        $('#mobTable > tbody').append(rows);
    }); 
}

function fillQuickTable(data){
    $.each(data[0], function (i, item) {
        var rows = "<tr>" +
            "<td>" + (i+1) + "</td>" +
            "<td>" +
                "<a class='btn btn-round btn-icon btn-light' onclick=\"showModal(" + item.id + ", \'rapido\')\";>" +
                "<em class='icon ni ni-edit'></em></a>" + 
            "</td>" +

            "<td>" + item.section + "</td>" +
            "<td>" + item.type + "</td>" +
            "<td>" + item.letters + "</td>" +

            getPoliticalFields(data[1], item) +
                    
        "</tr>";
        $('#mobTable > tbody').append(rows);
    });
}

function checkImage(src){
    return (src != null) ? "<td> <a data-fancybox='gallery' href='" + src + "'> <img src='" + src + "' alt='' style='max-width: 200px; width: 150px;'> </a> </td>" : " <td> No Image </td>";
}

function getPoliticalFields(array, booth){
    var political = '';
    $.each(array, function(i, field){
        political += '<td>' + booth[field] + '</td>';
    });
    return political;
}

</script>



<style>
    @media only screen and (min-width: 550px) and (max-width: 980px){
        #lbl-fields{
            font-size: 0.65rem !important;
            margin-right: 0px !important;
        }
    }

    @media only screen and (min-width: 1px) and (max-width: 549px){
        #lbl-fields{
            font-size: 0.6rem !important;
            margin-right: 0px !important;
        }
    }
</style>