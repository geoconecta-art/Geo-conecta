<div class="card-inner pt-1 px-0 h-100">
    <div class="nk-wg-action">
        <div>
            <input type="search" class="form-control form-control-sm my-2" placeholder="Buscar" aria-controls="mobTable"
                name="input-direction-search" id="input-catastro-search">
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="border border-1 rounded rounded-2 p-2 col-12 col-md-4 col-lg-12" style="overflow-y: auto;" id="catastro-container">
                @include('admin.map._catastro_list')
            </div>
    
            <div class="p-0 my-2 w-100 d-flex justify-content-center col-12 col-md-3 col-lg-12" style="height: fit-content;">
                <div class="btn btn-dim btn-danger m-0" onclick="removeLotes()">
                    Limpiar Mapa
                </div>
            </div>
    
            <div class="mb-3 border border-1 rounded rounded-2 p-2 col-12 col-md-4 col-lg-12" style="overflow-y: auto;" id="catastro-selected-container">
                <ul id="lotes-selected-list">
                </ul>
            </div>
        </div>

    </div>
</div>