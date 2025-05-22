
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

if(isset($_POST['VerificarPerfil'])){

        $txtUSR = isset($_POST['txtUSR']) ? $_POST['txtUSR'] : Null;
        $txtUSRr = trim($txtUSR);

        $usuar = decrypt($txtUSRr, "123");

        $consultar_idusuarioo = mysqli_query($conection, "SELECT idperfil as perfil  FROM usuario WHERE usuario='$usuar'");
        $respuesta_idusuarioo = mysqli_fetch_assoc($consultar_idusuarioo);
        $idperfil2 = $respuesta_idusuarioo['perfil'];

    if($consultar_idusuarioo->num_rows > 0){
                    
        $data['perfil'] = $idperfil2;
        $data['documento'] = $usuar;

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}

	if (isset($_POST['btnListarLotes'])) {

		$valor_documento = $_POST['txtFiltroDocumentoEC'];
		$valor_idlote = $_POST['idlote'];

		$condiciones = "gpv.esta_borrado = 0";

		// Filtros dinámicos
		if (!empty($valor_documento)) {
			$condiciones .= " AND dc.documento = '$valor_documento'";
		}

		if (!empty($valor_idlote)) {
			$condiciones .= " AND gpv.id_lote = '$valor_idlote'";
		}

		$query = mysqli_query($conection, "SELECT 
			gpv.id_lote as valor,
			CONCAT(tbl2.nombre, ' - ', tbl3.nombre, ' - ', tbl4.nombre, ' - ', tbl5.nombre) as texto
			FROM gp_venta gpv 
			INNER JOIN gp_lote AS tbl2 ON tbl2.idlote = gpv.id_lote 
			INNER JOIN gp_manzana AS tbl3 ON tbl3.idmanzana = tbl2.idmanzana
			INNER JOIN gp_zona AS tbl4 ON tbl4.idzona = tbl3.idzona
			INNER JOIN gp_proyecto AS tbl5 ON tbl5.idproyecto = tbl4.idproyecto
			INNER JOIN datos_cliente AS dc ON dc.id = gpv.id_cliente
			WHERE $condiciones
			GROUP BY gpv.id_lote");

		$dataList = [];
		array_push($dataList, [
			'valor' => '',
			'texto' => 'Seleccionar',
		]);

		if ($query->num_rows > 0) {
			while ($row = $query->fetch_assoc()) {
				array_push($dataList, [
					'valor' => $row['valor'],
					'texto' => $row['texto'],
				]);
			}
		}

		$data['data'] = $dataList;
		header('Content-type: text/javascript');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

if (isset($_POST['btnCuotas'])) {
    $iddlote = $_POST['idLote'];
    $documento = $_POST['documento'];
    
    $consultar_cliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$documento'");
    $respuesta_cliente = mysqli_fetch_assoc($consultar_cliente);
    $idcliente = $respuesta_cliente['id'];

    $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_lote='$iddlote' AND id_cliente='$idcliente'");
    $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
    $idventa = $respuesta_idventa['id'];

    $query = mysqli_query($conection, "SELECT 
        correlativo as valor,
        concat(item_letra,' (', concat(fecha_vencimiento),')') as texto
        FROM gp_cronograma
        WHERE id_venta='$idventa' AND estado IN ('1','3','2') AND pago_cubierto in ('0','1') AND esta_borrado='0'  ORDER BY correlativo ASC");
    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
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


if (isset($_POST['btnEliminarVoucherPago'])) {
    
    $idRegistro = $_POST['idRegistro'];
    
    $documento = $_POST['txtUSR'];
    $documento = decrypt($documento, "123");
    
    $consultar_ususario= mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$documento'");
    $respuesta_usuario = mysqli_fetch_assoc($consultar_ususario);
    $idusuario = $respuesta_usuario['id'];
    
    $actualiza = $fecha.' '.$hora;

    
    $query = mysqli_query($conection, "UPDATE gp_pagos_detalle SET voucher=null, actualizado='$actualiza', id_usuario_actualiza='$idusuario'
        WHERE idpago_detalle='$idRegistro'");

    if ($query) {

        $data['status'] = 'ok'; 
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo completar la operación';
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if(isset($_POST['ReturnListaActividades'])){

     $tipodoc = '1'; 
       $doc = '47869602';
       $nac = '200';


    $query = mysqli_query($conection, "SELECT 
        gpv.id_venta as id, 
        (select nombre as nom FROM gp_lote WHERE idlote= gpv.id_lote) as lote,
        (select area as area FROM gp_lote WHERE idlote= gpv.id_lote) as area,
        (select nombre_corto as nom FROM configuracion_detalle WHERE idconfig_detalle=gpv.tipo_casa) as tipocasa,
        (select nombre_corto as nom FROM configuracion_detalle WHERE idconfig_detalle=gpv.tipo_moneda) as tipomoneda,
        gpv.total as precioventa,
        tbl2.idlote as idLote,
        tbl3.idmanzana as idManzana,
        tbl4.idzona as idZona,
        tbl5.idproyecto as idProyecto,
        tbl2.valor_con_casa as valorLoteCasa,
        tbl2.valor_sin_casa as valorLoteSolo
        FROM gp_venta gpv
        INNER JOIN gp_lote AS tbl2 ON tbl2.idlote=gpv.id_lote 
        INNER JOIN gp_manzana AS tbl3 ON tbl3.idmanzana=tbl2.idmanzana
        INNER JOIN gp_zona AS tbl4 ON tbl4.idzona=tbl3.idzona
        INNER JOIN gp_proyecto AS tbl5 ON tbl5.idproyecto=tbl4.idproyecto
        WHERE gpv.id_cliente='88'
    ");

     

     if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row
            );}

        $data['data'] = $dataList;
       
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnCargarDatosAdicionales'])) {
   
   $iddlote = $_POST['idLote'];
   $cuota = $_POST['idCuota'];
   $documento = $_POST['documento'];
   
   $idventa="";
   $idcliente="";
   $monto=0;
   
   $consultar_cliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$documento'");
    $respuesta_cliente = mysqli_fetch_assoc($consultar_cliente);
    $idcliente = $respuesta_cliente['id'];
   
    //Consultar estado de la actividad
    $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_lote='$iddlote' AND id_cliente='$idcliente' AND esta_borrado='0'");
    $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
    $idventa = $respuesta_idventa['id'];
    
    //CONSULTAR ID PAGO


    $consulta_idpago = mysqli_query($conection, "SELECT gppc.idpago as idpago FROM gp_cronograma gpcr, gp_pagos_cabecera gppc WHERE gpcr.correlativo='$cuota' AND 
    gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpcr.id_venta AND gppc.id_venta='$idventa' AND gppc.esta_borrado='0'");
    $conat_idpago = mysqli_num_rows($consulta_idpago);

    if($conat_idpago>0){

        $respuesta_idpago = mysqli_fetch_assoc($consulta_idpago);
        $idpago = $respuesta_idpago['idpago'];
        
        //CONSULTAR PAGOS REALIZADOS SOBRE LA MISMA CUOTA
        $consultar_total_cuota = mysqli_query($conection, "SELECT 
        SUM(pagado) as importe
        FROM gp_pagos_detalle WHERE idpago='$idpago' AND esta_borrado='0'");
        $respuesta_total_cuota = mysqli_fetch_assoc($consultar_total_cuota);
        $importe = $respuesta_total_cuota['importe'];
    }

    $consulta_cuota = mysqli_query($conection, "SELECT monto_letra as monto FROM gp_cronograma WHERE id_venta='$idventa' AND correlativo='$cuota' ORDER BY item_letra ASC");
    $respuesta_cuota = mysqli_fetch_assoc($consulta_cuota);
    $monto = $respuesta_cuota['monto'];

    if($conat_idpago>0){
        $monto = $monto - $importe;
        if($monto<0){
            $monto=0;
        } 
    } 

    $moneda_pago = 0;
    $medio_pago = 0;
    $tipo_comprobante = 0;
    $agencia_bancaria = 0;

     $consultar_tipomoneda = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND nombre_corto='DOLARES'");
        $respuesta_tipomonedar = mysqli_fetch_assoc($consultar_tipomoneda);
        $moneda_pago = $respuesta_tipomonedar['id'];


        $consultar_mediopago = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_PAGO' AND nombre_corto='TRANSFERENCIA'");
        $respuesta_mediopagor = mysqli_fetch_assoc($consultar_mediopago);
        $medio_pago = $respuesta_mediopagor['id'];


        $consultar_tipocomprobante = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE' AND nombre_corto='VOUCHER DE PAGO'");
        $respuesta_tipocomprobanter = mysqli_fetch_assoc($consultar_tipocomprobante);
        $tipo_comprobante = $respuesta_tipocomprobanter['id'];


        $consultar_bancos = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE codigo_tabla='_BANCOS' AND nombre_corto='Bbva'");
        $respuesta_bancosr = mysqli_fetch_assoc($consultar_bancos);
        $agencia_bancaria = $respuesta_bancosr['id'];

     
       $query = mysqli_query($conection, "SELECT
        IF(cd.nombre_corto='SOLES', 'S/', IF(cd.nombre_corto='DOLARES','$','€')) as tm,
        gpv.id_venta as id
      FROM gp_venta gpv
      INNER JOIN configuracion_detalle as cd ON cd.idconfig_detalle=gpv.tipo_moneda
      WHERE gpv.id_venta='$idventa'");
	  
       if ($query->num_rows > 0) {
           $resultado = $query->fetch_assoc();
           $data['status'] = 'ok';
           $data['data'] = $resultado;
           $data['monto'] = number_format($monto, 2, '.', '');
           $data['moneda'] = $moneda_pago;
           $data['medio_pago'] = $medio_pago;
           $data['tipo_comprobante'] = $tipo_comprobante;
           $data['agencia_bancaria'] = $agencia_bancaria;
           $data['fecha_pago'] = $fecha_hoy;
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

/*******************VALIDAR TIPO MONEDA***********************************/
if (isset($_POST['ValidarTipoMoneda'])) {
    $TipoMoneda = $_POST['TipoMoneda'];

    $query = mysqli_query($conection, "SELECT nombre_corto as nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND idconfig_detalle='$TipoMoneda'");

    //CONSULTAR TIPO CAMBIO
    $consultar_tipocambio = mysqli_query($conection, "SELECT valor as valor FROM configuracion_valores WHERE descripcion='_TIPO_CAMBIO' AND  estado='1'");
    $respuesta_tipocambio = mysqli_fetch_assoc($consultar_tipocambio);
    $tipo_cambio = $respuesta_tipocambio['valor'];

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['tipo_cambio'] = $tipo_cambio;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnRegistrarPago'])){

    $txtidVenta = isset($_POST['txtidVenta']) ? $_POST['txtidVenta'] : Null;
    $txtidVentar = trim($txtidVenta);   

    $bxNroCuotas = isset($_POST['bxNroCuotas']) ? $_POST['bxNroCuotas'] : Null;
    $bxNroCuotasr = trim($bxNroCuotas);   

    $bxTipoMoneda2 = isset($_POST['bxTipoMoneda']) ? $_POST['bxTipoMoneda'] : Null;
    $bxTipoMoneda2r = trim($bxTipoMoneda2); 
    
    $txtTipoCambio = isset($_POST['txtTipoCambio']) ? $_POST['txtTipoCambio'] : Null;
    $txtTipoCambior = trim($txtTipoCambio); 

    $txtMontoPagado = isset($_POST['txtMontoPagado']) ? $_POST['txtMontoPagado'] : Null;
    $txtMontoPagador = trim($txtMontoPagado); 

    $bxMedioPago = isset($_POST['bxMedioPago']) ? $_POST['bxMedioPago'] : Null;
    $bxMedioPagor = trim($bxMedioPago); 

    $bxTipoComprobante = isset($_POST['bxTipoComprobante']) ? $_POST['bxTipoComprobante'] : Null;
    $bxTipoComprobanter = trim($bxTipoComprobante); 

    $bxAgenciaBancaria = isset($_POST['bxAgenciaBancaria']) ? $_POST['bxAgenciaBancaria'] : Null;
    $bxAgenciaBancariar = trim($bxAgenciaBancaria); 

    $txtNumeroOperacion = isset($_POST['txtNumeroOperacion']) ? $_POST['txtNumeroOperacion'] : Null;
    $txtNumeroOperacionr = trim($txtNumeroOperacion); 

    $txtFechaPago = isset($_POST['txtFechaPago']) ? $_POST['txtFechaPago'] : Null;
    $txtFechaPagor = trim($txtFechaPago); 

    $constancia = $_POST['constancia'];

    $txtMontoPagar = isset($_POST['txtMontoPagar']) ? $_POST['txtMontoPagar'] : Null;
    $txtMontoPagarr = trim($txtMontoPagar); 
    
    $bxFlujoCaja = isset($_POST['bxFlujoCaja']) ? $_POST['bxFlujoCaja'] : Null;
    $bxFlujoCajar = trim($bxFlujoCaja); 
    
    $txtUSR = $_POST['txtUSR'];
    $txtUSR = decrypt($txtUSR, "123");
    
    //CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	$actualiza = $fecha.' '.$hora;

    if(!empty($constancia)){

        if(!empty($txtMontoPagador) && !empty($bxMedioPagor) && !empty($bxTipoComprobanter) && !empty($bxAgenciaBancariar) && !empty($txtNumeroOperacionr) && !empty($txtFechaPagor)){

            if($txtMontoPagarr>0){

                $consultar_id_pago = mysqli_query($conection, "SELECT idpago as id, importe_pago FROM gp_pagos_cabecera WHERE id_venta='$txtidVentar' AND id_cronograma='$bxNroCuotasr' AND esta_borrado='0'"); 
                $contar_id_pago = mysqli_num_rows($consultar_id_pago);
                
                $codigo_operacion="";
                $consultar_idoperacion = mysqli_query($conection, "SELECT texto1 as codigo FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_PAGO' AND idconfig_detalle='$bxMedioPagor'");
                $contar_respuesta = mysqli_num_rows($consultar_idoperacion);
                if($contar_respuesta>0){
                    $respuesta_idoperacion = mysqli_fetch_assoc($consultar_idoperacion);
                    $codigo_operacion = $respuesta_idoperacion['codigo'];
                }

                //CONSULTA SI EXISTE REGISTRO DE PAGOS EN LA CABECERA EN RELACION AL PAGO INGRESADO
                if($contar_id_pago>0){ //SI EXISTE

                    //TOTAL DE LA CABECERA
                    $consultar_id_pago = mysqli_query($conection, "SELECT idpago as id, importe_pago as importe, pagado as pagado FROM gp_pagos_cabecera WHERE id_venta='$txtidVentar' AND id_cronograma='$bxNroCuotasr' AND esta_borrado='0'"); 
                    $respuesta_id_pago = mysqli_fetch_assoc($consultar_id_pago);
                    $idpago = $respuesta_id_pago['id'];
                    $total_cabecera_importe = $respuesta_id_pago['importe'];
                    $total_cabecera = $respuesta_id_pago['pagado'];

                    //TOTAL DE LA LETRA SEGUN CRONOGRAMA
                    $consultar_monto_letra = mysqli_query($conection, "SELECT monto_letra as total FROM gp_cronograma WHERE correlativo='$bxNroCuotasr' AND id_venta='$txtidVentar'");
                    $respuesta_monto_letra = mysqli_fetch_assoc($consultar_monto_letra);
                    $total_letra = $respuesta_monto_letra['total'];

                    //COMPARAMOS TOTAL CABECERA Y TOTAL CRONOGRAMA
                    if($total_cabecera < $total_letra){ //EXISTE UN SALDO PENDIENTE

                        $diferencia_total = 0;
                        $diferencia_total = $total_letra - $total_cabecera;

                        $dato_soles=0; // Monto si pago en Soles
                        $dato_dolares=0; // Monto si pago en Dolares
                        $dato_importe=0; // Importe pagado por el cliente (en soles o dolares)
                        $dato_pagado=0; //Total pagado por cliente (convertido a dolares)
                        
                        //CONSULTA TEXTO DE TIPO MONEDA SELECCIONADA
                        $consulta_tipoMod = mysqli_query($conection, "SELECT texto1 as sigla FROM configuracion_detalle WHERE idconfig_detalle='$bxTipoMoneda2r' AND codigo_tabla='_TIPO_MONEDA'");
                        $respuesta_tipoMod = mysqli_fetch_assoc($consulta_tipoMod);
                        
                        $sigla = $respuesta_tipoMod['sigla'];
                        
                        //CONSULTA SI CLIENTE PAGO EN SOLES O DOLARES
                        
                        if($sigla == "PEN"){
                            $dato_soles= $txtMontoPagador;
                            $dato_dolares=0;
                            $dato_importe=$txtMontoPagador;
                            $dato_pagado =  ($dato_importe/$txtTipoCambior);
                        }else{
                            $dato_soles= 0;
                            $dato_dolares=$txtMontoPagador;
                            $dato_importe=$txtMontoPagador;
                            $dato_pagado =  $dato_importe;
                        }
                        
                        /*if($dato_pagado <= $diferencia_total){*/
                            
                            
                            $insertar_pago_detalle = mysqli_query($conection,"INSERT INTO gp_pagos_detalle(id_venta, idpago, moneda_pago, tipo_cambio, monto_soles, monto_dolares,importe_pago, medio_pago, tipo_comprobante, agencia_bancaria, nro_operacion, fecha_pago, debe_haber, estado,pagado, id_usuario_crea) 
                            VALUES ('$txtidVentar','$idpago','$bxTipoMoneda2r', '$txtTipoCambior', '$dato_soles', '$dato_dolares', '$dato_importe','$bxMedioPagor','$bxTipoComprobanter','$bxAgenciaBancariar','$txtNumeroOperacionr','$txtFechaPagor','H','1','$dato_pagado','$idusuario')");                      
    
                            //CONSULTAR TOTAL DETALLE
                            $consultar_importe_cab = mysqli_query($conection, "SELECT ROUND(SUM(pagado),2) as pagado FROM gp_pagos_detalle WHERE idpago='$idpago' AND esta_borrado='0'");
                            $respuesta_importe_cab = mysqli_fetch_assoc($consultar_importe_cab);
                            $total_cabecera = $respuesta_importe_cab['pagado'];
    
                            $actualizar_pago_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$total_cabecera', pagado='$total_cabecera', estado='2', visto_bueno='1', operacion='$codigo_operacion', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                            $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET estado='2', pago_cubierto='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE correlativo='$bxNroCuotasr' AND id_venta='$txtidVentar'");
                            
    
                            $consultar_iddetalle = mysqli_query($conection, "SELECT idpago_detalle AS id FROM gp_pagos_detalle WHERE idpago='$idpago' AND id_venta='$txtidVentar' AND nro_operacion='$txtNumeroOperacionr' AND fecha_pago='$txtFechaPagor' AND esta_borrado='0'");
                            $respuesta_iddetalle = mysqli_fetch_assoc($consultar_iddetalle);
                            $id_detalle = $respuesta_iddetalle['id'];
    
                            $path = $constancia;
                            $file = new SplFileInfo($path);
                            $extension  = $file->getExtension();
                            $desc_codigo="voucher-";
                            $name_file = "voucher";
                            if(!empty($constancia)){
                                $name_file = $desc_codigo.$id_detalle.".".$extension;
                            }
    
                            $insertar_archivo = mysqli_query($conection, "UPDATE gp_pagos_detalle SET voucher='$name_file' WHERE idpago_detalle='$id_detalle'");
    
                            $data['status'] = "ok";
                            $data['data'] = "Se registro el pago.";
                            $data['nombre'] = $name_file;
                            
                       /* }else{
                            $data['status'] = "bad";
                            $data['data'] = "El monto ingresado excede al saldo pendiente para la letra seleccionada. Gracias";
                        }*/

                    }else{
                        $data['status'] = "bad";
                        $data['data'] = "Ya se pagó la cuota y esta pendiente de validacion por el area de cobranzas. Gracias";
                    }


                }else{   // NO EXISTEN REGISTROS EN LA CABECERA EN RELACION A LA LETRA SELECCIONADA
                        $dato_soles=0;
                        $dato_dolares=0;
                        $dato_importe=0;
                    
                    if(!empty($txtTipoCambior) && $txtTipoCambio!='0.00'){
                        $dato_soles= $txtMontoPagador;
                        $dato_dolares=0;
                        $dato_importe=$txtMontoPagador;
                    }else{
                        $dato_soles= 0;
                        $dato_dolares=$txtMontoPagador;
                        $dato_importe=$txtMontoPagador;
                    }
                    
                        $dato_pagado = "0.00";
                        if($bxTipoMoneda2r=="15381"){ //si es igual a tipo moneda : dolar
                           $dato_pagado =  $dato_importe;
                        }else{
                           $dato_pagado =  ($dato_importe/$txtTipoCambior);
                        }
                        
                        $consultar_tipo_pago = mysqli_query($conection,"SELECT es_cuota_inicial as valor FROM gp_cronograma WHERE id_venta='$txtidVentar' AND correlativo='$bxNroCuotasr'");
                        $respuesta_tipo_pago = mysqli_fetch_assoc($consultar_tipo_pago);
                        $valor_tipo_pago = $respuesta_tipo_pago['valor'];
                        
                        $tipo_pago = "";
                        if($valor_tipo_pago == "1"){
                            $tipo_pago = 1;
                        }else{
                            $tipo_pago = 2;
                        }
                        
                        $actualizar_actividad = mysqli_query($conection,"INSERT INTO gp_pagos_cabecera(id_venta, id_cronograma, moneda_pago, tipo_cambio,importe_pago, medio_pago, tipo_comprobante, agencia_bancaria, operacion, numero, fecha_pago, glosa, sede, pagado, tipo_pago, flujo_caja, id_usuario_crea) 
                        VALUES ('$txtidVentar','$bxNroCuotasr','$bxTipoMoneda2r', '$txtTipoCambior','$txtMontoPagador','$bxMedioPagor','$bxTipoComprobanter','$bxAgenciaBancariar', '$codigo_operacion','$txtNumeroOperacionr','$txtFechaPagor','COBRANZA AL CLIENTE','00001','$dato_pagado','$tipo_pago','$bxFlujoCajar','$idusuario')");
                        
                        $consultar_id_pago = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$txtidVentar' AND id_cronograma='$bxNroCuotasr' AND estado='1' AND esta_borrado='0'"); 
                        $respuesta_id_pago = mysqli_fetch_assoc($consultar_id_pago);
                        $idpago = $respuesta_id_pago['id'];
                        

                        $actualizar_actividad = mysqli_query($conection,"INSERT INTO gp_pagos_detalle(id_venta, idpago, moneda_pago, tipo_cambio, monto_soles, monto_dolares,importe_pago, medio_pago, tipo_comprobante, agencia_bancaria, nro_operacion, fecha_pago, debe_haber, estado,pagado,id_usuario_crea) 
                        VALUES ('$txtidVentar','$idpago','$bxTipoMoneda2r', '$txtTipoCambior', '$dato_soles', '$dato_dolares', '$dato_importe','$bxMedioPagor','$bxTipoComprobanter','$bxAgenciaBancariar','$txtNumeroOperacionr','$txtFechaPagor','H','1','$dato_pagado','$idusuario')"); 
            
                      
                        $consultar_importe_cab = mysqli_query($conection, "SELECT ROUND(SUM(pagado),2) as importe FROM gp_pagos_detalle WHERE idpago='$idpago' AND esta_borrado='0'");
                        $respuesta_importe_cab = mysqli_fetch_assoc($consultar_importe_cab);
                        $total_cabecera = $respuesta_importe_cab['importe'];

                        $consultar_monto_letra = mysqli_query($conection, "SELECT ROUND(monto_letra,2) as total FROM gp_cronograma WHERE correlativo='$bxNroCuotasr' AND id_venta='$txtidVentar'");
                        $respuesta_monto_letra = mysqli_fetch_assoc($consultar_monto_letra);
                        $total_letra = $respuesta_monto_letra['total'];

                        if($total_cabecera == $total_letra){
                            $actualizar_pago_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$total_cabecera', estado='2', visto_bueno='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago' AND esta_borrado='0'");
                            $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET estado='2', pago_cubierto='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE correlativo='$bxNroCuotasr' AND id_venta='$txtidVentar'");
                       }else{
                           $actualizar_pago_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$total_cabecera', estado='2', visto_bueno='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago' AND esta_borrado='0'");
                            $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET estado='2', pago_cubierto='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE correlativo='$bxNroCuotasr' AND id_venta='$txtidVentar'");
                       }
                       
                        //ASIGNAR GLOSA
                        $glosa = "";
                        $consultar_glosa = mysqli_query($conection, "SELECT 
                        if(gppc.tipo_pago='1', concat('PAGO INICIAL - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2),' LT ',SUBSTRING(gpl.nombre,6,2)),concat('PAGO LETRA - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2), ' LT ',SUBSTRING(gpl.nombre,6,2),' - Letra ',gpcr.item_letra,'/',(select count(id) from gp_cronograma where id_venta=gpv.id_venta and item_letra not in ('C.I.','ADENDA','AMORTIZADO')))) as dato
                        FROM gp_pagos_cabecera gppc
                        INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
                        INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gpv.id_venta
                        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
                        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                        WHERE gppc.id_venta='$txtidVentar' AND gppc.id_cronograma='$bxNroCuotasr' AND gppc.esta_borrado='0'");
                        $respuesta = mysqli_fetch_assoc($consultar_glosa);
                        $glosa = $respuesta['dato'];
                        
                        if(!empty($glosa)){
                            $actualizar_cabecera = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET glosa='$glosa' WHERE id_venta='$txtidVentar' AND id_cronograma='$bxNroCuotasr'");
                        }

                        $consultar_iddetalle = mysqli_query($conection, "SELECT idpago_detalle AS id FROM gp_pagos_detalle WHERE idpago='$idpago' AND id_venta='$txtidVentar' AND pagado='$dato_pagado' AND nro_operacion='$txtNumeroOperacionr' AND fecha_pago='$txtFechaPagor' AND esta_borrado='0'");
                        $respuesta_iddetalle = mysqli_fetch_assoc($consultar_iddetalle);
                        $id_detalle = $respuesta_iddetalle['id'];

                        $path = $constancia;
                        $file = new SplFileInfo($path);
                        $extension  = $file->getExtension();
                        $desc_codigo="voucher-";
                        $name_file = "voucher";
                        if(!empty($constancia)){
                            $name_file = $desc_codigo.$id_detalle.".".$extension;
                        }

                        $insertar_archivo = mysqli_query($conection, "UPDATE gp_pagos_detalle SET voucher='$name_file' WHERE idpago_detalle='$id_detalle'");

                    $data['status'] = "ok";
                    $data['data'] = "Se registro el pago.";
                    $data['nombre'] = $name_file;

                }
                
            }else{
              $data['status'] = "bad";
              $data['data'] = "El pago de la letra ya ha sido completado. Aun esta pendiente su validacion por el area de cobranzas. Gracias";
            }

        }else{
          $data['status'] = "bad";
          $data['data'] = "Completar todos los campos.";
        }

    }else{

        $data['status'] = "bad";
        $data['data'] = "Cargar constancia de pago.";
    }


    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

if (isset($_POST['btnListarTiposActividad'])) {

    $query = mysqli_query($conection, "SELECT idgestion as valor, nombre as texto FROM tipos WHERE estado='Activo' AND (area='$area' OR area='Todos') AND (categoria='ACTIVIDAD'  OR categoria='Todos')  ORDER BY nombre");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
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

if(isset($_POST['btnIrPagosRealizados'])){

    $HOST = $NAME_SERVER;
    $url = $_POST['txtuser'];

    if(!empty($HOST)){

        $data['status'] = "ok";
        $data['URL'] = $HOST."views/M04_Cobranzas/M04SM01_Cobranzas/M04SM03_PagosRealizados.php?Vsr=".$url;

    }else{
        $data['status'] = "bad";
        $data['data'] = "Error";
    }

    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

if(isset($_POST['btnIrPagos'])){

    $ruta = $_POST['txtUSR'];
    $HOST = $NAME_SERVER;
    if(!empty($HOST)){
        $data['status'] = "ok";
        $data['URL'] = $HOST."views/M04_Cobranzas/M04SM01_Cobranzas/M04SM01_NuevoPago.php?Vsr=".$ruta;
    }else{
        $data['status'] = "bad";
        $data['data'] = "Error";
    }

    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);

}

//LISTAR PAGOS
if(isset($_POST['ReturnListaPagos'])){


    $query = mysqli_query($conection, "SELECT 
        gpp.idpago as id, 
        gpl.nombre as lote,
        concat(gpl.nombre,' - ',concat(gpm.nombre,' - ',gpz.nombre)) as datos,
        gpc.fecha_vencimiento as fecha_pago,
        gpc.item_letra as nro_cuota,
        (SELECT texto1 FROM configuracion_detalle WHERE idconfig_detalle=gpp.moneda_pago) as moneda,
        gpp.importe_pago as importe,
        (SELECT nombre_corto FROM configuracion_detalle WHERE idconfig_detalle=gpp.medio_pago) as medio_pago,
        (SELECT nombre_corto FROM configuracion_detalle WHERE idconfig_detalle=gpp.tipo_comprobante) as tipo_comprobante,
        gpp.operacion as nro_operacion
        FROM gp_pagos_cabecera gpp
        INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpp.idventa
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN gp_estado_cuenta AS gpc ON gpc.id=gpp.idcronograma
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        WHERE gpp.idventa=29 ORDER BY gpp.fecha_pago DESC
    ");

     if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, [
            
                'id' => $row['id'],
                'lote' => $row['lote'],
                'datos' => $row['datos'],
                'fecha_pago' => $row['fecha_pago'],
                'nro_cuota' => $row['nro_cuota'],
                'moneda' => $row['moneda'],
                'importe' => $row['importe'],
                'medio_pago' => $row['medio_pago'],
                'tipo_comprobante' => $row['tipo_comprobante'],
                'nro_operacion' => $row['nro_operacion']
             ]);
        }

        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnConsultarTipoMoneda'])) {

    $idTipoMoneda = $_POST['idTipoMoneda'];

    $query = mysqli_query($conection, "SELECT 
        idconfig_detalle as id,
        nombre_corto as dato,
        if(nombre_corto='SOLES','S/.',if(nombre_corto='DOLARES','$','€')) as moneda
        FROM configuracion_detalle
        WHERE codigo_tabla='_TIPO_MONEDA' AND idconfig_detalle='$idTipoMoneda'");
    $respuesta = mysqli_fetch_assoc($query);


    $consultar_tp = mysqli_query($conection, "SELECT valorp as valor FROM parametros WHERE acciones='tipo_cambio'");
    $respuesta_tp = mysqli_fetch_assoc($consultar_tp);

 
    if ($respuesta['dato']=='SOLES') {

        $data['esatus'] = 'ok';
        $data['simbolo'] = $respuesta['moneda'];
        $data['tipo_cambio'] = $respuesta_tp['valor'];          
        
    } else {
        $data['estatus'] = 'bad';
        $data['simbolo'] = $respuesta['moneda'];
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['ReturnListaPagos2'])){

    
    $txtFiltroDatoCliente = isset($_POST['txtFiltroDatoCliente']) ? $_POST['txtFiltroDatoCliente'] : Null;
    $txtFiltroDatoClienter = trim($txtFiltroDatoCliente);
    
    $txtFiltroFecIniPago = isset($_POST['txtFiltroFecIniPago']) ? $_POST['txtFiltroFecIniPago'] : Null;
    $txtFiltroFecIniPagor = trim($txtFiltroFecIniPago);
    
    $txtFiltroFecFinPago = isset($_POST['txtFiltroFecFinPago']) ? $_POST['txtFiltroFecFinPago'] : Null;
    $txtFiltroFecFinPagor = trim($txtFiltroFecFinPago);
    
    $cbxFiltroEstadoPago = isset($_POST['cbxFiltroEstadoPago']) ? $_POST['cbxFiltroEstadoPago'] : Null;
    $cbxFiltroEstadoPagor = trim($cbxFiltroEstadoPago);
    
    $cbxFiltroEstadoPagoFac = isset($_POST['cbxFiltroEstadoPagoFac']) ? $_POST['cbxFiltroEstadoPagoFac'] : Null;
    $cbxFiltroEstadoPagoFacr = trim($cbxFiltroEstadoPagoFac);
    
    $cbxFiltroBancosPago = isset($_POST['cbxFiltroBancosPago']) ? $_POST['cbxFiltroBancosPago'] : Null;
    $cbxFiltroBancosPagor = trim($cbxFiltroBancosPago);
    
    $cbxMoraPago = isset($_POST['cbxMoraPago']) ? $_POST['cbxMoraPago'] : Null;
    $cbxMoraPagor = trim($cbxMoraPago);
    
    $bxFiltroProyectoEC = isset($_POST['bxFiltroProyectoEC']) ? $_POST['bxFiltroProyectoEC'] : Null;
    $bxFiltroProyectoECr = trim($bxFiltroProyectoEC);
    
    $bxFiltroZonaEC = isset($_POST['bxFiltroZonaEC']) ? $_POST['bxFiltroZonaEC'] : Null;
    $bxFiltroZonaECr = trim($bxFiltroZonaEC);
    
    $bxFiltroManzanaEC = isset($_POST['bxFiltroManzanaEC']) ? $_POST['bxFiltroManzanaEC'] : Null;
    $bxFiltroManzanaECr = trim($bxFiltroManzanaEC);
    
    $bxFiltroLoteEC = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
    $bxFiltroLoteECr = trim($bxFiltroLoteEC);
    
    $query_documento = "";
    $query_fecha = "";
    $query_estado = "";
    $query_estado_fac = "";
    $query_bancos = "";
    $query_mora = "";
    $query_proyecto = "";
    $query_zona = "";
    $query_manzana = "";
    $query_lote = "";
    $idventa = "0";
    
    if(!empty($txtFiltroDatoClienter)){
        $query_documento = "AND (dc.documento='$txtFiltroDatoClienter' OR dc.nombres like concat('%','$txtFiltroDatoClienter','%') OR dc.apellido_paterno like concat('%','$txtFiltroDatoClienter','%') OR dc.apellido_materno like concat('%','$txtFiltroDatoClienter','%'))";
    }
   
    if(!empty($txtFiltroFecIniPagor) AND !empty($txtFiltroFecFinPagor)){
        $query_fecha = "AND gppd.fecha_pago BETWEEN '$txtFiltroFecIniPagor' AND '$txtFiltroFecFinPagor'";
    }else{
        if(!empty($txtFiltroFecIniPagor) AND empty($txtFiltroFecFinPagor)){
           $query_fecha = "AND gppd.fecha_pago='$txtFiltroFecIniPagor'";
        }
    }
    
    if(!empty($cbxFiltroEstadoPagor)){
        $query_estado = "AND gppd.estado='$cbxFiltroEstadoPagor'";
    }
    
    if(!empty($cbxFiltroEstadoPagoFacr)){
        
        if($cbxFiltroEstadoPagoFacr == "2"){
        
            $query_estado_fac = "AND gppd.estado_cierre in (1,2)";
            
        }else{
            $query_estado_fac = "AND gppd.estado_cierre='$cbxFiltroEstadoPagoFacr'";
        }
    }
    
    
    if(!empty($cbxFiltroBancosPagor)){
        $query_bancos = "AND gppd.agencia_bancaria='$cbxFiltroBancosPagor'";
    }
    
    if($cbxMoraPagor=="Si"){
        $query_mora = "AND gppc.fecha_pago>gpcr.fecha_vencimiento";
    }
    
    if(!empty($bxFiltroProyectoECr)){
        $query_proyecto = "AND gpy.idproyecto='$bxFiltroProyectoECr'";
    }
   
    if(!empty($bxFiltroZonaECr)){
        $query_zona = "AND gpz.idzona='$bxFiltroZonaECr'";
    }
    
    if(!empty($bxFiltroManzanaECr)){
        $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaECr'";
    }
    
    if(!empty($bxFiltroLoteECr)){
        $query_lote = "AND gpl.idlote='$bxFiltroLoteECr'";
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
            gppd.estado as estado,
            cddxx.nombre_corto as estado_pago,
            cddxx.texto1 as color_estado_pago,
            cdddx.nombre_corto as banco,
            cdx.nombre_corto as medio_pago,
            cddx.nombre_corto as tipo_comprobante,
            gppd.nro_operacion as nro_operacion,
            gppd.voucher as voucher,
            gppd.estado_cierre as estado_cierre,
            if(gppd.estado='1','PENDIENTE',if(gppd.estado='2','VALIDADO','RECHAZADO')) AS estado_validacion
            FROM gp_pagos_detalle gppd
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
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.agencia_bancaria AND cdddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppd.moneda_pago AND cddddx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddxx ON cddxx.codigo_item=gppd.estado_pago AND cddxx.codigo_tabla='_ESTADO_PAGO_LETRA'
            WHERE gppd.esta_borrado=0
            $query_documento
            $query_fecha
            $query_estado
            $query_estado_fac
            $query_bancos
            $query_mora
            $query_proyecto
            $query_zona
            $query_manzana
            $query_lote
            GROUP BY gppd.idpago_detalle
            ORDER BY gppd.estado ASC, gppd.estado_cierre ASC, gppd.fecha_pago DESC, gppd.creado DESC"); 

     
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
                'estado' => $row['estado'],
                'estado_pago' => $row['estado_pago'],
                'color_estado_pago' => $row['color_estado_pago'],
                'banco' => $row['banco'],
                'medio_pago' => $row['medio_pago'],
                'voucher' => $row['voucher'],
                'boleta' => 'documento.pdf',
                'tipo_comprobante' => $row['tipo_comprobante'],
                'nro_operacion' => $row['nro_operacion'],
                'estado_cierre' => $row['estado_cierre'],
                'estado_validacion' => $row['estado_validacion']
            ]);
        }
            
       $data['data'] = $dataList;
       $data['query'] = "SELECT
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
            gppd.estado as estado,
            cddxx.nombre_corto as estado_pago,
            cddxx.texto1 as color_estado_pago,
            cdddx.nombre_corto as banco,
            cdx.nombre_corto as medio_pago,
            cddx.nombre_corto as tipo_comprobante,
            gppd.nro_operacion as nro_operacion,
            gppd.voucher as voucher,
            gppd.estado_cierre as estado_cierre,
            if(gppd.estado='1','PENDIENTE',if(gppd.estado='2','VALIDADO','RECHAZADO')) AS estado_validacion
            FROM gp_pagos_detalle gppd
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
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.agencia_bancaria AND cdddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppd.moneda_pago AND cddddx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddxx ON cddxx.codigo_item=gppd.estado_pago AND cddxx.codigo_tabla='_ESTADO_PAGO_LETRA'
            WHERE gppd.esta_borrado=0 AND gpv.devolucion!='1'
            $query_documento
            $query_fecha
            $query_estado
            $query_estado_fac
            $query_bancos
            $query_mora
            $query_proyecto
            $query_zona
            $query_manzana
            $query_lote
            ORDER BY gppd.estado_cierre ASC, gppd.fecha_pago ASC";
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
    $data['id'] = $idRegistro;
    
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

if(isset($_POST['btnValidarFechas2'])){
    
    $fecha = new DateTime();
    $fecha->modify('first day of this month');
    $primer_dia = $fecha->format('Y-m-d');
    
    $fecha = new DateTime();
    $fecha->modify('last day of this month');
    $ultimo_dia = $fecha->format('Y-m-d'); 
    
    $data['status'] = 'ok';
    $data['primero'] = "2022-01-01";
    $data['ultimo'] = $ultimo_dia;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['CargarDatos'])){

        $txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoEC']) ? $_POST['txtFiltroDocumentoEC'] : Null;
        $txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
        
        $bxFiltroLoteEC = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
        $bxFiltroLoteECr = trim($bxFiltroLoteEC);
        
        $query_documento = "";
        $query_lote   = "";
         
        if(empty($txtFiltroDocumentoECr)){
            if(empty($bxFiltroLoteECr)){
                $query_documento = "";
                $query_lote   = "AND gpl.idlote='0'";
            }else{          
                $query_lote = "AND gpl.idlote='$bxFiltroLoteECr'";
                $query_documento = "";
            }
        }else{      
            if(empty($bxFiltroLoteECr)){
                $query_lote   = "";
                $query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
            }else{
                $query_lote = "AND gpl.idlote='$bxFiltroLoteECr'"; 
                $query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
            }
        }


        $query = mysqli_query($conection,"SELECT 
            dc.documento as documento,
            dc.nombres as nombres,
            dc.apellido_paterno as apellido_paterno,
            dc.apellido_materno as apellido_materno
            FROM datos_cliente dc
            INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            WHERE dc.esta_borrado=0
            $query_documento
            $query_lote
            "); 
        $row = mysqli_fetch_assoc($query);

        
    if($query->num_rows > 0){
                    
        $data['documento'] = $row['documento'];
        $data['nombres'] = $row['nombres'];
        $data['apellido_paterno'] = $row['apellido_paterno'];
        $data['apellido_materno'] = $row['apellido_materno'];

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}


if(isset($_POST['ReturnListaPagos3'])){

    
    $txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoEC']) ? $_POST['txtFiltroDocumentoEC'] : Null;
    $txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
    
    $bxFiltroLoteEC = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
    $bxFiltroLoteECr = trim($bxFiltroLoteEC);
    
    $bxFiltroEV= isset($_POST['bxFiltroEV']) ? $_POST['bxFiltroEV'] : Null;
    $bxFiltroEVr = trim($bxFiltroEV);
    
    $bxFiltroEstadoEC= isset($_POST['bxFiltroEstadoEC']) ? $_POST['bxFiltroEstadoEC'] : Null;
    $bxFiltroEstadoECr = trim($bxFiltroEstadoEC);
    
    $txtFecIniFiltro= isset($_POST['txtFecIniFiltro']) ? $_POST['txtFecIniFiltro'] : Null;
    $txtFecIniFiltror = trim($txtFecIniFiltro);
    
    $txtFecFinFiltro= isset($_POST['txtFecFinFiltro']) ? $_POST['txtFecFinFiltro'] : Null;
    $txtFecFinFiltror = trim($txtFecFinFiltro);

    $query_documento = "";
    $query_lote = "";
    $query_estado = "";
    $idventa = "0";
    $query_vistoBueno = "";
    $query_venta = "";
    $query_estado_pago= "";
    $query_fecha = "";
    
    if(!empty($bxFiltroEstadoECr)){
        if($bxFiltroEstadoECr=="todos"){
            $query_estado_pago = "AND gpcr.estado IN ('2','4')";
        }else{
            $query_estado_pago = "AND gpcr.estado='$bxFiltroEstadoECr'";
        }        
    }
    
    if(!empty($bxFiltroEVr)){
        if($bxFiltroEVr=="todos"){
            $query_vistoBueno = "";
        }else{
            $query_vistoBueno = "AND gppc.visto_bueno='$bxFiltroEVr'";
        }
    }else{
        $query_vistoBueno = "AND gppc.visto_bueno IN ('1','0')";
    }
    
    if(empty($txtFiltroDocumentoECr)){
        if(empty($bxFiltroLoteECr)){
            $query_documento = "";
            $query_lote   = "AND gpv.id_lote='0'";
        }else{          
            $query_lote = "AND gpv.id_lote='$bxFiltroLoteECr'";
            $query_documento = "";
            
            $consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteECr'");
            $respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
            $idventa = $respuesta_idventa['id_venta'];
            
            $query_venta = "AND gpcr.id_venta='$idventa'";
        }
    }else{      
        if(empty($bxFiltroLoteECr)){
            
            $query_lote   = "";
            $query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
            
            $consulta_idventa = mysqli_query($conection, "SELECT gpv.id_venta as id_venta FROM gp_venta gpv, datos_cliente dc WHERE gpv.id_cliente=dc.id AND dc.documento='$txtFiltroDocumentoECr'");
            $respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
            $idventa = $respuesta_idventa['id_venta'];
            $query_venta = "AND gpcr.id_venta='$idventa'";
            
        }else{
            $query_lote = "AND gpv.id_lote='$bxFiltroLoteECr'"; 
            $query_documento = "AND dc.documento='$txtFiltroDocumentoECr'"; 
            
            $consulta_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_lote='$bxFiltroLoteECr'");
            $respuesta_idventa = mysqli_fetch_assoc($consulta_idventa);
            $idventa = $respuesta_idventa['id_venta'];
            $query_venta = "AND gpcr.id_venta='$idventa'";
        }
    }
    
    if(!empty($txtFecIniFiltror) && !empty($txtFecFinFiltror)){
        $query_fecha = "AND gppc.fecha_pago BETWEEN '$txtFecIniFiltror' AND '$txtFecFinFiltror'";    
    }else{
        if(!empty($txtFecIniFiltror) && empty($txtFecFinFiltror)){
            $query_fecha = "AND gppc.fecha_pago='$txtFecIniFiltror'"; 
        }
    }

        $query = mysqli_query($conection,"SELECT 
            gppc.idpago as id,
            concat(dc.documento,' - ',dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as nom_cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as nom_lote,
            date_format(gppc.fecha_pago, '%d/%m/%Y') as fecha,
            gpcr.item_letra as letra,
            cdx.texto1 as tipo_moneda,
            format(gppc.importe_pago,2) as monto,
            gppc.estado as estado,
            cddx.nombre_corto as descEstado,
            cddx.texto1 as colorEstado,
            gppc.visto_bueno as visto_bueno,
            cdddx.nombre_corto as descVisto_bueno,
            cdddx.texto1 as colorVisto_bueno,
            cddddx.nombre_corto as agencia_bancaria,
            cdddddx.nombre_corto as medio_pago,
            cddddddx.nombre_corto as tipo_comprobante,
            gppc.numero as nro_operacion,
            date_format(gpcr.fecha_vencimiento, '%d/%m/%Y') as fechaVencimiento,
            if(gpcr.fecha_vencimiento<gppc.fecha_pago, concat('-',TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, gppc.fecha_pago)),'0') as mora,
            concat(cdx.texto1,' - ',format(gppc.importe_pago,2)) as importe_pago
            FROM gp_pagos_cabecera gppc
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppc.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gppc.estado AND cddx.codigo_tabla='_ESTADO_EC'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.codigo_item=gppc.visto_bueno AND  cdddx.codigo_tabla='_ESTADO_VP'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppc.agencia_bancaria AND cddddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cdddddx ON cdddddx.idconfig_detalle=gppc.medio_pago AND cdddddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN configuracion_detalle AS cddddddx ON cddddddx.idconfig_detalle=gppc.tipo_comprobante AND cddddddx.codigo_tabla='_TIPO_COMPROBANTE'
            WHERE gppc.esta_borrado=0
            AND (select count(idpago_detalle) from gp_pagos_detalle where idpago=gppc.idpago AND estado='1')>0
            $query_vistoBueno
            $query_venta
            $query_estado_pago
            $query_fecha"); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'cliente' => $row['cliente'],
                'fechaVencimiento' => $row['fechaVencimiento'],
                'mora' => $row['mora'],
                'lote' => $row['lote'],
                'importe_pago' => $row['importe_pago'],
                'nom_lote' => $row['nom_lote'],
                'tipo_moneda' => $row['tipo_moneda'],
                'medio_pago' => $row['medio_pago'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
                'estado' => $row['estado'],
                'descEstado' => $row['descEstado'],
                'colorEstado' => $row['colorEstado'],
                'visto_bueno' => $row['visto_bueno'],
                'descVisto_bueno' => $row['descVisto_bueno'],
                'colorVisto_bueno' => $row['colorVisto_bueno'],
                'nro_operacion' => $row['nro_operacion'],
                'tipo_comprobante' => $row['tipo_comprobante'],
                'banco' => $row['agencia_bancaria']
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

if(isset($_POST['ReturnListaSubPagos'])){

        $idcodigo = $_POST['codigo'];
        
        $consultar_datos = mysqli_query($conection, "SELECT correlativo, id_venta FROM gp_cronograma WHERE id='$idcodigo'");
        $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
        $correlativo = $respuesta_datos['correlativo'];
        $idventa = $respuesta_datos['id_venta'];
        
        $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_cabecera WHERE id_cronograma='$correlativo' AND id_venta='$idventa'");
        $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
        $idpago = $respuesta_idpago['idpago'];


        $query = mysqli_query($conection,"
            gppd.fecha_pago as fecha,
            gpcr.item_letra as letra,
            gppd.monto_soles as monto_soles,
            gppd.monto_dolares as monto_dolares,
            gppd.nro_operacion as nro_operacion,
            concat(gppd.serie,'-',gppd.numero) as nro_boleta
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta='$idventa'
            WHERE gppd.esta_borrado=0
            AND gppd.idpago='$idpago'
            "); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'fecha' => $row['fecha'],
                'letra' => $row['letra'],
                'monto_soles' => $row['monto_soles'],
                'monto_dolares' => $row['monto_dolares'],
                'nro_operacion' => $row['nro_operacion'],
                'nro_boleta' => $row['nro_boleta']
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

if (isset($_POST['btnCargarDatosCliente'])) {

    $IdReg = $_POST['IdRegistro'];
    
    $query = mysqli_query($conection, "SELECT
    gpcr.id as id,
    gppc.idpago as idpago,
    gpv.id_venta as idventa,
   concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as datos,
   dc.celular_1 as celular,
   dc.email as correo,
   concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote
   FROM gp_cronograma gpcr
   INNER JOIN gp_pagos_cabecera AS gppc ON gppc.id_cronograma=gpcr.correlativo
   INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
   INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
   INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
   INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
   WHERE gppc.idpago='$IdReg' AND gpv.id_venta=gpcr.id_Venta");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['idventa'] = $resultado['idventa'];

        $idpago_new = $resultado['idpago'];
        $data['id_pago'] = encrypt($idpago_new,"123");
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

if(isset($_POST['ReturnPagosRealizados'])){

        $idcodigo = $_POST['txtidPago'];    
        $idcodigo = decrypt($idcodigo, "123");

        $consultar = mysqli_query($conection, "SELECT id_venta FROM gp_pagos_cabecera WHERE idpago='$idcodigo'");
        $respuesta = mysqli_fetch_assoc($consultar);
        $idventa = $respuesta['id_venta'];
    
        $query = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            date_format(gppd.fecha_pago, '%d/%m/%Y') as fecha,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as nom_cliente,
            concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as lote_nom,
            date_format(gppd.fecha_pago, '%d-%m-%Y') as fech_pago,
            cdx.nombre_corto as tipo_comprobante,
            concat(gppd.serie,'-',gppd.numero) as nro_boleta,
            cddx.texto1 as moneda_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.importe_pago,2) as importe_pago,
            cdddx.nombre_corto as medio_pago,
            gppd.nro_operacion as nro_operacion,
            cddddx.nombre_corto as banco,
            gppd.voucher as voucher,
            gppd.estado_observacion as observacion,
            format(gppd.pagado,2) as pagado
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppd.id_venta
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.tipo_comprobante AND cdx.codigo_tabla='_TIPO_COMPROBANTE'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.moneda_pago AND cddx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.medio_pago AND cdddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppd.agencia_bancaria AND cddddx.codigo_tabla='_BANCOS'
            WHERE gppd.esta_borrado=0 AND gppd.estado=1
            AND gppd.idpago='$idcodigo'
            "); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'tipo_comprobante' => $row['tipo_comprobante'],
                'nro_boleta' => $row['nro_boleta'],
                'moneda_pago' => $row['moneda_pago'],
                'tipo_cambio' => $row['tipo_cambio'],
                'importe_pago' => $row['importe_pago'],
                'pagado' => $row['pagado'],
                'medio_pago' => $row['medio_pago'],
                'nro_operacion' => $row['nro_operacion'],
                'banco' => $row['banco'],
                'voucher' => $row['voucher'],
                'nom_cliente' => $row['nom_cliente'],
                'lote_nom' => $row['lote_nom'],
                'fech_pago' => $row['fech_pago'],
                'observacion' => $row['observacion']
            ]);
        }
            
        $data['data'] = $dataList;
        $data['codigo'] = $idcodigo;
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
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnResponderObservacion'])) {

    $_ID_PAGO_DETALLE = $_POST['_ID_PAGO_DETALLE'];
    $txtRespuesta = $_POST['txtRespuesta'];     

    $query = mysqli_query($conection, "UPDATE 
        gp_pagos_detalle SET
        respuesta='$txtRespuesta'
        WHERE idpago_detalle='$_ID_PAGO_DETALLE' AND estado_observacion='1'");   

    if ($query){        
        $data['status'] = 'ok';
        $data['data'] = "Se ha registrado la respuesta a la observacion enviada. A continuacion, realice la validación del pago.";
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo registrar su respuesta, intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnSeleccionaDetallePagos'])) {

    $IdReg = $_POST['IdRegistro'];

      $query = mysqli_query($conection, "SELECT
       gpcr.id as id,
       gppc.idpago as idpago,
       gpv.id_venta as idventa,
       concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as datos,
       dc.celular_1 as celular,
       dc.email as correo,
       concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote
       FROM gp_cronograma gpcr
       INNER JOIN gp_pagos_cabecera AS gppc ON gppc.id_cronograma=gpcr.id
       INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
       INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
       INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
       INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
       WHERE gpcr.id='$IdReg' AND gpv.id_venta=gpcr.id_Venta");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['idventa'] = $resultado['idventa'];

        $idpago_new = $resultado['idpago'];
        $data['id_pago'] = encrypt($idpago_new,"123");
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

if(isset($_POST['ReturnPagosAprobados'])){

        $idcodigo = $_POST['txtidPago'];
        
        $consultar_crono = mysqli_query($conection, "SELECT id_venta, correlativo FROM gp_cronograma WHERE id='$idcodigo'");
        $respuesta_crono = mysqli_fetch_assoc($consultar_crono);
        $idventa = $respuesta_crono['id_venta'];
        $correlativo = $respuesta_crono['correlativo'];
        
        $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_cabecera WHERE id_venta='$idventa' AND id_cronograma='$correlativo'");
        $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
        $idpago =$respuesta_idpago['idpago'];


        $query = mysqli_query($conection,"SELECT
            gppd.fecha_pago as fecha,
            gpcr.item_letra as letra,
            gppd.importe_pago as monto,
            gppd.monto_soles as monto_soles,
            gppd.tipo_cambio as tipo_cambio,
            gppd.monto_dolares as monto_dolares,
            gppd.nro_operacion as nro_operacion,
            concat(gppd.serie,'-',gppd.numero) as nro_boleta
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta='$idventa'
            WHERE gppd.esta_borrado=0 AND gppd.estado=2
            AND gppd.idpago='$idpago'
            "); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'fecha' => $row['fecha'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
                'monto_soles' => $row['monto_soles'],
                'tipo_cambio' => $row['tipo_cambio'],
                'monto_dolares' => $row['monto_dolares'],
                'nro_operacion' => $row['nro_operacion'],
                'nro_boleta' => $row['nro_boleta']
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

if (isset($_POST['ListarCuotasCronograma'])) {

        $idventa = $_POST['idventa'];
        $idpago = $_POST['idpago'];
        $idpago = decrypt($idpago,"123");
        
        $query = mysqli_query($conection, "SELECT gpcr.correlativo as valor, concat(gpcr.item_letra,' - ',gpcr.fecha_vencimiento) as texto 
        FROM gp_cronograma gpcr
        INNER JOIN gp_pagos_cabecera AS gppc ON gppc.id_cronograma=gpcr.correlativo AND gppc.id_venta=gpcr.id_venta AND gppc.idpago='$idpago'
        WHERE gpcr.id_venta='$idventa'");
        
        //Buscar correlativo cronograma
        $query_correlativo = mysqli_query($conection, "SELECT id_cronograma as correlativo FROM gp_pagos_cabecera WHERE idpago='$idpago'");
        $respuesta_correlativo = mysqli_fetch_assoc($query_correlativo);
        $correlativo = $respuesta_correlativo['correlativo'];
        
        $query2 = mysqli_query($conection, "SELECT gpcr.correlativo as valor, concat(gpcr.item_letra,' - ',gpcr.fecha_vencimiento) as texto 
        FROM gp_cronograma gpcr 
        WHERE gpcr.id_venta='$idventa' AND gpcr.correlativo='$correlativo' AND gpcr.pago_cubierto IN ('1','0') AND gpcr.estado='2';");

        while ($row2 = $query2->fetch_assoc()) {
        array_push($dataList, [
            'valor' => $row2['valor'],
            'texto' => $row2['texto'],
        ]);}

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

if (isset($_POST['btnCargarDatosPago'])) {

    $IdReg = $_POST['IdRegistro'];
    $query = mysqli_query($conection, "SELECT
            gppd.idpago_detalle as id,
            gppd.fecha_pago as fecha,
            gppd.tipo_comprobante as tipo_comprobante,
            concat(gppd.serie,'-',gppd.numero) as nro_boleta,
            gppd.moneda_pago as moneda_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.importe_pago,2) as monto_pagado,
            format(gppd.pagado,2) as importe_pago,
            gppd.medio_pago as medio_pago,
            gppd.nro_operacion as nro_operacion,
            gppd.agencia_bancaria as banco,
            gppd.serie as serie,
            gppd.numero as numero
            FROM gp_pagos_detalle gppd
            WHERE gppd.esta_borrado=0 
            AND gppd.estado=1
            AND gppd.idpago_detalle='$IdReg'");

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

if (isset($_POST['btnGuardarDatosVerificacion'])) {

    $txtID_PAGO = $_POST['txtID_PAGO'];
    $txtFechaPagoDetalle = $_POST['txtFechaPagoDetalle'];
    $bxMedioPagoDetalle = $_POST['bxMedioPagoDetalle'];
    $bxTipoComprobanteDetalle = $_POST['bxTipoComprobanteDetalle'];
    $txtImportePagadoDetalle = $_POST['txtImportePagadoDetalle'];
    $txtImportePagadoDetalle=str_replace(",", "" , $txtImportePagadoDetalle);
    $bxAgenciaBancariaDetalle = $_POST['bxAgenciaBancariaDetalle'];
    $bxTipoMonedaDetalle = $_POST['bxTipoMonedaDetalle'];
    $txtTipoCambioDetalle = $_POST['txtTipoCambioDetalle'];
    $txtNroOperacionDetalle = $_POST['txtNroOperacionDetalle'];
    $file = $_POST['file'];
    

    if(!empty($txtID_PAGO)){
       
        $query = mysqli_query($conection, "UPDATE gp_pagos_detalle  SET 
            fecha_pago='$txtFechaPagoDetalle',
            medio_pago='$bxMedioPagoDetalle',
            tipo_comprobante='$bxTipoComprobanteDetalle',
            importe_pago='$txtImportePagadoDetalle',
            agencia_bancaria='$bxAgenciaBancariaDetalle',
            moneda_pago='$bxTipoMonedaDetalle',
            tipo_cambio='$txtTipoCambioDetalle',
            nro_operacion='$txtNroOperacionDetalle'
            WHERE idpago_detalle='$txtID_PAGO'");

            $data['status'] = 'ok';
        
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'Seleccione un registro de pago de la tabla.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnBuscarDatosLetra'])) {

    $correlativo = $_POST['correlativo'];
    $idpago = $_POST['idpago'];

    $idpago = decrypt($idpago, "123");

    $consultar_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_pagos_cabecera WHERE idpago='$idpago'");
    $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);

    $id_venta = $respuesta_idventa['id_venta'];
    $total = 0;
    $consultar_total = mysqli_query($conection, "SELECT SUM(pagado) as total FROM gp_pagos_detalle WHERE idpago='$idpago' AND id_venta='$id_venta' AND estado='2'");
    $respuesta_total = mysqli_fetch_assoc($consultar_total);
    $total = $respuesta_total['total'];

    if(empty($total)){
        $total = 0;
    }

    $query = mysqli_query($conection, "SELECT
            gpcr.id as id,
            gpcr.fecha_vencimiento as fecha,
            gpv.tipo_moneda as tipo_moneda,
            gpcr.monto_letra as monto
            FROM gp_cronograma gpcr
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
            WHERE gpcr.esta_borrado=0 
            AND gpcr.id_venta='$id_venta'
            AND gpcr.correlativo='$correlativo'");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $resul = ($resultado['monto'] - $total);
        if($resul>0){
            $resul = $resul;
        }else{
            $resul = 0;
        }
        $resul_ = number_format($resul, 2, '.', ',');
        $data['total'] = $resul_;
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

if (isset($_POST['btnAprobarPago'])) {

    $idpago = $_POST['idpago'];
    $idcronograma = $_POST['idcronograma'];
    $variable = "ninguno";
    if(!empty($idpago)){

        $consultar_idventa = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='2' WHERE idpago_detalle='$idpago'");
        $data['status'] = 'ok';

        $consultar_datos = mysqli_query($conection, "SELECT id_venta, idpago FROM gp_pagos_detalle WHERE idpago_detalle='$idpago'");
        $respuesta_datos = mysqli_fetch_assoc($consultar_datos);

        $idventa = $respuesta_datos['id_venta'];
        $idpagos = $respuesta_datos['idpago'];

        $consultar_totalpagado = mysqli_query($conection, "SELECT ROUND(SUM(pagado),2) as total FROM gp_pagos_detalle WHERE id_venta='$idventa' AND idpago='$idpagos' AND estado='2' AND esta_borrado='0'");
        $respuesta_totalpagado = mysqli_fetch_assoc($consultar_totalpagado);
        $total_pagado = $respuesta_totalpagado['total'];

        $consultar_totalletra = mysqli_query($conection, "SELECT ROUND(monto_letra,2) as total FROM gp_cronograma WHERE id='$idcronograma' AND esta_borrado='0'");
        $respuesta_totalletra = mysqli_fetch_assoc($consultar_totalletra);
        $total_letra = $respuesta_totalletra['total'];

        if($total_pagado == $total_letra){

            $actualizar_letra = mysqli_query($conection, "UPDATE gp_cronograma SET estado='2', pago_cubierto='2' WHERE id='$idcronograma'");
            $actualizar_letra = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET visto_bueno='2', estado='2' WHERE idpago='$idpagos'");
            $data['status'] = 'ok';
            $variable = "completo";
            $data['variable'] = $variable;
            $data['data'] = 'El pago de la letra fue completado. Gracias';
            

        }else{
            $data['status'] = 'ok';
            $data['data'] = 'El pago fue aprobado correctamente.';
        }

    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Seleccione un registro de pago de la tabla e intente nuevamente. Gracias';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnRechazarPago'])) {

    $idpago = $_POST['idpago'];

    if(!empty($idpago)){

        $consultar_idventa = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='3' WHERE idpago_detalle='$idpago'");
        $data['status'] = 'ok';
        $data['data'] = 'El pago fue rechazado. A continuacion se notificara al cliente';
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Seleccione un registro de pago de la tabla e intente nuevamente. Gracias';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['ReturnPagosRealizados3'])){

        $idcodigo = $_POST['txtidPago'];    
        $idcodigo = decrypt($idcodigo, "123");

        $consultar = mysqli_query($conection, "SELECT id_venta FROM gp_pagos_cabecera WHERE idpago='$idcodigo'");
        $respuesta = mysqli_fetch_assoc($consultar);
        $idventa = $respuesta['id_venta'];
    
        $query = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            gppd.fecha_pago as fecha,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as nom_cliente,
            concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as lote_nom,
            date_format(gppd.fecha_pago, '%d-%m-%Y') as fech_pago,
            cdx.nombre_corto as tipo_comprobante,
            concat(gppd.serie,'-',gppd.numero) as nro_boleta,
            cddx.texto1 as moneda_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.importe_pago,2) as importe_pago,
            cdddx.nombre_corto as medio_pago,
            gppd.nro_operacion as nro_operacion,
            cddddx.nombre_corto as banco,
            gppd.voucher as voucher,
            format(gppd.pagado, 2) as pagado
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppd.id_venta
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.tipo_comprobante AND cdx.codigo_tabla='_TIPO_COMPROBANTE'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.moneda_pago AND cddx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.medio_pago AND cdddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppd.agencia_bancaria AND cddddx.codigo_tabla='_BANCOS'
            WHERE gppd.esta_borrado=0 AND gppd.estado=2
            AND gppd.idpago='$idcodigo'
            ORDER BY gppd.fecha_pago DESC
            "); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'tipo_comprobante' => $row['tipo_comprobante'],
                'nro_boleta' => $row['nro_boleta'],
                'moneda_pago' => $row['moneda_pago'],
                'tipo_cambio' => $row['tipo_cambio'],
                'importe_pago' => $row['importe_pago'],
                'pagado' => $row['pagado'],
                'medio_pago' => $row['medio_pago'],
                'nro_operacion' => $row['nro_operacion'],
                'banco' => $row['banco'],
                'voucher' => $row['voucher'],
                'nom_cliente' => $row['nom_cliente'],
                'lote_nom' => $row['lote_nom'],
                'fech_pago' => $row['fech_pago']
            ]);
        }
            
        $data['data'] = $dataList;
        $data['codigo'] = $idcodigo;
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


if(isset($_POST['ReturnListaPagos4'])){

    
    $txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoPR']) ? $_POST['txtFiltroDocumentoPR'] : Null;
    $txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
    
    $bxFiltroLoteEC = isset($_POST['bxFiltroLotePR']) ? $_POST['bxFiltroLotePR'] : Null;
    $bxFiltroLoteECr = trim($bxFiltroLoteEC);
    
    $bxFiltroManzanaPR = isset($_POST['bxFiltroManzanaPR']) ? $_POST['bxFiltroManzanaPR'] : Null;
    $bxFiltroManzanaPRr = trim($bxFiltroManzanaPR);
    
    $bxFiltroZonaPR = isset($_POST['bxFiltroZonaPR']) ? $_POST['bxFiltroZonaPR'] : Null;
    $bxFiltroZonaPRr = trim($bxFiltroZonaPRv);
    
    $bxFiltroEV= isset($_POST['bxFiltroBancoPR']) ? $_POST['bxFiltroBancoPR'] : Null;
    $bxFiltroEVr = trim($bxFiltroEV);
    
    
    $txtFecIniFiltroPR= isset($_POST['txtFecIniFiltroPR']) ? $_POST['txtFecIniFiltroPR'] : Null;
    $txtFecIniFiltroPRr = trim($txtFecIniFiltroPR);
    
    $txtFecFinFiltroPR= isset($_POST['txtFecFinFiltroPR']) ? $_POST['txtFecFinFiltroPR'] : Null;
    $txtFecFinFiltroPRr = trim($txtFecFinFiltroPR);
    
    $query_documento = "";
    $query_lote = "";
    $query_manzana = "";
    $query_zona = "";
    $query_fecha= "";
    
    if(!empty($txtFiltroDocumentoECr)){
        $query_documento= "AND dc.documento='$txtFiltroDocumentoECr'";
    }
    
     if(!empty($bxFiltroLoteECr)){
        $query_lote= "AND gpl.idlote='$bxFiltroLoteECr'";
    }
    
     if(!empty($bxFiltroManzanaPRr)){
        $query_manzana= "AND gpm.idmanzana='$bxFiltroManzanaPRr'";
    }
    
     if(!empty($bxFiltroZonaPRr)){
        $query_zona= "AND gpz.idzona='$bxFiltroZonaPRr'";
    }
    
    if(!empty($txtFecIniFiltroPRr) && !empty($txtFecFinFiltroPRr)){
        $query_fecha = "AND gppd.fecha_pago BETWEEN '$txtFecIniFiltroPRr' AND '$txtFecFinFiltroPRr'";
    }else{
        if(!empty($txtFecIniFiltroPRr) && empty($txtFecFinFiltroPRr)){
            $query_fecha = "AND gppd.fecha_pago='$txtFecIniFiltroPRr'";
        }
    }

  

        $query = mysqli_query($conection,"SELECT 
            gppd.idpago_detalle as id,
            DATE_FORMAT(gppd.fecha_pago, '%d/%m/%Y') as fecha,
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            DATE_FORMAT(gpcr.fecha_vencimiento, '%d/%m/%Y') as fechaVencimiento,
            if(gppc.fecha_pago>gpcr.fecha_vencimiento,if((TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, gppd.fecha_pago)>0),concat('-',TIMESTAMPDIFF(DAY,gpcr.fecha_vencimiento, gppd.fecha_pago)),0),0) as mora,
            gpcr.item_letra as letra,
            cdx.texto1 as tipo_moneda,
            format(gppd.importe_pago,2) as importe_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            cddx.nombre_corto as banco,
            cdddx.nombre_corto as estado_nom,
            cdddx.texto1 as estado_col
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppd.id_venta
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.agencia_bancaria AND cddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.codigo_item=gppd.estado AND cdddx.codigo_tabla='_ESTADO_VALIDACION_PAGO'
            WHERE gppd.esta_borrado=0 AND gppd.estado IN ('2','3') AND gpv.devolucion!='1'
            $query_documento
            $query_lote
            $query_manzana
            $query_zona
            $query_fecha
            GROUP BY gppd.idpago_detalle
            ORDER BY gppd.fecha_pago DESC"); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'cliente' => $row['cliente'],
                'lote' => $row['lote'],
                'fechaVencimiento' => $row['fechaVencimiento'],
                'mora' => $row['mora'],
                'letra' => $row['letra'],
                'tipo_moneda' => $row['tipo_moneda'],
                'importe_pago' => $row['importe_pago'],
                'tipo_cambio' => $row['tipo_cambio'],
                'pagado' => $row['pagado'],
                'banco' => $row['banco'],
                'estado' => 'PAGADO',
                'estado_nom' => $row['estado_nom'],
                'estado_col' => $row['estado_col']
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

if (isset($_POST['btnEditarPago'])) {

    $IdReg = $_POST['idRegistro'];
    $query = mysqli_query($conection, "SELECT 
    gppd.idpago_detalle as id,
    gppd.fecha_pago as fecha,
    gppd.moneda_pago as tipomoneda,
    ROUND(gppd.importe_pago,2) as importePago,
    gppd.tipo_cambio as tipoCambio,
    ROUND(gppd.pagado,2) as pagado,
    gppd.agencia_bancaria as banco,
    gppd.medio_pago as medioPago,
    gppd.tipo_comprobante as tipoComprobante,
    /*gppd.serie as serie,
    gppd.numero as numero,*/
    gppd.nro_operacion as nro_operacion,
    gppd.voucher as voucher
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

if (isset($_POST['btnActualizarPago'])) {

    $_ID_PAGO = $_POST['_ID_PAGO'];
    $txtFechaPagoP = $_POST['txtFechaPagoP'];
    $cbxTipoMonedaP = $_POST['cbxTipoMonedaP'];
    $txtImportePagoP = $_POST['txtImportePagoP'];
    $txtTipoCambioP = $_POST['txtTipoCambioP'];
    $txtPagadoP = $_POST['txtPagadoP'];
    $cbxBancoP = $_POST['cbxBancoP'];
    $cbxMedioPagoP = $_POST['cbxMedioPagoP'];
    $cbxTipoComprobanteP = $_POST['cbxTipoComprobanteP'];
    /*$txtSerieP = $_POST['txtSerieP'];
    $txtNumeroP = $_POST['txtNumeroP'];*/
    $ficheroPago = $_POST['ficheroPago'];
    $txtNumOperacionP = $_POST['txtNumOperacionP'];
    
    $txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	
	$actualiza = $fecha.' '.$hora;

    $path = $ficheroPago;
    $file = new SplFileInfo($path);
    $extension  = $file->getExtension();
    $desc_codigo="voucher-";
    $name_file = "voucher";
    
    $cargo_file_adj = "no";
    $query_file = "";
    if(!empty($ficheroPago)){
		$name_file = $desc_codigo.$_ID_PAGO.".".$extension;
		$cargo_file_adj = "si";
		$query_file = "voucher='$name_file',";
	}
	
	//Verificar monto anterior a la modificacion
	$consultar_monto_det = mysqli_query($conection, "SELECT pagado, importe_pago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
	$respuesta_monto_det = mysqli_fetch_assoc($consultar_monto_det);
	$monto_det = $respuesta_monto_det['pagado'];
	$import_pago_det = $respuesta_monto_det['importe_pago'];

    $query = mysqli_query($conection, "UPDATE 
    gp_pagos_detalle SET
    fecha_pago='$txtFechaPagoP',
    moneda_pago='$cbxTipoMonedaP',
    importe_pago='$txtImportePagoP',
    tipo_cambio='$txtTipoCambioP',
    pagado='$txtPagadoP',
    agencia_bancaria='$cbxBancoP',
    medio_pago='$cbxMedioPagoP',
    tipo_comprobante='$cbxTipoComprobanteP',
    nro_operacion='$txtNumOperacionP',
    $query_file
    id_usuario_actualiza='$idusuario',
    actualizado='$actualiza'
    WHERE idpago_detalle='$_ID_PAGO'");
    if ($query) {
        
        //ACTUALIZAR LA CABECERA
        if($txtPagadoP > 0){
            
            //Consultar ID Pago
            $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
            $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
            $idpago = $respuesta_idpago['idpago'];
            
            //consultar cantidad de detalles
            $consultar_detalles = mysqli_query($conection, "SELECT idpago_detalle FROM gp_pagos_detalle WHERE idpago='$idpago'");
            $respuesta_detalles = mysqli_num_rows($consultar_detalles);
            
            if($respuesta_detalles>1){ //Significa que tiene mas de 1 item/detalle
                $diferencia = 0;
                $operacion = "";
                $monto_cb = 0;
                if($monto_det > $txtPagadoP){
                    $diferencia = $monto_det - $txtPagadoP;
                    $operacion = "sum";
                }else{
                    if($monto_det < $txtPagadoP){
                        $diferencia = $txtPagadoP - $monto_det;
                        $operacion = "dif";
                    }else{
                        $diferencia = $monto_det;
                        $operacion = "none";
                    }
                }
                
                
                if($operacion!="none"){
                    
                    //CONSULTAR TOTAL CABECERA
                    $consultar_pagado_cab = mysqli_query($conection, "SELECT pagado FROM gp_pagos_cabecera WHERE idpago='$idpago'");
                    $respuesta_pagado_cab = mysqli_fetch_assoc($consultar_pagado_cab);
                    $tot_pagado_cab = $respuesta_pagado_cab['pagado'];
                    
                    if($operacion=="sum"){
                       $monto_cb =  $tot_pagado_cab + $diferencia;
                    }else{
                       $monto_cb =  $tot_pagado_cab - $diferencia;
                    }
                
                    $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET pagado='$monto_cb' WHERE idpago='$idpago'");
                }
                
                
            }else{ //Significa que tiene solo 1 item/detalle
                
                //Actualizar Pagado en Cabecera
                $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET pagado='$txtPagadoP' WHERE idpago='$idpago'");
                
            }
            
        }
        
        if($txtImportePagoP > 0){
            
            //Consultar ID Pago
            $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
            $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
            $idpago = $respuesta_idpago['idpago'];
            
            //consultar cantidad de detalles
            $consultar_detalles = mysqli_query($conection, "SELECT idpago_detalle FROM gp_pagos_detalle WHERE idpago='$idpago'");
            $respuesta_detalles = mysqli_num_rows($consultar_detalles);
            
            if($respuesta_detalles>1){ //Significa que tiene mas de 1 item/detalle
                $diferencia = 0;
                $operacion = "";
                $monto_cb = 0;
                if($import_pago_det > $txtImportePagoP){
                    $diferencia = $import_pago_det - $txtImportePagoP;
                    $operacion = "sum";
                }else{
                    if($import_pago_det < $txtImportePagoP){
                        $diferencia = $txtImportePagoP - $import_pago_det;
                        $operacion = "dif";
                    }else{
                        $diferencia = $import_pago_det;
                        $operacion = "none";
                    }
                }
                
                
                if($operacion!="none"){
                    
                    //CONSULTAR TOTAL CABECERA
                    $consultar_importe_cab = mysqli_query($conection, "SELECT importe_pago FROM gp_pagos_cabecera WHERE idpago='$idpago'");
                    $respuesta_importe_cab = mysqli_fetch_assoc($consultar_importe_cab);
                    $tot_import_cab = $respuesta_importe_cab['importe_pago'];
                    
                    if($operacion=="sum"){
                       $monto_cb =  $tot_import_cab + $diferencia;
                    }else{
                       $monto_cb =  $tot_import_cab - $diferencia;
                    }
                
                    $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$monto_cb', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                }
                
                
            }else{ //Significa que tiene solo 1 item/detalle
                
                //Actualizar Pagado en Cabecera
                $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$txtImportePagoP', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                
            }
            
        }
        
        if(!empty($txtFechaPagoP)){
            
            //Consultar ID Pago
            $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
            $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
            $idpago = $respuesta_idpago['idpago'];
            
            //consultar cantidad de detalles
            $consultar_detalles = mysqli_query($conection, "SELECT idpago_detalle FROM gp_pagos_detalle WHERE idpago='$idpago'");
            $respuesta_detalles = mysqli_num_rows($consultar_detalles);
            
            if($respuesta_detalles == 1){ //Significa que tiene mas de 1 item/detalle
               
                //Actualizar Pagado en Cabecera
                $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET fecha_pago='$txtFechaPagoP', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                
            }
            
        }
        
        if(!empty($cbxTipoMonedaP)){
            
            //Consultar ID Pago
            $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
            $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
            $idpago = $respuesta_idpago['idpago'];
            
            //consultar cantidad de detalles
            $consultar_detalles = mysqli_query($conection, "SELECT idpago_detalle FROM gp_pagos_detalle WHERE idpago='$idpago'");
            $respuesta_detalles = mysqli_num_rows($consultar_detalles);
            
            if($respuesta_detalles == 1){ //Significa que tiene mas de 1 item/detalle
               
                //Actualizar Pagado en Cabecera
                $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET moneda_pago='$cbxTipoMonedaP', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                
            }
            
        }
        
        if($txtTipoCambioP>0){
            
            //Consultar ID Pago
            $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
            $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
            $idpago = $respuesta_idpago['idpago'];
            
            //consultar cantidad de detalles
            $consultar_detalles = mysqli_query($conection, "SELECT idpago_detalle FROM gp_pagos_detalle WHERE idpago='$idpago'");
            $respuesta_detalles = mysqli_num_rows($consultar_detalles);
            
            if($respuesta_detalles == 1){ //Significa que tiene mas de 1 item/detalle
               
                //Actualizar Pagado en Cabecera
                $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET tipo_cambio='$txtTipoCambioP', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                
            }
            
        }
        
        if(!empty($cbxBancoP)){
            
            //Consultar ID Pago
            $consultar_idpago = mysqli_query($conection, "SELECT idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO'");
            $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
            $idpago = $respuesta_idpago['idpago'];
            
            //consultar cantidad de detalles
            $consultar_detalles = mysqli_query($conection, "SELECT idpago_detalle FROM gp_pagos_detalle WHERE idpago='$idpago'");
            $respuesta_detalles = mysqli_num_rows($consultar_detalles);
            
            if($respuesta_detalles == 1){ //Significa que tiene mas de 1 item/detalle
               
                //Actualizar Pagado en Cabecera
                $actualiza_cab = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET agencia_bancaria='$cbxBancoP', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago'");
                
            }
            
        }
        
        $data['status'] = 'ok';
        $data['data'] = "Se guardaron los cambios en el pago seleccionado.";
        $data['name'] = $name_file;
        $data['cargo_file'] = $cargo_file_adj;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnAnularPago'])) {

    $idRegistro = $_POST['idRegistro'];

    $txtUSR = $_POST['txtUSR'];
	$txtUSR = decrypt($txtUSR, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	
	$actualiza = $fecha.' '.$hora;

    //Consultando informacion del pago detalle
    $consultar_datos_detalle = mysqli_query($conection, "SELECT
    gppc.id_venta as idventa,
    gppd.idpago as idpago,
    gppc.id_cronograma as idcronograma,
    ROUND(gppd.pagado,2) as pagado_detalle,
    ROUND(gppc.pagado,2) as pagado_cabecera
    FROM gp_pagos_detalle gppd
    INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago AND gppc.id_venta=gppd.id_venta
    WHERE gppd.idpago_detalle='$idRegistro'");
    $respuesta_datos_detalle = mysqli_fetch_assoc($consultar_datos_detalle);

    //Capturando datos de salida de la consulta al pago detalle
    $idventa = $respuesta_datos_detalle['idventa'];
    $idpago = $respuesta_datos_detalle['idpago'];
    $idcronograma = $respuesta_datos_detalle['idcronograma'];
    $pagado_detalle = $respuesta_datos_detalle['pagado_detalle'];
    $pagado_cabecera = $respuesta_datos_detalle['pagado_cabecera'];

    if( $pagado_detalle == $pagado_cabecera ){ // El monto del detalle a eliminar es igual al monto de la cabecera , tambien se consideran pagos que duplicados que seran equivalentes al total de la letra por duplicado

        //Verificar si el pago es duplicado
        $consultar_duplicado = mysqli_query($conection, "SELECT idpago_detalle as id FROM gp_pagos_detalle WHERE idpago='$idpago' AND esta_borrado='0'");


        if($consultar_duplicado->num_rows > 1){ //Si encuentra mas de 1 registro en el detalle ademas del registro seleccionado

            //Actualizar detalle
            $actualizar_detalle = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='0', esta_borrado='1', estado_cierre='0', estado_pago='0', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago_detalle='$idRegistro'");
            if($actualizar_detalle){

                //Consultar el Nuevo total del item detalle o mas que queden
                $total_detalle_saldo = 0;
                $consultar_resto_total_det = mysqli_query($conection, "SELECT SUM(pagado) as pagado FROM gp_pagos_detalle WHERE idpago='$idpago' AND id_venta='$idventa' AND esta_borrado='0'");
                if($consultar_resto_total_det->num_rows > 0){
                    $row_resto = mysqli_fetch_assoc($consultar_resto_total_det);
                    $total_detalle_saldo = $row_resto['pagado'];
                }
                
                //Actualizar cabecera
                $actualizar_cabecera = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$total_detalle_saldo',  pagado='$total_detalle_saldo', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago' AND id_venta='$idventa'");
                if($actualizar_cabecera){

                    $data['status'] = 'ok';
                    $data['data'] = 'Se ha anulado correctamente el pago seleccionado.';

                }else {
                    $data['status'] = 'bad';
                    $data['data'] = 'No se pudo actualizar el estado del pago (cabecera).';
                }

            }else {
                $data['status'] = 'bad';
                $data['data'] = 'No se pudo actualizar el estado del pago (detalle).';
            }

        }else{ //Si solo esta el registro seleccionando se eliminaran tanto en cabecera y detalle

            //Actualizar detalle
            $actualizar_detalle = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='0', esta_borrado='1', estado_cierre='0', estado_pago='0', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago_detalle='$idRegistro'");
            if($actualizar_detalle){

                //Actualizar cabecera
                $actualizar_cabecera = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET estado='0',  visto_bueno='0', esta_borrado='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago' AND id_venta='$idventa'");
                if($actualizar_cabecera){

                    //Actualizar cronograma
                    $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET estado='1', pago_cubierto='0', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$idventa' AND correlativo='$idcronograma'");
                    if($actualizar_cronograma){

                        $data['status'] = 'ok';
                        $data['data'] = 'Se ha anulado correctamente el pago seleccionado.';

                    }else {
                        $data['status'] = 'bad';
                        $data['data'] = 'No se pudo actualizar el estado del pago (cabecera).';
                    }

                }else {
                    $data['status'] = 'bad';
                    $data['data'] = 'No se pudo actualizar el estado del pago (cabecera).';
                }

            }else {
                $data['status'] = 'bad';
                $data['data'] = 'No se pudo actualizar el estado del pago (detalle).';
            }
        }


    }else{ //El monto a eliminar es menor al total del pago en la cabecera.

        //Actualizar detalle
        $actualizar_detalle = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='0', esta_borrado='1', estado_cierre='0', estado_pago='0', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago_detalle='$idRegistro'");
        if($actualizar_detalle){

            $consultar_new_total = mysqli_query($conection, "SELECT SUM(ROUND(pagado,2)) as pagado FROM gp_pagos_detalle WHERE idpago='$idpago' AND id_venta='$idventa' AND esta_borrado='0'");
            if($consultar_new_total->num_rows > 0){
                $row = mysqli_fetch_assoc($consultar_new_total);
                $new_pagado_item = $row['pagado'];

                $consultar_tot_letra = mysqli_query($conection, "SELECT ROUND(monto_letra,2) as letra FROM gp_cronograma WHERE id_venta='$idventa' AND correlativo='$idcronograma' AND esta_borrado='0'");
                if($consultar_tot_letra->num_rows > 0){

                    $row_letra = mysqli_fetch_assoc($consultar_tot_letra);
                    $total_letra = $row_letra['letra'];

                    if($total_letra  == $new_pagado_item){ //El saldo que queda al eliminar el pago sigue siendo equivalente al monto de la letra, por tanto solo se actualizan los totales de la cabecera.
                        //Actualizar cabecera
                        $actualizar_cabecera = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET importe_pago='$new_pagado_item', pagado='$new_pagado_item', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago' AND id_venta='$idventa'");
                        if($actualizar_cabecera){
                            $data['status'] = 'ok';
                            $data['data'] = 'Se ha anulado correctamente el pago seleccionado.';
                        }
                    }else{ // Si el saldo es menor al monto de la letra, el estado de la letra se mantendra "POR VALIDAR" debido a que el total pagado actual no cubre el monto que exige la letra.

                        $actualizar_cabecera = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET estado='2',  visto_bueno='1', importe_pago='$new_pagado_item', pagado='$new_pagado_item', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE idpago='$idpago' AND id_venta='$idventa'");
                        if($actualizar_cabecera){
                            //Actualizar cronograma
                            $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET estado='2', pago_cubierto='1', id_usuario_actualiza='$idusuario', actualizado='$actualiza' WHERE id_venta='$idventa' AND correlativo='$idcronograma'");
                            if($actualizar_cronograma){

                                $data['status'] = 'ok';
                                $data['data'] = 'Se ha anulado correctamente el pago seleccionado.';

                            }else {
                                $data['status'] = 'bad';
                                $data['data'] = 'No se pudo actualizar el estado de la letra.';
                            }
                        }else {
                            $data['status'] = 'bad';
                            $data['data'] = 'No se pudo actualizar la informacion del pago (cabecera).';
                        }

                    }

                }else {
                    $data['status'] = 'bad';
                    $data['data'] = 'No se encontro informacion de la letra relacionada al(los) pago(s). Consulte con soporte.';
                }

            }else {
                $data['status'] = 'bad';
                $data['data'] = 'No se encontraron mas pagos relacionados a la letra, revise que existan pagos activos para la letra. Si no encuentra alguno, consulte con soporte.';
            }
            
        }else {
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo actualizar el estado del pago (detalle).';
        }
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}




/*   METODOS NUEVOS PARA PAGOS RESERVA */ 

if(isset($_POST['ReturnListaPagosRES'])){

    
    $txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoEC']) ? $_POST['txtFiltroDocumentoEC'] : Null;
    $txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
    
    $bxFiltroLoteEC = isset($_POST['bxFiltroLoteEC']) ? $_POST['bxFiltroLoteEC'] : Null;
    $bxFiltroLoteECr = trim($bxFiltroLoteEC);
    
    $bxFiltroEV= isset($_POST['bxFiltroEV']) ? $_POST['bxFiltroEV'] : Null;
    $bxFiltroEVr = trim($bxFiltroEV);
    
    $bxFiltroEstadoEC= isset($_POST['bxFiltroEstadoEC']) ? $_POST['bxFiltroEstadoEC'] : Null;
    $bxFiltroEstadoECr = trim($bxFiltroEstadoEC);
    
    $txtFecIniFiltro= isset($_POST['txtFecIniFiltro']) ? $_POST['txtFecIniFiltro'] : Null;
    $txtFecIniFiltror = trim($txtFecIniFiltro);
    
    $txtFecFinFiltro= isset($_POST['txtFecFinFiltro']) ? $_POST['txtFecFinFiltro'] : Null;
    $txtFecFinFiltror = trim($txtFecFinFiltro);

    $query_documento = "";
    $query_lote = "";
    $query_estado = "";
    $idventa = "0";
    $query_vistoBueno = "";
    $query_venta = "";
    $query_estado_pago= "";
    $query_fecha = "";
    
    if(!empty($bxFiltroEstadoECr)){
        if($bxFiltroEstadoECr=="todos"){
            $query_estado_pago = "AND gpcr.estado IN ('2','4')";
        }else{
            $query_estado_pago = "AND gpcr.estado='$bxFiltroEstadoECr'";
        }        
    }
    
    if(!empty($bxFiltroEVr)){
        if($bxFiltroEVr=="todos"){
            $query_vistoBueno = "";
        }else{
            $query_vistoBueno = "AND gppc.visto_bueno='$bxFiltroEVr'";
        }
    }else{
        $query_vistoBueno = "AND gppc.visto_bueno IN ('1','0')";
    }
    
    if(!empty($txtFiltroDocumentoECr)){
        $query_documento = "AND dc.documento='$txtFiltroDocumentoECr'";
    }
    
    if(!empty($txtFecIniFiltror) && !empty($txtFecFinFiltror)){
        $query_fecha = "AND gppc.fecha_pago BETWEEN '$txtFecIniFiltror' AND '$txtFecFinFiltror'";    
    }else{
        if(!empty($txtFecIniFiltror) && empty($txtFecFinFiltror)){
            $query_fecha = "AND gppc.fecha_pago='$txtFecIniFiltror'"; 
        }
    }

        $query = mysqli_query($conection,"SELECT 
            gppd.idpago_detalle as id,
            concat(dc.documento,' - ',dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as nom_cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as nom_lote,
            date_format(gppc.fecha_pago, '%d/%m/%Y') as fecha,
            'RESERVA' as letra,
            cdx.texto1 as tipo_moneda,
            format(gppc.pagado,2) as monto,
            gppc.estado as estado,
            cddx.nombre_corto as descEstado,
            cddx.texto1 as colorEstado,
            gppc.visto_bueno as visto_bueno,
            cdddx.nombre_corto as descVisto_bueno,
            cdddx.texto1 as colorVisto_bueno,
            cddddx.nombre_corto as agencia_bancaria,
            cdddddx.nombre_corto as medio_pago,
            cddddddx.nombre_corto as tipo_comprobante,
            gppc.numero as nro_operacion,
            date_format(res.fecha_fin_reserva, '%d/%m/%Y') as fechaVencimiento,
            '0' as mora,
            concat(cdx.texto1,' - ',format(gppc.pagado,2)) as importe_pago,
            gppd.voucher as voucher
            FROM gp_pagos_cabecera gppc
            INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gppc.id_cronograma
            INNER JOIN gp_reservacion AS res ON res.id_lote=gppc.id_cronograma AND res.fecha_inicio_reserva=gppc.fecha_pago
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN datos_cliente AS dc ON dc.id=res.id_cliente
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppc.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gppc.estado AND cddx.codigo_tabla='_ESTADO_EC'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.codigo_item=gppc.visto_bueno AND  cdddx.codigo_tabla='_ESTADO_VP'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppc.agencia_bancaria AND cddddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cdddddx ON cdddddx.idconfig_detalle=gppc.medio_pago AND cdddddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN configuracion_detalle AS cddddddx ON cddddddx.idconfig_detalle=gppc.tipo_comprobante AND cddddddx.codigo_tabla='_TIPO_COMPROBANTE'
            WHERE gppc.esta_borrado=0
            AND gppc.id_venta='0' AND gppc.tipo_pago='1'
            $query_vistoBueno
            $query_fecha
            $query_documento
            "); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'cliente' => $row['cliente'],
                'fechaVencimiento' => $row['fechaVencimiento'],
                'mora' => $row['mora'],
                'lote' => $row['lote'],
                'importe_pago' => $row['importe_pago'],
                'nom_lote' => $row['nom_lote'],
                'tipo_moneda' => $row['tipo_moneda'],
                'medio_pago' => $row['medio_pago'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
                'estado' => $row['estado'],
                'descEstado' => $row['descEstado'],
                'colorEstado' => $row['colorEstado'],
                'visto_bueno' => $row['visto_bueno'],
                'descVisto_bueno' => $row['descVisto_bueno'],
                'colorVisto_bueno' => $row['colorVisto_bueno'],
                'nro_operacion' => $row['nro_operacion'],
                'tipo_comprobante' => $row['tipo_comprobante'],
                'banco' => $row['agencia_bancaria'],
                'voucher' => $row['voucher']
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

if(isset($_POST['btnMostrarVoucher2'])){
    
    $idRegistro = $_POST['idRegistro'];

    $consultar_voucher = mysqli_query($conection, "SELECT gpr.voucher as voucher 
	FROM gp_reservacion gpr
	WHERE gpr.id_reservacion='$idRegistro'");
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

if(isset($_POST['btnValidarPagoReserva'])){
    
    $idRegistro = $_POST['idRegistro'];
    
    //Consultar idpago en tabla detalle
    $consultar_idpago = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_detalle WHERE idpago_detalle='$idRegistro'");
    $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
    $idpago = $respuesta_idpago['id'];

    $consultar_voucher = mysqli_query($conection, "UPDATE gp_pagos_cabecera SET estado='2', visto_bueno='2' WHERE idpago='$idpago'");
    $respuesta_voucher = mysqli_fetch_assoc($consultar_voucher);
    
    $consultar_voucher = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado='2' WHERE idpago='$idpago'");
    $respuesta_voucher = mysqli_fetch_assoc($consultar_voucher);
    
    $data['status'] = 'ok';
    $data['data'] = 'Se ha validado el pago seleccionado.';
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['ReturnListaPagosVRES'])){

    
    $txtFiltroDocumentoEC = isset($_POST['txtFiltroDocumentoPR']) ? $_POST['txtFiltroDocumentoPR'] : Null;
    $txtFiltroDocumentoECr = trim($txtFiltroDocumentoEC);
    
    $bxFiltroLoteEC = isset($_POST['bxFiltroLotePR']) ? $_POST['bxFiltroLotePR'] : Null;
    $bxFiltroLoteECr = trim($bxFiltroLoteEC);
    
    $bxFiltroManzanaPR = isset($_POST['bxFiltroManzanaPR']) ? $_POST['bxFiltroManzanaPR'] : Null;
    $bxFiltroManzanaPRr = trim($bxFiltroManzanaPR);
    
    $bxFiltroZonaPR = isset($_POST['bxFiltroZonaPR']) ? $_POST['bxFiltroZonaPR'] : Null;
    $bxFiltroZonaPRr = trim($bxFiltroZonaPRv);
    
    $bxFiltroEV= isset($_POST['bxFiltroBancoPR']) ? $_POST['bxFiltroBancoPR'] : Null;
    $bxFiltroEVr = trim($bxFiltroEV);
    
    
    $txtFecIniFiltroPR= isset($_POST['txtFecIniFiltroPR']) ? $_POST['txtFecIniFiltroPR'] : Null;
    $txtFecIniFiltroPRr = trim($txtFecIniFiltroPR);
    
    $txtFecFinFiltroPR= isset($_POST['txtFecFinFiltroPR']) ? $_POST['txtFecFinFiltroPR'] : Null;
    $txtFecFinFiltroPRr = trim($txtFecFinFiltroPR);
    
    $query_documento = "";
    $query_lote = "";
    $query_manzana = "";
    $query_zona = "";
    $query_fecha= "";
    
    if(!empty($txtFiltroDocumentoECr)){
        $query_documento= "AND dc.documento='$txtFiltroDocumentoECr'";
    }
    
     if(!empty($bxFiltroLoteECr)){
        $query_lote= "AND gpl.idlote='$bxFiltroLoteECr'";
    }
    
     if(!empty($bxFiltroManzanaPRr)){
        $query_manzana= "AND gpm.idmanzana='$bxFiltroManzanaPRr'";
    }
    
     if(!empty($bxFiltroZonaPRr)){
        $query_zona= "AND gpz.idzona='$bxFiltroZonaPRr'";
    }
    
    if(!empty($txtFecIniFiltroPRr) && !empty($txtFecFinFiltroPRr)){
        $query_fecha = "AND gppd.fecha_pago BETWEEN '$txtFecIniFiltroPRr' AND '$txtFecFinFiltroPRr'";
    }else{
        if(!empty($txtFecIniFiltroPRr) && empty($txtFecFinFiltroPRr)){
            $query_fecha = "AND gppd.fecha_pago='$txtFecIniFiltroPRr'";
        }
    }

  

        $query = mysqli_query($conection,"SELECT 
            gppd.idpago_detalle as id,
            DATE_FORMAT(gppd.fecha_pago, '%d/%m/%Y') as fecha,
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            DATE_FORMAT(gppd.fecha_pago, '%d/%m/%Y') as fechaVencimiento,
            '0' as mora,
            'ninguna' as letra,
            cdx.texto1 as tipo_moneda,
            format(gppd.importe_pago,2) as importe_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            cddx.nombre_corto as banco,
            cdddx.nombre_corto as estado_nom,
            cdddx.texto1 as estado_col
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_reservacion AS res ON res.id_lote=gppc.id_cronograma AND res.fecha_inicio_reserva=gppc.fecha_pago
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gppc.id_cronograma
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN datos_cliente AS dc ON dc.id=res.id_cliente
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.agencia_bancaria AND cddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.codigo_item=gppd.estado AND cdddx.codigo_tabla='_ESTADO_VALIDACION_PAGO'
            WHERE gppd.esta_borrado=0 
            AND gppd.estado IN ('2','3') 
            AND gppc.id_venta='0'
            $query_documento
            $query_lote
            $query_manzana
            $query_zona
            $query_fecha
            ORDER BY gppd.fecha_pago DESC"); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'cliente' => $row['cliente'],
                'lote' => $row['lote'],
                'fechaVencimiento' => $row['fechaVencimiento'],
                'mora' => $row['mora'],
                'letra' => $row['letra'],
                'tipo_moneda' => $row['tipo_moneda'],
                'importe_pago' => $row['importe_pago'],
                'tipo_cambio' => $row['tipo_cambio'],
                'pagado' => $row['pagado'],
                'banco' => $row['banco'],
                'estado' => 'PAGADO',
                'estado_nom' => $row['estado_nom'],
                'estado_col' => $row['estado_col']
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

if (isset($_POST['btnEditarPagoReserva'])) {

    $IdReg = $_POST['idRegistro'];
    $query = mysqli_query($conection, "SELECT 
    gppd.idpago_detalle as id,
    gppd.fecha_pago as fecha,
    gppd.moneda_pago as tipomoneda,
    ROUND(gppd.importe_pago,2) as importePago,
    gppd.tipo_cambio as tipoCambio,
    ROUND(gppd.pagado,2) as pagado,
    gppd.agencia_bancaria as banco,
    gppd.medio_pago as medioPago,
    gppd.tipo_comprobante as tipoComprobante,
    /*gppd.serie as serie,
    gppd.numero as numero,*/
    gppd.nro_operacion as nro_operacion,
    gppd.voucher as voucher
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

if (isset($_POST['btnActualizarPagoReserva'])) {

    $_ID_PAGO = $_POST['_ID_PAGO'];
    $txtFechaPagoP = $_POST['txtFechaPagoP'];
    $cbxTipoMonedaP = $_POST['cbxTipoMonedaP'];
    $txtImportePagoP = $_POST['txtImportePagoP'];
    $txtTipoCambioP = $_POST['txtTipoCambioP'];
    $txtPagadoP = $_POST['txtPagadoP'];
    $cbxBancoP = $_POST['cbxBancoP'];
    $cbxMedioPagoP = $_POST['cbxMedioPagoP'];
    $cbxTipoComprobanteP = $_POST['cbxTipoComprobanteP'];
    /*$txtSerieP = $_POST['txtSerieP'];
    $txtNumeroP = $_POST['txtNumeroP'];*/
    $ficheroPago = $_POST['ficheroPago'];
    $txtNumOperacionP = $_POST['txtNumOperacionP'];

    $path = $ficheroPago;
    $file = new SplFileInfo($path);
    $extension  = $file->getExtension();
    $desc_codigo="voucher-";
    $name_file = "voucher";
    if(!empty($ficheroPago)){
		$name_file = $desc_codigo.$_ID_PAGO.".".$extension;
	}

    $query = mysqli_query($conection, "UPDATE 
    gp_pagos_detalle SET
    fecha_pago='$txtFechaPagoP',
    moneda_pago='$cbxTipoMonedaP',
    importe_pago='$txtImportePagoP',
    tipo_cambio='$txtTipoCambioP',
    pagado='$txtPagadoP',
    agencia_bancaria='$cbxBancoP',
    medio_pago='$cbxMedioPagoP',
    tipo_comprobante='$cbxTipoComprobanteP',
    nro_operacion='$txtNumOperacionP',
    voucher='$name_file'
    WHERE idpago_detalle='$_ID_PAGO'");
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


