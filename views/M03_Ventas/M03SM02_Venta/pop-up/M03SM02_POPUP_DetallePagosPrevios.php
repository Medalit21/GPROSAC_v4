<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> Nuevo Pago </span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnGuardarPagosPrevios2"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                 <input type="hidden" id="__IDPAGOS_PREVIOS" >
                <div class="col-md-6">
                    <label class="label-texto">Tipo Moneda<small id="cbxTipoDocumentoAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="cbxTipoMonedaPP2" class="cbx-texto">
                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                        <?php
                            $verTipoDocumento = new ControllerCategorias();
                            $VerTipoDocumento = $verTipoDocumento->VerTipoMonedaSigla();
                            foreach ($VerTipoDocumento as $Motiv){
                        ?>
                        <option value="<?php echo $Motiv['ID']; ?>"><?php echo $Motiv['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Importe <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="number" id="txtImportePP2" class="caja-texto" value="">
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Tipo Pago <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="cbxTipoPagoPP2" class="cbx-texto" disabled>
                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                        <option value="1">PAGO RESERVA</option>
                        <option selected="true" value="2">PAGO VENTA</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Fecha Pago<small id="txtFechaSubidaAdjuntoHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="date" id="txtFechaPagoPP2" class="caja-texto">
                </div>

                <div class="col-md-12">
                    <label class="label-texto">Descripcion <small id="txtDescripcionAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <textarea rows="4" maxlength="150" id="txtDescripcionPP2" class="caja-texto"  placeholder="Describa el motivo" style="height: auto;resize: none;" required></textarea>
                </div>
                <form class="col-md-12 mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                    <div class="col-md-12">
                        <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                        <label class="label-texto">Cargar Voucher <small id="txtDescripcionAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <input type="file" id="fichero42" name="fichero42" accept=".pdf">
                        <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>