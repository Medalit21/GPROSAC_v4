<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content" style="width: 800px; margin-left: 20%;">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                aria-hidden="true"></i></button>
                <span><i class="fa fa-edit" aria-hidden="true"></i> Actualizar Datos del Proyecto</span>
            </div>
            <div class="head-model cabecera-modal-accion">
                <button class="btn btn-model-success" id="btnGuardarActualizado"><i class="fa fa-save"></i> Guardar</button>
            </div>
            <div>
                <div class="p-campos-3 margen-pop-up" id="panel-options">
                    <input type="hidden" id="__ID_DATOS_PERSONALES">
                    <input type="hidden" id="__ID_DATOS_LABORALES">
                    <input type="hidden" id="txtidProyectoZona">

                    <!-- SECCION DE DATOS DEL PROYECTO -->
                    <div class="form-row" style="margin-top: 10px;">

                        <div class="form-row col-12">
                            <div class="col">
                                <div class="form-row" style="margin-top: -8px;">
                                    <div class="col">
                                        <input  id="txtidProyectocc" type="text"
                                        class="caja-texto" hidden>
                                        <label class="label-texto">Nombre:</label>
                                        <input id="txtNombrecc" type="text" class="caja-texto"
                                        placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                    </div>
                                    <div class="col" hidden>
                                        <label class="label-texto">Código:</label>
                                        <input id="txtCodigocc" type="text" class="caja-texto"
                                        placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                        <input id="txtCorrelativocc" type="text" class="caja-texto"
                                        placeholder="Escriba aquí" hidden>
                                    </div>
                                    <div class="col">
                                        <label class="label-texto">Responsable:</label>
                                        <input id="txtResponsablecc" type="text" class="caja-texto"
                                        placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                                    </div>
                                    <div class="col">
                                        <label class="label-texto">Area(m<sup>2</sup>):</label>
                                        <input  id="txtAreacc" type="text" class="caja-texto">
                                    </div>
                                    <div class="col">
                                        <label class="label-texto">Nro. Zonas:</label>
                                        <input id="txtNroZonascc" type="number" class="caja-texto">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row col-12">
                            <div class="col">
                                <label class="label-texto">Dirección:</label>
                                <input maxlength="100" id="txtDireccioncc" type="text" class="caja-texto"
                                placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label class="label-texto">Departamento <small
                                    id="cbxDepartamentoDirHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
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
                                    <?php }?>
                                </select>

                            </div>
                            <div class="col">
                                <label class="label-texto">Provincia <small id="cbxProvinciaDirHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                                <select id="bxProvinciaPopup" class="cbx-texto">
                                    <option selected="true" value="" disabled="disabled">Seleccione...
                                    </option>
                                </select>

                            </div>
                            <div class="col">
                                <label class="label-texto">Distrito <small id="cbxDistritoDirHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
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

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item size-nav"><a class="nav-link color-nav active" href="#Zonas" role="tab" data-toggle="tab">Zonas</a>
                        </li>
                        <li class="nav-item size-nav">
                            <a class="nav-link color-nav" href="#Manzanas" role="tab" data-toggle="tab">Manzanas</a>
                        </li>
                        <li class="nav-item size-nav">
                            <a class="nav-link color-nav" href="#Lotes" role="tab" data-toggle="tab">Lotes</a>
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
                                        <div class="col">
                                            <label class="label-texto">Nombre:</label>
                                            <input id="txtNombreZonacc" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row" style="margin-top: -8px;">
                                        <div class="col" hidden>
                                            <label class="label-texto">Código:</label>
                                            <input id="txtCodigoZonacc" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                            <input id="txtCorrelativoZonacc" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" hidden>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Área(m<sup>2</sup>):</label>
                                            <input maxlength="6" id="txtAreaZonacc" type="number"
                                            class="caja-texto">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Nro. Manzanas:</label>
                                            <input id="txtNroManzanacc" type="number" class="caja-texto">
                                        </div>

                                        <div class="col" style="margin-top: 4%">
                                            <button type="button" class="btn btn-model-info" id="btnAgregarZonaPopup"><i class="fa fa-plus-circle"></i> Agregar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="table-responsive">                                
                                    <table class="table table-striped table-bordered" cellspacing="0" id="TablaZonasPopup" style="width: 100%;">
                                        <thead class="cabecera">
                                            <tr>
                                                <th></th>
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
                                        <div class="col">
                                            <label class="label-texto">Zonas :</label>
                                            <select class="cbx-texto" id="cbxZonascc">
                                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Área Zona (m<sup>2</sup>) :</label>
                                            <input id="txtAreaZonascc" type="text" class="caja-texto nfond"
                                            placeholder="Escriba aquí" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Nro Manzanas :</label>
                                            <input id="txtNroManzanascc" type="text" class="caja-texto nfond"
                                            placeholder="Escriba aquí" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="label-texto">Nombre:</label>
                                            <input id="txtNombreManzanacc" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                        </div>
                                        <div class="col" hidden>
                                            <label class="label-texto">Código:</label>
                                            <input id="txtCodigoManzanacc" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                            <input id="txtCorrelativoManzanacc" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" hidden>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Área(m<sup>2</sup>):</label>
                                            <input maxlength="6" id="txtAreaManzanacc" type="number"
                                            class="caja-texto" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Nro. Lotes:</label>
                                            <input id="txtNumLotescc" type="number" class="caja-texto" disabled>
                                        </div>

                                        <div class="col">
                                            <label class="label-texto">Tipo Casa:</label>
                                            <select id="bxTipoCasacc" class="cbx-texto" disabled>
                                                <option selected="true" value="">Seleccione...
                                                </option>
                                                <?php
                                                    $TipoCasa = new ControllerCategorias();
                                                    $VerTipoCasa = $TipoCasa->VerTipoCasa();
                                                    foreach ($VerTipoCasa as $TipoC) {
                                                ?>
                                                <option value="<?php echo $TipoC['ID']; ?>">
                                                <?php echo $TipoC['Nombre']; ?>
                                                </option>
                                                <?php }?>
                                            </select>
                                        </div>

                                        <div class="col" style="margin-top: 2%">
                                            <button type="button" class="btn btn-model-info" id="btnAgregarManzanacc" disabled><i class="fa fa-plus-circle"></i> Agregar</button>
                                        </div>

                                    </div>
                                </div>
                            </div><br>
                            <div class="form-row">
                                <div class="table-responsive">                            
                                    <table class="table table-striped table-bordered" cellspacing="0" id="TablaManzanacc">
                                        <thead class="cabecera">
                                            <tr>
                                                <th></th>
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

                                        <div class="col">
                                            <label class="label-texto">Zona :</label>
                                            <select class="cbx-texto" id="bxZonaslte">
                                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Manzana :</label>
                                            <select class="cbx-texto" id="bxManzanaslte">
                                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Área(m<sup>2</sup>) :</label>
                                            <input id="txtAreaMzEdicion" type="text" class="caja-texto nfond"
                                            placeholder="Escriba aquí" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Nro Manzanas :</label>
                                            <input id="txtNroLotesEdicion" type="number" class="caja-texto nfond"
                                            placeholder="Escriba aquí" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col">
                                            <label class="label-texto">Nombre:</label>
                                            <input id="txtNombreLotee" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                        </div>
                                        <div class="col" hidden>
                                            <label class="label-texto">Código:</label>
                                            <input id="txtCodigoLotee" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                            <input id="txtCorrelativoLote" type="text" class="caja-texto"
                                            placeholder="Escriba aquí" hidden>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Área(m<sup>2</sup>):</label>
                                            <input maxlength="6" id="txtAreaLotee" type="number"
                                            class="caja-texto" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Tipo Moneda :</label>
                                            <select class="cbx-texto" id="cbxTipoMonedaLotee" disabled>
                                                <option selected="true" value="" disabled="disabled">Seleccione...</option>

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
                                            <input id="txtValorCCLotee" type="number" class="caja-texto"
                                            placeholder="Escriba aquí" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Valor sin Casa:</label>
                                            <input id="txtValorSCLotee" type="number" class="caja-texto"
                                            placeholder="Escriba aquí" disabled>
                                        </div>
                                    </div>                                
                                </div>
                            </div> 
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-row">                                    
                                        <div class="col">
                                            <label class="label-texto">Generar Lotes:</label>
                                            <input id="txtGeneracionLotee" type="radio" class="caja-texto"
                                            placeholder="Escriba aquí" disabled>
                                            <label for="txtGeneracionLotes">Si</label>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Extensión nombre:</label>
                                            <input id="txtExtensionNombreLotee" type="text" class="caja-texto" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Nro. Lotes a generar:</label>
                                            <input id="txtNroLoteeGenerar" type="number" class="caja-texto" disabled>
                                        </div>
                                        <div class="col" style="margin-top: 2%">
                                            <button type="button" class="btn btn-model-info" id="btnAgregarLotees" disabled><i class="fa fa-plus-circle"></i> Añadir</button>
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
                        </div>
                        <!-- FINAL DATOS LOTES -->

                        <!-------------- **************************************************** --------------->

                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>