<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content ach">
        <div class="modal-cabecera-list" style="text-align:left;">
            <button class="close btn-cerrar" data-dismiss="modal" aria-label="Close"><i class="fa fa-window-close"
                    aria-hidden="true"></i></button>
            <span><i class="fa fa-edit" aria-hidden="true"></i> Ingresa Datos del Cliente</span>
        </div>
        <div class="head-model cabecera-modal-accion">
            <button class="btn btn-model-success" id="btnGuardarNuevo"><i class="fa fa-save"></i> Guardar</button>
            <button class="btn btn-model-cerrar" data-dismiss="modal"><i class="fas fa-times"></i>
                Cerrar</button>
        </div>
        <div>
            <div class="p-campos-3 margen-pop-up mb-3" id="panel-options">
                <fieldset>
                    <legend>General</legend>
                    <div class="form-row" style="margin-top: -8px;">
                        <div class="col-md-2">
                            <label class="label-texto">Tipo Documento <small id="cbxTipoDocumentoHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info"> </small></label>
                            <select id="cbxTipoDocumento" class="cbx-texto">
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
                            <label class="label-texto">Documento <small id="txtDocumentoHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info"> </small></label>
                            <input id="txtDocumento" class="caja-texto" maxlength="8" placeholder="# Documento"
                                required>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">País Emisor
                                Documento <small id="cbxPaisEmisorDocumentoHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info"> </small></label>
                            <select id="cbxPaisEmisorDocumento" class="cbx-texto">
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
                            <label class="label-texto">Nacionalidad <small id="cbxNacionalidadHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxNacionalidad" class="cbx-texto">
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
                            <label class="label-texto">Sexo <small id="cbxSexoHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxSexo" class="cbx-texto">
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
                            <select id="cbxEstadoCivil" class="cbx-texto">
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
                            <label class="label-texto">Apellido Paterno <small id="txtApellidoPaternoHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info"> </small></label>
                            <input type="text" id="txtApellidoPaterno" class="caja-texto" placeholder="Ejm: Morales"
                                required>
                        </div>
                        <div class="col">
                            <label class="label-texto">Apellido Materno <small id="txtApellidoMaternoHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info"> </small></label>
                            <input type="text" id="txtApellidoMaterno" class="caja-texto" placeholder="Ejm: Gomez"
                                required>
                        </div>
                        <div class="col">
                            <label class="label-texto">Nombres <small id="txtNombresHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <input type="text" id="txtNombres" class="caja-texto" placeholder="Ejm: Julio Adrian"
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
                                    <input maxlength="9" id="txtTelefono" type="text" class="caja-texto"
                                        placeholder="Teléfono">
                                </div>
                                <div class="col-md-2">
                                    <label class="label-texto">Celular 1: <small id="txtCelularHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <input maxlength="9" id="txtCelular" type="text" class="caja-texto"
                                        placeholder="Celular">
                                </div>
                                <div class="col">
                                    <label class="label-texto">Celular 2:</label>
                                    <input maxlength="9" id="txtCelular2" type="text" class="caja-texto"
                                        placeholder="Celular">
                                </div>
                                <div class="col-6">
                                    <label class="label-texto">Email <small id="txtCorreoHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <input maxlength="50" id="txtCorreo" type="email" class="caja-texto"
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
                                    <label class="label-texto">Fecha <small id="txtFechaNaciminetoHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                    <input id="txtFechaNacimineto" type="date" min="1921-01-01" max="2007-12-31"
                                        class="caja-texto">

                                </div>
                                <div class="col">
                                    <label class="label-texto">País</label>
                                    <select id="cbxPaisNacimiento" class="cbx-texto">
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
                                    <select id="cbxDepartamentoNacimiento" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Seleccione...
                                        </option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="label-texto">Provincia</label>
                                    <select id="cbxProvinciaNacimiento" class="caja-texto">
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
                            <label class="label-texto">Departamento <small id="cbxDepartamentoDirHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxDepartamentoDir" class="cbx-texto">
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
                            <label class="label-texto">Provincia <small id="cbxProvinciaDirHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxProvinciaDir" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                            </select>

                        </div>
                        <div class="col">
                            <label class="label-texto">Distrito <small id="cbxDistritoDirHtml"
                                    class="form-text text-muted-validacion text-danger ocultar-info">
                                </small></label>
                            <select id="cbxDistritoDir" class="cbx-texto">
                                <option selected="true" value="" disabled="disabled">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Tipo Vía:</label>
                            <select id="cbxTipoVia" class="cbx-texto">
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
                            <input maxlength="20" id="txtNombreVia" type="text" class="caja-texto"
                                placeholder="Nombre Vía">
                        </div>
                        <div class="col-md-1">
                            <label class="label-texto">N° Vía:</label>
                            <input maxlength="4" id="txtNroVia" type="text" class="caja-texto" placeholder="N° Vía">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <label class="label-texto">Tipo Zona:</label>
                            <select id="cbxTipoZona" class="cbx-texto">
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
                            <input maxlength="20" id="txtNombreZona" type="text" class="caja-texto"
                                placeholder="Nombre Zona">
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Referencia:</label>
                            <input maxlength="40" id="txtReferencia" type="text" class="caja-texto"
                                placeholder="Referencia">
                        </div>
                        <div class="col-md-2">
                            <label class="label-texto">Situación
                                Domiciliaria:</label>
                            <select id="cbxSituacionDomiciliaria" class="cbx-texto">
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
                            <input maxlength="4" id="txtEtapa" type="text" class="caja-texto" placeholder="Etapa">
                        </div>
                        <div class="col">
                            <label class="label-texto">N° Dpto:</label>
                            <input maxlength="4" id="txtNroDpto" type="text" class="caja-texto" placeholder="N° Dpto">
                        </div>
                        <div class="col">
                            <label class="label-texto">Interior:</label>
                            <input maxlength="4" id="txtInterior" type="text" class="caja-texto"
                                placeholder="N° Interior">
                        </div>
                        <div class="col">
                            <label class="label-texto">Mz:</label>
                            <input maxlength="4" id="txtMz" type="text" class="caja-texto" placeholder="Mz">
                        </div>
                        <div class="col">
                            <label class="label-texto">Lt:</label>
                            <input maxlength="4" id="txtLt" type="text" class="caja-texto" placeholder="Lt">
                        </div>
                        <div class="col">
                            <label class="label-texto">Km:</label>
                            <input maxlength="4" id="txtKm" type="text" class="caja-texto" placeholder="Km">
                        </div>
                        <div class="col">
                            <label class="label-texto">Block:</label>
                            <input maxlength="4" id="txtBlock" type="text" class="caja-texto" placeholder="Block">
                        </div>
                    </div>

                </fieldset>
            </div>
        </div>
    </div>
</div>