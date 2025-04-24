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
                    <input type="hidden" name="txtSerieControlFacOC" id="txtSerieControlFacOC">
                    <input type="hidden" name="txtNumeroControlFacOC" id="txtNumeroControlFacOC">

                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Empresa :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtRazonSocialFacOC" id="txtRazonSocialFacOC" readonly>
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
                              <input type="text" class="caja-texto" name="txtDireccionFacOC" id="txtDireccionFacOC" readonly>
                          </div>
                          <div class="col-md-2"></div>
                          <div class="col-md-1 text-right">
                              <label for="" class="label-texto bajar-lb">Ruc :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtRucFacOC" id="txtRucFacOC" readonly>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-1 text-left">
                              <label for="" class="label-texto bajar-lb">Ubigeo :</label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="caja-texto" name="txtLugarFacOC" id="txtLugarFacOC" readonly>
                          </div>
                          <div class="col-md-1"></div>
                          <div class="col-md-2 text-right">
                              <label for="" class="label-texto bajar-lb">Serie/N&uacute;mero :</label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="txtSerieFacOC" id="txtSerieFacOC" readonly>
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
                              <input type="date" class="caja-texto" name="txtFechaVencimientoFacOC" id="txtFechaVencimientoFacOC">
                          </div>

                          <div class="col-md-2">
                              <label for="" class="label-texto">Fecha de Emisi&oacute;n</label>
                          </div>
                          <div class="col-md-2">
                              <input type="date" class="caja-texto" name="txtFechaEmisionFacOC" id="txtFechaEmisionFacOC">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">RUC</label>
                          </div>
                          <div class="col-md-2">
                              <input type="text" class="caja-texto" name="txtRucClienteFacOC" id="txtRucClienteFacOC" value="" maxlength="11" readonly>
                          </div>
                          <div class="col-md-2" hidden>
                              <button class="btn btn-registro" id="btnBuscarDocFacOC" name="btnBuscarDocFacOC"><i class="fas fa-search"></i> Buscar</button>
                          </div>
                      </div>
                      <div class="row" style="margin-top: 4px;">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Cliente</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtClienteFacOC" id="txtClienteFacOC" value="" readonly>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Direcci&oacute;n</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtDireccionClienteFacOC" id="txtDireccionClienteFacOC" value="">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Email</label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="caja-texto" name="txtEmailFacOC" id="txtEmailFacOC" value="">
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Tipo de Moneda</label>
                          </div>
                          <div class="col-md-2">
                              <select id="cbxTipoMonedaFacOC" class="cbx-texto">
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
                              <input type="text" class="caja-texto" name="txtObservacionFacOC" id="txtObservacionFacOC">
                          </div>
                      </div>

                  </div>
                  <hr>
                  <div>
                    <fieldset>
                      <legend>Ingresar valores - Detalle Comprobante</legend>
                      <div class="form-row">
                          <div class="col-md-1">
                              <label class="label-texto">Cantidad</label>
                              <input type="number" class="caja-texto text-center" name="txtCamCantidadFacOC" id="txtCamCantidadFacOC" placeholder="1">
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Unidad</label>
                              <input type="text" class="caja-texto text-center" name="txtCamUnidadFacOC" id="txtCamUnidadFacOC" placeholder="UNIDAD" readonly>
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Descripci&oacute;n</label>
                              <input type="text" class="caja-texto" name="txtCamDescripcionFacOC" id="txtCamDescripcionFacOC" placeholder="Escribir Aqui">
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Importe Total</label>
                              <input type="hidden" name="txtIdReservaValFac" id="txtIdReservaValFac">
                              <input type="number" class="caja-texto text-center" name="txtCamValorUnitFacOC" id="txtCamValorUnitFacOC">
                          </div>

                          <div class="col-md-2">
                              <label class="label-texto">Descuento</label>
                              <input type="number" class="caja-texto text-center" name="txtCamDescFacOC" id="txtCamDescFacOC">
                          </div>
                          <div class="col-md-1" id="PanelInafectoFac" style="display: none">
                              <label class="label-texto">Inafecto</label>
                              <select id="cbxInafectoFacOC" class="cbx-texto">
                                    <option value="1">SI</option>
                                    <option value="2">NO</option>
                                </select>
                          </div>

                          <div class="col-md-2" style=" margin-top: 12px;">
                              <button class="btn btn-registro" id="btnAgregarDetalleComFacOC" name="btnAgregarDetalleComFacOC"><i class="fas fa-plus-square"></i> Agregar</button>
                          </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-12">
                      <table class="table table-striped table-bordered table-hover w-100" id="TablaPagoComprobanteFacOC">
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
                              <input type="text" class="caja-texto text-right" name="txtOpGravadaFacOC" id="txtOpGravadaFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Op. : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOpFacOC" id="txtOpFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label for="" class="label-texto"></label>
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Exonerada : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtExoneradaFacOC" id="txtExoneradaFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label for="" class="label-texto"></label>
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Op. Inafecta : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOpInafectaFacOC" id="txtOpInafectaFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">ISC : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtIscFacOC" id="txtIscFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">IGV : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtIgvFacOC" id="txtIgvFacOC" value="$ 0.00">
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
                              <input type="text" class="caja-texto text-right" name="txtOtrosCargosFacOC" id="txtOtrosCargosFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Otros Tributos : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtOtrosTributosFacOC" id="txtOtrosTributosFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Monto de Redondeo : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtMontoRedondeoFacOC" id="txtMontoRedondeoFacOC" value="$ 0.00">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                          </div>
                          <div class="col-md-3 text-right">
                              <label for="" class="label-texto">Importe Total : </label>
                          </div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto text-right" name="txtImporteTotalFacOC" id="txtImporteTotalFacOC" value="$ 0.00">
                          </div>
                      </div>
                  </div>
                  <hr>
                  <div class="form-row d-flex justify-content-center">
                      <div class="col-md-4 text-center mt-1" id="botonesAccionFacOC">
                          <button class="btn btn-registro-success" id="btnEmitirFacturaOC" name="btnEmitirFacturaOC"><i class="fas fa-save"></i> Emitir</button>
                          <button class="btn btn-registro-danger" id="btnCancelarFacturaOC" name="btnCancelarFacturaOC"><i class="fas fa-sync-alt"></i> Cancelar</button>
                      </div>
                  </div>
                  <br>                    
                    <div class="col-md-12 table-responsive scroll-table" id="TablaComprobantesEmitidosFacOC" style="display: none">                        
                        <label for="" class="label-texto">COMPROBANTE EMITIDO</label>
                        <table class="table table-striped table-bordered table-hover w-100" id="TablaComprobanteImpresoFacOC">
                            <thead class="cabecera">
                                    <tr>
                                        <th>Fecha Emisi&oacute;n</th>
                                        <th>Serie</th>
                                        <th>Numero</th>
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