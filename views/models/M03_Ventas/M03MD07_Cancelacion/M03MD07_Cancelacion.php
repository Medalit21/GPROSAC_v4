<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

$hora = date("H:i:s", time());;
$fecha = date('Y-m-d');
$fecha_hoy = date('Y-m-d');  

$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
$IdUser=$respuesta_idusu['id'];

$data = array();
$dataList = array();

if(isset($_POST['btnValidarFechas'])){
    
    //CONSULTAR FECHA INICIO PROYECTO
    
    $consultar_fec_ini = mysqli_query($conection, "SELECT inicio_proyecto as inicio FROM gp_proyecto WHERE estado='1'");
    $respuesta_fec_ini = mysqli_fetch_assoc($consultar_fec_ini);
    $fec_ini = $respuesta_fec_ini['inicio'];
    
    $fec_fin = date('Y-m-d'); 
    
    $data['status'] = 'ok';
    $data['primero'] = $fec_ini;
    $data['ultimo'] = $fec_fin;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['btnCancelarVenta'])){
    
    $idRegistro = $_POST['idRegistro'];
    
    $txtUSR = $_POST['txtUsr'];
    $txtUSR = decrypt($txtUSR, "123");
    
    //CONSULTAR ID DE USUARIO
	$idusuario = "";
	$consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$txtUSR'");
	$respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
	$idusuario = $respuesta_idusuario['id'];
	$actualiza = $fecha.' '.$hora;

    
    //TOTAL CRONOGRAMA
    $consultar_total_cron = mysqli_query($conection, "SELECT SUM(monto_letra) as total FROM gp_cronograma WHERE id_venta='$idRegistro' AND dscto_acuerdo='0' AND esta_borrado='0'");
    $respuesta_total_cron = mysqli_fetch_assoc($consultar_total_cron);
    
    $total_cronograma = $respuesta_total_cron['total'];
    
    //TOTAL PAGADO
    $consultar_total_pag = mysqli_query($conection, "SELECT SUM(pagado) as total FROM gp_pagos_cabecera WHERE id_venta='$idRegistro' AND esta_borrado='0'");
    $respuesta_total_pag = mysqli_fetch_assoc($consultar_total_pag);
    
    $total_pagado = $respuesta_total_pag['total'];
    
    $diferencia = $total_cronograma - $total_pagado;
    
    if($diferencia <=1 ){
        $actualizar = mysqli_query($conection, "UPDATE gp_venta SET cancelado='1', id_usuario_cancela='$idusuario', actualiza_cancela='$actualiza' WHERE id_venta='$idRegistro'");
        
        $data['status'] = 'ok';
        $data['data'] = "Se establecio que la venta ha sido cancelada.";
    }else{
        $data['status'] = 'bad';
        $data['data'] = "La venta aun no puede asignarse como cancelada. Revise que el cliente haya pagado la totalidad de sus letras segun el cronograma de pagos.";
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['btnAnularCancelacionVenta'])){
    
     $idRegistro = $_POST['idRegistro'];
    
    $actualizar = mysqli_query($conection, "UPDATE gp_venta SET cancelado='0' WHERE id_venta='$idRegistro'"); 
    
    $data['status'] = 'ok';
    $data['data'] = "Se anulo la cancelacion de la venta.";
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

/**************************LISTA VENTAS CANCELACION********************************** */
if (isset($_POST['ReturnTablaVentasCancelacion'])) {

    $txtFiltroDocumento = $_POST['txtFiltroDocumento'];
    $txtFiltroDesde = $_POST['txtFiltroDesde'];
    $txtFiltroHasta = $_POST['txtFiltroHasta'];
    $cbxFiltroEstado = $_POST['cbxFiltroEstado'];
    
    $query_documento = "";
    $query_fecha = "";
    $query_estado = "";
    
    if(!empty($txtFiltroDocumento)){
       $query_documento = "AND dc.documento='$txtFiltroDocumento'"; 
    }
    
    if(!empty($txtFiltroDesde) && !empty($txtFiltroHasta)){
       $query_fecha = "AND gpv.fecha_venta BETWEEN '$txtFiltroDesde' AND '$txtFiltroHasta'"; 
    }else{
        if(!empty($txtFiltroDesde) && empty($txtFiltroHasta)){
            $query_fecha = "AND gpv.fecha_venta='$txtFiltroDesde'"; 
        }
    }
    
    /*if(!empty($cbxFiltroEstado)){
       if($cbxFiltroEstado == 2){
           $query_estado = "AND gpv.cancelado ='0'"; 
        } else {
           $query_estado = "AND gpv.cancelado ='$cbxFiltroEstado'"; 
        }
    }*/
	
	if (!empty($cbxFiltroEstado)) {
    if ($cbxFiltroEstado == 1) {
        // CANCELADO
        $query_estado = "AND gpv.cancelado = '1'";
    } elseif ($cbxFiltroEstado == 2) {
        // POR CANCELAR
        $query_estado = "AND gpv.cancelado = '0' AND 
            (
                IFNULL((
                    SELECT SUM(monto_letra) 
                    FROM gp_cronograma 
                    WHERE id_venta = gpv.id_venta 
                      AND dscto_acuerdo = '0' 
                      AND esta_borrado = '0'
                ), 0)
                -
                IFNULL((
                    SELECT SUM(pagado) 
                    FROM gp_pagos_detalle 
                    WHERE id_venta = gpv.id_venta 
                      AND estado = '2' 
                      AND esta_borrado = '0'
                ), 0)
            ) < 1";
    } elseif ($cbxFiltroEstado == 3) {
        // PENDIENTE
        $query_estado = "AND gpv.cancelado = '0' AND 
            (
                IFNULL((
                    SELECT SUM(monto_letra) 
                    FROM gp_cronograma 
                    WHERE id_venta = gpv.id_venta 
                      AND dscto_acuerdo = '0' 
                      AND esta_borrado = '0'
                ), 0)
                -
                IFNULL((
                    SELECT SUM(pagado) 
                    FROM gp_pagos_detalle 
                    WHERE id_venta = gpv.id_venta 
                      AND estado = '2' 
                      AND esta_borrado = '0'
                ), 0)
            ) >= 1";
    }
}





    $query = mysqli_query($conection, "SELECT
    gpv.id_venta as id,
    if(gpv.cancelado='1','CANCELADO',if(((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and dscto_acuerdo='0' and esta_borrado='0') - (select sum(pagado) from gp_pagos_cabecera where id_venta=gpv.id_venta and estado='2' and esta_borrado='0'))<1,'POR CANCELAR','PENDIENTE')) as estado,
    date_format(gpv.fecha_venta, '%d/%m/%Y') as fecha,
    dc.documento as documento,
    concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as datos,
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
    gpv.cantidad_letra as nro_letras,
    format(gpv.total, 2) as precio_venta,
    format((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and esta_borrado='0'),2) as total_lote,
    format(((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and esta_borrado='0') - gpv.total),2) as intereses,
    format(ROUND((SELECT (SUM(gppd.pagado) + if((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1')>0,(select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1'),0)) FROM gp_pagos_cabecera pagoc, gp_pagos_detalle gppd, gp_cronograma gpcr 
            WHERE pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagoc.idpago=gppd.idpago AND gppd.esta_borrado='0' AND gpcr.estado=2 AND pagoc.esta_borrado='0' AND gpcr.esta_borrado='0'),2),2) as total_cancelado,
    format(((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and dscto_acuerdo='0' and esta_borrado='0') - if((select sum(pagado) from gp_pagos_detalle where id_venta=gpv.id_venta and estado='2' and esta_borrado='0')>0,(select sum(pagado) from gp_pagos_detalle where id_venta=gpv.id_venta and estado='2' and esta_borrado='0'),'0.00')),2) as deuda_capital,
    ((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and esta_borrado='0') - (select sum(pagado) from gp_pagos_detalle where id_venta=gpv.id_venta and estado='2' and esta_borrado='0')) as deuda_capital2
    FROM gp_venta gpv
    INNER JOIN datos_cliente AS dc ON dc.id = gpv.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    WHERE gpv.esta_borrado='0' AND dc.esta_borrado='0'
    AND gpv.conformidad='1'
    $query_documento
    $query_fecha
    $query_estado
    ORDER BY estado DESC, deuda_capital2 ASC");

    if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            
            array_push($dataList,[
                'id' => $row['id'],
                'estado' => $row['estado'],
                'fecha' => $row['fecha'],
                'documento' => $row['documento'],
                'datos' => $row['datos'],
                'lote' => $row['lote'],
                'nro_letras' => $row['nro_letras'],
                'precio_venta' => $row['precio_venta'],
                'total_lote' =>$row['total_lote'],
                'intereses' => $row['intereses'],
                'total_cancelado' => $row['total_cancelado'],
                'deuda_capital' => $row['deuda_capital']
            ]);
        }
        
        $data['status'] = 'ok';
        $data['query'] = "SELECT
    gpv.id_venta as id,
    if(gpv.cancelado='0','POR CANCELAR','CANCELADO') as estado,
    date_format(gpv.fecha_venta, '%d/%m/%Y') as fecha,
    dc.documento as documento,
    concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as datos,
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
    gpv.cantidad_letra as nro_letras,
    format(gpv.total, 2) as precio_venta,
    format((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and esta_borrado='0'),2) as total_lote,
    format(((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and esta_borrado='0') - gpv.total),2) as intereses,
    format((select sum(pagado) from gp_pagos_cabecera where id_venta=gpv.id_venta and estado='2' and esta_borrado='0'),2) as total_cancelado,
    format(((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta and esta_borrado='0') - (select sum(pagado) from gp_pagos_cabecera where id_venta=gpv.id_venta and estado='2' and esta_borrado='0')),2) as deuda_capital
    FROM gp_venta gpv
    INNER JOIN datos_cliente AS dc ON dc.id = gpv.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    WHERE gpv.esta_borrado='0' AND dc.esta_borrado='0'
    AND gpv.conformidad='1'
    $query_documento
    $query_fecha
    $query_estado
    ORDER BY gpv.cancelado DESC, deuda_capital ASC";
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}



?>