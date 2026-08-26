<style>
    .pac-container {
        z-index: 1051 !important; 
    }

    .modal {
        z-index: 1041;
    }
</style>

<div class="modal fade" tabindex="-1" id="mapModal">
    <div class="modal-dialog modal-xl" role="document" id="modal_dialog_size">
        <div class="modal-content">
            <div class="btn btn-icon close" data-dismiss="modal" aria-label="Close" onclick="closeMapModal()">
                <em class="icon ni ni-cross"></em>
            </div>
            <div class="modal-header">
                <h6 class="modal-title">UBICA LA DIRECCIÓN EN EL MAPA</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Buscar dirección o lugar</label>
                            <input type="text" class="form-control" id="addressInput">
                            <div onclick="showMapAddress()" class="btn btn-outline-light mt-3">Mostrar en el mapa</div>
                        </div>
                        <div id="map" style="height: 400px;width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-block">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3" id="lat_modal_input">
                        <label class="form-label">Latitud</label>
                            <input type="text" class="form-control" 
                                value="{{ isset($person->lat) ? $person->lat : '' }}"
                                id="mapLat"
                                readonly 
                            >
                    </div>
                    
                    <div class="col-12 col-md-4 mb-3" id="lng_modal_input">
                        <label class="form-label">Longitud</label>
                            <input type="text" class="form-control" 
                                value="{{ isset($person->lng) ? $person->lng : '' }}"
                                id="mapLng" 
                                readonly
                            >
                    </div>

                    @if ( $plan->geometry == "LineString" )
                        <div class="col-12 col-md-4 mb-3" id="distance_modal_input">
                            <label class="form-label">Distancia</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control"
                                    value=""
                                    id="line_distance"
                                    readonly
                                >

                                <div class="form-icon form-icon-right" style="right: 10px !important;">
                                    Metros
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="col-12 col-md-12 text-right" id="btn-save-coor">
                        <div class="btn btn-outline-light btn-form" onclick="saveCoords()">
                            Guardar Coordenadas
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
    function closeMapModal(){
        $('#mapModal').modal('hide');
    }
</script>