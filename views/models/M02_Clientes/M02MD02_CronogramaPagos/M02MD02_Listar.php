<?php
   session_start();
   date_default_timezone_set("America/Lima");
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');
   $data = array();
   $dataList = array();

if(isset($_POST['ReturnListaCronogramaPagos'])){

	$txtFiltroDocumentoCP = isset($_POST['txtFiltroDocumentoCP']) ? $_POST['txtFiltroDocumentoCP'] : Null;
	$txtFiltroDocumentoCPr = trim($txtFiltroDocumentoCP);
	
	$bxFiltroLoteCP = isset($_POST['bxFiltroLoteCP']) ? $_POST['bxFiltroLoteCP'] : Null;
	$bxFiltroLoteCPr = trim($bxFiltroLoteCP);
	
	$bxFiltroEstadoCP = isset($_POST['bxFiltroEstadoCP']) ? $_POST['bxFiltroEstadoCP'] : Null;
	$bxFiltroEstadoCPr = trim($bxFiltroEstadoCP);


	$txtUSR = decrypt($_POST['txtUSR'],"123");

	//CONSULTAR PERMISOS DE USUARIO
	$idperfil="";
	$consultar_permisos = mysqli_query($conection, "SELECT u.idPerfil as id FROM usuario u WHERE u.usuario='$txtUSR'");
	$respuesta_permisos = mysqli_fetch_assoc($consultar_permisos);
	$idperfil = $respuesta_permisos['id'];
	$val_perfil = "ninguno";
	if($idperfil == 9 || $idperfil == 1 || $idperfil == 8){
		$val_perfil = "ok";
	}else{
		$val_perfil = "bad";
	}
	
	$query_documento = "";
	$query_lote	= "";
	$query_estado = "";
	
	if(!empty($bxFiltroEstadoCPr)){
		$query_estado = "AND gpcp.estado='$bxFiltroEstadoCPr'";
	}
	
	if(empty($txtFiltroDocumentoCPr)){
		if(empty($bxFiltroLoteCPr)){
			$query_documento = "";
			$query_lote	  = "AND gpv.id_lote='0'";
		}else{			
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteCPr'";
			$query_documento = "";
		}
	}else{		
		if(empty($bxFiltroLoteCPr)){
			$query_lote	  = "";
			$query_documento = "AND dc.documento='$txtFiltroDocumentoCPr'"; 
		}else{
			$query_lote = "AND gpv.id_lote='$bxFiltroLoteCPr'"; 
			$query_documento = "AND dc.documento='$txtFiltroDocumentoCPr'"; 
		}
	} 
	

	$query = mysqli_query($conection,"SELECT 
	gpcp.id as id,
	date_format(gpcp.fecha_vencimiento, '%d/%m/%Y') as fecha,
	gpcp.item_letra as letra,
	format(gpcp.monto_letra,2) as monto,
	format(gpcp.interes_amortizado,2) as intereses,
	format(gpcp.capital_amortizado,2) as amortizacion,
	format(gpcp.capital_vivo,2) as capital_vivo,
	format(gpcp.monto_letra,2) as pagado,
	gpcp.estado as estado,
	cd.nombre_corto as descEstado,
	cd.texto1 as color,
	gpcp.pago_cubierto as pago_cubierto
	FROM gp_cronograma gpcp
	INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcp.estado AND cd.codigo_tabla='_ESTADO_EC'
	INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcp.id_venta
	INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
	WHERE gpcp.esta_borrado=0
	$query_documento
	$query_lote
	$query_estado
	ORDER BY gpcp.correlativo ASC
	"); 


	$idventa = "";
	if(!empty($txtFiltroDocumentoCPr)){
		if(!empty($bxFiltroLoteCPr)){
			//consultar idventa
			$consultar_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id FROM gp_venta gpv INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente WHERE dc.documento='$txtFiltroDocumentoCPr' AND gpv.id_lote='$bxFiltroLoteCPr'");
			$respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
			$idventa = $respuesta_idventa['id'];
		}else{
			//consultar idventa
			$consultar_idventa = mysqli_query($conection, "SELECT MAX(gpv.id_venta) as id FROM gp_venta gpv INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente WHERE dc.documento='$txtFiltroDocumentoCPr'");
			$respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
			$idventa = $respuesta_idventa['id'];
		}
	}else{
		if(!empty($bxFiltroLoteCPr)){
			//consultar idventa
			$consultar_idventa = mysqli_query($conection, "SELECT MAX(gpv.id_venta) as id FROM gp_venta gpv INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente WHERE gpv.id_lote='$bxFiltroLoteCPr'");
			$respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
			$idventa = $respuesta_idventa['id'];
		}
	}
		
    if($query->num_rows > 0){
        
        //Consultar Datos Cliente
        if(!empty($txtFiltroDocumentoCPr)){
            $consultar_cliente = mysqli_query($conection, "SELECT concat(apellido_paterno,' ',apellido_materno,' ',nombres) as cliente FROM datos_cliente WHERE documento='$txtFiltroDocumentoCPr'");
            $respuesta_cliente = mysqli_fetch_assoc($consultar_cliente);
            $cliente = $respuesta_cliente['cliente'];
        }else{
            if(!empty($bxFiltroLoteCPr)){
                $consultar_cliente = mysqli_query($conection, "SELECT concat(cli.apellido_paterno,' ',cli.apellido_materno,' ',cli.nombres) as cliente FROM datos_cliente INNER JOIN gp_venta as gpv ON gpv.id_cliente=cli.id WHERE gpv.id_lote='$bxFiltroLoteCPr'");
                $respuesta_cliente = mysqli_fetch_assoc($consultar_cliente);
                $cliente = $respuesta_cliente['cliente'];
            }
        }


		//CONSULTAR EL ID CRONOGRAMA MAX DE LAS LETRAS PAGADAS 100%
		$idcron = "";
		$consultar_idcron = mysqli_query($conection, "SELECT MAX(id) AS id FROM gp_cronograma WHERE id_venta='$idventa' AND estado='2'");
		$respuesta_idcron = mysqli_fetch_assoc($consultar_idcron);
		$idcron = $respuesta_idcron['id'];

        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
				'id' => $row['id'],
                'fecha' => $row['fecha'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
                'intereses' => $row['intereses'],
                'amortizacion' => $row['amortizacion'],
				'capital_vivo' => $row['capital_vivo'],
				'pagado' => $row['pagado'],
				'estado' => $row['estado'],
				'pago_cubierto' => $row['pago_cubierto'],
				'descEstado' => $row['descEstado'],
				'color' => $row['color'],
				'acceso' => $val_perfil,
				'idcron' => $idcron+1
            ]);
        }
            
		$data['data'] = $dataList;
		$data['cliente'] = $cliente;
		$data['idventa'] = $idventa;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
        $data['recordsTotal'] = 0;
		$data['recordsFiltered'] = 0;
		$data['data'] = $dataList;
		$data['idventa'] = $idventa;
		header('Content-type: text/javascript');
		echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}

