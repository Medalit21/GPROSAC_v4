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

if(isset($_POST['btnMostrarTipoCambio'])){
    
    $fecha = date('Y-m-d');
    $__ID_USER = $_POST['__ID_USER'];

    //CONSULTA TIPO DE CAMBIO DEL DIA
    $consultar_tipocambio = mysqli_query($conection, "SELECT valor as tp FROM configuracion_tipo_cambio WHERE fecha='$fecha'");
    $respuesta_tipocambio = mysqli_num_rows($consultar_tipocambio); 
    
    $tipoCambio = "0";
    if($respuesta_tipocambio>0){
        $registro = mysqli_fetch_assoc($consultar_tipocambio);
        $tipoCambio= $registro['tp'];
    }
    
    $url = $NAME_SERVER."views/M00_Home/M01_Home/tipo_cambio.php?Vsr=".$__ID_USER;

    $data['status'] = 'ok';
    $data['tipoCambio'] = $tipoCambio;
    $data['url'] = $url;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
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
        $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idmanzana' AND estado IN (5,6) ORDER BY correlativo ASC");

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

if (isset($_POST['ListarProyectosDefecto'])) {

        $query = mysqli_query($conection, "SELECT gpp.idproyecto as valor, concat(gpp.nombre,' - ',ud.nombre) as texto FROM gp_proyecto gpp INNER JOIN ubigeo_distrito AS ud ON ud.codigo=gpp.distrito WHERE gpp.estado='1' ORDER BY gpp.idproyecto ASC");

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

if (isset($_POST['ListarZonasDefecto'])) {

        $idproyectoo = $_POST['idproy'];
        if(empty($idproyectoo)){
            $idproyectoo = 1;
        }
        $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idproyectoo' AND estado='1' ORDER BY nombre");

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
    
    $cbxFiltroProyectoPC = isset($_POST['cbxFiltroProyectoPC']) ? $_POST['cbxFiltroProyectoPC'] : Null;
    $cbxFiltroProyectoPCr = trim($cbxFiltroProyectoPC);
    
    $cbxEstadoC = isset($_POST['cbxFiltroEstadoPC']) ? $_POST['cbxFiltroEstadoPC'] : Null;
    $cbxEstadoCr = trim($cbxEstadoC);

    $bxFiltroZonaPC = isset($_POST['bxFiltroZonaPC']) ? $_POST['bxFiltroZonaPC'] : Null;
    $bxFiltroZonaPCr = trim($bxFiltroZonaPC);

    $bxFiltroManzanaPC = isset($_POST['bxFiltroManzanaPC']) ? $_POST['bxFiltroManzanaPC'] : Null;
    $bxFiltroManzanaPCr = trim($bxFiltroManzanaPC);
    
    $bxFiltroLotePC = isset($_POST['bxFiltroLotePC']) ? $_POST['bxFiltroLotePC'] : Null;
    $bxFiltroLotePCr = trim($bxFiltroLotePC);
    
    $cbxFiltroEstadoValPC = isset($_POST['cbxFiltroEstadoValPC']) ? $_POST['cbxFiltroEstadoValPC'] : Null;
    $cbxFiltroEstadoValPCr = trim($cbxFiltroEstadoValPC);
    
    
    $query_documento = "";
    $query_fecha = "";
    $query_bancos = "";
    $query_proyecto="";
    $query_ec="";
    $query_ev="";
    $query_zona="";
    $query_manzana="";
    $query_lote="";
    
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
   
    if(!empty($cbxFiltroProyectoPCr)){
        $query_proyecto = "AND gpy.idproyecto='$cbxFiltroProyectoPCr'";
    }
    
    
    if(!empty($cbxEstadoCr)){
        $query_ec = "AND gppd.estado_cierre='$cbxEstadoCr'";
    }
    
     if(!empty($cbxFiltroEstadoValPCr)){
        $query_ev = "AND gppd.estado='$cbxFiltroEstadoValPCr'";
    }

    if(!empty($bxFiltroZonaPCr)){
        $query_zona = "AND gpz.idzona='$bxFiltroZonaPCr'";
    }
    
    if(!empty($bxFiltroManzanaPCr)){
        $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaPCr'";
    }
    
    if(!empty($bxFiltroLotePCr)){
        $query_lote = "AND gpl.idlote='$bxFiltroLotePCr'";
    }

    $query_ordenar="gppd.fecha_pago DESC";
    

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
            cdddddddx.nombre_corto as estado_pago,
            cdddddddx.texto1 as estado_pago_color,
            cdddx.nombre_corto as banco,
            cdx.nombre_corto as medio_pago,
            cddx.nombre_corto as tipo_comprobante,
            gppd.nro_operacion as nro_operacion,
            gppd.voucher as voucher,
            gppd.serie as serie,
            gppd.numero as numero,
            gppd.comprobante as comprobante,
            if(gppd.tipo_comprobante_sunat=0, 'Falta',cdddddx.nombre_corto) as tipo_comprobante_sunat,
            cddddddx.nombre_corto as estado_cierre,
            cddddddx.texto1 as estado_cierre_color,
            gppd.fecha_emision as fecha_emision,
            concat('B : ',(select count(distinct serie, numero) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle AND tipo_comprobante_sunat='03' AND esta_borrado!='1'),' / F : ',
            (select count(distinct serie, numero) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle AND tipo_comprobante_sunat='01' AND esta_borrado!='1')) as nro_comprobantes
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
            INNER JOIN configuracion_detalle AS cdddddx ON (cdddddx.idconfig_detalle=gppd.tipo_comprobante_sunat OR gppd.tipo_comprobante_sunat=0) AND cdddddx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
            INNER JOIN configuracion_detalle AS cddddddx ON cddddddx.codigo_item=gppd.estado_cierre AND cddddddx.codigo_tabla='_ESTADO_FACTURACION_PAGO'
            INNER JOIN configuracion_detalle AS cdddddddx ON cdddddddx.codigo_item=gppd.estado AND cdddddddx.codigo_tabla='_ESTADO_VALIDACION_PAGO'
            WHERE gppd.esta_borrado=0
            AND gppd.estado='2'
            AND gppd.tipo_origen != '2' -- Esta es la línea que excluye registros con tipo_origen igual a '2'
            $query_proyecto
            $query_ec
            $query_ev
            $query_documento
            $query_fecha
            $query_bancos
            $query_zona
            $query_manzana
            $query_lote
            GROUP BY gppd.idpago_detalle
            ORDER BY estado_cierre DESC, fecha_pago DESC"); 

     
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
                'estado_pago_color' => $row['estado_pago_color'],
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
                'estado_cierre_color' => $row['estado_cierre_color'],
                'tipo_comprobante_sunat' => $row['tipo_comprobante_sunat'],
                'fecha_emision' => $row['fecha_emision'],
                'nro_comprobantes' => $row['nro_comprobantes']
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
        $data['data'] = 'OcurriÃ³ un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnEditarDetalleComprobante'])) {

    $IdReg = $_POST['idRegistro'];
    
    $query = mysqli_query($conection, "SELECT 
    gppd.idpago_comprobante as id,
    gppd.fecha_emision as fecha_emision,
    cdx.idconfig_detalle as tipoComprobante,
    gppd.serie as serie,
    gppd.numero as numero,
    gppd.cliente_tipodoc as tipo_documento,
    gppd.cliente_doc as documento,
    gppd.cliente_datos as cliente,
    gppd.tipo_moneda as tipo_moneda,
    gppd.tipo_cambio as tipo_cambio,
    gppd.pagado as total_pagado,
    gppd.fecha_vencimiento as fecha_vencimiento
    FROM gp_pagos_detalle_comprobante gppd
    INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppd.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
    WHERE gppd.idpago_comprobante='$IdReg'");
    
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'OcurriÃ³ un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnGuardarComprobante'])) {

    $__IDPAGO_DET = $_POST['__IDPAGO_DET'];
    $__IDPAGO_DET_COMPROBANTE = $_POST['__IDPAGO_DET_COMPROBANTE'];
    $txtFechaEmisionCV = $_POST['txtFechaEmisionCV'];
    $cbxTipoComprobanteCV = $_POST['cbxTipoComprobanteCV'];
    $txtSerieCV = $_POST['txtSerieCV'];
    $txtNumeroCV = $_POST['txtNumeroCV'];
    $ComprobanteCV = $_POST['ComprobanteCV'];
    $valor_comprobante="";
    $name_file = "comprobante";

	$cbxTipoDoc = $_POST['cbxTipoDoc'];
	$txtNroDocumento = $_POST['txtNroDocumento'];
	$txtDatosCliente = $_POST['txtDatosCliente'];
	$txtTipoMoneda = $_POST['txtTipoMoneda'];
	$txtTotalPagado = $_POST['txtTotalPagado'];
	$txtFechaVencimiento = $_POST['txtFechaVencimiento'];
	$cbxConceptos = $_POST['cbxConceptos'];
	$txtTipoCambio = $_POST['txtTipoCambio'];
	
	if(!empty($ComprobanteCV)){
        $path = $ComprobanteCV;
        $file = new SplFileInfo($path);
        $extension  = $file->getExtension();
        $desc_codigo="comprobante-";        
        if(!empty($ComprobanteCV)){
            $name_file = $desc_codigo.$__IDPAGO_DET.".".$extension;
        }
        $valor_comprobante = ",comprobante='$name_file'";
    }
    
    $operacion = "";

    if(empty($__IDPAGO_DET_COMPROBANTE)){
        
        $consultar_tipocom = mysqli_query($conection, "SELECT codigo_sunat as codigo FROM configuracion_detalle WHERE idconfig_detalle='$cbxTipoComprobanteCV'");
        $respuesta_tipocom = mysqli_fetch_assoc($consultar_tipocom);
        $codigo = $respuesta_tipocom['codigo'];
        
        $correlativo = $txtNumeroCV;
        $desc_correlativo="";
        $number = $txtNumeroCV;
        $length = 8;
        $desc_correlativo = substr(str_repeat(0, $length).$number, - $length);
    
        $query = mysqli_query($conection, "INSERT INTO gp_pagos_detalle_comprobante(idpago_detalle, serie, numero, cliente_tipodoc, cliente_doc, cliente_datos, tipo_moneda, pagado, fecha_emision, fecha_vencimiento, comprobante_adj, tipo_comprobante_sunat, tipo_cambio) VALUES 
        ('$__IDPAGO_DET','$txtSerieCV','$desc_correlativo','$cbxTipoDoc','$txtNroDocumento','$txtDatosCliente','$txtTipoMoneda','$txtTotalPagado','$txtFechaEmisionCV','$txtFechaVencimiento','$name_file','$codigo','$txtTipoCambio')");
        
        $operacion = "registra";
        
        
    }else{
        
        $consultar_tipocom = mysqli_query($conection, "SELECT codigo_sunat as codigo FROM configuracion_detalle WHERE idconfig_detalle='$cbxTipoComprobanteCV'");
        $respuesta_tipocom = mysqli_fetch_assoc($consultar_tipocom);
        $codigo = $respuesta_tipocom['codigo'];
        
        $correlativo = $txtNumeroCV;
        $desc_correlativo="";
        $number = $txtNumeroCV;
        $length = 8;
        $desc_correlativo = substr(str_repeat(0, $length).$number, - $length);
    
        $query_adjunto = "";
        if(!empty($ComprobanteCV)){
            $query_adjunto = "comprobante_adj='$name_file',";
        }
    
        $query = mysqli_query($conection, "UPDATE gp_pagos_detalle_comprobante SET serie='$txtSerieCV', 
        numero='$desc_correlativo', 
        cliente_tipodoc='$cbxTipoDoc', 
        cliente_doc='$txtNroDocumento', 
        cliente_datos='$txtDatosCliente', 
        tipo_moneda='$txtTipoMoneda', 
        pagado='$txtTotalPagado', 
        fecha_emision='$txtFechaEmisionCV', 
        fecha_vencimiento='$txtFechaVencimiento', 
        $query_adjunto
        tipo_comprobante_sunat='$codigo', 
        tipo_cambio='$txtTipoCambio'
        WHERE  idpago_comprobante='$__IDPAGO_DET_COMPROBANTE'");
        
        $operacion = "actualiza";
        
        
    }
    
    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = "Se guardaron los cambios en el pago seleccionado.";
        $data['name'] = $name_file;
        $data['operacion'] = $operacion;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/******************* INSERCION ********************/
if (isset($_POST['btnProcesarIngEg'])) {
	
	$glosa = "";
	$consultar_glosa = mysqli_query($conection, "SELECT 
	concat('PAGO LETRA - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2), ' LT ',SUBSTRING(gpl.nombre,6,2)) as dato
	FROM gp_pagos_detalle gppd
	INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
	INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
	INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gpv.id_venta
	INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
	INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
	INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
	WHERE gppd.idpago_detalle='$idpago_detalle'");
	$respuesta = mysqli_fetch_assoc($consultar_glosa);
	$glosa = $respuesta['dato'];
 
	$bxFiltroanio = $_POST['bxFiltroanio'];
    $bxFiltromeses = $_POST['bxFiltromeses'];
    $bxFiltroIngresEgres = $_POST['bxFiltroIngresEgres'];
    $id = $_POST['id'];
		/*************** CONSULTA EXTRAC IDDETALLE*****************/
		$consulta_cierre = mysqli_query($conection,"SELECT 
													gppd.idpago_detalle as iddetalle,
													gppd.idpago as idpago, 
													gppc.id_venta as id_venta
													FROM gp_pagos_detalle gppd
													INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
													INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=gppd.idpago_detalle
									 				WHERE gppd.esta_borrado=0 
													AND gppd.idpago_detalle='$id' AND gppd.estado='2' 
													ORDER BY gppd.fecha_pago DESC"); 

		$respuesta_cierre = mysqli_fetch_assoc($consulta_cierre); 
		$var_idpago = $respuesta_cierre['idpago'];
        $var_venta = $respuesta_cierre['id_venta'];

			/*************** CONSULTA PAGO CABECERA *****************/
			$consultar_pago = mysqli_query($conection, "SELECT 
														gppc.idpago as idpago,
														gppd.idpago_detalle as iddetalle,
														gppc.sede as Sede,                                    
														date_format(gppc.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
														cd.texto1 as Moneda,
														gppc.tipo_cambio as TipoCambio,
														gppc.glosa as Glosa,
														gppc.pagado as TotalImporte,
														gppc.cuenta_contable as CuentaContable,
														gppc.operacion as Operacion,
														gppc.numero as Numero,
														gppc.accion as Accion,
														gppc.flujo_caja as Flujo,
														gppc.debe_haber as DebHab
														FROM gp_pagos_cabecera gppc
														INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
														INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
														INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=gppd.idpago_detalle								
														WHERE gppc.esta_borrado=0 AND gppdc.idpago_detalle='$id'
														ORDER BY gppc.fecha_pago");
						
						$result = mysqli_num_rows($consultar_pago);
						
					if ($result>0){

						/*inicio de numfilas_consultapago*/
						//for ($i = 1; $i <= $result; $i++){		
						
							$respuesta_pago = mysqli_fetch_assoc($consultar_pago);
							$idpago = $respuesta_pago['idpago'];
							$iddetalle = $respuesta_pago['iddetalle'];
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
							$flujo = $respuesta_pago['Flujo'];
							$debe_haber = $respuesta_pago['DebHab'];	

							/****** CONSULTA VENTA ******/							
							$consultar_pagoVC = mysqli_query($conection,"SELECT
																		'1' AS Identificador,
																		gppdc.cliente_datos AS razon_social,
																		gppdc.cliente_doc AS Ruc_Dni,
																		date_format(gppdc.fecha_emision, '%Y-%m-%d %H:%i:%s') AS Fecha,
																		'0' AS Descuento,
																		gppdc.pagado AS TotalImporte,
																		'0' AS Servicio,
																		gppdc.serie AS Serie,
																		gppdc.numero AS Numero,
																		gppdc.tipo_comprobante_sunat AS tipoCsun,
																		'0' AS IGV,
																		gppdc.tipo_moneda AS Moneda,
																		gppdc.tipo_cambio AS TipoCambio,
																		'' AS Accion,
																		'' AS TipoR,
																		'' AS SerieR,
																		'' AS NumeroR,
																		'' AS FechaR,
																		'' AS Propina, 
																		'VENTAS INTERFACE LAGUNA' AS Glosa,
																		gppdc.comprobante_adj AS ComprobantAdj
																		FROM gp_pagos_detalle_comprobante gppdc
																		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
																		INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
																		WHERE gppdc.idpago_detalle='$id'
																		order by gppdc.fecha_emision");     
									
                            $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                            $idpagoVC = $respuesta_pago2['idpago'];
                            $iddetalleVC = $respuesta_pago2['Identificador'];
                            $rsVC=$respuesta_pago2['razon_social'];
                            $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                            $fecha_pVC = $respuesta_pago2['Fecha']; 
                            $desc_pVC = $respuesta_pago2['Descuento']; 							
                            $importeTVC = $respuesta_pago2['TotalImporte'];
                            $servcVC = $respuesta_pago2['Servicio'];
                            //$sedeVC = $respuesta_pago2['Sede'];
                            $serieVC = $respuesta_pago2['Serie'];
                            $numVC = $respuesta_pago2['Numero'];
                            $tipo_codsunatVC = $respuesta_pago2['tipoCsun'];
                            $igvVC = $respuesta_pago2['IGV'];
                            $monedaVC = $respuesta_pago2['Moneda'];
                            $tipo_cambVC=$respuesta_pago2['TipoCambio'];
                            $accionVC = $respuesta_pago2['Accion'];
                            $tipo_rVC = $respuesta_pago2['TipoR'];
                            $serie_rVC = $respuesta_pago2['SerieR'];
                            $numero_rVC = $respuesta_pago2['NumeroR'];
                            $fecha_rVC = $respuesta_pago2['FechaR'];
                            $glosaVC = $glosa;
							$ComprobAdjVC = $respuesta_pago2['ComprobantAdj'];
							
                            $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                            $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
							$contadorVC = $consultaVC['contador'];
							$contadorVC = $contadorVC + 1;
							
							
							$valorUnit = ($importeTVC/2.18); 
							$igv = ($valorUnit * 0.18);
							$precioVenta = ($valorUnit + $igv);
							$valor_Inafecto = ($importeTVC - $precioVenta); 
						
						if(!empty($ComprobAdjVC)){
						    
						    if($tipo_codsunatVC == '07'){
						        $fecha_rVC = "'$fecha_rVC'";
						    }else{
						        $fecha_rVC ="NULL";
						    }
						    
						    if($tipo_cambVC > '0'){ $tipo_cambVC = $tipo_cambVC; }else{ $tipo_cambVC == '1.00'; }
							
                            $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Fecha_Vencimiento, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                            VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC','$fecha_pVC','$desc_pVC','$importeTVC','$servcVC','$sede','$serieVC','$numVC','$tipo_codsunatVC','$igv', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC',$fecha_rVC,'0','$glosaVC')");
							
							if($insertar_pagoCabVentas){
								
								$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																				gppd.idpago_detalle as iddetalle,         																				
																				date_format(gppdc.fecha_emision, '%Y-%m-%d %H:%i:%s') as Fecha,
																				'0' AS Descuento,
																				gppdc.pagado as ImportePago,
																				'0' AS Servicio,   
																				gppdc.serie as Serie,    
																				gppdc.numero as Numero,  
																				gppdc.tipo_comprobante_sunat  as TipoCS,
																				cdtx.texto1 CtaContable,    
																				'0'  as IGV,
																				cdtx.texto2 as CentroCosto,
																				gppdc.tipo_moneda as Moneda
																				FROM gp_pagos_detalle_comprobante gppdc
																				INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
																				INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
																				INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																				WHERE gppdc.idpago_detalle='$id'
																				ORDER BY gppdc.fecha_emision");
									
                                    $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
									
                                    for ($j=1; $j <= $detalleVC ; $j++) { 
									
                                        $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                        $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                        //$sedeDVC= $respuesta_detalleVC['Sede'];
                                        $fechaDVC= $respuesta_detalleVC['Fecha'];
                                        $descuentoDVC= $respuesta_detalleVC['Descuento'];
                                        $importeDVC= $respuesta_detalleVC['ImportePago'];
                                        $servicioDVC= $respuesta_detalleVC['Servicio'];
                                        $serieDVC= $respuesta_detalleVC['Serie'];
                                        $numDVC= $respuesta_detalleVC['Numero'];
                                        $tipoDVC= $respuesta_detalleVC['TipoCS'];	
                                        $cuentcDVC= $respuesta_detalleVC['CtaContable'];	
                                        $igvDVC= $respuesta_detalleVC['IGV'];	
                                        $centrocDVC= $respuesta_detalleVC['CentroCosto'];	
                                        $monedaDVC= $respuesta_detalleVC['Moneda'];	
                                        
                                        
										$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo, Moneda)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sede','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igv', '$centrocDVC', '$monedaDVC')");
                                        
										
									
                                    }									
							}
						}
							
							
							/****** CONSULTA INGRESOS ******/	
							
							if($debe_haber == "H"){		
							
								$consulta_id = mysqli_query($conection,"SELECT if(MAX(Id_Cabecera) is null ,0,MAX(Id_Cabecera)) AS contador FROM ingresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								
    							$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, id_pago, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, flujo_caja) 
    							VALUES ('$contador','$sede','$iddetalle','$idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$flujo')");
								
								
								if($insertar_pagoCabHab){
									 /***********Insertar detalle ingreso ********/
									$consultar_detalle = mysqli_query($conection, "SELECT
														gppd.idpago as id,
														gppd.idpago_detalle as id_detalle,
														gppdc.tipo_comprobante_sunat as TipoComp,
														gppdc.serie as Serie,
														gppdc.numero as Numero,
														gppdc.pagado as TotalImporte,
														if(gppdc.tipo_moneda='USD', cdx.texto2, cdx.texto3) as CuentaContable,
														gppdc.tipo_moneda as moneda,
														cdx.texto4 as CentroCosto,
														gppdc.cliente_doc as DniRuc,
														gppdc.cliente_datos as RazonSocial,
														date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
														gppd.debe_haber as DebHab
														FROM gp_pagos_detalle_comprobante gppdc
														INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
														INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
														INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
														INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
														INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppdc.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
														WHERE gppd.esta_borrado=0 AND gppdc.idpago_detalle='$id' 
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
										$dni_ruc = $respuesta_detalle['DniRuc'];				
										$razon_social = $respuesta_detalle['RazonSocial'];										
										$fecha = $respuesta_detalle['FechaR'];							
										$debe_haber = $respuesta_detalle['DebHab'];	
										
										//CONSULTA SI EXISTE REGISTRO DETALLE CON LA SERIE Y NUMERO DEL COMPROBANTE
										$consultar_regdet = mysqli_query($conection, "SELECT idpago_comprobante FROM gp_pagos_detalle_comprobante WHERE serie='$serie' AND numero='$numero'");
										$respuesta_regdet = mysqli_num_rows($consultar_regdet);
										
										if($respuesta_regdet == 0){
    										//CONSULTA SI EXISTEN MAS REGISTROS CON LA MISMA SERIE Y NUMERO, PARA SUMAR LOS TOTALES.
    										$consultar_reg = mysqli_query($conection, "SELECT sum(pagado) as total FROM gp_pagos_detalle_comprobante WHERE serie='$serie' AND numero='$numero'");
    										$respuesta_reg = mysqli_Fetch_assoc($consultar_reg);
    										$total_detalle = $respuesta_reg['total'];
    										
    										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
    										VALUES ('$contador','$sede','$iddetalle','$cont', '$tipo_comprobante','$serie','$numero','$total_detalle','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','','$fecha','$debe_haber')");
										}
										
									}
									
									
									//CONSULTAR TOTAL PAGADO DE DETALLE
									$consultar_total_detalle = mysqli_query($conection, "SELECT SUM(Total) as total FROM ingresos_detalle WHERE Id_Cabecera='$contador'");
									$consultar_total_detalle = mysqli_fetch_assoc($consultar_total_detalle);
									$total = $consultar_total_detalle['total'];
									
									//ACTUALIZAR TOTAL PAGO CABECERA
									$actualizar = mysqli_query($conection, "UPDATE ingresos_cabecera SET Total='$total' WHERE Id_Cabecera='$contador'");
									
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
									if(gppd.moneda_pago = '15381', cd.texto2, cd.texto1) CuentaContable,
									gppd.moneda_pago as moneda,
									gppd.centro_costo as CentroCosto,
									dc.documento as DniRuc,
									concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as RazonSocial,
									date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									gppd.debe_haber as DebHab
									FROM gp_pagos_detalle gppd
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
									INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
									INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
									INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.tipo_comprobante_sunat AND cd.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
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
																	
										$insertar_pagoDetDeb = mysqli_query($conection,"INSERT INTO egresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
											VALUES ('$contador','$sede','$var_idpago','$cont', '$tipo_comprobante','$serie','$numero','$importe_pago','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','','$fecha','$debe_haber')");
										
									}	
								}							
							}	
						//$data = $respuesta_pago;			
						//}
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
    
    $__ID_USER = $_POST['__ID_USER'];
	$__ID_USER = decrypt($__ID_USER, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$__ID_USER'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	
	$actualiza = $fecha.' '.$hora;
    
    $ventas = "bad";
    $ingresos = "bad";

    $consulta = mysqli_query($conection, "SELECT idpago as idpago FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO_CV'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $idpago = $consultaa['idpago'];  
    
    $consultarx = mysqli_query($conection, "SELECT idpago_comprobante as comprobante FROM gp_pagos_detalle_comprobante WHERE idpago_detalle='$_ID_PAGO_CV'");
    $respuestax = mysqli_fetch_assoc($consultarx);
    $consultaaa = $respuestax['comprobante'];

    if (!empty($consultaaa)) {
        
        $consultar_total_cab = mysqli_query($conection, "SELECT ROUND(pagado,2) as total FROM gp_pagos_cabecera WHERE idpago='$idpago' AND esta_borrado='0'");
        $respuesta_total_cab = mysqli_fetch_assoc($consultar_total_cab);
        $total_cab = $respuesta_total_cab['total'];
        
        $consultar_total_det = mysqli_query($conection, "SELECT ROUND(SUM(pagado),2) as total FROM gp_pagos_detalle WHERE idpago='$idpago' AND esta_borrado='0'");
        $respuesta_total_det = mysqli_fetch_assoc($consultar_total_det);
        $total_det = $respuesta_total_det['total'];
        
		/*$consulta_agencia = mysqli_query($conection, "SELECT cc.agencia_bancaria as agencia 
													FROM gp_cuenta_contable cc
													INNER JOIN gp_pagos_detalle AS gppd ON gppd.cuenta_contable=cc.cuenta_contable
													WHERE idpago_detalle='$_ID_PAGO_CV'");
		$respuesta_agencia= mysqli_fetch_assoc($consulta_agencia);
        $agenciaBan = $respuesta_agencia['agencia'];*/
		
		$consulta_agencia = mysqli_query($conection, "SELECT 
														if(gppc.moneda_pago = '15381', cd.texto2, cd.texto3) CuentaContable
														FROM gp_pagos_detalle gppd
														INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
														INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.agencia_bancaria AND cd.codigo_tabla='_BANCOS'
														WHERE gppd.idpago_detalle='$_ID_PAGO_CV'");														
		$respuesta_consulta_agencia = mysqli_fetch_assoc($consulta_agencia);
        $respuesta_agencia = $respuesta_consulta_agencia['CuentaContable'];
		
        $dato_obs = 0;
        $data['status'] = 'ok';
		
        if($total_cab == $total_det){
            
            $dato_obs = 1;
			
            $query2 = mysqli_query($conection, "UPDATE 
								gp_pagos_cabecera gppc 
								INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
								SET gppc.cuenta_contable='$respuesta_agencia',
                                actualizado='$actualiza',
                                id_usuario_actualiza='$idusuario'
								WHERE gppd.idpago_detalle='$_ID_PAGO_CV'");
								
            $query = mysqli_query($conection, "UPDATE 
                                gp_pagos_detalle SET		
                                estado_cierre='4',
                                actualizado='$actualiza',
                                id_usuario_actualiza='$idusuario'
                                WHERE idpago_detalle='$_ID_PAGO_CV'"); 

            $data['data'] = "Se ha finalizado con exito el trabajo sobre el pago seleccionado.";
            $data['iddato'] = $_ID_PAGO_CV;
            $data['variable'] =$dato_obs;
            
            //CONSULTAR INFORMACION DE INGRESOS Y VENTAS
            
            $consultar_ingresos_cab = mysqli_query($conection, "SELECT idingresos_cabecera FROM ingresos_cabecera WHERE identificador='$_ID_PAGO_CV'");
            $respuesta_ingresos_cab = mysqli_num_rows($consultar_ingresos_cab);
            
            $consultar_ingresos_det = mysqli_query($conection, "SELECT idingresos_detalle FROM ingresos_detalle WHERE identificador='$_ID_PAGO_CV'");
            $respuesta_ingresos_det = mysqli_num_rows($consultar_ingresos_det);
            
            $cantidad = $respuesta_ingresos_cab + $respuesta_ingresos_det;
            
            if($cantidad>1){
                $ingresos = 'ok';
            }
            
            $consultar_ventas_det = mysqli_query($conection, "SELECT Id_ventaD, serie, numero FROM ventas_detalle WHERE Identificador='$_ID_PAGO_CV'");
            $respuesta_ventas_det = mysqli_num_rows($consultar_ventas_det);
            
            if($respuesta_ventas_det>0){
                
                $respuesta_ventas = mysqli_fetch_assoc($consultar_ventas_det);
                $serie = $respuesta_ventas['serie'];
                $numero = $respuesta_ventas['numero'];
                
                $consultar_ventas_cab = mysqli_query($conection, "SELECT Id_VentaC FROM ventas_cabecera WHERE serie='$serie' AND numero='$numero'");
                $respuesta_ventas_cab = mysqli_num_rows($consultar_ventas_cab);
                
                if($respuesta_ventas_det>0){
                    $ventas = 'ok';
                }
                
            }
            
            $data['ingresos'] =$ingresos;
            $data['ventas'] =$ventas;
            
        }else{
            
            $dato_obs = 2;
            $data['data'] = "El pago fue observado por un descuadre en los totales. Revisar la información del pago.";
            $data['iddato'] = $_ID_PAGO_CV;
            $data['variable'] =$dato_obs;
        }
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Es requerido que se cargue el adjunto del comprobante del pago.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnRestablecerComprobante'])) {

    $_ID_PAGO_CV = $_POST['_ID_PAGO_CV'];    
    
    $__ID_USER = $_POST['__ID_USER'];
	$__ID_USER = decrypt($__ID_USER, "123");

	//CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$__ID_USER'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	
	$actualiza = $fecha.' '.$hora;

    $query = mysqli_query($conection, "UPDATE 
        gp_pagos_detalle SET
        estado_cierre='1',
        estado_facturacion='0',
        actualizado='$actualiza',
        id_usuario_actualiza='$idusuario'
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
		gppc.operacion as Operacion,
		gppc.numero as Numero,
		gppc.accion as Accion,
		gppc.flujo_caja as Flujo,
		gppc.debe_haber as DebHab
		FROM gp_pagos_cabecera gppc
		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago
		INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
		WHERE gppc.esta_borrado=0 AND gppc.idpago='$var_idpago'
		ORDER BY gppc.fecha_pago");

		
		$result = mysqli_num_rows($consultar_pago);
		
		if ($result > 0 ){
			
			$respuesta_pago = mysqli_fetch_assoc($consultar_pago);
			$debe_haber = $respuesta_pago['DebHab'];
			
			/***********Eliminar registros de ventas cabecera y detalle************/
			$Elim_DetVentaCab=mysqli_query($conection,"DELETE FROM ventas_detalle WHERE Identificador = '$id'");
			
			$Elim_VentaCab=mysqli_query($conection,"DELETE FROM ventas_cabecera WHERE Identificador = '$id'");
			/***********Eliminar registros de ventas cabecera y detalle************/
			
			if($debe_haber == "H"){	
			
				$Elim_DetIng= mysqli_query($conection,"DELETE FROM ingresos_detalle WHERE ingresos_detalle.identificador = '$id'");
				
				$Elim_CabIng= mysqli_query($conection,"DELETE FROM ingresos_cabecera WHERE ingresos_cabecera.identificador = '$id'");
				
				$data['status'] = "ok";
                $data['data'] = "Se elimino el registro de Pagos ingresos.";
				  
			}else{								
				$Elim_DetEgr= mysqli_query($conection,"DELETE FROM egresos_detalle WHERE egresos_detalle.identificador = '$id'");
				
				$Elim_CabEgr = mysqli_query($conection,"DELETE FROM egresos_cabecera WHERE egresos_cabecera.identificador = '$id'");
				
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
        $data['data'] = 'No se pudo dar conformidad a la observaci¨®n, intente nuevamente.';
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
            $data['data'] = "Usted ha observado el pago. Espere a la respuesta de solucion por el area de cobranzas.";
        } else {
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo registrar la observaci¨®n, intente nuevamente.';
        }
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'Completar el campo OBSERVACI??N.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}



/* ================ POPUP COMPROBANTES =========================*/

if(isset($_POST['btnListarTablaDetalleComprobante'])){

    
    $__IDPAGO_DET = isset($_POST['__IDPAGO_DET']) ? $_POST['__IDPAGO_DET'] : Null;
    $__IDPAGO_DETr = trim($__IDPAGO_DET);
    

        $query = mysqli_query($conection,"SELECT
            gppdc.idpago_comprobante as id,
            gppdc.serie as serie,
			gppdc.numero as numero,
			if(gppdc.comprobante_url='',if(gppdc.comprobante_adj='','3','2'),'1') as adjunto,
			gppdc.comprobante_adj as tipcomp_1,
			gppdc.comprobante_url as tipcomp_2
            FROM gp_pagos_detalle_comprobante gppdc
            WHERE gppdc.idpago_detalle='$__IDPAGO_DETr' AND gppdc.esta_borrado='0'
            GROUP BY gppdc.serie, gppdc.numero"); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'serie' => $row['serie'],
                'numero' => $row['numero'],
                'adjunto' => $row['adjunto'],
                'tipcomp_1' => $row['tipcomp_1'],
                'tipcomp_2' => $row['tipcomp_2']
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

if (isset($_POST['btnEliminarComprobante'])) {

        $id = $_POST['id']; 
        
        //CONSULTAR SI EL COMPROBANTE ADJUNTO VIENE DE LA FACTURACION ELECTRONICA
        
        $actualizar_pagos = mysqli_query($conection, "SELECT comprobante_url as comprobante FROM gp_pagos_detalle_comprobante WHERE idpago_comprobante='$id'");
        $$actualizar_pagosr = mysqli_fetch_assoc($actualizar_pagos);
        
        $url_comprobante = $$actualizar_pagosr['comprobante'];
    
        if(empty($url_comprobante)){
            $dato_eliminar = 1;
            $eliminar_comprobante = mysqli_query($conection, "DELETE FROM gp_pagos_detalle_comprobante WHERE idpago_comprobante='$id'");
        }else{
            $dato_eliminar = 0;
        }
        
        if ($dato_eliminar==1){        
            $data['status'] = 'ok';
            $data['data'] = "Se ha eliminado el registro seleccionado.";
        } else {
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo completar la operacion, debido a que el comprobante adjunto al pago fue asociado por la facturación electrónica.';
        }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}











/* ================ PAGO RESERVAS =========================*/


if(isset($_POST['btnListarTablaPagosComprobanteReserva'])){

    
    $txtFiltroDocumentoCV = isset($_POST['txtFiltroDocumentoCV']) ? $_POST['txtFiltroDocumentoCV'] : Null;
    $txtFiltroDocumentoCVr = trim($txtFiltroDocumentoCV);
    
    $txtFiltroDesdeCV = isset($_POST['txtFiltroDesdeCV']) ? $_POST['txtFiltroDesdeCV'] : Null;
    $txtFiltroDesdeCVr = trim($txtFiltroDesdeCV);
    
    $txtFiltroHastaCV = isset($_POST['txtFiltroHastaCV']) ? $_POST['txtFiltroHastaCV'] : Null;
    $txtFiltroHastaCVr = trim($txtFiltroHastaCV);
    
    $cbxFiltroBancoCV = isset($_POST['cbxFiltroBancoCV']) ? $_POST['cbxFiltroBancoCV'] : Null;
    $cbxFiltroBancoCVr = trim($cbxFiltroBancoCV);
    
    $cbxEstadoC = isset($_POST['cbxFiltroEstadoPC']) ? $_POST['cbxFiltroEstadoPC'] : Null;
    $cbxEstadoCr = trim($cbxEstadoC);

    
    
    $query_documento = "";
    $query_fecha = "";
    $query_bancos = "";
    $query_ec="";
    
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
    
    if(!empty($cbxEstadoCr)){
        $query_ec = "AND gppd.estado_cierre='$cbxEstadoCr'";
    }

    $query_ordenar="gppd.fecha_pago DESC";
    

        $query = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            concat(dc.documento,' - ', dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as cliente,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as nom_cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as lote_nom,
            date_format(gppd.fecha_pago, '%d/%m/%Y') as fecha_vencimiento,
            'RESERVA' as letra,
            date_format(gppd.fecha_pago, '%d/%m/%Y') as fecha_pago,
            date_format(gppd.fecha_pago, '%d-%m-%Y') as fech_pago,
            gppd.fecha_pago as fec_pago,
            cddddx.texto1 as tipo_moneda,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            format(gppd.importe_pago,2) as importe,
            '0' as mora,
            cdddddddx.nombre_corto as estado_pago,
            cdddddddx.texto1 as estado_pago_color,
            cdddx.nombre_corto as banco,
            cdx.nombre_corto as medio_pago,
            cddx.nombre_corto as tipo_comprobante,
            gppd.nro_operacion as nro_operacion,
            gppd.voucher as voucher,
            gppd.serie as serie,
            gppd.numero as numero,
            gppd.comprobante as comprobante,
            if(gppd.tipo_comprobante_sunat=0, 'Falta',cdddddx.nombre_corto) as tipo_comprobante_sunat,
            cddddddx.nombre_corto as estado_cierre,
            cddddddx.texto1 as estado_cierre_color,
            gppd.fecha_emision as fecha_emision,
            concat('B : ',(select count(idpago_comprobante) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle AND tipo_comprobante_sunat='03'),' / F : ',
            (select count(idpago_comprobante) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle AND tipo_comprobante_sunat='01')) as nro_comprobantes
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_reservacion AS gpre ON gpre.id_lote=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id=gpre.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpre.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.medio_pago AND cdx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.tipo_comprobante AND cddx.codigo_tabla='_TIPO_COMPROBANTE'
            INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gppd.agencia_bancaria AND cdddx.codigo_tabla='_BANCOS'
            INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gppd.moneda_pago AND cddddx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cdddddx ON (cdddddx.idconfig_detalle=gppd.tipo_comprobante_sunat OR gppd.tipo_comprobante_sunat=0) AND cdddddx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
            INNER JOIN configuracion_detalle AS cddddddx ON cddddddx.codigo_item=gppd.estado_cierre AND cddddddx.codigo_tabla='_ESTADO_FACTURACION_PAGO'
            INNER JOIN configuracion_detalle AS cdddddddx ON cdddddddx.codigo_item=gppd.estado AND cdddddddx.codigo_tabla='_ESTADO_VALIDACION_PAGO'
            WHERE gppd.esta_borrado=0 AND gppc.id_venta='0'
            AND gppd.estado='2'
            $query_ec
            $query_documento
            $query_fecha
            $query_bancos
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
                'estado_pago_color' => $row['estado_pago_color'],
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
                'estado_cierre_color' => $row['estado_cierre_color'],
                'tipo_comprobante_sunat' => $row['tipo_comprobante_sunat'],
                'fecha_emision' => $row['fecha_emision'],
                'nro_comprobantes' => $row['nro_comprobantes']
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





?>