<div class="modal fade" tabindex="1" id="contrastModal" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-xl" role="document" id="modal_dialog_size">
        <div class="modal-content">
            <div class="close" data-dismiss="modal" aria-label="Close" onclick="closeContrastModal()" style="cursor: pointer;">
                <em class="icon ni ni-cross"></em>
            </div>
            <div class="modal-header">
                <h6 class="modal-title">Visualizar Color de Puntos y Líneas en Mapa</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="contrastMap" style="height: 400px;width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
@if ( Route::currentRouteName() != "plans.form.create" )
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASE5IyHpb_g-nsXNnDmey2GoZYJhOTE1Y&libraries=places&callback=initContrastMap"></script>
@endif

<script>
    let contrastMap;
    let iniContrastLat = "{{ isset($person->lat) ? $person->lat : '19.543454812522057' }}";
    let iniContrastLng = "{{ isset($person->lng) ? $person->lng : '-99.23502275276154' }}";
    let centerContrastLocation = { lat: Number(iniContrastLat), lng: Number(iniContrastLng) };
    let contrastMarker;
    let contrastLine;

    async function initContrastMap() {
        contrastMap = new google.maps.Map(document.getElementById('contrastMap'), {
            zoom: 14,
            center: centerContrastLocation,
            draggableCursor: 'default',
            mapTypeId: google.maps.MapTypeId.SATELLITE,
        });

        contrastMarker = new google.maps.Marker({
            position: centerContrastLocation,
            map: contrastMap,
        });

        let pathLine = [
            { lat: 19.545312455498387, lng: -99.23549173680092 },
            { lat: 19.542404241385814, lng: -99.23339196968048 }
        ];

        contrastLine = new google.maps.Polyline({
            path: pathLine,
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 4,
            map: contrastMap,
        });
    }

    $(document).ready(function(){        
        $("#contrastModal").on("hidden.bs.modal", function() {
            $basicModal.modal("show");
        });
    });
</script>