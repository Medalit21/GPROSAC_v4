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
    <link href="../../dist/css/style.min.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../../dist/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../css/estilos.css?v=<?php echo time(); ?>">

    <!-- LIBRERIAS LOADING PROCESANDO -->
    <link rel="stylesheet" type="text/css" href="../../css/LoadingProcesandoGeneral.css?v=<?php echo time(); ?>">
    <!-- LIBRERIAS ALERTA MENSAJES -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" type="text/css" href="../../main.css">
    <link rel="stylesheet" href="../../datatables/datatables.min.css" />
    <link rel="stylesheet" href="../../datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="../../datatables/select/css/select.bootstrap4.min.css" />

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
        <div class="page-wrapper fond-back">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Calculadora de Préstamos</a></li>
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
                    <div class="row">
                         
                        <div class="col-md"><br>
                            <label class="label-texto">TEA (%)</label>
                            <input type="text" id="txtTEA" class="caja-texto" placeholder="Escriba aqui">
                        </div>
                        
                        <div class="col-md" hidden><br>
                            <label class="label-texto">TEM (%)</label>
                            <input type="text" id="txtTEM" class="caja-texto" placeholder="Escriba aqui">
                        </div>

                        <div class="col-md"><br>
                            <label class="label-texto">Nro Cuotas (meses)</label>
                            <input type="text" id="txtCuotas" class="caja-texto" placeholder="Escriba aqui">
                        </div>
                        
                        <div class="col-md"><br>
                            <label class="label-texto">Precio de Venta (monto)</label>
                            <input type="text" id="txtPrecioVenta" class="caja-texto" placeholder="Escriba aqui">
                        </div>

                        <div class="col-md"><br>
                            <label class="label-texto">Cuota Inicial (monto)</label>
                            <input type="text" id="txtCuotaInicial" class="caja-texto" placeholder="Escriba aqui">
                        </div>						
                    </div>

                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-4 text-center mt-1">
                            <button id="btnBuscarCalcular" type="button" class="btn btn-registro-success"><i class="fas fa-calculator"></i> Calcular</button>
                            <button id="btnLimpiarCalculo" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                        </div>
                    </div>
                </div>

                 <!-- editor -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                
                                <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                                <div class="fn-frm-dt">                                  
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaCalculadoraReporte" style="display: none; width: 100%">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>#MES</th>
                                                    <th>Cuota</th>
													<th>Intereses</th>
                                                    <th>Amortización</th>
													<th>Capital Vivo</th>
													<th>Capital Amortizado</th>
													<th>Total Pagado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        
                                        <table class="table table-striped table-bordered" cellspacing="0" id="TablaCalculadora">
                                            <thead class="cabecera" style="text-align: right">
                                                <tr>
                                                    <th>#MES</th>
                                                    <th>Monto Cuota</th>
													<th>Intereses</th>
                                                    <th>Amortización</th>
													<th>Capital Vivo</th>
													<th>Capital Amortizado</th>
													<th>Total Pagado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody> 
                                        </table>
                                        <br>
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
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
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

    
    <script src="../../js/M02_Clientes/M02JS05_CalculadoraPrestamos/M02JS05_CalculadoraPrestamos.js?v=1.1.1"></script>

    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>