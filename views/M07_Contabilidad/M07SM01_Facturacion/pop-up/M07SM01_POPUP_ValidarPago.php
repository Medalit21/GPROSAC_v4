<div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="width: 900px;">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fas fa-plus-square"></i> Asignar importe a emitir</span>
        </div>
        <div class="head-model cabecera-modal-accion" id="PanelBotonRegComprobante">
            <button class="btn btn-model-success" id="btnAgregarPagoVAL"><i class="fas fa-arrow-circle-right"></i> Continuar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <input type="hidden" id="__IDPAGO_DET">
                <input type="hidden" id="__PROP">
                <input type="hidden" id="__CLIE">
                <input type="hidden" id="__TIPCOM">
                <div class="row">                    
                    <div class="col-md-6">
                        <label for="" class="label-texto">Tipo Moneda<small id="txtTipoMonedaHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <select id="cbxTipoMonedaVAL" class="form-control" disabled>
                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            <?php
                            $VerCompro = new ControllerCategorias();
                            $VerCompro = $VerCompro->VerTipoMonedaSigla();
                            foreach ($VerCompro as $item_comprobante) {
                            ?>
                                <option value="<?php echo $item_comprobante['ID']; ?>">
                                    <?php echo $item_comprobante['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Tipo Cambio<small id="txtTotalPagadoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="number" id="txtTipoCambioVAL" class="form-control" value="" readonly>
                    </div>

                    <div class="col-md-6 bajar-lb">
                        <label for="" class="label-texto">Saldo pendiente de emision<small id="txtTotalPagadoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="text" id="txtTotalPagadoVAL" class="form-control" value="" readonly>
                    </div>

                    <div class="col-md-6 bajar-lb">
                        <label for="" class="label-texto">Total a Emitir<small id="txtTotalPagadoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="text"  id="txtTotalEmitirVAL" class="form-control CurrencyInput campo-total" value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                    </div>
                </div>     

            </div>
        </div>
    </div>
</div>