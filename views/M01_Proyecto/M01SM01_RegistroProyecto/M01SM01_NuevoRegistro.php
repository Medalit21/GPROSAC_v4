<div class="card">
                    <div class="card-body wizard-content">
                        <h6 class="card-subtitle"></h6>
                        <form id="example-form" action="#" class="m-t-40">
                            <div>

                                <section>
                                    <div class="form-row" style="margin-top: -8px;">
                                        <div class="col-md-3">
                                            <button class="btn btn-info" id="btnproyecto">PROYECTO</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-secondary" id="btnzonas" disabled>ZONA</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-secondary" id="btnmanzanas" disabled>MANZANAS</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-secondary" id="btnlotes" disabled>LOTES</button>
                                        </div>
                                    </div>
                                </section><br>

                                <section id="panel_proyecto">
                                    <!-- PROYECTOS -->
                                    <hr><br>
                                    <div role="tabpanel" class="tab-pane fade active p-campos-2 show">
                                        <!-- FORMULARIO -->
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col-md">
                                                        <label class="label-texto">Nombre:</label>
                                                        <input id="txtNombre" type="text" class="caja-texto"
                                                                    placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                                    </div>
                                                    <div class="col-md" hidden>
                                                        <label class="label-texto">Código:</label>
                                                        <input id="txtCodigo" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                        <input id="txtCorrelativo" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" hidden>
                                                    </div>
													<div class="col-md">
                                                        <label class="label-texto">Responsable:</label>
                                                        <input id="txtResponsable" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">                                                    
                                                    <div class="col-md-3">
                                                        <label class="label-texto">Area(m<sup>2</sup>):</label>
                                                        <input  id="txtArea" type="text"
                                                            class="caja-texto">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="label-texto">Nro. Zonas:</label>
                                                        <input id="txtNroZonas" type="number" class="caja-texto">
                                                    </div>
													<div class="col-md">
                                                        <label class="label-texto">Plano(imagen):</label>
                                                        <input id="txtPlano" name="txtPlano" type="file" class="caja-texto" accept=".jpg,.png,.jpeg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>
 
                                        <div class="form-row" style="margin-top: -8px;">
                                            <div class="col">
                                                <label class="label-texto">Dirección:</label>
                                                <input maxlength="100" id="txtDireccion" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                            </div>
                                            <div class="col">
                                                <label class="label-texto">Departamento <small
                                                        id="cbxDepartamentoDirHtml"
                                                        class="form-text text-muted-validacion text-danger ocultar-info">
                                                    </small></label>
                                                <select id="cbxDepartamentoDir" class="cbx-texto">
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
                                                    <?php }?>
                                                </select>

                                            </div>
                                            <div class="col">
                                                <label class="label-texto">Provincia <small id="cbxProvinciaDirHtml"
                                                        class="form-text text-muted-validacion text-danger ocultar-info">
                                                    </small></label>
                                                <select id="cbxProvinciaDir" class="cbx-texto">
                                                    <option selected="true" value="" disabled="disabled">Seleccione...
                                                    </option>
                                                </select>

                                            </div>
                                            <div class="col">
                                                <label class="label-texto">Distrito <small id="cbxDistritoDirHtml"
                                                        class="form-text text-muted-validacion text-danger ocultar-info">
                                                    </small></label>
                                                <select id="cbxDistritoDir" class="cbx-texto">
                                                    <option selected="true" value="" disabled="disabled">Seleccione...
                                                    </option>
                                                </select>
                                            </div>
                                        </div><br>

                                        <div class="form-row" style="margin-top: -8px; margin-left: 45%">
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-model-success" id="btnGuardarProyecto" style="width: 150px"><i class="fa fa-save"></i> Guardar y continuar</button>
                                            </div>
                                        </div>

                                        <br><hr><br>

                                        <!-- FINAL FORMULARIO -->
                                        <br>
                                    </div>
                                    <!-- FINAL PROYECTOS -->
                                </section>


                                <section id="panel_zonas">
                                    <!-- ZONAS -->
                                    <hr><br>

                                    <div role="tabpanel" class="tab-pane fade active p-campos-2 show">
                                        <!-- FORMULARIO -->
                                        <div class="form-row" style="margin-top: -8px; margin-left: 45%">
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-model-success" id="btnGuardarZona"><i class="fa fa-arrow-alt-circle-right"></i> Continuar</button>
                                            </div>
                                        </div><br>

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">

                                                    <input id="txtidProyectoZona" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" hidden>
                                                    <div class="col">
                                                        <label class="label-texto">Proyecto :</label>
                                                        <input id="txtNomProyectoZona" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" readonly>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="label-texto">Área(m<sup>2</sup>) :</label>
                                                        <input id="txtAreaProyectoZona" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" readonly>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="label-texto">Nro Limite de Zonas :</label>
                                                        <input id="txtNroZonasProyectoZona" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col">
                                                        <label class="label-texto">Nombre:</label>
                                                        <input id="txtNombreZona" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col">
                                                        <label class="label-texto">Código:</label>
                                                        <input id="txtCodigoZona" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                        <input id="txtCorrelativoZona" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" hidden>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área(m<sup>2</sup>):</label>
                                                        <input maxlength="6" id="txtAreaZona" type="text"
                                                            class="caja-texto">
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro. Manzanas:</label>
                                                        <input id="txtNroManzana" type="number" class="caja-texto">
                                                    </div>

                                                    <div class="col" style="margin-top: 2.5%">
                                                        <button type="button" class="btn btn-model-info" id="btnAñadirZona"><i class="fa fa-plus-circle"></i> Añadir</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>

                                        <div class="form-row" style="margin-top: -8px;">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover w-100" id="TablaZonasReporte" style="display: none;">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>NOMBRE</th>
                                                            <th>CÓDIGO</th>
                                                            <th>NRO MANZANAS</th>
                                                            <th>ÁREA (m<sup>2</sup>)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="control-detalle">
                                                    </tbody>
                                                </table>
                                                <br><br>
                                                <table class="table table-striped table-bordered" cellspacing="0" id="TablaZonas">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>NOMBRE</th>
                                                            <th>CÓDIGO</th>
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

                                        <br><hr><br>

                                        <!-- FINAL FORMULARIO -->
                                        <br>
                                    </div>
                                    <!-- FINAL ZONAS-->
                                </section>


                                <section id="panel_manzanas">
                                    <hr><br>
                                    <!-- MANZANAS -->
                                    <div role="tabpanel" class="tab-pane fade active p-campos-2 show">
                                        <!-- FORMULARIO -->
                                        <div class="form-row" style="margin-top: -8px; margin-left: 45%">
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-model-success" id="btnGuardarManzana"><i class="fa fa-arrow-alt-circle-right"></i> Continuar</button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">

                                                    <input id="txtidProyectoz" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" hidden>
                                                    <div class="col">
                                                        <label class="label-texto">Proyecto :</label>
                                                        <input id="txtNomProyectoz" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área(m<sup>2</sup>) :</label>
                                                        <input id="txtAreaProyectoz" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro Zonas :</label>
                                                        <input id="txtNroZonasProyectoz" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col">
                                                        <label class="label-texto">Zonas :</label>
                                                        <select class="cbx-texto" id="cbxZonas">
                                                            <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área Zona (m<sup>2</sup>) :</label>
                                                        <input id="txtAreaZonaa" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro Manzanas :</label>
                                                        <input id="txtNroManzanas" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col">
                                                        <label class="label-texto">Nombre:</label>
                                                        <input id="txtNombreManzana" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Código:</label>
                                                        <input id="txtCodigoManzana" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                        <input id="txtCorrelativoManzana" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" hidden>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área(m<sup>2</sup>):</label>
                                                        <input maxlength="6" id="txtAreaManzana" type="text"
                                                            class="caja-texto" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro. Lotes:</label>
                                                        <input id="txtNumLotes" type="number" class="caja-texto" disabled>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col-3">
                                                        <label class="label-texto">Generar Mzs:</label>
                                                        <input id="txtGeneracionManzanas" type="radio" class="caja-texto"
                                                            placeholder="Escriba aquí" disabled>
                                                        <label for="txtGeneracionManzanas">Si</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="label-texto">Extensión Nombre:</label>
                                                        <input id="txtCodigoGeneracionManzanas" type="text" class="caja-texto" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="label-texto">Nro Mzs a generar:</label>
                                                        <input id="txtNumManzanasGeneradas" type="number" class="caja-texto" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    </div>
                                                    <div class="col" style="margin-top: 3%">
                                                        <button type="button" class="btn btn-model-info" id="btnAgregarManzana" disabled><i class="fa fa-plus-circle"></i> Añadir</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>

                                        <div class="form-row" style="margin-top: -8px;">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover w-100" id="TablaManzanasReporte" style="display: none;">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>NOMBRE</th>
                                                            <th>CÓDIGO</th>
                                                            <th>NRO LOTES</th>
                                                            <th>ÁREA (m<sup>2</sup>)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="control-detalle">
                                                    </tbody>
                                                </table>
                                                <br><br>
                                                <table class="table table-striped table-bordered" cellspacing="0" id="TablaManzanas">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>NOMBRE</th>
                                                            <th>CÓDIGO</th>
                                                            <th>NRO LOTES</th>
                                                            <th>ÁREA (m<sup>2</sup>)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="control-detalle">
                                                    </tbody>
                                                </table>
                                                <br>
                                            </div>
                                        </div>

                                        <br><hr><br>

                                        <!-- FINAL FORMULARIO -->
                                        <br>
                                    </div>
                                    <!-- FINAL MANZANAS -->
                                </section>

                                <section id="panel_lotes">
                                    <hr><br>
                                    <!-- LOTES -->
                                    <div role="tabpanel" class="tab-pane fade active p-campos-2 show">
                                        <!-- FORMULARIO -->
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">

                                                    <input id="txtidProyectozlt" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" hidden>
                                                    <div class="col">
                                                        <label class="label-texto">Proyecto :</label>
                                                        <input id="txtNomProyectozlt" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área(m<sup>2</sup>) :</label>
                                                        <input id="txtAreaProyectozlt" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro Zonas :</label>
                                                        <input id="txtNroZonasProyectozlt" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">

                                                    <div class="col">
                                                        <label class="label-texto">Zona :</label>
                                                        <select class="cbx-texto" id="cbxZonaslt">
                                                            <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Manzana :</label>
                                                        <select class="cbx-texto" id="cbxManzanaslt">
                                                            <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área(m<sup>2</sup>) :</label>
                                                        <input id="txtAreaMz" type="text" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro Manzanas :</label>
                                                        <input id="txtNroLotes" type="number" class="caja-texto nfond"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>

                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col">
                                                        <label class="label-texto">Nombre:</label>
                                                        <input id="txtNombreLote" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Código:</label>
                                                        <input id="txtCodigoLote" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                            <input id="txtCorrelativoLoteeee" type="text" class="caja-texto"
                                                            placeholder="Escriba aquí" hidden>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Área(m<sup>2</sup>):</label>
                                                        <input maxlength="6" id="txtAreaLote" type="number"
                                                            class="caja-texto" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Tipo Moneda :</label>
                                                        <select class="cbx-texto" id="cbxTipoMoneda" disabled>
                                                            <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                            </option>
                                                                <?php
