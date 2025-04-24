<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i>Modificar Tipo Registro</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnGuardarTipoRegistro"><i class="fas fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                 <input type="hidden" id="_ID_TEMP_RECAUDA" >
                <div class="col-md-12">
                    <label class="label-texto">Tipo Registro <small id="txtObservacionHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                    <select id="cbxTipoRegistro" style="width: 100%; font-size: 11px;" class="cbx-texto">
                        <option selected="true" value="" disabled="disabled">TODOS</option>
                        <?php
                        $Clientes = new ControllerCategorias();
                        $ClientesVer = $Clientes->VerTipoRegistroRecaudacion();
                        foreach ($ClientesVer as $Cliente) {
                        ?>
                       <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                        <?php echo $Cliente['Nombre']; ?>
                        </option>
                       <?php } ?>
                   </select>
                </div>
            </div>
        </div>
    </div>
</div>