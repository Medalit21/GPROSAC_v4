<?php
ob_start();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
require_once "../../../config/configuracion.php";
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
    

</head>

<body>
   
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
		
		$valor = $_GET['Dto'];
		$fecha = date('Y-m-d');
		$anio = date('Y');
		$mes = date('m');
		$dia = date('d');
		
		//echo "EL VALOR ES: ".$valor;
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
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

                 <!-- editor -->
                <div class="row" style="font-family: Vegur, 'PT Sans', Verdana, sans-serif;">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <style>
                                    th, td {
                                       width: 25%;
                                       text-align: center;
                                       vertical-align: top;
                                       border: 1px solid #000;
                                       border-collapse: collapse;
                                       padding: 0.3em;
                                       caption-side: bottom;
                                    }
                                    .cancel{
                                        background-color: #D50A00; 
                                        color: white; 
                                        font-size: 16px;
                                        font-weight: bold;
                                        height: 20px;
                                        text-align: center;
                                    }
                                </style>
                                <!-- Create the editor container -->
                                <?php 
                                
                                            $cbxFiltroNumDocumento = decrypt($_GET['d'],"123");
                                        	$txtDesdeFiltro = decrypt($_GET['fi'],"123");
                                        	$txtHastaFiltro = decrypt($_GET['ff'],"123");
                                        	$txtEstadoFiltro = decrypt($_GET['ee'],"123");
                                        	$bxFiltroProyecto = decrypt($_GET['p'],"123");
                                        	$bxFiltroZona = decrypt($_GET['z'],"123");
                                        	$bxFiltroManzana = decrypt($_GET['m'],"123");
                                        	$bxFiltroLote = decrypt($_GET['l'],"123");
                                        	
                                        	$fecha_ini = date("d/m/Y", strtotime($txtDesdeFiltro));
                                        	$fecha_fin = date("d/m/Y", strtotime($txtHastaFiltro));
                                        	
                                        	$query_documento = "";
                                        	$query_fecha = "";
                                        	$query_proyecto = "";
                                        	$query_zona = "";
                                        	$query_manzana = "";
                                        	$query_lote = "";
                                        	
                                        	if(!empty($cbxFiltroNumDocumento)){
                                        	   $query_documento = "AND dc.documento='$cbxFiltroNumDocumento'"; 
                                        	}
                                        	
                                        	if(!empty($txtDesdeFiltro) && empty($txtHastaFiltro)){
                                        	   $query_fecha = "AND gpcr.fecha_vencimiento='$txtDesdeFiltro'"; 
                                        	}else{
                                        	    if(empty($txtDesdeFiltro) && !empty($txtHastaFiltro)){
                                        	        $query_fecha = "AND gpcr.fecha_vencimiento='$txtHastaFiltro'"; 
                                        	    }else{
                                        	        if(!empty($txtDesdeFiltro) && !empty($txtHastaFiltro)){
                                        	           $query_fecha = "AND gpcr.fecha_vencimiento BETWEEN '$txtDesdeFiltro' AND '$txtHastaFiltro'"; 
                                        	        }
                                        	    }
                                        	}
                                        	
                                        	if(!empty($bxFiltroProyecto)){
                                        	   $query_proyecto = "AND gpy.idproyecto='$bxFiltroProyecto'"; 
                                        	}
                                        	
                                        	if(!empty($bxFiltroZona)){
                                        	   $query_zona = "AND gpz.idzona='$bxFiltroZona'"; 
                                        	}
                                        	
                                        	if(!empty($bxFiltroManzana)){
                                        	   $query_manzana = "AND gpm.idmanzana='$bxFiltroManzana'"; 
                                        	}
                                        	
                                        	if(!empty($bxFiltroLote)){
                                        	   $query_lote = "AND gpl.idlote='$bxFiltroLote'"; 
                                        	}
                                        	
                                        	$query_estado = "";
    
                                            if($txtEstadoFiltro === '1'){
                                                $query_estado = "AND (((gpcr.estado in ('3','1')) OR (gpcr.estado='2' AND gpcr.pago_cubierto='1')) AND (gpcr.fecha_vencimiento>='".$fecha."'))";
                                            }else{
                                                if($txtEstadoFiltro === '3'){
                                                    $query_estado = "AND (((gpcr.estado='3') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1')) AND (gpcr.fecha_vencimiento<'".$fecha."'))";
                                                }else{
                                                    $query_estado = "AND ((gpcr.estado in ('3','1')) OR (gpcr.estado='2' AND gpcr.pago_cubierto='1'))";
                                                }
                                            }
                                        
                                
                                    	$query_total = mysqli_query($conection,"SELECT 
                                    	            count(gpcr.item_letra) as letras,
                                    	            SUM(if('".$fecha."'>gpcr.fecha_vencimiento,if((TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, '".$fecha."')>0),concat('-',TIMESTAMPDIFF(DAY,gpcr.fecha_vencimiento, '".$fecha."')),0),0)) as mora,
                                        			format(SUM(gpcr.monto_letra),2) as total
                                        			FROM gp_cronograma gpcr
                                        			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta 
                                        			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
                                        			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
                                        			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                                        			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                                        			INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
                                        			WHERE gpcr.esta_borrado=0 AND gpv.devolucion!='1' AND gpv.cancelado!='1'
                                        			$query_estado
                                        			$query_documento
                                                	$query_fecha
                                                	$query_proyecto
                                                	$query_zona
                                                	$query_manzana
                                                	$query_lote
                                        			");
                                        			$respuesta_total = mysqli_fetch_assoc($query_total);
                                        			$letras = $respuesta_total['letras'];
                                        			$mora = $respuesta_total['mora'];
                                        			$total = $respuesta_total['total'];
                                        
                                ?>
                                <center>
                                    <label style="font-size: 21px; text-align: center; font-weight: bold; font-family: Vegur, 'PT Sans', Verdana, sans-serif;">LETRAS VENCIDAS</label><br>
                                    <label style="font-size: 11px;">( <?php echo 'De '.$fecha_ini.' al '.$fecha_fin; ?> )</label>
                                </center>
                                <br>
                                
                                <div class="table-responsive">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="font-size: 15px;"><b>TOTAL LETRAS : </b> <?php echo $letras; ?></td>
                                            <td style="font-size: 15px;"><b>TOTAL MORA (d&iacute;as) : </b> <?php echo $mora; ?></td>
                                            <td style="font-size: 15px;"><b>TOTAL MONTO : </b> <?php echo $total; ?></td>
                                        </tr>
                                    </table>
                                    <br>
                                </div>
                                
                                <!-- TABLA CONTENEDORA DE REGISTRO DE TRABAJADORES -->
                                <div class="fn-frm-dt">                                  
                                    <div class="table-responsive">
                                        <?php
                                        
                                          
                                        
                                        		$query = mysqli_query($conection,"SELECT 
                                        			gpcr.id as id,
                                        			date_format(gpcr.fecha_vencimiento, '%d/%m/%Y') as fecha,
                                        			concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as cliente,
                                        			concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
                                        			gpcr.item_letra as letra,
                                        			format(SUM(gpcr.monto_letra),2) as monto,
                                        			SUM(if('".$fecha."'>gpcr.fecha_vencimiento,if((TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, '".$fecha."')>0),concat('-',TIMESTAMPDIFF(DAY,gpcr.fecha_vencimiento, '".$fecha."')),0),0)) as mora,
                                        			if(gpcr.estado='3','VENCIDO','POR VENCER') as estado
                                        			FROM gp_cronograma gpcr
                                        			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
                                        			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
                                        			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
                                        			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                                        			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                                        			INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
                                        			WHERE gpcr.esta_borrado=0 AND gpv.devolucion!='1' AND gpv.cancelado!='1'
                                        			$query_estado
                                        			$query_documento
                                                	$query_fecha
                                                	$query_proyecto
                                                	$query_zona
                                                	$query_manzana
                                                	$query_lote
                                        			GROUP BY cliente, letra WITH ROLLUP
                                        			"); 
                                        			
                                        	
                                                                                
                                        ?>
										<table class="table table-striped table-bordered" cellspacing="0" id="TablaHojaResumen" style="width: 100%; text-align: center;">
                                            <thead class="cabecera">
                                                <tr style="background-color: #28539A; color: white;">
                                                    <th style="font-size: 10px;width: 250px">CLIENTE</th>
                                                    <th style="font-size: 10px;width: 60px">FECHA</th>
                                                    <th style="font-size: 10px;width: 60px">LOTE</th>
                                                    <th style="font-size: 10px;width: 60px">LETRA</th>
                                                    <th style="font-size: 10px;width: 80px">MONTO</th>
                                                    <th style="font-size: 10px;width: 60px">MORA</th>
                                                    <th style="font-size: 10px;width: 60px">ESTADO</th>
                                                </tr>
                                            </thead>
                                            <tbody class="control-detalle">
                                                <?php while($row = $query->fetch_assoc()){  
                                                    if($row['letra'] == "" && $row['cliente']!=""){
                                                    ?>
                                                    <tr>
                                                        <td style="font-size: 10px; font-weight: bold; background-color: #D8D8D8; text-align: right;" colspan="4">TOTAL</td>
                                                        <td style="font-size: 10px; font-weight: bold; background-color: #D8D8D8;"><?php echo $row['monto']; ?></td>
                                                        <td style="font-size: 10px; font-weight: bold; background-color: #D8D8D8;"><?php echo $row['mora']; ?></td>
                                                        <td style="font-size: 10px; background-color: #D8D8D8;"></td>
                                                    </tr>
                                                    <?php  
                                                    }else{ 
                                                        if($row['letra'] == "" && $row['cliente']==""){
                                                        ?>
                                                        <tr>
                                                            <td style="font-size: 10px; font-weight: bold; background-color: #FFFFC0; text-align: right;" colspan="4">TOTAL GENERAL</td>
                                                            <td style="font-size: 10px; font-weight: bold; background-color: #FFFFC0;"><?php echo $row['monto']; ?></td>
                                                            <td style="font-size: 10px; font-weight: bold; background-color: #FFFFC0;"><?php echo $row['mora']; ?></td>
                                                            <td style="font-size: 10px; background-color: #FFFFC0;"></td>
                                                        </tr>
                                                        <?php  
                                                        }else{ ?>
                                                            <tr>
                                                                <td style="font-size: 10px; text-align: left;"><?php echo $row['cliente']; ?></td>
                                                                <td style="font-size: 10px;"><?php echo $row['fecha']; ?></td>
                                                                <td style="font-size: 10px;"><?php echo $row['lote']; ?></td>
                                                                <td style="font-size: 10px;"><?php echo $row['letra']; ?></td>
                                                                <td style="font-size: 10px;"><?php echo $row['monto']; ?></td>
                                                                <td style="font-size: 10px;"><?php echo $row['mora']; ?></td>
                                                                <td style="color: #891500; font-size: 10px; font-weight: bold;"><?php echo $row['estado']; ?></td>
                                                            </tr>
                                                        <?php } 
                                                        
                                                    }
                                                } ?>
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

    <script src="../../js/M02_Clientes/M02JS04_HojaResumen/ReporteHojaResumen.js?v=1.1.1"></script>

    <script src="../../librerias/utilitario/jquery.blockUI.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/utilitario.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/sweetalert.min.js?v=1.1.1"></script>
    <script src="../../librerias/utilitario/dialogs.js?v=1.1.1"></script>
    <input type="hidden" id="__FECHA_ACTUAL" value="<?php echo strftime("%Y-%m-%d"); ?>">
</body>

</html>
<?php

$html = ob_get_clean();
//echo $html;

require_once '../../librerias/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemotoeEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$dompdf->stream("".$nombre_archivo.".pdf", array("Attachment" => false));

?>