if(isset($_POST['CargarDatosCP'])){

	
		$txtFiltroDocumentoCP = isset($_POST['txtFiltroDocumentoCP']) ? $_POST['txtFiltroDocumentoCP'] : Null;
		$txtFiltroDocumentoCPr = trim($txtFiltroDocumentoCP);
		
		$bxFiltroLoteCP = isset($_POST['bxFiltroLoteCP']) ? $_POST['bxFiltroLoteCP'] : Null;
		$bxFiltroLoteCPr = trim($bxFiltroLoteCP);
		
		
		$query_documento = "";
		$query_lote	  = "";
		
		
		if(!empty($bxFiltroLoteCPr)){
			$query_lote	  = "AND gpv.id_lote='$bxFiltroLoteCPr'";
		}
				
		if(!empty($txtFiltroDocumentoCPr)){
			$query_documento = "AND dc.documento='$txtFiltroDocumentoCPr'"; 
		}
		
		
		if(empty($bxFiltroLoteCPr) && empty($txtFiltroDocumentoCPr)){
		    $query_documento = "AND dc.documento='xxxxxx'";
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
            
            (select format(capital_vivo,2) from gp_cronograma cro where cro.correlativo=(select MAX(mx.correlativo) as max_id from gp_cronograma mx where mx.id_venta=gpv.id_venta and mx.estado='2' and mx.pago_cubierto='2') AND cro.id_venta=gpv.id_venta AND cro.estado='2' AND cro.pago_cubierto='2') as capital_vivo,
            
            ((select sum(gpcr.monto_letra) as total from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.esta_borrado='0')-(select sum(pagado) as total from gp_pagos_cabecera WHERE id_venta=gpv.id_venta AND esta_borrado='0')) as monto_pendiente,
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=2) as cont_pagadas,
            format((SELECT SUM(pagod.pagado) FROM gp_pagos_detalle pagod, gp_pagos_cabecera pagoc, gp_cronograma gpcr 
            WHERE pagod.idpago=pagoc.idpago AND pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagod.id_venta=gpv.id_venta AND gpcr.estado=2 AND gpcr.esta_borrado='0'),2) as letras_pagadas,			
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=3 AND gpcr.esta_borrado='0') as cont_vencidas,
            format((SELECT if(SUM(gpcr.monto_letra)>0,SUM(gpcr.monto_letra),0) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado=3 AND gpcr.esta_borrado='0'),2) as letras_vencidas,
            (SELECT COUNT(gpcr.id) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado IN (1,3) AND gpcr.esta_borrado='0') AS cont_pendientes,
            if(gpv.cancelado=1,'0.00',format((SELECT if(SUM(gpcr.monto_letra)>0,SUM(gpcr.monto_letra),0) FROM gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.estado in (1,3) AND dscto_acuerdo='0' AND gpcr.esta_borrado='0'),2)) as letras_pendientes,
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
		$data['capital_vivo'] = $row['capital_vivo'];
		$data['saldo_pendiente'] = $row['letras_pendientes'];
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
		$dato_docu="";
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
    		    $dato_doc= encrypt($valor_idlote,"123");
    		    $dato_docu= encrypt($txtFiltroDocumentoHRr,"123");
		    }else{
		        $dato_dni=0;
		    }
		}else{
		    if(!empty($bxFiltroLoteHRr)){    		   
    		    $dato_doc = encrypt($bxFiltroLoteHRr,"123");
    		    $dato_docu= encrypt($txtFiltroDocumentoHRr,"123");
    		    $dato_dni=1;
		    }else{
		        $dato_dni=0;
		    }
		    
		}
        
        if($dato_dni>0){
            
            $data['status']="ok";
            $data['param'] = $dato_doc;
            $data['param2'] = $dato_docu;
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

if (isset($_POST['ListarLetrasInicio'])) {

    $documento = $_POST['documento'];
    $query = mysqli_query($conection, "SELECT 
         gpcr.id as valor,
    	 gpcr.item_letra as texto
         FROM datos_cliente dc
         INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
         INNER JOIN gp_cronograma AS gpcr ON gpcr.id_venta=gpv.id_venta
         WHERE dc.documento='$documento' AND gpcr.estado!='2'
         ORDER BY gpcr.correlativo ASC");

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

if (isset($_POST['ListarLetrasUbicar'])) {

    $documento = $_POST['documento'];
	$lote = $_POST['lote'];

	array_push($dataList, [
        'valor' => '',
        'texto' => 'Última letra',
    ]);

	$query_lote = "";
	if(!empty($lote)){
		$query_lote = "AND gpv.id_lote='$lote'";
	}

    $query = mysqli_query($conection, "SELECT 
         gpcr.id as valor,
    	 if(gpcr.es_cuota_inicial=1,concat(gpcr.item_letra,' (', date_format(gpcr.fecha_vencimiento,'%d/%m/%Y') ,')'),gpcr.item_letra) as texto
         FROM datos_cliente dc
         INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
         INNER JOIN gp_cronograma AS gpcr ON gpcr.id_venta=gpv.id_venta
         WHERE dc.documento='$documento' $query_lote 
         ORDER BY gpcr.correlativo ASC");

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

if (isset($_POST['ListarLetrasFin'])) {

    $documento = $_POST['documento'];
    $letraInicio = $_POST['letraInicio'];
    
    //consultar correlativo
    $consultar_corre = mysqli_query($conection, "SELECT correlativo as corre FROM gp_cronograma WHERE id='$letraInicio'");
    $respuesta_corre = mysqli_fetch_assoc($consultar_corre);
    $correlativo = $respuesta_corre['corre'];
    
    $query = mysqli_query($conection, "SELECT 
         gpcr.id as valor,
    	 gpcr.item_letra as texto
         FROM datos_cliente dc
         INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
         INNER JOIN gp_cronograma AS gpcr ON gpcr.id_venta=gpv.id_venta
         WHERE dc.documento='$documento' AND gpcr.estado!='2' AND gpcr.correlativo>'$correlativo'
         ORDER BY gpcr.correlativo ASC");

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

if(isset($_POST['btnAgregarLetraCronograma'])){

	$cbxTipoLetraFormat = $_POST['cbxTipoLetraFormat'];
	$cbxUbicarLetraFormat = $_POST['cbxUbicarLetraFormat'];
	$txtFecVencimientoFormat = $_POST['txtFecVencimientoFormat'];
	$txtLetraFormatNumber = $_POST['txtLetraFormatNumber'];
	$txtLetraFormatText = $_POST['txtLetraFormatText'];
	$txtMontoLetraFormat = $_POST['txtMontoLetraFormat'];
	$txtInteresesFormat = $_POST['txtInteresesFormat'];
	$txtAmortizacionFormat = $_POST['txtAmortizacionFormat'];
	$txtCapitalVivoFormat = $_POST['txtCapitalVivoFormat'];
	$ID_VNTA = $_POST['ID_VNTA'];

	$txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	$actualiza = $fecha.' '.$hora;


	//VERIFICAR TIPO DE LETRA
	$letra = "";
	$es_inicial = "";
	if($cbxTipoLetraFormat == 2){
		$letra = $txtLetraFormatNumber;
		$es_inicial = "0";
	}else{
		$letra = $txtLetraFormatText;
		$es_inicial = "1";
	}


	if(!empty($ID_VNTA)){

		$idcorrelativo="";

		//CONSULTAR IDMAX + 1 CORRELATIVO
		$consultar_correlativo = mysqli_query($conection, "SELECT max(correlativo) + 1 as id FROM gp_cronograma WHERE id_venta='$ID_VNTA'");
		$respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
		$idcorrelativo = $respuesta_correlativo['id'];

		$ref_correlativo = "";
		$correlativo_ofic= "";
		if(!empty($cbxUbicarLetraFormat)){	

			//capturar correlativo del campo "ubicar debajo de"
			$consultar_dato = mysqli_query($conection, "SELECT correlativo as valor FROM gp_cronograma WHERE id='$cbxUbicarLetraFormat' AND id_venta='$ID_VNTA'");
			$respuesta_dato = mysqli_fetch_assoc($consultar_dato);
			$ref_correlativo = $respuesta_dato['valor'];

			//correlativo para nuevo registro
			$correlativo_ofic = $ref_correlativo + 1;

            if($cbxTipoLetraFormat == 2){
    			//actualizar letras de los registros posteriores
    			$actualizar_letras = mysqli_query($conection, "UPDATE gp_cronograma SET item_letra=item_letra+1, id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$ID_VNTA' AND correlativo>'$ref_correlativo' AND item_letra NOT IN ('ADENDA','AMORTIZADO','C.I.')");
            }
			//actualizar correlativos de los registros posteriores
			$actualizar_correlativos = mysqli_query($conection, "UPDATE gp_cronograma SET correlativo=correlativo+1, id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$ID_VNTA' AND correlativo>'$ref_correlativo'");

            //actualizar idcorrelativo en pagos
            $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET id_cronograma=id_cronograma+1 WHERE id_venta='$ID_VNTA' AND id_cronograma>'$ref_correlativo'");

			if($actualizar_correlativos){
				$idcorrelativo = $correlativo_ofic;	
			}
					
		}


		//INSERTAR
		$insertar = mysqli_query($conection, "INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_vivo, estado, es_cuota_inicial, pago_cubierto, id_usuario_crea, creado) VALUES ('$ID_VNTA','$letra','$idcorrelativo','$txtFecVencimientoFormat','$txtMontoLetraFormat','$txtInteresesFormat','$txtCapitalVivoFormat','1','$es_inicial','0','$idusuario','$actualiza')");

		if($insertar){
			$data['status'] = 'ok';
			$data['data'] = "Se registró correctamente la Letra.";
		}else{
			$data['status'] = 'bad';
			$data['data'] = "No se pudo completar la operación";
		}

	}else{
		$data['status'] = 'bad';
		$data['data'] = "No se pudo completar la operación";
	} 
		
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnModificarCronograma'])){

	$cbxLetraInicio = $_POST['cbxLetraInicio'];
	$cbxLetraFin = $_POST['cbxLetraFin'];
	$txtMondoLetra = $_POST['txtMondoLetra'];
	$txtTEALetra = $_POST['txtTEALetra'];
	$txtFechaInicio = $_POST['txtFechaInicio'];
	$txtDiasVencimiento = $_POST['txtDiasVencimiento'];

	$txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];

	$actualiza = $fecha.' '.$hora;
		
	if(!empty($cbxLetraInicio) && !empty($cbxLetraFin)){
	    if(!empty($txtMondoLetra) && empty($txtFechaInicio)){
	        if(!empty($txtTEALetra)){
	            
	            //DATOS
	            $consultar_datos = mysqli_query($conection, "SELECT id_venta as idventa,  correlativo as correlativo FROM gp_cronograma WHERE id='$cbxLetraInicio'");
	            $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
	            
	            $idventa = $respuesta_datos['idventa'];
	            $correlativo = $respuesta_datos['correlativo'];
	            
	            //consultar capital vivo
	            $correlativo_cv = $correlativo - 1;
	            $consultar_cv = mysqli_query($conection, "SELECT capital_vivo as capital FROM gp_cronograma WHERE id_venta='$idventa' AND correlativo='$correlativo_cv'");
	            $respuesta_cv = mysqli_fetch_assoc($consultar_cv);
	            
	            $capital_vivo = $respuesta_cv['capital'];
	            
	            $consultar_datos2 = mysqli_query($conection, "SELECT id_venta as idventa,  correlativo as correlativo FROM gp_cronograma WHERE id='$cbxLetraFin'");
	            $respuesta_datos2 = mysqli_fetch_assoc($consultar_datos2);
	            
	            $idventa2 = $respuesta_datos2['idventa'];
	            $correlativo2 = $respuesta_datos2['correlativo'];
	            
	            $txtTEALetra = ($txtTEALetra/100);
	            $valor_TEA = pow(( 1 + $txtTEALetra),(30/360)) - 1;
	            
	            //ACTUALIZAR MONTOS DE CRONOGRAMA
	            if($idventa==$idventa2){
	                
    	             //ACTUALIZAR
    	             for($i=$correlativo;$i<=$correlativo2;$i++){
    	                 
    	               $intereses = $capital_vivo * $valor_TEA;
                	   $amortizacion = $txtMondoLetra - $intereses;
                	   $capital_vivo = $capital_vivo - $amortizacion;
                	  
                	   $actualizar = mysqli_query($conection, "UPDATE gp_cronograma
                	   SET monto_letra='$txtMondoLetra', interes_amortizado='$intereses', capital_amortizado='$amortizacion', capital_vivo='$capital_vivo', id_usuario_actualiza='$idusuario', actualizado='$actualiza'
                	   WHERE id_venta='$idventa' AND correlativo='$i'");
    	                 
    	             }
    	              
                    $data['status'] = 'ok';
                    $data['data'] = "Se completo la modificación en el cronograma";
                        
	             
	            }
	        }else{
	            $data['status'] = 'bad';
                $data['data'] = 'Ingresar el valor de la TEA para los montos a ingresar.';
	        }
	    }else{
	        if(empty($txtMondoLetra) && !empty($txtFechaInicio)){
	            
	            
    	            //DATOS
    	            $consultar_datos = mysqli_query($conection, "SELECT id_venta as idventa,  correlativo as correlativo FROM gp_cronograma WHERE id='$cbxLetraInicio'");
    	            $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
    	            
    	            $idventa = $respuesta_datos['idventa'];
    	            $correlativo = $respuesta_datos['correlativo'];
    	            
    	            //consultar capital vivo
    	            $correlativo_cv = $correlativo - 1;
    	            $consultar_cv = mysqli_query($conection, "SELECT capital_vivo as capital FROM gp_cronograma WHERE id_venta='$idventa' AND correlativo='$correlativo_cv'");
    	            $respuesta_cv = mysqli_fetch_assoc($consultar_cv);
    	            
    	            $capital_vivo = $respuesta_cv['capital'];
    	            
    	            $consultar_datos2 = mysqli_query($conection, "SELECT id_venta as idventa,  correlativo as correlativo FROM gp_cronograma WHERE id='$cbxLetraFin'");
    	            $respuesta_datos2 = mysqli_fetch_assoc($consultar_datos2);
    	            
    	            $idventa2 = $respuesta_datos2['idventa'];
    	            $correlativo2 = $respuesta_datos2['correlativo'];
    	            
    	            $txtTEALetra = ($txtTEALetra/100);
    	            $valor_TEA = pow(( 1 + $txtTEALetra),(30/360)) - 1;
    	            
    	            //ACTUALIZAR MONTOS DE CRONOGRAMA
    	            if($idventa==$idventa2){
    	                $dato_fec = $txtFechaInicio;
    	                $dia_fijo = date("d",strtotime($txtFechaInicio));
    	                //ACTUALIZAR
        	             for($i=$correlativo;$i<=$correlativo2;$i++){
        	                 
        	                $consultar = mysqli_query($conection, "SELECT
        	                fecha_vencimiento as fecha,
        	                estado as estado
        	               FROM gp_cronograma
                    	   WHERE id_venta='$idventa' AND correlativo='$i'");
                    	   $respuesta = mysqli_fetch_assoc($consultar);
                    	   $estado= $respuesta['estado'];
                    	   
                    	   $dato_estado = 0;
                    	   if($estado == '3'){
                    	       if($dato_fec > $fecha){
                    	           $dato_estado = ", estado='1'";
                    	       }else{
                    	           $dato_estado = ", estado='3'";
                    	       }
                    	   }else{
                    	       $dato_estado = ", estado='$estado'";
                    	   }
                    	   
                    	   $actualizar = mysqli_query($conection, "UPDATE gp_cronograma
                    	   SET fecha_vencimiento='$txtFechaInicio', id_usuario_actualiza='$idusuario', actualizado='$actualiza' $dato_estado
                    	   WHERE id_venta='$idventa' AND correlativo='$i'");
                    	   
                    	   $anio = date("Y",strtotime($txtFechaInicio));
                    	   $mes = date("m",strtotime($txtFechaInicio));
                    	   $dia = $dia_fijo;
                    	   
                    	   $mes = $mes + 1;
                    	   if($mes=='13'){
                    	       $anio = $anio + 1;
                    	       $mes = '1';
                    	   }else{
                    	        if($mes =='2' && ($dia=='31' || $dia=='30' || $dia=='29')){
                    	            $dia = '28';
                    	       }else{
                    	         if($dia=='31' && ($mes=='1' || $mes=='3' || $mes=='5' || $mes=='7' || $mes=='8' || $mes=='10' || $mes=='12')){
                    	             $dia='31';
                    	         }else{
                    	             if($dia=='31'){
                    	                 $dia='30';
                    	             }
                    	         }  
                    	       }
                    	   }
                    	   
                    	   
                    	   $txtFechaInicio = $anio."-".$mes."-".$dia;
        	                 
        	             }
    	              
                        $data['status'] = 'ok';
                        $data['data'] = "Se completo la modificación en el cronograma";
    	            }
	            
	            
	            
	        }else{
	            if(!empty($txtMondoLetra) && !empty($txtFechaInicio)){
	                
	                if(!empty($txtTEALetra)){
	            
        	            //DATOS
        	            $consultar_datos = mysqli_query($conection, "SELECT id_venta as idventa,  correlativo as correlativo FROM gp_cronograma WHERE id='$cbxLetraInicio'");
        	            $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
        	            
        	            $idventa = $respuesta_datos['idventa'];
        	            $correlativo = $respuesta_datos['correlativo'];
        	            
        	            //consultar capital vivo
        	            $correlativo_cv = $correlativo - 1;
        	            $consultar_cv = mysqli_query($conection, "SELECT capital_vivo as capital FROM gp_cronograma WHERE id_venta='$idventa' AND correlativo='$correlativo_cv'");
        	            $respuesta_cv = mysqli_fetch_assoc($consultar_cv);
        	            
        	            $capital_vivo = $respuesta_cv['capital'];
        	            
        	            $consultar_datos2 = mysqli_query($conection, "SELECT id_venta as idventa,  correlativo as correlativo FROM gp_cronograma WHERE id='$cbxLetraFin'");
        	            $respuesta_datos2 = mysqli_fetch_assoc($consultar_datos2);
        	            
        	            $idventa2 = $respuesta_datos2['idventa'];
        	            $correlativo2 = $respuesta_datos2['correlativo'];
        	            
        	            $txtTEALetra = ($txtTEALetra/100);
        	            $valor_TEA = pow(( 1 + $txtTEALetra),(30/360)) - 1;
        	            
        	            //ACTUALIZAR MONTOS DE CRONOGRAMA
        	            if($idventa==$idventa2){
        	                $dia_fijo = date("d",strtotime($txtFechaInicio));
        	             //ACTUALIZAR
        	             for($i=$correlativo;$i<=$correlativo2;$i++){
        	                 
        	               $intereses = $capital_vivo * $valor_TEA;
                    	   $amortizacion = $txtMondoLetra - $intereses;
                    	   $capital_vivo = $capital_vivo - $amortizacion;
                    	   
                    	    $consultar = mysqli_query($conection, "SELECT
        	                fecha_vencimiento as fecha,
        	                estado as estado
        	               FROM gp_cronograma
                    	   WHERE id_venta='$idventa' AND correlativo='$i'");
                    	   $respuesta = mysqli_fetch_assoc($consultar);
                    	   $estado= $respuesta['estado'];
                    	   
                    	   $dato_estado = 0;
                    	   if($estado == '3'){
                    	       if($txtFechaInicio > $fecha){
                    	           $dato_estado = ", estado='1'";
                    	       }else{
                    	           $dato_estado = ", estado='3'";
                    	       }
                    	   }else{
                    	       $dato_estado = ", estado='$estado'";
                    	   }
                    	   
                    	   
                    	   $actualizar = mysqli_query($conection, "UPDATE gp_cronograma
                    	   SET monto_letra='$txtMondoLetra', interes_amortizado='$intereses', capital_amortizado='$amortizacion', capital_vivo='$capital_vivo', fecha_vencimiento='$txtFechaInicio', id_usuario_actualiza='$idusuario', actualizado='$actualiza' $dato_estado
                    	   WHERE id_venta='$idventa' AND correlativo='$i'");
                    	   
                    	   $anio = date("Y",strtotime($txtFechaInicio));
                    	   $mes = date("m",strtotime($txtFechaInicio));
                    	   $dia = $dia_fijo;
                    	   
                    	   $mes = $mes + 1;
                    	   if($mes=='13'){
                    	       $anio = $anio + 1;
                    	       $mes = '1';
                    	   }else{
                    	        if($mes =='2' && ($dia=='31' || $dia=='30' || $dia=='29')){
                    	            $dia = '28';
                    	       }else{
                    	         if($dia=='31' && ($mes=='1' || $mes=='3' || $mes=='5' || $mes=='7' || $mes=='8' || $mes=='10' || $mes=='12')){
                    	             $dia='31';
                    	         }else{
                    	             if($dia=='31'){
                    	                 $dia='30';
                    	             }
                    	         }  
                    	       }
                    	   }
                    	   
                    	   $txtFechaInicio = $anio."-".$mes."-".$dia;
        	                 
        	             }
        	              
                        $data['status'] = 'ok';
                        $data['data'] = "Se completo la modificación en el cronograma";
                            
        	             
        	            }
        	        }else{
        	            $data['status'] = 'bad';
                        $data['data'] = 'Ingresar el valor de la TEA para los montos a ingresar.';
        	        }
	                
	            }else {
                    $data['status'] = 'bad';
                    $data['data'] = 'Ingresar valor de Monto Letra o Fecha Inicio segun desee modificar.';
                }
	        }
	    }
	    
	   
	} else {
        $data['status'] = 'bad';
        $data['data'] = 'Seleccionar la Letra de Inicio y Letra de Termino (rango) a modificar.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnEditarCronograma'])){

	$idcronograma = $_POST['idCronograma'];
	
		
		//Consultar Datos del Registro Seleccionado
		$query = mysqli_query($conection, "SELECT cro.id as id,
		cro.fecha_vencimiento as fecha_vencimiento,
		cro.item_letra as item_letra,
		ROUND(cro.monto_letra,2) as monto,
		ROUND(cro.interes_amortizado,2) as interes_amortizado,
		ROUND(cro.capital_amortizado,2) as capital_amortizado,
		ROUND(cro.capital_vivo,2) as capital_vivo,
		cro.estado as estado
		FROM gp_cronograma cro
		WHERE cro.id = '$idcronograma'
		");
		
	if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro parametro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnActualizarCronograma'])){

	$idcronograma = $_POST['txtIdCronograma'];
	$txtFecVencimientoC = $_POST['txtFecVencimientoC'];
	$txtLetraC = $_POST['txtLetraC'];
	$txtMontoLetraC = $_POST['txtMontoLetraC'];
	$txtInteresesC = $_POST['txtInteresesC'];
	$txtAmortizacionC = $_POST['txtAmortizacionC'];
	$txtCapitalVivoC = $_POST['txtCapitalVivoC'];
	$cbxEstadoLetra = $_POST['cbxEstadoLetra'];

	$txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];

	$actualiza = $fecha.' '.$hora;
	
		$query = mysqli_query($conection, "UPDATE gp_cronograma SET fecha_vencimiento='$txtFecVencimientoC',
		item_letra='$txtLetraC',
		monto_letra='$txtMontoLetraC',
		interes_amortizado='$txtInteresesC',
		capital_amortizado='$txtAmortizacionC',
		capital_vivo='$txtCapitalVivoC', id_usuario_actualiza='$idusuario', actualizado='$actualiza'
		WHERE id = '$idcronograma'");
		
	if ($query) {
        $data['status'] = 'ok';
        $data['valor'] = $idcronograma;
    } else {
        $data['status'] = 'bad';
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnConsultarEstadoLetra'])){

		$idregistro = $_POST['idregistro'];
		
		$query = mysqli_query($conection, "SELECT estado as estado, id_venta as idventa, correlativo as correlativo FROM gp_cronograma WHERE id='$idregistro'");
		$respuesta = mysqli_fetch_assoc($resp);
		$estado = $respuesta['estado'];
		$idventa = $respuesta['idventa'];
		$correlativo = $respuesta['correlativo'];
		$validar = 0;
		
		if($estado=="2"){
		    $consultar_pagos = mysqli_query($conection, "SELECT idpago FROM gp_pagos_cabecera WHERE id_venta='$idventa' AND id_cronograma='$correlativo'");
		    $contar_pagos = mysqli_num_rows($consultar_pagos);
		    if($contar_pagos>0){
		        $validar = 1;
		    }else{
		       $validar = 0; 
		    }
		}else{
		    $validar = 0;
		}
        
        if($query){
            $data['status']="ok";
            $data['estado'] = $validar;
        }else{
            $data['status']="bad";
        }

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT);
}


if (isset($_POST['btnIrMasivo'])) {

    $iduser = $_POST['iduser'];

    $data['status'] = 'ok';
    $data['valor'] = $iduser;
    $data['ruta'] = $NAME_SERVER."views/M02_Clientes/M02SM02_CronogramaPagos/M02SM03_CronogramaMasivo?Vsr=".$iduser;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);  

}



if(isset($_POST['btnSubirLetraCron'])){

	$idcronograma = $_POST['idCronograma'];

	$txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	$actualiza = $fecha.' '.$hora;

	
	//consultar datos id_venta y correlativo
	$correlativo = "";
	$idventa = "";
	$consultar_datos = mysqli_query($conection, "SELECT correlativo, id_venta FROM gp_cronograma WHERE id='$idcronograma'");
	$respuesta_datos = mysqli_fetch_assoc($consultar_datos);

	//valores principales
	$correlativo = $respuesta_datos['correlativo'];
	$idventa = $respuesta_datos['id_venta'];

	//valores secundarios
	$correlativo_sec = $respuesta_datos['correlativo'] - 1;

	//Actualizar el que BAJA
	$query = mysqli_query($conection, "UPDATE gp_cronograma SET correlativo='$correlativo', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$idventa' AND correlativo='$correlativo_sec'");
	
	//consultar si tiene pagoos
	$consultar_pagos = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$idventa' AND id_cronograma='$correlativo_sec'");
	$respuesta_pagos = mysqli_num_rows($consultar_pagos);
	
	if($respuesta_pagos > 0){
	    	//actualizar idcorrelativo en pagos
            $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET id_cronograma='$correlativo', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$ID_VNTA' AND id_cronograma='$correlativo_sec'");
	}
	
		
	if ($query) {

		//Actualizar el que SUBE
		$query = mysqli_query($conection, "UPDATE gp_cronograma SET correlativo='$correlativo_sec', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$idventa' AND id='$idcronograma'");
		
		//consultar si tiene pagoos
    	$consultar_pagos = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$idventa' AND id_cronograma='$correlativo'");
    	$respuesta_pagos = mysqli_num_rows($consultar_pagos);
    	
    	if($respuesta_pagos > 0){
    	    	//actualizar idcorrelativo en pagos
                $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET id_cronograma='$correlativo_sec', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$ID_VNTA' AND id_cronograma='$correlativo'");
    	}

        $data['status'] = 'ok';
		$data['data'] = 'Se ha movido la letra con éxito.';

    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro parametro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnBajarLetraCron'])){

	$idcronograma = $_POST['idCronograma'];

	$txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	$actualiza = $fecha.' '.$hora;

	
	//consultar datos id_venta y correlativo
	$correlativo = "";
	$idventa = "";
	$consultar_datos = mysqli_query($conection, "SELECT correlativo, id_venta FROM gp_cronograma WHERE id='$idcronograma'");
	$respuesta_datos = mysqli_fetch_assoc($consultar_datos);

	//valores principales
	$correlativo = $respuesta_datos['correlativo'];
	$idventa = $respuesta_datos['id_venta'];

	//valores secundarios
	$correlativo_sec = $respuesta_datos['correlativo'] + 1;

	//Actualizar el que BAJA
	$query = mysqli_query($conection, "UPDATE gp_cronograma SET correlativo='$correlativo', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$idventa' AND correlativo='$correlativo_sec'");
	
	//consultar si tiene pagoos
	$consultar_pagos = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$idventa' AND id_cronograma='$correlativo_sec'");
	$respuesta_pagos = mysqli_num_rows($consultar_pagos);
	
	if($respuesta_pagos > 0){
	    
    	//actualizar idcorrelativo en pagos
        $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET id_cronograma='$correlativo_sec', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$ID_VNTA' AND id_cronograma='$correlativo_sec'");
            
        //consultar si tiene pagoos
    	$consultar_pagos = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$idventa' AND id_cronograma='$correlativo'");
    	$respuesta_pagos = mysqli_num_rows($consultar_pagos);
    	
    	if($respuesta_pagos > 0){
    	    	//actualizar idcorrelativo en pagos
                $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET id_cronograma='$correlativo_sec', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$ID_VNTA' AND id_cronograma='$correlativo'");
    	}
	}
		
	if ($query) {

		//Actualizar el que SUBE
		$query = mysqli_query($conection, "UPDATE gp_cronograma SET correlativo='$correlativo_sec', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$idventa' AND id='$idcronograma'");

        $data['status'] = 'ok';
		$data['data'] = 'Se ha movido la letra con éxito.';

    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro parametro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnEliminarLetraCron'])){

	$idcronograma = $_POST['idCronograma'];

	$txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	$actualiza = $fecha.' '.$hora;
	
	//Consultar Datos del Registro Seleccionado
	$query = mysqli_query($conection, "UPDATE gp_cronograma SET esta_borrado='1', id_usuario_borra='$idusuario', borrado='$actualiza'
	WHERE id = '$idcronograma'");
		
	if ($query) {
      
        $data['status'] = 'ok';
		$data['data'] = 'Se ha eliminado correctamente el registro seleccionado.';

    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro parametro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnRevisarDatosAudit'])){

	$idcronograma = $_POST['idCronograma'];
	
	//Consultar Datos del Registro Seleccionado
	$query = mysqli_query($conection, "SELECT cro.id as id,
	cro.fecha_vencimiento as fecha_vencimiento,
	cro.item_letra as item_letra,
	ROUND(cro.monto_letra,2) as monto,
	ROUND(cro.interes_amortizado,2) as interes_amortizado,
	ROUND(cro.capital_amortizado,2) as capital_amortizado,
	ROUND(cro.capital_vivo,2) as capital_vivo,
	cro.estado as estado,
	cro.id_usuario_crea as usu_crea,
	cro.creado as creado,
	(if(cro.id_usuario_crea IS NULL, 'Ninguno', (select concat(SUBSTRING_INDEX(p.nombre,' ',1),' ',p.apellido) from persona p where p.idusuario=cro.id_usuario_crea))) as nom_usu_crea,
	cro.id_usuario_actualiza as usu_actualiza,
	cro.actualizado as actualizado,
	(if(cro.id_usuario_actualiza IS NULL, 'Ninguno', (select concat(SUBSTRING_INDEX(p.nombre,' ',1),' ',p.apellido) from persona p where p.idusuario=cro.id_usuario_actualiza))) as nom_usu_actualiza
	FROM gp_cronograma cro
	WHERE cro.id = '$idcronograma'
	");
		
	if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro parametro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}