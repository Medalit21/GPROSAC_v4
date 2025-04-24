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
        $fecha = date('Y-m-d'); 
        $user_sesion = encrypt($valor_user,"123");
        $_GET['r']="";
        $_GET['l']="";
        if(!empty($_GET)){
            $IdReservaVenta = $_GET['r'];
            $IdReservaVenta = decrypt($IdReservaVenta, "123");
            $IdLoteVenta = $_GET['l'];
            $IdLoteVenta = decrypt($IdLoteVenta, "123");
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
                    <li><a class="enlace" href="javascript:void(0)">Ventas</a></li>
                    <li><a class="enlace" href="javascript:void(0)">Amortizaciones</a></li>
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
                    <div class="box-header with-border">
                        <input class="caja-texto" id="ValidUsuario" maxlength="15" type="text" value="<?php echo $valor_user; ?>" hidden>
                        <div class="botones-acciones-2">
                            <button id="nuevo" type="button" class="btn btn-registro"><i class="fas fa-file-alt"></i> Nuevo</button>
                            <button id="modificar" type="button" class="btn btn-registro"><i class="fas fa-edit"></i> Modificar</button>
                            <button id="guardar" type="button" class="btn btn-registro" disabled=""><i class="fas fa-save"></i> Guardar</button>
                            <button id="cancelar" type="button" class="btn btn-registro"><i class="fas fa-minus-circle"></i> Cancelar</button>
                            <button id="eliminar" type="button" class="btn btn-registro"><i class="fas fa-trash"></i> Eliminar</button>
                            <button id="busqueda_avanzada" type="button" class="btn btn-registro"><i class="fas fa-list"></i> Lista</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="contenido_registro" style="display:block;">
                            <br>
                            <div class="col-md-12 row">
                                <div class="col-md-2" hidden>
                                    <label class="col-md label-texto">Tipo Doc</label>
                                    <select id="cbxTipoDocumento" class="cbx-texto">
                                        <option selected="true" value="" disabled="">Seleccione...</option>
                                            <?php
                                                $tipoDoc = new ControllerCategorias();
                                                $VerTiposDoc = $tipoDoc->VerTipoDocumento();
                                                foreach ($VerTiposDoc as $td) {
                                            ?>
                                        <option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option><?php }?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-md label-texto">Nro Doc</label>
                                    <input class="caja-texto" id="txtDocumentoCliente" maxlength="15" type="text" value="" disabled="" hidden>
                                    <select id="txtDocumentoCliente" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                                <div class="col-md" id="panel-busqueda">
                                    <label class="col-md label-texto">Proyecto</label>
                                    <select id="bxFiltroProyectoVenta" class="cbx-texto">
                                        <option selected="true" value="" disabled="">Seleccionar...</option>
                                            <?php
                                                $tipoDoc = new ControllerCategorias();
                                                $VerTiposDoc = $tipoDoc->VerProyectos();
                                                foreach ($VerTiposDoc as $td) {
                                            ?>
                                        <option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option><?php }?>
                                    </select>
                                </div>
                                <div class="col-md" id="panel-busqueda">
                                    <label class="col-md label-texto">Zona</label>
                                    <select id="bxFiltroZonaVenta" class="cbx-texto">
                                        <option selected="true" value="" disabled="">Seleccionar...</option>}
                                    </select>
                                </div>
                                <div class="col-md" id="panel-busqueda2">
                                    <label class="col-md label-texto">Manzana</label>
                                    <select id="bxFiltroManzanaVenta" class="cbx-texto">
                                        <option selected="true" value="" disabled="">Seleccionar...</option>
                                    </select>
                                </div>
                                <div class="col-md" id="panel-busqueda3">
                                    <label class="col-md label-texto">Lote</label>
                                    <select id="bxFiltroLoteVenta" class="cbx-texto">
                                        <option selected="true" value="" disabled="">Seleccionar...</option>
                                    </select>
                                </div>
                                <div class="col-md" id="panel-busqueda4">
                                    <button type="button" class="btn btn-registro-success" id="btnBuscarCliente" style="margin-left:1px;margin-top: 15px; width: 25px;"><span title="Buscar registro"><i class="fas fa-search" aria-hidden="true"></i></span></button>
                                    <button type="button" class="btn btn-registro-primary" id="btnLimpiarFiltrosCliente" style="margin-left:1px;margin-top: 15px; width: 25px;"><span title="Nueva búsqueda"><i class="fas fa-sync-alt" aria-hidden="true"></i></span></button>
                                </div>                                            
                            </div>
                            <div class="form-row d-flex justify-content-center">
                                <div class="col-md-6 text-center mt-1" hidden>
                                    <button id="btnExportarPdf2" class="btn btn-registro-danger"><i class="fas fa-file-pdf" style="color:white"></i> Reporte</button>
                                </div>
                            </div>
                            <!-- DATOS DEL CLIENTE -->
                            <fieldset>
                                <legend>Datos del Cliente</legend>
                                <input type="hidden" id="__ID_LOTE_VENTA" value="<?php echo $IdLoteVenta?>" />
                                <input type="hidden" id="__ID_RESERVA_VENTA" value="<?php echo $IdReservaVenta?>" />
                                <input id="__ID_CLIENTE" type="hidden" value="">
                                <input id="__ID_VENTA" type="hidden" value="">
                                <input id="__ID_RESERVA" type="hidden" value="">
                                <input id="__ID_MONTO_RESERVA" type="hidden" value="">
                                <input id="__ACCION" type="hidden" value="">
                                <div class="row row-0" style="margin-top: -12px;"
                                    id="formularioRegistrarGeneralCliente">
                                    <div class="col-md-12 row">

                                        <label class="col-md-1 label-texto-sm">Nombres</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtNombreCliente" type="text"
                                            value="" disabled="" readonly="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">A. Paterno</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtApellidoPaternoCliente"
                                            type="text" value="" disabled="" readonly="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">A. Materno</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtApellidoMaternoCliente"
                                            type="text" value="" disabled="" readonly="">
                                        </div>
                                        <label class="col-md-1 label-texto-sm">Direccion</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtDireccionCliente"
                                            type="text" value="" disabled="" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- DATOS DE LA VENTA -->
                            <fieldset>
                                <legend>Datos de la Venta</legend>
                                <div class="row row-0" style="margin-top: -12px;" id="formularioRegistrarGeneralLote">
                                    <div class="col-md-12 row">

                                        <label class="col-md-1 label-texto-sm">Fecha Venta</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtFechaVenta" type="date"
                                            value="" disabled="" readonly="">
                                        </div>  

                                        <label class="col-md-1 label-texto-sm">Proyecto <small id="cbxProyectoHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                        <div class="col-md-2">
                                            <select id="cbxProyecto" class="cbx-texto">
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
                                        <label class="col-md-1 label-texto-sm" hidden>Zona <small id="cbxZonaHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                        <div class="col-md-2" hidden>
                                            <select id="cbxZona" class="cbx-texto">
                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm" hidden>Manzana <small id="cbxManzanaHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                        <div class="col-md-2" hidden>
                                            <select id="cbxManzana" class="cbx-texto">
                                            </select>
                                        </div>
                                        <label class="col-md-1 label-texto-sm" hidden>Lote <small id="cbxLoteHtml"
                                            class="form-text text-muted-validacion text-danger ocultar-info">
                                        </small></label>
                                        <div class="col-md-2" hidden>
                                            <select id="cbxLote" class="cbx-texto">
                                            </select>
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Propiedad</label>
                                        <div class="col-md-5">
                                            <input class="caja-texto tamano-text-10" id="txtPropiedad" type="text"
                                            value="" disabled="" readonly="">
                                        </div>    

                                        <label class="col-md-1 label-texto-sm">Precio Venta</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtPrecioVenta" type="text" value=""
                                            disabled="" readonly="">
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Monto inicial</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtMontoInicial" type="text"
                                            value="" disabled="" readonly="">
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Total Pagado</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtMontoPagado" type="text"
                                            value="" disabled="" readonly="">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Letras Pagadas</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtLetrasPagadas" type="text"
                                            value="" disabled="" readonly="">
                                        </div>                                        
                                    </div>
                                </div>
                            </fieldset>

                            <!-- DATOS DE LA AMORTIZACION -->
                            <fieldset>
                                <legend>Datos de la Amortización</legend>
                                <div class="row row-0 reducir" id="formularioDatosAmortizacion" style="display: none">
                                    <div class="col-md-12 row">                                       
                                        <input  id="_IDAMORTIZACION" type="hidden"> 
                                        
                                        <label class="col-md-1 label-texto-sm">Fecha Registro</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtFechaRegistro" type="date">
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Fecha Inicio</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtFechaInicio" type="date">
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Adjunto Adenda</label>
                                        <div class="col-md-5">
                                            <input class="caja-texto tamano-text-10" id="fileAmortizacion" type="file" accept=".pdf">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Tipo</label>
                                        <div class="col-md-2">
                                            <select id="bxTipoAmortizacion" class="cbx-texto">
                                                <option selected="true" value="" disabled="">Seleccionar...</option>
                                                    <?php
                                                        $TipoAmortizacion = new ControllerCategorias();
                                                        $VerTA = $TipoAmortizacion->VerTipoAmortizacion();
                                                        foreach ($VerTA as $ta) {
                                                    ?>
                                                <option value="<?php echo $ta['ID']; ?>"><?php echo $ta['Nombre']; ?></option><?php }?>
                                            </select>     
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Cantidad Letras</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtCantidadLetras" type="number" disabled>
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Precio Venta</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtNuevoPrecioVenta" type="text" readonly>
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Total Cancelado</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtMontoInicialEntrante" type="text" readonly>
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Tipo Moneda</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtTipoMoneda" type="text" readonly>
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm">Tipo Cambio</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtTipoCambio" type="text">
                                        </div>

                                        <label class="col-md-1 label-texto-sm">Monto Amortizado</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtMontoAmortizado" type="text" value="0.00">
                                        </div>
                                        
                                        <label class="col-md-1 label-texto-sm" hidden>Monto Inicial</label>
                                        <div class="col-md-2" hidden>
                                            <input class="caja-texto tamano-text-10" id="txtNuevoMontoInicial" type="text">
                                        </div>

                                        <label class="col-md-1 label-texto-sm" hidden>Total Inicial</label>
                                        <div class="col-md-2" hidden>
                                            <input class="caja-texto tamano-text-10" id="txtNuevoTotalInicial" type="text" readonly>
                                        </div>

                                        <label class="col-md-1 label-texto-sm">TEA %</label>
                                        <div class="col-md-2">
                                            <input class="caja-texto tamano-text-10" id="txtNuevaTEA" type="text">
                                        </div>

                                    </div>
                                </div>
                                <div class="row row-0 reducir" id="formularioMensajeAmortizacion">
                                    <label class="color-text">El registro de venta no tiene amortización.</label>
                                </div>
                            </fieldset>

                            <!-- CRONOGRAMA DE PAGOS -->
                            <fieldset>
                                <legend>Cronograma de Pagos</legend>
                                <div class="fn-frm-dt reducir">
                                    <div class="table-responsive scroll-table">
                                        <table class="table table-striped table-bordered table-hover w-100" id="TablaCronograma">
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
                                    </div>    
                                </div>
                            </fieldset>
                        </div>
                    </div>


                    <div id="contenido_lista" style="display:none;">
                        <div class="form-row mt-3">
                            <div class="col-md-12">
                                <label class="titulo-cont">Filtros de Busqueda:</label>
                            </div>
                            <div class="col-md">
                                <label class="label-texto">Documento</label>
                                <!--<input type="text" id="txtDocumentoFiltro" class="caja-texto" maxlength="15"
                                placeholder="Documento Cliente" value="">-->
                                <select id="txtDocumentoFiltro" style="width: 100%; font-size: 11px;" class="cbx-texto">
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
                                <label class="label-texto">Condición:</label>
                                <select id="cbxCondicionFiltro" class="cbx-texto">
                                    <option selected="true" value="">Todos
                                    </option>
                                    <?php
                                    $VerTipoCondicion = new ControllerCategorias();
                                    $verTipoCondicion = $VerTipoCondicion->VerTipoCondicionVenta();
                                    foreach ($verTipoCondicion as $item) {
                                        ?>
                                        <option value="<?php echo $item['ID']; ?>">
                                            <?php echo $item['Nombre']; ?>
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
                            <div class="col-md text-center" style=" margin-top: 12px;">
                                <button class="btn btn-registro-success" id="btnBuscarRegistro" name="btnBuscarRegistro"><i class="fas fa-search"></i> Buscar</button>
                                <button class="btn btn-registro-primary" id="btnLimpiar" name="btnLimpiar"><i class="fas fa-sync-alt"></i> Limpiar</button>
                            </div>
                        </div>
                        <br>
                        <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                        <div class="fn-frm-dt">
                            <div class="table-responsive scroll-table">
                                <table class="table table-striped table-bordered" id="tableRegistroReportes" style="display: none;">
                                    <thead class="cabecera">
                                        <tr>
                                            <th>Fecha Venta</th>
                                            <th>Cliente</th>
                                            <th>Lote</th>                                            
                                            <th>Condición</th>
                                            <th>Moneda</th>
                                            <th>Descuento</th>
                                            <th>Total</th>
                                            <th>Cuota Inicial</th>
                                            <th>Vendedor</th>
                                            <th>Situación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="control-detalle">
                                    </tbody>
                                </table>
                                <div style="margin: 36px;"> </div>
                                <table class="table table-striped table-bordered table-hover w-100" id="tableRegistroVenta">
                                    <thead class="cabecera">
                                        <tr>
                                            <th></th>
                                            <th>Fecha Venta</th>
                                            <th>Cliente</th>
                                            <th>Lote</th>                                            
                                            <th>Condición</th>
                                            <th>Moneda</th>
                                            <th>Descuento</th>
                                            <th>Total</th>
                                            <th>Cuota Inicial</th>                                            
                                            <th>Vendedor</th>
                                            <th>Situación</th>
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
    </div>

    <!-- POP UP NUEVO DOCUMENTO ADJUNTO-->
    <div class="modal fade" id="modalNuevoDocumentoAdjunto" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
        <?php
            //require_once "pop-up/M03SM02_POPUP_NuevoDocumentoAdjunto.php";
        ?>
    </div>

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

<script src="../../js/M03_Ventas/M03JS04_Amortizaciones/M03JS04_Amortizaciones.js?v=1.2.0"></script>
<script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
<script src="../../librerias/utilitario/xlsx.full.min.js?v=1.1.1"></script>
</body>

</html>
<script type="text/javascript">
	$(document).ready(function(){
	    $('#txtDocumentoFiltro').select2();
		$('#txtDocumentoFiltro').select2();
	});
</script>