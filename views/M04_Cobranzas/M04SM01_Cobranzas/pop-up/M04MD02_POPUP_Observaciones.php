<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> Observar Pago</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnEnviarRespuesta"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                 <input type="hidden" id="_ID_PAGO_DETALLE" >
                <div class="col-md-12">
                    <label class="label-texto">OBSERVACIÓN <small id="txtObservacionHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <textarea rows="4" maxlength="100" id="txtObservacion" class="caja-texto"  placeholder="Describa el motivo" style="height: auto;resize: none;" required disabled></textarea>
                </div>
                <div class="col-md-12">
                    <label class="label-texto">RESPUESTA <small id="txtRespuestaHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <textarea rows="4" maxlength="100" id="txtRespuesta" class="caja-texto"  placeholder="Describa el motivo" style="height: auto;resize: none;" required disabled></textarea>
                </div>
                
            </div>
        </div>
    </div>
</div>