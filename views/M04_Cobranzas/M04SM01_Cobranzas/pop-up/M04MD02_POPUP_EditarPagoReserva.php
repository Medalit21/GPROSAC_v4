<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> Editar Pago Reserva</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnGuardarPagoPR"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                 <input type="hidden" id="_ID_PAGOR" >
                <div class="col-md-6">
                    <label class="label-texto">Fecha Pago <small id="txtFechaPagoPHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <input type="date" id="txtFechaPagoPR" class="caja-texto" value="">
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Tipo Moneda <small id="cbxTipoMonedaPHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <select id="cbxTipoMonedaPR" class="cbx-texto">
                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                        <?php
                        $verNotarias = new ControllerCategorias();
                        $VerNotarias = $verNotarias->VerTipoMonedaSigla();
                        foreach ($VerNotarias as $Notarias) {
                        ?>
                        <option value="<?php echo $Notarias['ID']; ?>"><?php echo $Notarias['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Importe Pago <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <input type="text" id="txtImportePagoPR" class="caja-texto" value="">
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Tipo Cambio <small id="txtFechaSubidaAdjuntoHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="number" id="txtTipoCambioPR" class="caja-texto" value="">
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Pagado <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <input type="text" id="txtPagadoPR" class="caja-texto" value="">
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Agencia Bancaria <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
					<select id="cbxBancoPR" class="cbx-texto col-md">
                        <?php
                        $VerTipoMonedasSimbolo = new ControllerCategorias();
                        $VerTipoMonedasSimbolo = $VerTipoMonedasSimbolo->VerBancos();
                        foreach ($VerTipoMonedasSimbolo as $Moneda) {
                        ?>
                        <option value="<?php echo $Moneda['ID']; ?>"><?php echo $Moneda['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Medio Pago <small id="cbxMedioPagoPHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="cbxMedioPagoPR" class="cbx-texto col-md">
                        <?php
                        $VerTipoMonedasSimbolo = new ControllerCategorias();
                        $VerTipoMonedasSimbolo = $VerTipoMonedasSimbolo->VerMedioPago();
                        foreach ($VerTipoMonedasSimbolo as $Moneda) {
                        ?>
                        <option value="<?php echo $Moneda['ID']; ?>"><?php echo $Moneda['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Tipo Comprobante <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
					<select id="cbxTipoComprobantePR" class="cbx-texto col-md">
                        <?php
                        $VerTipoMonedasSimbolo = new ControllerCategorias();
                        $VerTipoMonedasSimbolo = $VerTipoMonedasSimbolo->VerTipoComprobanteVentas();
                        foreach ($VerTipoMonedasSimbolo as $Moneda) {
                        ?>
                        <option value="<?php echo $Moneda['ID']; ?>"><?php echo $Moneda['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Nro Operación <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <input type="text" id="txtNumOperacionPR" class="caja-texto" value="">
                </div>

                <div class="col-md-6" hidden>
                    <label for="" class="label-texto">Serie <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <input type="text" id="txtSeriePR" class="caja-texto" value="">
                </div>
                <div class="col-md-6" hidden>
                    <label for="" class="label-texto">Número <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <input type="number" id="txtNumeroPR" class="caja-texto" value="">
                </div>

                <form class="col-md-12 mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                    <div class="col-md-12" style="margin-left: -7px;">
                        <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                        <label class="label-texto">Voucher Pago <small>( JPG / JPEG / PNG / PDF )</small></label>
                        <input type="file" id="ficheroPagoR" name="ficheroPagoR" accept=".pdf, .jpg, .jpeg, .png">
                        <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                    </div>
                </form>
            </div>
            <br><br>
            <div class="col-md text-center">
                <button id="btnAprobarPagoReserva" type="button" class="btn btn-registro"><i class="fas fa-check"></i> Aprobar</button>  
            </div>
        </div>
    </div>
</div>