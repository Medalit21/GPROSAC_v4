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
    <title><?php echo $NAME_APP; ?> - Cronograma de Pagos</title>
    <link rel="icon" href="../../../images/<?php echo $NAME_LOGO; ?>" type="image/png" />
    <!-- Custom CSS -->
    <link href="../../assets/libs/flot/css/float-chart.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../../dist/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
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
        <div class="page-wrapper fond-back">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Cronograma de Pagos</a></li>
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

                    <div class="row" id="PanelFiltros">
                        <input type="hidden" id="txtUSR" value="<?php echo $user_sesion; ?>"> 
                        <div class="col-md"><br>
                            <label class="label-texto">Cliente </label>
                            <!--<input type="number" id="txtFiltroDocumentoCP" class="caja-texto" placeholder="Nro Documento">-->
                            <select id="txtFiltroDocumentoCP" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                        
                        <div class="col-md"><br>
                            <label class="label-texto">Proyecto </label>
                            <select id="bxFiltroProyectoCP" class="cbx-texto">
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

                        <div class="col-md"><br>
                            <label class="label-texto">Zona </label>
                            <select id="bxFiltroZonaCP" class="cbx-texto">
                                <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md"><br>
                            <label class="label-texto">Manzana </label>
                            <select id="bxFiltroManzanaCP" class="cbx-texto">
                                <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md"><br>
                            <label class="label-texto">Lote </label>
                            <select id="bxFiltroLoteCP" class="cbx-texto">
                                <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            </select>
                        </div>
						<div class="col-md"><br>
                            <label class="label-texto">Estado </label>
                            <select id="bxFiltroEstadoCP" class="cbx-texto">
								<option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                <?php
                                    $Estados = new ControllerCategorias();
                                    $EstadosVer = $Estados->VerEstadosEC();
                                    foreach ($EstadosVer as $Proy) {
                                ?>
                                <option value="<?php echo $Proy['ID']; ?>">
                                <?php echo $Proy['Nombre']; ?>
                                </option>
                                <?php }?>
                            </select>
                        </div>							
                    </div>

                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-12 text-center mt-1" id="PanelBotons">
                            <button id="btnBuscarCP" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button>
                            <button id="btnLimpiarCP" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                            <button id="btnExportarPdf" type="button" class="btn btn-registro-danger"><i class="fas fa-file-pdf"></i> Individual</button>
                            <button id="btnExportarMasivoPdf" type="button" class="btn btn-registro-danger" hidden><i class="fas fa-file-pdf"></i> Masivo</button>
                            <button id="btnExportarExcel" type="button" class="btn btn-registro-success" hidden><i class="fas fa-file-excel"></i> Excel</button>
                            <button id="btnModificar" type="button" class="btn btn-registro" disabled><i class="fas fa-pencil-alt"></i> Modificar</button>
                            <button id="btnFinalizar" type="button" class="btn btn-registro-success" style="display: none;"><i class="fas fa-check"></i> Finalizar</button>
                        </div>
                    </div>

                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-4 text-center mt-1" id="PanelLotes" style="display: none;">
                            <label class="label-texto">LOTES COMPRADOS</label>
                            <select id="bxLotesAdquiridos" class="cbx-texto">
                                <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-4 text-center mt-1" id="BtnPanelLotes" style="display: none;">
                            <input type="hidden" name="" id="txtID_LOTE">
                            <button id="btnExportarPdfCliente" type="button" class="btn btn-registro-danger"><i class="fas fa-file-pdf"></i> PDF</button>
                            <button id="btnExportarExcel" type="button" class="btn btn-registro-success" hidden><i class="fas fa-file-excel"></i> Excel</button>
                        </div>
                    </div>              

                </div>

                 <!-- editor -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Create the editor container -->
                                <div id="editor">                                   
                                    
                                     <div class="form-row">
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_DATOS" placeholder="APELLIDOS Y NOMBRES" style="background-color: #7BB1FF; color: white" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_UBICACION" placeholder="UBICACION" style="background-color: #FF8B7B; color: white" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_PRECTOTAL" placeholder="PRECIO TOTAL DEL LOTE : 0.00" readonly>
                                        </diV>
                                    </div>
                                    <div class="form-row">
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_PRECVENTA" placeholder="PRECIO DE VENTA : 0.00" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_INTERESES" placeholder="INTERESES : 0" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_FINANCIAMIENTO" placeholder="TIEMPO FINANCIAMIENTO: AÑOS (LETRAS)" readonly>
                                        </diV>
                                        
                                    </div>
                                    <div class="form-row">
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_CAPVIVO" placeholder="CAPITAL VIVO : 0.00" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_SALDOPENDIENTE" placeholder="SALDO PENDIENTE : 0.00" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_TOTCANCELADO" placeholder="TOTAL CANCELADO : 0.00" readonly>
                                        </diV>
                                        
                                    </div>
                                    <div class="form-row">
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_EMAIL" placeholder="Correo : Ninguno" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_CELULAR" placeholder="Teléfono : Ninguno" readonly>
                                        </diV>
                                        <diV class="col-md">
                                            <input type="text" class="form-control text-cronograma" id="TXT_ENTREGA" placeholder="Fecha Entrega Casa : 0000-00-00" readonly>
                                        </diV>
                                        
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12 text-center cancel" id="PanelCancelado" style="display: none;">C  A  N  C  E  L  A  D  O</div>
                                <div class="col-md-12 text-center cancel" id="PanelDevuelto" style="display: none">D E V U E L T O</div>
                                <br> 
                                <!-- Tabs -->
                                <div class="card" id="PanelEdicionCronograma" style="display: none">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#agregar" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Agregar Letras</span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#masivo" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Edici&oacute;n Masiva</span></a> </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active p-20" id="agregar" role="tabpanel">
                                            <input type="hidden" id="ID_VNTA">
                                            <div class="row">
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Tipo Letra<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                    <select id="cbxTipoLetraFormat" class="cbx-texto">
                                                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                                        <option value="1">Cuota Inicial (C.I.)</option>
                                                        <option value="2">Letra (1,2,3..)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Ubicar debajo de<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                    <select id="cbxUbicarLetraFormat" class="cbx-texto">
                                                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Fecha Vencimiento</label>
                                                    <input type="date" id="txtFecVencimientoFormat" class="caja-texto">
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Letra</label>
                                                    <input type="number" id="txtLetraFormatNumber" class="caja-texto">
                                                    <input type="text" id="txtLetraFormatText" class="caja-texto text-center" value="C.I." style="display: none;" readonly>
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Monto Letra</label>
                                                    <input type="text" id="txtMontoLetraFormat" class="caja-texto text-center" value="0.00">
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Intereses</label>
                                                    <input type="text" id="txtInteresesFormat" class="caja-texto text-center" value="0.00">
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Amortizaci&oacute;n</label>
                                                    <input type="text" id="txtAmortizacionFormat" class="caja-texto text-center" value="0.00">
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Capital Vivo</label>
                                                    <input type="text" id="txtCapitalVivoFormat" class="caja-texto text-center" value="0.00">
                                                </div>
                                            </div>
                                            <div class="form-row d-flex justify-content-center">
                                                <div class="col-md-4 text-center mt-1">
                                                    <button id="btnAgregarLetraFormat" type="button" class="btn btn-registro"><i class="fas fa-save"></i> Guardar</button>
                                                    <button id="btnLimpiarLetraFormat" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="tab-pane  p-20" id="masivo" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Letra Inicio<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                    <select id="cbxLetraInicio" class="cbx-texto">
                                                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Letra Termino<small id="txtNumeroCVHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <select id="cbxLetraFin" class="cbx-texto">
                                                        <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Monto Letra<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                    <input type="number" id="txtMondoLetra" class="caja-texto" value="0.00">
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">TEA(%)<small id="txtNumeroCVHtml" class="form-text text-muted-validacion text-danger ocultar-info">
                                                        </small></label>
                                                    <input type="number" id="txtTEALetra" class="caja-texto" value="0">
                                                </div>
                                                <div class="col-md">
                                                    <label for="" class="label-texto">Fecha Inicio<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                    <input type="date" id="txtFechaInicio" class="caja-texto" value="">
                                                </div>
                                                <div class="col-md" hidden>
                                                    <label for="" class="label-texto">Dias Vencimiento<small id="txtSerieCVHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
                                                    <input type="text" id="txtDiasVencimiento" class="caja-texto" value="">
                                                </div>
                                            </div>
                                            <div class="form-row d-flex justify-content-center">
                                                <div class="col-md-4 text-center mt-1">
                                                    <button id="btnGuardarCambiosCP" type="button" class="btn btn-registro"><i class="fas fa-save"></i> Guardar</button>
                                                    <button id="btnLimpiarCambiosCP" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                                <div class="fn-frm-dt">                                  
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaCronogramaReporte" style="display: none;">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Letra</th>
													<th>Monto Letra</th>
                                                    <th>Intereses</th>
                                                    <th>Amortización</th>
													<th>Capital Vivo</th>
													<th>Total Pagado</th>
													<th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <br><br>
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaCronograma">
                                            <thead class="cabecera">
                                                <tr>
                                                    <th></th>
                                                    <th>Fecha</th>
                                                    <th>Letra</th>
													<th>Monto Letra</th>
                                                    <th>Intereses</th>
                                                    <th>Amortización</th>
													<th>Capital Vivo</th>
													<th>Total Pagado</th>
													<th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- PopUp  -->
        <!-- ============================================================== -->
        <div class="modal fade" id="modalEditarCronograma" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
            <?php
                require_once "pop-up/M02SM02_POPUP_editar.php";
            ?>
        </div>

        <div class="modal fade" id="modalAuditoriaCronograma" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
            <?php
                require_once "pop-up/M02SM02_POPUP_auditoria.php";
            ?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!--<script src="../../assets/libs/jquery/dist/jquery.min.js"></script>-->
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

    <script src="../../js/M02_Clientes/M02JS02_CronogramaPagos/M02JS02_CronogramaPagos.js?v=1.1.1"></script>

    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#txtFiltroDocumentoCP').select2();
	});
</script>