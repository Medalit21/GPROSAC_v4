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
        //require_once "../../../config/control_sesion.php";
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
        <div>
            <div class="container-fluid">
               
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="reporte001" style="opacity: 1;">
                        <div class="box box-primary">                  
                            <div id="BusquedaRegistro">
                                
                                <div class="form-row mt-3">
                                    <div class="col-md-12">
                                        <label class="titulo-cont">Filtros de Busqueda:</label>
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Nro Documento:</label>
                                        <input type="Text" id="txtdocumentoFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Nombres:</label>
                                        <input type="Text" id="txtNombresFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Apellidos:</label>
                                        <input type="Text" id="txtApellidoFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Periodo:</label>
                                        <select id="bxFiltroPeriodo" class="cbx-texto">
                                            <option selected="true" value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-registro-success" id="btnBuscarRegistro" name="btnBuscarCliente" style="margin-top: 15px">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiarClient" style="margin-top: 15px">
                                            <i class="fas fa-sync-alt"></i> Limpiar
                                        </button>
                                        <button id="btnExportarPdf" class="btn btn-registro-danger" style="margin-top: 15px"><i class="fas fa-file-pdf" style="color:white"></i> Reporte</button>
                                    </div>
                                    
                                </div>
                                <br>
                                <div class="col-md-12" style="width: 100%">
                                    <figure class="highcharts-figure">
                                        <div id="Grafica1"></div>
                                    </figure>
                                </div>
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaUsuarioReporte"
                                            style="display: none;">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                            <tfoot class="class-tfoot" style="text-align: right">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>  
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaUsuario">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                            <tfoot class="control-detalle" style="text-align: right; font-weight: bold;">
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                    <br>
                                    <div class="col-md-12" style="width: 100%">
                                        <figure class="highcharts-figure">
                                        <div id="Grafica2"></div>
                                        </figure>
                                    </div>
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaUsuarioConteoReporte"
                                            style="display: none;">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                             <tfoot class="control-detalle" style="text-align: right; font-weight: bold;">
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                            </tfoot>
                                        </table> 
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaUsuarioConteo">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                             <tfoot class="control-detalle" style="text-align: right; font-weight: bold;">
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>                              
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="reporte002">
                        <div class="box box-primary">
                            <div id="BusquedaRegistro">
                                <div class="form-row mt-3">
                                    <div class="col-md-12">
                                        <label class="titulo-cont">Filtros de Busqueda:</label>
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Nro Documento:</label>
                                        <input type="Text" id="txtdocumentoFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Nombres:</label>
                                        <input type="Text" id="txtNombresFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md" hidden>
                                        <label class="label-texto">Apellidos:</label>
                                        <input type="Text" id="txtApellidoFiltro" class="caja-texto"
                                            placeholder="Escribir aqui...">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="label-texto">Periodo:</label>
                                        <select id="bxFiltroPeriodo2" class="cbx-texto">
                                            <option selected="true" value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-registro-success" id="btnBuscarRegistro2" name="btnBuscarCliente" style="margin-top: 15px">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                        <button class="btn btn-registro-primary" id="btnLimpiar2" name="btnLimpiarClient" style="margin-top: 15px">
                                            <i class="fas fa-sync-alt"></i> Limpiar
                                        </button>
                                        <button id="btnExportarPdf2" class="btn btn-registro-danger" style="margin-top: 15px"><i class="fas fa-file-pdf" style="color:white"></i> Reporte</button>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12" style="width: 100%">
                                    <figure class="highcharts-figure">
                                        <div id="Grafica3"></div>
                                    </figure>
                                </div>                                
                                <div class="fn-frm-dt">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaCasaReporte"
                                            style="display: none;">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo Casa</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right;">
                                            </tbody>
                                        </table> 
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaCasa">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo Casa</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                            <tfoot class="control-detalle" style="text-align: right; font-weight: bold;">
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                    <br>
                                    <div class="col-md-12" style="width: 100%">
                                        <figure class="highcharts-figure">
                                            <div id="Grafica4"></div>
                                        </figure>
                                    </div>                                   
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered" id="TablaUsuarioCasaReporte"
                                            style="display: none;">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo Casa</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                        </table> 
                                        <table class="table table-striped table-bordered table-hover w-100"
                                            id="TablaUsuarioCasa">
                                            <thead class="cabecera" style="text-align: center">
                                                <tr>
                                                    <th>Tipo Casa</th>
                                                    <th>Ene</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Abr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Ago</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dic</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle" style="text-align: right">
                                            </tbody>
                                             <tfoot class="control-detalle" style="text-align: right; font-weight: bold;">
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
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

    <script src="../../js/M05_Reportes/M05JS07_Indicadores/M05JS07_Indicadores.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>