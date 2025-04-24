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
                <input type="text" id="txtidTipoCPopupcc" hidden>
                <div class="col-md-4">
                    <label for="" class="label-texto">Zona</label>
                    <input type="text" id="txtZonaTipoCPopup" class="caja-texto" placeholder="Nombre Zona" readonly>
                </div>      
				<div class="col-md-4">
                    <label for="" class="label-texto">Manzana</label>
                    <input type="text" id="txtManzanaTipoCPopup" class="caja-texto" placeholder="Nombre Manzana" readonly>
                </div>      
				<div class="col-md-4">
                    <label for="" class="label-texto">Tipo Casa</label>
                    <input type="text" id="txtTipoCasaPopup" class="caja-texto" placeholder="Nombre Tipo Casa">
                </div>                

            </div>
        </div>
    </div>
</div>