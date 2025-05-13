<?php
session_start();

//RECURSOS DE CONEXION Y CONFIGURACION
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
$IdUser=$respuesta_idusu['id'];
$hora = date("H:i:s", time());
$fecha = date('Y-m-d'); 
$data = array();
$dataList = array();


if (isset($_POST['btnAnularReserva'])) {

    $idRegistro = $_POST['idRegistro'];
    
    //ANULAR RESERVA
    $anular = mysqli_query($conection, "UPDATE gp_reservacion SET esta_borrado='1' WHERE id_reservacion = '$idRegistro'");
    
    $consultar_lote = mysqli_query($conection, "SELECT id_lote as idlote FROM gp_reservacion WHERE id_reservacion='$idRegistro'");
    $respuesta_lote = mysqli_fetch_assoc($consultar_lote);
    $idlote = $respuesta_lote['idlote'];
    
    if($anular){
        
        //Liberar lote
        $liberar = mysqli_query($conection, "UPDATE gp_lote SET estado='1' WHERE idlote='$idlote'");
        
        $data['status'] = 'ok';
        $data['data'] = "La Reserva seleccionada fue anulada. Por lo mismo, el lote asignado a la Reserva fue liberado.";
        
    }else{
        $data['status'] = 'bad';
        $data['data'] = "La anulación de la reserva no pudo ser completada. Intente nuevamente.";
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}


if (isset($_POST['ListarZonass'])) {
    $IdProyecto = $_POST['idProyecto'];

    $query = mysqli_query($conection, "SELECT idzona as id, nombre FROM gp_zona where esta_borrado=0 AND idproyecto='$IdProyecto' AND estado='1'");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ListarManzanass'])) {
    $IdZona = $_POST['idZona'];

    $query = mysqli_query($conection, "SELECT idmanzana as id , nombre from gp_manzana where esta_borrado=0 and idzona=$IdZona;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ListarLotess'])) {
    $IdManzana = $_POST['idManzana'];

    $query = mysqli_query($conection, "SELECT idlote as id , nombre from gp_lote where esta_borrado=0 and idmanzana=$IdManzana;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}



if (isset($_POST['ReturnRestablecer'])) {

    $usuar = $_POST['idUser'];

    $data['status'] = 'ok';
    $data['ruta'] = $NAME_SERVER."views/M03_Ventas/M03SM01_Reservacion/M03SM01_Reservacion?Vsr=".$usuar;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

if (isset($_POST['ReturnIrCliente'])) {

    $idcliente = $_POST['idRegistro'];
    $idcliente = decrypt($idcliente, "123");

    $consultar_datos_cliente = mysqli_query($conection, "SELECT documento as doc FROM datos_cliente WHERE id='$idcliente'");
    $respuesta_datos_cliente = mysqli_fetch_assoc($consultar_datos_cliente);
    $doc_cliente = $respuesta_datos_cliente['doc'];

    $data['status'] = 'ok';
    $data['documento'] = $doc_cliente;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

if (isset($_POST['ReturnZonas'])) {
    $IdProyecto = $_POST['idProyecto'];

    $query = mysqli_query($conection, "SELECT idzona as id, nombre FROM gp_zona where esta_borrado=0 and idproyecto=$IdProyecto;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccione',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnManzana'])) {
    $IdZona = $_POST['idZona'];

    $query = mysqli_query($conection, "select idmanzana as id , nombre from gp_manzana where esta_borrado=0 and idzona=$IdZona;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccione',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnLote'])) {
    $IdManzana = $_POST['idManzana'];

    $query = mysqli_query($conection, "select idlote as id , nombre from gp_lote where esta_borrado=0 and estado=1 and idmanzana=$IdManzana and bloqueo_estado != 7;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccione',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnLoteActualizable'])) {
    $IdManzana = $_POST['idManzana'];
    $IdLote = $_POST['idLote'];

    $query = mysqli_query($conection, "select idlote as id , nombre from gp_lote where esta_borrado=0 and (estado=1 or idlote='$IdLote') and idmanzana=$IdManzana;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccione',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR CLIENTE POR  DNI************************** */
if (isset($_POST['ReturnBuscarCliente'])) {
    
    $TipoDocumento = $_POST['tipoDocumento'];
    $Documento = $_POST['documento'];
    $doc_codificado = encrypt($Documento,"123");
    $query = mysqli_query($conection, "SELECT id,documento,apellido_paterno as apellidoPaterno,apellido_materno as apellidoMaterno,nombres 
    FROM datos_cliente where (documento='$Documento' OR apellido_paterno like concat('%','$Documento','%') OR apellido_materno like concat('%','$Documento','%')  OR nombres like concat('%','$Documento','%'))");
    
    //CONSULTAR MEDIO DE PAGO
    $consultar_medio_pag = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_PAGO' AND nombre_corto='TRANSFERENCIA'");
    $respuesta_medio_pag = mysqli_fetch_assoc($consultar_medio_pag);
    $idmedio_pago = $respuesta_medio_pag['id'];
    
    //CONSULTAR TIPO DE CONSTANCIA
    $consultar_tipo_cons = mysqli_query($conection, "SELECT idconfig_detalle as id FROM configuracion_detalle WHERE codigo_tabla='_TIPO_COMPROBANTE' AND nombre_corto='VOUCHER DE PAGO'");
    $respuesta_tipo_cons = mysqli_fetch_assoc($consultar_tipo_cons);
    $tipo_constancia = $respuesta_tipo_cons['id'];

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['medio_pago'] = $idmedio_pago;
        $data['tipo_constancia'] = $tipo_constancia;
    } else {
        $data['status'] = 'bad';
        $data['urlRegistroCliente'] = $NAME_SERVER."views/M02_Clientes/M02SM01_RegistroCliente/M02SM01_RegistroCliente.php?Vsr=".$doc_codificado;
        $data['data'] = 'No se encontro datos para el documento ingresado.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR INFORMACION LOTE************************** */
if (isset($_POST['ReturnInfoLote'])) {
    $IdeLote = $_POST['idLote'];

    $query = mysqli_query($conection, "SELECT 
	format(tbl1.area,2) as area, 
	tbl2.texto1 as moneda, 
	format(tbl1.valor_con_casa,2) as valoLoteCasa , 
	format(tbl1.valor_sin_casa,2) as valorLoteSolo ,
	(SELECT cnf.idconfig_detalle FROM configuracion_detalle cnf WHERE cnf.codigo_item=gpm.tipo_casa AND cnf.codigo_tabla='_TIPO_CASA') AS tipoCasa
	FROM gp_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl2 on tbl1.tipo_moneda=tbl2.codigo_item
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=tbl1.idmanzana
    where tbl1.idlote='$IdeLote' LIMIT 1");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos para el documento ingresado.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************INSERTAR NUEVO REGISTRO RESERVACION******************* */
if (isset($_POST['ReturnGuardarReservacion'])) {

    $IdCliente = $_POST['idCliente'];

    $IdLote = $_POST['idLote'];

    $Descripcion = $_POST['descripcion'];

    $MontoReservado = $_POST['montoReservado'];

    $TipoMoneda = $_POST['tipoMoneda'];

    $FechaIni = $_POST['fechaIni'];
    $FechaIni = !empty($FechaIni) ? "'$FechaIni'" : "NULL";

    $FechaFin = $_POST['fechaFin'];
    $FechaFin = !empty($FechaFin) ? "'$FechaFin'" : "NULL";

    $TipoCasa = $_POST['tipoCasa'];
    $TipoCasa = !empty($TipoCasa) ? "'$TipoCasa'" : "NULL";

    $ficheroVoucher = $_POST['ficheroVoucher'];
    $ficheroVoucher = !empty($ficheroVoucher) ? "'$ficheroVoucher'" : "NULL";

    $cbxTipoMonedaPrecio = $_POST['cbxTipoMonedaPrecio'];
    $cbxTipoMonedaPrecio = !empty($cbxTipoMonedaPrecio) ? "'$cbxTipoMonedaPrecio'" : "NULL";

    $txtPrecioNegocio = $_POST['txtPrecioNegocio'];
    
    $ficheroVoucher = $_POST['ficheroVoucher'];
    
    $txtTipoCambio = $_POST['txtTipoCambio'];
    $txtMontoPagado = $_POST['txtMontoPagado'];
    $cbxMedioPago = $_POST['cbxMedioPago'];
    $cbxTipoComprobante = $_POST['cbxTipoComprobante'];
    $cbxAgenciaBancaria = $_POST['cbxAgenciaBancaria'];
    $txtNumeroOperacion = $_POST['txtNumeroOperacion'];
	
	$cbxVendedor = $_POST['cbxVendedor'];
	
	$IdUser = $_POST['IdUser'];
    $IdUser = decrypt($IdUser, "123");

    $path = $ficheroVoucher;
    $file = new SplFileInfo($path);
    $extension  = $file->getExtension();
    $desc_codigo="voucher-";
    $name_file = "voucher";
    if(!empty($ficheroVoucher)){
		$name_file = $desc_codigo.$IdLote."_RESERVA_".$IdLote.".".$extension;
	}

    if(empty($Descripcion)){
        $Descripcion ="Reserva de Lote";
    }
	$MontoReservado = str_replace(',', '',$MontoReservado);
	$txtPrecioNegocio = str_replace(',', '',$txtPrecioNegocio);
    $txtMontoPagado = str_replace(',', '',$txtMontoPagado);
    
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$IdUser'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $IdUser = $respuesta_idusuario['id'];

    $query = mysqli_query($conection, "call pa_gp_reservacion_insertar(
    '$IdCliente',
    '$IdLote',
    '$Descripcion',
    '$MontoReservado',
    '$TipoMoneda',
    $FechaIni,
    $FechaFin,
    $TipoCasa,
    '$cbxVendedor',
    '$name_file',
    $cbxTipoMonedaPrecio,
    '$txtPrecioNegocio',
    '$txtTipoCambio',
    '$txtMontoPagado',
    '$IdUser')");
    
    $insertar_pagos = mysqli_query($conection, "INSERT INTO gp_pagos_venta(id_lote, id_cliente, tipo_moneda, importe, fecha_pago, voucher, categoria, correlativo, registro_user) 
    VALUES ($IdLote, $IdCliente, $TipoMoneda, '$MontoReservado', '$fecha','$name_file', '1','1','$IdUser')");

    if($insertar_pagos){

        
        //CONSULTANDO CODIGO DE OPERACION
        $codigo_operacion="";
        $consultar_idoperacion = mysqli_query($conection, "SELECT texto1 as codigo FROM configuracion_detalle WHERE codigo_tabla='_MEDIO_PAGO' AND idconfig_detalle='$cbxMedioPago'");
        $contar_respuesta = mysqli_num_rows($consultar_idoperacion);
        if($contar_respuesta>0){
            $respuesta_idoperacion = mysqli_fetch_assoc($consultar_idoperacion);
            $codigo_operacion = $respuesta_idoperacion['codigo'];
        }
        
        //CONSULTANDO CODIGO FLUJO CAJA
        $codigo_flujo="";
        $consultar_flujo = mysqli_query($conection, "SELECT codigo_sunat as codigo FROM configuracion_detalle WHERE codigo_tabla='_FLUJO_CAJA' AND nombre_corto='CONTADO'");
        $cont_respuesta = mysqli_num_rows($consultar_flujo);
        if($cont_respuesta>0){
            $respuesta_flujo = mysqli_fetch_assoc($consultar_flujo);
            $codigo_flujo = $respuesta_flujo['codigo'];
        }
        
        //CONSULTANDO GLOSA
        $glosa = "";
        $consultar_glosa = mysqli_query($conection, "SELECT 
        concat('PAGO RESERVA - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2),' LT ',SUBSTRING(gpl.nombre,6,2)) as lote
        FROM  gp_lote AS gpl
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        WHERE gpl.idlote='$IdLote'");
        $respuesta_glosa = mysqli_fetch_assoc($consultar_glosa);
        $glosa = $respuesta_glosa['lote'];
        
        $fecha_p = date('Y-m-d'); 
        
        //INSERTAR PAGO CABECERA
        $insertar_cabecera = mysqli_query($conection,"INSERT INTO gp_pagos_cabecera(id_venta, id_cronograma, moneda_pago, tipo_cambio,importe_pago, medio_pago, tipo_comprobante, agencia_bancaria, operacion, numero, fecha_pago, glosa, sede, pagado, tipo_pago, flujo_caja, estado, visto_bueno) 
        VALUES ('0','$IdLote','$TipoMoneda', '$txtTipoCambio','$txtMontoPagado','$cbxMedioPago','$cbxTipoComprobante','$cbxAgenciaBancaria', '$codigo_operacion','$txtNumeroOperacion','$fecha_p','$glosa','00001','$MontoReservado','1','$codigo_flujo','2','1')");

        if ($insertar_cabecera) {
        
            //CONSULTA ID PAGO CABECERA
            $consultar_idcabecera = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='0' AND id_cronograma='$IdLote' AND pagado='$MontoReservado'");
            $respuesta_idcabecera = mysqli_fetch_assoc($consultar_idcabecera);
            $idcabecera = $respuesta_idcabecera['id'];
            //INSERTAR PAGO DETALLE
            
            $insertar_detalle = mysqli_query($conection,"INSERT INTO gp_pagos_detalle(id_venta, idpago, moneda_pago, tipo_cambio, monto_soles, monto_dolares,importe_pago, medio_pago, tipo_comprobante, agencia_bancaria, nro_operacion, fecha_pago, debe_haber, estado,pagado, voucher) 
            VALUES ('0','$idcabecera','$TipoMoneda', '$txtTipoCambio', '0', '0', '$txtMontoPagado','$cbxMedioPago','$cbxTipoComprobante','$cbxAgenciaBancaria','$txtNumeroOperacion','$fecha_p','H','1','$MontoReservado','$name_file')"); 
      
            if ($insertar_detalle) {

                //ACTUALIZAR ESTADO DE LOTE
                $actualiza_lote = mysqli_query($conection, "UPDATE gp_lote SET estado='2' WHERE idlote='$IdLote'");        

                $data['status'] = 'ok';
                $data['data'] = 'Se guardo con exito';
                $data['name'] = $name_file;

            } else {
                if (!$insertar_detalle) {
                    $data['dataDB'] = mysqli_error($conection);
                }
                $data['status'] = 'bad';
                $data['data'] = 'Ocurrio un problema al guardar el registro. (Registro Pago-Detalle)';
            }

        } else {
            if (!$insertar_cabecera) {
                $data['dataDB'] = mysqli_error($conection);
            }
            $data['status'] = 'bad';
            $data['data'] = 'Ocurrio un problema al guardar el registro.(Registro Pago-Cabecera)';
        }

    }else {
        if (!$insertar_pagos) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema al guardar el registro. (Registro Adjunto)';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnMostrarVoucher'])){
    
    $idRegistro = $_POST['idRegistro'];

    $consultar_voucher = mysqli_query($conection, "SELECT voucher as voucher FROM gp_reservacion WHERE id_reservacion='$idRegistro'");
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

/**************************ACTUALIZAR NUEVO REGISTRO RESERVACION******************* */
if (isset($_POST['ReturnActualizarReservacion'])) {

    $IdReservacion = $_POST['idReservacion'];
    $IdReservacion = !empty($IdReservacion) ? "'$IdReservacion'" : "NULL";

    $IdCliente = $_POST['idCliente'];
    $IdCliente = !empty($IdCliente) ? "'$IdCliente'" : "NULL";

    $IdLote = $_POST['idLote'];
    $IdLote = !empty($IdLote) ? "'$IdLote'" : "NULL";

    $Descripcion = $_POST['descripcion'];
    $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

    $MontoReservado = $_POST['montoReservado'];
    $MontoReservado = !empty($MontoReservado) ? "'$MontoReservado'" : "NULL";

    $TipoMoneda = $_POST['tipoMoneda'];
    $TipoMoneda = !empty($TipoMoneda) ? "'$TipoMoneda'" : "NULL";

    $FechaIni = $_POST['fechaIni'];
    $FechaIni = !empty($FechaIni) ? "'$FechaIni'" : "NULL";

    $FechaFin = $_POST['fechaFin'];
    $FechaFin = !empty($FechaFin) ? "'$FechaFin'" : "NULL";

    $TipoCasa = $_POST['tipoCasa'];
    $TipoCasa = !empty($TipoCasa) ? "'$TipoCasa'" : "NULL";
	
	$IdUser = $_POST['__IDUSUARIO'];
    $IdUser = decrypt($IdUser, "123");
    
    $cbxTipoMonedaPrecio = $_POST['cbxTipoMonedaPrecio'];
    
    $txtPrecioNegocio = $_POST['txtPrecioNegocio'];
    
    $cbxVendedor = $_POST['cbxVendedor'];

    $query = mysqli_query($conection, "call pa_gp_reservacion_actualizar(
        $IdReservacion,
        $IdCliente,
        $IdLote,
        $Descripcion,
        $MontoReservado,
        $TipoMoneda,
        $FechaIni,
        $FechaFin,
        $TipoCasa,
        '$IdUser',
        '$cbxTipoMonedaPrecio',
        '$txtPrecioNegocio',
        '$cbxVendedor')");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se guardo con exito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************ELIMINAR  NUEVO REGISTRO RESERVACION******************* */
if (isset($_POST['ReturnEliminarReservacion'])) {

    $IdReservacion = $_POST['idReservacion'];
    $IdReservacion = !empty($IdReservacion) ? "'$IdReservacion'" : "NULL";

    $query = mysqli_query($conection, "call pa_gp_reservacion_eliminar(
        $IdReservacion
        )");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se elimino con exito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema al eliminar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************LISTA PAGINADA RESERVACIONES******************* */
if (isset($_POST['ReturnReservacionPag'])) {

    $Documento = $_POST['documento'];
    $TipoCasa = $_POST['tipoCasa'];
    $Desde = $_POST['desde'];
    $Hasta = $_POST['hasta'];

    $bxFiltroProyectoReserva = $_POST['bxFiltroProyectoReserva'];
    $bxFiltroZonaReserva = $_POST['bxFiltroZonaReserva'];
    $bxFiltroManzanaReserva = $_POST['bxFiltroManzanaReserva'];
    $bxFiltroLoteReserva = $_POST['bxFiltroLoteReserva'];

    $ColumnaOrden = $_POST['columns'][$_POST['order']['0']['column']]['data'] . $_POST['order']['0']['dir'];

    $Start = intval($_POST['start']);
    $Length = intval($_POST['length']);
    if ($Length > 0) {
        $Start = (($Start / $Length) + 1);
    }
    if ($Start == 0) {
        $Start = 1;
    }
    $query = mysqli_query($conection, "call pa_gp_reservacion_listar_paginado($Start,$Length,'$ColumnaOrden',
    '$Documento','$TipoCasa','$Desde','$Hasta','$bxFiltroProyectoReserva','$bxFiltroZonaReserva','$bxFiltroManzanaReserva','$bxFiltroLoteReserva')");

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {

            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
        $data['host'] = $RUTA_ARCHIVOS_ADJUNTOS_3;
    } else {
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR MONTO REFERENCIAL RESERVA************************** */
if (isset($_POST['ReturnMontoReservaReferencial'])) {
    $query = mysqli_query($conection, "SELECT format(valor,2) as valor from gp_configuracion_parametro where llave='_MONTO_RESERVA' LIMIT 1");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['fecha'] = $fecha;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro parametro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR RANGO DE FECHA REFERENCIAL RESERVA************************** */
if (isset($_POST['ReturnDiasReservaReferencial'])) {
    $query = mysqli_query($conection, "SELECT valor from gp_configuracion_parametro where llave='_DIAS_RESERVA' LIMIT 1");
    if ($query->num_rows > 0) {
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

/*******************SUMAR FECHA************************** */
if (isset($_POST['ReturnSumarFechaReferencial'])) {
    $Desde = $_POST['desde'];
    $Dias = $_POST['dias'];
    $query = mysqli_query($conection, "select DATE_ADD('$Desde', interval $Dias DAY) as fecha");
    if ($query->num_rows > 0) {
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

/*******************BUSCAR INFORMACION LOTE SEGMENTADA************************** */
if (isset($_POST['ReturnLoteSegmentado'])) {
    $IdeLote = $_POST['idLote'];

    $query = mysqli_query($conection, "SELECT 
    format(tbl1.area,2) as area, 
    tbl2.texto1 as moneda,
     format(tbl1.valor_con_casa,2) as valoLoteCasa , 
     format(tbl1.valor_sin_casa,2) as valorLoteSolo ,
    (SELECT cnf.idconfig_detalle FROM configuracion_detalle cnf WHERE cnf.codigo_item=tbl3.tipo_casa AND cnf.codigo_tabla='_TIPO_CASA') AS TipoCasa,
     tbl1.idlote as idLote,
     tbl3.idmanzana as idManzana,
     tbl4.idzona as idZona,
     tbl5.idproyecto as idProyecto
     FROM gp_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl2 on tbl1.tipo_moneda=tbl2.idconfig_detalle
    inner join gp_manzana tbl3 on tbl1.idmanzana=tbl3.idmanzana
    inner join gp_zona tbl4 on tbl3.idzona=tbl4.idzona
    inner join gp_proyecto tbl5 on tbl5.idproyecto=tbl4.idproyecto
    where tbl1.idlote='$IdeLote' LIMIT 1");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnEditarReserva'])) {
 
    $idReservacion = $_POST['idReservacion'];

    $query = mysqli_query($conection, "SELECT 
	gpr.id_reservacion as idReservacion,
    gpr.id_cliente as idCliente,
    gpr.id_vendedor as IdUser,
    dc.tipodocumento as tipoDocumento,
    dc.documento as documento,
    dc.nombres as nombres,
    dc.apellido_paterno as apellidoPaterno,
    dc.apellido_materno as apellidoMaterno,
    gpy.idproyecto as idProyecto,
    gpz.idzona as idZona,
    gpm.idmanzana as idManzana,
    gpl.idlote as idLote,
    gpl.area as area,
    cdx.texto1 as siglaMoneda,
    format(gpl.valor_con_casa,2) as valorLoteCasa,
    format(gpl.valor_sin_casa,2) as valorLoteSolo,
    gpr.monto_reservado as montoReserva,
    gpr.tipo_moneda_reserva as tipoMonedaReserva,
    gpr.tipo_casa as tipoCasa,
    gpr.fecha_inicio_reserva as inicioReservaCadena,
    gpr.fecha_fin_reserva as finReservaCadena,
    gpr.descripcion as descripcion,
    gpr.moneda_precio as monedaPrecio,
    gpr.importe_precio as precioNegociado,
    gpr.id_vendedor as id_vendedor
	FROM gp_reservacion gpr
    INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
    INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gpl.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
    WHERE gpr.id_reservacion='$idReservacion'");

    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['valor'] = $idReservacion;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos para el documento ingresado.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnIrVenta'])) {

    $idReserva = $_POST['idRegistro'];
    $idUser = $_POST['idUser'];

    $consulta_idcliente = mysqli_query($conection, "SELECT id_cliente as id FROM gp_reservacion WHERE id_reservacion='$idReserva'");
    $respuesta_idcliente = mysqli_fetch_assoc($consulta_idcliente);
    $idcliente = $respuesta_idcliente['id'];

    $idcliente = encrypt($idcliente, "123");
    $idReserva = encrypt($idReserva, "123");
    $usuar = $idUser;

    $data['status'] = 'ok';
    $data['valor'] = $idReserva;
    $data['ruta'] = $NAME_SERVER."views/M03_Ventas/M03SM02_Venta/M03SM02_Venta?Vsr=".$usuar."&c=".$idcliente."&r=".$idReserva;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}



if (isset($_POST['btnMostrarClienteReserva'])) {

    $IdReg = $_POST['idReservacion'];
    
    $query = mysqli_query($conection, "SELECT
    gpr.id_reservacion as id,
    dc.id as idcliente,
    dc.documento as documento,
    concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as datos,
    dc.celular_1 as celular,
    dc.email as correo
    FROM gp_reservacion gpr
    INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
    WHERE gpr.id_reservacion='$IdReg'");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnCargarListaReservas'])) {

    $IdReg = $_POST['idReservacion'];
	
	$query = mysqli_query($conection, "SELECT
		gpco.idcopropietarios as id,
		dc.documento as documento,
		CONCAT(dc.apellido_paterno, ' ', dc.apellido_materno, ' ', dc.nombres) as datos,
		dc.celular_1 as celular,
		dc.email as correo,
		dc.adjunto_dni as adjunto
		FROM gp_copropietarios gpco
		INNER JOIN datos_cliente AS dc ON dc.id = gpco.id_cliente_copropietario
		WHERE gpco.idoperacion = '$IdReg' AND gpco.estado = 1");

	
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'documento' => $row['documento'],
                'datos' => $row['datos'],
                'celular' => $row['celular'],
                'correo' => $row['correo'],
                'adjunto' => $row['adjunto']
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


/*if (isset($_POST['ReturnGuardarCopropietario'])) {

    $idCliente = $_POST['idCliente'];  // ID del cliente principal
    $idUsuario = $_POST['IdUser'];  // Usuario que registra
    $idReserva = $_POST['txtidReserva'];  // ID de la operación/reserva
	
    $nroDoc = $_POST['txtNroDocCop'];
    $telefono = $_POST['txtTelefonoCop'];
    $correo = $_POST['txtCorreoCop'];
	$documento = $_POST['documento'];
	
	$apellidos = trim($_POST['txtApellido']);
	
	$nombre = trim($_POST['txtNombreC']);
	
	$__ID_USER = $_POST['__ID_USER'];

    $idUsuario = decrypt($__ID_USER,"123");
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$idUsuario'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $idUsuario=$respuesta_idusu['id'];
	
	// Manejar apellidos
    $apellido_paterno = "";
    $apellido_materno = "";
    $apellidosArray = explode(" ", $apellidos);

    if (count($apellidosArray) == 1) {
        $apellido_paterno = $apellidosArray[0];
    } elseif (count($apellidosArray) >= 2) {
        $apellido_paterno = $apellidosArray[0];
        $apellido_materno = $apellidosArray[1];
    }

    $name_file = "Ninguno";

	if (isset($_FILES['documento']) && $_FILES['documento']['error'] === 0) {
		$extension = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
		$name_file = "documento-" . $nroDoc . "." . $extension;

		$rutaDestino = "../../M02_Clientes/M02SM01_RegistroCliente/archivos/" . $name_file;

		if (!move_uploaded_file($_FILES['documento']['tmp_name'], $rutaDestino)) {
			$name_file = "Ninguno"; // fallback si falla
		}
	}


	// Verificar si ya existe
    $verificar = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE documento='$documento'");
    if (mysqli_num_rows($verificar) > 0) {
        echo json_encode(["status" => "bad", "data" => "El copropietario ya está registrado."]);
        exit;
    }
	
	// Obtener año actual
	$codigo_anio = date("Y");

	// Buscar el último correlativo del año actual
	$consulta_correlativo = mysqli_query($conection, "SELECT MAX(codigo_correlativo) as ultimo FROM datos_cliente WHERE codigo_anio = '$codigo_anio'");
	$respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);

	// Determinar nuevo correlativo
	$codigo_correlativo = (isset($respuesta_correlativo['ultimo']) && $respuesta_correlativo['ultimo']) ? $respuesta_correlativo['ultimo'] + 1 : 1;

	// Generar código de 10 dígitos: año + correlativo con ceros a la izquierda
	$codigo = $codigo_anio . str_pad($codigo_correlativo, 6, "0", STR_PAD_LEFT);

	// Insertar en datos_cliente
	$query = "INSERT INTO datos_cliente (
		codigo, codigo_anio, codigo_correlativo, tipodocumento, documento, nacionalidad, pais_emisor_doc,
		apellido_paterno, apellido_materno, nombres,
		celular_1, email, id_usuario, id_usuario_auditoria, adjunto_dni
	) VALUES (
		'$codigo',
		'$codigo_anio',
		'$codigo_correlativo',	
		'1',
		'$nroDoc',
		'200',
		'172',
		'$apellido_paterno',
		'$apellido_materno',
		'$nombre',
		'$telefono',
		'$correo',
		'$idUsuario',
		'$idUsuario',
		'$name_file'
	)";

	
	if (mysqli_query($conection, $query)) {
        $id_cliente_copropietario = mysqli_insert_id($conection); // ID del cliente recién insertado

        // Insertar en gp_copropietarios
        $insert_coprop = mysqli_query($conection, "INSERT INTO gp_copropietarios (
            identificador, idoperacion, idcliente, id_cliente_copropietario, estado,
            registro_user, registro_control
        ) VALUES (
            '1', '$idReserva', '$idCliente', '$id_cliente_copropietario', 1,
            '$idUsuario', NOW()
        )");

        if ($insert_coprop) {
            echo json_encode(["status" => "ok", "data" => "Copropietario registrado con éxito"]);
        } else {
            echo json_encode(["status" => "bad", "data" => "Error al registrar en gp_copropietarios"]);
        }
    } else {
        echo json_encode(["status" => "bad", "data" => "Error al registrar en datos_cliente"]);
    }

}

*/
/*************** NUEVO MODELO ***********/
if (isset($_POST['ReturnGuardarCopropietario'])) {

    require_once '../../config/db.php'; // Asegúrate de tener la conexión lista

    $idCliente = $_POST['idCliente'];
    $idUsuario = $_POST['IdUser'];
    $idReserva = $_POST['txtidReserva'];
    
    $nroDoc = $_POST['txtNroDocCop'];
    $telefono = $_POST['txtTelefonoCop'];
    $correo = $_POST['txtCorreoCop'];
    
    $apellidos = trim($_POST['txtApellido']);
    $nombre = trim($_POST['txtNombreC']);

    $__ID_USER = $_POST['__ID_USER'];

    $idUsuario = decrypt($__ID_USER, "123");
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$idUsuario'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $idUsuario = $respuesta_idusu['id'];

    // 🔹 Dividir apellidos
    $apellido_paterno = "";
    $apellido_materno = "";
    $apellidosArray = explode(" ", $apellidos);

    if (count($apellidosArray) == 1) {
        $apellido_paterno = $apellidosArray[0];
    } elseif (count($apellidosArray) >= 2) {
        $apellido_paterno = $apellidosArray[0];
        $apellido_materno = $apellidosArray[1];
    }

    // 🔹 Subida de archivo
    $documento = "Ninguno";
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === 0) {
        $extension = pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
        $documento = "documento-" . $nroDoc . "." . $extension;

        $rutaDestino = "../../M02_Clientes/M02SM01_RegistroCliente/archivos/" . $documento;
        if (!move_uploaded_file($_FILES['documento']['tmp_name'], $rutaDestino)) {
            $documento = "Ninguno";
        }
    }

    // 🔹 Verificar duplicado
    $verificar = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE documento='$nroDoc'");
    if (mysqli_num_rows($verificar) > 0) {
        echo json_encode(["status" => "bad", "data" => "El copropietario ya está registrado."]);
        exit;
    }

    // 🔹 Generar código único
    $codigo_anio = date("Y");
    $consulta_correlativo = mysqli_query($conection, "SELECT MAX(codigo_correlativo) as ultimo FROM datos_cliente WHERE codigo_anio = '$codigo_anio'");
    $respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);
    $codigo_correlativo = (isset($respuesta_correlativo['ultimo']) && $respuesta_correlativo['ultimo']) ? $respuesta_correlativo['ultimo'] + 1 : 1;
    $codigo = $codigo_anio . str_pad($codigo_correlativo, 6, "0", STR_PAD_LEFT);

    // 🔹 Insertar en datos_cliente
    $query = "INSERT INTO datos_cliente (
        codigo, codigo_anio, codigo_correlativo, tipodocumento, documento, nacionalidad, pais_emisor_doc,
        apellido_paterno, apellido_materno, nombres,
        celular_1, email, id_usuario, id_usuario_auditoria, adjunto_dni
    ) VALUES (
        '$codigo',
        '$codigo_anio',
        '$codigo_correlativo',
        '1',
        '$nroDoc',
        '200',
        '172',
        '$apellido_paterno',
        '$apellido_materno',
        '$nombre',
        '$telefono',
        '$correo',
        '$idUsuario',
        '$idUsuario',
        '$documento'
    )";

    if (mysqli_query($conection, $query)) {
        $id_cliente_copropietario = mysqli_insert_id($conection); // Nuevo ID

        // 🔹 Insertar en gp_copropietarios
        $insert_coprop = mysqli_query($conection, "INSERT INTO gp_copropietarios (
            identificador, idoperacion, idcliente, id_cliente_copropietario, estado,
            registro_user, registro_control
        ) VALUES (
            '1', '$idReserva', '$idCliente', '$id_cliente_copropietario', 1,
            '$idUsuario', NOW()
        )");

        if ($insert_coprop) {
            echo json_encode(["status" => "ok", "data" => "Copropietario registrado con éxito"]);
        } else {
            echo json_encode(["status" => "bad", "data" => "Error al registrar en gp_copropietarios"]);
        }
    } else {
        echo json_encode(["status" => "bad", "data" => "Error al registrar en datos_cliente"]);
    }
}


if (isset($_POST['btnEliminarCopropiet'])) {

    $IdReg = $_POST['idCoprop'];

    $query = mysqli_query($conection, "UPDATE gp_copropietarios 
        SET estado = '0' 
        WHERE idcopropietarios = '$IdReg'");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Registro eliminado correctamente.';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo eliminar el registro. ' . mysqli_error($conection);
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/*************** NUEVO MODELO ***********/


/**************************INSERTAR NUEVO REGISTRO TRABAJADOR******************* */
if (isset($_POST['ReturnGuardarRegCliente'])) {

    $TipoDocumento = $_POST['cbxTipoDocumento'];
    $Documento = $_POST['txtDocumento'];
    $Nacionalidad = $_POST['cbxNacionalidad'];
    $PaisEmisorDocumento = $_POST['cbxPaisEmisorDocumento'];
    $ApellidoPaterno = $_POST['txtApellidoPaterno'];
    $ApellidoMaterno = $_POST['txtApellidoMaterno'];
    $Nombres = $_POST['txtNombres'];
    $DepartamentoNacimiento = $_POST['cbxDepartamentoNacimiento'];
    $ProvinciaNacimiento = $_POST['cbxProvinciaNacimiento'];
    $PaisNacimiento = $_POST['cbxPaisNacimiento'];
    $FechaNacimiento = $_POST['txtFechaNacimineto'];
    $Sexo = $_POST['cbxSexo'];
    $TipoVia = $_POST['cbxTipoVia'];
    $NombreVia = $_POST['txtNombreVia'];
    $NroVia = $_POST['txtNroVia'];
    $NroDpto = $_POST['txtNroDpto'];
    $Interior = $_POST['txtInterior'];
    $Mz = $_POST['txtMz'];
    $Lt = $_POST['txtLt'];
    $Km = $_POST['txtKm'];
    $Block = $_POST['txtBlock'];
    $Etapa = $_POST['txtEtapa'];
    $TipoZona = $_POST['cbxTipoZona'];
    $NombreZona = $_POST['txtNombreZona'];
    $Referencia = $_POST['txtReferencia'];
    $DistritoDireccion = $_POST['cbxDistritoDir'];
    $ProvinciaDireccion = $_POST['cbxProvinciaDir'];
    $DepartamentoDireccion = $_POST['cbxDepartamentoDir'];
    $Telefono = $_POST['txtTelefono'];
    $Celular = $_POST['txtCelular'];
    $Celular2 = $_POST['txtCelular2'];
    $Correo = $_POST['txtCorreo'];
    $EstadoCivil = $_POST['cbxEstadoCivil'];
    $SituacionDomiciliaria = $_POST['cbxSituacionDomiciliaria'];

    $__ID_USER = $_POST['__ID_USER'];

    $idUsuario = decrypt($__ID_USER,"123");
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$idUsuario'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $idUsuario=$respuesta_idusu['id'];
	
	//VALIDAR si ya existe un cliente con ese documento
	$validar_doc = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE documento='$Documento' AND esta_borrado=0");
	if (mysqli_num_rows($validar_doc) > 0) {
		$data['status'] = 'warning';
		$data['data'] = 'Ya existe un cliente registrado con el mismo número de documento.';
		echo json_encode($data, JSON_PRETTY_PRINT);
		exit;
	}

    
    //CODIGO Y CORRELATIVO DEL CLIENTE
    $anio = date('Y'); 
    $consultar_correlativo = mysqli_query($conection, "SELECT max(codigo_correlativo) as correlativo FROM datos_cliente WHERE codigo_anio='$anio' AND esta_borrado=0");
    $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
    $dato_correlativo = $respuesta_correlativo['correlativo'];
    $dato_correlativo = $dato_correlativo + 1;

    $dato_codigo_desc = "";
    $length = 6;
    $dato_codigo_desc = substr(str_repeat(0, $length).$dato_correlativo, - $length);
    $nom_codigo = $anio.$dato_codigo_desc;

    $txtCodigoCliente = $nom_codigo;
    $txtCodigoAnio = $anio;
    $txtCodigoCorrelativo = $dato_correlativo;
    // ======================

    $query = mysqli_query($conection, "INSERT INTO datos_cliente( 
    tipodocumento, 
    documento, 
    nacionalidad, 
    pais_emisor_doc, 
    apellido_paterno, 
    apellido_materno, 
    nombres, 
    id_fndepartamento, 
    id_fnprovincia, 
    id_fnpais, 
    fec_nacimiento, 
    id_sexo, 
    tipo_via, 
    nombre_via, 
    nro_via, 
    nro_dpto, 
    interior, 
    manzana, 
    lote, 
    km, 
    block_dir, 
    etapa, 
    tipo_zona, 
    nombre_zona, 
    referencia, 
    id_dom_distrito, 
    id_dom_provincia, 
    id_dom_departamento, 
    telefono, 
    celular_1,
    celular_2, 
    email, 
    id_estado_civil, 
    situacion_domiciliaria,
    id_usuario,
    id_usuario_auditoria,
    id_vendedor,
    codigo, 
    codigo_anio, 
    codigo_correlativo)
    VALUES (
    '$TipoDocumento',
    '$Documento',
    '$Nacionalidad',
    '$PaisEmisorDocumento',
    '$ApellidoPaterno',
    '$ApellidoMaterno',
    '$Nombres',
    '$DepartamentoNacimiento',
    '$ProvinciaNacimiento',
    '$PaisNacimiento',
    '$FechaNacimiento',
    '$Sexo',
    '$TipoVia',
    '$NombreVia',
    '$NroVia',
    '$NroDpto',
    '$Interior',
    '$Mz',
    '$Lt',
    '$Km',
    '$Block',
    '$Etapa',
    '$TipoZona',
    '$NombreZona',
    '$Referencia',
    '$DistritoDireccion',
    '$ProvinciaDireccion',
    '$DepartamentoDireccion',
    '$Telefono',
    '$Celular',
    '$Celular2',
    '$Correo',
    '$EstadoCivil',
    '$SituacionDomiciliaria',
    '$idUsuario',
    '$idUsuario',
    '$idUsuario',
    '$txtCodigoCliente',
    '$txtCodigoAnio',
    '$txtCodigoCorrelativo'    
     )");


    if ($query) {
		// Recupera el cliente recién insertado
		$ultimoCliente = mysqli_query($conection, "SELECT id, documento, nombres, apellido_paterno, apellido_materno FROM datos_cliente WHERE documento = '$Documento' ORDER BY id DESC LIMIT 1");
		$clienteData = mysqli_fetch_assoc($ultimoCliente);
		
        $data['status'] = 'ok';
        $data['data'] = 'Se guardó con éxito';
		$data['cliente'] = $clienteData; // cliente

    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro.';
        //$data['path'] = $documentoCliente;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

