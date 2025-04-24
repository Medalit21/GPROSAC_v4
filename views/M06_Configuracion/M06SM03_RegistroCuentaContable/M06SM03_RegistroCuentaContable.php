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
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

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
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Configuracion</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Registro Cuenta Contable</a></li>
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
                    <div class="box-header with-border">
                        <div class="botones-acciones-2">
                            <button id="nuevo" type="button" class="btn btn-registro">
								<i class="fas fa-file-alt"></i> Nuevo
							</button>
                            <button id="modificar" type="button" class="btn btn-registro">
								<i class="fas fa-edit"></i>Modificar
							</button>
                            <button id="guardar" type="button" class="btn btn-registro" disabled="">
								<i class="fas fa-save"></i>Guardar
							</button>
                            <button id="cancelar" type="button" class="btn btn-registro">
								<i class="fas fa-minus-circle"></i> Cancelar
							</button>
                            <button id="eliminar" type="button" class="btn btn-registro">
								<i class="fas fa-trash"></i>Eliminar
							</button>
                            <button id="busqueda_avanzada" type="button" class="btn btn-registro">
								<i class="fas fa-list"></i>Lista
							</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="contenido_registro" style="display:none;">
                            <fieldset>
                                <legend>General</legend>
                                <div id="formularioRegistrarDatos">
									<div class="form-row"  style="margin-top: -8px;">
										<div class="col-md-2">
											<input type="hidden" id="__ID_DATOS_CUENTA">
											<label class="label-texto">Descripción corta<small id="txtDescripCortaHtml"
													class="form-text text-muted-validacion text-danger ocultar-info">
												</small>
											</label>
											<input type="text" id="txtDescripCorta" class="caja-texto"
												placeholder="Ejm: Acg" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-3">
											<label class="label-texto">Descripción larga<small id="txtDescripLargaHtml"
													class="form-text text-muted-validacion text-danger ocultar-info">
												</small></label>
											<input type="text" id="txtDescripLarga" class="caja-texto"
												placeholder="Ejm: Acg Soft" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-2">
											<label class="label-texto">Cuenta Contable USD</label><small id="txtCuentaContableUSDHtml"
													class="form-text text-muted-validacion text-danger ocultar-info">
													</small>
											<input id="txtCuentaContableUSD" type="text" class="caja-texto"
												placeholder="Cuenta Contable">
										</div>	
										<div class="col-md-2">
											<label class="label-texto">Cuenta Contable PEN</label><small id="txtCuentaContablePENHtml"
													class="form-text text-muted-validacion text-danger ocultar-info">
													</small>
											<input id="txtCuentaContablePEN" type="text" class="caja-texto"
												placeholder="Cuenta Contable">
										</div>	
										
										<div class="col-md-2">
											<label class="label-texto">Estado <small id="cbxEstadoHtml"
													class="form-text text-muted-validacion text-danger ocultar-info">
												</small>
											</label>
											<select id="cbxEstado" class="cbx-texto">
												<option selected="true" value="SI">Activo</option>
												<option value="NO" >Inactivo</option>
											</select>
										</div>
										<input type="hidden" id="txtempresa" value="000">
										<input type="hidden" id="txtcodigotabla" value="_BANCOS">
									</div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
					
                    <div id="contenido_lista" style="display:none;">
                        <!-- SECCIÓN DE BÚSQUEDA DE DATOS -->
                        <div class="fn-frm-dt mt-3" id="campo-busqueda">
                            <label class="titulo-cont">Filtrar Datos</label>
                            <div class="p-campos">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label class="label-texto">Banco:</label>
                                        <input type="Text" id="txtDescripCortaFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <!--<div class="col-2">
                                        <label class="label-texto">Cuenta Contable:</label>
                                        <input type="Text" id="txtCuentContFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>-->
                                    <div class="col-md-3 text-center" style=" margin-top: 11px;">
                                        <button class="btn btn-registro-success" id="btnBuscar">
											<i class="fas fa-search"></i> Buscar
										</button>
                                        <button class="btn btn-registro-primary" id="btnTodos">
											<i class="fas fa-sync-alt"></i> Limpiar
										</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <br>
                            <div class="table-resp">
                                <div class="table-responsive scroll-table">
                                    <table class="table table-striped table-bordered" id="tableReportCuentCont"
                                        style="display: none;">
                                        <thead class="cabecera">
                                            <tr>
												<th>DESCRIPCION</th>
                                                <th>CUENTA CONTABLE USD</th>                                     
                                                <th>CUENTA CONTABLE PEN</th>                                     
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">
                                        </tbody>
                                    </table>
                                    <div style="margin: 36px;"> </div>
                                    <table id="tablaDatosCuentCont" class="table table-striped table-bordered table-hover w-100">
                                        <thead class="cabecera">
                                            <tr>
												<th scope="col">EDITAR</th>     
												<th scope="col">DESCRIPCION</th>       
												<th scope="col">CUENTA CONTABLE USD</th>       
												<th scope="col">CUENTA CONTABLE PEN</th>
												
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" id="__SECTOR_EMPRESA">

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

    <script src="../../js/M06_Configuracion/M06JS03_RegistroCuentaContable/M06JS03_Index.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>