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
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <br>
            <div class="breadcrumb-ancho">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li><a class="enlace" href="javascript:void(0)">Cobranzas</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Validar Pagos</a></li>
                </ol>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#PagoLetras" role="tab" data-toggle="tab">Pago por Validar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#PagoRealizado" role="tab" data-toggle="tab">Pagos Validados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#PagoResValidar" role="tab" data-toggle="tab">Pagos Reservas por Validar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#PagoResValidado" role="tab" data-toggle="tab">Pagos Reservas Validados</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="PagoLetras" style="opacity: 1;">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <div class="row">
                                    <input type="hidden" id="txtuser" value="<?php echo $valor_user; ?>">
                                    <div class="col-md"><br>
                                        <label class="label-texto">Cliente</label>
                                        <!--<input type="text" id="txtFiltroDocumentoEC" class="caja-texto" placeholder="Nro Documento">-->
                                        <select id="txtFiltroDocumentoEC" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                                    
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Proyecto </label>
                                        <select id="bxFiltroProyectoEC" class="cbx-texto">
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
            
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Zona </label>
                                        <select id="bxFiltroZonaEC" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Manzana </label>
                                        <select id="bxFiltroManzanaEC" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Lote </label>
                                        <select id="bxFiltroLoteEC" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>  
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Inicio <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecIniFiltro" class="caja-texto">
                                    </div>
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Termino <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecFinFiltro" class="caja-texto">
                                    </div>

                                    <div class="col-md"><br>
                                        <label class="label-texto">Estado Pago</label>
                                        <select id="bxFiltroEstadoEC" class="cbx-texto">
                                            <option value="todos">TODOS</option>
                                            <?php
                                                $Proyectos = new ControllerCategorias();
                                                $ProyectoVer = $Proyectos->VerEstadoPagosEC();
                                                foreach ($ProyectoVer as $Proy) {
                                            ?>
                                            <option value="<?php echo $Proy['ID']; ?>">
                                            <?php echo $Proy['Nombre']; ?>
                                            </option>
                                            <?php }?>                                            
                                        </select>
                                    </div>
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Estado Validación</label>
                                        <select id="bxFiltroEV" class="cbx-texto">
                                            <?php
                                                $Proyectos = new ControllerCategorias();
                                                $ProyectoVer = $Proyectos->VerEstadosVP();
                                                foreach ($ProyectoVer as $Proy) {
                                            ?>
                                            <option value="<?php echo $Proy['ID']; ?>">
                                            <?php echo $Proy['Nombre']; ?>
                                            </option>
                                            <?php }?>
                                            <option value="todos">TODOS</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row d-flex justify-content-center">
                                    <div class="col-md-4 text-center mt-1">
                                        <button id="btnBuscar" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button>
                                        <button id="btnLimpiar" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                        <button id="btnNewPago" type="button" class="btn btn-registro" hidden><i class="fas fa-file-alt"></i> Nuevo</button>
                                    </div>
                                </div>                        

                                <div class="row">
                                    <div class="col-md-12" id="contenedorReservas">
                                        <fieldset>
                                            <legend>Historial de Pagos</legend>
                                            <div class="row row-0" id="formularioReservasRelacionadasAlCliente">
                                                <div class="col-md-12">
                                                    <div class="table-responsive scroll-table">
                                                        <table class="table table-striped table-bordered" id="TablaPagosReporte"
                                                            style="display: none;">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Estado Validacion</th>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Estado Pago</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Mora</th>
                                                                    <th>Importe Pago</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Pagado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="control-detalle">
                                                            </tbody>
                                                        </table>
                                                        <!--<br><br>-->                                              
                                                        <table class="table table-striped table-bordered" cellspacing="0" id="TablaPagos">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Estado Validacion</th>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Estado Pago</th>
                                                                    <th>Cliente</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Mora</th>
                                                                    <th>Pagado</th>
                                                                    <th>Nro Operación</th>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="PagoRealizado">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <div class="row">
                                    <input type="hidden" id="txtuser" value="<?php echo $valor_user; ?>">
                                    <div class="col-md"><br>
                                        <label class="label-texto">Cliente</label>
                                        <!--<input type="text" id="txtFiltroDocumentoPR" class="caja-texto" placeholder="Nro Documento">-->
                                        <select id="txtFiltroDocumentoPR" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                                        <label class="label-texto">Inicio <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecIniFiltroPR" class="caja-texto">
                                    </div>
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Termino <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecFinFiltroPR" class="caja-texto">
                                    </div>

                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Proyecto </label>
                                        <select id="bxFiltroProyectoPR" class="cbx-texto">
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
                                        <select id="bxFiltroZonaPR" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md"><br>
                                        <label class="label-texto">Manzana </label>
                                        <select id="bxFiltroManzanaPR" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md"><br>
                                        <label class="label-texto">Lote </label>
                                        <select id="bxFiltroLotePR" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>  

                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Agencia Bancaria</label>
                                        <select id="bxFiltroBancoPR" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">TODOS</option>
                                            <?php
                                                $Proyectos = new ControllerCategorias();
                                                $ProyectoVer = $Proyectos->VerBancos();
                                                foreach ($ProyectoVer as $Proy) {
                                            ?>
                                            <option value="<?php echo $Proy['ID']; ?>">
                                            <?php echo $Proy['Nombre']; ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row d-flex justify-content-center">
                                    <div class="col-md-4 text-center mt-1">
                                        <button id="btnBuscarR" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button>
                                        <button id="btnLimpiarR" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                            

                                <div class="row">
                                    <div class="col-md-12" id="contenedorReservas">
                                        <fieldset>
                                            <legend>Historial de Pagos</legend>
                                            <div class="row row-0" id="formularioReservasRelacionadasAlCliente">
                                                <div class="col-md-12">
                                                    <div class="table-responsive scroll-table">
                                                        <table class="table table-striped table-bordered" id="TablaPagosRealizadosReporte"
                                                            style="display: none;">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Cliente</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Mora</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Tipo Moneda</th>
                                                                    <th>Importe Pagado</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Total Pagado</th>
                                                                    <th>Agencia Bancaria</th>
                                                                    <th>Estado</th>
                                                                    <th>Estado Validacion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="control-detalle">
                                                            </tbody>
                                                        </table>
                                                        <br><br>                                               
                                                        <table class="table table-striped table-bordered" cellspacing="0" id="TablaPagosRealizados" style="width: 100%">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Cliente</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Mora</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Tipo Moneda</th>
                                                                    <th>Importe Pagado</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Total Pagado</th>
                                                                    <th>Agencia Bancaria</th>
                                                                    <th>Estado</th>
                                                                    <th>Estado Validacion</th>                                  
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
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="PagoResValidar">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <div class="row">
                                    <input type="hidden" id="txtuser" value="<?php echo $valor_user; ?>">
                                    <div class="col-md"><br>
                                        <label class="label-texto">Cliente</label>
                                        <!--<input type="text" id="txtFiltroDocumentoEC" class="caja-texto" placeholder="Nro Documento">-->
                                        <select id="txtFiltroDocumentoRES" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                                    
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Proyecto </label>
                                        <select id="bxFiltroProyectoRES" class="cbx-texto">
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
            
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Zona </label>
                                        <select id="bxFiltroZonaRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Manzana </label>
                                        <select id="bxFiltroManzanaRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Lote </label>
                                        <select id="bxFiltroLoteRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>  
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Inicio <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecIniFiltroRES" class="caja-texto">
                                    </div>
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Termino <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecFinFiltroRES" class="caja-texto">
                                    </div>

                                    <div class="col-md"><br>
                                        <label class="label-texto">Estado Pago</label>
                                        <select id="bxFiltroEstadoRES" class="cbx-texto">
                                            <option value="todos">TODOS</option>
                                            <?php
                                                $Proyectos = new ControllerCategorias();
                                                $ProyectoVer = $Proyectos->VerEstadoPagosEC();
                                                foreach ($ProyectoVer as $Proy) {
                                            ?>
                                            <option value="<?php echo $Proy['ID']; ?>">
                                            <?php echo $Proy['Nombre']; ?>
                                            </option>
                                            <?php }?>                                            
                                        </select>
                                    </div>
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Estado Validación</label>
                                        <select id="bxFiltroEVRES" class="cbx-texto">
                                            <?php
                                                $Proyectos = new ControllerCategorias();
                                                $ProyectoVer = $Proyectos->VerEstadosVP();
                                                foreach ($ProyectoVer as $Proy) {
                                            ?>
                                            <option value="<?php echo $Proy['ID']; ?>">
                                            <?php echo $Proy['Nombre']; ?>
                                            </option>
                                            <?php }?>
                                            <option value="todos">TODOS</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row d-flex justify-content-center">
                                    <div class="col-md-4 text-center mt-1">
                                        <button id="btnBuscarRES" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button>
                                        <button id="btnLimpiarRES" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                        <button id="btnNewPagoRES" type="button" class="btn btn-registro" hidden><i class="fas fa-file-alt"></i> Nuevo</button>
                                    </div>
                                </div>                        

                                <div class="row">
                                    <div class="col-md-12" id="contenedorReservas">
                                        <fieldset>
                                            <legend>Historial de Pagos</legend>
                                            <div class="row row-0" id="formularioReservasRelacionadasAlCliente">
                                                <div class="col-md-12">
                                                    <div class="table-responsive scroll-table">
                                                        <table class="table table-striped table-bordered" id="TablaPagosReporteRES"
                                                            style="display: none;">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Estado Validacion</th>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Estado Pago</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Mora</th>
                                                                    <th>Importe Pago</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Pagado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="control-detalle">
                                                            </tbody>
                                                        </table>
                                                        <!--<br><br>-->                                              
                                                        <table class="table table-striped table-bordered" cellspacing="0" id="TablaPagosRES" style="width: 100%">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Estado Validacion</th>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Voucher</th>
                                                                    <th>Estado Pago</th>
                                                                    <th>Cliente</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Mora</th>
                                                                    <th>Pagado</th>
                                                                    <th>Nro Operación</th>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="PagoResValidado">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <div class="row">
                                    <input type="hidden" id="txtuser" value="<?php echo $valor_user; ?>">
                                    <div class="col-md"><br>
                                        <label class="label-texto">Cliente</label>
                                        <!--<input type="text" id="txtFiltroDocumentoPR" class="caja-texto" placeholder="Nro Documento">-->
                                        <select id="txtFiltroDocumentoVRES" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                                        <label class="label-texto">Inicio <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecIniFiltroVRES" class="caja-texto">
                                    </div>
                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Termino <small>(FECHA PAGO)</small></label>
                                        <input type="date" id="txtFecFinFiltroVRES" class="caja-texto">
                                    </div>

                                    
                                    <div class="col-md"><br>
                                        <label class="label-texto">Proyecto </label>
                                        <select id="bxFiltroProyectoVRES" class="cbx-texto">
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
                                        <select id="bxFiltroZonaVRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md"><br>
                                        <label class="label-texto">Manzana </label>
                                        <select id="bxFiltroManzanaVRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>
            
                                    <div class="col-md"><br>
                                        <label class="label-texto">Lote </label>
                                        <select id="bxFiltroLoteVRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">Seleccionar...</option>
                                        </select>
                                    </div>  

                                    <div class="col-md" hidden><br>
                                        <label class="label-texto">Agencia Bancaria</label>
                                        <select id="bxFiltroBancoVRES" class="cbx-texto">
                                            <option selected="true" disabled="disabled" value="">TODOS</option>
                                            <?php
                                                $Proyectos = new ControllerCategorias();
                                                $ProyectoVer = $Proyectos->VerBancos();
                                                foreach ($ProyectoVer as $Proy) {
                                            ?>
                                            <option value="<?php echo $Proy['ID']; ?>">
                                            <?php echo $Proy['Nombre']; ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row d-flex justify-content-center">
                                    <div class="col-md-4 text-center mt-1">
                                        <button id="btnBuscarVRES" type="button" class="btn btn-registro-success"><i class="fas fa-search"></i> Buscar</button>
                                        <button id="btnLimpiarVRES" type="button" class="btn btn-registro-primary"><i class="fas fa-sync-alt"></i> Limpiar</button>
                                    </div>
                                </div>
                            

                                <div class="row">
                                    <div class="col-md-12" id="contenedorReservas">
                                        <fieldset>
                                            <legend>Historial de Pagos</legend>
                                            <div class="row row-0" id="formularioReservasRelacionadasAlCliente">
                                                <div class="col-md-12">
                                                    <div class="table-responsive scroll-table">
                                                        <table class="table table-striped table-bordered" id="TablaPagosRealizadosReporteVRES"
                                                            style="display: none;">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Cliente</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Mora</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Tipo Moneda</th>
                                                                    <th>Importe Pagado</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Total Pagado</th>
                                                                    <th>Agencia Bancaria</th>
                                                                    <th>Estado</th>
                                                                    <th>Estado Validacion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="control-detalle">
                                                            </tbody>
                                                        </table>
                                                        <br><br>                                               
                                                        <table class="table table-striped table-bordered" cellspacing="0" id="TablaPagosRealizadosVRES" style="width: 100%">
                                                            <thead class="cabecera">
                                                                <tr>
                                                                    <th>Fecha Pago</th>
                                                                    <th>Cliente</th>
                                                                    <th>Lote</th>
                                                                    <th>Fecha Vencimiento</th>
                                                                    <th>Mora</th>
                                                                    <th>Nro Cuota</th>
                                                                    <th>Tipo Moneda</th>
                                                                    <th>Importe Pagado</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Total Pagado</th>
                                                                    <th>Agencia Bancaria</th>
                                                                    <th>Estado</th>
                                                                    <th>Estado Validacion</th>                                  
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
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <!-- POP UP REGISTRO EMPLEADO -->
                <div class="modal fade justify-content-center" id="modalCobros" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                    require_once "pop-up/M04MD02_POPUP_Cobros.php";
                    ?>
                </div>
                
                <div class="modal fade" id="modalVerVoucher" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                        require_once "pop-up/M04MD02_POPUP_VerVoucher.php";
                    ?>
                </div>
                
                <div class="modal fade" id="modalVerObservaciones" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                        require_once "pop-up/M04MD02_POPUP_Observaciones.php";
                    ?>
                </div>
                
                <div class="modal fade" id="modalVerEditarPagoReserva" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
                    <?php
                        require_once "pop-up/M04MD02_POPUP_EditarPagoReserva.php";
                    ?>
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

    <script src="../../js/M04_Cobranzas/M04JS01_Cobranzas/M04JS01_Cobros.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>

</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#txtFiltroDocumentoEC').select2();
		$('#txtFiltroDocumentoPR').select2();
		$('#txtFiltroDocumentoRES').select2();
		$('#txtFiltroDocumentoVRES').select2();
	});
</script>