<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-lock" aria-hidden="true"></i> Bloquear Lote</span>
        </div>
        <div class="modal-body">           

            <div class="form-row separador">
                <input type="text" id="txtIdLote" hidden>
                <div class="col">
                    <label for="" class="label-texto">Clave de Autorizaci&oacute;n</label>
                </div>
                <div class="col">
                    <input type="password" id="txtCodigoBloqueoLote" class="caja-texto" placeholder="Escriba clave aqui">
                </div>
                <div class="col">
                    <button class="btn btn-registro-success" id="btnBloquearLote"><i class="fas fa-lock"></i> Ejecutar</button>
                </div>
            </div>
        </div>
    </div>
</div>