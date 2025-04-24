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
                    <li><a class="enlace" href="javascript:void(0)">Cobranzas</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Pagos Realizados</a></li>
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
                            <button id="btnNuevoPago" type="button" class="btn btn-registro"><i class="fas fa-file-alt"></i> Nuevo</button>
                             <button id="btnPagosRealizados" type="button" class="btn btn-registro-success" style="width: 150px;"><i class="fas fa-file-alt"></i> Pagos Realizados</button>
                        </div>

                        <div class="row">
                            <div class="col-md-12" id="contenido_registro" style="display:block;">
                                <fieldset>
                                    <legend>Datos Cliente</legend>
                                    <input type="hidden" id="txtTipoDocCliente"/>
                                    <input type="hidden" id="txtNacionalidadCliente"/>

                                    <div class="row row-0" style="margin-top: -12px;" id="formularioRegistrarGeneralCliente">
                                        <div class="col-md-12 row">
                                            <label class="col-md-1 label-texto-sm">Documento:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtDocCliente"
                                                maxlength="15" type="text" value="" disabled="">
                                            </div>
                                            <label class="col-md-1 label-texto-sm">Nombres:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtNomCliente" type="text"
                                                value="" disabled="" readonly="">
                                            </div>
                                            <label class="col-md-1 label-texto-sm">A. Paterno:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtApePaternoCliente"
                                                type="text" value="" disabled="" readonly="">
                                            </div>
                                            <label class="col-md-1 label-texto-sm">A. Materno:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtApeMaternoCliente"
                                                type="text" value="" disabled="" readonly="">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12" id="contenedorReservas">
                                <fieldset>
                                    <legend>Mis Pagos</legend>
                                    <div class="row row-0" id="formularioReservasRelacionadasAlCliente">
                                        <div class="col-md-12">
                                            <div class="table-responsive scroll-table"> 

                                                <div class="col-md-12 row">   
                                                    <label class="col-md-1 label-texto-sm">Lotes:</label>
                                                    <div class="col-md-2">
                                                        <select id="cbxProyecto" class="cbx-texto">
                                                            <option>LOTE 01</option>
                                                            <option>LOTE 02</option>
                                                            <option>LOTE 03</option>
                                                            <option>LOTE 04</option>
                                                        </select>
                                                    </div>  
                                                </div><br>                                         
                                                <table class="table table-striped table-bordered" cellspacing="0" id="TablaPagos">
                                                    <thead class="cabecera">
                                                        <tr>
                                                            <th></th>
                                                            <th>Lote </th>
                                                            <th>Área </th>
                                                            <th>Tipo Casa </th>
                                                            <th>Tipo Moneda </th>
                                                            <th>Precio Venta</th>
                                                            <th>Nro Cuotas</th>
                                                            <th>Cuotas Restantes</th>                                          
                                                        </tr>
                                                    </thead>
                                                    <tbody class="control-detalle">
                                                    </tbody>
                                                </table>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <br>
                            <div class="col-md-12" id="contenedorReservas">
                                <fieldset>
                                    <legend>Datos de Pago </legend>
                                    <div class="row row-0" id="formularioRegi">
                                        <div class="col-md-12 row">
                                            <label class="col-md-1 label-texto-sm">Nro de Cuota:</label>
                                            <div class="col-md-2">
                                                <select id="cbxProyecto" class="cbx-texto">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                </select>
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Num. cuotas a pagar:</label>            <div class="col-md-2">
                                                <select id="cbxProyecto" class="cbx-texto">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                </select>
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Importe a pagar:</label>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col">
                                                        <select id="cbxProyecto" class="cbx-texto">
                                                            <option>S/.</option>
                                                            <option>$</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input class="caja-texto tamano-text-10" id="txtFechaVenta" type="text" value="">
                                                    </div>
                                                </div>                                                
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Estado de cuota:</label>            <div class="col-md-2">
                                                <select id="cbxProyecto" class="cbx-texto" style="background-color: red; color: white;">
                                                    <option>PENDIENTE</option>
                                                    <option>VENCIDO</option>
                                                    <option>PAGADO</option>
                                                    <option>POR VENCER</option>
                                                    <option>OBSERVADO</option>
                                                </select>
                                            </div>
                                            <br><br>       
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-12 row">
                                            
                                           <label class="col-md-1 label-texto-sm">Medio de Pago:</label>
                                            <div class="col-md-2">
                                                <select id="cbxProyecto" class="cbx-texto">
                                                    <option>Efectivo</option>
                                                    <option>Banca Movil</option>
                                                    <option>Agente</option>
                                                    <option>Agencia</option>
                                                </select>
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Tipo de Comprobante:</label>            <div class="col-md-2">
                                                <select id="cbxProyecto" class="cbx-texto">
                                                    <option>Factura</option>
                                                    <option>Boleta</option>
                                                    <option>Voucher de pago</option>}
                                                </select>
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Nro de Operación:</label>
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtFechaVenta" type="text" value="">                                                                                          
                                            </div>

                                            <label class="col-md-1 label-texto-sm">Fecha de pago:</label>            
                                            <div class="col-md-2">
                                                <input class="caja-texto tamano-text-10" id="txtFechaVenta" type="date" value="">
                                            </div>
                                            <br><br>     
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-12 row">
                                            
                                           <label class="col-md-1 label-texto-sm">Constancia:</label>
                                            <div class="col-md-5">
                                                <a href="#">constancia_deposito.png</a>                                     
                                            </div>
                                            <br><br>     
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata">
                                        <div class="col-md-6 row">                                            
                                           <label class="col-md-1 label-texto-sm" style="color: red">Obervaciones:</label>
                                            <div class="col-md-12">
                                                <textarea id="txtDescripcion" class="caja-texto cbx-tam descripcion" maxlength="200" style="height: 50px;" disabled></textarea>                                    
                                            </div>    
                                        </div>
                                        <div class="col-md-6 row">                                            
                                           <label class="col-md-4 label-texto-sm">Respuesta Cliente:</label>
                                            <div class="col-md-12">
                                                <textarea id="txtDescripcion" class="caja-texto cbx-tam descripcion" maxlength="200" style="height: 50px;"></textarea>                                 
                                            </div>     
                                        </div>
                                    </div>

                                    <div class="row row-0" id="formularioRegistrata" style="text-align: right;">
                                        <div class="col-md-12 row">
                                             <div class="col-md-12">
                                                <button id="nuevo" type="button" class="btn btn-registro-success"><i class="fas fa-save"></i> Responder</button>                                          
                                            </div>
                                            <br><br>     
                                        </div>
                                    </div>
                                </fieldset>
                                <br><br><br>
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

<script src="../../js/M04_Cobranzas/M04JS01_Cobranzas/M04JS01_NuevoPago.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
<input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime('%Y-%m-%d'); ?>">

</body>

</html>