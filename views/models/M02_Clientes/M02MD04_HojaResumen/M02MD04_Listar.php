<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');
   


   $data = array();
   $dataList = array();

if(isset($_POST['ReturnListaHojaResumen'])){

	$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
	$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
	
	$bxFiltroLoteHR = isset($_POST['bxFiltroLoteHR']) ? $_POST['bxFiltroLoteHR'] : Null;
	$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
	
	$bxFiltroEstadoHR = isset($_POST['bxFiltroEstadoHR']) ? $_POST['bxFiltroEstadoHR'] : Null;
	$bxFiltroEstadoHRr = trim($bxFiltroEstadoHR);
	
	$cbxPropiedadesCliente = isset($_POST['cbxPropiedadesCliente']) ? $_POST['cbxPropiedadesCliente'] : Null;
	$cbxPropiedadesClienter = trim($cbxPropiedadesCliente);
	
	$query_documento = "";
	$query_lote	= "";
	$query_estado = "";
	$idventa = "0";
	
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
		}
	}else{		
		if(empty($bxFiltroLoteHRr)){
		    
			$query_lote	  = "";
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id_venta FROM gp_venta gpv, datos_cliente dc WHERE gpv.id_cliente=dc.id AND dc.documento='$txtFiltroDocumentoHRr'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
			
		}else{
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteHRr'"; 
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$txtFiltroDocumentoHRr'");
			$respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
			$idcliente=$respuesta_idcliente['id'];
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr' AND id_cliente='$idcliente'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		}
	} 
	
	
	if(!empty($cbxPropiedadesClienter)){
	    $idventa = $cbxPropiedadesClienter;
	}

		$query = mysqli_query($conection,"SELECT
            date_format(gpcr.fecha_vencimiento, '%d/%m/%Y') as fecha,
            gpcr.item_letra as letra,
            format(gpcr.monto_letra,2) as monto,
            (select date_format(fecha_pago, '%d/%m/%Y') from gp_pagos_cabecera WHERE id_venta=gpcr.id_venta AND esta_borrado='0' AND id_cronograma=gpcr.correlativo) as fecha_pago,
            (select ROUND(gppc.tipo_cambio,3) from gp_pagos_cabecera gppc WHERE gppc.id_venta=gpcr.id_venta AND gppc.esta_borrado='0' AND gppc.id_cronograma=gpcr.correlativo) as tipo_cambio,
            
            (select concat(cdx.texto1,' - ',format(SUM(gppc.importe_pago),2)) from gp_pagos_cabecera gppc, configuracion_detalle cdx WHERE gppc.esta_borrado='0' AND cdx.idconfig_detalle=gppc.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA' AND gppc.id_venta=gpcr.id_venta AND gppc.id_cronograma=gpcr.correlativo) as importe_pago,
            
            (select concat('USD - ',format(SUM(gppd.pagado),2)) from gp_pagos_cabecera gppc INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago AND gppd.esta_borrado='0' WHERE  gppc.esta_borrado='0' AND gppc.id_venta=gpcr.id_venta AND gppc.id_cronograma=gpcr.correlativo) as pagado,
            
            (select cdx.texto1 from gp_pagos_cabecera gppc, configuracion_detalle cdx WHERE cdx.idconfig_detalle='15381' AND gppc.esta_borrado='0' AND cdx.codigo_tabla='_TIPO_MONEDA' AND gppc.id_venta=gpcr.id_venta AND gppc.id_cronograma=gpcr.correlativo) as tipo_moneda_reporte,
            (select format(gppc.importe_pago,2) from gp_pagos_cabecera gppc, configuracion_detalle cdx WHERE cdx.idconfig_detalle=gppc.moneda_pago AND gppc.esta_borrado='0' AND cdx.codigo_tabla='_TIPO_MONEDA' AND gppc.id_venta=gpcr.id_venta AND gppc.id_cronograma=gpcr.correlativo) as importe_pago_reporte,
            (select format(gppc.pagado,2) from gp_pagos_cabecera gppc, configuracion_detalle cdx WHERE cdx.idconfig_detalle='15381' AND gppc.esta_borrado='0' AND cdx.codigo_tabla='_TIPO_MONEDA' AND gppc.id_venta=gpcr.id_venta AND gppc.id_cronograma=gpcr.correlativo) as pagado_reporte,
            
            format((SELECT if((SUM(gppd.monto_soles))>0, SUM(gppd.monto_soles),'0.00') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd WHERE gppc.esta_borrado='0' AND gppc.idpago=gppd.idpago AND gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpv.id_venta GROUP BY gppd.idpago),2) as soles,
            format((SELECT if((SUM(gppd.monto_dolares))>0, SUM(gppd.monto_dolares),'0.00') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd WHERE gppc.esta_borrado='0' AND gppc.idpago=gppd.idpago AND gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpv.id_venta GROUP BY gppd.idpago),2) as dolares,
            gpcr.estado as estado,
            cd.nombre_corto as descEstado,
            cd.texto1 as color,
            if((gpcr.fecha_vencimiento<'$fecha' && gpcr.estado='3'),concat('-',TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, '$fecha')),'0') as mora,
            (SELECT if((gppd.importe_pago)>0, gppd.nro_operacion,'Ninguno') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd WHERE gppc.esta_borrado='0' AND gppd.esta_borrado='0' AND gppc.idpago=gppd.idpago AND gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpv.id_venta GROUP BY gppd.idpago) as nro_operacion,
            
            (SELECT if((gppd.importe_pago)>0, GROUP_CONCAT(DISTINCT gppdc.serie,'-',gppdc.numero),'Ninguno') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd, gp_pagos_detalle_comprobante gppdc 
            WHERE gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpv.id_venta AND gppc.idpago=gppd.idpago AND gppd.idpago_detalle=gppdc.idpago_detalle AND gppd.esta_borrado='0' AND gppdc.esta_borrado='0') as boleta,
			
			gpcr.pago_cubierto as pago_cubierto,
			(select texto1 from configuracion_detalle where codigo_tabla='_ESTADO_EC' and codigo_item='1') as color_pendiente,
			(select nombre_corto from configuracion_detalle where codigo_tabla='_ESTADO_EC' and codigo_item='1') as nom_pendiente,
			gpcr.id_venta as idventa,
			gpcr.correlativo as correlativo
            FROM gp_cronograma gpcr
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcr.estado AND cd.codigo_tabla='_ESTADO_EC'
            WHERE gpcr.esta_borrado=0
            AND gpcr.id_venta='$idventa'
			$query_estado
			ORDER BY gpcr.correlativo, gpcr.fecha_vencimiento ASC
			"); 

	 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //variables
            $idventa = $row['idventa'];
            $correlativo = $row['correlativo'];
            
            //consultar boletas
            $consultar_boletas = mysqli_query($conection, "SELECT if((gppd.importe_pago)>0, GROUP_CONCAT(DISTINCT gppdc.serie,'-', CAST(gppdc.numero as int) SEPARATOR ' / '),'Ninguno') as boleta 
            FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd, gp_pagos_detalle_comprobante gppdc 
            WHERE gppdc.idpago_detalle=gppd.idpago_detalle AND gppc.esta_borrado='0' AND gppdc.esta_borrado='0' AND gppd.esta_borrado='0' AND gppc.idpago=gppd.idpago AND gppc.id_cronograma='$correlativo' AND gppc.id_venta='$idventa' 
            GROUP BY gppd.idpago");
            $respuesta = mysqli_fetch_assoc($consultar_boletas);
            $boletas = $respuesta['boleta'];
            
            /*if($boletas=="Ninguno")
            {
                $boletas=$row['boleta'];
            }*/
            
            $nro_operacion = "";
            $consultar_nro_operacion = mysqli_query($conection, "SELECT if((gppd.nro_operacion)!='', GROUP_CONCAT(DISTINCT CAST(gppd.nro_operacion as char) SEPARATOR ' / '),'Ninguno') as nro_operacion 
			FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd, gp_pagos_detalle_comprobante gppdc 
			WHERE gppdc.idpago_detalle=gppd.idpago_detalle AND gppc.esta_borrado='0' AND gppdc.esta_borrado='0' AND gppd.esta_borrado='0' AND gppc.idpago=gppd.idpago AND gppc.id_cronograma='$correlativo' AND gppc.id_venta='$idventa' 
			GROUP BY gppd.idpago;");
			if($consultar_nro_operacion->num_rows > 0){
				$respuesta = mysqli_fetch_assoc($consultar_nro_operacion);
				$nro_operacion = $respuesta['nro_operacion'];
			}
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'fecha' => $row['fecha'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
                'fecha_pago' => $row['fecha_pago'],
                'importe_pago' => $row['importe_pago'],
                'pagado' => $row['pagado'],
                'tipo_cambio' => $row['tipo_cambio'],
				'estado' => $row['estado'],
				'descEstado' => $row['descEstado'],
				'color' => $row['color'],
				'mora' => $row['mora'],
				'nro_operacion' => $nro_operacion,
				'nro_boleta' => $boletas,
				'pago_cubierto' => $row['pago_cubierto'],
				'color_pendiente' => $row['color_pendiente'],
				'nom_pendiente' => $row['nom_pendiente'],
				'tipo_moneda_reporte' => $row['tipo_moneda_reporte'],
				'importe_pago_reporte' => $row['importe_pago_reporte'],
                'pagado_reporte' => $row['pagado_reporte']
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
        $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}



if(isset($_POST['CargarDatosHR'])){

		$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
		$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
		
		$bxFiltroLoteHR = isset($_POST['bxFiltroLoteHR']) ? $_POST['bxFiltroLoteHR'] : Null;
		$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
		
		$cbxPropiedadesCliente = isset($_POST['cbxPropiedadesCliente']) ? $_POST['cbxPropiedadesCliente'] : Null;
	    $cbxPropiedadesClienter = trim($cbxPropiedadesCliente);
		
		$query_documento = "";
		$query_lote	  = "";
		
		$query_venta  = "";
		 
		
		if(!empty($bxFiltroLoteHRr)){
			$query_lote	  = "AND gpv.id_lote='$bxFiltroLoteHRr'";
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr'");
			$conteo_idventa = mysqli_num_rows($consulta_idventa);
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    if($conteo_idventa>1){
		        $query_venta  = "AND gpv.id_venta='$idventa' AND gpv.devolucion='0'";
		    }else{
		        $query_venta  = "AND gpv.id_venta='$idventa'";
		    }
		    
		    
		}
				
		if(!empty($txtFiltroDocumentoHRr)){
			$query_documento = "AND dc.documento='$txtFiltroDocumentoHRr'"; 
			
			$consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$txtFiltroDocumentoHRr'");
			$respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
			$idcliente=$respuesta_idcliente['id'];
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_cliente='$idcliente'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
			$conteo_idventa = mysqli_num_rows($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    if($conteo_idventa>1){
		        $query_venta  = "AND gpv.id_venta='$idventa' AND gpv.devolucion='0'";
		    }else{
		        $query_venta  = "AND gpv.id_venta='$idventa'";
		    }
			
		}
		
		if(!empty($bxFiltroLoteHRr) && !empty($txtFiltroDocumentoHRr)){
		    
		    $consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$txtFiltroDocumentoHRr'");
			$respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
			$idcliente=$respuesta_idcliente['id'];
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteHRr' AND id_cliente='$idcliente'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    
		    $query_venta  = "AND gpv.id_venta='$idventa'";
		}
		
		
		if(empty($bxFiltroLoteHRr) && empty($txtFiltroDocumentoHRr)){
		    $query_documento = "AND dc.documento='xxxxxx'";
		}


        if(!empty($cbxPropiedadesClienter)){
    	    $query_venta = "AND gpv.id_venta='$cbxPropiedadesClienter'";
    	}

		$query = mysqli_query($conection,"SELECT 
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as dato,
            concat(dc.celular_1,' - ',dc.email) as contacto,
            format(gpv.total,2) as precio_pactado,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            format((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0' AND es_cuota_inicial='1'),2) as importe_inicial,
            format((select sum(round(monto_letra,2)) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0'),2) as importe_financiado,
            cd.nombre_corto as tipo_casa,
            format((gpv.total - gpv.monto_cuota_inicial),2) as saldo_inicial,
            
            format(ROUND((SELECT (SUM(gppd.pagado) + if((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1')>0,(select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1'),0)) FROM gp_pagos_cabecera pagoc, gp_pagos_detalle gppd, gp_cronograma gpcr 
            WHERE pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagoc.idpago=gppd.idpago AND gppd.esta_borrado='0' AND gpcr.estado=2 AND pagoc.esta_borrado='0' AND gpcr.esta_borrado='0'),2),2) as monto_pagado,
            
            date_format(gpv.fecha_entrega_casa, '%d/%m/%Y') as fecha_entrega,
            format(((select sum(round(monto_letra,2)) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0') - (gpv.total)),2) as interes,
            
            ((select sum(gpcr.monto_letra) as total from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND dscto_acuerdo='0' AND gpcr.esta_borrado='0')-(select sum(gppd.pagado) as total from gp_pagos_cabecera gppc INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago AND gppd.esta_borrado='0' WHERE gppc.id_venta=gpv.id_venta AND gppc.esta_borrado='0')) as monto_pendiente,
            
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=2) as cont_pagadas,
            format((SELECT (SUM(pagod.pagado) + if((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1')>0,(select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1'),0)) FROM gp_pagos_detalle pagod, gp_pagos_cabecera pagoc, gp_cronograma gpcr 
            WHERE pagod.idpago=pagoc.idpago AND pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagod.id_venta=gpv.id_venta AND gpcr.estado=2 AND pagod.esta_borrado='0' AND pagoc.esta_borrado='0' AND gpcr.esta_borrado='0'),2) as letras_pagadas,			
            
            if(gpv.cancelado=1,'0',(SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=3 AND gpcr.esta_borrado='0')) as cont_vencidas,
            if(gpv.cancelado=1,'0.00',format((SELECT if(SUM(gpcr.monto_letra)>0,SUM(gpcr.monto_letra),0) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=3 AND gpcr.esta_borrado='0'),2)) as letras_vencidas,
            
            
            if(gpv.cancelado=1,'0',(SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=1 AND gpcr.esta_borrado='0')) AS cont_pendientes,
            if(gpv.cancelado=1,'0.00',format((SELECT if(SUM(gpcr.monto_letra)>0,SUM(gpcr.monto_letra),0) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=1 AND dscto_acuerdo='0' AND gpcr.esta_borrado='0'),2)) as letras_pendientes,
            
            gpv.cancelado as cancelado,
            gpv.devolucion as devolucion,
            gpv.estado_devolucion as estado_devolucion
            FROM datos_cliente dc
            INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_Casa AND cd.codigo_tabla='_TIPO_CASA'
            WHERE dc.esta_borrado=0
            $query_venta
			$query_documento
			$query_lote
			GROUP BY dc.id
			"); 
		$row = mysqli_fetch_assoc($query);

            $query2 = mysqli_query($conection,"SELECT 
            sum(monto_letra) as total,
            (select sum(pagado) from gp_pagos_cabecera where id_venta=gpv.id_venta AND esta_borrado='0') as pagado
            FROM gp_cronograma gpcr
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
            INNER JOIN datos_cliente AS dc ON gpv.id_cliente=dc.id
            INNER JOIN gp_pagos_cabecera AS gppc ON (gppc.id_venta=gpv.id_venta OR gppc.id_venta IS NULL)
            INNER JOIN gp_pagos_detalle AS gppd ON (gppd.idpago=gppc.idpago OR gppd.idpago IS NULL)
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_Casa AND cd.codigo_tabla='_TIPO_CASA'
            WHERE dc.esta_borrado=0
            $query_venta
			$query_documento
			$query_lote
			GROUP BY dc.id
			"); 
			$respuesta = mysqli_fetch_assoc($query2);
            $total_letras = $respuesta['total'];
            $total_pagado = $respuesta['pagado'];
            $saldo = $total_letras - $total_pagado;


		 
    if($query->num_rows > 0){
		            
        $data['data'] = $dataList;
	    $data['dato'] = $row['dato'];
		$data['contacto'] = $row['contacto'];
		$data['precio_pactado'] = $row['precio_pactado'];
		$data['lote'] = $row['lote'];
		
		if($row['importe_inicial']>0){$val_import_ini=$row['importe_inicial'];}else{$val_import_ini='0.00';}
		$data['importe_inicial'] = $val_import_ini;
		
		if($row['importe_financiado']>0){$val_import_finan=$row['importe_financiado'];}else{$val_import_finan='0.00';}
		$data['importe_financiado'] = $val_import_finan;
		
		$data['tipo_casa'] = $row['tipo_casa'];
		$data['saldo_inicial'] = $row['saldo_inicial'];
		
		if($row['monto_pagado']>0){$val_monto_pagado=$row['monto_pagado'];}else{$val_monto_pagado='0.00';}
		$data['monto_pagado'] = $val_monto_pagado;
		
		$data['fecha_entrega'] = $row['fecha_entrega'];
		
		if($row['interes']>0){$val_interes=$row['interes'];}else{$val_interes='0.00';}
		$data['interes'] = $val_interes;
		
		$val_mont_pend = $row['monto_pendiente'];
		if($val_mont_pend<1){
		    $val_mont_pend = '0.00';
		}
		
		$data['monto_pendiente'] = number_format($val_mont_pend,2);
		$data['cont_pagadas'] = $row['cont_pagadas'];
		
		if($row['letras_pagadas']>0){$val_letras_pagadas=$row['letras_pagadas'];}else{$val_letras_pagadas='0.00';}
		$data['letras_pagadas'] = $val_letras_pagadas;
		
		$data['cont_vencidas'] = $row['cont_vencidas'];
		
		if($row['letras_vencidas']>0){$val_letras_vencidas=$row['letras_vencidas'];}else{$val_letras_vencidas='0.00';}
		$data['letras_vencidas'] = $val_letras_vencidas;
		
		$data['cont_pendientes'] = $row['cont_pendientes'];
		
		if($row['letras_pendientes']>0){$val_letras_pendientes=$row['letras_pendientes'];}else{$val_letras_pendientes='0.00';}
		$data['letras_pendientes'] = $val_letras_pendientes;
		
		$data['cancelado'] = $row['cancelado'];
		$data['devolucion'] = $row['devolucion'];
		$data['devolucion_estado'] = $row['estado_devolucion'];
		
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}



if(isset($_POST['parametros'])){

		$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
		$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
		
		$bxFiltroLoteHR = isset($_POST['bxFiltroLoteHR']) ? $_POST['bxFiltroLoteHR'] : Null;
		$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
		
		$cbxPropiedadesCliente = isset($_POST['cbxPropiedadesCliente']) ? $_POST['cbxPropiedadesCliente'] : Null;
	    $cbxPropiedadesClienter = trim($cbxPropiedadesCliente);
		
		$dato_doc="";
		$dato_dni=0;
		$id_venta = "";
		
		if(!empty($cbxPropiedadesClienter)){
    	    $id_venta = encrypt($cbxPropiedadesClienter,"123");
    	}
		
		if(!empty($txtFiltroDocumentoHRr)){
		    
		    $consulta_docu = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE documento='$txtFiltroDocumentoHRr'");
		    $resp = mysqli_num_rows($consulta_docu);
		    if($resp>0){
    		    $dato_doc = $txtFiltroDocumentoHRr;
    		    $dato_dni=1;
		    }else{
		        $dato_dni=0;
		    }
		}else{
		    if(!empty($bxFiltroLoteHRr)){
    		    $consultar_dni = mysqli_query($conection, "SELECT
    		    dc.documento as dni
    		    FROM gp_lote gpl
    		    INNER JOIN gp_venta as gpv ON gpv.id_lote=gpl.idlote
    		    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    		    WHERE gpl.idlote='$bxFiltroLoteHRr'");
    		    $respuesta = mysqli_fetch_assoc($consultar_dni);
    		    $dato_doc = $respuesta['dni'];
    		    $dato_dni=1;
		    }else{
		        $dato_dni=0;
		    }
		    
		}
        
        if($dato_dni>0){
            
            $data['status']="ok";
            $data['param'] = $dato_doc;
            $data['idlote'] = $bxFiltroLoteHRr;
            $data['idventa'] = $id_venta;
            
        }else{
            $data['status']="bad";
        }
	     
		

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}



if(isset($_POST['parametros2'])){

		$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoHR']) ? $_POST['txtFiltroDocumentoHR'] : Null;
		$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
		
		$bxFiltroLoteHR = isset($_POST['bxFiltroLoteHR']) ? $_POST['bxFiltroLoteHR'] : Null;
		$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
		
		$cbxPropiedadesCliente = isset($_POST['cbxPropiedadesCliente']) ? $_POST['cbxPropiedadesCliente'] : Null;
	    $cbxPropiedadesClienter = trim($cbxPropiedadesCliente);
		
		$dato_doc="";
		$dato_dni=0;
		$id_venta = "";
		if(!empty($cbxPropiedadesClienter)){
    	    $id_venta = encrypt($cbxPropiedadesClienter,"123");
    	}
		
		
		if(!empty($txtFiltroDocumentoHRr)){
		    
		    $consulta_docu = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE documento='$txtFiltroDocumentoHRr'");
		    $resp = mysqli_num_rows($consulta_docu);
		    if($resp>0){
    		    $dato_doc = $txtFiltroDocumentoHRr;
    		    $dato_dni=1;
		    }else{
		        $dato_dni=0;
		    }
		}else{
		    if(!empty($bxFiltroLoteHRr)){
    		    $consultar_dni = mysqli_query($conection, "SELECT
    		    dc.documento as dni
    		    FROM gp_lote gpl
    		    INNER JOIN gp_venta as gpv ON gpv.id_lote=gpl.idlote
    		    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    		    WHERE gpl.idlote='$bxFiltroLoteHRr'");
    		    $respuesta = mysqli_fetch_assoc($consultar_dni);
    		    $dato_doc = $respuesta['dni'];
    		    $dato_dni=1;
		    }else{
		        $dato_dni=0;
		    }
		    
		}
        
        if($dato_dni>0){
            
            $data['status']="ok";
            $data['param'] = $dato_doc;
            $data['idlote'] = $bxFiltroLoteHRr;
            $data['idventa'] = $id_venta;
            
        }else{
            $data['status']="bad";
        }
	     
		

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}
