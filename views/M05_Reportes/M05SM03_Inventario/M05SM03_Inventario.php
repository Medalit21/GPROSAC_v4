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
    <title><?php echo $NAME_APP.' - Reporte de Inventario'; ?></title>
    <link rel="icon" href="../../../images/<?php echo $NAME_LOGO; ?>" type="image/png" />
    <!-- Custom CSS -->
    <link href="../../assets/libs/flot/css/float-chart.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../css/estilos.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="../../css/graficas.css?v=<?php echo time(); ?>">

    <!-- LIBRERIAS LOADING PROCESANDO -->
    <link rel="stylesheet" type="text/css" href="../../css/LoadingProcesandoGeneral.css?v=<?php echo time(); ?>">
    <!-- LIBRERIAS ALERTA MENSAJES -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" type="text/css" href="../../main.css">
    <link rel="stylesheet" href="../../datatables/datatables.min.css" />
    <link rel="stylesheet" href="../../datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="../../datatables/select/css/select.bootstrap4.min.css" />
	
</head>

<body>

    <script src="../../code/highcharts.js"></script>
	<script src="../../code/highcharts-3d.js"></script>
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
		require_once "indicadores.php";
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
                    <li><a class="enlace" href="javascript:void(0)">Resumen de Inventario</a></li>
                </ol>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                
				<!-- TABLA CONTENEDORA DE REPORTE 001 -->   
                    <div role="tabpanel" class="tab-pane fade in active" id="reporte001" style="opacity: 1;">
                        <div class="box box-primary"> 
                        
                            <input type="hidden" id="txtDisponible" value="<?php echo $disponible ?>">
                            <input type="hidden" id="txtNoDisponible" value="<?php echo $nodisponible ?>">
                            <input type="hidden" id="txtLibre" value="<?php echo $libres ?>">
                            <input type="hidden" id="txtBloqueados" value="<?php echo $bloqueados ?>">
                            <input type="hidden" id="txtVendidos" value="<?php echo $vendidos ?>">
                            <input type="hidden" id="txtReservados" value="<?php echo $reservados ?>">
                            <input type="hidden" id="txtCanje" value="<?php echo $canjes ?>">
                            <input type="hidden" id="txtLoteCasa" value="<?php echo $lotecasa ?>">
                            <input type="hidden" id="txtLoteSolo" value="<?php echo $lotesolo ?>">
						
							<div class="row">
								<div class="col-md">
									<div class="card">
										<div class="card-body">
											<h5 class="card-title" hidden>Disponibilidad de Lotes</h5>
											<div id="GraficaPie1" style="height: 200px;"></div>
										</div>
									</div>
								</div>
								<div class="col-md">
									<div class="card">
										<div class="card-body">
											<h5 class="card-title" hidden>Lotes Reservados y Vendidos</h5>
											<div id="GraficaPie2" style="height: 200px;"></div>
										</div>
									</div>
								</div>
								<div class="col-md">
									<div class="card">
										<div class="card-body">
											<h5 class="card-title" hidden>Lotes Reservados y Vendidos</h5>
											<div id="GraficaPie3" style="height: 200px;"></div>
										</div>
									</div>
								</div>
							</div>
                            <div id="BusquedaRegistro">
                                <div class="form-row mt-3">
									<div class="col-md">
										<label class="label-texto">Proyecto</label>
										<select id="bxFiltroProyectoInventario" class="cbx-texto">
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
									<div class="col-md">
										<label class="label-texto">Zona</label>
										<select id="bxFiltroZonaInventario" class="cbx-texto">
											<option selected="true" value="">TODOS</option>
										</select>
									</div>
									<div class="col-md">
										<label class="label-texto">Manzana</label>
										<select id="bxFiltroManzanaInventario" class="cbx-texto">
											<option selected="true" value="">TODOS</option>
										</select>
									</div>
									<div class="col-md">
										<label class="label-texto">Lote</label>
										<select id="bxFiltroLoteInventario" class="cbx-texto">
											<option selected="true" value="">TODOS</option>
										</select>
									</div>
									<div class="col-md">
										<label class="label-texto">Estado</label>
										<select id="bxFiltroEstadoInventario" class="cbx-texto">
											<option selected="true" value="">TODOS</option>
											<?php
												$EstadoReserva = new ControllerCategorias();
												$VerEstadoReserva = $EstadoReserva->VerEstadosLoteInventarioReport();
												foreach ($VerEstadoReserva as $EstadoR) {
											?>
											<option value="<?php echo $EstadoR['ID']; ?>">
											<?php echo $EstadoR['Nombre']; ?>
											</option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md">
										<label class="label-texto">Motivo : </label>
										<select id="bxFiltroMotivoInventario" class="cbx-texto">
										    <option selected="true" value="">TODOS</option>
											<option value="ninguno">NINGUNO</option>
											<?php
												$MotivosReporteReserva = new ControllerCategorias();
												$VerMotivosReporteReserva = $MotivosReporteReserva->VerMotivosLoteInventarioReport();
												foreach ($VerMotivosReporteReserva as $ReporteReservas) {
											?>
											<option value="<?php echo $ReporteReservas['ID']; ?>">
											<?php echo $ReporteReservas['Nombre']; ?>
											</option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md" hidden>
										<label class="label-texto">Vendedor</label>
										<select id="bxFiltroVendedorInventario" class="cbx-texto">
											<option selected="true" value="">TODOS</option>
											<?php
												$Vendedor = new ControllerCategorias();
												$VerVendedor = $Vendedor->VerFiltroVendedor();
												foreach ($VerVendedor as $Vendedores) {
											?>
											<option value="<?php echo $Vendedores['ID']; ?>">
											<?php echo $Vendedores['Nombre']; ?>
											</option>
											<?php } ?>
										</select>
									</div>
									
								</div>
                                <div class="form-row d-flex justify-content-center">
                                    <div class="col-md-4 text-center mt-1">
                                        <button class="btn btn-registro-success" id="btnBuscarInventario" name="btnBuscarInventario"><i
                                                class="fas fa-search"></i> Buscar</button>
                                        <button class="btn btn-registro-primary" id="btnLimpiarInventario" name="btnLimpiarInventario"><i
                                                class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaInventarioReporte"
                                            style="display: none;">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Zona</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Area</th>
                                                    <th>Tipo Casa</th>                                                    
                                                    <th>Valor Terreno</th>
                                                    <th>Valor T+C</th>
													<th>Valor de Venta</th>
													<th>Tipo Moneda</th>
													<th>Estado (Motivo)</th>
                                                    <th>Disponibilidad</th>
                                                    <th>Propietario</th>
												    <th>Vendedor</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <div style="margin: 36px;"> </div>
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaInventario">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Zona</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Area</th>
                                                    <th>Tipo Casa</th>                                                    
                                                    <th>Valor Terreno</th>
                                                    <th>Valor T+C</th>
													<th>Valor de Venta</th>
													<th>Tipo Moneda</th>
													<th>Estado (Motivo)</th>
                                                    <th>Disponibilidad</th>
                                                    <th>Propietario</th>
													<th>Vendedor</th> 													
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

    <script src="../../js/M05_Reportes/M05JS03_Inventario/M05JS03_Inventario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>


</html>