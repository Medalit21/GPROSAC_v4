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
    <title><?php echo $NAME_APP; ?></title>
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

    <script src="../../code/highcharts.js"></script>
    <script src="../../code/modules/exporting.js"></script>
    <script src="../../code/modules/export-data.js"></script>
    <script src="../../code/modules/accessibility.js"></script>

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
		}
		
		require_once "../../../config/configuracion.php";
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
    </div>-->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <?php include_once "../../recursos/header.php"; ?>
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
                <?php include_once "../../recursos/menu.php"; ?>
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
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Cobranzas</a></li>
                    <li><a class="enlace" href="M04SM02_Pagos.php?Vsr=<?php echo $user_sesion; ?>">Pagos</a></li>
                    <li><a class="enlace" href="M04SM01_NuevoPago.php?Vsr=<?php echo $user_sesion; ?>">Nuevo Pago</a></li>
                </ol>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <input type="hidden" id="txtUSR" value="<?php echo $user_sesion; ?>">
                        <div class="row" id="PanelFiltros">
                            <div class="col-md"><br>
                                <label class="label-texto">Documento Cliente </label>
                                <!--<input type="text" id="txtFiltroDocumentoEC" class="caja-texto" placeholder="Nro Documento">-->
                                <select id="txtFiltroDocumentoEC" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">TODOS</option>
                                        <?php
                                            $Clientes = new ControllerCategorias();
                                            $ClientesVer = $Clientes->VerClientesBusqueda();
                                            foreach ($ClientesVer as $Cliente) {
                                        ?>
                                        <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                        <?php echo $Cliente['Nombre']; ?>
                                        </option>
                                        <?php }?>
                                </select>
                            </div>
                            
                            <div class="col-md"><br>
                                <label class="label-texto">Proyecto </label>
                                <select id="bxFiltroProyectoEC" class="cbx-texto">
                                    <?php
                                        $Proyectos = new ControllerCategorias();
                                        $ProyectoVer = $Proyectos->VerProyectos();
                                        foreach ($ProyectoVer as $Proy) {
                                    ?>
                                    <option value="<?php echo $Proy['ID']; ?>">
                                    <?php echo $Proy['Nombre']; ?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
    
                            <div class="col-md"><br>
                                <label class="label-texto">Zona </label>
                                <select id="bxFiltroZonaEC" class="cbx-texto">
                                    <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                </select>
                            </div>
    
                            <div class="col-md"><br>
                                <label class="label-texto">Manzana </label>
                                <select id="bxFiltroManzanaEC" class="cbx-texto">
                                    <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                </select>
                            </div>
    
                            <div class="col-md"><br>
                                <label class="label-texto">Lote </label>
                                <select id="bxFiltroLoteEC" class="cbx-texto">
                                    <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                </select>
                            </div>					
                        </div>
                        
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-4 text-center mt-1" id="PanelBotons">
                                <button id="btnBuscar" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button>
                                <button id="btnLimpiar" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                <button id="btnNewPago" type="button" class="btn btn-registro"><i class="fas fa-file-alt"></i> Nuevo</button>
                            </div>
                        </div>
                        
                        <div class="botones-acciones-2" hidden>
                            <button id="btnNuevoPago" type="button" class="btn btn-registro"><i class="fas fa-file-alt"></i> Nuevo</button>
                             <button id="btnPagosRealizados" type="button" class="btn btn-registro-success" style="width: 150px;"><i class="fas fa-file-alt"></i> Pagos Realizados</button>
                        </div>

                        <div class="row">
                            <div class="col-md-12" id="contenido_registro" style="display:block;">
                                <fieldset>
                                    <legend>Datos Cliente</legend>
                                    <input type="hidden" id="txtTipoDocCliente"/>
                                    <input type="hidden" id="txtNacionalidadCliente"/>

                                    <div class="row row-0" style="margin-top: -12px;" id="formularioRegistrarGeneralCliente">
                                        <div class="col-md-12 row">
                                            <label class="col-md-1 label-texto-sm">Documento:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtDocCliente"
                                                maxlength="15" type="text" value="" disabled="">
                                            </div>
                                            <label class="col-md-1 label-texto-sm">Nombres:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtNomCliente" type="text"
                                                value="" disabled="" readonly="">
                                            </div>
                                            <label class="col-md-1 label-texto-sm">A. Paterno:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtApePaternoCliente"
                                                type="text" value="" disabled="" readonly="">
                                            </div>
                                            <label class="col-md-1 label-texto-sm">A. Materno:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtApeMaternoCliente"
                                                type="text" value="" disabled="" readonly="">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12" id="contenedorReservas">
                                <fieldset>
                                    <legend>Datos de Pago </legend>
                                    <div class="row row-0" id="formularioRegi">

                                        <div class="col-md-12 row">
                                            <label class="col-md-1 label-texto-sm">Propiedad:</label>
                                            <div class="col-md-5">
                                                <select id="bxListaLote" class="cbx-texto">
                                                    <option selected="true" disabled="disabled">Seleccionar..</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2" hidden>
                                                <input class="caja-texto tamano-text-10" id="txtidVenta" type="text">
                                            </div>
                                        </div>    

                                        <div class="col-md-12 row" style="margin-top: 2%;">
                                            <label class="col-md-1 label-texto-sm">Nro de Cuota:</label>
                                            <div class="col-md-2">
                                                <select id="bxNroCuotas" class="cbx-texto">
                                                    <option selected="true" disabled="disabled">Seleccionar..</option>
                                                </select>
                                            </div>                                            

                                            <label class="col-md-1 label-texto-sm">Medio de Pago:</label>
                                            <div class="col-md-2">
                                                <select id="bxMedioPago" class="cbx-texto">
                                                    <option selected="true" disabled="disabled">Seleccionar..</option>
                                                    <?php
                                                    $MedioPago = new ControllerCategorias();
                                                    $verMedioPago = $MedioPago->VerMedioPago();
                                                    foreach ($verMedioPago as $TMedioPago) {
                                                        ?>
                                                        <option value="<?php echo $TMedioPago['ID']; ?>"><?php echo $TMedioPago['Nombre']; ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Tipo de Constancia:</label>    
                                            <div class="col-md-2">
                                                <select id="bxTipoComprobante" class="cbx-texto" disabled>
                                                    <option selected="true" disabled="disabled">Seleccionar..</option>
                                                    <?php
                                                    $TipoComprobanteVenta = new ControllerCategorias();
                                                    $verTipoComprobanteVenta = $TipoComprobanteVenta->VerTipoComprobanteVentas();
                                                    foreach ($verTipoComprobanteVenta as $TTipoComprobanteVenta) {
                                                        ?>
                                                        <option value="<?php echo $TTipoComprobanteVenta['ID']; ?>"><?php echo $TTipoComprobanteVenta['Nombre']; ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Importe a pagar: ($) </label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtMontoPagar" type="text" placeholder="0.00" value="" disabled>                                          
                                            </div>
                                            <br><br>       
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-12 row">

                                            <label class="col-md-1 label-texto-sm">Tipo de Moneda:</label>           
                                            <div class="col-md-2">
                                                <select id="bxTipoMoneda" class="cbx-texto">
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

                                             <label class="col-md-1 label-texto-sm">Tipo de Cambio:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtTipoCambio" name="txtTipoCambio" type="number" placeholder="0.00">                                                                                        
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Importe pagado:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtMontoPagado" placeholder="0.00" type="text">
                                                                                               
                                            </div>
                                            
                                            <label class="col-md-1 label-texto-sm">Total pagado: ($) </label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtTotalPagado" placeholder="0.00" type="text">
                                            </div>

                                           
                                            <br><br>     
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-12 row">
                                            
                                            <label class="col-md-1 label-texto-sm">Agencia bancaria:</label>    
                                            <div class="col-md-2">
                                                <select id="bxAgenciaBancaria" class="cbx-texto">
                                                    <option selected="true" disabled="disabled">Seleccionar..</option>
                                                    <?php
                                                    $Bancos = new ControllerCategorias();
                                                    $verBancos = $Bancos->VerBancos();
                                                    foreach ($verBancos as $TBancos) {
                                                        ?>
                                                        <option value="<?php echo $TBancos['ID']; ?>"><?php echo $TBancos['Nombre']; ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>  
                                            
                                            <label class="col-md-1 label-texto-sm">Flujo de Caja:</label>           
                                            <div class="col-md-2">
                                                <select id="bxFlujoCaja" class="cbx-texto">
                                                    <?php
                                                    $Flujo = new ControllerCategorias();
                                                    $verFlujo = $Flujo->VerFlujoCaja();
                                                    foreach ($verFlujo as $TFC) {
                                                        ?>
                                                        <option value="<?php echo $TFC['ID']; ?>"><?php echo $TFC['Nombre']; ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                            
                                            <label class="col-md-1 label-texto-sm">Fecha de pago:</label>           
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtFechaPago" type="date" value="">
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Nro de Operación:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtNumeroOperacion" type="text" value="">                                                                                        
                                            </div> 
                                        </div>
                                    </div><br>
                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-12 row">
                                           <label class="col-md-1 label-texto-sm">Voucher Pago <small>(JPG / JPEG / PNG / PDF)</small></label>
                                            <form class="col-md mb-3" action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                                                <div class="col-md" style="margin-left: -7px;">
                                                    <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                                                    <input type="file" id="constancia" name="constancia" accept=".pdf, .jpg, .jpeg, .png">
                                                    <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                                                </div>
                                            </form>
                                            <br><br><br>    
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-12 row">
                                             <div class="col-md-12" style="text-align: center;">
                                                <button id="btnPagar" type="button" class="btn btn-registro-success"><i class="fas fa-save"></i> Pagar</button>                                          
                                            </div>
                                            <br><br>     
                                        </div>
                                    </div>
                                </fieldset>
                                <br><br><br>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<!--<script src="../../assets/libs/jquery/dist/jquery.min.js"></script>-->
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
<!-- <script src="../../dist/js/pages/dashboards/dashboard1.js"></script> -->
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

<script src="../../js/M04_Cobranzas/M04JS01_Cobranzas/M04JS01_NuevoPago.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
<input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#txtFiltroDocumentoEC').select2();
	});
</script>