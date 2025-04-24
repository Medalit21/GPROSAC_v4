<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php 

require_once "../../../config/configuracion.php";
require_once "../../../config/control_sesion.php"; 
require_once "../../../config/conexion_2.php";
require_once "../../../controllers/ControllerCategorias.php";
?>



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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>
    <?php
		/*session_start();
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
            $variable = encrypt($valor_user, "123");
        }
		
		require_once "../../../config/conexion_2.php";
		require_once "../../../config/control_sesion.php";
		require_once "../../../controllers/ControllerCategorias.php";
		$user_sesion = encrypt($valor_user,"123");*/
	?>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
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
                    <li><a class="enlace" href="javascript:void(0)">Proyecto</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Registro Proyecto</a></li>
                </ol>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <input type="text" id="d_u_sn" value="<?php echo $variable; ?>" hidden readonly>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="botones-acciones-2">
                        <button id="nuevo" type="button" class="btn btn-registro">
							<i class="fas fa-file-alt"></i>
                                Nuevo</button>
                            <button id="modificar" type="button" class="btn btn-registro">
							<i class="fas fa-edit"></i>
                                Modificar</button>
                            <button id="guardar" type="button" class="btn btn-registro" disabled="">
							<i class="fas fa-save"></i> Guardar</button>
                            <button id="cancelar" type="button" class="btn btn-registro"><i
                                    class="fas fa-minus-circle"></i> Cancelar</button>
                            <button id="eliminar" type="button" class="btn btn-registro">
							<i class="fas fa-trash"></i>
                                Elminar</button>
                            <button id="busqueda_avanzada" type="button" class="btn btn-registro">
							<i class="fas fa-list"></i>
                                Lista</button>
                        </div>
                    </div>

                    <div id="NuevoRegistro">
                        <?php include_once "M01SM01_NuevoRegistro.php";?>
                    </div>


                    <div id="RegistroProyecto">
                        <?php include_once "M01SM01_ControlRegistro.php";?>
                    </div>

                    <div id="BusquedaRegistro">
                        <div class="form-row mt-3">
                            <div class="col-md-12">
                                <label class="titulo-cont">Filtros de Busqueda:</label>
                            </div>
							<div class="col-md">							
								<label class="label-texto">Proyecto </label>
								<select id="txtFiltroNombre" class="cbx-texto">
									<option selected="true" value="">Seleccionar...</option>
									<?php
										$verProyecto = new ControllerCategorias();
										$verProyectos = $verProyecto->VerProyectos();
										foreach ($verProyectos as $item) {
									?>
									<option value="<?php echo $item['ID']; ?>">
										<?php echo $item['Nombre']; ?>
									</option>
									<?php }?>
								</select>
							</div>
                            <!--<div class="col-md">
                                <label class="label-texto">Nombre:</label>
                                <input type="text" id="txtFiltroNombre" class="caja-texto"
                                    placeholder="Ingrese la Descripción" value="">
                            </div>-->

                            <div class="col-md">
                                <label class="label-texto">Departamento </label>
                                <select id="bxFiltroDepartamento" class="cbx-texto">
                                    <option selected="true" value="">Seleccione...
                                    </option>
                                    <?php
                                        $departam = new ControllerCategorias();
                                        $VerDepart = $departam->VerDepartamento();
                                        foreach ($VerDepart as $depto) {
                                    ?>
                                    <option value="<?php echo $depto['ID']; ?>">
                                    <?php echo $depto['Nombre']; ?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="label-texto">Provincia </label>
                                <select id="bxFiltroProvincia" class="cbx-texto">
                                    <option selected="true" value="" disabled="disabled">Seleccione...
                                    </option>
                                </select>

                            </div>
                            <div class="col-md">
                                <label class="label-texto">Distrito </label>
                                <select id="bxFiltroDistrito" class="cbx-texto">
                                    <option selected="true" value="" disabled="disabled">Seleccione...
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-4 text-center mt-1">
                                <button class="btn btn-registro-success" id="btnBuscarRegistro" name="btnBuscarRegistro"><i
                                        class="fas fa-search"></i> Buscar</button>
                                <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiar"><i
                                        class="fas fa-sync-alt"></i> Limpiar</button>
                            </div>
                        </div>
                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <div class="table-responsive scroll-table">
                                <table class="table table-striped table-bordered" id="TablaProyectosReporte"
                                    style="display: none;">
                                    <thead class="cabecera">
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Departamento</th>
                                            <th>Provincia</th>
                                            <th>Distrito</th>
                                            <th>Area</th>
                                            <th>Zonas</th>
                                            <th>Manzanas</th>
                                            <th>Lotes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                                <div style="margin: 36px;"> </div>
                                <table class="table table-striped table-bordered table-hover w-100"
                                    id="TablaProyectos">
                                    <thead class="cabecera">
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Departamento</th>
                                            <th>Provincia</th>
                                            <th>Distrito</th>
                                            <th>Area</th>
                                            <th>Zonas</th>
                                            <th>Manzanas</th>
                                            <th>Lotes</th>
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
            <!-- POP UP REGISTRO EMPLEADO -->
            <div class="modal fade" id="modalProyecto" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M01SM01_POPUP_ActualizarProyecto.php";
                ?>
            </div>

            <!-- POP UP ACTUALIZAR ZONA -->
            <div class="modal fade" id="modalEditarZona" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M01SM02_POPUP_EditarZona.php";
                ?>
            </div>

            <!-- POP UP ACTUALIZAR MANZANA -->
            <div class="modal fade" id="modalEditarManzana" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M01SM03_POPUP_EditarManzana.php";
                ?>
            </div>

             <!-- POP UP ACTUALIZAR LOTE -->
             <div class="modal fade" id="modalEditarLote" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M01SM04_POPUP_EditarLote.php";
                ?>
            </div>
			
			  <!-- POP UP EDIT TIPO CASA  -->
            <div class="modal fade" id="modalEditarTipoCasa" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M01SM06_POPUP_EditarTipoCasa.php";
                ?>
            </div>
			
             <!-- POP UP NUEVO REGISTRO TIPO CASA  -->
             <div class="modal fade" id="modalNuevoTipoCasa" tabindex="-1" role="dialog" data-backdrop="static"
            aria-labelledby="myModalLabel">
                <?php
                require_once "pop-up/M01SM05_POPUP_TipoCasa.php";
                ?>
            </div>


        </div>
        <!-- ============================================================== -->
        <!-- ========= FOOTER ========= -->        
        <?php include_once "../../recursos/footer.php";?>        
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

    <script src="../../js/M01_Proyecto/M01JS01_RegistrarProyecto/M01JS01_RegistrarProyecto.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>