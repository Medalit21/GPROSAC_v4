<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> Liberar Lote </span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnLiberar"><i class="fas fa-hourglass-start"></i> Liberar</button>
        </div>
        <input type="hidden" id="__ID_CLIENTE" >
        <input type="hidden" id="__ID_LOTE" >
        <input type="hidden" id="__ID_RESERAVACION" >
        <div class="modal-body">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="" class="label-texto">Documento</label>
                    <input id="txtDocumento_cliente" class="caja-texto" placeholder="documento" disabled>
                </div>
            </div>

            <div class="form-row separador">
                <div class="col">
                    <label for="" class="label-texto">Ape. Paterno</label>
                    <input type="text" id="apePaterno_cliente" class="caja-texto" placeholder="Apellido Paterno"
                        disabled>
                </div>
                <div class="col">
                    <label for="" class="label-texto">Ape. Materno</label>
                    <input type="text" id="apeMaterno_cliente" class="caja-texto" placeholder="Apellido Materno"
                        disabled>
                </div>
                <div class="col">
                    <label for="" class="label-texto">Nombres</label>
                    <input type="text" id="nombres_cliente" class="caja-texto" placeholder="Nombres" disabled>
                </div>
            </div>

            <p class="texto-guia"> Para proceder a <strong>Liberar el Lote</strong> deberá seleccionar la fecha y
                completar con una breve descripción del mismo.</p>

            <div class="form-row">
                <div class="col-md-6">
                    <label for="" class="label-texto">Fecha Liberación <small id="txtFechaLiberacionHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <input type="date" id="txtFechaLiberacion" class="caja-texto">

                </div>
                <div class="col-md-6">
                    <label class="label-texto">Motivo <small id="cbxMotivoLiberacionHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <select id="cbxMotivoLiberacion" class="cbx-texto">
                        <option selected="true" disabled="disabled" value="">Seleccione...</option>
                        <?php
                        $Motivo = new ControllerCategorias();
                        $VerMotivo = $Motivo->VerMotivoLiberacion();
                        foreach ($VerMotivo as $Motiv) {
                        ?>
                        <option value="<?php echo $Motiv['ID']; ?>"><?php echo $Motiv['Nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="label-texto">Descripcion <small id="txtDescripcionLiberacionHtml"
                            class="form-text text-muted-validacion text-danger ocultar-info">
                        </small></label>
                    <textarea rows="4" maxlength="150" id="txtDescripcionLiberacion" class="caja-texto"
                        placeholder="Describa el motivo" style="height: auto;resize: none;" required></textarea>
                </div>
                
            </div>
        </div>
    </div>
</div>