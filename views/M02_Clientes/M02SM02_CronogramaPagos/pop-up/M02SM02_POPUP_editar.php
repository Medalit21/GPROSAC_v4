<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close">
				<i class="fa fa-window-close" aria-hidden="true"></i>
			</button>
            <span><i class="fa fas fa-edit" aria-hidden="true"></i> Datos de la Letra</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-registro-success" id="btnGuardarLetraC"><i class="fa fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="txtIdCronogramaC">

            <div class="form-row">

                <div class="col-md-6">
                    <label for="" class="label-texto">Fecha Vencimiento <small id="txtFecVencimientoCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="date" id="txtFecVencimientoC" class="caja-texto">
                </div>
                
                <div class="col-md-6">
                    <label for="" class="label-texto">Letra <small id="txtLetraCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="text" id="txtLetraC" class="caja-texto">
                </div>


                <div class="col-md-6">
                    <label for="" class="label-texto">Monto Letra<small id="txtMontoLetraCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="number" id="txtMontoLetraC" class="caja-texto">

                </div>
                <div class="col-md-6">
                    <label class="label-texto">Intereses<small id="boxmotivo_eliminarHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="number" id="txtInteresesC" class="caja-texto">
                </div>
                
                <div class="col-md-6">
                    <label class="label-texto">Amortizacion<small id="txtAmortizacionCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="number" id="txtAmortizacionC" class="caja-texto">
                </div>
                
                <div class="col-md-6">
                    <label class="label-texto">Capital Vivo<small id="txtCapitalVivoCHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="number" id="txtCapitalVivoC" class="caja-texto">
                </div>
                
                <div class="col-md-6">
                    <label class="label-texto">Estado<small id="cbxEstadoLetraHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <select id="cbxEstadoLetra" class="cbx-texto">
                        <option value="1">PENDIENTE</option>
                        <option value="2" disabled="disabled">PAGADO</option>
                        <option value="3">VENCIDO</option>
                    </select>
                </div>

            </div>
        </div>
    </div>
</div>