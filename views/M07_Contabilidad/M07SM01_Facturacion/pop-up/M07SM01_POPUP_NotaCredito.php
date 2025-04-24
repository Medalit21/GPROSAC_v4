<div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="width: 1000px;">

    <div class="modal-content" style="width: 1000px;  margin-left: -220px;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close" aria-hidden="true"></i></button>
            <span><i class="fas fa-clone"></i> NOTA DE CR&Eacute;DITO </span>
        </div>
        <div class="modal-body" style="width: 1000px;">

            <!-- accoridan part -->
            <div class="accordion" id="accordionExample">
                <div class="card m-b-0">
                    <div id="collapsethree" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="col-md-12" style="margin-top: -10px;">
                                <input type="hidden" name="txtSerieControlNC" id="txtSerieControlNC">
                                <input type="hidden" name="txtNumeroControlNC" id="txtNumeroControlNC">
                                <input type="hidden" name="txtSerieControlDNC" id="txtSerieControlDNC">
                                <input type="hidden" name="txtNumeroControlDNC" id="txtNumeroControlDNC">
                                
                                <div class="row">
                                    <div class="col-md-1 text-left">
                                        <label for="" class="label-texto bajar-lb">Empresa :</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="caja-texto" name="txtRazonSocialNC" id="txtRazonSocialNC" readonly>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <label for="" class="label-texto">NOTA DE CR&Eacute;DITO</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1 text-left">
                                        <label for="" class="label-texto bajar-lb">Direcci&oacute;n :</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="caja-texto" name="txtDireccionNC" id="txtDireccionNC" readonly>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-1 text-right">
                                        <label for="" class="label-texto bajar-lb">Ruc :</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto" name="txtRucNC" id="txtRucNC" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 text-left">
                                        <label for="" class="label-texto bajar-lb">Ubigeo :</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="caja-texto" name="txtLugarNC" id="txtLugarNC" readonly>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-2 text-right">
                                        <label for="" class="label-texto bajar-lb">Serie/N&uacute;mero :</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto" name="txtSerieNC" id="txtSerieNC" readonly>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-md-2">
                                        <label for="" class="label-texto bajar-lb">Fecha de Emisi&oacute;n : </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" class="caja-texto" name="txtFecEmisionNC" id="txtFecEmisionNC" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-texto bajar-lb">Fecha de Vencimiento : </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" class="caja-texto" name="txtFecVencimientoNC" id="txtFecVencimientoNC" value="">
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="" class="label-texto bajar-lb">Denominaci&oacute;n : </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="caja-texto" name="txtDenominacion" id="txtDenominacion" value="" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-texto bajar-lb">Fecha del comprobante que modifica : </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" class="caja-texto" name="txtFechaModificaCom" id="txtFechaModificaCom" value="" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="" class="label-texto bajar-lb">Nro : </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="caja-texto" name="txtNroComprobanteNC" id="txtNroComprobanteNC" value="" readonly> 
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="" class="label-texto">DNI / RUC</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="caja-texto" name="txtNroDocumentoNC" id="txtNroDocumentoNC" value="" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="" class="label-texto">Cliente</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="caja-texto" name="txtDatosClienteNC" id="txtDatosClienteNC" value="" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="" class="label-texto">Tipo de Moneda</label>
                                    </div>
                                    <div class="col-md-2">
                                        <select id="cbxTipoMonedaNC" class="cbx-texto" disabled>
                                            <?php
                                                $ComprobanteSunat = new ControllerCategorias();
                                                $ComprobanteSunatVer = $ComprobanteSunat->VerTipoMonedaSigla();
                                                foreach ($ComprobanteSunatVer as $Comprobante) {
                                                ?>
                                                <option value="<?php echo $Comprobante['Nombre']; ?>" style="font-size: 11px;">
                                                    <?php echo $Comprobante['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="" class="label-texto">Tipo Nota de Cr&eacute;dito</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="cbxTipoNotaCredito" class="cbx-texto">
                                            <?php
                                                $ComprobanteSunat = new ControllerCategorias();
                                                $ComprobanteSunatVer = $ComprobanteSunat->VerTiposNotaCredito();
                                                foreach ($ComprobanteSunatVer as $Comprobante) {
                                                ?>
                                                <option value="<?php echo $Comprobante['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Comprobante['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                               <div class="row">

                                    <div class="col-md-2">
                                        <label for="" class="label-texto">Motivo o Sustento</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="caja-texto" name="txtSustentoNC" id="txtSustentoNC" value="ANULACION DE LA OPERACION">
                                    </div>
                                </div> 

                            </div>
                            <hr>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover w-100" id="TablaNotaCredito">
                                    <thead class="cabecera">
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Unidad Medida</th>
                                            <th>Descripcion</th>
                                            <th>Valor Unitario(*)</th>
                                            <th>Importe de Venta(**)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Op. Gravada : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtOpGravadaNC" id="txtOpGravadaNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Op. : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtOpNC" id="txtOpNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="label-texto">(*) Sin impuestos.</label>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Exonerada : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtExoneradaNC" id="txtExoneradaNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="label-texto">(**) Incluye impuestos, de ser Op. Gravada.</label>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Op. Inafecta : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtOpInafectaNC" id="txtOpInafectaNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">ISC : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtIscNC" id="txtIscNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">IGV : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtIgvNC" id="txtIgvNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="label-texto"></label>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Otros Cargos : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtOtrosCargosNC" id="txtOtrosCargosNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Otros Tributos : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtOtrosTributosNC" id="txtOtrosTributosNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Monto de Redondeo : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtMontoRedondeoNC" id="txtMontoRedondeoNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <label for="" class="label-texto">Importe Total : </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="caja-texto text-right" name="txtImporteTotalNC" id="txtImporteTotalNC" value="$ 0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row d-flex justify-content-center">
                                <div class="col-md-4 text-center mt-1">
                                    <button class="btn btn-registro-success" id="btnEmitirNC" name="btnEmitirNC"><i class="fas fa-save"></i> Emitir</button>
                                    <button class="btn btn-registro-danger" id="btnCancelarNC" name="btnLimpiarCV"><i class="fas fa-sync-alt"></i> Cancelar</button>
                                </div>
                            </div>
                            <br>                    
                            <div class="col-md-12 table-responsive scroll-table" id="TablaComprobantesEmitidosNC" style="display: none">                        
                                <label for="" class="label-texto">COMPROBANTE EMITIDO</label>
                                <table class="table table-striped table-bordered table-hover w-100" id="TablaComprobanteImpresoNC">
                                    <thead class="cabecera">
                                            <tr>
                                                <th>Fecha Emisi&oacute;n</th>
                                                <th>Serie</th>
                                                <th>N&uacute;mero</th>
                                                <th>Cliente</th>
                                                <th>Propiedad</th>
                                                <th>Total Pagado</th>
                                                <th>Comprobante</th>
                                            </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>