<?php

    session_start();
    include_once "../../../../config/configuracion.php";
    include_once "../../../../config/conexion_2.php";
    include_once "../../../../config/codificar.php";
    $hora = date("H:i:s", time());;
    $fecha = date('Y-m-d');
    $fecha_hoy = date('Y-m-d');  

    $data = array();
    $dataList = array();
 
if(isset($_POST['btnMostrarVoucher'])){
    
    $idRegistro = $_POST['idRegistro'];

    $consultar_voucher = mysqli_query($conection, "SELECT voucher as voucher FROM gp_pagos_detalle WHERE idpago_detalle='$idRegistro'");
    $respuesta_voucher = mysqli_fetch_assoc($consultar_voucher);

    $nom_voucher = $respuesta_voucher['voucher'];

    $cadena = explode(".", $nom_voucher);
    $formato = $cadena[1];
    
    $data['status'] = 'ok';
    $data['formato'] = $formato;
    $data['voucher'] = $nom_voucher;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['btnValidarFechas'])){
    
    $fecha = new DateTime();
    $fecha->modify('first day of this month');
    $primer_dia = $fecha->format('Y-m-d');
    
    $fecha = new DateTime();
    $fecha->modify('last day of this month');
    $ultimo_dia = $fecha->format('Y-m-d'); 
    
    $data['status'] = 'ok';
    $data['primero'] = $primer_dia;
    $data['ultimo'] = $ultimo_dia;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}


