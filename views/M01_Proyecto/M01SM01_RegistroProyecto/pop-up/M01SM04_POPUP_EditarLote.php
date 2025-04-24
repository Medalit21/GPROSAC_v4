<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fa fa-sync" aria-hidden="true"></i> Actualizar Datos</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-registro-success" id="btnGuardarLotescc"><i class="fa fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">

            <div class="form-row separador">
                <input type="text" id="txtidLotePopupcc" hidden>
                <div class="col-md-4">
                    <label for="" class="label-texto">Nombre</label>
                    <input type="text" id="txtNombreLotePopup" class="caja-texto" placeholder="Nombre Lote">
                </div>                
                <div class="col-md-4">
                    <label for="" class="label-texto">Área Terreno (m<sup>2</sup>)</label>
                    <input type="number" id="txtAreaLotePopup" class="caja-texto" placeholder="Area de Lote">
                </div>
                <div class="col-md-4">
                    <label for="" class="label-texto">Tipo Moneda</label>
                    <select class="cbx-texto" id="cbxTipoMonedaPopup">
                        <option selected="true" value="" disabled="disabled">Seleccione...</option>

                        <?php
                        $TipoMoneda = new ControllerCategorias();
                        $VerTipoMond = $TipoMoneda->VerTipoMoneda2();
                        foreach ($VerTipoMond as $TipoMoned) {
                        ?>
                            <option value="<?php echo $TipoMoned['ID']; ?>">
                                <?php echo $TipoMoned['Nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="" class="label-texto">Valor con Casa</label>
                    <input type="number" id="txtValorCCasaPopup" class="caja-texto">
                </div>
                <div class="col-md-4">
                    <label for="" class="label-texto">Valor sin Casa</label>
                    <input type="number" id="txtValorSCasaPopup" class="caja-texto">
                </div>
            </div>
        </div>
    </div>
</div>