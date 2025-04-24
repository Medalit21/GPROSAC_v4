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

    <!-- LIBRERIAS NOTIFICACIONES -->
 
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
	
	if(!empty($_GET['i'])){
		$IdManzana = $_GET['i'];
		$IdManzana = decrypt($IdManzana, "123");
		$NombreManzana = $_GET['n'];
		$NombreManzana = decrypt($NombreManzana, "123");

		$IdBoton = $_GET['b'];
		$IdBoton = decrypt($IdBoton, "123");

		$IdHeardCard = $_GET['h'];
		$IdHeardCard = decrypt($IdHeardCard, "123");
		$IdBodyCard = $_GET['c'];
		$IdBodyCard = decrypt($IdBodyCard, "123");
	}else{
		$IdManzana = 1;
	}
    
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
            <div class="breadcrumb-ancho mt-1">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Proyecto</a></li>
                    <li><a class="enlace"
                            href="<?php echo $NAME_SERVER."views/M01_Proyecto/M01SM05_Inventario/M01_SM05_Inventario.php" ?>">Inventario</a>
                    </li>
                    <li class="bread-opcion">
                        <div class="row">
                            <div class="page-breadcrumb">
                                <div class="col-12 d-flex no-block align-items-right">
                                    <div class="ml-auto text-right">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="../../home.php">Inicio</a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a
                                                        href="../../../index.php">
                                                        Salir</a></li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid pt-0">
                <div class="box box-primary">
                    <div class="row">
						<input type="hidden" id="val_user" value="<?php echo $valor_user; ?>" />
                        <input type="hidden" id="__ID_MZ" value="<?php echo $IdManzana; ?>" />
                        <input type="hidden" id="__ID_BOTON" value="<?php echo $IdBoton; ?>" />
                        <input type="hidden" id="__ID_HEARD_CARD" value="<?php echo $IdHeardCard; ?>" />
                        <input type="hidden" id="__ID_BODY_CARD" value="<?php echo $IdBodyCard; ?>" />

                        <div class="col-md-12">
                            <div class="d-flex align-items-center p-3 p-3 mt-3  mb-1 text-white rounded shadow-sm"
                                style=" border: solid 1px #003f75;color: #003f75 !important;">
                                <div class="w-100 row titulos">
                                    <div class="col-md m-titulo">
                                        <div class="row">
                                            <label class="col-md-3 label-texto-sm">Proyecto:</label>
                                            <div class="col-md-9">
                                                <select id="bxProyectoInventario" class="cbx-texto">
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
                                        </div>
                                    </div>
                                    <div class="col-md m-titulo">
                                        <div class="row">
                                            <label class="col-md-3 label-texto-sm">Zona:</label>
                                            <div class="col-md-9">
                                                <select id="bxZonaInventario" class="cbx-texto">
                                                    <option selected="true" value="">Seleccionar...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md m-titulo">
                                        <div class="row">
                                            <label class="col-md-3 label-texto-sm">Manzana:</label>
                                            <div class="col-md-9">
                                                <select id="bxManzanaInventario" class="cbx-texto">
                                                    <option selected="true" value="">Seleccionar...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md m-titulo" hidden>
                                        <div class="row">
                                            <label class="col-md-3 label-texto-sm">Lotes:</label>
                                            <div class="col-md-9">
                                                <select id="bxLotesInventario" class="cbx-texto">
                                                    <option selected="true" value="">Seleccionar...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>                      

                        <div class="col-md-12">
                            <div class="d-flex align-items-center p-3 mt-0  mb-3 text-white rounded shadow-sm"
                                style=" border: solid 1px #003f75;color: #003f75 !important;">
                                <div class="w-100 row " id="LeyedaEstados">
                                  
                                </div>

                            </div>
                        </div>

                         <div class="col-md-12">
                            <div class="d-flex align-items-center p-3 p-3 mt-3  mb-1 text-white rounded shadow-sm"
                                style=" border: solid 1px #003f75;color: #003f75 !important;">
                                <div class="w-100 row titulos">
                                    <div class="col-md m-titulo">
                                        <h1 class="h6 mb-0 lh-1 text-left title-01">PROYECTO: <span
                                                id="htmlNombreProyecto"></span></h1>
                                    </div>
                                    <div class="col-md m-titulo">
                                        <h1 class="h6 mb-0 lh-1 text-left title-01" id="htmlNombreZona"></h1>
                                    </div>
                                    <div class="col-md m-titulo">
                                        <div class="lh-1">
                                            <h1 class="h6 mb-0 lh-1 text-left title-01" id="htmlNombreManzana"></h1>
                                        </div>
                                    </div>
                                    <div class="col-md m-titulo">
                                        <div class="lh-1">
                                            <h1 class="h6 mb-0 lh-1 text-left title-01">TOTAL LOTES: <span
                                                    id="totalLote">0</span></h1>
                                        </div>
                                    </div>
                                    <div class="col-md m-titulo">
                                        <div class="row">
                                            <label class="col-md-3 label-texto-sm">Situación:</label>
                                            <div class="col-md-9">
                                                <select id="cbxFiltroEstado" class="cbx-texto">
                                                    <option value="">Todos</option>
                                                    <option value="1">Libre</option>
                                                    <option value="2">Reservado</option>
                                                    <option value="3">Por Vencer</option>
                                                    <option value="4">Vencido</option>
                                                    <option value="5">Vendido Terreno</option>
                                                    <option value="6">Vendido Terreno + Casa</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                      
                        <div class="col-md-12"><br>
                            <div class="row">
                                <div class="col-md-3" hidden>
                                    <div id="accordionZona">

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row" id="accordion">

                                    </div>
                                </div>
                            </div>

                        </div>

                         
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- POP UP LIBERAR -->
    <div class="modal fade" id="modalLiberar" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
        <?php require_once "pop-up/M01SM05_POPUP_Liberar.php"; ?>
    </div>
    <!-- POP UP VER SUCESO-->
    <div class="modal fade" id="modalSucesos" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
        <?php require_once "pop-up/M01SM05_POPUP_Sucesos.php"; ?>
    </div>

    <!-- POP UP VER SUCESO-->
    <div class="modal fade" id="modalCronograma" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
        <?php require_once "pop-up/M01SM05_POPUP_Cronograma.php"; ?>
    </div>
	

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


    <script src="../../js/M01_Proyecto/M01JS05_Inventario/M01JS05_Lotes.js?v=1.2.0"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/xlsx.full.min.js?v=1.1.1"></script>
</body>

</html>