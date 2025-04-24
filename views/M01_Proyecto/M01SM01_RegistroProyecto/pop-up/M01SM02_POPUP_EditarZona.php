<div class="modal-dialog modal-sm modal-dialog-centered" role="document">

    <div class="modal-content">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fa fa-sync" aria-hidden="true"></i> Actualizar Datos de la Zona</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-registro-success" id="btnGuardarZonaascc"><i class="fa fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body">           

            <div class="form-row separador">
                <input type="text" id="txtidZonaPopupcc" hidden>
                <div class="col">
                    <label for="" class="label-texto">Nombre</label>
                    <input type="text" id="txtNombreZonaPopup" class="caja-texto" placeholder="Nombre Zona">
                </div>
                <div class="col">
                    <label for="" class="label-texto">Nro Manzanas</label>
                    <input type="number" id="txtNroManzanasPopup" class="caja-texto" placeholder="Nro Manzanas">
                </div>
                <div class="col">
                    <label for="" class="label-texto">Área (m<sup>2</sup>)</label>
                    <input type="number" id="txtAreaPopup" class="caja-texto" placeholder="Area de Zona">
                </div>
            </div>
        </div>
    </div>
</div>