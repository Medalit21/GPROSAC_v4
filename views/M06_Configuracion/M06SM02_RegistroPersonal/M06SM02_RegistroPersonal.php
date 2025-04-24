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
                    <li><a class="enlace" href="javascript:void(0)">Registro Personal</a></li>
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
                                <div id="formularioRegistrarGeneral">
									<div class="form-row" style="margin-top: -8px;" >
										<div class="col-md-2">
											<input type="hidden" id="__ID_DATOS_PERSONAL">
											
											<label class="label-texto">Tipo Documento 
												<small id="cbxTipoDocumentoHtml" class="form-text text-muted-validacion text-danger ocultar-info">
												</small>
											</label>
											<select id="cbxTipoDocumento" class="cbx-texto">
												<option selected="true" value="" disabled="disabled">Seleccione...</option>
												<?php
													$tipoDoc = new ControllerCategorias();
													$VerTiposDoc = $tipoDoc->VerTipoDocumento();
													foreach ($VerTiposDoc as $td) {
												?>
												<option value="<?php echo $td['ID']; ?>"><?php echo $td['Nombre']; ?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-md-2">
											<label class="label-texto">Documento <code class="text-danger">*</code><small id="txtDocumentoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input id="txtDocumento" class="caja-texto" maxlength="20" placeholder="Nro Documento" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Apellidos <code class="text-danger">*</code><small id="txtApellidosHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtApellidos" class="caja-texto" placeholder="Ejm: Morales" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-4">
											<label class="label-texto">Nombres <code class="text-danger">*</code><small id="txtNombresHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input type="text" id="txtNombres" class="caja-texto" placeholder="Ejm: Julio Adrian" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
										</div>
										<div class="col-md-2">
											<label class="label-texto">Sexo <code class="text-danger">*</code><small id="cbxSexoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<select id="cbxSexo" class="cbx-texto">
												<option selected="true" value="" disabled="disabled">Seleccione...</option>
												<?php
												$genero = new ControllerCategorias();
												$Vergenero = $genero->VerGeneroUsuario();
												foreach ($Vergenero as $gen) {
													?>
												<option value="<?php echo $gen['ID']; ?>"><?php echo $gen['Nombre']; ?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-md-2">
											<label class="label-texto">Fecha Nacimiento <code class="text-danger">*</code><small id="txtFechaNacimientoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input id="txtFechaNacimiento" type="date" min="1921-01-01" max="2007-12-31" class="caja-texto">
										</div>
										<div class="col-md-2">
											<label class="label-texto">Celular <code class="text-danger">*</code><small id="txtTelefonoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input maxlength="9" id="txtTelefono" type="text" class="caja-texto" placeholder="Teléfono">
										</div>
										<div class="col-md-2">
											<label class="label-texto">Email <small id="txtDireccionHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input id="txtemail" type="email" class="caja-texto" placeholder="@ejemplo.com" onkeyup="javascript:this.value=this.value.toUpperCase();">
										</div>
										<div class="col-md-4">
											<label class="label-texto">Direcci&oacute;n <code class="text-danger">*</code><small id="txtDireccionHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
											<input id="txtDireccion" type="text" class="caja-texto" placeholder="Dirección" onkeyup="javascript:this.value=this.value.toUpperCase();">
										</div>
									</div>
                                </div>
                            </fieldset>

							<fieldset>
								<legend>Datos Usuario</legend>
								<div class="row" style="margin-top: -8px;" id="formularioRegistrarUsuario">
								    <input type="hidden" id="txtUsuario" value="<?php echo $user_sesion; ?>">
									<div class="col-md-2">
										<label class="label-texto">Usuario <small id="txtDatoUserHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
										<input id="txtDatoUser" type="text" class="caja-texto" placeholder="Usuario" readonly>
									</div>
									<div class="col-md-4">
										<label class="label-texto">Clave <code class="text-danger">*</code><small id="txtpasswordHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
										<div class="row">
										    <div class="col-md-8">
    										    <input id="txtpassword" type="password" class="caja-texto" placeholder="Clave">
    										</div>
    										<div class="col-md-4">
        										<div class="input-group-append">
                                                    <button id="show_password" class="btn btn-registro" type="button"> <span class="fa fa-eye-slash icon"></span> </button>
                                                </div>
                                            </div>
                                        </div>
									</div>
									<div class="col-md-4">
										<label class="label-texto">Repetir Clave <code class="text-danger">*</code><small id="txtpassword2Html" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
										<div class="row">
										    <div class="col-md-8">
    										    <input id="txtpassword2" type="password" class="caja-texto" placeholder="Clave">
    										</div>
    										<div class="col-md-4">
        										<div class="input-group-append">
                                                    <button id="show_password2" class="btn btn-registro" type="button"> <span class="fa fa-eye-slash icon"></span> </button>
                                                </div>
                                            </div>
                                        </div>
									</div>
									<div class="col-md-2">
										<label class="label-texto">Estado</label>
										<select id="cbxEstado" class="cbx-texto">
											<option selected="true" value="1">ACTIVO</option>
											<option value="0">INACTIVO</option>
										</select>
									</div>
									<div class="col-md-3">
										<label class="label-texto">Cargo <code class="text-danger">*</code><small id="cbxCargoHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
										<select id="cbxCargo" class="cbx-texto">
											<option selected="true" value="" disabled="disabled">Seleccione...</option>
											<?php
											$cargo = new ControllerCategorias();
											$Vercargo = $cargo->VerCargo();
											foreach ($Vercargo as $carg) {
												?>
											<option value="<?php echo $carg['ID']; ?>"><?php echo $carg['Nombre']; ?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-md-3">
										<label class="label-texto">Area <code class="text-danger">*</code><small id="cbxAreaHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
										<select id="cbxArea" class="cbx-texto">
											<option selected="true" value="" disabled="disabled">Seleccione...</option>
											<?php
											$area = new ControllerCategorias();
											$Verarea = $area->VerAreaPers();
											foreach ($Verarea as $areas) {
												?>
											<option value="<?php echo $areas['ID']; ?>"><?php echo $areas['Area']; ?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-md-3">
										<label class="label-texto">Perfil <code class="text-danger">*</code><small id="cbxPerfilHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small></label>
										<select id="cbxPerfilUsu" class="cbx-texto">
											<option selected="true" value="" disabled="disabled">Seleccione...</option>
											<?php
											$perfil = new ControllerCategorias();
											$Verperfil = $perfil->VerPerfilUsu();
											foreach ($Verperfil as $perf) {
												?>
											<option value="<?php echo $perf['ID']; ?>"><?php echo $perf['Descripcion']; ?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-md-3">
										<label class="label-texto">Jefe Inmediato <code class="text-danger">*</code><small id="cbxJefeInmedHtml" class="form-text text-muted-validacion text-danger ocultar-info"></small>
										</label>
										<select id="cbxJefeInmed" class="cbx-texto">
											<option selected="true" value="" disabled="disabled">Seleccione...</option>
											<?php
											$genero = new ControllerCategorias();
											$Vergenero = $genero->VerJefeInmUsu();
											foreach ($Vergenero as $gen) {
												?>
											<option value="<?php echo $gen['ID']; ?>"><?php echo $gen['Nombre']; ?></option>
											<?php }?>
										</select>
									</div>
									<div class="col-md-12">
                                       <label class="col-md-12 label-texto">Adjunto <small>(Documento)</small> <code class="text-danger">*</code></label>
                                        <form action="" method="POST" enctype="multipart/form-data" id="filesFormAdjuntosVenta">
                                            <div class="col-md-12" style="margin-left: -7px;">
                                                <input type="file" class="caja-texto" id="constancia" name="constancia" accept=".pdf">
                                                <input type="hidden" id="ReturnSubirAdjuntoPdf" name="ReturnSubirAdjuntoPdf" value="true">                 
                                            </div>
                                        </form>
                                    </div>
									
									<div class="col-md-6">
										<div class="fw-normal fs-7 fst-italic text-gray-900 mb-1 mt-3">
											<span class="fw-bolder text-info">Nota:</span> 
											Los campos en asterisco (<i class="mdi ms-1 mdi-asterisk icon-dual-danger" style="color: #da542e !important;" data-bs-toggle="tooltip" aria-label="Info"></i>) son obligatorios.        
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
                                        <label class="label-texto">Nombre/Apellido </label>
                                        <!--<input type="text" id="txtFiltroDatoCliente" class="caja-texto" placeholder="Nro Documento">-->
                                        <select id="cbxFiltroTrabajador" style="width: 100%; font-size: 11px;" class="cbx-texto">
                                            <option selected="true" value="" disabled="disabled">TODOS</option>
                                            <?php
                                                $Clientes = new ControllerCategorias();
                                                $ClientesVer = $Clientes->VerFiltroTrabajadores();
                                                foreach ($ClientesVer as $Cliente) {
                                            ?>
                                            <option value="<?php echo $Cliente['ID']; ?>" style="font-size: 11px;">
                                            <?php echo $Cliente['Nombre']; ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
										<label class="label-texto">Estado</label>
										<select id="cbxFiltroEstado" class="cbx-texto">
											<option selected="true" value="1">ACTIVO</option>
											<option value="0">INACTIVO</option>
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
                                                <th>DOCUMENTO</th>
                                                <th>APELLIDOS</th>
                                                <th>NOMBRES</th>
												<th>CELULAR</th>
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
                                                <th scope="col">DOCUMENTO</th>
                                                <th scope="col">APELLIDOS</th>
                                                <th scope="col">NOMBRES</th>
												<th scope="col">CELULAR</th>
                                                <th scope="col">FECHA NAC.</th> 
                                                <th scope="col">DOC. ADJUNTO</th>  
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
        <div class="modal fade" id="modalVerAdjunto" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel">
        <?php
            require_once "pop-up/M06MD02_POPUP_VerAdjunto.php";
        ?>
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

    <script src="../../js/M06_Configuracion/M06JS02_RegistroPersonal/M06JS02_Index.js?v=1.1.1"></script>

    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">

</body>
<script>
  $(document).ready(function() {
    // Mostrar y ocultar contraseñas
    function mostrarPassword(){
        var cambio = document.getElementById("txtpassword");
        if (cambio.type == "password") {
            cambio.type = "text";
            $('.icon').removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            cambio.type = "password";
            $('.icon').removeClass('fa-eye').addClass('fa-eye-slash');
        }
    } 

    function mostrarPassword2(){
        var cambio = document.getElementById("txtpassword2");
        if (cambio.type == "password") {
            cambio.type = "text";
            $('.icon').removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            cambio.type = "password";
            $('.icon').removeClass('fa-eye').addClass('fa-eye-slash');
        }
    } 

    // Mostrar y ocultar contraseña con checkbox (si es necesario)
    $('#ShowPassword').click(function() {
        $('#Password').attr('type', $(this).is(':checked') ? 'text' : 'password');
    });

    $('#ShowPassword2').click(function() {
        $('#Password').attr('type', $(this).is(':checked') ? 'text' : 'password');
    });

    // Copiar el valor de Documento a Usuario automáticamente
    $('#txtDocumento').on('input', function() {
        $('#txtDatoUser').val($(this).val());
    });

    // Asigna los eventos mostrarPassword a los botones respectivos
    $('#show_password').click(mostrarPassword);
    $('#show_password2').click(mostrarPassword2);
});

    
</script>



</html>