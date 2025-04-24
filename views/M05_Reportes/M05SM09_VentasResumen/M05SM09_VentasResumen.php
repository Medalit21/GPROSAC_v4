<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
session_start();
require_once "../../../config/configuracion.php";
$_SESSION['nom'] = "Segun fecha de Pago";
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <title><?php echo $NAME_APP.' - REPORTE DE VENTAS RESUMEN (Segun Fecha Pago)'; ?></title>
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
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Reportes</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Ventas Resumen</a></a></li>
                </ol>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="reporte001" style="opacity: 1;">
                        <div class="box box-primary">                  
                            <div id="BusquedaRegistro">
                                
                                <div class="form-row mt-3">
                                    <div class="col-md-12">
                                        <label class="titulo-cont">Filtros de Busqueda:</label>
                                    </div>
                                    <div class="col-md">
                                        <label class="label-texto">Cliente</label>
                                            <select id="txtdocumentoFiltro" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                                <option selected="true" value="" disabled="disabled">TODOS</option>
                                                <?php
                                                $Clientes = new ControllerCategorias();
                                                $ClientesVer = $Clientes->VerClientesBusqueda();
                                                foreach ($ClientesVer as $Cliente) {
                                                ?>
                                                <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;"><?php echo $Cliente['Nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Nombres:</label>
                                        <input type="Text" id="txtNombresFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Apellidos:</label>
                                        <input type="Text" id="txtApellidoFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Periodo</label>
                                        <select id="bxFiltroPeriodo" class="cbx-texto">
                                            <option value="2031">2031</option>
                                            <option value="2030">2030</option>
                                            <option value="2029">2029</option>
                                            <option value="2028">2028</option>
                                            <option value="2027">2027</option>
                                            <option value="2026">2026</option>
                                            <option selected="true" value="2025">2025</option>
                                            <option value="2024">2024</option>
                                            <option value="2023">2023</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Tipo</label>
                                        <select id="bxFiltroTipo" class="cbx-texto">
                                            <option selected="true" value="1">Según Fecha Pago</option>
                                            <option value="2">Según Fecha Vencimiento</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-registro-success" id="btnBuscarRegistro" name="btnBuscarCliente" style="margin-top: 15px">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiarClient" style="margin-top: 15px">
                                            <i class="fas fa-sync-alt"></i> Limpiar
                                        </button>
                                        <button id="btnExportarPdf" class="btn btn-registro-danger" style="margin-top: 15px"><i class="fas fa-file-pdf" style="color:white"></i> Reporte</button>
                                    </div>       
                                </div>
                                <br>
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <div id="reporte1">  
                                            <table class="table table-striped table-bordered" id="TablaUsuarioReporte" style="display: none;">
                                                <thead class="cabecera" style="text-align: center">
                                                    <tr>
                                                        <th>Vendedor</th>
                                                        <th>Fecha Venta</th>
                                                        <th>Documento</th>
                                                        <th>Cliente</th>
                                                        <th>Tipo Moneda</th>
                                                        <th>Proyecto</th>
                                                        <th>Zona</th>
                                                        <th>Lote</th>
                                                        <th>Estado</th>
                                                        <th>Total Venta</th>
                                                        <th>Total Financiado</th>
                                                        <th>Monto Pagado</th>
                                                        <th>Saldo</th>
                                                        <th>Monto Inicial</th>
                                                        <th>Enero</th>
                                                        <th>Febrero</th>
                                                        <th>Marzo</th>
                                                        <th>Abril</th>
                                                        <th>Mayo</th>
                                                        <th>Junio</th>
                                                        <th>Julio</th>
                                                        <th>Agosto</th>
                                                        <th>Septiembre</th>
                                                        <th>Octubre</th>
                                                        <th>Noviembre</th>
                                                        <th>Diciembre</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle" style="text-align: right">
                                                </tbody>
                                            </table>  
                                            <br><br>
                                            <table class="table table-striped table-bordered table-hover w-100"
                                                id="TablaUsuario">
                                                <thead class="cabecera" style="text-align: center">
                                                    <tr>
                                                        <th>Vendedor</th>
                                                        <th>Fecha Venta</th>
                                                        <th>Cliente</th>
                                                        <th>Tipo Moneda</th>
                                                        <th>Proyecto</th>
                                                        <th>Zona</th>
                                                        <th>Lote</th>
                                                        <th>Estado</th>
                                                        <th>Total Venta</th>
                                                        <th>Total Financiado</th>
                                                        <th>Monto Pagado</th>
                                                        <th>Saldo</th>
                                                        <th>Monto Inicial</th>
                                                        <th>Enero</th>
                                                        <th>Febrero</th>
                                                        <th>Marzo</th>
                                                        <th>Abril</th>
                                                        <th>Mayo</th>
                                                        <th>Junio</th>
                                                        <th>Julio</th>
                                                        <th>Agosto</th>
                                                        <th>Septiembre</th>
                                                        <th>Octubre</th>
                                                        <th>Noviembre</th>
                                                        <th>Diciembre</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle" style="text-align: right">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="reporte2" style="display: none;">    
                                            <table class="table table-striped table-bordered" id="TablaUsuarioReporte2" style="display: none;">
                                                <thead class="cabecera" style="text-align: center">
                                                    <tr>
                                                        <th>Vendedor</th>
                                                        <th>Fecha Venta</th>
                                                        <th>Documento</th>
                                                        <th>Cliente</th>
                                                        <th>Tipo Moneda</th>
                                                        <th>Proyecto</th>
                                                        <th>Zona</th>
                                                        <th>Lote</th>
                                                        <th>Estado</th>
                                                        <th>Precio Total Lote</th>
                                                        <th>Total Cancelado</th>
                                                        <th>Saldo</th>
                                                        <th>Monto Inicial</th>
                                                        <th>ENE-A cancelar</th>
                                                        <th>ENE-Cancelado</th>
                                                        <th>ENE-Por cancelar</th>
                                                        <th>ENE-Fecha</th>
                                                        <th>ENE-Letra</th>
                                                        <th>FEB-A cancelar</th>
                                                        <th>FEB-Cancelado</th>
                                                        <th>FEB-Por cancelar</th>
                                                        <th>FEB-Fecha</th>
                                                        <th>FEB-Letra</th>
                                                        <th>MAR-A cancelar</th>
                                                        <th>MAR-Cancelado</th>
                                                        <th>MAR-Por cancelar</th>
                                                        <th>MAR-Fecha</th>
                                                        <th>MAR-Letra</th>
                                                        <th>ABR-A cancelar</th>
                                                        <th>ABR-Cancelado</th>
                                                        <th>ABR-Por cancelar</th>
                                                        <th>ABR-Fecha</th>
                                                        <th>ABR-Letra</th>
                                                        <th>MAY-A cancelar</th>
                                                        <th>MAY-Cancelado</th>
                                                        <th>MAY-Por cancelar</th>
                                                        <th>MAY-Fecha</th>
                                                        <th>MAY-Letra</th>
                                                        <th>JUN-A cancelar</th>
                                                        <th>JUN-Cancelado</th>
                                                        <th>JUN-Por cancelar</th>
                                                        <th>JUN-Fecha</th>
                                                        <th>JUN-Letra</th>
                                                        <th>JUL-A cancelar</th>
                                                        <th>JUL-Cancelado</th>
                                                        <th>JUL-Por cancelar</th>
                                                        <th>JUL-Fecha</th>
                                                        <th>JUL-Letra</th>
                                                        <th>AGO-A cancelar</th>
                                                        <th>AGO-Cancelado</th>
                                                        <th>AGO-Por cancelar</th>
                                                        <th>AGO-Fecha</th>
                                                        <th>AGO-Letra</th>
                                                        <th>SEP-A cancelar</th>
                                                        <th>SEP-Cancelado</th>
                                                        <th>SEP-Por cancelar</th>
                                                        <th>SEP-Fecha</th>
                                                        <th>SEP-Letra</th>
                                                        <th>OCT-A cancelar</th>
                                                        <th>OCT-Cancelado</th>
                                                        <th>OCT-Por cancelar</th>
                                                        <th>OCT-Fecha</th>
                                                        <th>OCT-Letra</th>
                                                        <th>NOV-A cancelar</th>
                                                        <th>NOV-Cancelado</th>
                                                        <th>NOV-Por cancelar</th>
                                                        <th>NOV-Fecha</th>
                                                        <th>NOV-Letra</th>
                                                        <th>DIC-A cancelar</th>
                                                        <th>DIC-Cancelado</th>
                                                        <th>DIC-Por cancelar</th>
                                                        <th>DIC-Fecha</th>
                                                        <th>DIC-Letra</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle" style="text-align: right">
                                                </tbody>
                                            </table>  
                                            <br><br>
                                            <table class="table table-striped table-bordered table-hover w-100" id="TablaUsuario2">
                                                <thead class="cabecera" style="text-align: center">
                                                    <tr>
                                                        <th ROWSPAN=2>Vendedor</th>
                                                        <th ROWSPAN=2>Fecha Venta</th>
                                                        <th ROWSPAN=2>Cliente</th>
                                                        <th ROWSPAN=2>Tipo Moneda</th>
                                                        <th ROWSPAN=2>Proyecto</th>
                                                        <th ROWSPAN=2>Zona</th>
                                                        <th ROWSPAN=2>Lote</th>
                                                        <th ROWSPAN=2>Estado</th>
                                                        <th ROWSPAN=2>Precio Total Lote</th>
                                                        <th ROWSPAN=2>Total Cancelado</th>
                                                        <th ROWSPAN=2>Saldo</th>
                                                        <th ROWSPAN=2>Monto Inicial</th>
                                                        <th COLSPAN=5>Enero</th>
                                                        <th COLSPAN=5>Febrero</th>
                                                        <th COLSPAN=5>Marzo</th>
                                                        <th COLSPAN=5>Abril</th>
                                                        <th COLSPAN=5>Mayo</th>
                                                        <th COLSPAN=5>Junio</th>
                                                        <th COLSPAN=5>Julio</th>
                                                        <th COLSPAN=5>Agosto</th>
                                                        <th COLSPAN=5>Septiembre</th>
                                                        <th COLSPAN=5>Octubre</th>
                                                        <th COLSPAN=5>Noviembre</th>
                                                        <th COLSPAN=5>Diciembre</th>
                                                    </tr>
                                                    <tr>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                        <th>A cancelar</th>
                                                        <th>Cancelado</th>
                                                        <th>Por cancelar</th>
                                                        <th>Fecha</th>
                                                        <th>Letra</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="control-detalle" style="text-align: right">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>                            
                                </div>
                                <br>
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
    <script src="../../assets/libs/jquery-steps/build/jquery.steps.min.js"></script>
    <script src="../../assets/libs/jquery-validation/dist/jquery.validate.min.js"></script>
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

    <script src="../../js/M05_Reportes/M05JS09_VentasResumen/M05JS09_VentasResumen.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#txtdocumentoFiltro').select2();
    });
</script>