if(isset($_POST['btnListarTablaPagosComprobante'])){

    
    $txtFiltroDocumentoCV = isset($_POST['txtFiltroDocumentoCV']) ? $_POST['txtFiltroDocumentoCV'] : Null;
    $txtFiltroDocumentoCVr = trim($txtFiltroDocumentoCV);
    
    $txtFiltroDesdeCV = isset($_POST['txtFiltroDesdeCV']) ? $_POST['txtFiltroDesdeCV'] : Null;
    $txtFiltroDesdeCVr = trim($txtFiltroDesdeCV);
    
    $txtFiltroHastaCV = isset($_POST['txtFiltroHastaCV']) ? $_POST['txtFiltroHastaCV'] : Null;
    $txtFiltroHastaCVr = trim($txtFiltroHastaCV);
    
    $cbxFiltroBancoCV = isset($_POST['cbxFiltroBancoCV']) ? $_POST['cbxFiltroBancoCV'] : Null;
    $cbxFiltroBancoCVr = trim($cbxFiltroBancoCV);
    
    $cbxTipoPagoC = isset($_POST['cbxTipoPagoC']) ? $_POST['cbxTipoPagoC'] : Null;
    $cbxTipoPagoCr = trim($cbxTipoPagoC);
    
    $cbxEstadoC = isset($_POST['cbxEstadoC']) ? $_POST['cbxEstadoC'] : Null;
    $cbxEstadoCr = trim($cbxEstadoC);

    $cbxTipoComprobanteSunat = isset($_POST['cbxTipoComprobanteSunat']) ? $_POST['cbxTipoComprobanteSunat'] : Null;
    $cbxTipoComprobanteSunatr = trim($cbxTipoComprobanteSunat);

    $cbxOrdenar = isset($_POST['cbxOrdenar']) ? $_POST['cbxOrdenar'] : Null;
    $cbxOrdenarr = trim($cbxOrdenar);
    
    $query_documento = "";
    $query_fecha = "";
    $query_bancos = "";
    $query_tp="";
    $query_ec="";
    $query_tcs="";
    $query_ordenar="";
    
    if(!empty($txtFiltroDocumentoCVr)){
        $query_documento = "AND dc.documento='$txtFiltroDocumentoCVr'";
    }

    if(!empty($txtFiltroDesdeCVr) && !empty($txtFiltroHastaCVr)){
        $query_fecha = "AND gppd.fecha_pago BETWEEN '$txtFiltroDesdeCVr' AND '$txtFiltroHastaCVr'";
    }else{
        if(!empty($txtFiltroDesdeCVr) && empty($txtFiltroHastaCVr)){
            $query_fecha = "AND gppd.fecha_pago='$txtFiltroDesdeCVr'";
        }
    }

    if(!empty($cbxFiltroBancoCVr)){
        $query_bancos = "AND gppd.agencia_bancaria='$cbxFiltroBancoCVr'";
    }
   
    if(!empty($cbxTipoPagoCr)){
        $query_tp = "AND gppd.tipo_pago='$cbxTipoPagoCr'";
    }
    
    
    /*if(!empty($cbxEstadoCr)){
        $query_ec = "AND gppd.estado_cierre='$cbxEstadoCr'";
    }*/
	
	if (!empty($cbxEstadoCr)) {
		if ($cbxEstadoCr == '1') {
			$query_ec = "AND gppd.estado_cierre = '1'"; // Filtra solo "PROCESANDO"
		} elseif ($cbxEstadoCr == '2') {
			$query_ec = "AND gppd.estado_cierre >= '2'"; // Filtra los estados 2 o mayores (CERRADO)
		}
	}


    if(!empty($cbxTipoComprobanteSunatr)){
        $query_tcs = "AND gppd.tipo_comprobante_sunat='$cbxTipoComprobanteSunatr'";
    }

    if(!empty($cbxOrdenarr)){
        if($cbxOrdenarr=='1'){
            $query_ordenar="gppd.serie ASC";
        }else{
            if($cbxOrdenarr=='2'){
                $query_ordenar="gppd.serie DESC";
            }else{
                if($cbxOrdenarr=='3'){
                    $query_ordenar="gppd.numero ASC";
                }else{
                    $query_ordenar="gppd.numero DESC"; 
                }
            }
        }
    }else{
        $query_ordenar="gppd.fecha_pago DESC";
    }

        $query = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            concat(dc.documento,' - ', dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as cliente,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as nom_cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as lote_nom,
            date_format(gpcr.fecha_vencimiento, '%d/%m/%Y') as fecha_vencimiento,
            gpcr.item_letra as letra,
            date_format(gppd.fecha_pago, '%d/%m/%Y') as fecha_pago,
            date_format(gppd.fecha_pago, '%d-%m-%Y') as fech_pago,
            gppd.fecha_pago as fec_pago,
            cddddx.texto1 as tipo_moneda,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            format(gppd.importe_pago,2) as importe,
            if(gppc.fecha_pago>gpcr.fecha_vencimiento,if((TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, gppd.fecha_pago)>0),concat('-',TIMESTAMPDIFF(DAY,gpcr.fecha_vencimiento, gppd.fecha_pago)),0),0) as mora,
            if(gppd.estado=2, 'APROBADO', if(gppd.estado=3,'RECHAZADO','POR VALIDAR')) as estado_pago,
            cdddx.nombre_corto as banco,
            cdx.nombre_corto as medio_pago,
            cddx.nombre_corto as tipo_comprobante,
            gppd.nro_operacion as nro_operacion,
            gppd.voucher as voucher,
            gpcom.serie as serie,
            gpcom.numero as numero,
            if(gpcom.comprobante_adj='', gpcom.comprobante_url, gpcom.comprobante_adj) as comprobante,
			CASE gppd.tipo_comprobante_sunat 
			WHEN '15589' THEN 'FACTURA'
			WHEN '15588' THEN 'BOLETA DE VENTA'
			WHEN '15590' THEN 'NOTA CREDITO'
			WHEN '15597' THEN 'NOTA DE DÉBITO'
			ELSE ''
			END AS tipo_comprobante_sunat,
            if(gppd.estado_cierre='1', 'PROCESANDO', 'CERRADO') as estado_cierre,
            date_format(gpcom.fecha_emision, '%d/%m/%Y') as fecha_emision
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_detalle_comprobante AS gpcom ON gpcom.idpago_detalle=gppd.idpago_detalle
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.medio_pago AND cdx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.tipo_comprobante AND cddx.codigo_tabla='_TIPO_COMPROBANTE'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppc.agencia_bancaria AND cdddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppd.moneda_pago AND cddddx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cdddddx ON (cdddddx.idconfig_detalle=gppd.tipo_comprobante_sunat OR gppd.tipo_comprobante_sunat=0) AND cdddddx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
            WHERE gppd.esta_borrado=0
            AND gppd.estado='2'
            $query_tp
            $query_ec
            $query_documento
            $query_fecha
            $query_bancos
            $query_tcs
            GROUP BY gppd.idpago_detalle
            ORDER BY $query_ordenar"); 
		

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'cliente' => $row['cliente'],
                'lote' => $row['lote'],
                'lote_nom' => $row['lote_nom'],
                'fecha_vencimiento' => $row['fecha_vencimiento'],
                'letra' => $row['letra'],
                'fecha_pago' => $row['fecha_pago'],
                'fech_pago' => $row['fech_pago'],
                'fec_pago' => $row['fec_pago'],
                'tipo_moneda' => $row['tipo_moneda'],
                'tipo_cambio' => $row['tipo_cambio'],
                'importe' => $row['importe'],
                'pagado' => $row['pagado'],
                'mora' => $row['mora'],
                'estado_pago' => $row['estado_pago'],
                'banco' => $row['banco'],
                'medio_pago' => $row['medio_pago'],
                'voucher' => $row['voucher'],
                'boleta' => 'documento.pdf',
                'tipo_comprobante' => $row['tipo_comprobante'],
                'nro_operacion' => $row['nro_operacion'],
                'serie' => $row['serie'],
                'numero' => $row['numero'],
                'comprobante' => $row['comprobante'],
                'estado_cierre' => $row['estado_cierre'],
                'tipo_comprobante_sunat' => $row['tipo_comprobante_sunat'],
                'fecha_emision' => $row['fecha_emision']
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


if (isset($_POST['btnEditarComprobante'])) {

    $IdReg = $_POST['idRegistro'];
    $query = mysqli_query($conection, "SELECT 
    gppd.idpago_detalle as id,
    gppd.fecha_emision as fecha_emision,
    gppd.tipo_comprobante_sunat as tipoComprobante,
    gppd.serie as serie,
    gppd.numero as numero
    FROM gp_pagos_detalle gppd
    WHERE gppd.idpago_detalle='$IdReg'");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnGuardarComprobante'])) {

    $_ID_PAGO_CV = $_POST['_ID_PAGO_CV'];
    $txtFechaEmisionCV = $_POST['txtFechaEmisionCV'];
    $cbxTipoComprobanteCV = $_POST['cbxTipoComprobanteCV'];
    $txtSerieCV = $_POST['txtSerieCV'];
    $txtNumeroCV = $_POST['txtNumeroCV'];
    $ComprobanteCV = $_POST['ComprobanteCV'];
    $valor_comprobante="";
    $name_file = "comprobante";

    if(!empty($ComprobanteCV)){
        $path = $ComprobanteCV;
        $file = new SplFileInfo($path);
        $extension  = $file->getExtension();
        $desc_codigo="comprobante-";        
        if(!empty($ComprobanteCV)){
            $name_file = $desc_codigo.$_ID_PAGO_CV.".".$extension;
        }
        $valor_comprobante = ",comprobante='$name_file'";
    }

    $query = mysqli_query($conection, "UPDATE 
    gp_pagos_detalle SET
    fecha_emision='$txtFechaEmisionCV',
    tipo_comprobante_sunat='$cbxTipoComprobanteCV',
    serie='$txtSerieCV',
    numero='$txtNumeroCV'
    $valor_comprobante
    WHERE idpago_detalle='$_ID_PAGO_CV'");
    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = "Se guardaron los cambios en el pago seleccionado.";
        $data['name'] = $name_file;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/******************* INSERCION ********************/
if (isset($_POST['btnProcesarIngEg'])) {
 
	$bxFiltroanio = $_POST['bxFiltroanio'];
    $bxFiltromeses = $_POST['bxFiltromeses'];
    $bxFiltroIngresEgres = $_POST['bxFiltroIngresEgres'];
    $id = $_POST['id'];

		$consulta_cierre = mysqli_query($conection,"SELECT 
													gppd.idpago_detalle as iddetalle,
													gppd.idpago as idpago, 
													gppc.id_venta as id_venta
													FROM gp_pagos_detalle gppd
													INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									 				WHERE gppd.esta_borrado=0 
													AND gppd.idpago_detalle='$id' AND gppd.estado='2' 
													ORDER BY gppd.fecha_pago DESC"); 

		$respuesta_cierre = mysqli_fetch_assoc($consulta_cierre);
		$var_idpago = $respuesta_cierre['idpago'];
        $var_venta = $respuesta_cierre['id_venta'];

		
			$consultar_pago = mysqli_query($conection, "SELECT 
									gppc.idpago as id,
									gppc.sede as Sede,                                    
									date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
									cd.texto1 as Moneda,
									gppc.tipo_cambio as TipoCambio,
									gppc.glosa as Glosa,
									gppc.pagado as TotalImporte,
									gppc.cuenta_contable as CuentaContable,
									cdx.codigo_sunat as Operacion,
									gppc.numero as Numero,
									gppc.accion as Accion,
									gppc.debe_haber as DebHab
									FROM gp_pagos_cabecera gppc
	 								INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
									INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppc.operacion AND cdx.codigo_tabla='_OPERACION_PAGO'
									WHERE gppc.esta_borrado=0 AND gppc.idpago='$var_idpago' AND gppc.id_venta='$var_venta'
									ORDER BY gppc.fecha_pago");
						
						$result = mysqli_num_rows($consultar_pago);
					if ($result>0){ 
						for ($i = 1; $i <= $result; $i++){		

							$respuesta_pago = mysqli_fetch_assoc($consultar_pago);
							$idpago = $respuesta_pago['id'];
							$sede = $respuesta_pago['Sede'];
							$fecha_pago = $respuesta_pago['Fecha'];
							$moneda_pago = $respuesta_pago['Moneda'];
							$tipo_cambio = $respuesta_pago['TipoCambio'];
							$glosa = $respuesta_pago['Glosa'];
							$importe_pago = $respuesta_pago['TotalImporte'];
							$cuenta_contable = $respuesta_pago['CuentaContable'];
							$operacion = $respuesta_pago['Operacion'];
							$numero = $respuesta_pago['Numero'];
							$accion = $respuesta_pago['Accion'];
							$debe_haber = $respuesta_pago['DebHab'];							
							
							if($debe_haber == "H"){		
							
								$consulta_id = mysqli_query($conection,"SELECT if(MAX(Id_Cabecera) is null ,0,MAX(Id_Cabecera)) AS contador FROM ingresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion) 
								VALUES ('$contador','$sede','$var_idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion')");
								
								if($insertar_pagoCabHab){
									
									$consultar_detalle = mysqli_query($conection, "SELECT 
										gppc.idpago as id,
										gppd.idpago_detalle as id_detalle,
										cd.codigo_sunat as TipoComp,
										gppd.serie as Serie,
										gppd.numero as Numero,
										gppd.pagado as TotalImporte,
										gppc.cuenta_contable as CuentaContable,
										gppd.centro_costo as CentroCosto,
										gppd.razon_social as RazonSocial,
										gppd.dni_ruc as DniRuc,
										date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
										gppd.debe_haber as DebHab
										FROM gp_pagos_detalle gppd
										INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
										INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.tipo_comprobante AND cd.codigo_tabla='_TIPO_COMPROBANTE'
										WHERE gppd.esta_borrado=0 AND gppc.idpago='$var_idpago' 
										ORDER BY gppd.fecha_pago DESC");
							
									$detalle = mysqli_num_rows($consultar_detalle);
									
									for ($cont = 1; $cont <= $detalle; $cont++){
										
									
										$respuesta_detalle = mysqli_fetch_assoc($consultar_detalle);
										$iddetalle = $respuesta_detalle['id_detalle'];
										$tipo_comprobante = $respuesta_detalle['TipoComp'];
										$serie = $respuesta_detalle['Serie'];
										$numero = $respuesta_detalle['Numero'];
										$importe_pago = $respuesta_detalle['TotalImporte'];
										$cuenta_contable = $respuesta_detalle['CuentaContable'];
										$centro_costo = $respuesta_detalle['CentroCosto'];
										$razon_social = $respuesta_detalle['RazonSocial'];
										$dni_ruc = $respuesta_detalle['DniRuc'];							
										$fecha = $respuesta_detalle['FechaR'];							
										$debe_haber = $respuesta_detalle['DebHab'];							
													
										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, RazonSocial, DniRuc, TipoR, SerieR, NumeroR, FechaR, DebHab)
										VALUES ('$contador','$sede','$var_idpago','$cont', '$tipo_comprobante','$serie','$numero','$importe_pago','$cuenta_contable', '$centro_costo','$razon_social','$dni_ruc','','','','$fecha','$debe_haber')");
																	
									}
									
								}
							}else{
								
								$consulta_id = mysqli_query($conection,"SELECT if(MAX(Id_Cabecera) is null ,0,MAX(Id_Cabecera)) AS contador FROM egresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								$insertar_pagoCabDeb = mysqli_query($conection,"INSERT INTO egresos_cabecera(Id_Cabecera, Sede, identificador, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion) 
								VALUES ('$contador','$sede','$var_idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion')");
								
								if($insertar_pagoCabDeb){
									
									$consultar_detalle = mysqli_query($conection, "SELECT 
									gppc.idpago as id,
									gppd.idpago_detalle as id_detalle,
									cd.codigo_sunat as TipoComp,
									gppd.serie as Serie,
									gppd.numero as Numero,
									gppd.pagado as TotalImporte,
									gppc.cuenta_contable as CuentaContable,
									gppd.centro_costo as CentroCosto,
									gppd.razon_social as RazonSocial,
									gppd.dni_ruc as DniRuc,
									date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									gppd.debe_haber as DebHab
									FROM gp_pagos_detalle gppd
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
									INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.tipo_comprobante AND cd.codigo_tabla='_TIPO_COMPROBANTE'
									WHERE gppd.esta_borrado=0 AND gppc.idpago='$var_idpago'
									ORDER BY gppd.fecha_pago DESC");
						
									$detalle = mysqli_num_rows($consultar_detalle);
								
									for ($cont = 1; $cont <= $detalle; $cont++){
										
										$respuesta_detalle = mysqli_fetch_assoc($consultar_detalle);
										$iddetalle = $respuesta_detalle['id_detalle'];
										$tipo_comprobante = $respuesta_detalle['TipoComp'];
										$serie = $respuesta_detalle['Serie'];
										$numero = $respuesta_detalle['Numero'];
										$importe_pago = $respuesta_detalle['TotalImporte'];
										$cuenta_contable = $respuesta_detalle['CuentaContable'];
										$centro_costo = $respuesta_detalle['CentroCosto'];
										$razon_social = $respuesta_detalle['RazonSocial'];
										$dni_ruc = $respuesta_detalle['DniRuc'];							
										$fecha = $respuesta_detalle['FechaR'];							
										$debe_haber = $respuesta_detalle['DebHab'];						
																	
										$insertar_pagoDetDeb = mysqli_query($conection,"INSERT INTO egresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, RazonSocial, DniRuc, TipoR, SerieR, NumeroR, FechaR, DebHab)
										VALUES ('$contador','$sede','$var_idpago','$cont', '$tipo_comprobante','$serie','$numero','$importe_pago','$cuenta_contable', '$centro_costo','$razon_social','$dni_ruc','','','','$fecha','$debe_haber')");
										
									}	
								}							
							}	
						//$data = $respuesta_pago;			
						}
					}
						header('Content-type: text/javascript');
						echo json_encode($data, JSON_PRETTY_PRINT);
}
/******************* INSERCION ********************/

/******************* INSERCION OBSERVADOS ********************/
if (isset($_POST['btnProcesarIngEgObserv'])) {

	$bxFiltroanio = $_POST['bxFiltroanio'];
    $bxFiltromeses = $_POST['bxFiltromeses'];
    $bxFiltroIngresEgres = $_POST['bxFiltroIngresEgres']; // HABER / DEBE
    $id = $_POST['id'];

		$consulta_cierre = mysqli_query($conection,"SELECT 
													gppd.idpago_detalle as iddetalle,
													gppd.idpago as idpago, 
													gppc.id_venta as id_venta
													FROM gp_pagos_detalle gppd
													INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									 				WHERE gppd.esta_borrado=0 
													AND gppd.idpago_detalle='$id' AND gppd.estado='2' 
													ORDER BY gppd.fecha_pago DESC"); 

		$respuesta_cierre = mysqli_fetch_assoc($consulta_cierre);
		$var_idpago = $respuesta_cierre['idpago'];
        $var_venta = $respuesta_cierre['id_venta'];

		
			$consultar_pago = mysqli_query($conection, "SELECT 
									gppc.idpago as id,
									gppc.sede as Sede,                                    
									date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
									cd.texto1 as Moneda,
									gppc.tipo_cambio as TipoCambio,
									gppc.glosa as Glosa,
									gppc.pagado as TotalImporte,
									gppc.cuenta_contable as CuentaContable,
									cdx.codigo_sunat as Operacion,
									gppc.numero as Numero,
									gppc.accion as Accion,
									gppc.debe_haber as DebHab
									FROM gp_pagos_cabecera gppc
	 								INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
									INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppc.operacion AND cdx.codigo_tabla='_OPERACION_PAGO'
									WHERE gppc.esta_borrado=0 AND gppc.idpago='$var_idpago' AND gppc.id_venta='$var_venta'
									ORDER BY gppc.fecha_pago");
						
						$result = mysqli_num_rows($consultar_pago);
						
						if ($result){ 
							
							for ($i = 1; $i <= $result; $i++){		

								$respuesta_pago = mysqli_fetch_assoc($consultar_pago);
								$idpago = $respuesta_pago['id'];
								$sede = $respuesta_pago['Sede'];
								$fecha_pago = $respuesta_pago['Fecha'];
								$moneda_pago = $respuesta_pago['Moneda'];
								$tipo_cambio = $respuesta_pago['TipoCambio'];
								$glosa = $respuesta_pago['Glosa'];
								$importe_pago = $respuesta_pago['TotalImporte'];
								$cuenta_contable = $respuesta_pago['CuentaContable'];
								$operacion = $respuesta_pago['Operacion'];
								$numero = $respuesta_pago['Numero'];
								$accion = $respuesta_pago['Accion'];
								$debe_haber = $respuesta_pago['DebHab'];							
								
								if($debe_haber == "H"){		
								
									$consulta_id = mysqli_query($conection,"SELECT if(MAX(Id_Cabecera) is null ,0,MAX(Id_Cabecera)) AS contador FROM ingresos_cabecera_obs");
									$consulta = mysqli_fetch_assoc($consulta_id);								
									$contador = $consulta['contador'];
									$contador = $contador + 1;
									
									$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera_obs(Id_Cabecera, Sede, identificador, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion) 
									VALUES ('$contador','$sede','$var_idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion')");
									
									if($insertar_pagoCabHab){
										
										$consultar_detalle = mysqli_query($conection, "SELECT 
											gppc.idpago as id,
											gppd.idpago_detalle as id_detalle,
											cd.codigo_sunat as TipoComp,
											gppd.serie as Serie,
											gppd.numero as Numero,
											gppd.pagado as TotalImporte,
											gppc.cuenta_contable as CuentaContable,
											gppd.centro_costo as CentroCosto,
											gppd.razon_social as RazonSocial,
											gppd.dni_ruc as DniRuc,
											date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
											gppd.debe_haber as DebHab
											FROM gp_pagos_detalle gppd
											INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
											INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.tipo_comprobante AND cd.codigo_tabla='_TIPO_COMPROBANTE'
											WHERE gppd.esta_borrado=0 AND gppc.idpago='$var_idpago' 
											ORDER BY gppd.fecha_pago DESC");
								
										$detalle = mysqli_num_rows($consultar_detalle);
										
										for ($cont = 1; $cont <= $detalle; $cont++){
											
										
											$respuesta_detalle = mysqli_fetch_assoc($consultar_detalle);
											$iddetalle = $respuesta_detalle['id_detalle'];
											$tipo_comprobante = $respuesta_detalle['TipoComp'];
											$serie = $respuesta_detalle['Serie'];
											$numero = $respuesta_detalle['Numero'];
											$importe_pago = $respuesta_detalle['TotalImporte'];
											$cuenta_contable = $respuesta_detalle['CuentaContable'];
											$centro_costo = $respuesta_detalle['CentroCosto'];
											$razon_social = $respuesta_detalle['RazonSocial'];
											$dni_ruc = $respuesta_detalle['DniRuc'];							
											$fecha = $respuesta_detalle['FechaR'];							
											$debe_haber = $respuesta_detalle['DebHab'];							
														
											$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle_obs(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, RazonSocial, DniRuc, TipoR, SerieR, NumeroR, FechaR, DebHab)
											VALUES ('$contador','$sede','$var_idpago','$cont', '$tipo_comprobante','$serie','$numero','$importe_pago','$cuenta_contable', '$centro_costo','$razon_social','$dni_ruc','','','','$fecha','$debe_haber')");
																		
										}
										
									}
								}else{
									
									$consulta_id = mysqli_query($conection,"SELECT if(MAX(Id_Cabecera) is null ,0,MAX(Id_Cabecera)) AS contador FROM egresos_cabecera_obs");
									$consulta = mysqli_fetch_assoc($consulta_id);								
									$contador = $consulta['contador'];
									$contador = $contador + 1;
									
									$insertar_pagoCabDeb = mysqli_query($conection,"INSERT INTO egresos_cabecera_obs(Id_Cabecera, Sede, identificador, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion) 
									VALUES ('$contador','$sede','$var_idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion')");
									
									if($insertar_pagoCabDeb){
										
										$consultar_detalle = mysqli_query($conection, "SELECT 
										gppc.idpago as id,
										gppd.idpago_detalle as id_detalle,
										cd.codigo_sunat as TipoComp,
										gppd.serie as Serie,
										gppd.numero as Numero,
										gppd.pagado as TotalImporte,
										gppc.cuenta_contable as CuentaContable,
										gppd.centro_costo as CentroCosto,
										gppd.razon_social as RazonSocial,
										gppd.dni_ruc as DniRuc,
										date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
										gppd.debe_haber as DebHab
										FROM gp_pagos_detalle gppd
										INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
										INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.tipo_comprobante AND cd.codigo_tabla='_TIPO_COMPROBANTE'
										WHERE gppd.esta_borrado=0 AND gppc.idpago='$var_idpago'
										ORDER BY gppd.fecha_pago DESC");
							
										$detalle = mysqli_num_rows($consultar_detalle);
									
										for ($cont = 1; $cont <= $detalle; $cont++){
											
											$respuesta_detalle = mysqli_fetch_assoc($consultar_detalle);
											$iddetalle = $respuesta_detalle['id_detalle'];
											$tipo_comprobante = $respuesta_detalle['TipoComp'];
											$serie = $respuesta_detalle['Serie'];
											$numero = $respuesta_detalle['Numero'];
											$importe_pago = $respuesta_detalle['TotalImporte'];
											$cuenta_contable = $respuesta_detalle['CuentaContable'];
											$centro_costo = $respuesta_detalle['CentroCosto'];
											$razon_social = $respuesta_detalle['RazonSocial'];
											$dni_ruc = $respuesta_detalle['DniRuc'];							
											$fecha = $respuesta_detalle['FechaR'];							
											$debe_haber = $respuesta_detalle['DebHab'];						
																		
											$insertar_pagoDetDeb = mysqli_query($conection,"INSERT INTO egresos_detalle_obs(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, RazonSocial, DniRuc, TipoR, SerieR, NumeroR, FechaR, DebHab)
											VALUES ('$contador','$sede','$var_idpago','$cont', '$tipo_comprobante','$serie','$numero','$importe_pago','$cuenta_contable', '$centro_costo','$razon_social','$dni_ruc','','','','$fecha','$debe_haber')");
											
										}	
									}							
								}						
							}
						}

						header('Content-type: text/javascript');
						echo json_encode($data, JSON_PRETTY_PRINT);
}
/******************* INSERCION OBSERVADOS ********************/

/******************* CERRAR PAGO ********************/
if (isset($_POST['btnCierreComprobante'])) {

    $_ID_PAGO_CV = $_POST['_ID_PAGO_CV'];    

    $consulta = mysqli_query($conection, "SELECT comprobante as comprobante, idpago as idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO_CV'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $consultaaa = $consultaa['comprobante'];
    $idpago = $consultaa['idpago'];  

    if (!empty($consultaaa)) {
        
        $consultar_total_cab = mysqli_query($conection, "SELECT ROUND(pagado,2) as total FROM gp_pagos_cabecera WHERE idpago='$idpago'");
        $respuesta_total_cab = mysqli_fetch_assoc($consultar_total_cab);
        $total_cab = $respuesta_total_cab['total'];
        
        $consultar_total_det = mysqli_query($conection, "SELECT ROUND(SUM(pagado),2) as total FROM gp_pagos_detalle WHERE idpago='$idpago' AND esta_borrado='0'");
        $respuesta_total_det = mysqli_fetch_assoc($consultar_total_det);
        $total_det = $respuesta_total_det['total'];
        
        $dato_obs = 0;
        $data['status'] = 'ok';
        
        if($total_cab == $total_det){
            
            $dato_obs = 1;
            
           $query = mysqli_query($conection, "UPDATE 
            gp_pagos_detalle SET
            estado_cierre='2'
            WHERE idpago_detalle='$_ID_PAGO_CV'"); 
            
            $data['data'] = "Se ha finalizado con exito el trabajo sobre el pago seleccionado.";
            $data['iddato'] = $_ID_PAGO_CV;
            $data['variable'] =$dato_obs;
            
        }else{
            
            $dato_obs = 2;
            $data['data'] = "El pago fue observado por un descuadre en los totales. Revisar la información del pago.";
            $data['iddato'] = $_ID_PAGO_CV;
            $data['variable'] =$dato_obs;
        }
        
    }else {
        $data['status'] = 'bad';
        $data['data'] = 'Es requerido que se cargue el adjunto del comprobante del pago.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnRestablecerComprobante'])) {

    $_ID_PAGO_CV = $_POST['_ID_PAGO_CV'];    

    $query = mysqli_query($conection, "UPDATE 
        gp_pagos_detalle SET
        estado_cierre='1'
        WHERE idpago_detalle='$_ID_PAGO_CV'");   

    if ($query){        
        $data['status'] = 'ok';
        $data['data'] = "Usted ha restablecido el estado del pago cerrado.";
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo restablecer, intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*********************** FUNCION ELIMINAR **********************/

if (isset($_POST['btnElimPago'])) {

    $id = $_POST['id'];

	$consulta_cierre = mysqli_query($conection,"
	SELECT 
	gppd.idpago_detalle as iddetalle,
	gppd.idpago as idpago, 
	gppc.sede as sede
	FROM gp_pagos_detalle gppd
	INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
	WHERE gppd.esta_borrado=0 
	AND gppd.idpago_detalle='$id' AND gppd.estado='2' 
	ORDER BY gppd.fecha_pago DESC"); 

	$respuesta_cierre = mysqli_fetch_assoc($consulta_cierre);
	$var_idpago = $respuesta_cierre['idpago'];
	
		$consultar_pago = mysqli_query($conection, "
		SELECT 
		gppc.idpago as id,
		gppc.sede as Sede,                                    
		date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
		cd.texto1 as Moneda,
		gppc.tipo_cambio as TipoCambio,
		gppc.glosa as Glosa,
		gppc.pagado as TotalImporte,
		gppc.cuenta_contable as CuentaContable,
		cdx.codigo_sunat as Operacion,
		gppc.numero as Numero,
		gppc.accion as Accion,
		gppc.debe_haber as DebHab
		FROM gp_pagos_cabecera gppc
		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago
		INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
		INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppc.operacion AND cdx.codigo_tabla='_OPERACION_PAGO'
		WHERE gppc.esta_borrado=0 AND gppc.idpago='$var_idpago'
		ORDER BY gppc.fecha_pago");
		
		$result = mysqli_num_rows($consultar_pago);
		
		if ($result > 0 ){
			
			$respuesta_pago = mysqli_fetch_assoc($consultar_pago);
			$debe_haber = $respuesta_pago['DebHab'];

			if($debe_haber == "H"){	
			
				$Elim_DetIng= mysqli_query($conection,"DELETE FROM ingresos_detalle WHERE ingresos_detalle.identificador = '$var_idpago'");
				
				$Elim_CabIng= mysqli_query($conection,"DELETE FROM ingresos_cabecera WHERE ingresos_cabecera.identificador = '$var_idpago'");
				
				$data['status'] = "ok";
                $data['data'] = "Se elimino el registro de Pagos ingresos.";
				
			}else{								
				$Elim_DetEgr= mysqli_query($conection,"DELETE FROM egresos_detalle WHERE egresos_detalle.identificador = '$var_idpago'");
				
				$Elim_CabEgr = mysqli_query($conection,"DELETE FROM egresos_cabecera WHERE egresos_cabecera.identificador = '$var_idpago'");
				
				$data['status'] = "ok";
                $data['data'] = "Se elimino el registro de Pagos Egresos.";

			}		

		}
						
	header('Content-type: text/javascript');
	echo json_encode($data, JSON_PRETTY_PRINT);
}
/*********************** FUNCION ELIMINAR **********************/

if (isset($_POST['btnVerObservaciones'])) {

    $idRegistro = $_POST['idRegistro'];    

    $query = mysqli_query($conection, "SELECT 
        idpago_detalle as id,
        observacion as observacion,
        respuesta as respuesta,
        estado_observacion as estado
        FROM gp_pagos_detalle
        WHERE idpago_detalle='$idRegistro'");   

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnConformidadPago'])) {

    $_ID_PAGO_DETALLE = $_POST['_ID_PAGO_DETALLE'];    

    $query = mysqli_query($conection, "UPDATE 
        gp_pagos_detalle SET
        estado_observacion='2'
        WHERE idpago_detalle='$_ID_PAGO_DETALLE' AND estado_observacion='1'");   

    if ($query){        
        $data['status'] = 'ok';
        $data['data'] = "Usted ha confirmado estar de acuerdo con la solucion de lo observado. Por lo tanto ya puede proceder con el cierre del pago.";
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo dar conformidad a la observación, intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnObservarPago'])) {

    $_ID_PAGO_DETALLE = $_POST['_ID_PAGO_DETALLE'];
    $txtObservacion = $_POST['txtObservacion'];       

    if(!empty($txtObservacion)){
        $query = mysqli_query($conection, "UPDATE 
            gp_pagos_detalle gppd, gp_pagos_cabecera gppc, gp_cronograma gpcr SET
            gppd.estado_observacion='1',
            gppd.observacion='$txtObservacion',
            gppd.estado='1',
            gppc.visto_bueno='1',
            gppd.registro_observacion='$fecha.' '.$hora',
            gpcr.pago_cubierto='1'
            WHERE gpcr.id_venta=gppc.id_venta AND gpcr.correlativo=gppc.id_cronograma AND gppd.idpago=gppc.idpago AND gppd.id_venta=gppc.id_venta AND idpago_detalle='$_ID_PAGO_DETALLE'");  
        
        $consultar_idpago = mysqli_query($conection, "SELECT idpago as idpago, id_venta as idventa FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO_DETALLE'");
        $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
        $idpago = $respuesta_idpago['idpago'];
        $idventa = $respuesta_idpago['idventa'];

        $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='1' WHERE idpago='$idpago' AND id_venta='$idventa'");

        if ($query){        
            $data['status'] = 'ok';
            $data['data'] = "Usted ha observado el pago. Espere a la respuesta de solución por el área de cobranzas.";
        } else {
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo registrar la observación, intente nuevamente.';
        }
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'Completar el campo OBSERVACIÓN.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}




?>