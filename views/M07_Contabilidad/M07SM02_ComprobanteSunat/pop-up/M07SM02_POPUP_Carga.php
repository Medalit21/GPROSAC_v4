<div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="width: 900px;">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> Comprobantes Sunat</span>
        </div>
        <div class="head-model cabecera-modal-accion" id="PanelBotonRegComprobante">
            <button class="btn btn-registro-success" id="btnGuardarPagoCV"><i class="fas fa-save"></i> Guardar</button>
            <button class="btn btn-registro" id="btnNuevoPagoCV"><i class="fas fa-file-alt"></i> Nuevo</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <input type="hidden" id="__IDPAGO_DET">
                <input type="hidden" id="__IDPAGO_DET_COMPROBANTE">
                <div class="row" id="PanelCamposRegComprobante">
                    <div class="col-md-6">
                        <label class="label-texto">Fecha Emision<small id="txtFechaEmisionCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <input type="date" id="txtFechaEmisionCV" class="caja-texto" value="">
                    </div>
                    <div class="col-md-6">
                        <label for="" class="label-texto">Tipo Comprobante Sunat<small id="cbxTipoComprobanteCVHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <select id="cbxTipoComprobanteCV" class="cbx-texto">
                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            <?php
                            $VerCompro = new ControllerCategorias();
                            $VerCompro = $VerCompro->VerTipoComprobanteSunat();
                            foreach ($VerCompro as $item_comprobante) {
                            ?>
                                <option value="<?php echo $item_comprobante['ID']; ?>">
                                    <?php echo $item_comprobante['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Serie<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <input type="text" id="txtSerieCV" class="caja-texto" value="" maxlength="4">
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Numero<small id="txtNumeroCVHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="number" id="txtNumeroCV" class="caja-texto" value="" maxlength="8">
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Tipo Documento<small id="cbxTipoDocHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <select id="cbxTipoDoc" class="cbx-texto">
                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            <?php
                            $VerCompro = new ControllerCategorias();
                            $VerCompro = $VerCompro->VerTipoDocumento();
                            foreach ($VerCompro as $item_comprobante) {
                            ?>
                                <option value="<?php echo $item_comprobante['ID']; ?>">
                                    <?php echo $item_comprobante['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Nro Documento<small id="txtNumeroCVHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="text" id="txtNroDocumento" class="caja-texto" value="">
                    </div>

                    <div class="col-md-12">
                        <label for="" class="label-texto">Cliente<small> (Apellidos y Nombres)</small></label>
                        <input type="text" id="txtDatosCliente" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();">
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Tipo Moneda<small id="txtTipoMonedaHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <select id="txtTipoMoneda" class="cbx-texto">
                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            <?php
                            $VerCompro = new ControllerCategorias();
                            $VerCompro = $VerCompro->VerTipoMonedaSigla();
                            foreach ($VerCompro as $item_comprobante) {
                            ?>
                                <option value="<?php echo $item_comprobante['Nombre']; ?>">
                                    <?php echo $item_comprobante['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="" class="label-texto">Tipo Cambio<small id="txtTotalPagadoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="number" id="txtTipoCambio" class="caja-texto" value="">
                    </div>

                    <div class="col-md-3">
                        <label for="" class="label-texto">Total Pagado<small id="txtTotalPagadoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="number" id="txtTotalPagado" class="caja-texto" value="">
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Fecha Vencimiento<small id="txtFechaVencimientoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                            </small></label>
                        <input type="date" id="txtFechaVencimiento" class="caja-texto" value="">
                    </div>

                    <div class="col-md-6">
                        <label for="" class="label-texto">Concepto<small id="cbxConceptosHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <select id="cbxConceptos" class="cbx-texto">
                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            <?php
                            $VerCompro = new ControllerCategorias();
                            $VerCompro = $VerCompro->VerConceptos();
                            foreach ($VerCompro as $item_comprobante) {
                            ?>
                                <option value="<?php echo $item_comprobante['ID']; ?>">
                                    <?php echo $item_comprobante['Nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>


                    <form class="col-md-12 mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                        <div class="col-md-12" style="margin-left: -7px;">
                            <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                            <label class="label-texto">Comprobante <small>( PDF )</small></label>
                            <input type="file" id="ComprobanteCV" name="ComprobanteCV" accept=".pdf">
                            <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">
                        </div>
                    </form> 
                </div>                         
                <div class="col-md-12">
                    <table class="table table-striped table-bordered w-100" cellspacing="0" id="TablaDetalleComprobantes">
                        <thead class="cabecera">
                            <tr>
                                <th></th>
                                <th>Serie</th>
                                <th>Numero</th>
                                <th>Comprobante</th>
                            </tr>
                        </thead>
                        <tbody class="control-detalle">
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>