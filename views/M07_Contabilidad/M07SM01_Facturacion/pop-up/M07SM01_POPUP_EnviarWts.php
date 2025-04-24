<div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="width: 900px;">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fab fa-whatsapp"></i> Enviar Documento</span>
        </div>
        <div class="head-model cabecera-modal-accion" id="PanelBotonRegComprobante">
            <button class="btn btn-model-success" id="btnEnviarMensaje"><i class="fas fa-location-arrow"></i> Enviar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <input type="hidden" id="__txtIDCOMPROBANTE">
                <div class="row col-md-12">
                    <div class="col-md-12 text-center">
                        <label class="label-texto">Nro Celular<small id="txtFechaEmisionCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                        <input type="text" id="txtNroCelular" class="form-control text-center" maxlength="9" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" placeholder="980223344">
                    </div>
                </div> 

            </div>
        </div>
    </div>
</div>