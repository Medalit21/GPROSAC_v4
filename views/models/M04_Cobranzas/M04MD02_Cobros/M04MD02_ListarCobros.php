
<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d');   

   $tipodoc = '1'; 
   $doc = '40524285';
   $nac = '200';

   $consulta_idusu = mysqli_query($conection, "SELECT 
        id as id
        FROM datos_cliente WHERE 
        tipodocumento='$tipodoc'
        AND documento = '$doc'
        AND nacionalidad='$nac'
    ");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdLote=$respuesta_idusu['id'];


   $data = array();
   $dataList = array();


if (isset($_POST['btnListarLotes'])) {

    $valor_idlote = $_POST['idlote'];
    
    $query = mysqli_query($conection, "SELECT 
        gpv.id_lote as valor,
        concat(tbl2.nombre,' - ',concat(tbl3.nombre,' - ',concat(tbl4.nombre,' - ',tbl5.nombre))) as texto
        FROM gp_venta gpv 
        INNER JOIN gp_lote AS tbl2 ON tbl2.idlote=gpv.id_lote 
        INNER JOIN gp_manzana AS tbl3 ON tbl3.idmanzana=tbl2.idmanzana
        INNER JOIN gp_zona AS tbl4 ON tbl4.idzona=tbl3.idzona
        INNER JOIN gp_proyecto AS tbl5 ON tbl5.idproyecto=tbl4.idproyecto
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        WHERE gpv.esta_borrado=0
        AND gpv.id_lote='$valor_idlote'
        ");

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

if (isset($_POST['btnCuotas'])) {
    $iddlote = $_POST['idLote'];

    $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_lote='$iddlote'");
    $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
    $idventa = $respuesta_idventa['id'];

    $query = mysqli_query($conection, "SELECT 
        id as valor,
        concat(item_letra,' (', concat(fecha_vencimiento),')') as texto
        FROM gp_cronograma
        WHERE id_venta='$idventa' AND estado='1' ORDER BY item_letra ASC");

 
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
   $cuota = 182;
   //Consultar estado de la actividad
   $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_lote='$iddlote'");
    $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
    $idventa = $respuesta_idventa['id'];

    $consulta_cuota = mysqli_query($conection, "SELECT format(monto_letra,2) as monto FROM gp_cronograma WHERE id_venta='$idventa' AND estado='1' ORDER BY item_letra ASC");
    $respuesta_cuota = mysqli_fetch_assoc($consulta_cuota);
    $monto = $respuesta_cuota['monto'];


        $consulta_cronograma = mysqli_query($conection, "SELECT max(id) as valor FROM gp_cronograma WHERE id_venta='$idventa' AND estado='3' ORDER BY item_letra ASC");
        $respuesta_cronograma = mysqli_fetch_assoc($consulta_cronograma);
        $idcronograma = $respuesta_cronograma['valor'];


        $consultar_datos_config = mysqli_query($conection, "SELECT 
            (SELECT idconfig_detalle FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND nombre_corto='DOLARES') as tipo_moneda,
            (SELECT idconfig_detalle FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_PAGO' AND nombre_corto='BANCA MOVIL') as medio_pago,
            (SELECT idconfig_detalle FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE' AND nombre_corto='VOUCHER DE PAGO') as tipo_comprobante,
            (SELECT idconfig_detalle FROM configuracion_detalle WHERE codigo_tabla='_BANCOS' AND nombre_corto='Bbva') as agencia_bancaria
            FROM configuracion_detalle        
            WHERE estado='ACTI'");
        $respuesta_datos_config = mysqli_fetch_assoc($consultar_datos_config);


        $consultar_datos_pagos = mysqli_query($conection, "SELECT moneda_pago, medio_pago, tipo_comprobante, agencia_bancaria FROM gp_pagos WHERE idventa='$idventa' AND idcronograma='$idcronograma'");
        $contar_datos_pagos = mysqli_num_rows($consultar_datos_pagos);
        $respuesta_datos_pagos = mysqli_fetch_assoc($consultar_datos_pagos);

        if($contar_datos_pagos > 0){

            $moneda_pago = $respuesta_datos_pagos['moneda_pago'];
            $medio_pago = $respuesta_datos_pagos['medio_pago'];
            $tipo_comprobante = $respuesta_datos_pagos['tipo_comprobante'];
            $agencia_bancaria = $respuesta_datos_pagos['agencia_bancaria'];

        }else{

            $moneda_pago = $respuesta_datos_config['tipo_moneda'];
            $medio_pago = $respuesta_datos_config['medio_pago'];
            $tipo_comprobante = $respuesta_datos_config['tipo_comprobante'];
            $agencia_bancaria = $respuesta_datos_config['agencia_bancaria'];

        }

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
           $data['monto'] = $monto;
            $data['moneda'] = $moneda_pago;
            $data['medio_pago'] = $medio_pago;
            $data['tipo_comprobante'] = $tipo_comprobante;
            $data['agencia_bancaria'] = $agencia_bancaria;
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

if(isset($_POST['btnRegistrarPago'])){

    $txtidVenta = isset($_POST['txtidVenta']) ? $_POST['txtidVenta'] : Null;
    $txtidVentar = trim($txtidVenta);   

    $bxNroCuotas = isset($_POST['bxNroCuotas']) ? $_POST['bxNroCuotas'] : Null;
    $bxNroCuotasr = trim($bxNroCuotas);   

    $bxTipoMoneda2 = isset($_POST['bxTipoMoneda2']) ? $_POST['bxTipoMoneda2'] : Null;
    $bxTipoMoneda2r = trim($bxTipoMoneda2); 

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


    if(!empty($txtMontoPagador) && !empty($bxMedioPagor) && !empty($bxTipoComprobanter) && !empty($bxAgenciaBancariar) && !empty($txtNumeroOperacionr) && !empty($txtFechaPagor)){


           $consultar_actividadd = mysqli_query($conection, "SELECT idpago FROM gp_pagos WHERE idventa='$txtidVentar' AND idcronograma='$bxNroCuotasr' AND nro_operacion='$txtNumeroOperacionr' AND estado='1'");
           $respuesta_actividadd = mysqli_num_rows($consultar_actividadd);

           if($respuesta_actividadd==0){

            $actualizar_actividad = mysqli_query($conection, "INSERT INTO gp_pagos(idventa, idcronograma, moneda_pago, importe_pago, medio_pago, tipo_comprobante, agencia_bancaria, nro_operacion, fecha_pago) VALUES ('$txtidVentar','$bxNroCuotasr','$bxTipoMoneda2r','$txtMontoPagador','$bxMedioPagor','$bxTipoComprobanter','$bxAgenciaBancariar','$txtNumeroOperacionr','$txtFechaPagor')"); 


            $consultar_actividad_2 = mysqli_query($conection, "SELECT idpago FROM gp_pagos WHERE idventa='$txtidVentar' AND idcronograma='$bxNroCuotasr' AND nro_operacion='$txtNumeroOperacionr' AND estado='1'");

            $respuesta_actividad_2 = mysqli_num_rows($consultar_actividad_2);

            if($respuesta_actividad_2>0){

                $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET estado='2' WHERE id='$bxNroCuotasr' AND id_venta='$txtidVentar' AND estado='1'");

                $data['status'] = "ok";
                $data['data'] = "Se registro el pago.";

            }else{
                $data['status'] = "bad";
                $data['data'] = "El pago no pudo ser registrado.";
            }

        }else{
            $data['status'] = "bad";
            $data['data'] = "Ya se pagó la cuota";
        }

    }else{
      $data['status'] = "bad";
      $data['data'] = "Completar todos los campos.";
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

    if(!empty($HOST)){

        $data['status'] = "ok";
        $data['URL'] = $HOST."views/M04_Cobranzas/M04SM01_Cobranzas/M04SM03_PagosRealizados.php?Vsr=".$_SESSION['US'];

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



