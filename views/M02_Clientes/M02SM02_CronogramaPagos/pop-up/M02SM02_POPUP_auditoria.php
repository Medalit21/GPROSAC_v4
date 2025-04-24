<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close">
				<i class="fa fa-window-close" aria-hidden="true"></i>
			</button>
            <span><i class="fas fa-user-secret" aria-hidden="true"></i> Datos de Control</span>
        </div>
        <div class="modal-body">
            <input type="hidden" id="txtIdCronogramaC">

            <div class="form-row">

                <div class="col-md-12">
                    <label for="" class="label-texto">Registrado por:<small id="txtFecVencimientoCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="text" id="txtUserRegister" class="caja-texto text-center" readonly>
                    <input type="text" id="txtDateRegister" class="caja-texto text-center" readonly>
                </div>
                <br><br>
                <div class="col-md-12"><br>
                    <label for="" class="label-texto">Actualizado por:<small id="txtFecVencimientoCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="text" id="txtUserUpdate" class="caja-texto text-center" readonly>
                    <input type="text" id="txtDateUpdate" class="caja-texto text-center" readonly>
                </div>
                
            </div>
        </div>
    </div>
</div>