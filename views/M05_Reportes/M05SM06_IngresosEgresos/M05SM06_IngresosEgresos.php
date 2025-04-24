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
                    <li><a class="enlace" href="javascript:void(0)">IngresosEgregos</a></li>
                </ol>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!--<ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#reporte001" role="tab" data-toggle="tab">Cabecera</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="#reporte002" role="tab" data-toggle="tab">Detalle</a>
                    </li>
                </ul>-->
                <div class="tab-content">
                    <!-- TABLA CONTENEDORA DE REPORTE 001 -->   
                    <div role="tabpanel" class="tab-pane fade in active" id="reporte001" style="opacity: 1;">
                        <div class="box box-primary">                  
                            <div id="BusquedaRegistro">
                                <div class="form-row mt-3">
									<div class="col-md-2">
										<label class="label-texto">Desde:</label>
										<input type="date" id="txtDesdeFiltro" class="caja-texto"
											placeholder="Documento Cliente" value="">
									</div>
									<div class="col-md-2">
										<label class="label-texto">Hasta: </label>
										<input type="date" id="txtHastaFiltro" class="caja-texto"
											placeholder="Documento Cliente" value="">
									</div>
									<div class="col-md-3">
										<label class="label-texto">Ingreso/Egreso : </label>
										<select id="bxFiltroIngresEgres" class="cbx-texto">
											<option value="H">INGRESO</option>
											<option value="D">EGRESO</option>
										</select>
									</div>
									<div class="col-md-4">
                                        <button class="btn btn-registro-success" id="btnBuscarRegistro" name="btnBuscarRegistro" style="margin-top: 15px">
											<i class="fas fa-search"></i> Buscar
										</button>
                                        <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiar" style="margin-top: 15px">
											<i class="fas fa-sync-alt"></i> Limpiar
										</button>
                                    </div>
								</div>
                                <br>
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaIngresoReporte"
                                            style="display: none;">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Sede</th>    
													<th>Fecha</th>
                                                    <th>Glosa</th>
                                                    <th>Total</th>
                                                    <!--<th>Accion</th>-->
                                                    <th>Cuenta contable</th>
                                                    <th>Operacion</th>
                                                    <th>Numero</th>
													<th>Id_Cabecera</th>
													<th>Tipo</th>
													<th>Serie</th>
                                                    <th>Numero</th>
                                                    <th>Moneda</th>
                                                    <th>Tipo Cambio</th>
                                                    <th>Importe Pagado</th>
                                                    <th>Total Pagado</th>
                                                    <th>Cuenta Contable</th>
                                                    <th>Centro costo</th>
                                                    <th>Razon social</th>
                                                    <th>Dni/Ruc</th>
                                                    <th>TipoR</th>
                                                    <th>SerieR</th>
                                                    <th>NumeroR</th>
                                                    <th>FechaR</th>
													<th>DebHab</th>	
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <div style="margin: 36px;"> </div>
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaIngreso">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Sede</th>
													<th>Fecha</th>
                                                    <th>Glosa</th>
                                                    <th>Total</th>
                                                    <th>Accion</th>
                                                    <th>Cuenta contable</th>
                                                    <th>Operacion</th>
                                                    <th>Numero</th>
													<th>Id_Cabecera</th>
													<th>Tipo</th>
													<th>Serie</th>
                                                    <th>Numero</th>
                                                    <th>Moneda</th>
                                                    <th>Tipo Cambio</th>
                                                    <th>Importe Pagado</th>
                                                    <th>Total Pagado</th>
                                                    <th>Cuenta Contable</th>
                                                    <th>Centro costo</th>
                                                    <th>Razon social</th>
                                                    <th>Dni/Ruc</th>
                                                    <th>TipoR</th>
                                                    <th>SerieR</th>
                                                    <th>NumeroR</th>
                                                    <th>FechaR</th>
													<th>DebHab</th>		
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
                    <!-- TABLA CONTENEDORA DE REPORTE 001 -->       
                    
                    <!-- TABLA CONTENEDORA DE REPORTE 002 -->
                    <!--<div role="tabpanel" class="tab-pane fade" id="reporte002">
                        <div class="box box-primary">                  
                            <div id="BusquedaVenta002">
								<div class="form-row mt-3">
									<div class="col-md-2">
										<label class="label-texto">Desde:</label>
										<input type="date" id="txtDesdeFiltro2" class="caja-texto"
											placeholder="Documento Cliente" value="">
									</div>
									<div class="col-md-2">
										<label class="label-texto">Hasta: </label>
										<input type="date" id="txtHastaFiltro2" class="caja-texto"
											placeholder="Documento Cliente" value="">
									</div>
									<div class="col-md-3">
										<label class="label-texto">Ingreso/Egreso : </label>
										<select id="bxFiltroIngresEgres2" class="cbx-texto">
											<option selected="true" value="">Seleccionar...</option>
											<option value="H">INGRESO</option>
											<option value="D">EGRESO</option>
										</select>
									</div>
									
									<div class="col-md-4">
                                        <button class="btn btn-registro-success" id="btnBuscarDetalle" name="btnBuscarRegistro" style="margin-top: 15px">
											<i class="fas fa-search"></i> Buscar
										</button>
                                        <button class="btn btn-registro-primary" id="btnLimpiarDetalle" name="btnLimpiar" style="margin-top: 15px">
											<i class="fas fa-sync-alt"></i> Limpiar
										</button>
                                    </div>
								</div>
                               <br>
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaIngEgrDetalReporte"
                                            style="display: none;">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Id_Cabecera</th>
													<th>Tipo</th>
													<th>Serie</th>
                                                    <th>Numero</th>
                                                    <th>Total</th>
                                                    <th>Cuenta Contable</th>
                                                    <th>Centro costo</th>
                                                    <th>Razon social</th>
                                                    <th>Dni/Ruc</th>
                                                    <th>TipoR</th>
                                                    <th>SerieR</th>
                                                    <th>NumeroR</th>
                                                    <th>FechaR</th>
													<th>DebHab</th>												
												</tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <div style="margin: 36px;"> </div>
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaIngEgrDetalle">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Id_Cabecera</th>
													<th>Tipo</th>
													<th>Serie</th>
                                                    <th>Numero</th>
                                                    <th>Total</th>
                                                    <th>Cuenta Contable</th>
                                                    <th>Centro costo</th>
                                                    <th>Razon social</th>
                                                    <th>Dni/Ruc</th>
                                                    <th>TipoR</th>
                                                    <th>SerieR</th>
                                                    <th>NumeroR</th>
                                                    <th>FechaR</th>
													<th>DebHab</th>													
												</tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <!-- TABLA CONTENEDORA DE REPORTE 002 -->
                    
                    <!-- TABLA CONTENEDORA DE REPORTE 003 -->           
                    <!--<div role="tabpanel" class="tab-pane fade" id="reporte003">
                        
                    </div>-->
                    <!-- TABLA CONTENEDORA DE REPORTE 003 -->           
                    
                    <!-- TABLA CONTENEDORA DE REPORTE 004 -->           
                    <!--<div role="tabpanel" class="tab-pane fade" id="reporte004">
                    </div>-->
                    <!-- TABLA CONTENEDORA DE REPORTE 004 -->
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

    <script src="../../js/M05_Reportes/M05JS06_IngresosEgresos/M05JS06_IngresosEgresos.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>