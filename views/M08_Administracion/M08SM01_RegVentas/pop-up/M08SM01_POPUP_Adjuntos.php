<div class="modal-dialog modal-sm modal-dialog-centered justify-content-center" role="document" style="width: 1600px;">

    <div class="modal-content" style="width: 1600px;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fa fa-list" aria-hidden="true"></i>Adjuntos</span>
        </div>
        <div class="head-model cabecera-modal-accion" hidden>
            <button class="btn btn-registro-success" id="btnGuardarTarea"><i class="fa fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body" style="width: 1000px;">
            <input type="hidden" id="txtidPago">
            <div id="PagosRealizados">
                <label for="" class="label-texto">DOCUMENTOS ADJUNTOS RELACIONADOS A LA VENTA</label>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="table-responsive tamanio-tabla">
                            <table class="table table-striped table-bordered w-100" cellspacing="0" id="dataAdjuntoTable">
                                <thead class="cabecera">
                                    <tr>
                                        <th>Tipo Documento</th>
                                        <th>Fecha</th>
                                        <th>Notaria</th>
                                        <th>Fecha Firma</th>
                                        <th>Importe Inicial</th>
                                        <th>Valor Cerrado</th>
                                        <th>Adjunto</th>
                                        <th>Nombre Adjunto</th>
                                    </tr>
                                </thead>
                                <tbody class="control-detalle">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
            </div>

        </div>
    </div>
</div>