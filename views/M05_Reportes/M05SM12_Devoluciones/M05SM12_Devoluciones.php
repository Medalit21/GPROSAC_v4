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
    <title><?php echo $NAME_APP.' - Reporte Devoluciones'; ?></title>
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

<body>

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
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Reportes</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Devoluciones</a></li>
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
					<div id="BusquedaVenta002">  
						<div class="form-row mt-3">
						    <div class="col-md-12">
								<label class="titulo-cont">FILTROS DE B&Uacute;SQUEDA</label>
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
                                        <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                        <?php echo $Cliente['Nombre']; ?>
                                        </option>
                                        <?php }?>
                                </select>
                            </div>
							<div class="col-md">
								<label class="label-texto">Desde:</label>
								<input type="date" id="txtDesdeFiltro" class="caja-texto"
									placeholder="Documento Cliente" value="">
							</div>
							<div class="col-md">
								<label class="label-texto">Hasta: </label>
								<input type="date" id="txtHastaFiltro" class="caja-texto"
									placeholder="Documento Cliente" value="">
							</div>
							<div class="col-md">
								<label class="label-texto">Estado : </label>
								<select id="bxFiltroEstado" class="cbx-texto">
									<option selected="true" value="">TODOS</option>
                                    <option value="1">LIBRE</option>
                                    <option value="2">RESERVADO</option>
									<?php
										$MotivosReporteReserva = new ControllerCategorias();
										$VerMotivosReporteReserva = $MotivosReporteReserva->VerMotivosReporteVentas();
										foreach ($VerMotivosReporteReserva as $ReporteReservas) {
									?>
									<option value="<?php echo $ReporteReservas['ID']; ?>">
									<?php echo $ReporteReservas['Nombre']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md">
								<label class="label-texto">Lote : </label>
								<select id="bxFiltroLote" class="cbx-texto">
									<option selected="true" value="">TODOS</option>
									<?php
										$FiltroLote = new ControllerCategorias();
										$VerFiltroLotes = $FiltroLote->VerFiltroLote();
										foreach ($VerFiltroLotes as $FiltroLotes) {
									?>
									<option value="<?php echo $FiltroLotes['ID']; ?>">
									<?php echo $FiltroLotes['Nombre']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md">
								<label class="label-texto">Tipo Casa : </label>
								<select id="bxFiltroTipoCasa" class="cbx-texto">
									<option selected="true" value="">TODOS</option>
									<?php
										$FiltroLote = new ControllerCategorias();
										$VerFiltroLotes = $FiltroLote->VerTipoCasaReporte();
										foreach ($VerFiltroLotes as $FiltroLotes) {
									?>
									<option value="<?php echo $FiltroLotes['ID']; ?>">
									<?php echo $FiltroLotes['Nombre']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md">
								<label class="label-texto">Vendedor : </label>
								<select id="bxFiltroVendedor" class="cbx-texto">
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
								<button class="btn btn-registro-success" id="btnBuscar" name="btnBuscarRegistro">
									<i class="fas fa-search"></i> Buscar
								</button>
								<button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiarReporte">
									<i class="fas fa-sync-alt"></i> Limpiar
								</button>
							</div>
						</div>
						<div class="fn-frm-dt">
							<div class="table-responsive scroll-table">
								<table class="table table-striped table-bordered" id="TablaReservasReporte"
									style="display: none;">
									<thead class="cabecera">
										<tr>
										    <th>Fecha Venta</th>
											<th>Zona</th>
											<th>Mz - Lote</th>
											<th>Area m<sup>2</sup></th>
											<th>Casa</th>
											<th>Tipo Casa</th>
											<th>Estado</th>
                                            <th>Cliente</th>
											<th>Moneda</th>											
											<th>Cuota inicial</th>
											<th>Valor Terreno</th>
											<th>Valor Casa</th>
											<th>Total</th>											
											<th>Vendedor</th>
										</tr>
									</thead>
									<tbody class="control-detalle">
									</tbody>
								</table>
								<div style="margin: 36px;"> </div>
								<table class="table table-striped table-bordered table-hover w-100"
									id="TablaReservas">
									<thead class="cabecera">
										<tr>
										    <th>Fecha Venta</th>
											<th>Zona</th>
											<th>Mz - Lote</th>
											<th>Area m<sup>2</sup></th>
											<th>Casa</th>
											<th>Tipo Casa</th>
											<th>Estado</th>
                                            <th>Cliente</th>
											<th>Moneda</th>											
											<th>Cuota inicial</th>
											<th>Valor Terreno</th>
											<th>Valor Casa</th>
											<th>Total</th>	
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

    <script src="../../js/M05_Reportes/M05JS12_Devoluciones/M05JS12_Devoluciones.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#txtdocumentoFiltro').select2();
	});
</script>