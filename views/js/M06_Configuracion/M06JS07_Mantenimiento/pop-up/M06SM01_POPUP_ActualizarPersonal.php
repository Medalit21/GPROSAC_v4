<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content ach">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fa fa-edit" aria-hidden="true"></i> Actualizar Datos del Cliente</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnGuardarActulizado"><i class="fa fa-save"></i> Guardar</button>
            <button class="btn btn-model-cerrar" data-dismiss="modal"><i class="fas fa-times"></i>
                Cerrar</button>
        </div>
        <div>
            <div class="p-campos-3 margen-pop-up" id="panel-options">
                <input type="hidden" id="__ID_DATOS_PERSONAL">


                <fieldset>
                    <legend>General</legend>
                    <div class="form-row" style="margin-top: -8px;">
                        <div class="col-md-2">
                            <label class="label-texto">Tipo Documento <small id="cbxTipoDocumentoHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxTipoDocumento_a" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                <?php
$tipoDoc = new ControllerCategorias();
$VerTiposDoc = $tipoDoc->VerTipoDocumento();
foreach ($VerTiposDoc as $td) {
    ?>
                                <option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Documento <small id="txtDocumentoHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <input id="txtDocumento_a" class="caja-texto" maxlength="8" placeholder="# Documento"
                                required>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">País Emisor
                                Documento <small id="cbxPaisEmisorDocumentoHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxPaisEmisorDocumento_a" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                <?php
$PaisEmisorDoc = new ControllerCategorias();
$paisEmisorDoc = $PaisEmisorDoc->VerPaisEmisorDoc();
foreach ($paisEmisorDoc as $td) {
    ?>
                                <option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Nacionalidad <small id="cbxNacionalidadHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxNacionalidad_a" class="cbx-texto">
                                <option value="" selected="true" disabled="disabled">Seleccione...</option>
                                <?php
$Nacionalidad = new ControllerCategorias();
$VerNac = $Nacionalidad->VerNacionalidad();
foreach ($VerNac as $Nac) {
    ?>
                                <option value="<?php echo $Nac['ID']; ?>"><?php echo $Nac['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Sexo <small id="cbxSexoHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxSexo_a" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                <?php
$genero = new ControllerCategorias();
$Vergenero = $genero->VerGeneroPersonal();
foreach ($Vergenero as $gen) {
    ?>
                                <option value="<?php echo $gen['ID']; ?>"><?php echo $gen['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Estado Civil:</label>
                            <select id="cbxEstadoCivil_a" class="cbx-texto">
                                <option selected="true" value="">Seleccione...</option>
                                <?php
$estado_civil = new ControllerCategorias();
$Verestado_civil = $estado_civil->VerEstadoCivil();
foreach ($Verestado_civil as $estado_civil) {
    ?>
                                <option value="<?php echo $estado_civil['ID']; ?>">
                                    <?php echo $estado_civil['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="label-texto">Apellido Paterno <small id="txtApellidoPaternoHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <input type="text" id="txtApellidoPaterno_a" class="caja-texto" placeholder="Ejm: Morales"
                                required>
                        </div>
                        <div class="col">
                            <label class="label-texto">Apellido Materno <small id="txtApellidoMaternoHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <input type="text" id="txtApellidoMaterno_a" class="caja-texto" placeholder="Ejm: Gomez"
                                required>
                        </div>
                        <div class="col">
                            <label class="label-texto">Nombres <small id="txtNombresHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <input type="text" id="txtNombres_a" class="caja-texto" placeholder="Ejm: Julio Adrian"
                                required>

                        </div>
                    </div>
                </fieldset>
                <div class="form-row ">
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Datos Contacto</legend>
                            <div class="form-row" style="margin-top: -8px;">
                                <div class="col-md-2">
                                    <label class="label-texto">Teléfono:</label>
                                    <input maxlength="9" id="txtTelefono_a" type="text" class="caja-texto"
                                        placeholder="Teléfono">
                                </div>
                                <div class="col-md-2">
                                    <label class="label-texto">Celular 1:<small id="txtCelularHtml_a"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <input maxlength="9" id="txtCelular_a" type="text" class="caja-texto"
                                        placeholder="Celular">
                                </div>
                                <div class="col">
                                    <label class="label-texto">Celular 2:</label>
                                    <input maxlength="9" id="txtCelular2_a" type="text" class="caja-texto"
                                        placeholder="Celular">
                                </div>
                                <div class="col-6">
                                    <label class="label-texto">Email :<small id="txtCorreoHtml_a"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <input maxlength="50" id="txtCorreo_a" type="email" class="caja-texto"
                                        placeholder="Ejm: Contable@acg.com.pe">

                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Datos Nacimiento</legend>
                            <div class="form-row" style="margin-top: -8px;">
                                <div class="col">
                                    <label class="label-texto">Fecha <small id="txtFechaNaciminetoHtml_a"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <input id="txtFechaNacimineto_a" type="date" min="1921-01-01" max="2007-12-31"
                                        class="caja-texto">

                                </div>
                                <div class="col">
                                    <label class="label-texto">País</label>
                                    <select id="cbxPaisNacimiento_a" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                        <?php
$Pais = new ControllerCategorias();
$VerPais = $Pais->VerPais();
foreach ($VerPais as $pais) {
    ?>
                                        <option value="<?php echo $pais['ID']; ?>">
                                            <?php echo $pais['Nombre']; ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="label-texto">Departamento</label>
                                    <select id="cbxDepartamentoNacimiento_a" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="label-texto">Provincia</label>
                                    <select id="cbxProvinciaNacimiento_a" class="caja-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </fieldset>
                    </div>
                </div>

                <fieldset>
                    <legend>Datos Domicilio</legend>
                    <div class="form-row" style="margin-top: -8px;">
                        <div class="col">
                            <label class="label-texto">Departamento <small id="cbxDepartamentoDirHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxDepartamentoDir_a" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                <?php
$departam = new ControllerCategorias();
$VerDepart = $departam->VerDepartamento();
foreach ($VerDepart as $depto) {
    ?>
                                <option value="<?php echo $depto['ID']; ?>"><?php echo $depto['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>

                        </div>
                        <div class="col">
                            <label class="label-texto">Provincia <small id="cbxProvinciaDirHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxProvinciaDir_a" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                            </select>

                        </div>
                        <div class="col">
                            <label class="label-texto">Distrito <small id="cbxDistritoDirHtml_a"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxDistritoDir_a" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Tipo Vía:</label>
                            <select id="cbxTipoVia_a" class="cbx-texto">
                                <option selected="true" value="">Seleccione...</option>
                                <?php
$Via = new ControllerCategorias();
$VerVia = $Via->VerVia();
foreach ($VerVia as $via) {
    ?>
                                <option value="<?php echo $via['ID']; ?>"><?php echo $via['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label-texto">Nombre Vía:</label>
                            <input maxlength="20" id="txtNombreVia_a" type="text" class="caja-texto"
                                placeholder="Nombre Vía">
                        </div>
                        <div class="col-md-1">
                            <label class="label-texto">N° Vía:</label>
                            <input maxlength="4" id="txtNroVia_a" type="text" class="caja-texto" placeholder="N° Vía">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label class="label-texto">Tipo Zona:</label>
                            <select id="cbxTipoZona_a" class="cbx-texto">
                                <option selected="true" value="">Seleccione...</option>
                                <?php
$Zona = new ControllerCategorias();
$VerZona = $Zona->VerZona();
foreach ($VerZona as $zona) {
    ?>
                                <option value="<?php echo $zona['ID']; ?>"><?php echo $zona['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Nombre Zona:</label>
                            <input maxlength="20" id="txtNombreZona_a" type="text" class="caja-texto"
                                placeholder="Nombre Zona">
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Referencia:</label>
                            <input maxlength="40" id="txtReferencia_a" type="text" class="caja-texto"
                                placeholder="Referencia">
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Situación
                                Domiciliaria:</label>
                            <select id="cbxSituacionDomiciliaria_a" class="cbx-texto">
                                <option selected="true" value="">Seleccione...</option>
                                <?php
$sdomiciliaria = new ControllerCategorias();
$VerSitDomiciliaria = $sdomiciliaria->VerSituacionDomiciliaria();
foreach ($VerSitDomiciliaria as $sitdom) {
    ?>
                                <option value="<?php echo $sitdom['ID']; ?>">
                                    <?php echo $sitdom['Nombre']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col">
                            <label class="label-texto">Etapa:</label>
                            <input maxlength="4" id="txtEtapa_a" type="text" class="caja-texto" placeholder="Etapa">
                        </div>
                        <div class="col">
                            <label class="label-texto">N° Dpto:</label>
                            <input maxlength="4" id="txtNroDpto_a" type="text" class="caja-texto" placeholder="N° Dpto">
                        </div>
                        <div class="col">
                            <label class="label-texto">Interior:</label>
                            <input maxlength="4" id="txtInterior_a" type="text" class="caja-texto"
                                placeholder="N° Interior">
                        </div>
                        <div class="col">
                            <label class="label-texto">Mz:</label>
                            <input maxlength="4" id="txtMz_a" type="text" class="caja-texto" placeholder="Mz">
                        </div>
                        <div class="col">
                            <label class="label-texto">Lt:</label>
                            <input maxlength="4" id="txtLt_a" type="text" class="caja-texto" placeholder="Lt">
                        </div>
                        <div class="col">
                            <label class="label-texto">Km:</label>
                            <input maxlength="4" id="txtKm_a" type="text" class="caja-texto" placeholder="Km">
                        </div>
                        <div class="col">
                            <label class="label-texto">Block:</label>
                            <input maxlength="4" id="txtBlock_a" type="text" class="caja-texto" placeholder="Block">
                        </div>
                    </div>

                </fieldset>
                <!-- FINAL FORMULARIO -->
                <br>
            </div>
            <!-- FINAL DATOS PERSONALES -->

        </div>
    </div>
</div>
</div>