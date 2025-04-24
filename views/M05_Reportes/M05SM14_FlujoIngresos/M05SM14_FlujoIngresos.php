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
    <title><?php echo $NAME_APP.' - Reporte Flujo de Ingresos'; ?></title>
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
            $variable = $_GET['Vsr'];
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
        <div class="page-wrapper fond-back">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Reportes</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Flujo de Ingresos</a></li>
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
                    <!-- TABLA CONTENEDORA DE REPORTE 001 -->   
                    <div role="tabpanel" class="tab-pane fade in active" id="reporte001" style="opacity: 1;">
                        <div class="box box-primary">                  
                            <div id="BusquedaRegistro">
                                <div class="form-row mt-3">
                                    <input type="hidden" id="_TXTUSR" value="<?php echo $variable; ?>">

                                    <div class="col-md-3">
                                        <label class="col-md label-texto">Periodo</label>
                                        <select id="cbxPeriodo" class="cbx-texto">
                                            <option value="2035">2035</option>
                                            <option value="2034">2034</option>
                                            <option value="2033">2033</option>
                                            <option value="2032">2032</option>
                                            <option value="2031">2031</option>
                                            <option value="2030">2030</option>
                                            <option value="2029">2029</option>
                                            <option value="2028">2028</option>
                                            <option value="2027">2027</option>
                                            <option value="2026">2026</option>
                                            <option value="2025" selected="true">2025</option>
                                            <option value="2024">2024</option>
                                            <option value="2023">2023</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" hidden>
										<label class="label-texto">Cliente</label>
										<select id="cbxFiltroNumDocumento" class="cbx-texto">
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
									<div class="col-md-3" hidden>
										<label class="label-texto">Desde</label>
										<input type="date" id="txtDesdeFiltro" class="caja-texto"
											placeholder="Documento Cliente" value="">
									</div>
									<div class="col-md-3" hidden>
										<label class="label-texto">Hasta</label>
										<input type="date" id="txtHastaFiltro" class="caja-texto"
											placeholder="Documento Cliente" value="">
									</div>
                                    <div class="col-md-3" hidden>
                                        <label class="col-md label-texto">Estado</label>
                                        <select id="cbxEstadoLetra" class="cbx-texto">
                                            <option value="3" selected="true">VENCIDO</option>
                                            <option value="1">POR VENCER</option>
                                            <option value="">TODOS</option>
                                        </select>
                                    </div>
									<div class="col-md-3" hidden>
                                        <label class="col-md label-texto">Proyecto</label>
                                        <select id="bxFiltroProyecto" class="cbx-texto">
                                                <?php
                                                    $tipoDoc = new ControllerCategorias();
                                                    $VerTiposDoc = $tipoDoc->VerProyectos();
                                                    foreach ($VerTiposDoc as $td) {
                                                ?>
                                            <option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option><?php }?>
                                        </select>
                                    </div>
                                    <div class="col-md-3" hidden>
                                        <label class="col-md label-texto">Zona</label>
                                        <select id="bxFiltroZona" class="cbx-texto">
                                            <option selected="true" value="" disabled="">Seleccionar...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" hidden>
                                        <label class="col-md label-texto">Manzana</label>
                                        <select id="bxFiltroManzana" class="cbx-texto">
                                            <option selected="true" value="" disabled="">Seleccionar...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" hidden>
                                        <label class="col-md label-texto">Lote</label>
                                        <select id="bxFiltroLote" class="cbx-texto">
                                            <option selected="true" value="" disabled="">Seleccionar...</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6" style="margin-top: 15px;">
                                        <button class="btn btn-registro-success" id="btnBuscarRegistro" name="btnBuscarRegistro"><i class="fas fa-search"></i> Buscar</button>
                                        <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiar"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
								</div>
                                <div class="form-row d-flex justify-content-center">
                                    
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-control text-left text-res" id="labelTotal"></label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" id="txtTotalMesActual" class="form-control inp-res" value="0.00" readonly>
                                        <input type="hidden" id="txtTotalLetras" class="form-control inp-res" value="0.00" readonly>
                                    </div>
                                    <div class="col-md-2" hidden>
                                        <label class="form-control text-right text-res">TOTAL MORA (d&iacute;as) </label>
                                    </div>
                                    <div class="col-md-2" hidden>
                                        <input type="text" id="txtTotalMora" class="form-control inp-res" value="0.00" readonly>
                                    </div>
                                    <div class="col-md-2" hidden>
                                        <label class="form-control text-right text-res">TOTAL MONTO </label>
                                    </div>
                                    <div class="col-md-2" hidden>
                                        <input type="text" id="txtTotalMonto" class="form-control inp-res" value="0.00" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaReservasReporte" style="display: none;">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Descripcion</th>
                                                    <th>A Cancelar</th>
                                                    <th>Cancelado</th>
                                                    <th>Por Cancelar</th>                                 
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <div style="margin: 36px;"> </div>
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaLetrasVencidas">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Descripcion</th>
                                                    <th class="text-center">A Cancelar</th>
                                                    <th class="text-center">Cancelado</th>
                                                    <th class="text-center">Por Cancelar</th>  
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

    <script src="../../js/M05_Reportes/M05JS14_FlujoIngresos/M05JS14_FlujoIngresos.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#cbxFiltroNumDocumento').select2();
    });
</script>