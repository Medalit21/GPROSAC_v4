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
                    <li><a class="enlace" href="javascript:void(0)">Administracion</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Consulta de Pagos</a></li>
                    <li class="bread-opcion">
                        <div class="row">
                            <div class="page-breadcrumb">
                                <div class="col-12 d-flex no-block align-items-right">
                                    <div class="ml-auto text-right">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="../../home.php">Inicio</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a href="../../../index.php">
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
                    <div id="contenido_lista">
                        <div class="form-row mt-3">
                            <div class="col-md-12">
                                <label class="titulo-cont">Filtros de Busqueda:</label>
                            </div>
                            <div class="col-md">
                                <label class="label-texto">Cliente</label>
                                <select id="txtFiltroDocumentoCV" style="width: 100%; font-size: 11px;" class="cbx-texto">
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

                            <div class="col-md">
                                <label class="label-texto">Desde</label>
                                <input type="date" id="txtFiltroDesdeCV" class="caja-texto">
                            </div>
                            <div class="col-md">
                                <label class="label-texto">Hasta</label>
                                <input type="date" id="txtFiltroHastaCV" class="caja-texto">
                            </div>

                            <div class="col-md">
                                <label class="label-texto">Agencia Bancaria</label>
                                <select id="cbxFiltroBancoCV" class="cbx-texto">
                                    <option selected="true" value="">TODOS
                                    </option>
                                    <?php
                                    $VerBanco = new ControllerCategorias();
                                    $VerBanco = $VerBanco->VerBancos();
                                    foreach ($VerBanco as $item) {
                                    ?>
                                        <option value="<?php echo $item['ID']; ?>">
                                            <?php echo $item['Nombre']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-row mt-3">
                            <div class="col-md">
                                <label class="label-texto">Tipo Pago</label>
                                <select id="cbxTipoPagoC" class="cbx-texto">
                                    <option selected="true" value="">TODOS
                                    </option>
                                    <?php
                                    $VerB = new ControllerCategorias();
                                    $VerB = $VerB->VerTipoPagoC();
                                    foreach ($VerB as $item) {
                                    ?>
                                        <option value="<?php echo $item['ID']; ?>">
                                            <?php echo $item['Nombre']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="label-texto">Estado Cierre</label>
                                <select id="cbxEstadoC" class="cbx-texto">
                                    <option selected="true" value="">TODOS</option>
                                    <option value="2">CERRADO</option>
                                    <option value="1">PROCESANDO</option>
                                </select>
                            </div>
                            
                            <div class="col-md">
                                <label class="label-texto">Tipo Comprobante Sunat</label>
                                <select id="cbxTipoComprobanteSunat" name="cbxTipoComprobanteSunat" class="cbx-texto">
                                    <option selected="true" value="">TODOS
                                    </option>
                                    <?php
                                    $VerB = new ControllerCategorias();
                                    $VerB = $VerB->VerTipoComprobanteSunat();
                                    foreach ($VerB as $item) {
                                    ?>
                                        <option value="<?php echo $item['ID']; ?>">
                                            <?php echo $item['Nombre']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="label-texto" style="color: red">Ordenar por:</label>
                                <select id="cbxOrdenar" class="cbx-texto">
                                    <option selected="true" value="">TODOS</option>
                                    <option value="1">SERIE (<i class="fas fa-arrow-down"></i><small>De A a Z)</small></option>
                                    <option value="2">SERIE (<i class="fas fa-arrow-down"></i><small>De Z a A)</small></option>
                                    <option value="3">NUMERO (<i class="fas fa-arrow-down"></i><small>De A a Z)</small></option>
                                    <option value="4">NUMERO (<i class="fas fa-arrow-down"></i><small>De Z a A)</small></option>
                                </select>
                            </div>


                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-4 text-center mt-1">
                                <button class="btn btn-registro-success" id="btnBuscarRegistroCV" name="btnBuscarRegistroCV"><i class="fas fa-search"></i> Buscar</button>
                                <button class="btn btn-registro-primary" id="btnLimpiarCV" name="btnLimpiarCV"><i class="fas fa-sync-alt"></i> Limpiar</button>
                            </div>
                        </div>
                        <br>
                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <div class="table-responsive scroll-table">
                                <table class="table table-striped table-bordered table-hover w-100" id="TablaPagoComprobanteReporte" style="display: none;">
                                    <thead class="cabecera">
                                        <tr>
                                            <th>Fecha Pago</th>
                                            <th>Voucher Pago</th>
                                            <th>Estado Validación</th>
                                            <th>Fecha Emision</th>
                                            <th>Tipo Comprobante Sunat</th>
                                            <th>Serie</th>
                                            <th>Numero</th>
                                            <th>Comprobante</th>
                                            <th>Estado Cierre</th>
                                            <th>Cliente</th>
                                            <th>Lote</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Letra</th>
                                            <th>Mora</th>
                                            <th>Tipo Comprobante Pago</th>
                                            <th>Tipo Moneda</th>
                                            <th>Importe Pago</th>
                                            <th>Tipo Cambio</th>
                                            <th>Pagado</th>
                                            <th>Banco</th>
                                            <th>Medio Pago</th>
                                            <th>N° Operación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                                <br><br>
                                <table class="table table-striped table-bordered table-hover w-100" id="TablaPagoComprobante">
                                    <thead class="cabecera">
                                        <tr>
                                            <th>Fecha Pago</th>
                                            <th>Voucher Pago</th>
                                            <th>Estado Validación</th>
                                            <th>Fecha Emision</th>
                                            <th>Tipo Comprobante Sunat</th>
                                            <th>Serie</th>
                                            <th>Numero</th>
                                            <th>Comprobante</th>
                                            <th>Estado Cierre</th>
                                            <th>Cliente</th>
                                            <th>Lote</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Letra</th>
                                            <th>Mora</th>
                                            <th>Tipo Comprobante Pago</th>
                                            <th>Tipo Moneda</th>
                                            <th>Importe Pago</th>
                                            <th>Tipo Cambio</th>
                                            <th>Pagado</th>
                                            <th>Banco</th>
                                            <th>Medio Pago</th>
                                            <th>N° Operación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
            <div class="modal fade" id="modalCargaComprobante" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                //require_once "pop-up/M07SM02_POPUP_Carga.php";
                ?>
            </div>
            
            <div class="modal fade" id="modalVerVoucher" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M08SM02_POPUP_VerVoucher.php";
                ?>
            </div>
            
            <div class="modal fade" id="modalVerComprobante" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M08SM02_POPUP_VerComprobante.php";
                ?>
            </div>
            
            <div class="modal fade" id="modalObservaciones" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                //require_once "pop-up/M07SM02_POPUP_Observaciones.php";
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

    <script src="../../js/M08_Administracion/M08JS02_RegPagos/M08JS02_Index.js?v=1.2.0"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/xlsx.full.min.js?v=1.1.1"></script>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#txtFiltroDocumentoCV').select2();
    });
</script>