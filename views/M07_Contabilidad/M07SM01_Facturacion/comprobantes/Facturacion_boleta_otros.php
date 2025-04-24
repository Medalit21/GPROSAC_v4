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
                  <input type="hidden" name="txtSerieControlBolOC" id="txtSerieControlBolOC">
                  <input type="hidden" name="txtNumeroControlBolOC" id="txtNumeroControlBolOC">
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Empresa :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtRazonSocialOC" id="txtRazonSocialOC" readonly>
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
                              <input type="text" class="caja-texto" name="txtDireccionOC" id="txtDireccionOC" readonly>
                          </div>
                          <div class="col-md-2"></div>
                          <div class="col-md-1 text-right">
                              <label for="" class="label-texto bajar-lb">Ruc :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtRucOC" id="txtRucOC" readonly>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Ubigeo :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtLugarOC" id="txtLugarOC" readonly>
                          </div>
                          <div class="col-md-1"></div>
                          <div class="col-md-2 text-right">
                              <label for="" class="label-texto bajar-lb">Serie/N&uacute;mero :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtSerieOC" id="txtSerieOC" readonly>
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
                              <input type="date" class="caja-texto" name="txtFechaVencimientoOC" id="txtFechaVencimientoOC">
                          </div>

                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Fecha de Emisi&oacute;n : </label>
                          </div>
                          <div class="col-md-2">
                              <input type="date" class="caja-texto" name="txtFechaEmisionOC" id="txtFechaEmisionOC">
                          </div>

                      </div>

                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Tipo de Moneda : </label>
                          </div>
                          <div class="col-md-2">
                              <select id="cbxTipoMonedaOC" class="cbx-texto">
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
                              <select id="cbxTipoDocOC" class="cbx-texto" disabled>
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
                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Nro Documento :</label>
                          </div>
                          <div class="col-md-2">
                              <input type="text" class="caja-texto" name="txtNroDocOC" id="txtNroDocOC" maxlength="20" readonly>
                          </div>

                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Cliente :</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtdatosOC" id="txtdatosOC" placeholder="Escribir aqui" readonly>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Direcci&oacute;n :</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtDireccionClienteOC" id="txtDireccionClienteOC" placeholder="Escribir aqui">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto bajar-lb">Email :</label>
                          </div>
                          <div class="col-md-10">
                              <input type="email" class="caja-texto" name="txtCorreoClienteOC" id="txtCorreoClienteOC" placeholder="Ejemplo: gprosac@gmail.com">
                          </div>
                      </div>

                  </div>
                  <hr>
                  <div id="contenido_campos" style="display: none">
                    <fieldset>
                      <legend>Ingresar valores - Detalle Comprobante</legend>
                      <div class="form-row">
                          <div class="col-md-1">
                              <label class="label-texto">Cantidad</label>
                              <input type="number" class="caja-texto text-center" name="txtCamCantidadOC" id="txtCamCantidadOC" placeholder="1">
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Unidad</label>
                              <input type="text" class="caja-texto text-center" name="txtCamUnidadOC" id="txtCamUnidadOC" placeholder="UNIDAD" readonly>
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Descripci&oacute;n</label>
                              <input type="text" class="caja-texto" name="txtCamDescripcionOC" id="txtCamDescripcionOC" placeholder="Escribir Aqui">
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Importe Total</label>
                              <input type="hidden" name="txtIdReservaVal" id="txtIdReservaVal">
                              <input type="number" class="caja-texto text-center" name="txtCamValorUnitOC" id="txtCamValorUnitOC">
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Descuento</label>
                              <input type="number" class="caja-texto text-center" name="txtCamDescOC" id="txtCamDescOC">
                          </div>
                          <div class="col-md-1" id="PanelInafecto" style="display: none">
                              <label class="label-texto">Inafecto</label>
                              <select id="cbxInafectoOC" class="cbx-texto">
                                    <option value="1">SI</option>
                                    <option value="2">NO</option>
                                </select>
                          </div>

                          <div class="col-md-2" style=" margin-top: 12px;">
                              <button class="btn btn-registro" id="btnAgregarDetalleComOC" name="btnAgregarDetalleComOC"><i class="fas fa-plus-square"></i> Agregar</button>
                          </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-12 table-responsive scroll-table">
                      <table class="table table-striped table-bordered table-hover w-100" id="TablaItemsBoletaOC">
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
                              <input type="text" class="caja-texto text-right" name="txtOpGravadaOC" id="txtOpGravadaOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Op. : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOpOC" id="txtOpOC" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtExoneradaOC" id="txtExoneradaOC" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtOpInafectaOC" id="txtOpInafectaOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">ISC : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtIscOC" id="txtIscOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">IGV : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtIgvOC" id="txtIgvOC" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtOtrosCargosOC" id="txtOtrosCargosOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Otros Tributos : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOtrosTributosOC" id="txtOtrosTributosOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Monto de Redondeo : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtMontoRedondeoOC" id="txtMontoRedondeoOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Importe Total : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtImporteTotalOC" id="txtImporteTotalOC" value="$ 0.00">
                          </div>
                      </div>
                  </div>
                  <hr>
                  <div class="form-row d-flex justify-content-center">
                      <div class="col-md-4 text-center mt-1" id="botonesAccionOC">
                          <button class="btn btn-registro-success" id="btnEmitirBoletaOC" name="btnEmitirBoletaOC"><i class="fas fa-save"></i> Emitir</button>
                          <button class="btn btn-registro-danger" id="btnCancelarBoletaOC" name="btnCancelarBoletaOC"><i class="fas fa-sync-alt"></i> Cancelar</button>
                      </div>
                  </div>
                  <br>
                  <div class="col-md-12 table-responsive scroll-table" id="TablaComprobantesEmitidosOC" style="display: none">
                      <label for="" class="label-texto">COMPROBANTE EMITIDO</label>
                      <table class="table table-striped table-bordered table-hover w-100" id="TablaComprobanteImpresoOC">
                          <thead class="cabecera">
                              <tr>
                                  <th>Fecha Emisi&oacute;n</th>
                                  <th>Serie</th>
                                  <th>N&uacute;mero</th>
                                  <th>Cliente</th>
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