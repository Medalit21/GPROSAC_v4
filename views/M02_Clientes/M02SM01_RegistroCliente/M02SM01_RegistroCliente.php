<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php require_once "../../../config/configuracion.php";?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <title><?php echo $NAME_APP.' - Clientes'; ?></title>
    <link rel="icon" href="../../../images/<?php echo $NAME_LOGO; ?>" type="image/png" />
    <!-- Custom CSS -->
    <link href="../../assets/libs/flot/css/float-chart.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../css/estilos.css?v=<?php echo time(); ?>">
	
    <!-- LIBRERIAS LOADING PROCESANDO -->
    <link rel="stylesheet" type="text/css" href="../../css/LoadingProcesandoGeneral.css?v=<?php echo time(); ?>">
    <!-- LIBRERIAS ALERTA MENSAJES -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" type="text/css" href="../../main.css">
    <link rel="stylesheet" href="../../datatables/datatables.min.css" />
    <link rel="stylesheet" href="../../datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="../../datatables/select/css/select.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../../code/select2/select2.min.css?v=<?php echo time(); ?>">
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script src="../../code/select2/select2.min.js"></script>
</head>

<body class="fond-back">

    <?php
        session_start();		
    	include_once "../../../config/codificar.php";		
        if(empty($_SESSION['usu'])){            
            $variable = $_GET['Vsr'];
            $user_doc = decrypt($variable,"123");
            $_SESSION['usu'] = $user_doc;
            $valor_user = $_SESSION['usu'];
            $_SESSION['variable_user'] = $valor_user;
        }else{
            $valor_user = $_SESSION['usu'];
            $_SESSION['variable_user'] = $valor_user;
            $variable = encrypt($valor_user, "123");
        }
        require_once "../../../config/conexion_2.php";
        require_once "../../../config/control_sesion.php";  
        require_once "../../../controllers/ControllerCategorias.php";
		$user_sesion = encrypt($valor_user,"123");
    ?>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!--<div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    -->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <?php include_once "../../recursos/header.php";?>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <?php include_once "../../recursos/menu.php";?>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <div class="breadcrumb-ancho mt-1">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Cliente</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Registro de Cliente</a></li>

                </ol>
            </div>

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid pt-0">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="botones-acciones-2">
                            <button id="nuevo" type="button" class="btn btn-registro"><i class="fas fa-file-alt"></i> Nuevo</button>
                            <button id="modificar" type="button" class="btn btn-registro"><i class="fas fa-edit"></i> Modificar</button>
                            <button id="guardar" type="button" class="btn btn-registro" disabled=""><i class="fas fa-save"></i> Guardar</button>
                            <button id="cancelar" type="button" class="btn btn-registro"><i class="fas fa-minus-circle"></i> Cancelar</button>
                            <button id="eliminar" type="button" class="btn btn-registro"><i class="fas fa-trash"></i> Elminar</button>
                            <button id="busqueda_avanzada" type="button" class="btn btn-registro"><i class="fas fa-list"></i> Lista</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="contenido_registro" style="display:none;">
                            <fieldset>
                                <legend>General</legend>
                                <div id="formularioRegistrarGeneral">
                                <div class="form-row" style="margin-top: -8px;" >
                                    <div class="col-md-2">
                                        <input type="hidden" id="__ID_USER" value="<?php echo $variable; ?>">
                                        <input type="hidden" id="__ID_DATOS_CLIENTE">
                                        <label class="label-texto">Tipo Documento <small id="cbxTipoDocumentoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
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
                                        <label class="label-texto">Documento <small id="txtDocumentoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                        <input id="txtDocumento" class="caja-texto" maxlength="20" placeholder="# Documento" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="label-texto">País Emisor Documento <small id="cbxPaisEmisorDocumentoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
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
                                        <label class="label-texto">Nacionalidad <small id="cbxNacionalidadHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                        <select id="cbxNacionalidad" class="cbx-texto">
                                            <option value="" selected="true" disabled="disabled">Seleccione...</option>
                                            <?php
                                                $Nacionalidad = new ControllerCategorias();
                                                $VerNac = $Nacionalidad->VerNacionalidad();
                                                foreach ($VerNac as $Nac) {
                                            ?>
                                            <option value="<?php echo $Nac['ID']; ?>"><?php echo $Nac['Nombre']; ?></option>
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
                                            <option value="<?php echo $gen['ID']; ?>"><?php echo $gen['Nombre']; ?></option>
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
                                            <option value="<?php echo $estado_civil['ID']; ?>"><?php echo $estado_civil['Nombre']; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col-md">
                                        <label class="label-texto">Código Cliente <small id="txtCodigoClienteHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                        <input type="text" id="txtCodigoCliente" class="caja-texto" maxlength="10" placeholder="Ejm: 2022000001" required>
                                        <input type="hidden" id="txtCodigoAnio" class="caja-texto">
                                        <input type="hidden" id="txtCodigoCorrelativo" class="caja-texto">
                                    </div>

                                    <div class="col-md">
                                        <label class="label-texto">Apellido Paterno <small id="txtApellidoPaternoHtml"  class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                        <input type="text" id="txtApellidoPaterno" class="caja-texto" placeholder="Ejm: Morales" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    </div>

                                    <div class="col-md">
                                        <label class="label-texto">Apellido Materno <small id="txtApellidoMaternoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                        <input type="text" id="txtApellidoMaterno" class="caja-texto" placeholder="Ejm: Gomez" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    </div>

                                    <div class="col-md">
                                        <label class="label-texto">Nombres <small id="txtNombresHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                        <input type="text" id="txtNombres" class="caja-texto" placeholder="Ejm: Julio Adrian" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                    </div>

                                    <div class="col-md">
                                        <label class="col-md label-texto-sm">Documento <small>(DNI/Carnet Extr./Otros)</small></label>
                                        <form class="col-md mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                                            <div class="col-md" style="margin-left: -7px;">
                                                <input type="file" id="file_documentoCliente"  name="file_documentoCliente" accept=".pdf">
                                                <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </fieldset>
                            <div class="form-row ">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Datos Contacto</legend>
                                        <div class="form-row" style="margin-top: -8px;"
                                            id="formularioRegistrarContacto">
                                            <div class="col-md-2">
                                                <label class="label-texto">Teléfono:</label>
                                                <input maxlength="9" id="txtTelefono" type="text" class="caja-texto" placeholder="Teléfono">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="label-texto">Celular 1: <small id="txtCelularHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                <input maxlength="9" id="txtCelular" type="text" class="caja-texto" placeholder="Celular">
                                            </div>
                                            <div class="col">
                                                <label class="label-texto">Celular 2:</label>
                                                <input maxlength="9" id="txtCelular2" type="text" class="caja-texto" placeholder="Celular">
                                            </div>
                                            <div class="col-6">
                                                <label class="label-texto">Email <small id="txtCorreoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                <input maxlength="50" id="txtCorreo" type="email" class="caja-texto" placeholder="Ejm: Contable@acg.com.pe">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Datos Nacimiento</legend>
                                        <div class="form-row" style="margin-top: -8px;"
                                            id="formularioRegistrarNacimiento">
                                            <div class="col">
                                                <label class="label-texto">Fecha <small id="txtFechaNaciminetoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                <input id="txtFechaNacimineto" type="date" min="1921-01-01" max="2007-12-31" class="caja-texto">
                                            </div>
                                            <div class="col">
                                                <label class="label-texto">País</label>
                                                <select id="cbxPaisNacimiento" class="cbx-texto">
                                                    <option selected="true" value="" disabled="disabled">Seleccione...</option>
                                                    <?php
                                                        $Pais = new ControllerCategorias();
                                                        $VerPais = $Pais->VerPais();
                                                        foreach ($VerPais as $pais) {
                                                    ?>
                                                    <option value="<?php echo $pais['ID']; ?>"><?php echo $pais['Nombre']; ?></option>
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
                                <div id="formularioRegistrarDomicilio">
                                    <div class="form-row" style="margin-top: -8px;">
                                        <div class="col">
                                            <label class="label-texto">Departamento <small id="cbxDepartamentoDirHtml"
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
                                            <input maxlength="80" id="txtNombreVia" type="text" class="caja-texto"
                                                placeholder="Nombre Vía">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="label-texto">N° Vía:</label>
                                            <input maxlength="4" id="txtNroVia" type="text" class="caja-texto"
                                                placeholder="N° Vía">
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
                                                <option value="<?php echo $zona['ID']; ?>">
                                                    <?php echo $zona['Nombre']; ?>
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
                                            <input maxlength="4" id="txtEtapa" type="text" class="caja-texto"
                                                placeholder="Etapa">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Dpto:</label>
                                            <input maxlength="4" id="txtNroDpto" type="text" class="caja-texto"
                                                placeholder="N° Dpto">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Interior:</label>
                                            <input maxlength="4" id="txtInterior" type="text" class="caja-texto"
                                                placeholder="N° Interior">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Mz:</label>
                                            <input maxlength="4" id="txtMz" type="text" class="caja-texto"
                                                placeholder="Mz">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Lt:</label>
                                            <input maxlength="4" id="txtLt" type="text" class="caja-texto"
                                                placeholder="Lt">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Km:</label>
                                            <input maxlength="4" id="txtKm" type="text" class="caja-texto"
                                                placeholder="Km">
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Block:</label>
                                            <input maxlength="4" id="txtBlock" type="text" class="caja-texto"
                                                placeholder="Block">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div id="contenido_lista" style="display:none;">
                        <!-- SECCIÓN DE BÚSQUEDA DE DATOS -->
                        <div class="fn-frm-dt mt-3" id="campo-busqueda">
                            <label class="titulo-cont">FILTROS DE B&Uacute;SQUEDA</label>
                            <div class="p-campos">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label class="label-texto">Cliente</label>
                                        <select id="txtdocumentoFiltro" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                                <option selected="true" value="" disabled="disabled">TODOS</option>
                                                <?php
                                                    $Clientes = new ControllerCategorias();
                                                    $ClientesVer = $Clientes->VerClientesBusqueda();
                                                    foreach ($ClientesVer as $Cliente) {
                                                ?>
                                                <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                <?php echo $Cliente['Nombre'].' - '.$Cliente['ID']; ?>
                                                </option>
                                                <?php }?>
                                        </select>
                                    </div>
                                    <div class="col" hidden>
                                        <label class="label-texto">Apellidos y Nombres:</label>
                                        <input type="Text" id="txtNombreApellidoFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md-3" style=" margin-top: 15px;">
                                        <button class="btn btn-registro-success" id="btnBuscar"><i
                                                class="fas fa-search"></i> Buscar</button>
                                        <button class="btn btn-registro-primary" id="btnTodos"><i
                                                class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <br>
                            <div class="table-resp">
                                <div class="table-responsive scroll-table">
                                    <table class="table table-striped table-bordered" id="tableRegistroReportes" style="display: none;">
                                        <thead class="cabecera">
                                            <tr>
                                                <th>CODIGO</th>
                                                <th>DOCUMENTO</th>
                                                <th>APELLIDOS</th>
                                                <th>NOMBRES</th>
                                                <th>FECHA NACIMIENTO</th>
                                                <th>CORREO</th>
                                                <th>CELULAR/TELÉFONO</th>
                                                <th>VENDEDOR</th>
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">
                                        </tbody>
                                    </table>
                                    <div style="margin: 36px;"> </div>
                                    <table id="tablaDatosCliente" class="table table-striped table-bordered table-hover w-100">
                                        <thead class="cabecera">
                                            <tr>
                                                <th>ACCIONES</th>
                                                <th>CÓDIGO</th>
                                                <th>DOCUMENTO</th>
                                                <th>NOMBRES Y APELLIDOS</th>
                                                <th>FECHA NAC.</th>
                                                <th>EMAIL</th>
                                                <th>CELULAR / TELÉFONO</th>
                                                <th>ADJUNTO DOC.</th>                                                
                                                <th style="text-align: center;">ESTADO</th>
                                                <th>VENDEDOR</th>
                                                <th>REGISTRO</th>
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" id="__SECTOR_EMPRESA">
                    
                    
                   

                    <!-- POP UP REGISTRO EMPLEADO -->
                    <div class="modal fade" id="modalRegistrar" tabindex="-1" role="dialog" data-backdrop="static"
                        aria-labelledby="myModalLabel">
                        <?php
                       // require_once "pop-up/M02SM01_POPUP_RegistroCliente.php";
                        ?>
                    </div>

                    <!-- POP UP ACTUALIZAR EMPLEADO -->
                    <div class="modal fade" id="modalActualizar" tabindex="-1" role="dialog" data-backdrop="static"
                        aria-labelledby="myModalLabel">
                        <?php
                      // require_once "pop-up/M02SM01_POPUP_ActualizarCliente.php";
                        ?>
                    </div>


                    <!-- POP UP ELIMINAR EMPLEADO -->
                    <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" data-backdrop="static"
                        aria-labelledby="myModalLabel">
                        <?php
                       // require_once "pop-up/M02SM01_POPUP_EliminarPersonal.php";
                        ?>
                    </div>

                    <!-- POP UP ESTADO REGISTRO EMPLEADO -->
                    <div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <?php
                        //require_once "pop-up/M02SM01_POPUP_EstadoPersonal.php";
                        ?>
                    </div>

                    <!-- POP UP NOTIFICACION DE REGISTROS -->
                    <div class="modal fade" id="modalNotificacion" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <?php
                       // require_once "pop-up/M02SM01_POPUP_Notificacion.php";
                        ?>
                    </div>

                    <!-- POP UP VER DOCUMENTOS -->
                    <div class="modal fade" id="modalVerDocumento" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel">
                        <?php
                         require_once "pop-up/M02SM01_POPUP_VerDocumento.php";
                        ?>
                    </div>
                </div>

            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->

    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <!-- <script src="../dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files -->
    <script src="../../assets/libs/flot/excanvas.js"></script>
    <script src="../../assets/libs/flot/jquery.flot.js"></script>
    <script src="../../assets/libs/flot/jquery.flot.pie.js"></script>
    <script src="../../assets/libs/flot/jquery.flot.time.js"></script>
    <script src="../../assets/libs/flot/jquery.flot.stack.js"></script>
    <script src="../../assets/libs/flot/jquery.flot.crosshair.js"></script>
    <script src="../../assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="../../dist/js/pages/chart/chart-page-init.js"></script>

    <!-- datatables JS paginado ajax -->
    <script src="../../datatables/paginado/datatables.bundle.js" type="text/javascript"></script>
    <!-- datatables JS -->
    <script src="../../datatables/datatables.min.js " type="text/javascript"></script>

    <!-- datatables JS SELECCIONA FILA -->
    <script src='../../datatables/select/js/dataTables.select.min.js'></script>
    <!-- datatables JS RECORRE FILA CON TECLADO -->
    <script src='../../datatables/paginado/dataTables.keyTable.min.js'></script>

    <!-- para usar botones en datatables JS -->
    <script src="../../datatables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="../../datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="../../datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="../../datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="../../datatables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
    <!-- c¨®digo JS prop¨¬o-->
    <script type="text/javascript" src="../../main.js"></script>

    <script src="../../js/M02_Clientes/M02JS01_RegistroCliente/M02JS01_Index.js?v=1.1.1"></script>

    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">
</body>

</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#txtdocumentoFiltro').select2();
	});
</script>