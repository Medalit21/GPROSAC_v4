  <!-- accoridan part -->
  <div class="accordion" id="accordionExample">
      <div class="card m-b-0">
          <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                  <a data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapsethree">
                      <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                      <span>NOTA DE CRÉDITO</span>
                  </a>                  
              </h5>
          </div>
          <div id="collapsethree" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">

                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-6">
                              <input type="text" class="caja-texto" name="" id="" value="G-PROSAC S.A.C">
                          </div>
                          <div class="col-md-3"></div>
                          <div class="col-md-3">
                              <label for="" class="label-texto">BOLETA DE VENTA ELECTRONICA</label>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <input type="text" class="caja-texto" name="" id="" value="CAL. LAS CAMELIAS 585">
                          </div>
                          <div class="col-md-3"></div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="" id="" value="RUC: 20600719280">
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-6">
                              <input type="text" class="caja-texto" name="" id="" value="SAN ISIDRO - LIMA - LIMA">
                          </div>
                          <div class="col-md-3"></div>
                          <div class="col-md-3">
                              <input type="text" class="caja-texto" name="" id="" value="EB01-1083">
                          </div>
                      </div>
                      <div class="row">
                            <div class="col-md-2">
                                <label for="">Fecha de Emisión : </label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="caja-texto" name="" id="" value="07/03/2022">
                            </div>
                            <div class="col-md-8"></div>
                      </div>
                      <div class="row">
                            <div class="col-md-4">
                                <label for="">Documento que modifica:</label>
                            </div>
                            <div class="col-md-6"></div>
                      </div>
                  </div>
                  <hr>
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Boleta de Venta Electrónica</label>
                          </div>
                          <div class="col-md-2">
                              <input type="text" class="caja-texto" name="" id="" value="EB01 - 614">
                          </div>
                          <div class="col-md-4"></div>
                          <div class="col-md-4">
                              <label for="" class="label-texto">ANULACIÓN DE LA OPERACIÓN</label>
                          </div>                          
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">DNI</label>
                          </div>
                          <div class="col-md-2">
                              <input type="text" class="caja-texto" name="" id="" value="71130635">
                          </div>
                          <div class="col-md-2">
                              <label for="" class="label-texto">Señor(es)</label>
                          </div>
                          <div class="col-md-6">
                              <input type="text" class="caja-texto" name="" id="" value="KAREN LIZBETH ARANA GIRAL">
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-2">
                              <label for="" class="label-texto">Tipo de Moneda</label>
                          </div>
                          <div class="col-md-2">
                              <select id="cbxFiltroBancoCV" class="cbx-texto">
                                  <option selected="true" value="">TODOS
                              </select>
                          </div>

                          <div class="col-md-2">
                              <label for="" class="label-texto">Motivo o Sustento</label>
                          </div>
                          <div class="col-md-6">
                              <input type="text" class="caja-texto" name="" id="" value="ANULACION DE LA OPERACION">
                          </div>
                      </div>

                  </div>
                  <hr>
                  <div class="col-md-12">
                      <table class="table table-striped table-bordered table-hover w-100" id="TablaPagoComprobante">
                          <thead class="cabecera">
                                <tr>
                                    <th></th>
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
                            <div class="col-md-3">
                                <label for="" class="label-texto">Op. Gravada : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">Op. Exonerada: </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="label-texto">(*) Sin impuestos.</label>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">Op. Inafecta : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="label-texto">(**) Incluye impuestos, de ser Op. Gravada.</label>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">ISC: </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">IGV : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">Otros Cargos : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">Otros Tributos : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">Monto de Redondeo : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="label-texto">Importe Total : </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="caja-texto" name="" id="">
                            </div>
                        </div>
                  </div>
                  <hr>
                  <div class="form-row d-flex justify-content-center">
                        <div class="col-md-4 text-center mt-1">
                            <button class="btn btn-registro-success" id="btnBuscarRegistroCV" name="btnBuscarRegistroCV"><i class="fas fa-save"></i> Emitir</button>
                            <button class="btn btn-registro-danger" id="btnLimpiarCV" name="btnLimpiarCV"><i class="fas fa-sync-alt"></i> Cancelar</button>
                        </div>
                    </div>
              </div>
          </div>
      </div>
  </div>