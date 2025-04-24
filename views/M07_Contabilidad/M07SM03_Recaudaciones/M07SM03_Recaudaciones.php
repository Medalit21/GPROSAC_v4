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
    <title><?php echo $NAME_APP.' - Recaudaciones'; ?></title>
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
        $variable = encrypt($valor_user, "123");
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
                    <li><a class="enlace" href="javascript:void(0)">Recaudaciones</a></li>
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
                <div class="tab-content">
                    <br>
                    <!-- BOLETA ELECTRONICA -->
                    <div>
                        <!-- accoridan part -->
                        <div class="accordion" id="accordionExample">
                            <div class="card m-b-0">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="m-r-5 fa fa-magnet" aria-hidden="true"></i>
                                            <span>GENERADOR DE ARCHIVO DE COBRANZAS</span>
                                        </a>
                                    </h5>
                                </div>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="GenerarArchivoCobranza" style="opacity: 1;">
                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    
                                                    <fieldset style="margin-left: -10px">
                                                        <legends>Procesar Informaci&oacute;n</legends>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="" class="label-texto">Cliente</label>
                                                                <select id="txtDocumentoREC" class="cbx-texto">
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
                                                                <label for="" class="label-texto">Criterio b&uacute;squeda</label>
                                                                <select id="txtFecIniREC" class="cbx-texto">
                                                                    <option selected="true" value="" disabled="disabled">Seleccionar</option>
                                                                    <option value="1">AL MES</option>
                                                                    <option value="2">DEL MES</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="" class="label-texto">Mes</label>
                                                                <select id="txtFecFinREC" class="cbx-texto">
                                                                    <option selected="true" value="" disabled="disabled">TODOS</option>
                                                                    <?php
                                                                    $Clientes = new ControllerCategorias();
                                                                    $ClientesVer = $Clientes->VerMesRecaudaciones();
                                                                    foreach ($ClientesVer as $Cliente) {
                                                                    ?>
                                                                        <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                                            <?php echo $Cliente['Nombre']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>  
                                                            
                                                            <div class="col-md bajar-lb-2 text-center">
                                                                <button id="btnProcesarRecaudaciones" type="button" class="btn btn-registro"><i class="fas fa-save"></i> Procesar</button> 
                                                            </div>
                                                            <div class="col-md bajar-lb-2 text-center">
                                                                <button id="btnRestablecerRecaudaciones" type="button" class="btn btn-registro-primary btn-Modif"><i class="fas fa-sync-alt"></i> Restablecer</button> 
                                                            </div>
                                                            <div class="col-md bajar-lb-2 text-left">
                                                                <button id="btnGenerarArchivo" type="button" class="btn btn-registro"><i class="fas fa-download"></i> TXT</button> 
                                                            </div>
                                                            
                                                        </div>
                                                    </fieldset>
                                                    <br>
                                                    <div class="row  bajar-lb">
                                                        <input type="hidden" id="__ID_USER" value="<?php echo $variable; ?>">
                                                        <div class="col-md-2 text-left fond-labels">
                                                            <label for="" class="label-texto bajar-lb text-labels">Cuenta del Afiliado :</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <!--<input type="text" class="caja-texto text-right" name="txtCuentaAfiliadoREC" id="txtCuentaAfiliadoREC">-->
                                                            <select id="txtCuentaAfiliadoREC" style="width: 100%; font-size: 11px;" class="cbx-texto text-right">
                                                                <option selected="true" value="" disabled="disabled">Seleccionar</option>
                                                                <?php
                                                                $Clientes = new ControllerCategorias();
                                                                $ClientesVer = $Clientes->VerNumCuentaBanco();
                                                                foreach ($ClientesVer as $Cliente) {
                                                                ?>
                                                                    <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                                        <?php echo $Cliente['Nombre']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-2 text-left fond-labels">
                                                            <label for="" class="label-texto bajar-lb text-labels">Total de Registros :</label>
                                                        </div>
                                                        
                                                        <div class="col-md-2">
                                                            <input type="text" class="caja-texto text-right" name="txtTotalRegistrosREC" id="txtTotalRegistrosREC" readonly>
                                                        </div>
                                                        
                                                        <div class="col-md-2 text-left fond-labels">
                                                            <label for="" class="label-texto bajar-lb text-labels">Tipo de Archivo :</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select id="txtTipoArchivoREC" style="width: 100%; font-size: 11px;" class="cbx-texto text-right">
                                                                <option selected="true" value="" disabled="disabled">Seleccionar</option>
                                                                <?php
                                                                $Clientes = new ControllerCategorias();
                                                                $ClientesVer = $Clientes->VerTipoArchivoRecaudacion();
                                                                foreach ($ClientesVer as $Cliente) {
                                                                ?>
                                                                    <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                                        <?php echo $Cliente['Nombre']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>                                                   
                                                        
                                                    </div>
                                                    <div class="row  bajar-lb">
                                                        <div class="col-md-2 text-left fond-labels">
                                                            <label for="" class="label-texto bajar-lb text-labels">Nombre de la Empresa:</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="caja-texto text-right" name="txtNombreEmpresaREC" id="txtNombreEmpresaREC">
                                                        </div>
                                                        <div class="col-md-2 text-left fond-labels">
                                                            <label for="" class="label-texto bajar-lb text-labels">Monto Total :</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="caja-texto text-right" name="txtMontoTotalREC" id="txtMontoTotalREC" readonly>
                                                        </div>
                                                        
                                                        <div class="col-md-2 text-left fond-labels">
                                                            <label for="" class="label-texto bajar-lb text-labels">C&oacute;digo de Servicio :</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="caja-texto text-right" name="txtCodigoServicioREC" id="txtCodigoServicioREC" value="000000">
                                                        </div>
                                                        
                                                    </div>
                                                    <br>
                                                    <fieldset style="margin-left: -10px">
                                                        <legends>Modificar Tipo Registro</legends>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <select id="cbxFiltroTipoRegistro" class="cbx-texto">
                                                                    <option selected="true" value="" disabled="disabled">TODOS</option>
                                                                    <?php
                                                                    $Clientes = new ControllerCategorias();
                                                                    $ClientesVer = $Clientes->VerTipoRegistroRecaudacion();
                                                                    foreach ($ClientesVer as $Cliente) {
                                                                    ?>
                                                                        <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                                                            <?php echo $Cliente['Nombre']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>  
                                                            
                                                            <div class="col-md-2">
                                                                <button id="btnModificarTodos" type="button" class="btn btn-registro btn-Modif"><i class="fas fa-save"></i> Modificar todos</button> 
                                                            </div>
                                                            
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <hr>
                                                <fieldset style="margin-left: -10px">
                                                    <legends>Filtros de B&uacute;squeda</legends>
                                                    <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="" class="label-texto">Cliente</label>
                                                                <select id="txtFiltroDocumento" class="cbx-texto">
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
                        										<label class="label-texto">Desde</label>
                        										<input type="date" id="txtDesdeFiltro" class="caja-texto" value="">
                        									</div>
                        									<div class="col-md-2">
                        										<label class="label-texto">Hasta</label>
                        										<input type="date" id="txtHastaFiltro" class="caja-texto" value="">
                        									</div> 
                                                            
                                                            <div class="col-md bajar-lb-2">
                                                                <button id="btnBuscarRecaudaciones" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button> 
                                                            </div>
                                                            <div class="col-md bajar-lb-2 text-center">
                                                                <button id="btnLimpiarRecaudaciones" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button> 
                                                            </div>
                                                            <div class="col-md"></div>
                                                            
                                                    </div>
                                                </fieldset>
                                                <br>
                                                <div class="col-md-12 table-responsive scroll-table">
                                                    <table class="table table-striped table-bordered" id="TablaRecaudacionesReporte" style="display: none;">
                                                        <thead class="cabecera">
                                                            <tr>
                                                                <th>Codigo del Depositante</th>
                                                                <th>Nombre del Depositante</th>
                                                                <th>Informacion de Retorno</th>
                                                                <th>Fecha de Emisi&oacute;n</th>
                                                                <th>Fecha de Vencimiento</th>
                                                                <th>Monto a Pagar</th>
                                                                <th>Mora / Cargo Fijo</th>
                                                                <th>Monto Minimo</th>
                                                                <th>Tipo de Registro</th>
                                                                <th>Nro. Documento de Pago</th>
                                                                <th>Nro. Documento de Identidad</th>                                         
                                                            </tr>
                                                        </thead>
                                                        <tbody class="control-detalle">
                                                        </tbody>
                                                    </table>
                                                    <div style="margin: 36px;"> </div>
                                                    <table class="table table-striped table-bordered table-hover w-100" id="TablaRecaudaciones">
                                                        <thead class="cabecera">
                                                            <tr>
                                                                <th></th>
                                                                <th>Codigo del Depositante</th>
                                                                <th>Nombre del Depositante</th>
                                                                <th>Informacion de Retorno</th>
                                                                <th>Fecha de Emisi&oacute;n</th>
                                                                <th>Fecha de Vencimiento</th>
                                                                <th>Monto a Pagar</th>
                                                                <th>Mora / Cargo Fijo</th>
                                                                <th>Monto Minimo</th>
                                                                <th>Tipo de Registro</th>
                                                                <th>Nro. Documento de Pago</th>
                                                                <th>Nro. Documento de Identidad</th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
            <div class="modal fade" id="modalTipoRegistro" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M07SM03_POPUP_TipoRegistro.php";
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

    <script src="../../js/M07_Contabilidad/M07JS03_Recaudaciones/M07JS03_Index.js?v=1.2.0"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/xlsx.full.min.js?v=1.1.1"></script>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#txtDocumentoREC').select2();
        $('#txtFiltroDocumento').select2();
    });
</script>