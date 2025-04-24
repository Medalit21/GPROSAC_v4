<div class="modal-dialog modal-sm modal-dialog-centered justify-content-center" role="document" style="width: 1300px;">

    <div class="modal-content" style="width: 1300px;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fa fa-list" aria-hidden="true"></i> Adenda de Contrato</span>
        </div>
        <div class="head-model cabecera-modal-accion"><br>
        </div>
        <div class="modal-body" style="width: 900px;">

        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Registro Adenda</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Pagos Devueltos</span></a> </li>
            </ul>
            <input type="hidden" id="txtidPago">
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="home" role="tabpanel"><br>
                    <div class="cabecera-modal-accion-2">
                        <button class="btn btn-registro-success" id="btnGuardarAdenda"><i class="fa fa-save"></i> Guardar</button>
                        <button class="btn btn-registro" id="btnActualizarAdenda"><i class="fa fa-save"></i> Actualizar</button>
                        <button class="btn btn-registro" id="btnNuevaAdenda"><i class="fas fa-sync-alt"></i> Nuevo</button>
                    </div>
                    <label for="" class="label-texto">DATOS DE LA VENTA</label>

                    <div class="col-md-12 form-row separador">   
                        <input type="hidden"  name="txtIDVENTA_" id="txtIDVENTA_" readonly>
                        <input type="hidden"  name="txtIDADENDA_" id="txtIDADENDA_" readonly>               
                        <div class="col-md-4">
                            <label for="" class="label-texto">Cliente</label>
                            <input type="text"  name="txtClienteLote" id="txtClienteLote" class="caja-texto" readonly>
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Fecha Venta</label>
                            <input type="date"  name="txtFechaVentaLote" id="txtFechaVentaLote" class="caja-texto" readonly>
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Propiedad</label>
                            <input type="text"  name="txtNombreLote" id="txtNombreLote" class="caja-texto" readonly>
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Nro Letras</label>
                            <input type="text"  name="txtNroLetras" id="txtNroLetras" class="caja-texto" readonly>
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Monto Venta</label>
                            <input type="text"  name="txtMontoVentaLote" id="txtMontoVentaLote" class="caja-texto" readonly>
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Monto Pagado</label>
                            <input type="text"  name="txtMontoPagadoLote" id="txtMontoPagadoLote" class="caja-texto" readonly>
                        </div>
                        
                    </div>
                    <div class="col-md-12 form-row separador">
                        <div class="col-md-3">
                            <label for="" class="label-texto">Fecha Registro Devoluci&oacute;n</label>
                            <input type="date" id="txtFechaRegDevolucion" class="caja-texto">
                        </div>     
                    </div>
                    <br>
                    <!-- CAMPOS DE SELECCION EN TABLA -->
                    <div class="form-row separador">
                        <label for="" class="label-texto">DATOS DE ADENDA</label>
                        
                        <div class="col-md-12 row">
                            <div class="col-md">
                                <label for="" class="label-texto">Contrato</label>
                                <input type="text" id="txtContrato" class="caja-texto">
                            </div>       
                                                    
                            <div class="col-md">
                                <label for="" class="label-texto">Nro Adenda</label>
                                <input type="text" id="txtNroAdenda" class="caja-texto">                            
                            </div>

                            <div class="col-md">
                                <label for="" class="label-texto">Estado Adenda</label>
                                <select id="bxEstadoAdenda" class="cbx-texto" disabled>
                                    <?php
                                    $EstadoAdenda = new ControllerCategorias();
                                    $verEstadoAdenda = $EstadoAdenda->VerEstadoAdenda();
                                    foreach ($verEstadoAdenda as $TEstadoAdenda) {
                                    ?>
                                    <option value="<?php echo $TEstadoAdenda['ID']; ?>"><?php echo $TEstadoAdenda['Nombre']; ?></option>
                                    <?php }  ?>
                                </select>                            
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Tipo</label>
                                <select id="bxTipoAdenda" class="cbx-texto">
                                    <?php
                                    $TiposAdenda = new ControllerCategorias();
                                    $verTiposAdenda = $TiposAdenda->VerTiposAdenda();
                                    foreach ($verTiposAdenda as $TTiposAdenda) {
                                    ?>
                                    <option value="<?php echo $TTiposAdenda['ID']; ?>"><?php echo $TTiposAdenda['Nombre']; ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                            
                        </div>                        

                        <div class="col-md-12 row">  
                            <div class="col-md">
                                <label for="" class="label-texto">Importe Solicitado</label>
                                <input type="number" id="txtImporteSolicitado" class="caja-texto">   
                            </div>

                            <div class="col-md">
                                <label for="" class="label-texto">Fecha de Inicio</label>
                                <input type="date" id="txtFechaInicio" class="caja-texto">   
                            </div>
                            
                            <div class="col-md">
                                <label for="" class="label-texto">Fecha de Termino</label>
                                <input type="date" id="txtFechaTermino" class="caja-texto">   
                            </div>  
                            
                            <div class="col-md">
                                <label for="" class="label-texto">Duraci&oacute;n(meses)</label>
                                <input type="number" class="caja-texto" name="txtDuracion" id="txtDuracion">
                            </div>
                            
                        </div> 

                        <div class="col-md-12 row">
                            <div class="col-md" hidden>
                                <label for="" class="label-texto">Referencia</label>
                                <input type="text" class="caja-texto" name="txtReferencia" id="txtReferencia" value="Referencia">
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Justificaci&oacute;n</label>
                                <input type="text" class="caja-texto" name="txtJustificacion" id="txtJustificacion">
                            </div>
                        </div> 

                        <div class="col-md-12 row">                    
                            <div class="col-md">
                                <label for="" class="label-texto">Observaci&oacute;n</label>
                                <input type="text" class="caja-texto" name="txtObservacion" id="txtObservacion" accept=".jpg">
                            </div>                        
                            <div class="col-md">
                                <label for="" class="label-texto">Constancia</label>
                                <input type="file" class="caja-texto" name="fichero" id="fichero" accept=".pdf">
                            </div>
                        </div> 
                    </div>
                    <!-- FIN CAMPOS DE SELECCION EN TABLA -->
                    <br>
                    <div id="PagosRealizados">
                        <label for="" class="label-texto">ADENDAS DE CONTRATO</label>
                        <div class="form-row">     
                            <div class="col-md-12">
                                <div class="table-responsive tamanio-tabla">
                                    <table class="table table-striped table-bordered w-100" cellspacing="0"
                                        id="TablaAdendasContratos">
                                        <thead class="cabecera">
                                            <tr>
                                                <th></th>
                                                <th>Contrato</th>
                                                <th>Nro Adenda</th>
                                                <th>Tipo</th>
                                                <th>Importe Solicitado</th>
                                                <th>Fecha Inicio</th>
                                                <th>Duraci&oacute;n</th>
                                                <th>Fecha Termino</th>
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
                <div class="tab-pane  p-20" id="profile" role="tabpanel"><br>

                    <label for="" class="label-texto">SELECCI&Oacute;N DE NOTA DE CR&Eacute;DITO EMITIDA</label>
                    <div class="col-md-12 row">
                        <div class="col-md-5">
                            <label for="" class="label-texto">Listado NC</label>
                            <select id="cbxNotasCreditoList" class="cbx-texto" style="width: 100%;">
                                <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            </select>                            
                        </div>
                        <div class="col-md-7 bajar-15">                          
                            <button class="btn btn-registro-danger" id="btnVerNC" disabled><i class="fas fa-file-pdf"></i> Ver N.C.</button>
                            <button class="btn btn-registro-success" id="btnAgregarPagoNC" disabled><i class="fas fa-plus"></i> Agregar</button>
                            <button class="btn btn-registro" id="btnNuevoPagoNC"><i class="fas fa-sync-alt"></i> Nuevo</button>
                            <button class="btn btn-registro-primary" id="btnRecargarTabNC"><i class="fas fa-sync-alt"></i> Recargar</button>
                        </div>

                    </div>
                    <br>
                    <div id="PagosRealizados">
                        <label for="" class="label-texto">PAGOS DEVUELTOS</label><br>
                        <div class="form-row">     
                            <div class="col-md-12">
                                <div class="table-responsive tamanio-tabla">
                                    <table class="table table-striped table-bordered w-100" cellspacing="0"
                                        id="TablaPagosDevueltosReporte" style="display: none;">
                                        <thead class="cabecera">
                                            <tr>
                                                <th>Lote</th>
                                                <th>Letra</th>
                                                <th>Serie</th>
                                                <th>N&uacute;mero</th>
                                                <th>Impr. N.C.</th>
                                                <th>Devuelto</th>
                                                <th>Moneda</th>
                                                <th>Serie Ref</th>
                                                <th>Num. Ref</th>
                                                <th>Tipo</th>
                                                <th>Impr. Doc.</th>
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">
                                        </tbody>
                                    </table>
                                    <div style="margin: 36px;"> </div>
                                    <table class="table table-striped table-bordered table-hover w-100"
                                        id="TablaPagosDevueltos">
                                        <thead class="cabecera">
                                            <tr>
                                                <th></th>
                                                <th>Lote</th>
                                                <th>Letra</th>
                                                <th>Serie</th>
                                                <th>N&uacute;mero</th>
                                                <th>Impr. N.C.</th>
                                                <th>Devuelto</th>
                                                <th>Moneda</th>
                                                <th>Serie Ref</th>
                                                <th>Num. Ref</th>
                                                <th>Tipo</th>
                                                <th>Impr. Doc.</th>
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
    </div>
</div>