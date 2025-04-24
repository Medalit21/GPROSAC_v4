<?php
   session_start();
   //setlocale(LC_MONETARY, 'en_US');
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');

   $data = array();
   $dataList = array();

if(isset($_POST['ReturnListaClientes'])){
	
	$txtdocumentoFiltro = isset($_POST['txtdocumentoFiltro']) ? $_POST['txtdocumentoFiltro'] : Null;
	$bxFiltrodocumento = trim($txtdocumentoFiltro);
	
	$bxFiltroPeriodo = isset($_POST['bxFiltroPeriodo']) ? $_POST['bxFiltroPeriodo'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo);
	

	$query_documento = "";
	
	if(!empty($bxFiltrodocumento)){
	   $query_documento = "AND dc.documento='$bxFiltrodocumento'"; 
	}
	
	$_SESSION['nom'] = "Segun fecha de Pago";
	

        $query = mysqli_query($conection,"
    	SELECT 
		gpv.id_venta as id,
		gpv.id_vendedor as vendedor,
		if(gpv.id_vendedor>0,(select CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) from persona per where per.idusuario=gpv.id_vendedor),'Ninguno') as nom_vendedor,
		gpv.fecha_venta as fecha,
		dc.documento as documento,
		concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
		cdx.texto1 as tipo_moneda,
		gpp.nombre as proyecto,
		gpz.nombre as zona,
		concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
		FORMAT(gpv.total,2) as total,
		FORMAT((select sum(gc.monto_letra) from gp_cronograma gc where gc.id_venta=gpv.id_venta AND gc.esta_borrado='0'),2) as total_financiado,
		if(gpv.devolucion='1','DEVUELTO','ACTIVO') as estado_venta,
		format(ROUND((SELECT (SUM(gppd.pagado) + if((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1')>0,(select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1'),0)) FROM gp_pagos_cabecera pagoc, gp_pagos_detalle gppd, gp_cronograma gpcr 
            WHERE pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagoc.idpago=gppd.idpago AND gppd.esta_borrado='0' AND gpcr.estado=2 AND pagoc.esta_borrado='0' AND gpcr.esta_borrado='0'),2),2) as monto_pagado,
		((select sum(gpcr.monto_letra) as total from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND dscto_acuerdo='0' AND gpcr.esta_borrado='0')-(select sum(gppd.pagado) as total from gp_pagos_cabecera gppc INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago AND gppd.esta_borrado='0' WHERE gppc.id_venta=gpv.id_venta AND gppc.esta_borrado='0')) as saldo,
		FORMAT(SUM(gpv.monto_cuota_inicial),2) as monto_inicial,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='1' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as enero,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='2' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0' )),2) as febrero,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='3' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as marzo,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='4' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as abril,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='5' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as mayo,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='6' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as junio,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='7' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as julio,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='8' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as agosto,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='9' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as septiembre,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='10' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as octubre,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='11' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as noviembre,
		FORMAT(SUM((SELECT SUM(gppd2.pagado) FROM gp_pagos_cabecera gppd1, gp_pagos_detalle gppd2 WHERE gppd1.idpago=gppd2.idpago AND gppd1.id_venta=gpv.id_venta AND gppd1.estado=2 AND MONTH(gppd2.fecha_pago)='12' AND YEAR(gppd2.fecha_pago)='$bxFiltroPeriodos' AND gppd1.esta_borrado='0' AND gppd2.esta_borrado='0')),2) as diciembre
		FROM gp_venta gpv
		INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
		INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
		INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
		INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
		INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
		INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gpv.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
		WHERE gpv.esta_borrado=0 AND gpv.conformidad='1' AND YEAR(gpv.fecha_venta) = '$bxFiltroPeriodos'
		$query_documento
		GROUP BY cliente, gpv.id_lote
		ORDER BY cliente
	");
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $valida_1 = $row['cliente'];
            $valida_2 = $row['vendedor'];

            $dato_1=$row['nom_vendedor'];
            $dato_2=$row['fecha'];
            $dato_3=$row['tipo_moneda'];
            $dato_4=$row['proyecto'];
            $dato_5=$row['zona'];
            $dato_6=$row['lote'];
            $accion = 0;

            if(empty($valida_1) && !empty($valida_2)){
            	$dato_1="TOTAL";
	            $dato_2="";
	            $dato_3="";
	            $dato_4="";
	            $dato_5="";
	            $dato_6="";
	            $accion = 2;
            }else{
            	if(empty($valida_1) && empty($valida_2)){
            		$dato_1="TOTAL GENERAL";
		            $dato_2="";
		            $dato_3="";
		            $dato_4="";
		            $dato_5="";
		            $dato_6="";
		            $accion = 3;
            	}
            }

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'accion' => $accion,
				'vendedor' => $row['vendedor'],
				'nom_vendedor' => $dato_1,
				'fecha' => $dato_2,
				'documento' => $row['documento'],
				'cliente' => $row['cliente'],
				'tipo_moneda' => $dato_3,
				'proyecto' => $dato_4,
				'zona' => $dato_5,
				'lote' => $dato_6,
				'total' => $row['total'],
				'total_financiado' => $row['total_financiado'],
				'estado_venta' => $row['estado_venta'],
				'monto_pagado' => $row['monto_pagado'],
				'saldo' => number_format($row['saldo'],2),
				'monto_inicial' => $row['monto_inicial'],
				'enero' => $row['enero'],
				'febrero' => $row['febrero'],
				'marzo' => $row['marzo'],
				'abril' => $row['abril'],
				'mayo' => $row['mayo'],
				'junio' => $row['junio'],
				'julio' => $row['julio'],
				'agosto' => $row['agosto'],
				'septiembre' => $row['septiembre'],
				'octubre' => $row['octubre'],
				'noviembre' => $row['noviembre'],
				'diciembre' =>  $row['diciembre']
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
			//$data['recordsTotal'] = 0;
            //$data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}

if(isset($_POST['ReturnListaClientes2'])){
	
	$txtdocumentoFiltro = isset($_POST['txtdocumentoFiltro']) ? $_POST['txtdocumentoFiltro'] : Null;
	$bxFiltrodocumento = trim($txtdocumentoFiltro);
	
	$bxFiltroPeriodo = isset($_POST['bxFiltroPeriodo']) ? $_POST['bxFiltroPeriodo'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo);
	

	$query_documento = "";
	
	if(!empty($bxFiltrodocumento)){
	   $query_documento = "AND dc.documento='$bxFiltrodocumento'"; 
	}
	
	$_SESSION['nom'] = "Segun fecha de Vencimiento";
	
    $query = mysqli_query($conection,"
    	SELECT 
		gpv.id_venta as id,
		gpv.id_vendedor as vendedor,
		if(gpv.id_vendedor>0,(select CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) from persona per where per.idusuario=gpv.id_vendedor),'Ninguno') as nom_vendedor,
		gpv.fecha_venta as fecha,
		dc.documento as documento,
		concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
		cdx.texto1 as tipo_moneda,
		gpp.nombre as proyecto,
		gpz.nombre as zona,
		concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote, 
		if(gpv.devolucion='1','DEVUELTO','ACTIVO') as estado_venta,
		FORMAT((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0'),2) as total,
		FORMAT((select sum(pagado) from gp_pagos_cabecera where id_venta=gpv.id_venta AND esta_borrado='0'),2) as monto_pagado,
		FORMAT(((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0') - (select sum(pagado) from gp_pagos_cabecera where id_venta=gpv.id_venta AND esta_borrado='0')),2) as saldo,
		FORMAT(SUM(gpv.monto_cuota_inicial),2) as monto_inicial,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='1' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as enero,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='2' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as febrero,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='3' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as marzo,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='4' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as abril,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='5' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as mayo,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='6' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as junio,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='7' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as julio,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='8' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as agosto,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='9' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as septiembre,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='10' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as octubre,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='11' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as noviembre,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='12' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')),2) as diciembre,	
		
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='1' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_enero,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='2' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_febrero,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='3' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_marzo,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='4' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_abril,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='5' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_mayo,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='6' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_junio,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='7' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_julio,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='8' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_agosto,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='9' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_septiembre,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='10' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_octubre,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='11' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_noviembre,
		FORMAT(SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='12' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0')),2) as cancel_diciembre,	
		
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='1' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='1' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_enero,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='2' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='2' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_febrero,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='3' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='3' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_marzo,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='4' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='4' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_abril,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='5' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='5' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_mayo,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='6' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='6' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_junio,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='7' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='7' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_julio,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='8' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='8' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_agosto,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='9' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='9' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_septiembre,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='10' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='10' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_octubre,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='11' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='11' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_noviembre,
		FORMAT(SUM((SELECT if(SUM(gppd1.monto_letra)>0,SUM(gppd1.monto_letra),'0.00') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='12' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos')) - (SUM((SELECT if(SUM(gppc.pagado)>0,SUM(gppc.pagado),'0.00') FROM gp_cronograma gppd1, gp_pagos_cabecera gppc WHERE gppd1.id_venta=gpv.id_venta AND gppd1.id_venta=gppc.id_venta AND gppd1.correlativo=gppc.id_cronograma AND gppd1.estado=2 AND MONTH(gppd1.fecha_vencimiento)='12' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' AND gppc.esta_borrado='0'))),2) as saldo_diciembre,
		
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='1' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_enero,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='2' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_febrero,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='3' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_marzo,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='4' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_abril,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='5' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_mayo,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='6' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_junio,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='7' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_julio,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='8' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_agosto,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='9' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_septiembre,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='10' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_octubre,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='11' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_noviembre,
		(SELECT date_format(gppd1.fecha_vencimiento, '%d/%m/%Y') FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='12' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as fec_diciembre,
		
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='1' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_enero,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='2' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_febrero,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='3' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_marzo,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='4' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_abril,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='5' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_mayo,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='6' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_junio,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='7' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_julio,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='8' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_agosto,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='9' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_septiembre,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='10' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_octubre,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='11' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_noviembre,
		(SELECT gppd1.item_letra FROM gp_cronograma gppd1 WHERE gppd1.id_venta=gpv.id_venta AND gppd1.estado in (1, 2, 3) AND MONTH(gppd1.fecha_vencimiento)='12' AND YEAR(gppd1.fecha_vencimiento)='$bxFiltroPeriodos' group by MONTH(gppd1.fecha_vencimiento)) as letra_diciembre
		FROM gp_venta gpv
		INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
		INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
		INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
		INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
		INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
		INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gpv.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
		WHERE gpv.esta_borrado=0 AND gpv.conformidad='1' AND gpv.cancelado!='1' AND gpv.devolucion!='1'
		$query_documento
		GROUP BY cliente
		ORDER BY cliente
	");
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $valida_1 = $row['cliente'];
            $valida_2 = $row['vendedor'];

            $dato_1=$row['nom_vendedor'];
            $dato_2=$row['fecha'];
            $dato_3=$row['tipo_moneda'];
            $dato_4=$row['proyecto'];
            $dato_5=$row['zona'];
            $dato_6=$row['lote'];
            $accion = 0;

            if(empty($valida_1) && !empty($valida_2)){
            	$dato_1="TOTAL";
	            $dato_2="";
	            $dato_3="";
	            $dato_4="";
	            $dato_5="";
	            $dato_6="";
	            $accion = 2;
            }else{
            	if(empty($valida_1) && empty($valida_2)){
            		$dato_1="TOTAL GENERAL";
		            $dato_2="";
		            $dato_3="";
		            $dato_4="";
		            $dato_5="";
		            $dato_6="";
		            $accion = 3;
            	}
            }

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'accion' => $accion,
				'vendedor' => $row['vendedor'],
				'nom_vendedor' => $dato_1,
				'fecha' => $dato_2,
				'documento' => $row['documento'],
				'cliente' => $row['cliente'],
				'tipo_moneda' => $dato_3,
				'proyecto' => $dato_4,
				'zona' => $dato_5,
				'lote' => $dato_6,
				'total' => $row['total'],
				'estado_venta' => $row['estado_venta'],
				'monto_pagado' => $row['monto_pagado'],
				'saldo' => $row['saldo'],
				'monto_inicial' => $row['monto_inicial'],
				
				'enero' => $row['enero'],
				'febrero' => $row['febrero'],
				'marzo' => $row['marzo'],
				'abril' => $row['abril'],
				'mayo' => $row['mayo'],
				'junio' => $row['junio'],
				'julio' => $row['julio'],
				'agosto' => $row['agosto'],
				'septiembre' => $row['septiembre'],
				'octubre' => $row['octubre'],
				'noviembre' => $row['noviembre'],
				'diciembre' =>  $row['diciembre'],
				
				'cancel_enero' => $row['cancel_enero'],
				'cancel_febrero' => $row['cancel_febrero'],
				'cancel_marzo' => $row['cancel_marzo'],
				'cancel_abril' => $row['cancel_abril'],
				'cancel_mayo' => $row['cancel_mayo'],
				'cancel_junio' => $row['cancel_junio'],
				'cancel_julio' => $row['cancel_julio'],
				'cancel_agosto' => $row['cancel_agosto'],
				'cancel_septiembre' => $row['cancel_septiembre'],
				'cancel_octubre' => $row['cancel_octubre'],
				'cancel_noviembre' => $row['cancel_noviembre'],
				'cancel_diciembre' =>  $row['cancel_diciembre'],
				
				'saldo_enero' => $row['saldo_enero'],
				'saldo_febrero' => $row['saldo_febrero'],
				'saldo_marzo' => $row['saldo_marzo'],
				'saldo_abril' => $row['saldo_abril'],
				'saldo_mayo' => $row['saldo_mayo'],
				'saldo_junio' => $row['saldo_junio'],
				'saldo_julio' => $row['saldo_julio'],
				'saldo_agosto' => $row['saldo_agosto'],
				'saldo_septiembre' => $row['saldo_septiembre'],
				'saldo_octubre' => $row['saldo_octubre'],
				'saldo_noviembre' => $row['saldo_noviembre'],
				'saldo_diciembre' =>  $row['saldo_diciembre'],
				
				'fec_enero' =>  $row['fec_enero'],
				'fec_febrero' =>  $row['fec_febrero'],
				'fec_marzo' =>  $row['fec_marzo'],
				'fec_abril' =>  $row['fec_abril'],
				'fec_mayo' =>  $row['fec_mayo'],
				'fec_junio' =>  $row['fec_junio'],
				'fec_julio' =>  $row['fec_julio'],
				'fec_agosto' =>  $row['fec_agosto'],
				'fec_septiembre' =>  $row['fec_septiembre'],
				'fec_octubre' =>  $row['fec_octubre'],
				'fec_noviembre' =>  $row['fec_noviembre'],
				'fec_diciembre' =>  $row['fec_diciembre'],
				'letra_enero' =>  $row['letra_enero'],
				'letra_febrero' =>  $row['letra_febrero'],
				'letra_marzo' =>  $row['letra_marzo'],
				'letra_abril' =>  $row['letra_abril'],
				'letra_mayo' =>  $row['letra_mayo'],
				'letra_junio' =>  $row['letra_junio'],
				'letra_julio' =>  $row['letra_julio'],
				'letra_agosto' =>  $row['letra_agosto'],
				'letra_septiembre' =>  $row['letra_septiembre'],
				'letra_octubre' =>  $row['letra_octubre'],
				'letra_noviembre' =>  $row['letra_noviembre'],
				'letra_diciembre' =>  $row['letra_diciembre']
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
			//$data['recordsTotal'] = 0;
            //$data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}


if(isset($_POST['Reporte1'])){

		$bxFiltroPeriodo = isset($_POST['bxFiltroPeriodo']) ? $_POST['bxFiltroPeriodo'] : Null;
		$bxFiltroPeriodor = trim($bxFiltroPeriodo);
		
		$dato_doc="";
		
		if(!empty($bxFiltroPeriodor)){
		    $dato_doc = encrypt($bxFiltroPeriodor, "123");

		    $data['status']="ok";
            $data['param'] = $dato_doc;		    
		}else{
            $data['status']="bad";
        }
	     
		

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}
