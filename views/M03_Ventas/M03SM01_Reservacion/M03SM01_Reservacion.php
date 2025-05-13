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
    <title><?php echo $NAME_APP.' - Reservas'; ?></title>
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
    <!-- LIBRERIAS NOTIFICACIONES -->

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
            $variable = encrypt($_SESSION['usu'],"123");
            $valor_user = $_SESSION['usu'];
            $_SESSION['variable_user'] = $valor_user;
        }
		
		require_once "../../../config/conexion_2.php";
		require_once "../../../config/control_sesion.php";
		require_once "../../../controllers/ControllerCategorias.php";
		$user_sesion = encrypt($valor_user,"123");
        $IdLoteReservar = "ninguno";
        $IdCliente = "ninguno";
		if(!empty($_GET['l'])){    
			$IdLoteReservar = $_GET['l'];
			$IdLoteReservar = decrypt($IdLoteReservar, "123");
		}
        if(!empty($_GET['c'])){    
			$IdCliente = $_GET['c'];
		}
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
                    <li><a class="enlace" href="javascript:void(0)">Ventas</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Reserva</a></li>
                    <li class="bread-opcion">
                        <div class="row">
                            <div class="page-breadcrumb">
                                <div class="col-12 d-flex no-block align-items-right">
                                    <div class="ml-auto text-right">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="../../home.php">Inicio</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a
                                                        href="../../../index.php">
                                                        Salir</a></li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
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
                            <button id="nuevo" type="button" class="btn btn-registro"><i class="fas fa-file-alt"></i>
                                Nuevo</button>
                            <button id="modificar" type="button" class="btn btn-registro"><i class="fas fa-edit"></i>
                                Modificar</button>
                            <button id="guardar" type="button" class="btn btn-registro" disabled=""><i
                                    class="fas fa-save"></i> Guardar</button>
                            <button id="cancelar" type="button" class="btn btn-registro"><i
                                    class="fas fa-minus-circle"></i> Cancelar</button>
                            <button id="eliminar" type="button" class="btn btn-registro"><i class="fas fa-trash"></i>
                                Elminar</button>
                            <button id="busqueda_avanzada" type="button" class="btn btn-registro"><i
                                    class="fas fa-list"></i>
                                Lista</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12" id="contenido_registro" style="display:none;">
                            
                            <div class="col-lg-12 col-md-12">
                                <label class="titulo-cont">Cliente :</label>
                                <a href="javascript:void(0)" onclick="abrirModalCliente();" id="btnAgregarCliente" name="btnAgregarCliente" class="add-btn"> [+ Nuevo]</a>
                            </div>
                            <div class="row col-lg-12 col-md-12">
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <select id="txtDocumentoCliente" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                        <option selected="true" value="" disabled="disabled">Buscar cliente</option>
                                        <?php
                                        $Clientes = new ControllerCategorias();
                                        $ClientesVer = $Clientes->VerClientesBusqueda();
                                        foreach ($ClientesVer as $Cliente) {
                                        ?>
                                        <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;"><?php echo $Cliente['Nombre'].' - '.$Cliente['ID']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-md-3 col-sm-6">
                                    <button type="button" class="btn btn-registro" id="btnBuscarCliente" style="margin-top:1px" disabled=""> <i class="fas fa-search" aria-hidden="true"></i> Buscar</button>

									<button type="button" class="btn btn-registro-primary" id="btnLimpiarFiltroCliente"> <i class="fas fa-sync-alt" aria-hidden="true"></i> Limpiar</button>
									
                                </div>
                                
                                <div class="col-lg-4 col-md-6"></div>
                            </div>
                            <br>
                            <fieldset>
                                <legend>Datos Cliente</legend>
                                <input type="hidden" id="__ID_LOTE_RESERVAR" value="<?php echo $IdLoteReservar?>" />
                                <input id="__ID_CLIENTE" type="hidden" value="<?php echo $IdCliente; ?>">
                                <input id="__ID_RESERVACION" type="hidden" value="">
                                <input id="___DIAS_RESERVA_REFERENCIAL" type="hidden" value="">
								<input type="hidden" name="__IDUSUARIO" id="__IDUSUARIO" value="<?php echo $variable; ?>">

                                <div class="row row-0" style="margin-top: -12px;"
                                    id="formularioRegistrarGeneralCliente">
                                    <div class="col-md-12 row">
                                        <select id="cbxTipoDocumento" class="col-md-1 cbx-texto" hidden>
                                            <option selected="true" value="" disabled>Seleccione...
                                                <?php
													$tipoDoc = new ControllerCategorias();
													$VerTiposDoc = $tipoDoc->VerTipoDocumento();
													foreach ($VerTiposDoc as $td) {
												?>
                                            <option value="<?php echo $td['ID']; ?>">
                                                <?php echo $td['Nombre']; ?></option>
                                            <?php }?>

                                        </select>
                                        <label class="col-md-1 label-texto-sm">Nro documento:</label>
                                        <div class="col-lg-2 col-md-3 ">
                                            <input class="caja-texto tamano-text-10" id="txtNroDocumentoCliente" type="text" value="" disabled="">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Nombres:</label>
                                        <div class="col-lg-2 col-md-3 ">
                                            <input class="caja-texto tamano-text-10" id="txtNombreCliente" type="text" value="" disabled="">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">A. Paterno:</label>
                                        <div class="col-lg-2 col-md-3 ">
                                            <input class="caja-texto tamano-text-10" id="txtApellidoPaternoCliente" type="text" value="" disabled="">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">A. Materno:</label>
                                        <div class="col-lg-2 col-md-3 ">
                                            <input class="caja-texto tamano-text-10" id="txtApellidoMaternoCliente" type="text" value="" disabled="" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Datos Lote</legend>

                                <div class="row row-0" style="margin-top: -12px;" id="formularioRegistrarGeneralLote">
                                    <div class="col-md-12 row">
                                        <label class="col-md-1 label-texto-sm">Proyecto:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxProyecto" class="cbx-texto">
                                                <?php
                                                    $verProyecto = new ControllerCategorias();
                                                    $verProyectos = $verProyecto->VerProyectos();
                                                    foreach ($verProyectos as $item) {
                                                ?>
                                                <option value="<?php echo $item['ID']; ?>">
                                                    <?php echo $item['Nombre']; ?>
                                                </option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Zona:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxZona" class="cbx-texto">

                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Manzana:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxManzana" class="cbx-texto">
                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Lote:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxLote" class="cbx-texto">
                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Área (M²):</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtArea" type="text" value=""
                                                disabled="" readonly="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Tipo Moneda:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtTipoMonedaLote" type="text"
                                                value="" disabled="" readonly="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">V. Lote - Casa:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtValorLoteCasa" type="text"
                                                value="" disabled="" readonly="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">V. Lote - Solo:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtValorLoteSolo" type="text"
                                                value="" disabled="" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Información de Reserva</legend>

                                <div class="row row-0" style="margin-top: -12px;" id="formularioRegistrarReserva">
                                    <div class="col-md-12 row">
                                        <label class="col-md-1 label-texto-sm">Tipo Moneda</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxTipoMonedaReserva" class="cbx-texto">
                                                <option selected="true" value="" disabled="disabled">Seleccione...
                                                </option>
                                                <?php
                                                    $VerTipoMonedaSigl = new ControllerCategorias();
                                                    $VerTipoMonedaSig = $VerTipoMonedaSigl->VerTipoMonedaSigla();
                                                    foreach ($VerTipoMonedaSig as $item) {
                                                ?>
                                                <option value="<?php echo $item['ID']; ?>">
                                                    <?php echo $item['Nombre']; ?>
                                                </option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Tipo Cambio</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto" id="txtTipoCambio" type="number">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Monto Pagado</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto CurrencyInput" id="txtMontoPagado" type="text" value="0.00" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Total Reserva</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto CurrencyInput" id="txtMontoReserva" type="text" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" readonly>
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Tipo Casa</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxTipoCasa" class="cbx-texto">
                                                <option selected="true" value="">Ninguno</option>
                                                <?php
                                                    $VerTipoC = new ControllerCategorias();
                                                    $VerTipoCa = $VerTipoC->VerTipoCasa();
                                                    foreach ($VerTipoCa as $item) {
                                                ?>
                                                <option value="<?php echo $item['ID']; ?>"><?php echo $item['Nombre']; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Fecha Inicio</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtDesdeReserva" type="date"
                                                value="" disabled="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Fecha Fin:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtHastaReserva" type="date" value="" disabled="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Precio negociación</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxTipoMonedaPrecio" class="col-md-6 cbx-texto">
                                                <?php
                                                    $VerTipoMonedaSigl = new ControllerCategorias();
                                                    $VerTipoMonedaSig = $VerTipoMonedaSigl->VerTipoMonedaSigla();
                                                    foreach ($VerTipoMonedaSig as $item) {
                                                ?>
                                                <option value="<?php echo $item['ID']; ?>"><?php echo $item['Nombre']; ?></option>
                                                <?php }?>
                                            </select>
                                            <input class="caja-texto tamano-text-5 CurrencyInput" id="txtPrecioNegocio" type="text" value="0.00" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Medio de Pago</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxMedioPago" class="cbx-texto">
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
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxTipoComprobante" class="cbx-texto">
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
                                            
                                        <label class="col-md-1 label-texto-sm">Agencia bancaria:</label>    
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxAgenciaBancaria" class="cbx-texto">
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
                                        
                                        <label class="col-md-1 label-texto-sm">Nro de Operación:</label>
                                        <div class="col-lg-2 col-md-3">
                                            <input class="caja-texto tamano-text-10" id="txtNumeroOperacion" type="text" value="">                                                                                        
                                        </div> 
                                        
                                        <label class="col-md-1 label-texto-sm">Vendedor</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select id="cbxVendedor" class="cbx-texto">
                                                <option selected="true" value="" disabled="">Seleccione...</option>
                                                <?php
                                                $VerTipoMonedas = new ControllerCategorias();
                                                $verTipoMoneda = $VerTipoMonedas->VerFiltroVendedor();
                                                foreach ($verTipoMoneda as $item) {
                                                    ?>
                                                    <option value="<?php echo $item['ID']; ?>">
                                                        <?php echo $item['Nombre']; ?>
                                                    </option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Voucher de Pago</label>
                                        <div class="col-lg-7 col-md-7">
                                            <form action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                                                <!--<label for="fileSubirAdjuntoVenta" class="sr-only"><i class="fas fa-upload"></i> Seleccionar Documento (.pdf)</label>-->
                                                <input type="file" class="caja-texto" id="ficheroVoucher" name="ficheroVoucher" accept=".pdf, .png, .jpg, .jpeg">
                                                <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">   
                                            </form>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 5px"></div>
                                        <label class="col-md-1 label-texto-sm">Descripción</label>
                                        <div class="col-md-11">
                                            <textarea class="caja-texto tamano-text-10" id="txtDescripcion" maxlength="200" rows="2" style="resize: none;height:auto;margin-bottom: 0px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div id="contenido_lista" style="display:none;">
                        <div class="form-row mt-3">
                            <div class="col-md-12">
                                <label class="titulo-cont">FILTROS DE B&Uacute;SQUEDA</label>
                            </div>
                            <div class="col-md-2">
                                <label class="label-texto">Cliente</label>
                                <select id="txtDocumentoFiltro" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                            <div class="col-md" hidden>
                                <label class="label-texto">Tipo Casa:</label>
                                <select id="cbxTipoCasaFiltro" class="cbx-texto">
                                    <option selected="true" value="">Todos
                                    </option>
                                    <?php
										$VerTipoC = new ControllerCategorias();
										$VerTipoCa = $VerTipoC->VerTipoCasa();
										foreach ($VerTipoCa as $item) {
									?>
                                    <option value="<?php echo $item['ID']; ?>">
                                        <?php echo $item['Nombre']; ?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md label-texto">Proyecto</label>
                                <select id="bxFiltroProyectoReserva" class="cbx-texto">
                                        <?php
                                            $tipoDoc = new ControllerCategorias();
                                            $VerTiposDoc = $tipoDoc->VerProyectos();
                                            foreach ($VerTiposDoc as $td) {
                                        ?>
                                    <option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option><?php }?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="col-md label-texto">Zona</label>
                                <select id="bxFiltroZonaReserva" class="cbx-texto">
                                    <option selected="true" value="" disabled="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="col-md label-texto">Manzana</label>
                                <select id="bxFiltroManzanaReserva" class="cbx-texto">
                                    <option selected="true" value="" disabled="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="col-md label-texto">Lote</label>
                                <select id="bxFiltroLoteReserva" class="cbx-texto">
                                    <option selected="true" value="" disabled="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="label-texto">Desde:</label>
                                <input type="date" id="txtDesdeFiltro" class="caja-texto"
                                    placeholder="Documento Cliente" value="">
                            </div>
                            <div class="col-md-1">
                                <label class="label-texto">Hasta: </label>
                                <input type="date" id="txtHastaFiltro" class="caja-texto"
                                    placeholder="Documento Cliente" value="">
                            </div>
                            <div class="col-md text-left" style=" margin-top: 11px;">
                                <button class="btn btn-registro-success" id="btnBuscarRegistro"
                                    name="btnBuscarRegistro"><i class="fas fa-search"></i> Buscar</button>
                                <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiar"><i
                                        class="fas fa-sync-alt"></i> Limpiar</button>
                            </div>
                        </div>
                        <br>
                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <div class="table-responsive scroll-table">
                                <table class="table table-striped table-bordered" id="tableRegistroReportes" style="display: none;">
                                    <thead class="cabecera text-center">
                                        <tr>
                                            <th ROWSPAN=2>Acciones</th>
                                            <th ROWSPAN=2>Estado Venta</th>
                                            <th ROWSPAN=2>Cliente</th>
                                            <th ROWSPAN=2>Mz - Lote</th>
                                            <th ROWSPAN=2>Área</th>
                                            <th COLSPAN=2>Precio Negociado</th>
                                            <th ROWSPAN=2>Inicio </th>
                                            <th ROWSPAN=2>Fin</th>
                                            <th COLSPAN=2>Monto Reserva</th>
                                            <th ROWSPAN=2>Tipo Casa</th> 
                                            <th ROWSPAN=2>Voucher Pago</th>                                           
                                            <th ROWSPAN=2>Vendedor</th>
                                            <th ROWSPAN=2>Registro</th>                                            
                                        </tr>
                                        <tr>
                                            <th>moneda</th>
                                            <th>monto</th>
                                            <th>moneda</th>
                                            <th>monto</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                                <div style="margin: 36px;"> </div>
                                <table class="table table-striped table-bordered table-hover w-100" id="tableRegistroReservacion">
                                    <thead class="cabecera text-center">
                                        <tr>
                                            <th ROWSPAN=2>Acciones</th>
                                            <th ROWSPAN=2>Estado Venta</th>
                                            <th ROWSPAN=2>Cliente</th>
                                            <th ROWSPAN=2>Mz - Lote</th>
                                            <th ROWSPAN=2>Área</th>
                                            <th COLSPAN=2>Precio Negociado</th>
                                            <th ROWSPAN=2>Inicio </th>
                                            <th ROWSPAN=2>Fin</th>
                                            <th COLSPAN=2>Monto Reserva</th>
                                            <th ROWSPAN=2>Tipo Casa </th> 
                                            <th ROWSPAN=2>Voucher Pago</th>                                           
                                            <th ROWSPAN=2>Vendedor</th>
                                            <th ROWSPAN=2>Registro</th>                                            
                                        </tr>
                                        <tr>
                                            <th>moneda</th>
                                            <th>monto</th>
                                            <th>moneda</th>
                                            <th>monto</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- POP UP COOPROPIETARIOS -->
                <div class="modal fade" id="modalCopropietarios" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                        require_once "pop-up/M03MD01_POPUP_Coopropietarios.php";
                    ?>
                </div>
                
                <div class="modal fade" id="modalVerVoucher" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                        require_once "pop-up/M03MD02_POPUP_VerVoucher.php";
                    ?>
                </div>

                <div class="modal fade" id="modalClientes" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                        require_once "pop-up/M03MD03_POPUP_Cliente.php";
                    ?>
                </div>

            </div>
        </div>
         

    </div>
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

    <script src="../../js/M03_Ventas/M03JS01_Reservacion/M03JS01_Index.js?v=1.2.0"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/xlsx.full.min.js?v=1.1.1"></script>
</body>

</html>

<script type="text/javascript">
	$(document).ready(function(){
		$('#txtDocumentoFiltro').select2();
		$('#txtDocumentoCliente').select2();
	});
</script>