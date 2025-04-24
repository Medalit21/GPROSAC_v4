<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> Documento Adjunto </span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnGuardarAdjunto"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                 <input type="hidden" id="_ID_ARCHIVO" >
                 <input type="hidden" id="_ID_ARCHIVO_VENTA" >
                <div class="col-md-6">
                    <label class="label-texto">Tipo Documento <small id="cbxTipoDocumentoAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="cbxTipoDocumentoAdjunto" class="cbx-texto">
                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                        <?php
                        $verTipoDocumento = new ControllerCategorias();
                        $VerTipoDocumento = $verTipoDocumento->VerTipoDocumentoVenta();
                        foreach ($VerTipoDocumento as $Motiv) {
                        ?>
                        <option value="<?php echo $Motiv['ID']; ?>"><?php echo $Motiv['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Fecha <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="date" id="txtFechaSubidaAdjunto" class="caja-texto" value="">
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Notaria <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="txtNotariaAdjunto" class="cbx-texto">
                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                        <?php
                        $verNotarias = new ControllerCategorias();
                        $VerNotarias = $verNotarias->VerNotarias();
                        foreach ($VerNotarias as $Notarias) {
                        ?>
                        <option value="<?php echo $Notarias['ID']; ?>"><?php echo $Notarias['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Fecha Firma <small id="txtFechaSubidaAdjuntoHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="date" id="txtFechaFirmaAdjunto" class="caja-texto">
                </div>

                <div class="col-md-6">
                    <label for="" class="label-texto">Importe Inicial <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="txtTipoMonedaImporteInicial" class="cbx-texto col-md-4">
                        <?php
                        $VerTipoMonedasSimbolo = new ControllerCategorias();
                        $VerTipoMonedasSimbolo = $VerTipoMonedasSimbolo->VerTipoMonedaSimbolo();
                        foreach ($VerTipoMonedasSimbolo as $Moneda) {
                        ?>
                        <option value="<?php echo $Moneda['ID']; ?>"><?php echo $Moneda['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                    <input type="number" id="txtImporteInicialAdjunto" class="caja-texto col-md-8">
                </div>
                <div class="col-md-6">
                    <label for="" class="label-texto">Valor Cerrado <small id="txtFechaSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
					<select id="txtTipoMonedaValorCerrado" class="cbx-texto col-md-4">
                        <?php
                        $VerTipoMonedasSimbolo = new ControllerCategorias();
                        $VerTipoMonedasSimbolo = $VerTipoMonedasSimbolo->VerTipoMonedaSimbolo();
                        foreach ($VerTipoMonedasSimbolo as $Moneda) {
                        ?>
                        <option value="<?php echo $Moneda['ID']; ?>"><?php echo $Moneda['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                    <input type="number" id="txtValorCerradoAdjunto" class="caja-texto col-md-8">
                </div>

                <div class="col-md-12">
                    <label class="label-texto">Descripcion <small id="txtDescripcionAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <textarea rows="4" maxlength="150" id="txtDescripcionAdjunto" class="caja-texto"  placeholder="Describa el motivo" style="height: auto;resize: none;" required></textarea>
                </div>
                <form class="col-md-12 mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                    <div class="col-md-12">
                        <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                        <label class="label-texto">Cargar Adjunto <small id="txtDescripcionAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <input type="file" id="fichero" name="fichero" accept=".pdf">
                        <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                    </div>
                </form>
                <div class="col-md-12">
                    <label for="" class="label-texto">Nombre Archivo <small id="txtNombreArchivoSubidaAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="text" id="txtNombreArchivo" class="caja-texto" value="">
                </div>
            </div>
        </div>
    </div>
</div>