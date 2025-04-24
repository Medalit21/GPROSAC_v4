<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');
   
  

   $data = array();
   $dataList = array();

if(isset($_POST['ReturnListaDetalle'])){

	$idRegistro = $_POST['Codigo'];
	
	
		$query = mysqli_query($conection,"SELECT 
		    gppc.idpago as id,
			concat(dc.documento,' - ', dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
			gpy.nombre as proyecto,
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
			INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
			INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gppd.estado AND cdx.codigo_tabla='_ESTADO_VP'
			INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.moneda_pago AND cddx.codigo_tabla='_TIPO_MONEDA'
			INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.medio_pago AND cdddx.codigo_tabla='_MEDIO_PAGO'
			INNER JOIN configuracion_detalle AS cddddx ON cddddx.codigo_item=gpcr.estado AND cddddx.codigo_tabla='_ESTADO_EC'
			INNER JOIN configuracion_detalle AS cdddddx ON cdddddx.idconfig_detalle=gppd.tipo_comprobante AND cdddddx.codigo_tabla='_TIPO_COMPROBANTE'
			WHERE gppd.esta_borrado=0
            AND gppc.idpago='$idRegistro'
            ORDER BY gppd.fecha_pago DESC 
			"); 

	 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {            
           
            //Campos para llenar Tabla
            array_push($dataList,[
                'cliente' => $row['cliente'],
                'proyecto' => $row['proyecto'],
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
				'medio_pago' => $row['medio_pago'],
				'tipo_comprobante' => $row['tipo_comprobante'],
				'boleta' => $row['boleta'],
				'nro_operacion' => $row['nro_operacion']
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


if(isset($_POST['ReturnLista'])){

	$txtFiltroFechaInicio = isset($_POST['txtFiltroFechaInicio']) ? $_POST['txtFiltroFechaInicio'] : Null;
	$txtFiltroFechaInicior = trim($txtFiltroFechaInicio);
	
	$txtFiltroFechaFin = isset($_POST['txtFiltroFechaFin']) ? $_POST['txtFiltroFechaFin'] : Null;
	$txtFiltroFechaFinr = trim($txtFiltroFechaFin);
	
	$bxFiltroProyectoHR = isset($_POST['bxFiltroProyectoHR']) ? $_POST['bxFiltroProyectoHR'] : Null;
	$bxFiltroProyectoHRr = trim($bxFiltroProyectoHR);
	
	$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
	$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
	
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
	
	if(!empty($txtFiltroDocumentoHRr)){
	   $query_documento = "AND dc.documento like '%$txtFiltroDocumentoHRr%'"; 
	}
	
	if(!empty($txtFiltroFechaInicior)){
	    $query_tiempo = "AND gppc.fecha_pago='$txtFiltroFechaInicior'";
	}
	
	if(!empty($txtFiltroFechaInicior) && !empty($txtFiltroFechaFinr)){
	    $query_tiempo = "AND gppc.fecha_pago BETWEEN '$txtFiltroFechaInicior' AND '$txtFiltroFechaFinr'";
	}
	
	if(!empty($bxFiltroProyectoHRr)){
	   $query_proyecto = "AND gpy.idproyecto='$bxFiltroProyectoHRr'"; 
	}
	
	if(!empty($bxFiltroZonaHRr)){
	   $query_zona = "AND gpz.idzona='$bxFiltroZonaHRr'"; 
	}
	
	if(!empty($bxFiltroManzanaHRr)){
	   $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaHRr'"; 
	}
	
	
	if(!empty($bxFiltroEstadoHRr)){
	    if($bxFiltroEstadoHRr == "todos"){
	        $query_estado = "";
	    }else{
		    $query_estado = "AND gpcr.estado='$bxFiltroEstadoHRr'";
	    }
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
		    gppc.idpago as id,
			concat(dc.documento,' - ', dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
			gpy.nombre as proyecto,
			gpz.nombre as zona,
			concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
			gpcr.item_letra as letra,
			gpcr.fecha_vencimiento as fecha_vencimiento,
			gpcr.estado as estado_cuota,
			cddddx.nombre_corto as descEstado_cuota,
			cddddx.texto1 as color_estado_cuota,
			gppc.fecha_pago as fecha_pago,
			gppc.estado as estado_pago,
			IFNULL(cdx.nombre_corto,'') as descEstado_pago,
			IFNULL(cdx.texto1,'') as color_estado_pago,
			if((gpcr.fecha_vencimiento>=gppc.fecha_pago),'0', concat('-',TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, gppc.fecha_pago))) as mora,
			cddx.texto1 as tipo_moneda,
			gppc.tipo_cambio as tipo_cambio,
			format(gppc.importe_pago,2) as importe_pago
			FROM gp_pagos_cabecera AS gppc
			INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
			INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
			INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gppc.estado AND cdx.codigo_tabla='_ESTADO_VP'
			INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppc.moneda_pago AND cddx.codigo_tabla='_TIPO_MONEDA'
			INNER JOIN configuracion_detalle AS cddddx ON cddddx.codigo_item=gpcr.estado AND cddddx.codigo_tabla='_ESTADO_EC'
			WHERE gppc.esta_borrado=0 AND (gpcr.estado='2' OR gppc.estado='1')
			$query_proyecto
			$query_zona
			$query_manzana
			$query_documento
            $query_tiempo
            $query_estado
            $query_venta
            ORDER BY gppc.fecha_pago DESC 
			"); 

	 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {            
           
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'cliente' => $row['cliente'],
                'proyecto' => $row['proyecto'],
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
				'importe_pago' => $row['importe_pago']
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
