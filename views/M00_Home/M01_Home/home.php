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
            $sesion = "";
        }else{
            $valor_user = $_SESSION['usu'];
            $_SESSION['variable_user'] = $valor_user;
            $variable = encrypt($valor_user, "123");
        }
	
        require_once "../../../config/configuracion.php";
        require_once "../../../config/conexion_2.php";
        require_once "../../../config/control_sesion.php";
        require_once "../../../controllers/ControllerCategorias.php";
        //require_once "../../../controllers/ControllerHome.php";
        $user_sesion = encrypt($valor_user,"123");
		
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
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <input type="text" id="d_u_sn" value="<?php echo $variable; ?>" hidden readonly>
                <div class="col-lg-12">
                    <div class="row">

                        <div class="col-md-4">
                            <label class="label-texto marcar-n">Proyecto: </label>
                            <select id="bxProyectoDato" class="form-control">
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
                    </div>
                    <br>
                    <div class="row">                        
                        <div class="col-md">
                            <div class="bg-dark p-10 text-white text-center">
                                 <small class="font-light">TOTAL ZONAS</small>
                                 <input type="text" class="m-b-0 sin-fondo" id="txtTotalZonas" placeholder="0" readonly>
                             </div>                            
                        </div>
                        <div class="col-md">
                            <div class="bg-dark p-10 text-white text-center">
                                 <small class="font-light">TOTAL MANZANAS</small>
                                 <input type="text" class="m-b-0 sin-fondo" id="txtTotalManzanas" placeholder="0" readonly>
                             </div>                              
                        </div>
                        <div class="col-md">
                            <div class="bg-dark p-10 text-white text-center">
                                 <small class="font-light">TOTAL LOTES</small>
                                 <input type="text" class="m-b-0 sin-fondo" id="txtTotalLotes" placeholder="0" readonly>
                             </div>                              
                        </div>
                        <div class="col-md">
                            <div class="bg-dark p-10 text-white text-center">
                                 <small class="font-light">TOTAL CLIENTES</small>
                                 <input type="text" class="m-b-0 sin-fondo" id="txtTotalClientes" placeholder="0" readonly>
                             </div>                             
                        </div>
                        <div class="col-md">
                            <div class="bg-dark p-10 text-white text-center">
                                 <small class="font-light">TOTAL VENTAS</small>
                                 <input type="text" class="m-b-0 sin-fondo" id="txtTotalVentas" placeholder="0" readonly>
                             </div>                         
                        </div>       
                    </div>
                    
                    <br>
                    <div class="row text-center">
                        
                        <div class="col-md">
                            <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalLibres">
                                    <small class="label-texto marcar-n blanc">Libres</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalLibres" placeholder="0" readonly>
                                </div>
                            </div>                             
                        </div>
                        <div class="col-md">
                            <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalReservados">
                                    <small class="label-texto marcar-n blanc">Reservados</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalReservados" placeholder="0" readonly>
                                </div>
                            </div>                           
                        </div>
                        <div class="col-md">
                            <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalPorVencer">
                                    <small class="label-texto marcar-n blanc">Por Vencer </small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalPorVencer" placeholder="0" readonly>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md">
                             <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalVencido">
                                    <small class="label-texto marcar-n blanc">Vencido</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalVencido" placeholder="0" readonly>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md">
                             <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalVendidoT">
                                    <small class="label-texto marcar-n blanc">Vendido T</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalVendidoT" placeholder="0" readonly>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md">
                             <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalVendidoTC">
                                    <small class="label-texto marcar-n blanc">Vendido T+C</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalVendidoTC" placeholder="0" readonly>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md">
                             <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalBloqueados">
                                    <small class="label-texto marcar-n blanc">Bloqueados</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalBloqueados" placeholder="0" readonly>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md">
                             <div class="card card-hover" id="camps">
                                <div class="box text-center" id="pnlTotalCanjes">
                                    <small class="label-texto marcar-n blanc">Canjes</small>
                                    <input type="text" class="m-b-0 sin-fondo" id="txtTotalCanjes" placeholder="0" readonly>
                                </div>
                            </div>                            
                        </div>
                        
                    </div>
                    
                </div>
                <br>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 fond-panl p-10 text-white">
                            <fieldset>
                                <legend class="text-center titulo-panl">Datos del Proyecto</legend>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label class="label-texto">Nombre:</label>
                                            <input type="text" id="txtNombreProyecto" class="caja-texto"
                                                placeholder="Ingrese la Descripción" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="label-texto">Área:</label>
                                            <input type="text" id="txtAreaProyecto" class="caja-texto"
                                                placeholder="m2" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="label-texto">Inicio del Proyecto:</label>
                                            <input type="date" id="txtInicioProyecto" class="caja-texto"
                                                placeholder="Ingrese la Descripción" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="label-texto">Dirección:</label>
                                            <input type="text" id="txtDireccionProyecto" class="caja-texto"
                                                placeholder="Ingrese la Descripción" readonly>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Departamento:</label>
                                            <select id="bxDepartamentoProyecto" class="cbx-texto" disabled>
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
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="label-texto">Provincia:</label>
                                            <select id="bxProvinciaProyecto" class="cbx-texto" disabled>
                                                <option selected="true" value="" disabled="disabled">Seleccione...
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-texto">Distrito:</label>
                                            <select id="bxDistritoProyecto" class="cbx-texto" disabled>
                                                <option selected="true" value="" disabled="disabled">Seleccione...
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                       <div class="col-lg-6 fond-panl p-10 text-white">
                            <fieldset>
                                <legend class="text-center titulo-panl">Consultar Información de Lotes</legend>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label class="label-texto blanc">Zona:</label>
                                            <select id="bxZonasProyecto" class="cbx-texto">
                                                <option selected="true" value="">Seleccionar...
                                            </select>
                                        </div>                                        
                                        <div class="col">
                                            <label class="label-texto blanc">Manzana:</label>
                                            <select id="bxManzanasProyecto" class="cbx-texto">
                                                <option selected="true" value="todos">Todos
                                            </select>
                                        </div>
										<div class="col">
                                            <label class="label-texto blanc">Total Lotes:</label>
                                            <input type="text" id="txtTotalLotesDetalle" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Libres:</label>
                                            <input type="text" id="txtValorLteLibres" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Reservados:</label>
                                            <input type="text" id="txtValorLteReservados" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Por Vencer:</label>
                                            <input type="text" id="txtValorLtePorVencer" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Vencidos:</label>
                                            <input type="text" id="txtValorLteVencidos" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Vendidos T:</label>
                                            <input type="text" id="txtValorLteVendidosT" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Vendidos T+C:</label>
                                            <input type="text" id="txtValorLteVendidosTC" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Bloqueados:</label>
                                            <input type="text" id="txtValorLteBloqueados" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="label-texto blanc">Lotes Canje:</label>
                                            <input type="text" id="txtValorLteCanjes" class="caja-texto"
                                                placeholder="0" value="" readonly>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>                    
                </div>
                <br><br>
            </div>

        </div>
        
        <!-- ============================================================== -->
        <!-- =========================== FOOTER =========================== -->
        <?php include_once "../../recursos/footer.php"; ?>
        <!-- ============================================================== -->
        <!-- ============================================================== -->

    </div>
    
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="../../dist/js/waves.js"></script>
    <script src="../../dist/js/sidebarmenu.js"></script>
    <script src="../../dist/js/custom.min.js"></script>
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
    <script src="../../datatables/datatables.min.js"></script>
    <script src='../../datatables/select/js/dataTables.select.min.js'></script>
    <script src='../../datatables/paginado/dataTables.keyTable.min.js'></script>
    <script src="../../datatables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="../../datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="../../datatables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
    <script src="../../main.js"></script>
    <script src="../../js/Home/Home_Index.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>