$TipoMoneda = new ControllerCategorias();
$VerTipoMond = $TipoMoneda->VerTipoMoneda();
foreach ($VerTipoMond as $TipoMoned) {
    ?>
                                                                <option value="<?php echo $TipoMoned['ID']; ?>">
                                                                    <?php echo $TipoMoned['Nombre']; ?>
                                                                </option>
                                                                <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Valor con Casa:</label>
                                                        <input id="txtValorCCLote" type="number" class="caja-texto"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-row" style="margin-top: -8px;">
                                                    <div class="col">
                                                        <label class="label-texto">Valor sin Casa:</label>
                                                        <input id="txtValorSCLote" type="number" class="caja-texto"
                                                            placeholder="Escriba aquí" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Generar Lotes:</label>
                                                        <input id="txtGeneracionLotes" type="radio" class="caja-texto"
                                                            placeholder="Escriba aquí" disabled>
                                                        <label for="txtGeneracionLotes">Si</label>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Extensión nombre:</label>
                                                        <input id="txtExtensionNombreLote" type="text" class="caja-texto" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                                    </div>
                                                    <div class="col">
                                                        <label class="label-texto">Nro. Lotes a generar:</label>
                                                        <input id="txtNroLotesGenerar" type="number" class="caja-texto" disabled>
                                                    </div>
                                                    <div class="col" style="margin-top: 3%">
                                                        <button type="button" class="btn btn-model-info" id="btnAgregarLote" disabled><i class="fa fa-plus-circle"></i> Añadir</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br>

                                        <div class="form-row" style="margin-top: -8px;">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover w-100" id="TablaLotesReporte" style="display: none;">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>NOMBRE</th>
                                                            <th>ÁREA (m<sup>2</sup>)</th>
                                                            <th>TIPO MONEDA</th>
                                                            <th>PRECIO LOTE + CASA</th>
                                                            <th>PRECIO LOTE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="control-detalle">
                                                    </tbody>
                                                </table>
                                                <br><br>
                                                <table class="table table-striped table-bordered" cellspacing="0" id="TablaLotes">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>NOMBRE</th>
                                                            <th>ÁREA (m<sup>2</sup>)</th>
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

                                        <br><hr><br>

                                        <div class="form-row" style="margin-top: -8px; margin-left: 45%">
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-model-success" id="btnGuardarLote"><i class="fa fa-save"></i> Finalizar</button>
                                            </div>
                                        </div>
                                        <!-- FINAL FORMULARIO -->
                                        <br>
                                    </div>
                                    <!-- FINAL LOTES -->
                                </section>
                            </div>
                        </form>
                    </div>
                </div>