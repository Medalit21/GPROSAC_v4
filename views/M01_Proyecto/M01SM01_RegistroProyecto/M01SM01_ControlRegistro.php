<div class="row">
    <div class="col-md-12" id="contenido_registro">
        <fieldset>
            <legend>General</legend>
            <div id="formularioRegistrarGeneral">
                <div class="form-row" style="margin-top: -8px;">

                    <div class="p-campos-3 margen-pop-up" id="panel-options">
                        <input type="hidden" id="__ID_DATOS_PERSONALES">
                        <input type="hidden" id="__ID_DATOS_LABORALES">
                        <input type="hidden" id="txtidProyectoZona">

                        <!-- SECCION DE DATOS DEL PROYECTO -->
                        <div class="form-row" style="margin-top: 10px;">

                            <div class="form-row col-12">
                                <div class="col-md">
                                    <div class="form-row" style="margin-top: -8px;">
                                        <div class="col-md">
                                            <input id="txtidProyectocc" type="hidden" class="caja-texto" >
                                            <label class="label-texto">Nombre:</label>
                                            <input id="txtNombrecc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                        </div>
                                        <div class="col-md" hidden>
                                            <label class="label-texto">Código:</label>
                                            
											<input id="txtCodigocc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
											
                                            <input id="txtCorrelativoccx" type="text" class="caja-texto" placeholder="Escriba aquí">
                                        </div>

                                        <div class="col-md">
                                            <label class="label-texto">Responsable:</label>
                                            <input id="txtResponsablecc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                        </div>
                                        <div class="col-md">
                                            <label class="label-texto">Area(m<sup>2</sup>):</label>
                                            <input id="txtAreacc" type="text" class="caja-texto">
                                        </div>
                                        <div class="col-md">
                                            <label class="label-texto">Nro. Zonas:</label>
                                            <input id="txtNroZonascc" type="number" class="caja-texto">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row col-12">
                                <div class="col-md">
                                    <label class="label-texto">Dirección:</label>
                                    <input maxlength="100" id="txtDireccioncc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                </div>
                                <div class="col-md">
                                    <label class="label-texto">Departamento <small id="cbxDepartamentoDirHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <select id="bxDepartamentoPopup" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                        <?php
                                        $departam = new ControllerCategorias();
                                        $VerDepart = $departam->VerDepartamento();
                                        foreach ($VerDepart as $depto) {
                                        ?>
                                            <option value="<?php echo $depto['ID']; ?>">
                                                <?php echo $depto['Nombre']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div class="col-md">
                                    <label class="label-texto">Provincia <small id="cbxProvinciaDirHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <select id="bxProvinciaPopup" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                    </select>

                                </div>
                                <div class="col-md">
                                    <label class="label-texto">Distrito <small id="cbxDistritoDirHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <select id="bxDistritoPopup" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- FIN SECCION DE DATOS DEL PROYECTO -->
                        <div id="DetalleProyecto" style="display: none;">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item size-nav"><a class="nav-link color-nav active" href="#Zonas" role="tab" data-toggle="tab">Zonas</a>
                                </li>
                                <li class="nav-item size-nav">
                                    <a class="nav-link color-nav" href="#Manzanas" role="tab" data-toggle="tab">Manzanas</a>
                                </li>
                                <li class="nav-item size-nav">
                                    <a class="nav-link color-nav" href="#Lotes" role="tab" data-toggle="tab">Lotes</a>
                                </li>
                                <li class="nav-item size-nav">
                                    <a class="nav-link color-nav" href="#TipoCasa" role="tab" data-toggle="tab">Tipos de Casa</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content fn-tab-content">

                                <!-- SECCIÓN 01 : DATOS ZONAS -->
                                <div role="tabpanel" class="tab-pane fade active p-campos-2 show" id="Zonas">
                                    <br>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-row" style="margin-top: -8px;">
                                                <div class="col-md">
                                                    <label class="label-texto">Nombre:</label>
                                                    <input id="txtNombreZonacc" type="text" class="caja-texto" placeholder="Escriba aquí" style="text-transform:uppercase;" value="" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-row" style="margin-top: -8px;">
                                                <div class="col-md-4" hidden>
                                                    <label class="label-texto">Código:</label>
                                                    <input id="txtCodigoZonacc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    <input id="txtCorrelativoZonacc" type="text" class="caja-texto" placeholder="Escriba aquí" hidden>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Área(m<sup>2</sup>):</label>
                                                    <input maxlength="6" id="txtAreaZonacc" type="number" class="caja-texto" placeholder="Ejm: 800">
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Nro. Manzanas:</label>
                                                    <input id="txtNroManzanacc" type="number" class="caja-texto" placeholder="Ejm: 12">
                                                </div>

                                                <div class="row" style="margin-top: 3%">
                                                    <div class="col-md">
                                                        <button type="button" class="btn btn-model-info" id="btnAgregarZonaP"><i class="fa fa-plus-circle"></i> Añadir</button>
                                                    </div>
                                                    <div class="col-md">
                                                        <button type="button" class="btn btn-model-secondary" id="btnLimpiarZonaP"><i class="fa fa-sync"></i> Limpiar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" cellspacing="0" id="TablaZonasPopup" style="width: 100%;">
                                                <thead class="cabecera">
                                                    <tr>
                                                        <th>ACCIONES</th>
                                                        <th>NOMBRE</th>
                                                        <th>NRO MANZANAS</th>
                                                        <th>ÁREA (m<sup>2</sup>)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle">
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <!-- FINAL DATOS ZONAS -->

                                <!-------------- **************************************************** --------------->

                                <!-- SECCIÓN 02 : DATOS MANZANAS -->
                                <div role="tabpanel" class="tab-pane fadeIn p-campos-2" id="Manzanas">
                                    <!-- FORMULARIO -->
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <br>
                                            <div class="form-row">
                                                <div class="col-md">
                                                    <label class="label-texto">Zonas <small id="cbxZonasccHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="cbxZonascc">
                                                        <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="label-texto">Área Zona (m<sup>2</sup>)</label>
                                                    <input id="txtAreaZonascc" type="text" class="caja-texto nfond" placeholder="Escriba aquí" disabled>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="label-texto">Nro Manzanas</label>
                                                    <input id="txtNroManzanascc" type="text" class="caja-texto nfond" placeholder="Escriba aquí" disabled>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Nombre <small id="txtNombreManzanaccHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNombreManzanacc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-row">
                                                <div class="col-md" hidden>
                                                    <label class="label-texto">Código</label>
                                                    <input id="txtCodigoManzanacc" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    <input id="txtCorrelativoManzanacc" type="text" class="caja-texto" placeholder="Escriba aquí" hidden>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Área(m<sup>2</sup>) <small id="txtAreaManzanaccHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input maxlength="6" id="txtAreaManzanacc" type="number" class="caja-texto" placeholder="Ejm: 500" disabled>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Nro. Lotes <small id="txtNumLotesccHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNumLotescc" type="number" class="caja-texto" placeholder="Ejm: 8" disabled>
                                                </div>

                                                <div class="col-md">
                                                    <label class="label-texto">Generacion Automática </label>
                                                    <select id="cbxGeneracionAutom" class="cbx-texto" disabled>
                                                        <option selected="true" value="0">No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Nro. Manzanas <small id="txtNroMzccHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNroMzcc" type="number" class="caja-texto" placeholder="Ejm: 6" disabled>
                                                </div>

                                                <div class="col-md">
                                                    <label class="label-texto">Extensión nombre <small id="txtNombreMzsHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNombreMzs" type="text" class="caja-texto" placeholder="Ejm: Manzana" disabled>
                                                </div>

                                                <div class="col-md-1.5" style="margin-top: 1.5%">
                                                    <button type="button" class="btn btn-model-info" id="btnAgregarManzanacc" disabled><i class="fa fa-plus-circle"></i> Agregar</button>
                                                </div>
                                                <div class="col-md-1.5" style="margin-top: 1.5%">
                                                    <button type="button" class="btn btn-model-secondary" id="btnLimpiarManzanacc" disabled><i class="fa fa-sync"></i> Limpiar</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="form-row">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" cellspacing="0" id="TablaManzanacc">
                                                <thead class="cabecera">
                                                    <tr>
                                                        <th>ACCIONES</th>
                                                        <th>NOMBRE</th>
                                                        <th>NRO LOTES</th>
                                                        <th>ÁREA (m<sup>2</sup>)</th>
                                                        <th>TIPO CASA</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle">
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <!-- FINAL DATOS MANZANAS -->

                                <!-------------- **************************************************** --------------->

                                <!-- SECCIÓN 03 : DATOS LOTES -->
                                <div role="tabpanel" class="tab-pane fade p-campos-2" id="Lotes">
                                    <br>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-row">

                                                <div class="col-md-3">
                                                    <label class="label-texto">Zona <small id="bxZonaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="bxZonaslte">
                                                        <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="label-texto">Manzana <small id="bxManzanaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="bxManzanaslte">
                                                        <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Área Terreno (m<sup>2</sup>) 
														<small id="txtAreaMzEdicionHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small>
													</label>
                                                    <input id="txtAreaMzEdicion" type="text" class="caja-texto nfond" placeholder="Escriba aquí" disabled>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Nro Lotes <small id="txtNroLotesEdicionHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNroLotesEdicion" type="number" class="caja-texto nfond" placeholder="Escriba aquí" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <label class="label-texto">Nombre <small id="txtNombreLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNombreLotee" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                </div>
                                                <div class="col-md" hidden>
                                                    <label class="label-texto">Código <small id="txtCodigoLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtCodigoLotee" name="txtCodigoLotee" type="text" class="caja-texto" placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    <input id="txtCorrelativoLote" name ="txtCorrelativoLote" type="text" class="caja-texto" placeholder="Escriba aquí" hidden>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="label-texto">Área Terreno(m<sup>2</sup>) <small id="txtAreaLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input maxlength="6" id="txtAreaLotee" type="number" class="caja-texto" disabled>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="label-texto">Tipo Moneda <small id="cbxTipoMonedaLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="cbxTipoMonedaLoteess" name="cbxTipoMonedaLoteess" >
                                                        <option selected="true" value="" disabled="disabled">Seleccione...</option>

                                                        <?php
                                                        $TipoMoneda = new ControllerCategorias();
                                                        $VerTipoMondx = $TipoMoneda->VerTipoMoneda2();
                                                        foreach ($VerTipoMondx as $TipoMonedas) {
                                                        ?>
                                                            <option value="<?php echo $TipoMonedas['ID']; ?>">
                                                                <?php echo $TipoMonedas['Nombre']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Valor con Casa <small id="txtValorCCLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtValorCCLotee" type="number" class="caja-texto" placeholder="Escriba aquí" disabled>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Valor sin Casa <small id="txtValorSCLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtValorSCLotee" type="number" class="caja-texto" placeholder="Escriba aquí" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <label class="label-texto">Generación Automática <small id="cbxGeneracionAutomLoteHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select id="cbxGeneracionAutomLote" class="cbx-texto" disabled>
                                                        <option selected="true" value="0">No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="label-texto">Extensión nombre <small id="txtExtensionNombreLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtExtensionNombreLotee" type="text" class="caja-texto" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Nro. Lotes a generar <small id="txtNroLoteeGenerarHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input id="txtNroLoteeGenerar" type="number" class="caja-texto" disabled>
                                                </div>
												<div class="col-md-1.5" style="margin-top: 1.5%">
                                                    <button type="button" class="btn btn-model-info" id="btnAgregarLotees" disabled><i class="fa fa-plus-circle"></i> Agregar</button>
                                                </div>
                                                <div class="col-md-1.5" style="margin-top: 1.5%">
                                                    <button type="button" class="btn btn-model-secondary" id="btnLimpiarLotees" disabled><i class="fa fa-sync"></i> Limpiar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="table-responsive">
                                            <br>
                                            <table class="table table-striped table-bordered" cellspacing="0" id="TablaLotesEdicion">
                                                <thead class="cabecera">
                                                    <tr>
                                                        <th></th>
                                                        <th>NOMBRE</th>
                                                        <th>ÁREA TERRENO (m<sup>2</sup>)</th>
                                                        <th>TIPO MONEDA</th>
                                                        <th>PRECIO LOTE + CASA</th>
                                                        <th>PRECIO LOTE</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle">
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <!-- FINAL DATOS LOTES -->

                                <!-------------- **************************************************** --------------->

                                <!-- SECCIÓN 04 : TIPO CASA -->
                                <div role="tabpanel" class="tab-pane fade p-campos-2" id="TipoCasa">                         
                                    <div class="form-row" hidden>
                                        <div class="col-md-12">
                                            <fieldset>
                                                <legend>Nuevo Tipo Casa</legend>
                                                <div class="form-row">
                                                    <div class="col-md-4">
                                                        <label class="label-texto">Nombre de Tipo Casa <small id="txtExtensionNombreLoteeHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                            </small></label>
                                                        <input id="txtNombreTipoCasa" type="text" class="caja-texto" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                                    </div>
                                                    <form class="col-md mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                                                        <div class="col-md">
                                                            <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                                                            <label class="label-texto">Plano / Modelo Diseño<small id="txtDescripcionAdjuntoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                            <input type="file" id="fichero" name="fichero" accept=".pdf">
                                                            <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                                                        </div>
                                                    </form>
                                                    <div class="col-md-1.5" style="margin-top: 2%">
                                                        <button type="button" class="btn btn-model-info" id="btnAgregarTipoCasa"><i class="fa fa-plus-circle"></i> Añadir</button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div><br>
                                    <div class="form-row" hidden>
                                        <div class="col-md-12">
                                            <div class="form-row">
                                                <div class="col-md" hidden>
                                                    <label class="label-texto">Zona <small id="bxZonaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="bxZonaslte">
                                                        <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md" hidden>
                                                    <label class="label-texto">Manzana <small id="bxManzanaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="bxManzanaslte">
                                                        <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label class="label-texto">Tipos de Casa Registrados<small id="bxManzanaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select class="cbx-texto" id="cbxTipoCasalista">
                                                    </select>
                                                </div>
                                                <div class="col-md-1.5" style="margin-top: 2%" hidden>
                                                    <button type="button" class="btn btn-model-info" id="btnAgregarTP"><i class="fa fa-plus-circle"></i> Añadir</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="table-responsive">
                                            <div class="col-md-12">
                                                <div class="form-row">
                                                    <div class="col-md text-left">
                                                        <label class="label-texto">Zona <small id="bxZonaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                        <select class="cbx-texto" id="cbxZonaTC">
                                                            <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md text-left">
                                                        <label class="label-texto">Manzana <small id="bxZonaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                        <select class="cbx-texto" id="cbxManzanaTC">
                                                            <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md text-left">
                                                        <label class="label-texto">Tipo Casa <small id="bxZonaslteHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                        <select class="cbx-texto" id="cbxTipoCasaTC" disabled>
                                                            <option selected="true" value="">Seleccione...</option>
                                                            <?php
                                                                $TipoCasa = new ControllerCategorias();
                                                                $VerTipoCasa = $TipoCasa->VerTipoCasaReporte();
                                                                foreach ($VerTipoCasa as $TipoCasa) {
                                                            ?>
                                                            <option value="<?php echo $TipoCasa['ID']; ?>"><?php echo $TipoCasa['Nombre']; ?>
                                                            </option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 text-left" style="margin-top: 14px;">
                                                        <button type="button" class="btn btn-registro" id="btnAgregarTC"><i class="fa fa-save"></i> Actualizar</button>
                                                    </div>
                                                    <div class="col-md text-right" style="margin-top: 14px;">
                                                        <button type="button" class="size-button btn btn-registro-success " id="btnNuevoAgregarTC"><i class="fa fa-plus-circle"></i> Nuevo Tipo de Casa</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped table-bordered" cellspacing="0" id="TablaTipoCasa" style="margin-top: 5px;">
                                                <thead class="cabecera">
                                                    <tr>
                                                        <th></th>
                                                        <th>N°</th>
                                                        <th>ZONA</th>
                                                        <th>MANZANA</th>
                                                        <th>TIPO CASA</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle">
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <!-- FINAL DATOS LOTES -->
                                <!-------------- **************************************************** --------------->
                            </div>
                        </div>
                        <br>
                    </div>

                </div>
            </div>
        </fieldset>
    </div>
</div>