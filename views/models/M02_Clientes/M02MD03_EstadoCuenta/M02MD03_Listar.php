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

if(isset($_POST['ReturnListaEstadoCuenta'])){

	$txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoEC']) ? $_POST['txtFiltroDocumentoEC'] : Null;
	$txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
	
	$bxFiltroLoteEC = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
	$bxFiltroLoteECr = trim($bxFiltroLoteEC);
	
	$bxFiltroEstadoEC = isset($_POST['bxFiltroEstadoEC']) ? $_POST['bxFiltroEstadoEC'] : Null;
	$bxFiltroEstadoECr = trim($bxFiltroEstadoEC);
	
	$query_documento = "";
	$query_lote	= "";
	$query_estado = "";
	$query_venta = "";
	
	$query_venta = "AND gpcr.id_venta='0";
	
	if(!empty($bxFiltroEstadoECr)){
		$query_estado = "AND gpcr.estado='$bxFiltroEstadoECr'";
	}
	
	if(empty($txtFiltroDocumentoECr)){
		if(empty($bxFiltroLoteECr)){
			$query_documento = "";
			$query_lote	  = "AND gpv.id_lote='0'";
		}else{			
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteECr'";
			$query_documento = "";
			
			$consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteECr' AND esta_borrado='0'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    $query_venta = "AND gpcr.id_venta='$idventa'";
		}
	}else{		
		if(empty($bxFiltroLoteECr)){
			$query_lote	  = "";
			$query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id_venta FROM gp_venta gpv, datos_cliente dc WHERE gpv.id_cliente=dc.id AND dc.documento='$txtFiltroDocumentoECr' AND gpv.esta_borrado='0'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    $query_venta = "AND gpcr.id_venta='$idventa'";
		    
		}else{
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteECr'"; 
			$query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
			
			$consulta_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id_venta FROM gp_venta gpv INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente WHERE gpv.id_lote='$bxFiltroLoteECr' AND dc.documento='$txtFiltroDocumentoECr' AND gpv.esta_borrado='0'");
			$respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
		    $idventa = $respuesta_idventa['id_venta'];
		    $query_venta = "AND gpcr.id_venta='$idventa'";
		}
	} 

		$query = mysqli_query($conection,"SELECT
            date_format(gpcr.fecha_vencimiento, '%d/%m/%Y') as fecha,
            gpcr.item_letra as letra,
            format(gpcr.monto_letra,2) as monto,
            (select date_format(fecha_pago, '%d/%m/%Y') from gp_pagos_cabecera WHERE id_venta=gpcr.id_venta AND id_cronograma=gpcr.correlativo AND esta_borrado='0') as fecha_pago,
            
            (select concat(cdx.texto1,' - ',format(SUM(gppd.pagado),2)) from gp_pagos_cabecera gppc, gp_pagos_detalle gppd, configuracion_detalle cdx WHERE gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta AND gppd.esta_borrado='0' AND gppc.esta_borrado='0' AND cdx.idconfig_detalle='15381' AND cdx.codigo_tabla='_TIPO_MONEDA' AND gppc.id_venta=gpcr.id_venta AND gppc.id_cronograma=gpcr.correlativo) as pagado,
            
            gpcr.estado as estado,
            cd.nombre_corto as descEstado,
            cd.texto1 as color,
            gpcr.id_venta as idventa,
            gpcr.correlativo as correlativo,
            if((gpcr.fecha_vencimiento<'$fecha' && gpcr.estado='3'),concat('-',TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, '$fecha')),'0') as mora,
            (SELECT if((gppd.importe_pago)>0, gppd.nro_operacion,'Ninguno') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd WHERE gppc.idpago=gppd.idpago AND gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpv.id_venta AND gppd.esta_borrado='0' AND gppc.esta_borrado='0' GROUP BY gppd.idpago) as nro_operacion
            FROM gp_cronograma gpcr
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcr.estado AND cd.codigo_tabla='_ESTADO_EC'
            WHERE gpcr.esta_borrado=0
            $query_venta
			$query_estado
			ORDER BY gpcr.correlativo ASC"); 

	 
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
            if($consultar_boletas->num_rows>0){
                $respuesta = mysqli_fetch_assoc($consultar_boletas);
                $boletas = $respuesta['boleta'];
            }
            
             //consultar nro operacion
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
                'pagado' => $row['pagado'],
				'estado' => $row['estado'],
				'descEstado' => $row['descEstado'],
				'color' => $row['color'],
				'mora' => $row['mora'],
				'nro_operacion' => $nro_operacion,
				'nro_boleta' => $boletas
				
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

if(isset($_POST['CargarDatosECc'])){

		$txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoEC']) ? $_POST['txtFiltroDocumentoEC'] : Null;
		$txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
		
		$bxFiltroLoteEC = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
		$bxFiltroLoteECr = trim($bxFiltroLoteEC);
		
		$query_documento = "";
		$query_lote	  = "";
		 
		if(empty($txtFiltroDocumentoECr)){
			if(empty($bxFiltroLoteECr)){
				$query_documento = "";
				$query_lote	  = "AND gpv.id_lote='0'";
			}else{			
				$query_lote = "AND gpv.id_lote='$bxFiltroLoteECr'";
				$query_documento = "";
			}
		}else{		
			if(empty($bxFiltroLoteECr)){
				$query_lote	  = "";
				$query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
			}else{
				$query_lote = "AND gpv.id_lote='$bxFiltroLoteECr'"; 
				$query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
			}
		}


		$query = mysqli_query($conection,"SELECT 
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as dato,
            concat(dc.celular_1,' - ',dc.email) as contacto,
            dc.celular_1 as celular,
            dc.email as correo,
            format(gpv.total,2) as precio_pactado,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            format(gpv.monto_cuota_inicial,2) as importe_inicial,
            format((select sum(round(monto_letra,2)) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0'),2) as importe_financiado,
            if((gpv.cantidad_letra/12)>0, concat(format((gpv.cantidad_letra/12),0),'AÑOS (',gpv.cantidad_letra,' MESES)'), concat(gpv.cantidad_letra,' MESES')) as financiamiento,
            cd.nombre_corto as tipo_casa,
            format((gpv.total - gpv.monto_cuota_inicial),2) as saldo_inicial,
            
            format(ROUND((SELECT (SUM(gppd.pagado) + if((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1')>0,(select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1'),0)) FROM gp_pagos_cabecera pagoc, gp_pagos_detalle gppd, gp_cronograma gpcr 
            WHERE pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagoc.idpago=gppd.idpago AND gppd.esta_borrado='0' AND gpcr.estado=2 AND pagoc.esta_borrado='0' AND gpcr.esta_borrado='0'),2),2) as monto_pagado,
            
            date_format(gpv.fecha_entrega_casa, '%d/%m/%Y') as fecha_entrega,
            format(((select sum(round(monto_letra,2)) from gp_cronograma where id_venta=gpv.id_venta AND esta_borrado='0') - (gpv.total)),2) as interes,
            ((select sum(gpcr.monto_letra) as total from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.esta_borrado='0')-(select sum(pagado) as total from gp_pagos_cabecera WHERE id_venta=gpv.id_venta AND esta_borrado='0')) as monto_pendiente,
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=2) as cont_pagadas,
            format((SELECT SUM(pagod.pagado) FROM gp_pagos_detalle pagod, gp_pagos_cabecera pagoc, gp_cronograma gpcr 
            WHERE pagod.idpago=pagoc.idpago AND pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagod.id_venta=gpv.id_venta AND gpcr.estado=2 AND gpcr.esta_borrado='0'),2) as letras_pagadas,			
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=3 AND gpcr.esta_borrado='0') as cont_vencidas,
            format((SELECT if(SUM(gpcr.monto_letra)>0,SUM(gpcr.monto_letra),0) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=3 AND gpcr.esta_borrado='0'),2) as letras_vencidas,
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado IN (1,3) AND gpcr.esta_borrado='0') AS cont_pendientes,
            format(((select sum(gpcr.monto_letra) as total from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.dscto_acuerdo='0' AND gpcr.esta_borrado='0')-(select sum(pagado) as total from gp_pagos_detalle WHERE id_venta=gpv.id_venta AND esta_borrado='0')),2) as letras_pendientes,
            gpv.cancelado as cancelado,
            gpv.devolucion as devolucion
            FROM datos_cliente dc
            INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
            INNER JOIN gp_pagos_cabecera AS gppc ON (gppc.id_venta=gpv.id_venta OR gppc.id_venta IS NULL)
            INNER JOIN gp_pagos_detalle AS gppd ON (gppd.idpago=gppc.idpago OR gppd.idpago IS NULL)
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_Casa AND cd.codigo_tabla='_TIPO_CASA'
            WHERE dc.esta_borrado=0
			$query_documento
			$query_lote
			GROUP BY dc.id
			"); 
		$row = mysqli_fetch_assoc($query);

		
	
	    if($query->num_rows > 0){
			            
	        $data['validar'] = "ok";	
		    $data['dato'] = $row['dato'];
			$data['ubicacion'] = $row['lote'];
			$data['precio_venta'] = $row['precio_pactado'];
			$data['intereses'] = $row['interes'];
			$data['precio_total'] = $row['importe_financiado'];
			$data['capital_vivo'] = $row['letras_pendientes'];
			$data['financiamiento'] = $row['financiamiento'];
			$data['monto_pagado'] = $row['monto_pagado'];
			$data['correo'] = $row['correo'];
			$data['telefono'] = $row['celular'];
			$data['fecha_entrega'] = $row['fecha_entrega'];
			$data['cancelado'] = $row['cancelado'];
		    $data['devolucion'] = $row['devolucion'];

	        header('Content-type: text/javascript');
	        echo json_encode($data,JSON_PRETTY_PRINT) ;
		
    }else{       
          	$data['validar'] = "bad";
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}

if(isset($_POST['parametros'])){

		$txtFiltroDocumentoHR = isset($_POST['txtFiltroDocumentoEC']) ? $_POST['txtFiltroDocumentoEC'] : Null;
		$txtFiltroDocumentoHRr = trim($txtFiltroDocumentoHR);
		
		$bxFiltroLoteHR = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
		$bxFiltroLoteHRr = trim($bxFiltroLoteHR);
		$dato_doc="";
		$dato_lote="";
		$dato_dni=0;
		
		if(!empty($txtFiltroDocumentoHRr)){
		    
		    $consulta_docu = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE documento='$txtFiltroDocumentoHRr'");
		    $resp = mysqli_num_rows($consulta_docu);
		    $respuesta = mysqli_fetch_assoc($consulta_docu);
		    if($resp>0){    		    
    		    $dato_dni=1;
    		    $id_cliente = $respuesta['id'];
    		    $consultar_idlote = mysqli_query($conection, "SELECT id_lote as id FROM gp_venta WHERE id_cliente='$id_cliente'");
    		    $id_lote = mysqli_fetch_assoc($consultar_idlote);
    		    $valor_idlote = $id_lote['id'];
    		    $dato_lote= encrypt($valor_idlote,"123");
		    }else{
		        $dato_dni=0;
		    }
		}else{
		    if(!empty($bxFiltroLoteHRr)){    		   
    		    $dato_lote = encrypt($bxFiltroLoteHRr,"123");
    		    $dato_dni=1;
		    }else{
		        $dato_dni=0;
		    }
		    
		}
		
		$dato_doc = encrypt($txtFiltroDocumentoHRr, "123");
        
        if($dato_dni>0){
            
            $data['status']="ok";
            $data['param_lote'] = $dato_lote;
            $data['param_doc'] = $dato_doc;
            $data['idlote'] = $bxFiltroLoteHRr;
            
        }else{
            $data['status']="bad";
        }
	     
		

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
}


if(isset($_POST['VerificarPerfil'])){

        $txtUSR = isset($_POST['txtUSR']) ? $_POST['txtUSR'] : Null;
        $txtUSRr = trim($txtUSR);

        $usuar = decrypt($txtUSRr, "123");

        $consultar_lotes = mysqli_query($conection, "SELECT 
        	gpv.id_venta
        	FROM gp_venta gpv
        	INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        	WHERE dc.documento='$usuar'");
        $contar_lotes = mysqli_num_rows($consultar_lotes);

		$new_idlote="";

        if($contar_lotes==1){
	        $consultar_idlote = mysqli_query($conection, "SELECT 
	        	gpv.id_lote as id
	        	FROM gp_venta gpv
	        	INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
	        	WHERE dc.documento='$usuar'");
	        $respuesta_idlote = mysqli_fetch_assoc($consultar_idlote);
	        $id_lote = $respuesta_idlote['id'];
	        $new_idlote = encrypt($id_lote,"123");
	    }

        $consultar_idusuarioo = mysqli_query($conection, "SELECT idperfil as perfil  FROM usuario WHERE usuario='$usuar'");
        $respuesta_idusuarioo = mysqli_fetch_assoc($consultar_idusuarioo);
        $idperfil2 = $respuesta_idusuarioo['perfil'];

    if($consultar_idusuarioo->num_rows > 0){
                    
        $data['perfil'] = $idperfil2;
        $data['documento'] = $usuar;
        $data['num_lotes'] = $contar_lotes;
        $data['idlote'] = $new_idlote;

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}


if (isset($_POST['ListarLotesAdquiridos'])) {

    $documento = $_POST['documento'];
    $query = mysqli_query($conection, "SELECT 
    	 gpl.idlote as valor,
         concat(gpl.nombre, ' - ',gpm.nombre,' - ',gpz.nombre) as texto
         FROM datos_cliente dc
         INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
         INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
         INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
         INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
         WHERE dc.documento='$documento'");

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


if(isset($_POST['DesencriptarLote'])){

        $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : Null;
        $codigor = trim($codigo);

        $idlote = decrypt($codigor, "123");            
                    
        $data['idlote'] = $idlote;

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
}
