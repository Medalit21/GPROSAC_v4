<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $fecha_hoy = date('d-m-Y'); 
   $mes = date('m');
   //$anio = date('Y');
   
  

   $data = array();
   $dataList = array();

if(isset($_POST['ReturnLista'])){

	$txtFiltroFechaInicio = isset($_POST['txtFiltroFechaInicio']) ? $_POST['txtFiltroFechaInicio'] : Null;
	$txtFiltroFechaInicior = trim($txtFiltroFechaInicio);
	
	$txtFiltroFechaFin = isset($_POST['txtFiltroFechaFin']) ? $_POST['txtFiltroFechaFin'] : Null;
	$txtFiltroFechaFinr = trim($txtFiltroFechaFin);
	
	$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
	$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
	
	$bxFiltroProyectoHR = isset($_POST['bxFiltroProyectoHR']) ? $_POST['bxFiltroProyectoHR'] : Null;
	$bxFiltroProyectoHRr = trim($bxFiltroProyectoHR);
	
	$bxFiltroZonaHR = isset($_POST['bxFiltroZonaHR']) ? $_POST['bxFiltroZonaHR'] : Null;
	$bxFiltroZonaHRr = trim($bxFiltroZonaHR);
	
	$bxFiltroManzanaHR = isset($_POST['bxFiltroManzanaHR']) ? $_POST['bxFiltroManzanaHR'] : Null;
	$bxFiltroManzanaHRr = trim($bxFiltroManzanaHR);
	
	$bxFiltroLoteHR = isset($_POST['bxFiltroLoteHR']) ? $_POST['bxFiltroLoteHR'] : Null;
	$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
	
	$bxFiltroEstadoHR = isset($_POST['bxFiltroEstadoHR']) ? $_POST['bxFiltroEstadoHR'] : Null;
	$bxFiltroEstadoHRr = trim($bxFiltroEstadoHR);
	
	$query_tiempo = "";
	$query_documento = "";
	$query_proyecto = "";
	$query_zona = "";
	$query_manzana = "";
	$query_lote	= "";
	$query_estado = "";
	$idventa = "0";
	$query_venta="";
	$dato_fecha="";
	if(!empty($txtFiltroFechaInicior)){
	    $query_tiempo = "AND gppd.fecha_pago='$txtFiltroFechaInicior'";
	    $dato_fecha = "( ".date("d-m-Y", strtotime($txtFiltroFechaInicior))." )";
	}
	
	if(!empty($txtFiltroFechaInicior) && !empty($txtFiltroFechaFinr)){
	    $query_tiempo = "AND gppd.fecha_pago BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'";
	    $dato_fecha = "( Del ".date("d-m-Y", strtotime($txtFiltroFechaInicior))." al ".date("d-m-Y", strtotime($txtFiltroFechaFinr))." )";
	}
	
	if(!empty($txtFiltroDocumentoHRr)){
	   $query_documento = "AND dc.documento like '%$txtFiltroDocumentoHRr%'"; 
	}
	
	if(!empty($bxFiltroProyectoHRr)){
	   $query_proyecto = "AND gpp.idproyecto='$bxFiltroProyectoHRr'"; 
	}
	
	if(!empty($bxFiltroZonaHRr)){
	   $query_zona = "AND gpz.idzona='$bxFiltroZonaHRr'"; 
	}
	
	if(!empty($bxFiltroManzanaHRr)){
	   $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaHRr'"; 
	}
	
	if(!empty($bxFiltroEstadoHRr)){
		$query_estado = "AND gpcr.estado='$bxFiltroEstadoHRr'";
	}
	
	if(empty($txtFiltroDocumentoHRr)){
		if(empty($bxFiltroLoteHRr)){
			$query_documento = "";
			$query_lote	  = "AND gpv.id_lote='0'";
		}else{			
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteHRr'";
			$query_documento = "";
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta="AND gpv.id_venta='$idventa'";
		}
	}else{		
		if(empty($bxFiltroLoteHRr)){
		    
			$query_lote	  = "";
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id_venta FROM gp_venta gpv, datos_cliente dc WHERE gpv.id_cliente=dc.id AND dc.documento='$txtFiltroDocumentoHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta="AND gpv.id_venta='$idventa'";
			
		}else{
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteHRr'"; 
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta="AND gpv.id_venta='$idventa'";
		}
	} 

		$query = mysqli_query($conection,"SELECT 
		    dc.documento as documento,
			concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
			gpz.nombre as zona,
			concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
			gpcr.item_letra as letra,
			gpcr.fecha_vencimiento as fecha_vencimiento,
			gpcr.estado as estado_cuota,
			cddddx.nombre_corto as descEstado_cuota,
			cddddx.texto1 as color_estado_cuota,
			gppd.fecha_pago as fecha_pago,
			gppd.estado as estado_pago,
			IFNULL(cdx.nombre_corto,'') as descEstado_pago,
			IFNULL(cdx.texto1,'') as color_estado_pago,
			if((gpcr.fecha_vencimiento>=gppd.fecha_pago),'0', concat('-',TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, gppd.fecha_pago))) as mora,
			cddx.texto1 as tipo_moneda,
			gppd.tipo_cambio as tipo_cambio,
			format(gppd.importe_pago,2) as importe_pago,
			format(gppd.pagado,2) as pagado,
			cdddx.nombre_corto as medio_pago,
			cdddddx.nombre_corto as tipo_comprobante,
			concat(gppd.serie,' - ',gppd.numero) as boleta,
			gppd.nro_operacion as nro_operacion
			FROM gp_pagos_detalle gppd
			INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
			INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
			INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
			INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gppd.estado AND cdx.codigo_tabla='_ESTADO_VP'
			INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.moneda_pago AND cddx.codigo_tabla='_TIPO_MONEDA'
			INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.medio_pago AND cdddx.codigo_tabla='_MEDIO_PAGO'
			INNER JOIN configuracion_detalle AS cddddx ON cddddx.codigo_item=gpcr.estado AND cddddx.codigo_tabla='_ESTADO_EC'
			INNER JOIN configuracion_detalle AS cdddddx ON cdddddx.idconfig_detalle=gppd.tipo_comprobante AND cdddddx.codigo_tabla='_TIPO_COMPROBANTE'
			WHERE gppd.esta_borrado=0 AND gpv.devolucion!='1'
			$query_proyecto
			$query_zona
			$query_manzana
			$query_documento
            $query_tiempo
            $query_estado
            $query_venta
            ORDER BY fecha_pago DESC, cliente DESC 
			"); 


	 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {            
           
            //Campos para llenar Tabla
            array_push($dataList,[
                'documento' => $row['documento'],
                'cliente' => $row['cliente'],
                'zona' => $row['zona'],
                'lote' => $row['lote'],
                'letra' => $row['letra'],
                'fecha_vencimiento' => $row['fecha_vencimiento'],
                'estado_cuota' => $row['estado_cuota'],
                'descEstado_cuota' => $row['descEstado_cuota'],
                'color_estado_cuota' => $row['color_estado_cuota'],
                'fecha_pago' => $row['fecha_pago'],
				'estado_pago' => $row['estado_pago'],
				'descEstado_pago' => $row['descEstado_pago'],
				'color_estado_pago' => $row['color_estado_pago'],
				'mora' => $row['mora'],
				'tipo_moneda' => $row['tipo_moneda'],
				'tipo_cambio' => $row['tipo_cambio'],
				'importe_pago' => $row['importe_pago'],
				'pagado' => $row['pagado'],
				'medio_pago' => $row['medio_pago'],
				'tipo_comprobante' => $row['tipo_comprobante'],
				'boleta' => $row['boleta'],
				'nro_operacion' => $row['nro_operacion']
            ]);
        }
            
       $data['data'] = $dataList;
       $data['fec_hoy'] = $dato_fecha;
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

if(isset($_POST['Fechas'])){
    
     $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
        $ultimo_dia =  date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    
        $month = date('m');
        $year = date('Y');
        $primer_dia = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
        
        $data['primer_dia'] = $primer_dia;
        $data['ultimo_dia'] = $ultimo_dia;
        
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    
}

if(isset($_POST['ConsultarTotalesAcumulados'])){

        //PAGADOS

		$query1 = mysqli_query($conection,"SELECT format(SUM(gppc.pagado),2) as total FROM gp_cronograma gpcr, gp_pagos_cabecera gppc WHERE gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta AND gpcr.esta_borrado=0 AND gpcr.estado=2"); 
		$row1 = mysqli_fetch_assoc($query1);
		$total_pagados = $row1['total'];

        $query11 = mysqli_query($conection,"SELECT COUNT(gppc.idpago) as total FROM gp_cronograma gpcr, gp_pagos_cabecera gppc WHERE gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta AND gpcr.esta_borrado=0 AND gpcr.estado=2"); 
		$row11 = mysqli_fetch_assoc($query11);
		$total_pagados_cont = $row11['total'];
		
		
		//PENDIENTES
		
		$query2 = mysqli_query($conection,"SELECT format(SUM(monto_letra),2) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=1"); 
		$row2 = mysqli_fetch_assoc($query2);
		$total_pendientes = $row2['total'];
		
		$query22 = mysqli_query($conection,"SELECT COUNT(monto_letra) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=1"); 
		$row22 = mysqli_fetch_assoc($query22);
		$total_pendientes_cont = $row22['total'];
		
		
		//VENCIDOS
		
		$query3 = mysqli_query($conection,"SELECT 
		format((SUM(monto_letra - (if(gpcr.estado='2',(select pagado from gp_pagos_cabecera where id_venta=gpv.id_venta AND id_cronograma=gpcr.correlativo),0)))),2) as total 
		FROM gp_cronograma gpcr
		INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta 
		WHERE gpv.devolucion!='1' 
		AND gpv.cancelado!='1' 
		AND gpcr.esta_borrado=0 
		AND ((gpcr.estado='3') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1' AND gpcr.fecha_vencimiento<'".$fecha."'))
		AND gpcr.fecha_vencimiento BETWEEN '2018-10-01' AND '".$fecha."'
		"); 
		$row3 = mysqli_fetch_assoc($query3);
		$total_vencidos = $row3['total'];
		
		$query33 = mysqli_query($conection,"SELECT 
		COUNT(gpcr.item_letra) as total 
		FROM gp_cronograma gpcr
		INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta 
		WHERE gpv.devolucion!='1' 
		AND gpv.cancelado!='1'
		AND gpcr.esta_borrado=0 
		AND ((gpcr.estado='3') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1' AND gpcr.fecha_vencimiento<'".$fecha."'))
		AND gpcr.fecha_vencimiento BETWEEN '2018-10-01' AND '".$fecha."'
		"); 
		$row33 = mysqli_fetch_assoc($query33);
		$total_vencidos_cont = $row33['total'];


        //TOTALES

        $query_1 = mysqli_query($conection,"SELECT SUM(gppc.importe_pago) as total FROM gp_cronograma gpcr, gp_pagos_cabecera gppc WHERE gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta AND gpcr.esta_borrado=0 AND gpcr.estado=2"); 
		$row_1 = mysqli_fetch_assoc($query_1);
		$total_pagados_ = $row_1['total'];
		
		$query_2 = mysqli_query($conection,"SELECT SUM(monto_letra) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=1"); 
		$row_2 = mysqli_fetch_assoc($query_2);
		$total_pendientes_ = $row_2['total'];
		
		$saldo = mysqli_query($conection,"SELECT 
		(SUM(monto_letra - (if(gpcr.estado='2',(select pagado from gp_pagos_cabecera where id_venta=gpv.id_venta AND id_cronograma=gpcr.correlativo),0)))) as total 
		FROM gp_cronograma gpcr
		INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta 
		WHERE gpcr.esta_borrado=0 
		AND gpcr.estado='2' AND gpcr.pago_cubierto='1' 
		"); 
		$row_saldo = mysqli_fetch_assoc($saldo);
		$total_saldo = $row_saldo['total'];
		
		$query_3 = mysqli_query($conection,"SELECT SUM(monto_letra) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=3"); 
		$row_3 = mysqli_fetch_assoc($query_3);
		$total_vencidos_ = $row_3['total'];

        

        $total = number_format($total_pagados_ + $total_pendientes_ + $total_saldo + $total_vencidos_, 2, '.', ',');
        $total_cont = $total_pagados_cont + $total_pendientes_cont + $total_vencidos_cont;

	    $data['pagados'] = $total_pagados_cont." - $ ".$total_pagados;
		$data['pendientes'] = $total_pendientes_cont." - $ ".$total_pendientes;
		$data['vencidos'] = $total_vencidos_cont." - $ ".$total_vencidos;
		$data['total'] = $total_cont." - $ ".$total;
		
		
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

   
}

if(isset($_POST['ConsultarTotales'])){

		$txtFiltroFechaInicio = isset($_POST['txtFiltroFechaInicio']) ? $_POST['txtFiltroFechaInicio'] : Null;
		$txtFiltroFechaInicior = trim($txtFiltroFechaInicio);
		
		$txtFiltroFechaFin = isset($_POST['txtFiltroFechaFin']) ? $_POST['txtFiltroFechaFin'] : Null;
		$txtFiltroFechaFinr = trim($txtFiltroFechaFin);
		
		
		//PAGADAS
		
		$query1 = mysqli_query($conection,"SELECT format(SUM(gppc.importe_pago),2) as total FROM gp_cronograma gpcr, gp_pagos_cabecera gppc
		WHERE gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta AND gppc.estado=2 AND gppc.fecha_pago BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row1 = mysqli_fetch_assoc($query1);
		$total_pagados = $row1['total'];

        $query11 = mysqli_query($conection,"SELECT COUNT(gppc.idpago) as total FROM gp_cronograma gpcr, gp_pagos_cabecera gppc 
        WHERE gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta AND gpcr.esta_borrado=0 AND gpcr.estado=2 AND gpcr.fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row11 = mysqli_fetch_assoc($query11);
		$total_pagados_cont = $row11['total'];
		
		
		//PENDIENTES
		
		$query2 = mysqli_query($conection,"SELECT format(SUM(monto_letra),2) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=1 AND fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row2 = mysqli_fetch_assoc($query2);
		$total_pendientes = $row2['total'];
		
		$query22 = mysqli_query($conection,"SELECT COUNT(monto_letra) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=1 AND fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row22 = mysqli_fetch_assoc($query22);
		$total_pendientes_cont = $row22['total'];
		
		
		//VENCIDOS
		
		$query3 = mysqli_query($conection,"SELECT 
		format((SUM(monto_letra - (if(gpcr.estado='2',(select pagado from gp_pagos_cabecera where id_venta=gpv.id_venta AND id_cronograma=gpcr.correlativo),0)))),2) as total 
		FROM gp_cronograma gpcr
		INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta 
		WHERE gpv.devolucion!='1' 
		AND gpv.cancelado!='1' 
		AND gpcr.esta_borrado=0 
		AND ((gpcr.estado='3') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1' AND gpcr.fecha_vencimiento<'".$txtFiltroFechaFinr."'))
		AND fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'
		"); 
		$row3 = mysqli_fetch_assoc($query3);
		$total_vencidos = $row3['total'];
		
		$query33 = mysqli_query($conection,"SELECT 
		COUNT(gpcr.item_letra) as total 
		FROM gp_cronograma gpcr
		INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta 
		WHERE gpv.devolucion!='1' 
		AND gpv.cancelado!='1'
		AND gpcr.esta_borrado=0 
		AND ((gpcr.estado='3') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1' AND gpcr.fecha_vencimiento<'".$txtFiltroFechaFinr."'))
		AND fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'
		"); 
		$row33 = mysqli_fetch_assoc($query33);
		$total_vencidos_cont = $row33['total'];
		
		
		//TOTAL

        $query_1 = mysqli_query($conection,"SELECT SUM(gppc.importe_pago) as total FROM gp_cronograma gpcr, gp_pagos_cabecera gppc 
        WHERE gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta AND gpcr.esta_borrado=0 AND gpcr.estado=2 AND gpcr.fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row_1 = mysqli_fetch_assoc($query_1);
		$total_pagados_ = $row_1['total'];
		
		$query_2 = mysqli_query($conection,"SELECT SUM(monto_letra) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado=1 AND fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row_2 = mysqli_fetch_assoc($query_2);
		$total_pendientes_ = $row_2['total'];
		
		$query_3 = mysqli_query($conection,"SELECT SUM(monto_letra) as total FROM gp_cronograma WHERE esta_borrado=0 AND estado IN ('1', '2') AND fecha_vencimiento BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'"); 
		$row_3 = mysqli_fetch_assoc($query_3);
		$total_vencidos_ = $row_3['total'];
		 
        $total = number_format($total_pagados_ + $total_pendientes_ + $total_vencidos_, 2, '.', ',');
        $total_cont = $total_pagados_cont + $total_pendientes_cont + $total_vencidos_cont;

	    $data['pagados'] = $total_pagados_cont." - $ ".$total_pagados;
		$data['pendientes'] = $total_pendientes_cont." - $ ".$total_pendientes;
		$data['vencidos'] = $total_vencidos_cont." - $ ".$total_vencidos;
		$data['total'] = $total_cont." - $ ".$total;
		
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

 
}

if(isset($_POST['ReturnListaExcel'])){

	$txtFiltroFechaInicio = isset($_POST['txtFiltroFechaInicio']) ? $_POST['txtFiltroFechaInicio'] : Null;
	$txtFiltroFechaInicior = trim($txtFiltroFechaInicio);
	
	$txtFiltroFechaFin = isset($_POST['txtFiltroFechaFin']) ? $_POST['txtFiltroFechaFin'] : Null;
	$txtFiltroFechaFinr = trim($txtFiltroFechaFin);
	
	$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
	$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
	
	$bxFiltroProyectoHR = isset($_POST['bxFiltroProyectoHR']) ? $_POST['bxFiltroProyectoHR'] : Null;
	$bxFiltroProyectoHRr = trim($bxFiltroProyectoHR);
	
	$bxFiltroZonaHR = isset($_POST['bxFiltroZonaHR']) ? $_POST['bxFiltroZonaHR'] : Null;
	$bxFiltroZonaHRr = trim($bxFiltroZonaHR);
	
	$bxFiltroManzanaHR = isset($_POST['bxFiltroManzanaHR']) ? $_POST['bxFiltroManzanaHR'] : Null;
	$bxFiltroManzanaHRr = trim($bxFiltroManzanaHR);
	
	$bxFiltroLoteHR = isset($_POST['bxFiltroLoteHR']) ? $_POST['bxFiltroLoteHR'] : Null;
	$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
	
	$bxFiltroEstadoHR = isset($_POST['bxFiltroEstadoHR']) ? $_POST['bxFiltroEstadoHR'] : Null;
	$bxFiltroEstadoHRr = trim($bxFiltroEstadoHR);
	
	$query_tiempo = "";
	$query_documento = "";
	$query_proyecto = "";
	$query_zona = "";
	$query_manzana = "";
	$query_lote	= "";
	$query_estado = "";
	$idventa = "0";
	$query_venta="";
	
	if(!empty($txtFiltroFechaInicior)){
	    $query_tiempo = "AND gppd.fecha_pago='$txtFiltroFechaInicior'";
	}
	
	if(!empty($txtFiltroFechaInicior) && !empty($txtFiltroFechaFinr)){
	    $query_tiempo = "AND gppd.fecha_pago BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'";
	}
	
	if(!empty($txtFiltroDocumentoHRr)){
	   $query_documento = "AND dc.documento like '%$txtFiltroDocumentoHRr%'"; 
	}
	
	if(!empty($bxFiltroProyectoHRr)){
	   $query_proyecto = "AND gpp.idproyecto='$bxFiltroProyectoHRr'"; 
	}
	
	if(!empty($bxFiltroZonaHRr)){
	   $query_zona = "AND gpz.idzona='$bxFiltroZonaHRr'"; 
	}
	
	if(!empty($bxFiltroManzanaHRr)){
	   $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaHRr'"; 
	}
	
	if(!empty($bxFiltroEstadoHRr)){
		$query_estado = "AND gpcr.estado='$bxFiltroEstadoHRr'";
	}
	
	if(empty($txtFiltroDocumentoHRr)){
		if(empty($bxFiltroLoteHRr)){
			$query_documento = "";
			$query_lote	  = "AND gpv.id_lote='0'";
		}else{			
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteHRr'";
			$query_documento = "";
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta="AND gpv.id_venta='$idventa'";
		}
	}else{		
		if(empty($bxFiltroLoteHRr)){
		    
			$query_lote	  = "";
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id_venta FROM gp_venta gpv, datos_cliente dc WHERE gpv.id_cliente=dc.id AND dc.documento='$txtFiltroDocumentoHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta="AND gpv.id_venta='$idventa'";
			
		}else{
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteHRr'"; 
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta="AND gpv.id_venta='$idventa'";
		}
	} 

		$query = mysqli_query($conection,"SELECT 
			concat(dc.documento,' - ', dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
			gpz.nombre as zona,
			concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
			gpcr.item_letra as letra,
			gpcr.fecha_vencimiento as fecha_vencimiento,
			gpcr.estado as estado_cuota,
			cddddx.nombre_corto as descEstado_cuota,
			cddddx.texto1 as color_estado_cuota,
			gppd.fecha_pago as fecha_pago,
			gppd.estado as estado_pago,
			IFNULL(cdx.nombre_corto,'') as descEstado_pago,
			IFNULL(cdx.texto1,'') as color_estado_pago,
			if((gpcr.fecha_vencimiento>=gppd.fecha_pago),'0', concat('-',TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, gppd.fecha_pago))) as mora,
			cddx.texto1 as tipo_moneda,
			gppd.tipo_cambio as tipo_cambio,
			format(gppd.importe_pago,2) as importe_pago,
			cdddx.nombre_corto as medio_pago,
			cdddddx.nombre_corto as tipo_comprobante,
			concat(gppd.serie,' - ',gppd.numero) as boleta,
			gppd.nro_operacion as nro_operacion
			FROM gp_pagos_detalle gppd
			INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
			INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
			INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
			INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gppd.estado AND cdx.codigo_tabla='_ESTADO_VP'
			INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.moneda_pago AND cddx.codigo_tabla='_TIPO_MONEDA'
			INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.medio_pago AND cdddx.codigo_tabla='_MEDIO_PAGO'
			INNER JOIN configuracion_detalle AS cddddx ON cddddx.codigo_item=gpcr.estado AND cddddx.codigo_tabla='_ESTADO_EC'
			INNER JOIN configuracion_detalle AS cdddddx ON cdddddx.idconfig_detalle=gppd.tipo_comprobante AND cdddddx.codigo_tabla='_TIPO_COMPROBANTE'
			WHERE gppd.esta_borrado=0 AND (gpcr.estado='2' OR gppc.estado='1') AND gpv.devolucion!='1'
			$query_proyecto
			$query_zona
			$query_manzana
			$query_documento
            $query_tiempo
            $query_estado
            $query_venta
            ORDER BY gppd.fecha_pago DESC 
			"); 

	 
    if($query->num_rows > 0){
        
        
        $nombre_archivo = "Reporte_Pagos.xls";
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$nombre_archivo);
        header('Pragma: no-cache');
        header('Expires: 0');
        echo '<table border=1>';
        echo '<tr>';
        echo '<th colspan=16> GPROSAC - Reporte de Pagos</th>';
        echo '</th>';
        echo '<tr><th>Cliente</th><th>Zona</th><th>Lote</th><th>Letra</th><th>Fecha Vencimiento</th><th>Estado Letra</th><th>Fecha Pago</th><th>Estado Pago</th><th>Mora</th><th>Tipo Moneda</th><th>Tipo Cambio</th><th>Importe Pago</th><th>Medio Pago</th><th>Tipo Comprobante</th><th>Nro Boleta</th><th>Nro Operación</th></tr>';
     
        while($row = mysqli_fetch_array($query)) {            
           
                echo '<tr>';
                echo '<td>'.$row['cliente'].'</td>';
                echo '<td>'.$row['zona'].'</td>';
                echo '<td>'.$row['lote'].'</td>';
                echo '<td>'.$row['letra'].'</td>';
                echo '<td>'.$row['fecha_vencimiento'].'</td>';
                echo '<td>'.$row['descEstado_cuota'].'</td>';
                echo '<td>'.$row['fecha_pago'].'</td>';
                echo '<td>'.$row['descEstado_pago'].'</td>';
                echo '<td>'.$row['mora'].'</td>';
                echo '<td>'.$row['tipo_moneda'].'</td>';
                echo '<td>'.$row['tipo_cambio'].'</td>';
                echo '<td>'.$row['importe_pago'].'</td>';
                echo '<td>'.$row['medio_pago'].'</td>';
                echo '<td>'.$row['tipo_comprobante'].'</td>';
                echo '<td>'.$row['boleta'].'</td>';
                echo '<td>'.$row['nro_operacion'].'</td>';
                
        }
        echo '</table>';    
       

    }
        
    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT) ;
}

if (isset($_POST['ListarZonas'])) {

        $idproyecto = $_POST['idproyecto'];
        $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idproyecto' AND estado='1' ORDER BY nombre");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
        ]);

        if ($query->num_rows > 0) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }

if (isset($_POST['ListarManzanas'])) {

        $idzona = $_POST['idzona'];
        $query = mysqli_query($conection, "SELECT idmanzana as valor, nombre as texto FROM gp_manzana WHERE idzona='$idzona' AND estado='1' ORDER BY nombre");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
        ]);

        if ($query->num_rows > 0) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }

if (isset($_POST['ListarLotes'])) {

        $idmanzana = $_POST['idmanzana'];
        $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idmanzana' ORDER BY correlativo ASC");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
        ]);

        if ($query->num_rows > 0) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }