<div class="modal fade" tabindex="-1" id="searchModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title">Búsqueda de Clave de Elector</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 row">

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="first_name">Nombre (s) *</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" name="first_name_search" 
                                id="firstName_search"
                                oninput="convertToUppercase('firstName_search')">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="name">Apellido Paterno *</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" name="last_name_1_search"
                                id="lastName1_search"
                                oninput="convertToUppercase('lastName1_search')">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="name">Apellido Materno *</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" name="last_name_2_search"
                                id="lastName2_search"
                                oninput="convertToUppercase('lastName2_search')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="name">Calle *</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" name="street_search"
                                id="street_search"
                                oninput="convertToUppercase('street_search')">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light">
                <div id="buscarClaveBtn" class="btn btn-outline-dark mb-3 mb-md-0">
                    <span>Buscar Clave</span>
                </div>
            </div>
        </div>
    </div>
</div>
