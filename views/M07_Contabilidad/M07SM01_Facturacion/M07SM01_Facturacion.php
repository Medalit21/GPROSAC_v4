<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php require_once "../../../config/configuracion.php"; ?>

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
    <!-- ESTILOS PRINCIPAL -->
    <link rel="stylesheet" type="text/css" href="../../css/estilos.css?v=<?php echo time(); ?>">
</head>

<body class="fond-back">

    <script src="../../code/highcharts.js"></script>
    <script src="../../code/modules/exporting.js"></script>
    <script src="../../code/modules/export-data.js"></script>
    <script src="../../code/modules/accessibility.js"></script>

    <?php
    session_start();
    include_once "../../../config/codificar.php";

    if (empty($_SESSION['usu'])) {
        $variable = $_GET['Vsr'];
        $user_doc = decrypt($variable, "123");
        $_SESSION['usu'] = $user_doc;
        $valor_user = $_SESSION['usu'];
        $_SESSION['variable_user'] = $valor_user;
    } else {
        $valor_user = $_SESSION['usu'];
        $_SESSION['variable_user'] = $valor_user;
    }

    require_once "../../../config/conexion_2.php";
    require_once "../../../config/control_sesion.php";
    require_once "../../../controllers/ControllerCategorias.php";
    $fecha = date('Y-m-d');
    $user_sesion = encrypt($valor_user, "123");
    $_GET['r'] = "";
    $_GET['l'] = "";
    if (!empty($_GET['l']) || !empty($_GET['r'])) {
        $IdReservaVenta = $_GET['r'];
        $IdReservaVenta = decrypt($IdReservaVenta, "123");
        $IdLoteVenta = $_GET['l'];
        $IdLoteVenta = decrypt($IdLoteVenta, "123");
    }
    $IdCliente = "ninguno";
    if (!empty($_GET['c'])) {
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
            <div class="breadcrumb-ancho mt-1">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Contabilidad</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Facturaci&oacute;n Electr&oacute;nica</a></li>
                    <li class="bread-opcion">
                        <div class="row">
                            <div class="page-breadcrumb">
                                <div class="col-12 d-flex no-block align-items-right">
                                    <div class="ml-auto text-right">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="../../home.php">Inicio</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a href="../../../index.php">Salir</a></li>
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
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#ComprobantesEmitidos" role="tab" data-toggle="tab">Comprobantes Facturados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#EmitirComprobantes" role="tab" data-toggle="tab">Emitir Nuevo Comprobante</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#EmitirOtrosConceptos" role="tab" data-toggle="tab">Emitir Otros Conceptos</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="ComprobantesEmitidos" style="opacity: 1;">
                        <div class="box box-primary">
                            <div id="contenido_lista">
                                <div class="form-row mt-3">
                                    <input type="hidden" name="txtUsuario" id="txtUsuario" value="<?php echo $valor_user; ?>">
                                    <div class="col-md-3">
                                        <label class="label-texto">Cliente</label>
                                        <select id="txtFiltroCliente2" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                            <option selected="true" value="" disabled="disabled">TODOS</option>
                                            <?php
                                            $Clientes = new ControllerCategorias();
                                            $ClientesVer = $Clientes->VerClientesBusqueda();
                                            foreach ($ClientesVer as $Cliente) {
                                            ?>
                                                <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Cliente['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Tipo Comprobante</label>
                                        <select id="txtFiltroTipoComprobante2" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                            <option selected="true" value="" disabled="disabled">Seleccionar</option>
                                            <?php
                                            $ComprobanteSunat = new ControllerCategorias();
                                            $ComprobanteSunatVer = $ComprobanteSunat->VerTipoComprobanteSunat();
                                            foreach ($ComprobanteSunatVer as $Comprobante) {
                                            ?>
                                                <option value="<?php echo $Comprobante['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Comprobante['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-1.5" style=" margin-top: 12px;">
                                        <button class="btn btn-registro-success" id="btnBuscarImpresos" name="btnBuscarImpresos"><i class="fas fa-search"></i> Buscar</button>
                                    </div>
                                    <div class="col-md-1.5" style=" margin-top: 12px;">
                                        <button class="btn btn-registro-primary" id="btnLimpiarFiltros" name="btnLimpiarFiltros"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="table-responsive scroll-table">
                                    <table class="table table-striped table-bordered table-hover w-100" id="TablaDocImpresos">
                                        <thead class="cabecera">
                                            <tr>
                                                <th>Acciones</th>
                                                <th>Tipo</th>
                                                <th>Fecha Emisi&oacute;n</th>
                                                <th>Serie</th>
                                                <th>Numero</th>
                                                <th>Cliente</th>
                                                <th>Igv</th>
                                                <th>Inafecto</th>
                                                <th>Total</th>
                                                <th>Comprobante</th>
                                                <th>Tipo Doc. Ref.</th>
                                                <th>Serie Ref.</th>
                                                <th>Numero Ref.</th>
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="EmitirComprobantes">
                        <div class="box box-primary">
                            <div id="contenido_lista">
                                <div class="col-md-12 row mt-3" id="PanlFiltros">
                                    <input type="hidden" name="txtUsuario" id="txtUsuario" value="<?php echo $valor_user; ?>">

                                    <div class="col-md-2">
                                        <label class="label-texto">Proyecto</label>
                                        <select id="txtFiltroProyecto" class="cbx-texto" style="width: 100%; font-size: 10px !important;">
                                            <?php
                                            $Valores = new ControllerCategorias();
                                            $ValoresVer = $Valores->VerProyectos();
                                            foreach ($ValoresVer as $respuesta) {
                                            ?>
                                                <option value="<?php echo $respuesta['ID']; ?>" style="font-size: 10px !important;">
                                                    <?php echo $respuesta['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="label-texto">Cliente</label>
                                        <select id="txtFiltroCliente" class="cbx-texto" style="width: 100%">
                                            <option selected="true" value="">TODOS</option>
                                            <?php
                                            $Clientes = new ControllerCategorias();
                                            $ClientesVer = $Clientes->VerClientesBusqueda();
                                            foreach ($ClientesVer as $Cliente) {
                                            ?>
                                                <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Cliente['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="label-texto">Propiedad</label>
                                        <select id="txtFiltroPropiedad" class="cbx-texto" style="width: 100%">
                                            <option selected="true" value="">TODOS</option>
                                            <?php
                                            $valores = new ControllerCategorias();
                                            $valoresVer = $valores->VerListadoPropiedades();
                                            foreach ($valoresVer as $respuesta) {
                                            ?>
                                                <option value="<?php echo $respuesta['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $respuesta['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="label-texto">Desde</label>
                                        <input type="date" id="txtFiltroDesde" class="caja-texto">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="label-texto">Hasta</label>
                                        <input type="date" id="txtFiltroHasta" class="caja-texto">
                                    </div>

                                    <div class="col-md-3" hidden>
                                        <label class="label-texto">Tipo Comprobante</label>
                                        <select id="txtFiltroTipoComprobante" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                            <?php
                                            $ComprobanteSunat = new ControllerCategorias();
                                            $ComprobanteSunatVer = $ComprobanteSunat->VerTipoComprobanteSunatImpr();
                                            foreach ($ComprobanteSunatVer as $Comprobante) {
                                            ?>
                                                <option value="<?php echo $Comprobante['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Comprobante['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>      
                                    
                                </div>
                                <div class="form-row d-flex justify-content-center">
                                    <div class="col-md-4 text-center mt-1" id="PanlBusqueda">
                                        <button class="btn btn-registro-success" id="btnBuscarPagoPendiente" name="btnBuscarPagoPendiente"><i class="fas fa-search"></i> Buscar</button>
                                        <button class="btn btn-registro-primary" id="btnLimpiarPagoPendiente" name="btnLimpiarPagoPendiente"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                                <div class="form-row mt-3" id="PanlBtnsAction" style="display: none;">
                                    <div class="col-md-1.5" id="panel_btn_nuevo">
                                        <button class="btn btn-registro" id="btnCancelarEmision" name="btnCancelarEmision"><i class="fas fa-sync-alt"></i> Nuevo</button>
                                    </div>

                                    <div class="col-md-2" id="panel_btn_bol" style="display: none;">
                                        <button class="btn btn-registro" id="btnIrBoleta" name="btnIrBoleta"><i class="fas fa-arrow-circle-right"></i> Ir a Boleta</button>
                                    </div>

                                    <div class="col-md-2" id="panel_btn_fac" style="display: none;">
                                        <button class="btn btn-registro-danger" id="btnIrFactura" name="btnIrFactura"><i class="fas fa-arrow-circle-right"></i> Ir a Factura</button>
                                    </div>
                                </div>
                            </div>    
                                                 
                            <div class="col-md-12">
                                <div class="table-responsive scroll-table">
                                    <div id="PanelRegistrosPagosValidadosGeneral">
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaPagosFacturacionGnral">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Acciones</th>
                                                    <th>Documento</th>
                                                    <th>Cliente</th>
                                                    <th>Lote</th>
                                                    <th>Letra</th>
                                                    <th>Fecha Pago</th>
                                                    <th>Pagado</th>
                                                    <th>Facturado</th>
                                                    <th>Por Facturar</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <div id="PanelRegistrosPagosValidadosEspecifico" style="display: none;">
                                        <input type="hidden" id="__clt">
                                        <input type="hidden" id="__prd">
                                        <input type="hidden" id="__tpc">
                                        <div class="col-md-12 row fond-etiqueta">
                                            <div class="col-md-6 row bajar-4">
                                                <label class="text-etiqueta">Cliente : </label>&nbsp;&nbsp;
                                                <label class="text-parraf" id="label_cliente"></label>
                                            </div>
                                            <div class="col-md-6 row bajar-4">
                                                <label class="text-etiqueta">Lote : </label>&nbsp;&nbsp;
                                                <label class="text-parraf" id="label_lote"></label>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaPagosFacturacion">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Agregar</th>
                                                    <th>Fecha Pago</th>
                                                    <th>Letra</th>
                                                    <th>TEA%</th>
                                                    <th>Inter&eacute;s</th>
                                                    <th>Capital</th>
                                                    <th>Moneda</th>
                                                    <th>Pagado</th>
                                                    <th>T.C.</th>
                                                    <th>Total</th>
                                                    <th>Total Emitido</th>
                                                    <th>Por Emitir</th>
                                                    <th>Estado Emisi&oacute;n</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <p><span class="importante">Leyenda: </span><span class="text-leyenda">El bot&oacute;n <a class="btn btn-edit-action"><i class="fas fa-plus-square"></i></a> es utilizado para asignar el importe a emitir.</span></p>
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- BOLETA ELECTRONICA -->
                            <div id="PanelBoletaElectronica" style="display: none;"><br>
                                <?php include_once "comprobantes/Facturacion_boleta.php"; ?>
                            </div>

                            <!-- FACTURA ELECTRONICA -->
                            <div id="PanelFacturaElectronica" style="display: none;"><br>
                                <?php include_once "comprobantes/Facturacion_factura.php"; ?>
                            </div>

                            <!-- NOTA DE CREDITO ELECTRONICA -->
                            <div id="PanelNotaCreditoElectronica" style="display: none;"><br>
                                <?php include_once "comprobantes/Facturacion_notaCredito.php"; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="EmitirOtrosConceptos">
                        <div class="box box-primary">
                            <div id="contenido_lista">
                                <div class="form-row mt-3">
                                    <div class="col-md-2">
                                        <label class="label-texto">Buscar por</label>
                                        <select id="cbxFiltroTipoBusqueda" class="cbx-texto">
                                            <option value="NUEVO">CLIENTE NUEVO</option>
                                            <option value="REGISTRADO" selected="true">CLIENTE REGISTRADO</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4" id="PanelBusquedaDocReg">
                                        <label class="label-texto">Cliente</label>
                                        <select id="txtFiltroClienteOC" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                            <option selected="true" value="" disabled="disabled">TODOS</option>
                                            <?php
                                            $Clientes = new ControllerCategorias();
                                            $ClientesVer = $Clientes->VerClientesBusqueda();
                                            foreach ($ClientesVer as $Cliente) {
                                            ?>
                                                <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Cliente['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 row" id="PanelBusquedaDocNew" style="display: none">
                                        <div class="col-md-4 subir-campos">
                                            <label for="" class="label-texto bajar-lb">Tipo Documento </label>
                                            <select id="cbxTipoDocumentoOC" class="cbx-texto">
                                                <?php
                                                $TipoDocumento = new ControllerCategorias();
                                                $TipoDocumentoVer = $TipoDocumento->VerTipoDocumentoFacturacion();
                                                foreach ($TipoDocumentoVer as $TipoDocumento) {
                                                ?>
                                                    <option value="<?php echo $TipoDocumento['ID']; ?>" style="font-size: 11px;">
                                                        <?php echo $TipoDocumento['Nombre']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md subir-campos">
                                            <label for="" class="label-texto bajar-lb">Nro Documento</label>
                                            <input type="text" class="caja-texto" name="txtNroDocumentoOC" id="txtNroDocumentoOC" maxlength="20">
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-md-2 bajar-campos">
                                        <button class="btn btn-registro" id="btnBuscarDocumentoOC" name="btnBuscarDocumentoOC" style="width: 40px; margin-top: -2px; margin-left: -3px;"><i class="fas fa-search"></i></button>
                                    </div>
                                    
                                    <div class="col-md-2"></div>
                                    
                                    <div class="col-md-2 row" hidden>
                                        <div class="col-md bajar-campos text-right">
                                            <button class="btn btn-registro-primary" id="btnConfigConceptosOC" name="btnConfigConceptosOC" style="width: 150px; margin-top: -2px; margin-left: -3px;"><i class="mdi mdi-settings"></i> Config. Conceptos</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-md-1">
                                        <label for="" class="label-texto">Tipo Doc. </label>
                                        <select id="cbxFiltroTipoDocumentoOC" class="cbx-texto" disabled>
                                            <?php
                                            $TipoDocumento = new ControllerCategorias();
                                            $TipoDocumentoVer = $TipoDocumento->VerTipoDocumentoFacturacion();
                                            foreach ($TipoDocumentoVer as $TipoDocumento) {
                                            ?>
                                                <option value="<?php echo $TipoDocumento['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $TipoDocumento['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="" class="label-texto">Nro Doc.</label>
                                        <input type="text" class="caja-texto" name="txtFiltroNroDocumentoOC" id="txtFiltroNroDocumentoOC" maxlength="20" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="" class="label-texto">Apellidos y Nombres</label>
                                        <input type="text" class="caja-texto" name="txtFiltroDatoClienteOC" id="txtFiltroDatoClienteOC" placeholder="Escribir aqui" disabled>
                                    </div>
                                    <div class="col-md-1" hidden>
                                        <label for="" class="label-texto">Direccion</label>
                                        <input type="text" class="caja-texto" name="txtFiltroDireccionOC" id="txtFiltroDireccionOC" disabled>
                                    </div>    
                                    <div class="col-md-1" hidden>
                                        <label for="" class="label-texto">correo</label>
                                        <input type="text" class="caja-texto" name="txtFiltroCorreoOC" id="txtFiltroCorreoOC" disabled>
                                    </div>  
                                    <div class="col-md-1">
                                        <label class="label-texto">Concepto</label>
                                        <select id="txtfiltroConceptoVentaOC" style="width: 100%; font-size: 11px;" class="cbx-texto" disabled>
                                            <?php
                                            $Conceptos = new ControllerCategorias();
                                            $ConceptosVentas = $Conceptos->VerConceptosFacturacion();
                                            foreach ($ConceptosVentas as $ConceptosV) {
                                            ?>
                                                <option value="<?php echo $ConceptosV['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $ConceptosV['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Motivo Concepto</label>
                                        <select id="txtfiltroMotivoConceptoOC" style="width: 100%; font-size: 11px;" class="cbx-texto" disabled>
                                            <option selected="true" value="" disabled="disabled">Seleccionar..</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Tipo Comprobante</label>
                                        <select id="txtFiltroTipoComprobanteOC" style="width: 100%; font-size: 11px;" class="cbx-texto" disabled>
                                            <?php
                                            $ComprobanteSunat = new ControllerCategorias();
                                            $ComprobanteSunatVer = $ComprobanteSunat->VerTipoComprobanteSunatImpr();
                                            foreach ($ComprobanteSunatVer as $Comprobante) {
                                            ?>
                                                <option value="<?php echo $Comprobante['ID']; ?>" style="font-size: 11px;">
                                                    <?php echo $Comprobante['Nombre']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-1" style="margin-top: 12px;">
                                        <button class="btn btn-registro-success" id="btnContinuarComprobanteOC" name="btnContinuarComprobanteOC" disabled><i class="fas fa-arrow-circle-right"></i> Generar</button>
                                    </div>
                                    <div class="col-md" style=" margin-top: 12px; margin-left: 15px;">
                                        <button class="btn btn-registro" id="btnNuevaEmisionOC" name="btnNuevaEmisionOC" disabled><i class="fas fa-sync-alt" disabled></i> Nuevo</button>
                                    </div>

                                </div>
                            </div>
                            <br>
                            
                            <!-- TABLAS PAGOS RESERVAS -->
                            <div id="PanelPagosReservas" style="display: none">
                                <fileset>
                                    <legends>Pago de Reservas</legends>
                                    <br>
                                    <table class="table table-striped table-bordered table-hover w-100" id="TablaPagosReservas">
                                          <thead class="cabecera">
                                              <tr>
                                                  <th></th>
                                                  <th>Fecha Inicio</th>
                                                  <th>Fecha Termino</th>
                                                  <th>Cliente</th>
                                                  <th>Lote</th>
                                                  <th>Tipo Moneda</th>
                                                  <th>Tipo Cambio</th>
                                                  <th>Importe Pagado</th>
                                                  <th>Total Pagado</th>
                                                  <th>Estado</th>
                                              </tr>
                                          </thead>
                                          <tbody class="control-detalle">
                                          </tbody>
                                      </table>
                                    
                                </fileset>
                                
                                
                            </div>
                            
                            <!-- BOLETA ELECTRONICA -->
                            <div id="PanelBoletaElectronicaOC" style="display: none;"><br>
                                <?php include_once "comprobantes/Facturacion_boleta_otros.php"; ?>
                            </div>

                            <!-- FACTURA ELECTRONICA -->
                            <div id="PanelFacturaElectronicaOC" style="display: none;"><br>
                                <?php include_once "comprobantes/Facturacion_factura_otros.php"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
            <div class="modal fade" id="modalNotaCredito" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                  require_once "pop-up/M07SM01_POPUP_NotaCredito.php";
                ?>
            </div>
            <div class="modal fade" id="modalSeleccionPago" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                  require_once "pop-up/M07SM01_POPUP_ValidarPago.php";
                ?>
            </div>
            <div class="modal fade" id="modalMensajeWts" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                  require_once "pop-up/M07SM01_POPUP_EnviarWts.php";
                ?>
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

    <script src="../../js/M07_Contabilidad/M07JS01_Facturacion/M07JS01_Index.js?v=1.2.0"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/xlsx.full.min.js?v=1.1.1"></script>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#txtFiltroProyecto').select2();
        $('#txtFiltroCliente').select2();
        $('#txtFiltroPropiedad').select2();
        $('#txtFiltroTipoComprobante').select2();
        $('#txtFiltroCliente2').select2();
        $('#txtFiltroClienteOC').select2();
    });
</script>