  <!-- accoridan part -->
  <div class="accordion" id="accordion2">
      <div class="card m-b-0">
          <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                  <a data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapsetwo">
                      <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                      <span>FACTURA ELECTR&Oacute;NICA</span>
                  </a>
              </h5>
          </div>
          <div id="collapsetwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion2">
              <div class="card-body">
                    <input type="hidden" name="txtSerieControlFac" id="txtSerieControlFac">
                    <input type="hidden" name="txtNumeroControlFac" id="txtNumeroControlFac">

                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Empresa :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtRazonSocialFac" id="txtRazonSocialFac" readonly>
                          </div>
                          <div class="col-md-3"></div>
                          <div class="col-md-3">
                              <label for="" class="label-texto">FACTURA ELECTR&Oacute;NICA</label>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Direcci&oacute;n :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtDireccionFac" id="txtDireccionFac" readonly>
                          </div>
                          <div class="col-md-2"></div>
                          <div class="col-md-1 text-right">
                              <label for="" class="label-texto bajar-lb">Ruc :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtRucFac" id="txtRucFac" readonly>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Ubigeo :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtLugarFac" id="txtLugarFac" readonly>
                          </div>
                          <div class="col-md-1"></div>
                          <div class="col-md-2 text-right">
                              <label for="" class="label-texto bajar-lb">Serie/N&uacute;mero :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtSerieFac" id="txtSerieFac" readonly>
                          </div>
                      </div>

                  </div>
                  <hr>
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Fecha de Vencimiento</label>
                          </div>
                          <div class="col-md-2">
                              <input type="date" class="caja-texto" name="txtFechaVencimientoFac" id="txtFechaVencimientoFac">
                          </div>

                          <div class="col-md-2">
                              <label for="" class="label-texto">Fecha de Emisi&oacute;n</label>
                          </div>
                          <div class="col-md-2">
                              <input type="date" class="caja-texto" name="txtFechaEmisionFac" id="txtFechaEmisionFac">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">RUC</label>
                          </div>
                          <div class="col-md-2">
                              <input type="text" class="caja-texto" name="txtRucClienteFac" id="txtRucClienteFac" value="" maxlength="11">
                          </div>
                          <div class="col-md-2">
                              <button class="btn btn-registro" id="btnBuscarDocFac" name="btnBuscarDocFac"><i class="fas fa-search"></i> Buscar</button>
                          </div>
                      </div>
                      <div class="row" style="margin-top: 4px;">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Cliente</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtClienteFac" id="txtClienteFac" value="">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Direcci&oacute;n</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtDireccionClienteFac" id="txtDireccionClienteFac" value="">
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Tipo de Moneda</label>
                          </div>
                          <div class="col-md-2">
                              <select id="cbxTipoMonedaFac" class="cbx-texto">
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
                              <label for="" class="label-texto">Observaci&oacute;n</label>
                          </div>
                          <div class="col-md-6">
                              <input type="text" class="caja-texto" name="" id="">
                          </div>
                      </div>

                  </div>
                  <br>
                    <div class="col-md-12 row" id="PanelTotCronogramaFac">
                        <div class="col-md-7"><br></div>
                        <div class="col-md-5 row">                            
                            <div class="col-md-6 row" style="text-align: center !important;">
                                <label for="" class="ref-totales">Total Capital: </label>
                                <label for="" class="texto-totales" id="lbl_tot_cap_f"> 0.00</label>
                            </div>
                            <div class="col-md-6 row" style="text-align: center !important;">
                                <label for="" class="ref-totales">Total Intereses: </label>
                                <label for="" class="texto-totales" id="lbl_tot_int_f"> 0.00</label>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12">
                      <table class="table table-striped table-bordered table-hover w-100" id="TablaPagoComprobanteFac">
                          <thead class="cabecera">
                              <tr>
                                  <th></th>
                                  <th>Cantidad</th>
                                  <th>Medida</th>
                                  <th>Descripci&oacute;n</th>
                                  <th>Valor Unitario(*)</th>
                                  <th>Descuento(*)</th>
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
                              <input type="text" class="caja-texto text-right" name="txtOpGravadaFac" id="txtOpGravadaFac" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Op. : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOpFac" id="txtOpFac" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtExoneradaFac" id="txtExoneradaFac" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtOpInafectaFac" id="txtOpInafectaFac" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">ISC : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtIscFac" id="txtIscFac" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">IGV : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtIgvFac" id="txtIgvFac" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtOtrosCargosFac" id="txtOtrosCargosFac" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Otros Tributos : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOtrosTributosFac" id="txtOtrosTributosFac" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Monto de Redondeo : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtMontoRedondeoFac" id="txtMontoRedondeoFac" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Importe Total : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtImporteTotalFac" id="txtImporteTotalFac" value="$ 0.00">
                          </div>
                      </div>
                  </div>
                  <hr>
                  <div class="form-row d-flex justify-content-center">
                      <div class="col-md-4 text-center mt-1" id="botonesAccionFac">
                          <button class="btn btn-registro-success" id="btnEmitirFactura" name="btnEmitirFactura"><i class="fas fa-save"></i> Emitir</button>
                          <button class="btn btn-registro-danger" id="btnCancelarFactura" name="btnCancelarFactura"><i class="fas fa-sync-alt"></i> Cancelar</button>
                      </div>
                  </div>
                  <br>                    
                    <div class="col-md-12 table-responsive scroll-table" id="TablaComprobantesEmitidosFac" style="display: none">                        
                        <label for="" class="label-texto">COMPROBANTE EMITIDO</label>
                        <table class="table table-striped table-bordered table-hover w-100" id="TablaComprobanteImpresoFac">
                            <thead class="cabecera">
                                    <tr>
                                        <th>Fecha Emisi&oacute;n</th>
                                        <th>Serie</th>
                                        <th>Numero</th>
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