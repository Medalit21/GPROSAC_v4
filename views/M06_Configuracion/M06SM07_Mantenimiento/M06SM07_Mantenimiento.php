<!DOCTYPE html>
<html dir="ltr" lang="es">
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
    <link rel="stylesheet" type="text/css" href="../../code/select2/select2.min.css?v=<?php echo time(); ?>">
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
                    <li><a class="enlace" href="javascript:void(0)">Mantenimiento</a></li>
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
							<br>
							<br>
                            <fieldset>
                                <legend>General</legend>
                                <div id="formularioRegistrarGeneral">
									<div class="form-row" style="margin-top: -8px;" >
										<div class="col-md-2">
											<input type="hidden" id="__ID_DATOS_PERSONAL">
											
											<label class="label-texto">Categoría <code class="text-danger">*</code>
												<small id="cbxCategoriaHtml" class="form-text text-muted-validacion text-danger ocultar-info">
												</small>
											</label>
											<select id="cbxCategoria" name="cbxCategoria" class="cbx-texto">
												<option selected="true" value="" disabled="disabled">Seleccione...</option>
												<?php
													$Cat = new ControllerCategorias();
													
													$VerCats = $Cat->VerCatDetalle();
													foreach ($VerCats as $ca) {
												?>
												<option value="<?php echo $ca['codigo_tabla']; ?>"><?php echo $ca['nombre_corto']; ?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-md-2">
											<label class="label-texto">Código Item <small id="txtCoditemHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input id="txtCoditem" name="txtCoditem" class="caja-texto" readonly>
										</div>
										<div class="col-md-5">
											<label class="label-texto">Nombre <code class="text-danger">*</code><small id="txtNombresHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtNombres" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-3">
											<label class="label-texto">Abreviatura <code class="text-danger">*</code><small id="txtAbreviatHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtAbreviat" class="caja-texto" style="text-transform:uppercase;" value="" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Texto 1 <small id="txtTexto1Html" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtTexto1" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Texto 2 <small id="txtTexto2Html" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtTexto2" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Texto 3 <small id="txtTexto3Html" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtTexto3" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Texto 4 <small id="txtTexto4Html" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtTexto4" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Texto 5 <small id="txtTexto5Html" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtTexto5" class="caja-texto" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Estado</label>
											<select id="cbxEstado" class="cbx-texto">
												<option selected="true" value="ACTI">ACTIVO</option>
												<option value="INAC">INACTIVO</option>
											</select>
										</div>
										<div class="col-md-6">
											<div class="fw-normal fs-7 fst-italic text-gray-900 mb-1 mt-3">
												<span class="fw-bolder text-info">Nota:</span> 
												Los campos en asterisco (<i class="mdi ms-1 mdi-asterisk icon-dual-danger" style="color: #da542e !important;" data-bs-toggle="tooltip" aria-label="Info"></i>) son obligatorios.        
											</div>
										</div>
									</div>
                                </div>
                            </fieldset>
                        </div>
                    </div>					
                    <div id="contenido_lista">
                        <!-- SECCIÓN DE BÚSQUEDA DE DATOS PERSONAL -->
                        <div class="fn-frm-dt mt-3" id="campo-busqueda">
                            <label class="titulo-cont">Filtrar Clientes</label>
                            <div class="p-campos">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label class="label-texto">Código de tabla  </label>
                                        <!--<input type="text" id="txtFiltroDatoCliente" class="caja-texto" placeholder="Nro Documento">-->
                                        <select id="cbxFiltroTrabajador" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                            <option selected="true" value="" disabled="disabled">TODOS</option>
                                            <?php
                                                $Clientes = new ControllerCategorias();
                                                $ClientesVer = $Clientes->VerFiltroMant();
                                                foreach ($ClientesVer as $Cliente) {
                                            ?>
                                            <option value="<?php echo $Cliente['codigo_tabla']; ?>" style="font-size: 11px;">
                                            <?php echo $Cliente['nombre_largo']; ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
										<label class="label-texto">Estado</label>
										<select id="cbxFiltroEstado" class="cbx-texto">
											<option selected="true" value="ACTI">ACTIVO</option>
											<option value="INAC">INACTIVO</option>
										</select>
									</div>
                                    <div class="col-md-6" style=" margin-top: 15px;">
                                        <button class="btn btn-registro-success" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
                                        <button class="btn btn-registro-primary" id="btnTodos"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <br>
                            <div class="table-resp">
                                <div class="table-responsive scroll-table">
                                    <table class="table table-striped table-bordered" id="tableRegistReportPersonal"
                                        style="display: none;">
                                        <thead class="cabecera">
                                            <tr>
                                                <th>COD TABLA</th>
                                                <th>NOMBRE L</th>
                                                <th>NOMBRE CORTO</th>
                                                <th>FECHA NACIMIENTO</th>
                                            </tr>
                                        </thead>
                                        <tbody class="control-detalle">
                                        </tbody>
                                    </table>
                                    <div style="margin: 36px;"> </div>
                                    <table id="tablaDatosPersonal" class="table table-striped table-bordered table-hover w-100">
                                        <thead class="cabecera">
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">COD TABLA</th>
                                                <th scope="col">NOMBRE Cab.</th>
                                                <th scope="col">NOMBRE L</th>
                                              
                                                <th scope="col">NOMBRE CORTO</th>
                                                <th scope="col" style="text-align: center;">ESTADO</th>
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
        <!--<div class="modal fade" id="modalVerAdjunto" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
        <?php
            //require_once "pop-up/M06MD02_POPUP_VerAdjunto.php";
        ?>
		</div>-->
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

    <script src="../../code/select2/select2.min.js"></script>
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

    <script src="../../js/M06_Configuracion/M06JS07_Mantenimiento/M06JS07_Index.js?v=1.1.1"></script>

    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">
	
	
	<script>
		/*$(document).ready(function() {
			$('#cbxCategoria').on('change', function() {
				var id = $(this).val();
				if (id) {
					$.ajax({
						url: '../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php',
						type: "POST",
						dataType: "json",
						data: {
							"ReturnTraerCodItem": true,
							"IdRegistro": id
						},
						success: function(data) {
							
							if (data.status === 'ok') {
								$("#txtCoditem").val(data.data.total);
							console.log(data.data.total);
							} else {
								alert(data.data); // Show error message
							}
						},
						error: function(xhr, status, error) {
							console.error("AJAX Error: " + status + ": " + error);
						}
					});
				} else {
					$("#txtCoditem").val('');
				}
			});
		});*/
		
		
		$(document).ready(function() {
			$('#cbxCategoria').on('change', function() {
				var categoryId = $(this).val();
				if (categoryId) {
					$.ajax({
						url: '../../models/M06_Configuracion/M06MD07_Mantenimiento/M06MD07_Mantenimiento.php',
						type: "POST",
						dataType: "json",
						data: {
							"ReturnTraerCodItem": true,
							"IdRegistro": categoryId
						},
						success: function(response) {
							if (response.status === 'ok') {
								$("#txtCoditem").val(response.data.total);
								console.log("New code item:", response.data.total);
							} else {
								console.error("Error:", response.data);
								alert("Error: " + response.data);
							}
						},
						error: function(xhr, status, error) {
							console.error("AJAX Error:", status, error);
						}
					});
				} else {
					$("#txtCoditem").val('');
				}
			});
		});

	</script>

	
</body>


</html>


