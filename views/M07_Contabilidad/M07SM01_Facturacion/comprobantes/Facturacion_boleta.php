  <!-- accoridan part -->
  <div class="accordion" id="accordionExample">
      <div class="card m-b-0">
          <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                  <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                      <span>BOLETA ELECTR&Oacute;NICA</span>
                  </a>                  
              </h5>
          </div>
          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">
                    <input type="hidden" name="txtSerieControlBol" id="txtSerieControlBol">
                    <input type="hidden" name="txtNumeroControlBol" id="txtNumeroControlBol">
                   <div class="col-md-12">
                       <div class="row">
                            <div class="col-md-1 text-left">
                                <label for="" class="label-texto bajar-lb">Empresa :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtRazonSocial" id="txtRazonSocial" readonly>
                          </div>
                          <div class="col-md-3"></div>
                          <div class="col-md-3">
                              <label for="" class="label-texto">BOLETA DE VENTA ELECTR&Oacute;NICA</label>
                          </div>
                      </div>
                      <div class="row">
                            <div class="col-md-1 text-left">
                                <label for="" class="label-texto bajar-lb">Direcci&oacute;n :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtDireccion" id="txtDireccion" readonly>
                          </div>
                          <div class="col-md-2"></div>
                          <div class="col-md-1 text-right">
                                <label for="" class="label-texto bajar-lb">Ruc :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtRuc" id="txtRuc" readonly>
                          </div>
                      </div>

                      <div class="row">
                            <div class="col-md-1 text-left">
                                <label for="" class="label-texto bajar-lb">Ubigeo :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtLugar" id="txtLugar" readonly>
                          </div>
                          <div class="col-md-1"></div>
                          <div class="col-md-2 text-right">
                                <label for="" class="label-texto bajar-lb">Serie/N&uacute;mero :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtSerie" id="txtSerie" readonly>
                          </div>
                      </div>

                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Fecha de Vencimiento : </label>
                          </div>
                          <div class="col-md-2">
                              <input type="date" class="caja-texto" name="txtFechaVencimiento" id="txtFechaVencimiento">
                          </div>

                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Fecha de Emisi&oacute;n : </label>
                          </div>
                          <div class="col-md-2">
                              <input type="date" class="caja-texto" name="txtFechaEmision" id="txtFechaEmision">
                          </div>
                          
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Tipo de Moneda  : </label>
                            </div>
                            <div class="col-md-2">
                              <select id="cbxTipoMoneda" class="cbx-texto">
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
                              <label for="" class="label-texto bajar-lb">Tipo Documento : </label>
                            </div>
                            <div class="col-md-2">
                                <select id="cbxTipoDocumento" class="cbx-texto">
                                        <?php
                                        $TipoDocumento = new ControllerCategorias();
                                        $TipoDocumentoVer = $TipoDocumento->VerTipoDocumentoFacturacion();
                                        foreach ($TipoDocumentoVer as $TipoDocumento) {
                                        ?>
                                        <option value="<?php echo $TipoDocumento['ID']; ?>" style="font-size: 11px;">
                                                <?php echo $TipoDocumento['Nombre']; ?>
                                            </option>
                                        <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-md-1.5">
                                <label for="" class="label-texto bajar-lb">Nro Documento :</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="caja-texto" name="txtNroDocumento" id="txtNroDocumento" maxlength="20">
                            </div>
                            <div class="col-md">
                                <button class="btn btn-registro" id="btnBuscarDocumento" name="btnBuscarDocumento" style="width: 40px; margin-top: -2px; margin-left: -3px;"><i class="fas fa-search"></i></button>
                            </div>
                            
                        </div>
                        <div class="row"> 
                            <div class="col-md-2">
                                <label for="" class="label-texto bajar-lb">Cliente :</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="caja-texto" name="txtdatos" id="txtdatos" placeholder="Escribir aqui">
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-2">
                                <label for="" class="label-texto bajar-lb">Direcci&oacute;n :</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="caja-texto" name="txtDireccionCliente" id="txtDireccionCliente" placeholder="Escribir aqui">
                            </div>
                        </div>   

                    </div>
                    <br>
                    <div class="col-md-12 row" id="PanelTotCronogramaBol">
                        <div class="col-md-7"><br></div>
                        <div class="col-md-5 row">                            
                            <div class="col-md-6 row" style="text-align: center !important;">
                                <label for="" class="ref-totales">Total Capital: </label>
                                <label for="" class="texto-totales" id="lbl_tot_cap"> 0.00</label>
                            </div>
                            <div class="col-md-6 row" style="text-align: center !important;">
                                <label for="" class="ref-totales">Total Intereses: </label>
                                <label for="" class="texto-totales" id="lbl_tot_int"> 0.00</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive scroll-table">
                        <table class="table table-striped table-bordered table-hover w-100" id="TablaItemsBoleta">
                            <thead class="cabecera">
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Medida</th>
                                        <th>Descripci&oacute;n</th>
                                        <th>Tipo</th>
                                        <th>Valor Unitario(*)</th>
                                        <th>Igv</th>
                                        <th>Dscto(*)</th>
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
                                <input type="text" class="caja-texto text-right" name="txtOpGravada" id="txtOpGravada" value="$ 0.00">
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="" class="label-texto">Op. : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto text-right" name="txtOp" id="txtOp" value="$ 0.00">
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
                                <input type="text" class="caja-texto text-right" name="txtExonerada" id="txtExonerada" value="$ 0.00">
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
                                <input type="text" class="caja-texto text-right" name="txtOpInafecta" id="txtOpInafecta" value="$ 0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="" class="label-texto">ISC : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto text-right" name="txtIsc" id="txtIsc" value="$ 0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="" class="label-texto">IGV : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto text-right" name="txtIgv" id="txtIgv" value="$ 0.00">
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
                                <input type="text" class="caja-texto text-right" name="txtOtrosCargos" id="txtOtrosCargos" value="$ 0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="" class="label-texto">Otros Tributos : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto text-right" name="txtOtrosTributos" id="txtOtrosTributos" value="$ 0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="" class="label-texto">Monto de Redondeo : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto text-right" name="txtMontoRedondeo" id="txtMontoRedondeo" value="$ 0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3 text-right">
                                <label for="" class="label-texto">Importe Total : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto text-right" name="txtImporteTotal" id="txtImporteTotal" value="$ 0.00">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-4 text-center mt-1" id="botonesAccion">
                            <button class="btn btn-registro-success" id="btnEmitirBoleta" name="btnEmitirBoleta"><i class="fas fa-save"></i> Emitir</button>
                            <button class="btn btn-registro-danger" id="btnCancelarBoleta" name="btnCancelarBoleta"><i class="fas fa-sync-alt"></i> Cancelar</button>
                        </div>
                    </div>
                    <br>                    
                    <div class="col-md-12 table-responsive scroll-table" id="TablaComprobantesEmitidos" style="display: none">                        
                        <label for="" class="label-texto">COMPROBANTE EMITIDO</label>
                        <table class="table table-striped table-bordered table-hover w-100" id="TablaComprobanteImpreso">
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