<div class="modal-dialog modal-sm modal-dialog-centered justify-content-center" role="document" style="width: 1300px;">

    <div class="modal-content" style="width: 1300px;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fa fa-list" aria-hidden="true"></i> Validación de Pagos</span>
        </div>
        <div class="head-model cabecera-modal-accion" hidden>
            <button class="btn btn-registro-success" id="btnGuardarTarea"><i class="fa fa-save"></i> Guardar</button>
        </div>
        <div class="modal-body" style="width: 900px;">
            <input type="hidden" id="txtidPago">
            <label for="" class="label-texto">DATOS DEL CLIENTE</label>
            <div class="form-row separador">                
                <div class="col">
                    <label for="" class="label-texto">Apellidos y Nombres</label>
                    <input type="text"  name="txtApellidoNombre" id="txtApellidoNombre" class="caja-texto" disabled>
                </div>
                <div class="col-2">
                    <label for="" class="label-texto">Teléfono/Celular</label>
                    <input type="text"  name="txtTelefono" id="txtTelefono" class="caja-texto" disabled>
                </div>
                <div class="col">
                    <label for="" class="label-texto">Correo</label>
                    <input type="text"  name="txtCorreo" id="txtCorreo" class="caja-texto" disabled>
                </div>
                <div class="col-2">
                    <label for="" class="label-texto">Lote </label>
                     <input type="text"  name="txtLote" id="txtLote" class="caja-texto" disabled>
                </div>
            </div>
            <br>
            
            <div id="PagosRealizados">
                <label for="" class="label-texto">PAGOS REALIZADOS (POR VALIDAR)</label>
                <div class="form-row">     
                    <div class="col-md-12">
                        <div class="table-responsive tamanio-tabla">
                            <table class="table table-striped table-bordered w-100" cellspacing="0"
                                id="TablaPagosRealizadosCobros">
                                <thead class="cabecera">
                                    <tr>
                                        <th></th>
                                        <th>Fecha</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Boleta</th>
                                        <th>Tipo Moneda</th>
                                        <th>Tipo Cambio</th>
                                        <th>Monto Pagado</th>
                                        <th>Medio Pago</th>
                                        <th>Nro Operacions</th>
                                        <th>Agencia Bancaria</th>
                                        <th>Ajunto</th>
                                    </tr>
                                </thead>
                                <tbody class="control-detalle">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <!-- CAMPOS DE SELECCION EN TABLA -->
                <div class="form-row separador">
                    <label for="" class="label-texto">ACTUALIZAR DATOS DE PAGO <small>(Seleccione uno de los registros en la tabla "Pagos Realizados" <i class="fas fa-pencil-alt"></i> )</small> </label>
                    <input type="hidden" class="caja-texto" name="txtID_PAGO" id="txtID_PAGO">
                    <div class="col-md-12">
                        <fieldset>
                        <div class="col-md-12 row">
                            <div class="col-md">
                                <label for="" class="label-texto">Fecha Pago</label>
                                <input type="date" id="txtFechaPagoDetalle" class="caja-texto">
                            </div>       
                                                    
                            <div class="col-md">
                                <label for="" class="label-texto">Tipo de Comprobante</label>
                                <select id="bxTipoComprobanteDetalle" class="cbx-texto">
                                    <option value="" selected="true" disabled="disabled">Seleccionar..</option>

                                    <?php
                                    $TipoComprobanteVenta = new ControllerCategorias();
                                    $verTipoComprobanteVenta = $TipoComprobanteVenta->VerTipoComprobanteVentas();
                                    foreach ($verTipoComprobanteVenta as $TTipoComprobanteVenta) {
                                    ?>
                                    <option value="<?php echo $TTipoComprobanteVenta['ID']; ?>"><?php echo $TTipoComprobanteVenta['Nombre']; ?></option>
                                    <?php }  ?>
                                </select>
                                    
                            </div>
                            <div class="col-md" hidden>
                                <label for="" class="label-texto">Serie</label>
                                <input type="text" class="caja-texto" name="txtSerieBoletaDetalle" id="txtSerieBoletaDetalle">                              
                            </div>
                            <div class="col-md" hidden>
                                <label for="" class="label-texto">Número</label>
                                <input type="text" class="caja-texto" name="txtNumeroBoletaDetalle" id="txtNumeroBoletaDetalle">
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Agencia Bancaria</label>
                                <select id="bxAgenciaBancariaDetalle" class="cbx-texto">
                                    <option value="" selected="true" disabled="disabled">Seleccionar..</option>
									
                                    <?php
                                    $Bancos = new ControllerCategorias();
                                    $verBancos = $Bancos->VerBancos();
                                    foreach ($verBancos as $TBancos) {
                                    ?>
                                    <option value="<?php echo $TBancos['ID']; ?>"><?php echo $TBancos['Nombre']; ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Tipo de Moneda</label>
                                <select id="bxTipoMonedaDetalle" class="cbx-texto">
                                    <option value="" selected="true" disabled="disabled">Seleccionar..</option>
                                    <?php
                                    $tipoMod = new ControllerCategorias();
                                    $vertipoMod = $tipoMod->VerTipoMoneda();
                                    foreach ($vertipoMod as $TtipoMod) {
                                    ?>
                                    <option value="<?php echo $TtipoMod['ID']; ?>"><?php echo $TtipoMod['Nombre']; ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
        
                        <div class="col-md-12 row">  
                            
                            <div class="col-md">
                                <label for="" class="label-texto">Tipo de Cambio</label>
                                <input type="number" class="caja-texto" name="txtTipoCambioDetalle" id="txtTipoCambioDetalle">
                                  
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Medio de Pago</label>
                                <select id="bxMedioPagoDetalle" class="cbx-texto">
                                    <option value="" selected="true" disabled="disabled">Seleccionar..</option>
									
                                    <?php
                                    $MedioPago = new ControllerCategorias();
                                    $verMedioPago = $MedioPago->VerMedioPago();
                                    foreach ($verMedioPago as $TMedioPago) {
                                    ?>
                                    <option value="<?php echo $TMedioPago['ID']; ?>"><?php echo $TMedioPago['Nombre']; ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Importe Pagado</label>
                                <input type="text" class="caja-texto" name="txtImportePagadoDetalle" id="txtImportePagadoDetalle">
                            </div>
                            <div class="col-md">
                                <label for="" class="label-texto">Nro Operacion</label>
                                <input type="number" class="caja-texto" name="txtNroOperacionDetalle" id="txtNroOperacionDetalle">
                            </div> 
                            
                        </div> 
                        <div class="col-md-12 row" hidden>                         
                            <div class="col-md-6">
                                <label for="" class="label-texto">Constancia</label>
                                <input type="file" class="caja-texto" name="fichero" id="fichero" accept=".jpg">
                            </div>
                        </div> 
                    </fieldset>
                    </div>
                </div>
                <br>
                <div class="form-row d-flex justify-content-center">
                    <div class="col-md-6 text-center mt-1">
                         <button id="btnGuardarVerificacion" type="button" class="btn btn-registro-success"><i class="fas fa-save"></i> Guardar</button>
                        <button id="btnContinuarVerificacion" type="button" class="btn btn-registro-primary"><i class="fas fa-arrow-circle-right"></i> Continuar</button>
                        <button id="btnRechazarVerificacion" type="button" class="btn btn-registro-danger" hidden><i class="fas fa-times-circle"></i> Rechazar</button>
                   </div>
                </div>
                 <!-- FIN CAMPOS DE SELECCION EN TABLA -->
                <br>
            </div>
           
            
             <!-- CAMPOS DE CONFORMIDAD DE PAGO -->
            <div id="ConformidadPagos">
                <div class="form-row separador">
                    <input type="hidden" id="txtidcronograma">
                    <div class="col-md">
                        <label for="" class="label-texto">Letra <small>(LETRA - FECHA VENCIMIENTO)</small> </label>
                        <select id="bxFiltroLetraCobros" class="cbx-texto" disabled>
                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="" class="label-texto">Fecha Vencimiento</label>
                        <input type="date" id="txtFechaVencimientoLetra" class="caja-texto" readonly>
                    </div>
    
                    <div class="col">
                        <label for="" class="label-texto">Tipo Moneda</label>
                        <select id="bxTipoMonedaLetra" class="cbx-texto" disabled>
                            <option selected="true" disabled="disabled">Seleccionar..</option>
                            <?php
                            $tipoMod = new ControllerCategorias();
                            $vertipoMod = $tipoMod->VerTipoMoneda();
                            foreach ($vertipoMod as $TtipoMod) {
                            ?>
                            <option value="<?php echo $TtipoMod['ID']; ?>"><?php echo $TtipoMod['Nombre']; ?></option>
                            <?php }  ?>
                        </select>
                    </div>
    
                    <div class="col">
                        <label for="" class="label-texto">Monto a Pagar</label>
                        <input type="text" class="caja-texto" name="txtMontoLetra" id="txtMontoLetra" readonly>
                    </div>
                   
                </div>
                <br>
                <div class="form-row">
                    <label for="" class="label-texto">PAGOS REALIZADOS (POR VALIDAR)</label>     
                    <div class="col-md-12">
                        <div class="table-responsive tamanio-tabla">
                            <table class="table table-striped table-bordered w-100" cellspacing="0"
                                id="TablaPagosRealizadosCobros2">
                                <thead class="cabecera">
                                    <tr>
                                        <th></th>
                                        <th>Fecha</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Tipo Moneda</th>
                                        <th>Tipo Cambio</th>
                                        <th>Monto Pagado</th>
                                        <th>Total</th>
                                        <th>Medio Pago</th>
                                        <th>Nro Operacion</th>
                                        <th>Agencia Bancaria</th>
                                        <th>Ajunto</th>
                                    </tr>
                                </thead>
                                <tbody class="control-detalle">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-row separador">
                    <label for="" class="label-texto">DATOS DE PAGO SELECCIONADO PARA VALIDACIÓN <small>(Seleccione uno de los registros en la tabla "Pagos Realizados" <i class="fas fa-pencil-alt"></i> )</small> </label>
                    <input type="hidden" class="caja-texto" name="txtID_PAGO2" id="txtID_PAGO2" readonly>
                    <br>
                    <div class="col-md-12 row">
                        <div class="col-md">
                            <label for="" class="label-texto">Fecha Pago</label>
                            <input type="date" id="txtFechaPagoDetalle2" class="caja-texto" readonly>
                        </div>       
                                                
                        <div class="col-md">
                            <label for="" class="label-texto">Tipo de Comprobante</label>
                            <select id="bxTipoComprobanteDetalle2" class="cbx-texto" disabled>
                                <option value="" selected="true" disabled="disabled">Seleccionar..</option>
                                <?php
                                $TipoComprobanteVenta = new ControllerCategorias();
                                $verTipoComprobanteVenta = $TipoComprobanteVenta->VerTipoComprobanteVentas();
                                foreach ($verTipoComprobanteVenta as $TTipoComprobanteVenta) {
                                ?>
                                <option value="<?php echo $TTipoComprobanteVenta['ID']; ?>"><?php echo $TTipoComprobanteVenta['Nombre']; ?></option>
                                <?php }  ?>
                            </select>
                                
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Tipo Moneda</label>
                            <select id="bxTipoMonedaDetalle2" class="cbx-texto" disabled>
                                <option value="" selected="true" disabled="disabled">Seleccionar..</option>
                                <?php
                                $tipoMod = new ControllerCategorias();
                                $vertipoMod = $tipoMod->VerTipoMoneda();
                                foreach ($vertipoMod as $TtipoMod) {
                                ?>
                                <option value="<?php echo $TtipoMod['ID']; ?>"><?php echo $TtipoMod['Nombre']; ?></option>
                                <?php }  ?>
                            </select>                              
                        </div>
                        <div class="col-md">
                            <label for="" class="label-texto">Tipo Cambio</label>
                            <input type="text" class="caja-texto" name="txtTipoCambioDetalle2" id="txtTipoCambioDetalle2" readonly>
                        </div>
                        
                        <div class="col-md">
                            <label for="" class="label-texto">Monto Pagado</label>
                            <input type="text" class="caja-texto" name="txtMontoPagado2" id="txtMontoPagado2" readonly>
                        </div>
                        
                        <div class="col-md">
                            <label for="" class="label-texto">Total</label>
                            <input type="text" class="caja-texto total_pagado" name="txtImportePagadoDetalle2" id="txtImportePagadoDetalle2" readonly>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-row d-flex justify-content-center">
                    <div class="col-md-6 text-center mt-1">
                        <button id="btnAtras" type="button" class="btn btn-registro-primary"><i class="fas fa-arrow-circle-left"></i> Atras</button>
                        <button id="btnAprobar" type="button" class="btn btn-registro"><i class="fas fa-check"></i> Aprobar</button>                        
                        <button id="btnRechazar" type="button" class="btn btn-registro-danger"><i class="fas fa-times-circle"></i> Rechazar</button>
                        <button id="btnPagosConformes" type="button" class="btn btn-registro-primary"><i class="fas fa-arrow-circle-right"></i> Pagos</button>
                   </div>
                </div>
                <br>
            </div>
            <!-- FIN CAMPOS DE CONFORMIDAD DE PAGO -->
            
            <!-- TABLA FINAL DE PAGOS VERIFICADOS -->
            <div id="PagosVerificados">
                <label for="" class="label-texto">PAGOS APROBADOS</label>                
                <div class="form-row">     
                    <div class="col-md-12">
                        <div class="table-responsive tamanio-tabla">
                            <table class="table table-striped table-bordered w-100" cellspacing="0"
                                id="TablaPagosRealizadosCobros3">
                                <thead class="cabecera">
                                    <tr>
                                        <th></th>
                                        <th>Fecha</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Boleta</th>
                                        <th>Tipo Moneda</th>
                                        <th>Tipo Cambio</th>
                                        <th>Monto Pago</th>
                                        <th>Medio Pago</th>
                                        <th>Nro Operacion</th>
                                        <th>Agencia Bancaria</th>
                                        <th>Ajunto</th>
                                    </tr>
                                </thead>
                                <tbody class="control-detalle">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-row d-flex justify-content-center">
                    <div class="col-md-6 text-center mt-1">
                        <button id="btnAtras2" type="button" class="btn btn-registro-primary"><i class="fas fa-arrow-circle-left"></i> Atras</button>
                   </div>
                </div>
                <br>
            </div>
            <!-- FIN TABLA FINAL DE PAGOS VERIFICADOS -->
        </div>
    </div>
</div>