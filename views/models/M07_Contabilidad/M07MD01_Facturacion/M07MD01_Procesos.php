<?php

session_start();
date_default_timezone_set('America/Lima');
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";
$hora = date("H:i:s", time());;
$fecha = date('Y-m-d');
$fecha_hoy = date('Y-m-d');
$data = array();
$dataList = array();


if(isset($_POST['btnCargarFechasFiltro'])){
    
    $idproyecto = $_POST['idproyecto'];

    $fecha = new DateTime();
    $fecha->modify('first day of this month');
    $primer_dia = $fecha->format('Y-m-d');

    $anio = date('Y'); 
    $new_fecha = $anio."-01-01";

    //consultar inicio proyecto
    $consultar_inicio = mysqli_query($conection, "SELECT inicio_proyecto AS inicio FROM gp_proyecto WHERE idproyecto='$idproyecto'");
    $cont_inicio = mysqli_num_rows($consultar_inicio);
    if($cont_inicio > 0){
        $row = mysqli_fetch_assoc($consultar_inicio);
        $new_fecha = $row['inicio'];
    }
    
    $fecha = new DateTime();
    $fecha->modify('last day of this month');
    $ultimo_dia = $fecha->format('Y-m-d'); 
    
    $data['status'] = 'ok';
    $data['primero'] = $new_fecha;
    $data['ultimo'] = $ultimo_dia;
    $data['hoy'] = $fecha_hoy;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

/*if(isset($_POST['btnAnularComprobante'])){

        $idUsuario = $_POST['idUsuario'];
        $idRegistro = $_POST['idRegistro'];
        $control = $fecha.' '.$hora;
        
        //CONSULTAR DATOS
        $actualizar_comprobante = mysqli_query($conection, "UPDATE fac_comprobante_cab SET esta_anulado='1', actualiza_usuario='$idUsuario', actualiza_registro='$control' WHERE idcomprobante_cab='$idRegistro'");
        
        if($actualizar_comprobante){
            
            //Consultar Serie y Numero
            $consultar_datos = mysqli_query($conection, "SELECT NUM_SERIE_CPE as serie, NUM_CORRE_CPE as numero FROM fac_comprobante_cab WHERE idcomprobante_cab='$idRegistro'");
            $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
            
            $serie = $respuesta_datos['serie'];
            $numero = $respuesta_datos['numero'];
            
            //consultar id pago detalle
            $consultar_detalle = mysqli_query($conection, "SELECT idpago_detalle as id FROM gp_pagos_detalle_comprobante WHERE serie='$serie' AND numero='$numero' GROUP BY serie, numero");
            $respuesta_detalle = mysqli_fetch_assoc($consultar_detalle);
            
            $idpago_detalle = $respuesta_detalle['id'];
            
            
            //Reaperturar opcion para facturar pago
            $actualizar_pago = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado_facturacion='0', estado_cierre='1', actualizado='$control', id_usuario_actualiza='$idUsuario' WHERE idpago_detalle='$idpago_detalle'");
            
            if($actualizar_pago){
                
                $actualizar_pago_com = mysqli_query($conection, "UPDATE gp_pagos_detalle_comprobante SET esta_borrado='1', actualiza_usuario='$idUsuario', actualiza_registro='$control' WHERE idpago_detalle='$idpago_detalle' AND serie='$serie' AND numero='$numero'");
            
                $data['status'] = 'ok';
                $data['data'] = 'Correcto';
            
            }
            
            
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo anular el comprobante, intentar nuevamente.';
        }
        
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}*/

if(isset($_POST['btnAnularComprobante'])){

        $idUsuario = $_POST['idUsuario'];
        $idRegistro = $_POST['idRegistro'];
        $control = $fecha.' '.$hora;
        
        //CONSULTAR DATOS
        $actualizar_comprobante = mysqli_query($conection, "UPDATE fac_comprobante_cab SET esta_anulado='1', actualiza_usuario='$idUsuario', actualiza_registro='$control' WHERE idcomprobante_cab='$idRegistro'");
        
        if($actualizar_comprobante){
            
            //Consultar Serie y Numero
            $consultar_datos = mysqli_query($conection, "SELECT NUM_SERIE_CPE as serie, NUM_CORRE_CPE as numero FROM fac_comprobante_cab WHERE idcomprobante_cab='$idRegistro'");
            $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
            
            $serie = $respuesta_datos['serie'];
            $numero = $respuesta_datos['numero'];
            
            //consultar id pago detalle
            $consultar_detalle = mysqli_query($conection, "SELECT idpago_detalle as id FROM gp_pagos_detalle_comprobante WHERE serie='$serie' AND numero='$numero' GROUP BY serie, numero");
            $respuesta_detalle = mysqli_fetch_assoc($consultar_detalle);
            
            $idpago_detalle = $respuesta_detalle['id'];
            
            
            //Reaperturar opcion para facturar pago
            $actualizar_pago = mysqli_query($conection, "UPDATE gp_pagos_detalle SET estado_facturacion='0', estado_cierre='1', actualizado='$control', id_usuario_actualiza='$idUsuario' WHERE idpago_detalle='$idpago_detalle'");
            
			if($actualizar_pago){
					
					// Marcar como borrado el registro de comprobante asociado
					$actualizar_pago_com = mysqli_query($conection, "UPDATE gp_pagos_detalle_comprobante SET esta_borrado='1', actualiza_usuario='$idUsuario', actualiza_registro='$control' WHERE idpago_detalle='$idpago_detalle' AND serie='$serie' AND numero='$numero'");
					
					// Consultar información necesaria para revertir el cronograma
					$consulta_info = mysqli_query($conection, "SELECT gpc.id_cronograma, gpd.id_venta
					FROM gp_pagos_detalle gpd
					INNER JOIN gp_pagos_cabecera gpc ON gpd.idpago = gpc.idpago
					WHERE gpd.idpago_detalle = '$idpago_detalle'");
				
				if ($info = mysqli_fetch_assoc($consulta_info)) {
					$id_cronograma = $info['id_cronograma'];
					$id_venta = $info['id_venta'];
					
					// Revertir el cronograma
					$revertir_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET
					estado='1',
					pago_cubierto='0',
					id_usuario_actualiza='$idUsuario',
					actualizado='$control'
					WHERE correlativo='$id_cronograma' AND id_venta='$id_venta'");
				
					if ($revertir_cronograma) {
						$data['status'] = 'ok';
						$data['data'] = 'El comprobante ha sido anulado, el pago y los comprobantes asociados actualizados correctamente, y el cronograma revertido.';
					} else {
						$data['status'] = 'bad';
						$data['data'] = 'Error al revertir el cronograma.';
					}
				} else {
					$data['status'] = 'bad';
					$data['data'] = 'No se encontró información para revertir el cronograma.';
				}          
				
			} else {
				$data['status'] = 'bad';
				$data['data'] = 'Error al actualizar el estado de facturación y cierre del pago.';
			}	
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo anular el comprobante, intentar nuevamente.';
        }
        
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}

if(isset($_POST['btnGenerarClaveSol'])) {
	
    $idUsuario = $_POST['idUsuario'];
	
	$variable = encrypt($idUsuario, "123");
	
    $idRegistro = $_POST['idRegistro']; // Este es el ID que pasas desde JS
    $control = date('Y-m-d H:i:s'); // Asegúrate de que esta fecha se genera correctamente

    // Actualizar el campo tipo_origen en gp_pagos_detalle
    $actualizarPagoDetalle = mysqli_query($conection, "UPDATE gp_pagos_detalle SET
        tipo_origen='2',
        id_usuario_actualiza='$idUsuario',
        actualizado='$control'
        WHERE idpago_detalle = '$idRegistro'");

    if ($actualizarPagoDetalle) {
        $data['status'] = 'ok';
        $data['data'] = 'El tipo de origen ha sido actualizado correctamente.';
		$data['ruta'] = $NAME_SERVER."views/M07_Contabilidad/M07SM04_ClaveSol/M07SM04_ClaveSol?Vsr=".$variable;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Error al actualizar el tipo de origen: ' . mysqli_error($conection);
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnVerDatosMensaje'])){

       
        $idRegistro = $_POST['idRegistro'];
        
        //CONSULTAR DATOS
        $consultar_datos = mysqli_query($conection, "SELECT
        cab.idcomprobante_cab as id,
        dc.celular_1 as numero
        FROM fac_comprobante_cab cab
        INNER JOIN datos_cliente AS dc ON dc.documento=cab.NUM_NIF_RECP
        WHERE idcomprobante_cab='$idRegistro'");
        $respuesta = mysqli_fetch_assoc($consultar_datos);
        
        $numero= $respuesta['numero'];

        $data['status'] = 'ok';
        $data['numero'] = $numero;
        $data['id'] = $idRegistro;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}

if(isset($_POST['btnEnviarMensajeWts'])){

        $celular = $_POST['txtNroCelular'];
        $idRegistro = $_POST['idRegistro'];
        
        //CONSULTAR DATOS
        $consultar_datos = mysqli_query($conection, "SELECT
        cab.NOM_RZN_SOC_RECP as cliente,
        if(cab.COD_TIP_CPE='03','BOLETA DE VENTA','FACTURA ELECTRONICA') as descripcion,
        concat(cab.NUM_SERIE_CPE,' - ',cab.NUM_CORRE_CPE) as comprobante,
        impr.url_Valor as url
        FROM fac_comprobante_cab cab
        INNER JOIN fac_comprobante_impr AS impr ON impr.correlativo_cpe=cab.NUM_CORRE_CPE AND impr.serie_cpe=cab.NUM_SERIE_CPE AND impr.tipo_cpe=cab.COD_TIP_CPE
        WHERE idcomprobante_cab='$idRegistro'");
        $respuesta = mysqli_fetch_assoc($consultar_datos);
        
        $cliente= $respuesta['cliente'];
        $descripcion= $respuesta['descripcion'];
        $comprobante= $respuesta['comprobante'];
        $url= $respuesta['url'];

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.ultramsg.com/instance8326/messages/chat",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "token=bpnleu3mjub3utqa&to=%2B51".$celular."&body=Estimado(a) ".$cliente." se ha generado una nueva ".$descripcion." ".$comprobante." de G-PRO S.A.C a través del sistema.mifact.net puede revisarlo en el siguiente enlace \n ".$url."&priority=1&referenceId=",
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        /*
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }*/

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}

if(isset($_POST['Enviartxt'])){


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://acg-facturador.acg-soft.com/file.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('imagen'=> new CURLFILE('/C:/Users/trini/Downloads/EB01093720220511.txt')),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: multipart/form-data'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}

if(isset($_POST['btnListarTablaPagosFacturacion'])){

    
    $txtFiltroCliente = isset($_POST['txtFiltroCliente']) ? $_POST['txtFiltroCliente'] : Null;
    $txtFiltroClienter = trim($txtFiltroCliente);
    
    $txtFiltroPropiedad = isset($_POST['txtFiltroPropiedad']) ? $_POST['txtFiltroPropiedad'] : Null;
    $txtFiltroPropiedadr = trim($txtFiltroPropiedad);

    $txtFiltroTipoComprobante = isset($_POST['txtFiltroTipoComprobante']) ? $_POST['txtFiltroTipoComprobante'] : Null;
    $txtFiltroTipoComprobanter = trim($txtFiltroTipoComprobante);
    
    if(!empty($txtFiltroClienter) || !empty($txtFiltroPropiedadr)){

        $query = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            gppd.fecha_pago as fecha_pago,
            gpcr.item_letra as letra,
            cdx.texto1 as tipo_moneda,
            format(gppd.importe_pago,2) as importe_pago,
            format(gppd.tipo_cambio,2) as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            cddx.nombre_corto as medio_pago,
            gppd.nro_operacion as nro_operacion,
            concat((format(gpv.tna,2)),' %') as tea,
            format(gpcr.interes_amortizado,2) as interes,
            format(gpcr.capital_amortizado,2) as capital,
            if(gppd.estado_facturacion='0','PENDIENTE','FACTURADO') as estado_fac,
            
            if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle and esta_borrado='0' group by idpago_detalle)>0,(select format(sum(pagado),2) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle and esta_borrado='0' group by idpago_detalle),'0.00') as facturado,
            
            format((gppd.pagado - (if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle and esta_borrado='0' group by idpago_detalle)>0,(select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle and esta_borrado='0'  group by idpago_detalle),'0.00'))),2) as saldo
            
            FROM gp_pagos_detalle gppd
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.medio_pago AND cddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppd.id_venta
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_cronograma AS gpcr ON gpcr.id_venta=gpv.id_venta AND gpcr.correlativo=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id = gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            WHERE gppd.esta_borrado=0 AND gppd.estado_cierre='1' AND gppd.estado='2'
            AND dc.documento='$txtFiltroClienter'
            AND gpl.idlote='$txtFiltroPropiedadr'
            ORDER BY gppd.fecha_pago ASC"); 

            if($query->num_rows > 0){
                
                while($row = $query->fetch_assoc()) {
                    
                    //Campos para llenar Tabla
                    array_push($dataList,[
                        'id' => $row['id'],
                        'fecha_pago' => $row['fecha_pago'],
                        'tipo_moneda' => $row['tipo_moneda'],
                        'importe_pago' => $row['importe_pago'],
                        'tipo_cambio' => $row['tipo_cambio'],
                        'pagado' => $row['pagado'],
                        'medio_pago' => $row['medio_pago'],
                        'nro_operacion' => $row['nro_operacion'],
                        'tea' => $row['tea'],
                        'interes' => $row['interes'],
                        'capital' => $row['capital'],
                        'letra' => $row['letra'],
                        'estado_fac' => $row['estado_fac'],
                        'facturado' => $row['facturado'],
                        'saldo' => $row['saldo'],
                        'cliente' => $txtFiltroCliente,
                        'propiedad' => $txtFiltroPropiedadr,
                        'tipo_comp' => $txtFiltroTipoComprobanter
                    ]);
                }
                    
                $data['data'] = $dataList;
                $data['tipo_comp'] = $txtFiltroTipoComprobanter;
                header('Content-type: text/javascript');
                echo json_encode($data,JSON_PRETTY_PRINT) ;

            }else{
                
                $data['recordsTotal'] = 0;
                $data['recordsFiltered'] = 0;
                $data['data'] = $dataList;
                header('Content-type: text/javascript');
                echo json_encode($data,JSON_PRETTY_PRINT) ;
            }
    }else{

        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
        
    }
     
}

if(isset($_POST['btnListarTablaPagosFacturacionGnral'])){

    $txtFiltroProyecto = isset($_POST['txtFiltroProyecto']) ? $_POST['txtFiltroProyecto'] : Null;
    $txtFiltroProyector = trim($txtFiltroProyecto);

    $txtFiltroCliente = isset($_POST['txtFiltroCliente']) ? $_POST['txtFiltroCliente'] : Null;
    $txtFiltroClienter = trim($txtFiltroCliente);
    
    $txtFiltroPropiedad = isset($_POST['txtFiltroPropiedad']) ? $_POST['txtFiltroPropiedad'] : Null;
    $txtFiltroPropiedadr = trim($txtFiltroPropiedad);

    $txtFiltroTipoComprobante = isset($_POST['txtFiltroTipoComprobante']) ? $_POST['txtFiltroTipoComprobante'] : Null;
    $txtFiltroTipoComprobanter = trim($txtFiltroTipoComprobante);

    $txtFiltroDesde = isset($_POST['txtFiltroDesde']) ? $_POST['txtFiltroDesde'] : Null;
    $txtFiltroDesder = trim($txtFiltroDesde);

    $txtFiltroHasta = isset($_POST['txtFiltroHasta']) ? $_POST['txtFiltroHasta'] : Null;
    $txtFiltroHastar = trim($txtFiltroHasta);

    if(!empty($txtFiltroClienter)){
        $txtFiltroClienter = "AND dc.documento='$txtFiltroClienter'";
    }

    if(!empty($txtFiltroPropiedadr)){
        $txtFiltroPropiedadr = "AND gpl.idlote='$txtFiltroPropiedadr'";
    }

    $query_fecha="";
    if(!empty($txtFiltroDesder) && !empty($txtFiltroHastar)){
        $query_fecha = "AND gppd.fecha_pago BETWEEN '$txtFiltroDesder' AND '$txtFiltroHastar'";
    }else{
        if(!empty($txtFiltroDesder) && empty($txtFiltroHastar)){
            $query_fecha = "AND gppd.fecha_pago='$txtFiltroDesder'";
        }else{
            if(empty($txtFiltroDesder) && !empty($txtFiltroHastar)){
                $query_fecha = "AND gppd.fecha_pago='$txtFiltroHastar'";
            }
        } 
    }

    $query = mysqli_query($conection,"SELECT
    dc.id as id,
    gpv.id_venta as id_venta,
    dc.documento as doc_cliente,
    concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as cliente,
    gpl.idlote as cod_lote,
    concat('Mz. ',SUBSTRING(gpm.nombre,9,2), ' - Lte. ',SUBSTRING(gpl.nombre,6,2),' - ',gpy.nombre) as lote,
    gpcr.item_letra as letra,
    format(gpcr.interes_amortizado,2) as interes,
    format(gpcr.capital_amortizado,2) as capital,
    gppd.idpago_detalle as iddetalle,
    format(gppd.pagado,2) as pagado,
    date_format(gppd.fecha_pago,'%d/%m/%Y') as fecha_pago,
    if((select sum(gpd.pagado) from gp_pagos_detalle_comprobante gpd where gpd.idpago_detalle=gppd.idpago_detalle AND gpd.esta_borrado='0' GROUP BY gpd.idpago_detalle)>0, (select sum(gpd.pagado) from gp_pagos_detalle_comprobante gpd where gpd.idpago_detalle=gppd.idpago_detalle AND gpd.esta_borrado='0' GROUP BY gpd.idpago_detalle), 0) as monto_facturado,
    format((gppd.pagado - if((select sum(gpd.pagado) from gp_pagos_detalle_comprobante gpd where gpd.idpago_detalle=gppd.idpago_detalle AND gpd.esta_borrado='0' GROUP BY gpd.idpago_detalle)>0, (select sum(gpd.pagado) from gp_pagos_detalle_comprobante gpd where gpd.idpago_detalle=gppd.idpago_detalle AND gpd.esta_borrado='0' GROUP BY gpd.idpago_detalle), 0)),2) as por_facturar
    FROM datos_cliente dc
    INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    INNER JOIN gp_proyecto as gpy ON gpy.idproyecto=gpz.idproyecto
    INNER JOIN gp_pagos_detalle AS gppd ON gppd.id_venta=gpv.id_venta
    INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
    INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gppc.id_venta
    WHERE dc.esta_borrado=0 
    AND gppd.estado='2' 
    AND gppd.esta_borrado='0'
    AND gppd.estado_facturacion='0' 
    AND gppc.estado='2' 
    AND gppc.visto_bueno in (1,2)
    AND gpv.cancelado='0' 
    AND gpv.devolucion='0' 
    AND gpv.conformidad='1' 
    AND gppd.estado_cierre!='4'
    AND gpy.idproyecto='$txtFiltroProyector'
    $txtFiltroClienter
    $txtFiltroPropiedadr
    $query_fecha
    ORDER BY cliente ASC;"); 

    if($query->num_rows > 0){
        
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'doc_cliente' => $row['doc_cliente'],
                'cliente' => $row['cliente'],
                'cod_lote' => $row['cod_lote'],
                'lote' => $row['lote'],
                'letra' => $row['letra'],
                'interes' => $row['interes'],
                'capital' => $row['capital'],
                'iddetalle' => $row['iddetalle'],
                'pagado' => $row['pagado'],
                'fecha_pago' => $row['fecha_pago'],
                'monto_facturado' => $row['monto_facturado'],
                'por_facturar' => $row['por_facturar']
            ]);
        }
            
        $data['data'] = $dataList;
        $data['tipo_comp'] = $txtFiltroTipoComprobanter;
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



if (isset($_POST['btnCargarDatosBoleta'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];

    //DATOS EMPRESA
    $consultar_datos = mysqli_query($conection, "SELECT 
    de.razon_social as razon_social,
    de.direccion as direccion,
    concat(ud.nombre,' - ',up.nombre,' - ',ur.nombre) as lugar,
    de.ruc as ruc
    FROM datos_empresa de
    INNER JOIN ubigeo_distrito AS ud ON ud.codigo=de.ubigeo_distrito
    INNER JOIN ubigeo_provincia as up ON up.codigo=de.ubigeo_provincia
    INNER JOIN ubigeo_region as ur ON ur.codigo=de.ubigeo_region
    WHERE de.estado='1'");
    $respuesta = mysqli_fetch_assoc($consultar_datos);
    $razon_social = $respuesta['razon_social'];
    $direccion = $respuesta['direccion'];
    $lugar = $respuesta['lugar'];
    $ruc = $respuesta['ruc'];


    $anio = date('Y');

    //NUMERO COMPROBANTE BOLETA
    $consultar_numero = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='BOL' AND anio='$anio'");
    $respuesta_numero = mysqli_fetch_assoc($consultar_numero);
    $numero = $respuesta_numero['num'];
    $serie = $respuesta_numero['serie'];
    $correlativo = $respuesta_numero['correlativo'];

    $desc_serie="";
    if($numero>0 && $numero<10){
        $desc_serie=$serie."00".$numero;
    }else{
        if($numero>10 && $numero<100){
            $desc_serie=$serie."0".$numero;
        }else{
            $desc_serie=$serie.$numero;  
        }  
    }

    $correlativo = $correlativo;
    $desc_correlativo="";
    $cont_correlativo = strlen($correlativo);
    if($cont_correlativo>0 && $cont_correlativo<2){
        $desc_correlativo="0000000".$correlativo;
    }else{
       if($cont_correlativo>1 && $cont_correlativo<3){
            $desc_correlativo="000000".$correlativo;
       }else{
           if($cont_correlativo>2 && $cont_correlativo<4){
                $desc_correlativo="00000".$correlativo;
           }else{
                if($cont_correlativo>3 && $cont_correlativo<5){
                    $desc_correlativo="0000".$correlativo;
                }else{
                    if($cont_correlativo>4 && $cont_correlativo<6){
                        $desc_correlativo="000".$correlativo;
                    }else{
                        if($cont_correlativo>5 && $cont_correlativo<7){
                            $desc_correlativo="00".$correlativo;
                       }else{
                            if($cont_correlativo>6 && $cont_correlativo<8){
                                $desc_correlativo="0".$correlativo;
                            }else{
                                $desc_correlativo=$correlativo;
                            }
                       }
                    }
                }
           }
       }
    }
    
    $descrip_serie = $desc_serie." - ".$desc_correlativo;

    $query = mysqli_query($conection, "SELECT 
    dc.documento as documento,
    concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as datos,
    cd.texto1 as tipo_moneda
    FROM datos_cliente dc
    INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpv.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
    WHERE dc.documento='$txtFiltroCliente' AND gpv.id_lote='$txtFiltroPropiedad'");
    
    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';        
        $data['data'] = $resultado;

        $data['fecha'] = $fecha_hoy;
        $data['razon_social'] = $razon_social;
        $data['direccion'] = $direccion;
        $data['lugar'] = $lugar;
        $data['ruc'] = $ruc;
        $data['serie'] = $descrip_serie;

        $data['num_bol'] = $desc_correlativo;
        $data['serie_bol'] = $desc_serie;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnCargarDatosFactura'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];

    //DATOS EMPRESA
    $consultar_datos = mysqli_query($conection, "SELECT 
    de.razon_social as razon_social,
    de.direccion as direccion,
    concat(ud.nombre,' - ',up.nombre,' - ',ur.nombre) as lugar,
    de.ruc as ruc
    FROM datos_empresa de
    INNER JOIN ubigeo_distrito AS ud ON ud.codigo=de.ubigeo_distrito
    INNER JOIN ubigeo_provincia as up ON up.codigo=de.ubigeo_provincia
    INNER JOIN ubigeo_region as ur ON ur.codigo=de.ubigeo_region
    WHERE de.estado='1'");
    $respuesta = mysqli_fetch_assoc($consultar_datos);
    $razon_social = $respuesta['razon_social'];
    $direccion = $respuesta['direccion'];
    $lugar = $respuesta['lugar'];
    $ruc = $respuesta['ruc'];


    $anio = date('Y');

    //NUMERO COMPROBANTE FACTURA
    $consultar_numerofac = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='FAC' AND anio='$anio'");
    $respuesta_numerofac = mysqli_fetch_assoc($consultar_numerofac);
    $numerofac = $respuesta_numerofac['num'];
    $seriefac = $respuesta_numerofac['serie'];
    $correlativofac = $respuesta_numerofac['correlativo'];

    $desc_serie_fac="";
    if($numerofac>0 && $numerofac<10){
        $desc_serie_fac=$seriefac."00".$numerofac;
    }else{
        if($numerofac>10 && $numerofac<100){
            $desc_serie_fac=$seriefac."0".$numerofac;
        }else{
            $desc_serie_fac=$seriefac.$numerofac;  
        }  
    }

    $correlativofac = $correlativofac;
    $desc_correlativofac="";
    $cont_correlativo = strlen($correlativofac);
    if($cont_correlativo>0 && $cont_correlativo<2){
        $desc_correlativofac="0000000".$correlativofac;
    }else{
       if($cont_correlativo>1 && $cont_correlativo<3){
            $desc_correlativofac="000000".$correlativofac;
       }else{
           if($cont_correlativo>2 && $cont_correlativo<4){
                $desc_correlativofac="00000".$correlativofac;
           }else{
                if($cont_correlativo>3 && $cont_correlativo<5){
                    $desc_correlativofac="0000".$correlativofac;
                }else{
                    if($cont_correlativo>4 && $cont_correlativo<6){
                        $desc_correlativofac="000".$correlativofac;
                    }else{
                        if($cont_correlativo>5 && $cont_correlativo<7){
                            $desc_correlativofac="00".$correlativofac;
                       }else{
                            if($cont_correlativo>6 && $cont_correlativo<8){
                                $desc_correlativofac="0".$correlativofac;
                            }else{
                                $desc_correlativofac=$correlativofac;
                            }
                       }
                    }
                }
           }
       }
    }
    
    $descrip_serie_fac = $desc_serie_fac." - ".$desc_correlativofac;

    $query = mysqli_query($conection, "SELECT 
    dc.documento as documento,
    concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as datos,
    cd.texto1 as tipo_moneda
    FROM datos_cliente dc
    INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpv.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
    WHERE dc.documento='$txtFiltroCliente'");
    
    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';        
        $data['data'] = $resultado;

        $data['fecha'] = $fecha_hoy;
        $data['razon_social'] = $razon_social;
        $data['direccion'] = $direccion;
        $data['lugar'] = $lugar;
        $data['ruc'] = $ruc;
        $data['seriefac'] = $descrip_serie_fac;

        $data['num_control'] = $desc_correlativofac;
        $data['serie_control'] = $desc_serie_fac;

    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnCargarDatosNotaCredito'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $idRegistro = $_POST['idRegistro'];

    //DATOS EMPRESA
    $consultar_datos = mysqli_query($conection, "SELECT 
    de.razon_social as razon_social,
    de.direccion as direccion,
    concat(ud.nombre,' - ',up.nombre,' - ',ur.nombre) as lugar,
    de.ruc as ruc
    FROM datos_empresa de
    INNER JOIN ubigeo_distrito AS ud ON ud.codigo=de.ubigeo_distrito
    INNER JOIN ubigeo_provincia as up ON up.codigo=de.ubigeo_provincia
    INNER JOIN ubigeo_region as ur ON ur.codigo=de.ubigeo_region
    WHERE de.estado='1'");
    $respuesta = mysqli_fetch_assoc($consultar_datos);
    $razon_social = $respuesta['razon_social'];
    $direccion = $respuesta['direccion'];
    $lugar = $respuesta['lugar'];
    $ruc = $respuesta['ruc'];

    $anio = date('Y');
    
    //CONSULTAR DATOS
    $consultar_datoss = mysqli_query($conection, "SELECT 
    NUM_SERIE_CPE as serie,
    NUM_CORRE_CPE as numero,
    FEC_EMIS as fecha_emision,
    concat(NUM_SERIE_CPE,' - ',NUM_CORRE_CPE) as dato_ser_num,
    if(COD_TIP_CPE='03','BOLETA','FACTURA') as denominacion,
    NUM_NIF_RECP as documento,
    NOM_RZN_SOC_RECP as dato_cliente
    FROM fac_comprobante_cab 
    WHERE idcomprobante_cab='$idRegistro'");

    $respuesta_datoss = mysqli_fetch_assoc($consultar_datoss);
    $denominacion = $respuesta_datoss['denominacion'];
    
    $dato_serie = $respuesta_datoss['serie'];
    $dato_numero = $respuesta_datoss['numero'];
    
    if($denominacion == "BOLETA"){
        $denominacion = "NCB";
    }else{
        $denominacion = "NCF";
    }

    //NUMERO COMPROBANTE FACTURA
    $consultar_numerofac = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='$denominacion' AND anio='$anio'");
    $respuesta_numerofac = mysqli_fetch_assoc($consultar_numerofac);
    $numerofac = $respuesta_numerofac['num'];
    $seriefac = $respuesta_numerofac['serie'];
    $correlativofac = $respuesta_numerofac['correlativo'];

    $desc_serie_fac="";
    if($numerofac>0 && $numerofac<10){
        $desc_serie_fac=$seriefac."0".$numerofac;
    }else{
       if($numerofac>=10 && $numerofac<100){
            $desc_serie_fac=$seriefac.$numerofac;
        } 
    }

    $correlativofac = $correlativofac;
    $desc_correlativofac="";
    $cont_correlativo = strlen($correlativofac);
    if($cont_correlativo>0 && $cont_correlativo<2){
        $desc_correlativofac="0000000".$correlativofac;
    }else{
       if($cont_correlativo>1 && $cont_correlativo<3){
            $desc_correlativofac="000000".$correlativofac;
       }else{
           if($cont_correlativo>2 && $cont_correlativo<4){
                $desc_correlativofac="00000".$correlativofac;
           }else{
                if($cont_correlativo>3 && $cont_correlativo<5){
                    $desc_correlativofac="0000".$correlativofac;
                }else{
                    if($cont_correlativo>4 && $cont_correlativo<6){
                        $desc_correlativofac="000".$correlativofac;
                    }else{
                        if($cont_correlativo>5 && $cont_correlativo<7){
                            $desc_correlativofac="00".$correlativofac;
                       }else{
                            if($cont_correlativo>6 && $cont_correlativo<8){
                                $desc_correlativofac="0".$correlativofac;
                            }else{
                                $desc_correlativofac=$correlativofac;
                            }
                       }
                    }
                }
           }
       }
    }
    
    $descrip_serie_fac = $desc_serie_fac." - ".$desc_correlativofac;

    $query = mysqli_query($conection, "SELECT 
    dc.documento as documento,
    concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as datos,
    cd.texto1 as tipo_moneda
    FROM datos_cliente dc
    INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpv.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'");
    
    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';        
        $data['data'] = $resultado;
        $data['val'] = $denominacion;

        $data['fecha'] = $fecha_hoy;
        $data['razon_social'] = $razon_social;
        $data['direccion'] = $direccion;
        $data['lugar'] = $lugar;
        $data['ruc'] = $ruc;
        $data['seriefac'] = $descrip_serie_fac;

        $data['num_control'] = $desc_correlativofac;
        $data['serie_control'] = $desc_serie_fac;
        
        $data['dato_serie'] = $dato_serie;
        $data['dato_numero'] = $dato_numero;

    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnSeleccionarPago'])) {

    $idRegistro = $_POST['IdRegistro'];
    $cliente = $_POST['cliente'];
    $propiedad = $_POST['propiedad'];
    $tipodoc = $_POST['tipodoc'];

    //CONSULTAR DATOS
    $query = mysqli_query($conection, "SELECT 
    gppd.idpago_detalle as id,
    cd.texto1 as tipo_moneda,
    gppd.moneda_pago as cod_tipo_moneda,
    gppd.tipo_cambio as tipo_cambio,
    format((gppd.pagado - (if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle AND esta_borrado='0' AND debe_haber='H')>0,(select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle AND esta_borrado='0' AND debe_haber='H'),'0.00'))),2) as pagado
    FROM gp_pagos_detalle gppd
    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA' 
    WHERE gppd.idpago_detalle='$idRegistro'");

    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok'; 
        $data['id'] = $resultado['id'];
        $data['tipo_moneda'] = $resultado['tipo_moneda'];
        $data['tipo_cambio'] = $resultado['tipo_cambio'];
        $data['cod_tipo_moneda'] = $resultado['cod_tipo_moneda'];
        $data['pagado'] = $resultado['pagado'];

        $data['cliente'] = $cliente;
        $data['propiedad'] = $propiedad;
        $data['tipodoc'] = $tipodoc;
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

if (isset($_POST['btnAgregarPago'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];
    $txtUsuario = $_POST['txtUsuario'];
    $txtFechaEmision = $_POST['txtFechaEmision'];
    $IdRegistro = $_POST['IdRegistro'];

    $txtSerieControlFac = $_POST['txtSerieControlFac'];
    $txtNumeroControlFac = $_POST['txtNumeroControlFac'];
    
    $TipoDoc = $_POST['txtFiltroTipoComprobante'];
    $DatoTotal = $_POST['DatoTotal'];
    $TotalRef = $_POST['TotalRef'];
    //$txtUsuario = decrypt($txtUsuario,"123");

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];
    
    //consultar id venta
    $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_pagos_detalle WHERE idpago_detalle='$IdRegistro'");
    $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
    $idventa = $respuesta_idventa['id'];
    
    //Max correlativo Cronograma
    $consultar_correlativo = mysqli_query($conection, "SELECT max(correlativo) as id FROM gp_cronograma WHERE id_venta='$idventa'");
    $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
    $id_correlativo = $respuesta_correlativo['id'];
    
    //Max item Cronograma
    $consultar_item = mysqli_query($conection, "SELECT id as id FROM gp_cronograma WHERE id_venta='$idventa' AND correlativo='$id_correlativo'");
    $respuesta_item = mysqli_fetch_assoc($consultar_item);
    $id_item = $respuesta_item['id'];

    //datos de letra
    $glosa = "";
    $consultar_glosa = mysqli_query($conection, "SELECT 
    if(gpcr.es_cuota_inicial='1', concat('PAGO INICIAL - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2),' LT ',SUBSTRING(gpl.nombre,6,2)),concat('PAGO LETRA - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2), ' LT ',SUBSTRING(gpl.nombre,6,2), if( gpcr.item_letra='ADENDA',' - ADENDA',concat(' - Letra ',gpcr.item_letra,'/',(select count(item_letra) from gp_cronograma where id_venta=gpv.id_venta and item_letra not in ('C.I.','C.I','ADENDA','AMORTIZADO')))))) as dato,
    if(gpcr.es_cuota_inicial='1', 
    concat('PAGO INTERESES - CUOTA INICIAL - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2),' LT ',SUBSTRING(gpl.nombre,6,2)),
    concat('PAGO INTERESES - ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2), ' LT ',SUBSTRING(gpl.nombre,6,2), if( gpcr.item_letra='ADENDA',' - ADENDA',concat(' - Letra ',gpcr.item_letra,'/',(select count(item_letra) from gp_cronograma where id_venta=gpv.id_venta and item_letra not in ('C.I.','C.I','ADENDA','AMORTIZADO')))))) as glosa_interes,
    gpcr.interes as interes,
    gpcr.interes_amortizado as interes_amortizado,
    gpcr.capital_amortizado as capital,
    gpcr.monto_letra as monto_letra,
    if(gpv.fecha_venta>='2023-08-01',1,0) as nuevo_cliente
    FROM gp_pagos_detalle gppd
    INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
    INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
    INNER JOIN gp_cronograma AS gpcr ON gpcr.correlativo=gppc.id_cronograma AND gpcr.id_venta=gpv.id_venta
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    WHERE gppd.idpago_detalle='$IdRegistro'");
    $respuesta = mysqli_fetch_assoc($consultar_glosa);
    $glosa = $respuesta['dato'];
    $interes = $respuesta['interes']; //----------------------------------------Para trabajar con la tasa de interes de la venta
    $glosa_interes = $respuesta['glosa_interes'];
    $nuevo_cliente = $respuesta['nuevo_cliente'];

    $monto_letra = $respuesta['monto_letra'];

    $interes_amortizado = $respuesta['interes_amortizado']; //---------------------Para trabajar con el monto de interes amortizado del cronograma
    $capital = $respuesta['capital'];

    $pago = str_replace(',', '',$DatoTotal);
    $pagoRef = str_replace(',', '',$TotalRef);

    $total_capital = '0.00';
    $total_intereses = '0.00';

    if($pago<=$pagoRef){


            if(($interes>0) && ($interes_amortizado>0) && ($nuevo_cliente>0)){ // Se valida que exista una tasa de interes anual > 0 y que la letra segun cronograma tenga el importe correspondiente al interes de la letra.

                if($pago == $monto_letra){ //Se valida que el monto a facturar es el total de la letra segun el cronograma

                    $intereres = $interes_amortizado;
                    $capital = $capital;

                }else{ //Se calcula el interes y capital del monto parcial a facturar

                    $porcentaje_parcial = (($pago * 100) / $monto_letra); //Calculo por regla de 3 para conocer el porcentaje equivalente al monto a facturar de la letra.
                    $interes_parcial = (($interes_amortizado * $porcentaje_parcial) / 100); //Se calcula el nuevo interes del monto parcial
                    $capital_parcial = (($capital * $porcentaje_parcial) / 100); //Se calcula el nuevo capital del monto parcial

                    $intereres = $interes_parcial;
                    $capital = $capital_parcial;

                }
                

                $monto_emitir = $capital;
                $precio_unitario = round(($capital / 2.18),2);
                $igv = round(($precio_unitario * 0.18),2);
                $precio_unitario_venta = round(($precio_unitario + $igv),2);
                $inafecto = round(($monto_emitir - $precio_unitario_venta),2);

                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie,tipo_monto) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','1','UNIDAD','$glosa', '$precio_unitario','0.00','$igv','0.00','$precio_unitario_venta','1','0', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac','C')");

                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie,tipo_monto) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','1','UNIDAD','$glosa','$inafecto','0.00','0.00','0.00','$inafecto','0','1', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac','C')");

                $monto_emitir = $intereres;
                $precio_unitario = round(($intereres / 2.18),2);
                $igv = round(($precio_unitario * 0.18),2);
                $precio_unitario_venta = round(($precio_unitario + $igv),2);
                $inafecto = round(($monto_emitir - $precio_unitario_venta),2);

                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie,tipo_monto) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','1','UNIDAD','$glosa_interes', '$precio_unitario','0.00','$igv','0.00','$precio_unitario_venta','1','0', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac','I')");

                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie,tipo_monto) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','1','UNIDAD','$glosa_interes','$inafecto','0.00','0.00','0.00','$inafecto','0','1', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac','I')");



                $consultar_capital = mysqli_query($conection, "SELECT  format(SUM(importe_venta),2) as total FROM temporal_facturador WHERE estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision' AND tipo_monto='C'");   
                $conteo_capital = mysqli_num_rows($consultar_capital);
                if($conteo_capital>0){
                    $row = mysqli_fetch_assoc($consultar_capital);
                    $total_capital = $row['total'];
                }

                $consultar_interes = mysqli_query($conection, "SELECT  format(SUM(importe_venta),2) as total FROM temporal_facturador WHERE estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision' AND tipo_monto='I'");
                $conteo_interes = mysqli_num_rows($consultar_interes);
                if($conteo_interes>0){
                    $row = mysqli_fetch_assoc($consultar_interes);
                    $total_intereses = $row['total'];
                }


            }else{

                $monto_emitir = $pago;
                $precio_unitario = round(($pago / 2.18),2);
                $igv = round(($precio_unitario * 0.18),2);
                $precio_unitario_venta = round(($precio_unitario + $igv),2);
                $inafecto = round(($monto_emitir - $precio_unitario_venta),2);
                
                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','1','UNIDAD','$glosa', '$precio_unitario','0.00','$igv','0.00','$precio_unitario_venta','1','0', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac')");
                
                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','1','UNIDAD','$glosa','$inafecto','0.00','0.00','0.00','$inafecto','0','1', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac')");
    

            }

            //consulta si existen totales temporal
            $consultar_tot = mysqli_query($conection, "SELECT 
            idfacturador_total as id,
            op_gravada as op_gravada,
            op_inafecta as op_inafecta,
            igv as igv,
            importe_total as total
            FROM temporal_facturador_totales
            WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
            $respuesta_tot = mysqli_num_rows($consultar_tot);
            $resp = 0;
            if($respuesta_tot>0){
            
                //SUMAR DETALLES
                    //CONSULTAR TOTALES
                    $op_gravada= 0;
                    $igv =0;
                    $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                    $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                    $op_gravada = $respuesta_op_gravada['subtotal'];
                    $igv = $respuesta_op_gravada['igv'];

                    $op_inafecta = 0;
                    $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE inafecto='1' AND estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                    $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
                    $op_inafecta = $respuesta_op_inafecta['total'];

                    $total =0;
                    $consultar_totales = mysqli_query($conection, "SELECT  ROUND(if(SUM(importe_venta)>0,SUM(importe_venta),'0'),2) as total FROM temporal_facturador WHERE estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                    $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                    $total = $respuesta_totales['total'];

                    //ACTUALIZAR TOTALES

                    $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
                    op_gravada='$op_gravada',
                    op_inafecta='$op_inafecta',
                    igv='$igv',
                    importe_total='$total'
                    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

                    $resp = 1;
                
            }else{

                //INGRESAR NUEVO TOTAL
                    $op_gravada= 0;
                    $igv =0;
                    $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                    $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                    $op_gravada = $respuesta_op_gravada['subtotal'];
                    $igv = $respuesta_op_gravada['igv'];

                    $op_inafecta = 0;
                    $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE inafecto='1' AND estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                    $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
                    $op_inafecta = $respuesta_op_inafecta['total'];

                    $total =0;
                    $consultar_totales = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                    $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                    $total = $respuesta_totales['total'] + $op_inafecta;

                    //ACTUALIZAR TOTALES
                    $atotal = mysqli_query($conection, "INSERT INTO temporal_facturador_totales(op_gravada, op_inafecta, igv, importe_total, iduser, doc_cliente,idlote, fecha_emision, op, exonerada, isc, otros_cargos, otros_tributos, monto_redondeo) VALUES
                    ('$op_gravada','$op_inafecta','$igv','$total','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtFechaEmision','0.00','0.00','0.00','0.00','0.00','0.00')");    
                  
                    $resp = 1;

            }

            


            if ($resp == 1) {
                $data['status'] = 'ok';
                $data['data'] = "Se agrego el pago al comprobante.";

                $data['cliente'] = $txtFiltroCliente;
                $data['propiedad'] = $txtFiltroPropiedad;

                $data['total_capital'] = $total_capital;
                $data['total_intereses'] = $total_intereses;

            } else {
                $data['status'] = 'bad';
                $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
            }

    }else {        

        $data['status'] = 'bad';
        $data['data'] = 'El Total a Emitir ('.$DatoTotal.') no puede ser mayor al Saldo pendiente de emision ('.$TotalRef.').';

    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnListarItemsBoleta'])){

    
    $txtFiltroCliente = isset($_POST['txtFiltroCliente']) ? $_POST['txtFiltroCliente'] : Null;
    $txtFiltroClienter = trim($txtFiltroCliente);
    
    $txtFiltroPropiedad = isset($_POST['txtFiltroPropiedad']) ? $_POST['txtFiltroPropiedad'] : Null;
    $txtFiltroPropiedadr = trim($txtFiltroPropiedad);

    $txtUsuario = $_POST['txtUsuario'];
    //$txtUsuario = decrypt($txtUsuario, "123");
    
    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    if(!empty($txtFiltroClienter) || !empty($txtFiltroPropiedadr)){

        $query = mysqli_query($conection,"SELECT
            idfacturador as id,
            cantidad as cantidad,
            medida as medida,
            descripcion as descripcion,
            if(inafecto=1,'I','A') as tipo,
            format(valor_unitario,2) as valor_unitario,
            format(valor_igv,2) as valor_igv,
            format(descuento,2) as descuento,
            format(importe_venta,2) as importe_venta,
            igv as igv,
            inafecto as inafecto,
            valor_inafecto as valor_inafecto
            FROM temporal_facturador
            WHERE estado='1'
            AND doc_cliente='$txtFiltroClienter'
            AND idlote='$txtFiltroPropiedadr'
            AND iduser='$idusuario'"); 

            if($query->num_rows > 0){                
                while($row = $query->fetch_assoc()) {                    
                    //Campos para llenar Tabla
                    array_push($dataList,[
                        'id' => $row['id'],
                        'cantidad' => $row['cantidad'],
                        'medida' => $row['medida'],
                        'descripcion' => $row['descripcion'],
                        'tipo' => $row['tipo'],
                        'valor_unitario' => $row['valor_unitario'],
                        'valor_igv' => $row['valor_igv'],
                        'descuento' => $row['descuento'],
                        'importe_venta' => $row['importe_venta'],
                        'igv' => $row['igv'],
                        'inafecto' => $row['inafecto'],
                        'valor_inafecto' => $row['valor_inafecto'],
                        'cliente' => $txtFiltroClienter,
                        'propiedad' => $txtFiltroPropiedadr
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
    }else{

        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
        
    }
     
}

if (isset($_POST['btnEliminarPagoComprobante'])) {

    $IdRegistro = $_POST['IdRegistro'];
    $cliente = $_POST['cliente'];
    $propiedad = $_POST['propiedad'];

   //CONSULTAR DATOS
    $consultar_datos = mysqli_query($conection, "SELECT 
    iduser as usuario,
    fecha_emision as fecha_emision,
    doc_cliente as cliente,
    idlote as idlote
    FROM temporal_facturador
    WHERE idfacturador='$IdRegistro'");
    $respuesta_datos = mysqli_fetch_assoc($consultar_datos);

    $idusuario = $respuesta_datos['usuario'];
    $fecha_emision = $respuesta_datos['fecha_emision'];
    $doc_cliente = $respuesta_datos['cliente'];
    $idlote = $respuesta_datos['idlote'];
    
    $query = mysqli_query($conection, "DELETE 
    FROM temporal_facturador 
    WHERE idfacturador='$IdRegistro'");   

    $validar_totales= "";

    if ($query) {

       //CONSULTAR SI EXISTEN REGISTROS EN RELACION AL COMPROBANTE
        $consultar_registros = mysqli_query($conection, "SELECT COUNT(idfacturador) as conteo FROM temporal_facturador WHERE iduser='$idusuario' AND fecha_emision='$fecha_emision' AND doc_cliente='$doc_cliente' AND idlote='$idlote'");
        $respuesta_registros = mysqli_fetch_assoc($consultar_registros);
        $conteo_Reg = $respuesta_registros['conteo'];

        if($conteo_Reg>0){

             //CONSULTAR TOTALES
             $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
             $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
             $op_gravada = $respuesta_op_gravada['subtotal'];
             $igv = $respuesta_op_gravada['igv'];

             $consultar_op_inafecta = mysqli_query($conection, "SELECT  SUM(importe_venta) as total FROM temporal_facturador WHERE estado='1' AND inafecto='1' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
             $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
             $op_inafecta = $respuesta_op_inafecta['total'];

             $consultar_totales = mysqli_query($conection, "SELECT  ROUND(SUM(importe_venta),2) as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
             $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
             $total = $respuesta_totales['total'];

             //ACTUALIZAR TOTALES

             $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
             op_gravada='$op_gravada',
             op_inafecta='$op_inafecta',
             igv='$igv',
             importe_total='$total'
             WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");

            $validar_totales="V";

        }else{

            $eliminar = mysqli_query($conection, "DELETE FROM temporal_facturador_totales 
             WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");

            $validar_totales="F";
        }

        $data['status'] = 'ok';
        $data['validar'] = $validar_totales;
        $data['cliente'] = $cliente;
        $data['propiedad'] = $propiedad;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo quitar el registro seleccionad, intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnEliminarItemFacturaOC'])) {

    $IdRegistro = $_POST['IdRegistro'];

   //CONSULTAR DATOS
    $consultar_datos = mysqli_query($conection, "SELECT 
    iduser as usuario,
    fecha_emision as fecha_emision,
    doc_cliente as cliente,
    idlote as idlote
    FROM temporal_facturador
    WHERE idfacturador='$IdRegistro'");
    $respuesta_datos = mysqli_fetch_assoc($consultar_datos);

    $idusuario = $respuesta_datos['usuario'];
    $fecha_emision = $respuesta_datos['fecha_emision'];
    $doc_cliente = $respuesta_datos['cliente'];
    $idlote = $respuesta_datos['idlote'];
    
    $query = mysqli_query($conection, "DELETE 
    FROM temporal_facturador 
    WHERE idfacturador='$IdRegistro'");   

    $validar_totales= "";

    if ($query) {

       //CONSULTAR SI EXISTEN REGISTROS EN RELACION AL COMPROBANTE
        $consultar_registros = mysqli_query($conection, "SELECT COUNT(idfacturador) as conteo FROM temporal_facturador WHERE iduser='$idusuario' AND fecha_emision='$fecha_emision' AND doc_cliente='$doc_cliente' AND idlote='$idlote'");
        $respuesta_registros = mysqli_fetch_assoc($consultar_registros);
        $conteo_Reg = $respuesta_registros['conteo'];

        if($conteo_Reg>0){

             //CONSULTAR TOTALES
             $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
             $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
             $op_gravada = $respuesta_op_gravada['subtotal'];
             $igv = $respuesta_op_gravada['igv'];

             $consultar_op_inafecta = mysqli_query($conection, "SELECT  SUM(importe_venta) as total FROM temporal_facturador WHERE estado='1' AND inafecto='1' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
             $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_gravada);
             $op_inafecta = $respuesta_op_inafecta['total'];

             $consultar_totales = mysqli_query($conection, "SELECT  ROUND(SUM(importe_venta),2) as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
             $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
             $total = $respuesta_totales['total'];

             //ACTUALIZAR TOTALES

             $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
             op_gravada='$op_gravada',
             op_inafecta='$op_inafecta',
             igv='$igv',
             importe_total='$total'
             WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");

            $validar_totales="V";

        }else{

            $eliminar = mysqli_query($conection, "DELETE FROM temporal_facturador_totales 
             WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");

            $validar_totales="F";
        }

        $data['status'] = 'ok';
        $data['validar'] = $validar_totales;
        $data['codigo']=$IdRegistro;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo quitar el registro seleccionad, intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}



if (isset($_POST['btnCargarTotalesComprobante'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];
    $txtUsuario = $_POST['txtUsuario'];
    $txtFechaEmision = $_POST['txtFechaEmision'];

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    $query = mysqli_query($conection, "SELECT format(op_gravada,2) as op_gravada,
    format(op,2) as op,
    format(exonerada,2) as exonerada,
    format(op_inafecta,2) as op_inafecta,
    format(isc,2) as isc,
    format(igv,2) as igv,
    format(otros_cargos,2) as otros_cargos,
    format(otros_tributos,2) as otros_tributos,
    format(monto_redondeo,2) as monto_redondeo,
    format(importe_total,2) as importe_total
    FROM temporal_facturador_totales
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    
    if ($query->num_rows>0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';        
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnLimpiarBoleta'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];
    $txtUsuario = $_POST['txtUsuario'];

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    $query = mysqli_query($conection, "DELETE 
    FROM temporal_facturador 
    WHERE doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND iduser='$idusuario'");

    if ($query) {

        $query = mysqli_query($conection, "DELETE 
        FROM temporal_facturador_totales
        WHERE doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND iduser='$idusuario'");

        if ($query) {
            $data['status'] = 'ok';

        } else {
            $data['status'] = 'bad';
            $data['data'] = 'No se pudo quitar el registro seleccionad, intente nuevamente.';
        }

    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo quitar el registro seleccionad, intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnListarPropiedad'])) {

    $valor_documento = $_POST['txtFiltroCliente'];
    
    if(!empty($valor_documento)){

        $query = mysqli_query($conection, "SELECT 
        gpv.id_lote as valor,
        concat(SUBSTRING(tbl3.nombre,9,2), ' - ',SUBSTRING(tbl2.nombre,6,2),' - ',tbl5.nombre) as texto 
        FROM gp_venta gpv 
        INNER JOIN gp_lote AS tbl2 ON tbl2.idlote=gpv.id_lote 
        INNER JOIN gp_manzana AS tbl3 ON tbl3.idmanzana=tbl2.idmanzana
        INNER JOIN gp_zona AS tbl4 ON tbl4.idzona=tbl3.idzona
        INNER JOIN gp_proyecto AS tbl5 ON tbl5.idproyecto=tbl4.idproyecto
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        WHERE gpv.esta_borrado=0
        AND dc.documento='$valor_documento'");

    }else{

        array_push($dataList, [
            'valor' => '',
            'texto' => 'TODOS',
        ]);

        $query = mysqli_query($conection, "SELECT 
		gpl.idlote as valor, 
		concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2),' - ',gpy.nombre) as texto 
		FROM gp_lote gpl 
		INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
		INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
		INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
		INNER JOIN gp_venta AS gpv ON gpv.id_lote=gpl.idlote AND gpv.cancelado='0' AND gpv.devolucion='0'
		WHERE gpl.esta_borrado='0' AND gpm.esta_borrado='0'
		ORDER BY gpm.nombre ASC, gpl.nombre ASC");

    }    

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

if (isset($_POST['btnConsultarVistaComprobante'])) {

    $txtFiltroTipoComprobante = $_POST['txtFiltroTipoComprobante'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];
    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    
     $query_reg = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            gppd.fecha_pago as fecha_pago,
            gpcr.item_letra as letra,
            cdx.texto1 as tipo_moneda,
            format(gppd.importe_pago,2) as importe_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            cddx.nombre_corto as medio_pago,
            gppd.nro_operacion as nro_operacion,
            if(gppd.estado_facturacion='0','PENDIENTE','FACTURADO') as estado_fac,
            if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle)>0,(select format(sum(pagado),2) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle),'0.00') as facturado,
            format((gppd.pagado - (if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle)>0,(select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle),'0.00'))),2) as saldo,
            concat('MZ. ',SUBSTRING(gpm.nombre,9,2), ' - LT. ',SUBSTRING(gpl.nombre,6,2),' - ',gpy.nombre) as lote,
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente
            FROM gp_pagos_detalle gppd
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.medio_pago AND cddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppd.id_venta
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_cronograma AS gpcr ON gpcr.id_venta=gpv.id_venta AND gpcr.correlativo=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id = gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
            WHERE gppd.esta_borrado=0 AND gppd.estado_cierre='1' AND gppd.estado='2'
            AND dc.documento='$txtFiltroCliente'
            AND gpl.idlote='$txtFiltroPropiedad'
            ORDER BY gppd.fecha_pago ASC"); 
    
    $respuesta = mysqli_num_rows($query_reg);
    
    $registros = 0;
    if($respuesta>0){
        $registros = 1;
    }else{
        $registros = 0;
    }    
    
    $query = mysqli_query($conection, "SELECT 
        codigo_item as codigo
        FROM configuracion_detalle gppd
        WHERE codigo_sunat='$txtFiltroTipoComprobante' AND codigo_tabla='_TIPO_COMPROBANTE_SUNAT'");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;

        $row = $query_reg->fetch_assoc();
        $dato_cliente = $row['cliente'];
        $dato_lote = $row['lote'];

        $data['tipodoc'] = $txtFiltroTipoComprobante;
        $data['propiedad'] = $txtFiltroPropiedad;
        $data['cliente'] = $txtFiltroCliente;

        $data['lote'] = $dato_lote;
        $data['nom_cliente'] = $dato_cliente;

        $data['registro'] = $registros;
    } else {
        $data['status'] = 'bad';
        $data['registro'] = $registros;
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
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
    
    $query_documento = "";
    $query_fecha = "";
    $query_bancos = "";
    
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
            if(gppd.estado=2, 'VALIDADO', if(gppd.estado=3,'RECHAZADO','PENDIENTE')) as estado_pago,
            cdddx.nombre_corto as banco,
            cdx.nombre_corto as medio_pago,
            cddx.nombre_corto as tipo_comprobante,
            gppd.nro_operacion as nro_operacion,
            gppd.voucher as voucher,
            gppd.serie as serie,
            gppd.numero as numero,
            gppd.comprobante as comprobante,
            if(gppd.estado_cierre='1', 'PROCESANDO', 'CERRADO') as estado_cierre
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
            WHERE gppd.esta_borrado=0
            AND gppd.tipo_pago='1'
            AND gppd.estado='2'
            $query_documento
            $query_fecha
            $query_bancos
            ORDER BY gppd.fecha_pago DESC"); 

     
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
                'estado_cierre' => $row['estado_cierre']
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
    gppd.tipo_comprobante as tipoComprobante,
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
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
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
    tipo_comprobante='$cbxTipoComprobanteCV',
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
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnCierreComprobante'])) {

    $_ID_PAGO_CV = $_POST['_ID_PAGO_CV'];    

    $consulta = mysqli_query($conection, "SELECT comprobante as comprobante FROM gp_pagos_detalle WHERE idpago_detalle='$_ID_PAGO_CV'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $consultaaa = $consultaa['comprobante'];    

    if (!empty($consultaaa)) {
        $query = mysqli_query($conection, "UPDATE 
        gp_pagos_detalle SET
        estado_cierre='2'
        WHERE idpago_detalle='$_ID_PAGO_CV'");

        $data['status'] = 'ok';
        $data['data'] = "Se ha finalizado con exito el trabajo sobre el pago seleccionado.";
    } else {
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

if (isset($_POST['btnEmitirBoleta'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];    
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];    
    $txtUsuario = $_POST['txtUsuario'];  
    $txtFechaEmision = $_POST['txtFechaEmision'];  
    $txtFechaVencimiento = $_POST['txtFechaVencimiento'];
    $cbxTipoMoneda = $_POST['cbxTipoMoneda'];     

    $cbxTipoDocumento = $_POST['cbxTipoDocumento'];  
    $txtNroDocumento = $_POST['txtNroDocumento'];  
    $txtdatos = $_POST['txtdatos'];
    $txtDireccionCliente = $_POST['txtDireccionCliente'];   
    
    $txtUsuario = $_POST['txtUsuario'];

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    //DATOS EMPRESA
    $empresa = mysqli_query($conection, "SELECT 
    token_facturacion as token,
    ruc as ruc,
    razon_social as razon_social,
    nombre_comercial as nombre_comercial,
    ubigeo_inei as ubigeo,
    direccion as direccion,
    url_facturacion as url
    FROM datos_empresa
    WHERE ESTADO='1'");
    $resp_empresa = mysqli_fetch_assoc($empresa);

    $token = $resp_empresa['token'];
    $ruc = $resp_empresa['ruc'];
    $razon_social = $resp_empresa['razon_social'];
    $nombre_comercial = $resp_empresa['nombre_comercial'];
    $ubigeo = $resp_empresa['ubigeo'];
    $direccion = $resp_empresa['direccion'];
    $URL = $resp_empresa['url'];


    //DATOS CLIENTE
    $cliente = mysqli_query($conection, "SELECT 
    tipodocumento as tipo_documento,
    documento as documento,
    concat(apellido_paterno,' ',apellido_materno,' ',nombres) as datos,
    nombre_via as nombre_via,
    email as correo
    FROM datos_cliente
    WHERE documento='$txtFiltroCliente'");
    $resp_cliente = mysqli_fetch_assoc($cliente);

    $tipo_documento = $resp_cliente['tipo_documento'];
    $documento = $resp_cliente['documento'];
    $datos_cliente = $resp_cliente['datos'];
    $nombre_via = $resp_cliente['nombre_via'];
    $correo = $resp_cliente['correo'];
    if(empty($correo)){
      $correo = "admn.gpro@gmail.com";
    }
    //SERIE Y NUMERO
 
    $anio = date('Y');
    $consulta_sn = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='BOL' AND anio='$anio'");
    $resp_sn = mysqli_fetch_assoc($consulta_sn);
    $numero = $resp_sn['num'];
    $serie = $resp_sn['serie'];
    $correlativo = $resp_sn['correlativo'];

    $desc_serie = "";
    if ($numero > 0 && $numero < 10) {
        $desc_serie = $serie . "00" . $numero;
    } else {
        if ($numero > 10 && $numero < 100) {
            $desc_serie = $serie . "0" . $numero;
        } else {
            $desc_serie = $serie . $numero;
        }
    }

    $desc_correlativo = "";
    if ($correlativo > 0 && $correlativo < 10) {
        $desc_correlativo = "0000000" . $correlativo;
    } else {
        if ($correlativo >= 10 && $correlativo < 100) {
            $desc_correlativo = "000000" . $correlativo;
        } else {
            if ($correlativo >= 100 && $correlativo < 1000) {
                $desc_correlativo = "00000" . $correlativo;
            } else {
                if ($correlativo >= 1000 && $correlativo < 10000) {
                    $desc_correlativo = "0000" . $correlativo;
                } else {
                    if ($correlativo >= 10000 && $correlativo < 100000) {
                        $desc_correlativo = "000" . $correlativo;
                    } else {
                        if ($correlativo >= 100000 && $correlativo < 1000000) {
                            $desc_correlativo = "00" . $correlativo;
                        } else {
                            if ($correlativo >= 1000000 && $correlativo < 10000000) {
                                $desc_correlativo = "0" . $correlativo;
                            } else {
                                $desc_correlativo = $correlativo;
                            }
                        }
                    }
                }
            }
        }
    }

    //totales
    $consulta_totales = mysqli_query($conection, "SELECT
    op_gravada as op_gravada,
    igv as igv,
    importe_total as total,
    op_inafecta as op_inafecta
    FROM temporal_facturador_totales
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    $resp_totales = mysqli_fetch_assoc($consulta_totales);

    $op_gravada = $resp_totales['op_gravada'];
    $op_inafecta = $resp_totales['op_inafecta'];
    $igv = $resp_totales['igv'];
    $total = $resp_totales['total'];

    $buscar = mysqli_query($conection, "SELECT
    idfacturador as codigo,
    round(valor_unitario,2) as unitario,
    valor_igv as igv,
    importe_venta as total,
    descripcion as descripcion,
    cantidad as cantidad,
    inafecto as inafecto
    FROM temporal_facturador
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

    while($row = $buscar->fetch_assoc()) {
        
        $inafecto = $row['inafecto'];
        $var_inafecto = 10;
        if($inafecto == 1){
            $var_inafecto = 30;
        }
    
        array_push($dataList,
            '{        
            "COD_ITEM": "'.$row['codigo'].'",
            "COD_UNID_ITEM": "ARE",
            "CANT_UNID_ITEM": "'.$row['cantidad'].'",
            "VAL_UNIT_ITEM": "'.$row['unitario'].'",      
            "PRC_VTA_UNIT_ITEM": "'.$row['total'].'",
            "VAL_VTA_ITEM": "'.$row['unitario'].'",
            "MNT_BRUTO": "'.$row['unitario'].'",
            "MNT_PV_ITEM": "'.$row['total'].'",
            "COD_TIP_PRC_VTA": "01",
            "COD_TIP_AFECT_IGV_ITEM":"'.$var_inafecto.'",
            "COD_TRIB_IGV_ITEM": "1000",
            "POR_IGV_ITEM": "18",
            "MNT_IGV_ITEM": "'.$row['igv'].'",      
            "TXT_DESC_ITEM": "'.$row['descripcion'].'",                  
            "DET_VAL_ADIC01": "PROYECTO LAGUNA BEACH",
            "DET_VAL_ADIC02": "",
            "DET_VAL_ADIC03": "",
            "DET_VAL_ADIC04": ""
            }'
        );
    }

    $array = implode(",",$dataList);
    
   
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$URL.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "TOKEN":"'.$token.'",
    "COD_TIP_NIF_EMIS": "6",
    "NUM_NIF_EMIS": "'.$ruc.'",
    "NOM_RZN_SOC_EMIS": "'.$razon_social.'",
    "NOM_COMER_EMIS": "'.$nombre_comercial.'",
    "COD_UBI_EMIS": "'.$ubigeo.'",
    "TXT_DMCL_FISC_EMIS": "'.$direccion.'",
    "COD_TIP_NIF_RECP": "'.$cbxTipoDocumento.'",
    "NUM_NIF_RECP": "'.$txtNroDocumento.'",
    "NOM_RZN_SOC_RECP": "'.$txtdatos.'",
    "TXT_DMCL_FISC_RECEP": "'.$txtDireccionCliente.'",
    "FEC_EMIS": "'.$txtFechaEmision.'",
    "FEC_VENCIMIENTO": "'.$txtFechaVencimiento.'",
    "COD_TIP_CPE": "03",
    "NUM_SERIE_CPE": "'.$desc_serie.'",
    "NUM_CORRE_CPE": "'.$desc_correlativo.'",
    "COD_MND": "'.$cbxTipoMoneda.'",
    "TIP_CAMBIO":"1.000",
    "MailEnvio": "'.$correo.'",
    "COD_PRCD_CARGA": "001",
    "MNT_TOT_GRAVADO": "'.$op_gravada.'",     
    "MNT_TOT_TRIB_IGV": "'.$igv.'", 
    "MNT_TOT": "'.$total.'",
    "MNT_TOT_INAFECTO": "'.$op_inafecta.'", 
    "COD_PTO_VENTA": "jmifact",
    "ENVIAR_A_SUNAT": "true",
    "RETORNA_XML_ENVIO": "true",
    "RETORNA_XML_CDR": "true",
    "RETORNA_PDF": "true",
      "COD_FORM_IMPR":"001",
      "TXT_VERS_UBL":"2.1",
      "TXT_VERS_ESTRUCT_UBL":"2.0",
      "COD_ANEXO_EMIS":"0000",
      "COD_TIP_OPE_SUNAT": "0101",
      
    "items": [
        '.$array.'
    ]
        
    }',
    CURLOPT_HTTPHEADER => array(
        'postman-token: b4938777-800c-1fb1-b127-aefda436e223',
        'cache-control: no-cache',
        'content-type: application/json'
    ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    //echo $response;

    $datos = json_decode($response, true);

    $cadena_para_codigo_qr = $datos["cadena_para_codigo_qr"];
    $cdr_sunat = $datos["cdr_sunat"];
    $codigo_hash = $datos["codigo_hash"];
    $correlativo_cpe = $datos["correlativo_cpe"];
    $errors = $datos["errors"];
    $estado_documento = $datos["estado_documento"];
    $pdf_bytes = $datos["pdf_bytes"];
    $serie_cpe = $datos["serie_cpe"];
    $sunat_description = $datos["sunat_description"];
    $sunat_note = $datos["sunat_note"];
    $sunat_responsecode = $datos["sunat_responsecode"];
    $ticket_sunat = $datos["ticket_sunat"];
    $tipo_cpe = $datos["tipo_cpe"];
    $url = $datos["url"];
    $xml_enviado = $datos["xml_enviado"];
   
    if(!empty($errors)){ 
        
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo emitir la boleta. Detalle del error : '.$errors;
        $data['detalle'] = $array;

    } else {    

        //GRABAR TABLA COMPROBANTE CABECERA
       $insertar_cabecera = mysqli_query($conection, "INSERT INTO fac_comprobante_cab(COD_TIP_NIF_EMIS, NUM_NIF_EMIS, NOM_RZN_SOC_EMIS, NOM_COMER_EMIS, COD_UBI_EMIS, TXT_DMCL_FISC_EMIS, COD_TIP_NIF_RECP, NUM_NIF_RECP,NOM_RZN_SOC_RECP, TXT_DMCL_FISC_RECEP, FEC_EMIS, FEC_VENCIMIENTO, COD_TIP_CPE, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_MND, TIP_CAMBIO, MAIL_ENVIO, COD_PRCD_CARGA, MNT_TOT_GRAVADO, MNT_TOT_TRIB_IGV, MNT_TOT, COD_PTO_VENTA, ENVIAR_A_SUNAT, RETORNA_XML_ENVIO, RETORNA_XML_CDR, RETORNA_PDF, COD_FORM_IMPR, TXT_VERS_UBL, TXT_VERS_ESTRUCT_UBL, COD_ANEXO_EMIS, COD_TIP_OPE_SUNAT, ID_SEDE, MNT_TOT_INAFECTO) VALUES ('6','$ruc','$razon_social','$nombre_comercial','$ubigeo','$direccion','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$txtDireccionCliente','$txtFechaEmision','$txtFechaVencimiento','03','$desc_serie','$desc_correlativo','$cbxTipoMoneda','1.000','$correo','001', '$op_gravada', '$igv', '$total', 'jmifact', 'true', 'true', 'true', 'true','001','2.1','2.0','0000','0101','00001','$op_inafecta')");

       if($insertar_cabecera){

            //GRABAR TABLA COMPROBANTE DETALLE
            $buscar_detalle = mysqli_query($conection, "SELECT
            idfacturador as codigo,
            valor_unitario as unitario,
            valor_igv as igv,
            importe_venta as total,
            descripcion as descripcion,
            cantidad as cantidad,
            idpago as idpago,
            inafecto as inafecto
            FROM temporal_facturador
            WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

            if($buscar_detalle->num_rows > 0){
                while($row = $buscar_detalle->fetch_assoc()) {

                    $codigo = $row['codigo'];
                    $cantidad = $row['cantidad'];
                    $unitario = $row['unitario'];
                    $total = $row['total'];
                    $igv = $row['igv'];
                    $descripcion = $row['descripcion'];
                    $idpago = $row['idpago'];
                    $inafecto = $row['inafecto'];
                    
                    if($inafecto=='1'){
                        $inafecto = 30;
                    }else{
                        $inafecto = 10;
                    }

                    $insertar_detalle = mysqli_query($conection,"INSERT INTO fac_comprobante_det(COD_ITEM, FEC_EMIS, FEC_VENCIMIENTO, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_UNID_ITEM, CANT_UNID_ITEM, VAL_UNIT_ITEM, PRC_VTA_UNIT_ITEM, VAL_VTA_ITEM, MNT_BRUTO, MNT_PV_ITEM, COD_TIP_PRC_VTA,COD_TIP_AFECT_IGV_ITEM,COD_TRIB_IGV_ITEM,POR_IGV_ITEM,MNT_IGV_ITEM, TXT_DESC_ITEM,DET_VAL_ADIC01,DET_VAL_ADIC02,DET_VAL_ADIC03,DET_VAL_ADIC04,idpago_detalle) VALUES ('$codigo','$txtFechaEmision','$txtFechaVencimiento','$desc_serie','$desc_correlativo','ARE','$cantidad','$unitario','$total','$unitario','$unitario','$total','01','$inafecto','1000','18','$igv','$descripcion','PROYECTO LAGUNA BEACH','','','','$idpago')");

                    //INGRESAR DATOS DE COMPROBANTE EN TABLA PAGOS DETALLE
                    $actualiza_datos_pago = mysqli_query($conection, "INSERT INTO gp_pagos_detalle_comprobante(idpago_detalle, serie, numero, cliente_tipodoc, cliente_doc, cliente_datos, pagado, fecha_emision, fecha_vencimiento, comprobante_url, tipo_moneda, id_concepto, debe_haber,tipo_comprobante_sunat) VALUES ('$idpago','$desc_serie','$desc_correlativo','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$total','$txtFechaEmision','$txtFechaVencimiento','$url','$cbxTipoMoneda','03', 'H','03')");

                    //totales en tablas pagos detalle
                    $consultar_total_1 = mysqli_query($conection, "SELECT round(pagado,2) as pagado FROM gp_pagos_detalle WHERE idpago_detalle='$idpago'");
                    $respuesta_total_1 = mysqli_fetch_assoc($consultar_total_1);
                    $total_1 = $respuesta_total_1['pagado'];

                    $consultar_total_2 = mysqli_query($conection, "SELECT round(SUM(pagado),2) as pagado FROM gp_pagos_detalle_comprobante WHERE idpago_detalle='$idpago'");
                    $respuesta_total_2 = mysqli_fetch_assoc($consultar_total_2);
                    $total_2 = $respuesta_total_2['pagado'];

                   if($total_1 == $total_2){

                        $actualiza_datos_pago = mysqli_query($conection, "UPDATE gp_pagos_detalle
                        SET estado_facturacion='1'
                        WHERE idpago_detalle='$idpago'");   

                    }
                }    
                    //===========================================
                    
                    $id = $idpago;
                    
                   $consulta_agencia = mysqli_query($conection, "SELECT 
					if(gppc.moneda_pago = '15381', cd.texto2, cd.texto3) as CuentaContable,
					gppd.nro_operacion as nro_operacion,
					gppd.id_venta as id_venta
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.agencia_bancaria AND cd.codigo_tabla='_BANCOS'
					WHERE gppd.idpago_detalle='$id'");														
            		$respuesta_consulta_agencia = mysqli_fetch_assoc($consulta_agencia);
                    $respuesta_agencia = $respuesta_consulta_agencia['CuentaContable'];
                    
                    
                     $consulta_datos = mysqli_query($conection, "SELECT 
					gppd.nro_operacion as nro_operacion,
					gppd.id_venta as id_venta
					FROM gp_pagos_detalle gppd
					WHERE gppd.idpago_detalle='$id'");
					$conteo = mysqli_num_rows($consulta_datos);
            		$respuesta_datos = mysqli_fetch_assoc($consulta_datos);
            		
                    if($conteo >0){
                        $dato_nro_operacion = $respuesta_datos['nro_operacion'];
                        $dato_id_venta = $respuesta_datos['id_venta'];
                    }else{
                        $dato_nro_operacion = 0;
                        $dato_id_venta = 0;
                    }
                    
                    $query2 = mysqli_query($conection, "UPDATE 
					gp_pagos_cabecera gppc 
					INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
					SET gppc.cuenta_contable='$respuesta_agencia'
					WHERE gppd.idpago_detalle='$id'");
                   
        
        			/*************** CONSULTA PAGO CABECERA *****************/
        			$consultar_pago = mysqli_query($conection, "SELECT 
					gppc.idpago as idpago,
					gppd.idpago_detalle as iddetalle,
					gppc.sede as Sede,                                    
					date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
					cd.texto1 as Moneda,
					gppd.tipo_cambio as TipoCambio,
					gppc.glosa as Glosa,
					SUM(gppd.importe_pago) as TotalImporte,
					if(cd.texto1='USD',cdx.texto2,cdx.texto3) as CuentaContable,
					gppc.operacion as Operacion,
					gppd.nro_operacion as Numero,
					gppc.accion as Accion,
					gppc.flujo_caja as Flujo,
					gppd.debe_haber as DebHab
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
					INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.agencia_bancaria AND cdx.codigo_tabla='_BANCOS'
					WHERE gppd.esta_borrado=0 AND gppd.id_venta='$dato_id_venta' AND gppd.nro_operacion='$dato_nro_operacion'");
        						
					$result = mysqli_num_rows($consultar_pago);
						
					if ($result>0){

						//inicio de numfilas_consultapago	
						
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
							
							if($tipo_cambio=='0'){
							    
							    $consultar_mx = mysqli_query($conection, "SELECT max(idconfig_tipo_cambio) as max FROM configuracion_tipo_cambio");
                                $respuesta_mx = mysqli_fetch_assoc($consultar_mx);
                                $max = $respuesta_mx['max'];
							    
							    $consultar_tc = mysqli_query($conection, "SELECT valor FROM configuracion_tipo_cambio WHERE idconfig_tipo_cambio='$max'");
                                $respuesta_tc = mysqli_fetch_assoc($consultar_tc);
                                
                                $tipo_cambio = $respuesta_tc['valor'];
							}
							
							//COMPLEMENTAR GLOSA CON NOMBRE DE CLIENTE
                            $nombre="";
							$consultar_nombre = mysqli_query($conection, "SELECT 
							concat(dc.apellido_paterno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as nombre
							FROM gp_pagos_detalle gppd
							INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
							INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
							INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
							WHERE gppd.idpago_detalle='$id'");
							$respuesta_nombre = mysqli_fetch_assoc($consultar_nombre);
							$nombre = $respuesta_nombre['nombre'];
							
							$glosa = $nombre.' - '.$glosa;
							$contador = "";
							if($debe_haber == "H"){		
							
								$consulta_id = mysqli_query($conection,"SELECT MAX(Id_Cabecera) AS contador FROM ingresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								//consultar si ya se registro pago
								$consultar_cabecera = mysqli_query($conection, "SELECT idingresos_cabecera as id, Id_Cabecera as codigo, Total as importe, Numero FROM ingresos_cabecera WHERE identificador='$iddetalle' AND Numero='$numero' AND Moneda='$moneda_pago'");
								$respuesta_cabecera = mysqli_num_rows($consultar_cabecera);
								
								if($respuesta_cabecera>0){
								    
								    //OBTENER ID DE INGRESOS CABECERA
								    $consultar_id = mysqli_fetch_assoc($consultar_cabecera);
								    $idingresos_cab = $consultar_id['id'];
								    $cod_cabecera = $consultar_id['codigo'];
								    $insertar_pagoCabHab = mysqli_query($conection, "SELECT * FROM ingresos_cabecera WHERE idingresos_cabecera='$idingresos_cab'");
								    
								}else{
								 
    								$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, id_pago, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, flujo_caja) 
    								VALUES ('$contador','$sede','$iddetalle','$idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$flujo')");
    								$cod_cabecera = $contador;
								}
								if($insertar_pagoCabHab){
									 /***********Insertar detalle ingreso ********/
									$detalle = 0;
									$consultar_detalle = mysqli_query($conection, "SELECT
									gppd.idpago as id,
									gppd.idpago_detalle as id_detalle,
									gppdc.tipo_comprobante_sunat as TipoComp,
									gppdc.serie as Serie,
									gppdc.numero as Numero,
									if(gppdc.tipo_moneda='USD',gppdc.pagado,(gppdc.pagado * gppd.tipo_cambio)) as TotalImporte,
									if(gppdc.tipo_moneda='USD', cdx.texto2, cdx.texto3) as CuentaContable,
									gppdc.tipo_moneda as moneda,
									cdx.texto5 as CentroCosto,
									gppdc.cliente_doc as DniRuc,
									gppdc.cliente_datos as RazonSocial,
									date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									gppd.debe_haber as DebHab,
									gppd.nro_operacion as nro_operacion
									FROM gp_pagos_detalle_comprobante gppdc
									INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
									INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
									INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppdc.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
									WHERE gppd.esta_borrado=0 AND gppd.id_venta='$dato_id_venta' AND gppd.nro_operacion='$numero'
									GROUP BY Serie, Numero");
								
									$detalle = mysqli_num_rows($consultar_detalle);
									
									for ($c=1; $c <= $detalle ; $c++){
									
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
										$nro_operacion = $respuesta_detalle['nro_operacion'];		
										
										//CONSULTA SI EXISTE REGISTRO DETALLE CON LA SERIE Y NUMERO DEL COMPROBANTE
										$consultar_regdet = mysqli_query($conection, "SELECT 
										if(cdx.texto1='USD',SUM(gppdc.pagado),(gppd.importe_pago)) as importePago
										FROM gp_pagos_detalle_comprobante gppdc
										INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
										INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
										WHERE gppdc.serie='$serie' AND gppdc.numero='$numero' AND gppd.nro_operacion='$nro_operacion'");
										$respuesta_regdet = mysqli_num_rows($consultar_regdet);
										
										if($respuesta_regdet>0){
										    
										    $row = mysqli_fetch_assoc($consultar_regdet);
										    $total_pagado = $row['importePago'];
										    
    										//consultar si ya se registro el detalle con la serie y numero
    										$consultar_detalles = mysqli_query($conection, "SELECT idingresos_detalle FROM ingresos_detalle WHERE Serie='$serie' AND Numero='$numero'");
    										$respuesta_detalles = mysqli_num_rows($consultar_detalles);
    										
    										if($respuesta_detalles<=0){
    										
        										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
        										VALUES ('$cod_cabecera','$sede','$iddetalle','$c', '$tipo_comprobante','$serie','$numero','$total_pagado','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','',NULL,'$debe_haber')");
    										
    										}
										}
										
										$VARIABLE = $detalle;
									}
									
								}
							
							    
							}
						//$data = $respuesta_pago;			
						
					}
					
                    
                    
                    //============================================
                    

                        

                
                
                //============================================ VENTAS
                
                    $serie_filtro = $desc_serie;
                    $numero_filtro = $desc_correlativo;
                    
                    /****** CONSULTA VENTA ******/
                    
                            $consultar_iddet = mysqli_query($conection, "SELECT idpago_detalle as id FROM fac_comprobante_det");
                    
                            $consultar_iddet = mysqli_query($conection, "SELECT max(idpago_detalle) as id FROM fac_comprobante_det WHERE NUM_SERIE_CPE='$serie_filtro' AND NUM_CORRE_CPE='$numero_filtro'");
                            $respuesta_iddet = mysqli_fetch_assoc($consultar_iddet);
                            
                            $idpago_detalle=$respuesta_iddet['id'];
                            
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
                    
							$consultar_pagoVC = mysqli_query($conection,"SELECT
																'1' AS Identificador,
																fcc.NOM_RZN_SOC_RECP AS razon_social,
																CONCAT(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',1),' ',SUBSTRING_INDEX(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',3),' ',-1)) as glosaa,
																fcc.NUM_NIF_RECP AS Ruc_Dni,
																date_format(fcc.FEC_EMIS, '%Y-%m-%d') AS Fecha,
																date_format(fcc.FEC_VENCIMIENTO, '%Y-%m-%d') AS FechaVencimiento,
																fcc.MNT_TOT_DESCUENTO AS Descuento,
																fcc.MNT_TOT AS TotalImporte,
																fcc.MNT_TOT_OTR_CGO AS Servicio,
																fcc.ID_SEDE AS Sede,
																fcc.NUM_SERIE_CPE AS Serie,
																fcc.NUM_CORRE_CPE AS Numero,
																fcc.COD_TIP_CPE AS tipoCsun,
																fcc.MNT_TOT_TRIB_IGV AS IGV,
																fcc.COD_MND AS Moneda,
																fcc.TIP_CAMBIO AS TipoCambio,
																'' AS Accion,
																'' AS TipoR,
																'' AS SerieR,
																'' AS NumeroR,
																'' AS FechaR,
																'' AS Propina, 
																'VENTAS INTERFACE LAGUNA' AS Glosa
																FROM fac_comprobante_cab fcc
																WHERE fcc.NUM_SERIE_CPE='$serie_filtro' AND fcc.NUM_CORRE_CPE='$numero_filtro'
																order by fcc.FEC_EMIS");     
										
                            $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                            $idpagoVC = $respuesta_pago2['idpago'];
                            $iddetalleVC = $respuesta_pago2['Identificador'];
                            $rsVC=$respuesta_pago2['razon_social'];
                            $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                            $fecha_pVC = $respuesta_pago2['Fecha'];
                            $fecha_VencimientoVC = $respuesta_pago2['FechaVencimiento']; 
                            $desc_pVC = $respuesta_pago2['Descuento']; 							
                            $importeTVC = $respuesta_pago2['TotalImporte'];
                            $servcVC = $respuesta_pago2['Servicio'];
                            $sedeVC = $respuesta_pago2['Sede'];
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
                            $nombre_glosa = $respuesta_pago2['glosaa'];
                            $glosaVC = $nombre_glosa.' - '.$glosa;
							
							//id para venta cabecera
                            $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                            $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
							$contadorVC = $consultaVC['contador'];
							$contadorVC = $contadorVC + 1;
							
							/****insertar datos en ventas cabecera****/
                            $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Fecha_Vencimiento, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                            VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC', '$fecha_VencimientoVC','$desc_pVC','$importeTVC','$servcVC','$sedeVC','$serieVC','$numVC','$tipo_codsunatVC','$igvVC', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC',NULL,'0','$glosaVC')");
							
							 /***********Insertar detalle ventas ********/
							if($insertar_pagoCabVentas){
								$detalleVC = 0;
								$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																		gppd.idpago_detalle as iddetalle,                                   						
																		fcc.ID_SEDE as Sede,                                   						
																		date_format(fcd.FEC_EMIS, '%Y-%m-%d %H:%i:%s') as Fecha,
																		fcc.MNT_TOT_DESCUENTO as Descuento,
																		fcd.MNT_PV_ITEM as ImportePago,
																		fcc.MNT_TOT_OTR_CGO as Servicio,   
																		fcd.NUM_SERIE_CPE as Serie,    
																		fcd.NUM_CORRE_CPE as Numero,  
																		fcc.COD_TIP_CPE  as TipoCS,
																		cdtx.texto1 as CtaContable,    
																		fcd.MNT_IGV_ITEM  as IGV,
																		cdtx.texto2 as CentroCosto
																		FROM fac_comprobante_det fcd
																		INNER JOIN fac_comprobante_cab AS fcc ON fcc.NUM_CORRE_CPE=fcd.NUM_CORRE_CPE AND fcc.NUM_SERIE_CPE=fcd.NUM_SERIE_CPE
																		INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=fcd.idpago_detalle
																		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
																		INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
																		INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																		WHERE fcc.NUM_CORRE_CPE='$numero_filtro' AND fcc.NUM_SERIE_CPE='$serie_filtro'
																		GROUP BY fcd.idcomprobante_det
																		ORDER BY fcd.FEC_EMIS");
																
                                    $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
									
                                    for ($j=1; $j <= $detalleVC ; $j++) {
									
                                        $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                        $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                        $sedeDVC= $respuesta_detalleVC['Sede'];
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
												
										$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')");
                                        
                                        $consulta = "INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')";
                                        
                                        array_push($dataList, $consulta);
									
                                    }										
							}
							
				//============================================ FIN VENTAS
                
                
            }


            $cdr_sunat = str_replace(array(",","'"), ' ', $cdr_sunat);
            $sunat_description = str_replace(array(","), ' ', $sunat_description);

            //GRABAR TABLA COMPROBANTE IMPRESION
            $insertar_comprobante = mysqli_query($conection, "INSERT INTO fac_comprobante_impr(cadena, cdr_sunat, codigo_hash, correlativo_cpe, errors, estado_documento, pdf_bytes, serie_cpe, sunat_descripcion, sunat_note, sunat_responsecode, ticket_sunat, tipo_cpe, url_valor, xml_enviado, control_usuario, cliente, idlote, numero, serie, fecha_emision, tipo_comprobante_sunat) VALUES ('$cadena_para_codigo_qr','$cdr_sunat','$codigo_hash','$correlativo_cpe','$errors','$estado_documento','$pdf_bytes','$serie_cpe','$sunat_description','$sunat_note','$sunat_responsecode','$ticket_sunat','$tipo_cpe','$url', '$xml_enviado','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$desc_correlativo','$desc_serie','$txtFechaEmision','03')");

            if($insertar_comprobante){
                $consulta_correlativo = mysqli_query($conection, "SELECT
                correlativo as correlativo
                FROM fac_correlativo
                WHERE tipo_documento='BOL' AND estado='1'");
                
                if($consulta_correlativo->num_rows > 0){

                    $respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);
                    $correlativo = $respuesta_correlativo['correlativo'];
                    $correlativo = ($correlativo + 1);

                    //ACTUALIZAR EL CORRELATIVO
                    $actualiza_correlativo = mysqli_query($conection, "UPDATE fac_correlativo
                    SET correlativo='$correlativo', user_registro='$idusuario'
                    WHERE tipo_documento='BOL' AND estado='1'");

                    $data['status'] = 'ok';
                    $data['data'] = 'El comprobante fue emitido con exito.';
                    $data['serie'] = $desc_serie;
                    $data['numero'] = $desc_correlativo;
                    $data['fecha_emision'] = $txtFechaEmision;
                    //$data['VARIABLE'] = $VARIABLE;

                    $data['cliente'] = $txtFiltroCliente;
                    $data['propiedad'] = $txtFiltroPropiedad;
                    $data['tipodoc'] = '03';
                }   
            }                     
       }
                
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnListarTablaComprobantesEmitidos'])){

    
    $serie = isset($_POST['serie']) ? $_POST['serie'] : Null;
    $serier = trim($serie);
    
    $numero = isset($_POST['numero']) ? $_POST['numero'] : Null;
    $numeror = trim($numero);
    
    $fechaEmision = isset($_POST['fechaEmision']) ? $_POST['fechaEmision'] : Null;
    $fechaEmisionr = trim($fechaEmision);
       
   
    $query = mysqli_query($conection,"SELECT
            fac.fecha_emision as fecha_emision,
            fac.serie as serie,
            fac.numero as numero,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as datos,
            concat('ZONA ',gpz.nombre,' MZ ',SUBSTRING(gpm.nombre,9,2),' LT ',SUBSTRING(gpl.nombre,6,2)) as propiedad,
            fac.url_valor as url_valor
            FROM fac_comprobante_impr fac
            INNER JOIN datos_cliente AS dc ON dc.documento=fac.cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=fac.idlote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            WHERE fac.serie='$serier' AND numero='$numeror' AND fecha_emision='$fechaEmisionr'" ); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'fecha_emision' => $row['fecha_emision'],
                'serie' => $row['serie'],
                'numero' => $row['numero'],
                'datos' => $row['datos'],
                'propiedad' => $row['propiedad'],
                'url_valor' => $row['url_valor']
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

if(isset($_POST['btnListarTablaComprobantesImpresos'])){

    
    $txtFiltroCliente = isset($_POST['txtFiltroCliente']) ? $_POST['txtFiltroCliente'] : Null;
    $txtFiltroClienter = trim($txtFiltroCliente);
    
    $txtFiltroPropiedad = isset($_POST['txtFiltroPropiedad']) ? $_POST['txtFiltroPropiedad'] : Null;
    $txtFiltroPropiedadr = trim($txtFiltroPropiedad);
    
    $txtFechaVencimiento = isset($_POST['txtFechaVencimiento']) ? $_POST['txtFechaVencimiento'] : Null;
    $txtFechaVencimientor = trim($txtFechaVencimiento);
    
    $txtFiltroCliente2 = isset($_POST['txtFiltroCliente2']) ? $_POST['txtFiltroCliente2'] : Null;
    $txtFiltroCliente2r = trim($txtFiltroCliente2);

    $txtFiltroTipoComprobante2 = isset($_POST['txtFiltroTipoComprobante2']) ? $_POST['txtFiltroTipoComprobante2'] : Null;
    $txtFiltroTipoComprobante2r = trim($txtFiltroTipoComprobante2);
   
    
    $query_cliente = "";
    $query_TipoComprobante= "";

    if(!empty($txtFiltroCliente2r)){
        $query_cliente = "AND facab.NUM_NIF_RECP='$txtFiltroCliente2r'";
    }

    if(!empty($txtFiltroTipoComprobante2r)){

        $consulta_codigosunat = mysqli_query($conection, "SELECT codigo_sunat as codigo FROM configuracion_detalle WHERE idconfig_detalle='$txtFiltroTipoComprobante2r' AND codigo_tabla='_TIPO_COMPROBANTE_SUNAT'");
        $respuesta_codigosunat = mysqli_fetch_assoc($consulta_codigosunat);
        $codigo_sunat = $respuesta_codigosunat['codigo'];

        $query_TipoComprobante= "AND facab.COD_TIP_CPE='$codigo_sunat'";
    }
   
   
    $query = mysqli_query($conection,"SELECT 
            facab.idcomprobante_cab as id,
            facab.FEC_EMIS as fecha_emision,
            facab.NUM_SERIE_CPE as serie,
            facab.NUM_CORRE_CPE as numero,
            facab.NOM_RZN_SOC_RECP as cliente,
            if(facab.esta_anulado='0',if(facab.COD_TIP_DOC_REF='01', 'FACTURA',if(facab.COD_TIP_DOC_REF='03', 'BOLETA DE VENTA', '-')), 'ANULADO') as tip_doc_ref,
            if(facab.NUM_SERIE_CPE_REF='','-',facab.NUM_SERIE_CPE_REF) as serie_ref,
            if(facab.NUM_CORRE_CPE_REF='','-',facab.NUM_CORRE_CPE_REF) as correlativo_ref,
            format(facab.MNT_TOT_TRIB_IGV,2) as igv,
            if(facab.MNT_TOT_INAFECTO>0, facab.MNT_TOT_INAFECTO, '0.00') as inafecto,
            format(facab.MNT_TOT,2) as total,
            faccom.url_valor as url_valor,
            facab.COD_TIP_CPE as codigo_tipo_comprobante,
            if(facab.esta_anulado='0',cdx.nombre_corto,'ANULADO') as nombre_comprobante,
            if(facab.esta_anulado='0',cdx.texto4,'#F20B00') as color_comprobante
            FROM fac_comprobante_cab facab
            INNER JOIN fac_comprobante_impr AS faccom ON faccom.serie=facab.NUM_SERIE_CPE AND faccom.numero=facab.NUM_CORRE_CPE AND faccom.fecha_emision=facab.FEC_EMIS
            INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=facab.COD_TIP_CPE AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
            WHERE facab.idcomprobante_cab>0
            $query_TipoComprobante
            $query_cliente
            GROUP BY facab.idcomprobante_cab
            ORDER BY fecha_emision DESC, serie ASC, numero DESC
            "); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha_emision' => $row['fecha_emision'],
                'serie' => $row['serie'],
                'numero' => $row['numero'],
                'cliente' => $row['cliente'],
                'tip_doc_ref' => $row['tip_doc_ref'],
                'serie_ref' => $row['serie_ref'],
                'correlativo_ref' => $row['correlativo_ref'],
                'url_valor' => $row['url_valor'],
                'total' => $row['total'],
                'inafecto' => $row['inafecto'],
                'total' => $row['total'],
                'igv' => $row['igv'],
                'nombre_comprobante' => $row['nombre_comprobante'],
                'color_comprobante' => $row['color_comprobante'],
                'codigo_tipo_comprobante' => $row['codigo_tipo_comprobante']
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
        $data['query']=$query;

    }else{
        
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $data['data'] = $dataList;
        $data['query']=$query;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}

if (isset($_POST['btnIncluirIGV'])) {

    $idRegistro = $_POST['idRegistro'];    

    $consulta = mysqli_query($conection, "SELECT 
    valor_unitario as unitario, 
    importe_venta as venta, 
    igv as igv,
    iduser as usuario,
    fecha_emision as fecha_emision,
    doc_cliente as cliente,
    idlote as idlote
    FROM temporal_facturador 
    WHERE idfacturador='$idRegistro'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $total_unitario = $consultaa['unitario'];
    $estado_igv = $consultaa['igv'];

    $idusuario = $consultaa['usuario'];
    $fecha_emision = $consultaa['fecha_emision'];
    $doc_cliente = $consultaa['cliente'];
    $idlote = $consultaa['idlote'];
    
    if ($consulta) {
        if($estado_igv=="0"){
            $total_unitario = ($total_unitario/1.18);
            $total_igv = ($total_unitario * 0.18);
            $total_importe = $total_unitario + $total_igv;

            $query = mysqli_query($conection, "UPDATE 
                    temporal_facturador SET
                    igv='1',
                    valor_unitario='$total_unitario',
                    valor_igv='$total_igv',
                    importe_venta='$total_importe'
                    WHERE idfacturador='$idRegistro'");

            //CONSULTAR TOTALES
            $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
            $op_gravada = $respuesta_op_gravada['subtotal'];
            $igv = $respuesta_op_gravada['igv'];

            $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0.00') as total FROM temporal_facturador WHERE estado='1' AND inafecto='1' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_gravada);
            $op_inafecta = $respuesta_op_inafecta['total'];

            $consultar_totales = mysqli_query($conection, "SELECT  ROUND(SUM(importe_venta),2) as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
            $total = $respuesta_totales['total'];

            //ACTUALIZAR TOTALES

            $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
            op_gravada='$op_gravada',
            op_inafecta='$op_inafecta',
            igv='$igv',
            importe_total='$total'
            WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
        }   
        $data['status'] = 'ok';
        $data['data'] = 'Se establecio que el pago tiene incluido el IGV.';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No pudo completarse la operacion. Itente nuevamente';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnNoIncluidoIGV'])) {

    $idRegistro = $_POST['idRegistro'];    

    $consulta = mysqli_query($conection, "SELECT 
    valor_unitario as unitario, 
    importe_venta as venta, 
    igv as igv,
    iduser as usuario,
    fecha_emision as fecha_emision,
    doc_cliente as cliente,
    idlote as idlote
    FROM temporal_facturador 
    WHERE idfacturador='$idRegistro'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $total_venta = $consultaa['venta'];
    $estado_igv = $consultaa['igv'];

    $idusuario = $consultaa['usuario'];
    $fecha_emision = $consultaa['fecha_emision'];
    $doc_cliente = $consultaa['cliente'];
    $idlote = $consultaa['idlote'];
    
    if ($consulta) {
        if($estado_igv=="1"){
            $total_igv = ($total_venta * 0.18);
            $total_importe = $total_venta + $total_igv;

            $query = mysqli_query($conection, "UPDATE 
                    temporal_facturador SET
                    igv='0',
                    valor_unitario='$total_venta',
                    valor_igv='$total_igv',
                    importe_venta='$total_importe'
                    WHERE idfacturador='$idRegistro'");

            //CONSULTAR TOTALES
            $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
            $op_gravada = $respuesta_op_gravada['subtotal'];
            $igv = $respuesta_op_gravada['igv'];

            $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0.00') as total FROM temporal_facturador WHERE estado='1' AND inafecto='1' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_gravada);
            $op_inafecta = $respuesta_op_inafecta['total'];

            $consultar_totales = mysqli_query($conection, "SELECT  ROUND(SUM(importe_venta),2) as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
            $total = $respuesta_totales['total'];

            //ACTUALIZAR TOTALES

            $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
            op_gravada='$op_gravada',
            op_inafecta='$op_inafecta',
            igv='$igv',
            importe_total='$total'
            WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            
        }   
        $data['status'] = 'ok';
        $data['data'] = 'Se establecio que el pago no incluye IGV.';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No pudo completarse la operacion. Itente nuevamente';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnHabilitarInafecto'])) {

    $idRegistro = $_POST['idRegistro'];    

    $consulta = mysqli_query($conection, "SELECT 
    valor_unitario as unitario, 
    importe_venta as venta, 
    igv as igv, 
    inafecto as inafecto, 
    iduser as usuario,
    fecha_emision as fecha_emision,
    doc_cliente as cliente,
    idlote as idlote
    FROM temporal_facturador 
    WHERE idfacturador='$idRegistro'");

    $consultaa = mysqli_fetch_assoc($consulta);
    $total_unitario = $consultaa['unitario'];
    $total_venta = $consultaa['venta'];
    $estado_inafecto = $consultaa['inafecto'];
    $estado_igv = $consultaa['igv'];

    $idusuario = $consultaa['usuario'];
    $fecha_emision = $consultaa['fecha_emision'];
    $doc_cliente = $consultaa['cliente'];
    $idlote = $consultaa['idlote'];
    
    if ($consulta) {
        if($estado_inafecto=="0"){

            if($estado_igv == "1"){

                $query = mysqli_query($conection, "UPDATE 
                    temporal_facturador SET
                    valor_unitario='$total_venta',
                    valor_igv='0.00',
                    importe_venta='$total_venta',
                    inafecto='1',
                    igv='2'
                    WHERE idfacturador='$idRegistro'");

            }else{
                if($estado_igv == "0"){

                    $query = mysqli_query($conection, "UPDATE 
                        temporal_facturador SET
                        valor_unitario='$total_unitario',
                        valor_igv='0.00',
                        importe_venta='$total_unitario',
                        inafecto='1',
                        igv='2'
                        WHERE idfacturador='$idRegistro'");
                }
            }

            //CONSULTAR TOTALES
            $consultar_op_gravada = mysqli_query($conection, "SELECT  if(SUM(valor_unitario)>0,SUM(valor_unitario),'0.00') as subtotal ,if(SUM(valor_igv)>0,SUM(valor_igv),'0.00') as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
            $op_gravada = $respuesta_op_gravada['subtotal'];
            $igv = $respuesta_op_gravada['igv'];

            $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0.00') as total FROM temporal_facturador WHERE estado='1' AND inafecto='1' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_gravada);
            $op_inafecta = $respuesta_op_inafecta['total'];

            $consultar_totales = mysqli_query($conection, "SELECT  ROUND(if(SUM(importe_venta)>0,SUM(importe_venta),'0.00'),2) as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
            $total = $respuesta_totales['total'];
            
            //ACTUALIZAR TOTALES

            $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
            op_gravada='$op_gravada',
            op_inafecta='$op_inafecta',
            igv='$igv',
            importe_total='$total'
            WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
            
        }  
        $data['status'] = 'ok';
        $data['data'] = 'Se establecio que el pago es Inafecto del IGV';
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No pudo completarse la operacion. Itente nuevamente';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnDeshabilitarInafecto'])) {

    $idRegistro = $_POST['idRegistro'];    

    $consulta = mysqli_query($conection, "SELECT 
    valor_unitario as unitario, 
    importe_venta as venta, 
    igv as igv, 
    inafecto as inafecto, 
    iduser as usuario,
    fecha_emision as fecha_emision,
    doc_cliente as cliente,
    idlote as idlote 
    FROM temporal_facturador 
    WHERE idfacturador='$idRegistro'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $total_unitario = $consultaa['unitario'];
    $total_venta = $consultaa['venta'];
    $estado_inafecto = $consultaa['inafecto'];
    $estado_igv = $consultaa['igv'];

    $idusuario = $consultaa['usuario'];
    $fecha_emision = $consultaa['fecha_emision'];
    $doc_cliente = $consultaa['cliente'];
    $idlote = $consultaa['idlote'];
    
    if ($consulta) {
        if($estado_inafecto=="1"){

            if($estado_igv == "2"){

                $total_unitario = ($total_unitario/1.18);
                $total_igv = ($total_unitario * 0.18);
                $total_importe = $total_unitario + $total_igv;

                $query = mysqli_query($conection, "UPDATE 
                        temporal_facturador SET
                        igv='1',
                        inafecto='0',
                        valor_unitario='$total_unitario',
                        valor_igv='$total_igv',
                        importe_venta='$total_importe'
                        WHERE idfacturador='$idRegistro'");
                
                //CONSULTAR TOTALES
                $consultar_op_gravada = mysqli_query($conection, "SELECT  if(SUM(valor_unitario)>0,SUM(valor_unitario),'0.00') as subtotal ,if(SUM(valor_igv)>0,SUM(valor_igv),'0.00') as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
                $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                $op_gravada = $respuesta_op_gravada['subtotal'];
                $igv = $respuesta_op_gravada['igv'];

                $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0.00') as total FROM temporal_facturador WHERE estado='1' AND inafecto='1' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
                $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_gravada);
                $op_inafecta = $respuesta_op_inafecta['total'];

                $consultar_totales = mysqli_query($conection, "SELECT  ROUND(if(SUM(importe_venta)>0,SUM(importe_venta),'0.00'),2) as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");
                $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                $total = $respuesta_totales['total'];

                //ACTUALIZAR TOTALES

                $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
                op_gravada='$op_gravada',
                op_inafecta='$op_inafecta',
                igv='$igv',
                importe_total='$total'
                WHERE iduser='$idusuario' AND doc_cliente='$doc_cliente' AND idlote='$idlote' AND fecha_emision='$fecha_emision'");

            }
            
        }   
        $data['status'] = 'ok';
        $data['data'] = 'Se establecio que el pago NO es Inafecto del IGV';
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No pudo completarse la operacion. Itente nuevamente';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnBuscarDocumento'])) {

    $cbxTipoDocumento = $_POST['cbxTipoDocumento']; 
    $txtNroDocumento = $_POST['txtNroDocumento'];   

    $consulta = mysqli_query($conection, "SELECT codigo_sunat as codigo FROM configuracion_detalle WHERE codigo_tabla='_TIPO_DOCUMENTO' AND idconfig_detalle='$cbxTipoDocumento'");
    $consultaa = mysqli_fetch_assoc($consulta);
    $codigo_api = $consultaa['codigo'];

    $val_sunat = "";
    $val_reniec = "";
    $nombre="";
    $direccion=""; 
    $error_api="";
    $operacion = 0; // 1 : Sunat , 2 : Reniec

    if($codigo_api=='6'){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero='.$txtNroDocumento,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: http://apis.net.pe/api-ruc',
            'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
        ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        //echo $response;  
        $datos = json_decode($response, true);      

        $operacion = 1;
        if(empty($datos["error"])){  
            $val_sunat = "ok";
            $nombre = $datos["nombre"];
            $direccion = $datos["direccion"];            
        }else{
            $val_sunat = "bad";
            $nombre="";
            $direccion="";
            $error_api="(Error: ".$datos["error"].")";  
        }

    }else{

        if($codigo_api=='1'){

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero='.$txtNroDocumento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
            ),
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);
            //echo $response;    
            $datos = json_decode($response, true);     

            $operacion = 2;
            if(empty($datos["error"])){            
                $val_reniec = "ok";
                $nombre = $datos["nombre"];
                $direccion = $datos["direccion"];            
            }else{
                $val_reniec = "bad";
                $nombre="";
                $direccion="";
                $error_api="(Error: ".$datos["error"].")";    
            }
        }
    }

    //CONSULTAR DATOS EN HISTORICO
    $consultar_datos = mysqli_query($conection, "SELECT direccion as direc FROM fac_clientes WHERE tipodocumento='$cbxTipoDocumento' AND documento='$txtNroDocumento'");
    $conteo_consulta = mysqli_num_rows($consultar_datos);
    $val_direccion = "";
    if($conteo_consulta>0){
        $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
        $val_direccion = $respuesta_datos['direc'];
    }

    if((empty($direccion) || ($direccion=="-")) && !empty($val_direccion)){
        $direccion = $val_direccion;
    }

    if ($operacion == "1") {
        if($val_sunat == "ok"){
            $data['status'] = 'ok';
            $data['cliente'] = $nombre;
            $data['direccion'] = $direccion;
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'No se encontraron resultados para el nro documento ingresado. '.$error_api;
            $data['cliente'] = $nombre;
            $data['direccion'] = $direccion;
        }  
        
    } else {
        if($val_reniec == "ok"){
            $data['status'] = 'ok';
            $data['cliente'] = $nombre;
            $data['direccion'] = $direccion;
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'No se encontraron resultados para el nro documento ingresado. '.$error_api;
            $data['cliente'] = $nombre;
            $data['direccion'] = $direccion;
         }  
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnEmitirFactura'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];    
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];    
    $txtUsuario = $_POST['txtUsuario'];  
    $txtFechaEmision = $_POST['txtFechaEmision'];  
    $txtFechaVencimiento = $_POST['txtFechaVencimiento'];
    $cbxTipoMoneda = $_POST['cbxTipoMoneda'];     

    $cbxTipoDocumento = $_POST['cbxTipoDocumento'];  
    $txtNroDocumento = $_POST['txtNroDocumento'];  
    $txtdatos = $_POST['txtdatos'];
    $txtDireccionCliente = $_POST['txtDireccionCliente'];   
    
    $txtUsuario = $_POST['txtUsuario'];

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    //DATOS EMPRESA
    $empresa = mysqli_query($conection, "SELECT 
    token_facturacion as token,
    ruc as ruc,
    razon_social as razon_social,
    nombre_comercial as nombre_comercial,
    ubigeo_inei as ubigeo,
    direccion as direccion,
    url_facturacion as url
    FROM datos_empresa
    WHERE ESTADO='1'");
    $resp_empresa = mysqli_fetch_assoc($empresa);

    $token = $resp_empresa['token'];
    $ruc = $resp_empresa['ruc'];
    $razon_social = $resp_empresa['razon_social'];
    $nombre_comercial = $resp_empresa['nombre_comercial'];
    $ubigeo = $resp_empresa['ubigeo'];
    $direccion = $resp_empresa['direccion'];
    $URL = $resp_empresa['url'];

    //DATOS CLIENTE
    $cliente = mysqli_query($conection, "SELECT 
    tipodocumento as tipo_documento,
    documento as documento,
    concat(apellido_paterno,' ',apellido_materno,' ',nombres) as datos,
    nombre_via as nombre_via,
    email as correo
    FROM datos_cliente
    WHERE documento='$txtFiltroCliente'");
    $resp_cliente = mysqli_fetch_assoc($cliente);

    $tipo_documento = $resp_cliente['tipo_documento'];
    $documento = $resp_cliente['documento'];
    $datos_cliente = $resp_cliente['datos'];
    $nombre_via = $resp_cliente['nombre_via'];
    $correo = $resp_cliente['correo'];
    if(empty($correo)){
        $correo = "admn.gpro@gmail.com";
    }
    //SERIE Y NUMERO
  
    $anio = date('Y');
    $consulta_sn = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='FAC' AND anio='$anio'");
    $resp_sn = mysqli_fetch_assoc($consulta_sn);
    $numero = $resp_sn['num'];
    $serie = $resp_sn['serie'];
    $correlativo = $resp_sn['correlativo'];

    $desc_serie = "";
    if ($numero > 0 && $numero < 10) {
        $desc_serie = $serie . "00" . $numero;
    } else {
        if ($numero > 10 && $numero < 100) {
            $desc_serie = $serie . "0" . $numero;
        } else {
            $desc_serie = $serie . $numero;
        }
    }

    $desc_correlativo = "";
    if ($correlativo > 0 && $correlativo < 10) {
        $desc_correlativo = "0000000" . $correlativo;
    } else {
        if ($correlativo >= 10 && $correlativo < 100) {
            $desc_correlativo = "000000" . $correlativo;
        } else {
            if ($correlativo >= 100 && $correlativo < 1000) {
                $desc_correlativo = "00000" . $correlativo;
            } else {
                if ($correlativo >= 1000 && $correlativo < 10000) {
                    $desc_correlativo = "0000" . $correlativo;
                } else {
                    if ($correlativo >= 10000 && $correlativo < 100000) {
                        $desc_correlativo = "000" . $correlativo;
                    } else {
                        if ($correlativo >= 100000 && $correlativo < 1000000) {
                            $desc_correlativo = "00" . $correlativo;
                        } else {
                            if ($correlativo >= 1000000 && $correlativo < 10000000) {
                                $desc_correlativo = "0" . $correlativo;
                            } else {
                                $desc_correlativo = $correlativo;
                            }
                        }
                    }
                }
            }
        }
    }

    //totales
    $consulta_totales = mysqli_query($conection, "SELECT
    op_gravada as op_gravada,
    igv as igv,
    importe_total as total,
    op_inafecta as op_inafecta
    FROM temporal_facturador_totales
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    $resp_totales = mysqli_fetch_assoc($consulta_totales);

    $op_gravada = $resp_totales['op_gravada'];
    $op_inafecta = $resp_totales['op_inafecta'];
    $igv = $resp_totales['igv'];
    $total = $resp_totales['total'];

    $buscar = mysqli_query($conection, "SELECT
    idfacturador as codigo,
    round(valor_unitario,2) as unitario,
    valor_igv as igv,
    importe_venta as total,
    descripcion as descripcion,
    cantidad as cantidad,
    inafecto as inafecto
    FROM temporal_facturador
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

    while($row = $buscar->fetch_assoc()) {
        
        $inafecto = $row['inafecto'];
        $var_inafecto = 10;
        if($inafecto == 1){
            $var_inafecto = 30;
        }
        
        array_push($dataList,
            '{        
            "COD_ITEM": "'.$row['codigo'].'",
            "COD_UNID_ITEM": "ARE",
            "CANT_UNID_ITEM": "'.$row['cantidad'].'",
            "VAL_UNIT_ITEM": "'.$row['unitario'].'",      
            "PRC_VTA_UNIT_ITEM": "'.$row['total'].'",
            "VAL_VTA_ITEM": "'.$row['unitario'].'",
            "MNT_BRUTO": "'.$row['unitario'].'",
            "MNT_PV_ITEM": "'.$row['total'].'",
            "COD_TIP_PRC_VTA": "01",
            "COD_TIP_AFECT_IGV_ITEM":"'.$var_inafecto.'",
            "COD_TRIB_IGV_ITEM": "1000",
            "POR_IGV_ITEM": "18",
            "MNT_IGV_ITEM": "'.$row['igv'].'",      
            "TXT_DESC_ITEM": "'.$row['descripcion'].'",                  
            "DET_VAL_ADIC01": "PROYECTO LAGUNA BEACH",
            "DET_VAL_ADIC02": "",
            "DET_VAL_ADIC03": "",
            "DET_VAL_ADIC04": ""
            }'
        );
    }

    $array = implode(",",$dataList);
    
   
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$URL.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "TOKEN":"'.$token.'",
    "COD_TIP_NIF_EMIS": "6",
    "NUM_NIF_EMIS": "'.$ruc.'",
    "NOM_RZN_SOC_EMIS": "'.$razon_social.'",
    "NOM_COMER_EMIS": "'.$nombre_comercial.'",
    "COD_UBI_EMIS": "'.$ubigeo.'",
    "TXT_DMCL_FISC_EMIS": "'.$direccion.'",
    "COD_TIP_NIF_RECP": "'.$cbxTipoDocumento.'",
    "NUM_NIF_RECP": "'.$txtNroDocumento.'",
    "NOM_RZN_SOC_RECP": "'.$txtdatos.'",
    "TXT_DMCL_FISC_RECEP": "'.$txtDireccionCliente.'",
    "FEC_EMIS": "'.$txtFechaEmision.'",
    "FEC_VENCIMIENTO": "'.$txtFechaVencimiento.'",
    "COD_TIP_CPE": "01",
    "NUM_SERIE_CPE": "'.$desc_serie.'",
    "NUM_CORRE_CPE": "'.$desc_correlativo.'",
    "COD_MND": "'.$cbxTipoMoneda.'",
    "TIP_CAMBIO":"1.000",
    "MailEnvio": "'.$correo.'",
    "COD_PRCD_CARGA": "001",
    "MNT_TOT_GRAVADO": "'.$op_gravada.'",     
    "MNT_TOT_TRIB_IGV": "'.$igv.'", 
    "MNT_TOT": "'.$total.'", 
    "MNT_TOT_INAFECTO": "'.$op_inafecta.'",
    "COD_PTO_VENTA": "jmifact",
    "ENVIAR_A_SUNAT": "true",
    "RETORNA_XML_ENVIO": "true",
    "RETORNA_XML_CDR": "true",
    "RETORNA_PDF": "true",
      "COD_FORM_IMPR":"001",
      "TXT_VERS_UBL":"2.1",
      "TXT_VERS_ESTRUCT_UBL":"2.0",
      "COD_ANEXO_EMIS":"0000",
      "COD_TIP_OPE_SUNAT": "0101",
      
    "items": [
        '.$array.'
    ]
        
    }',
    CURLOPT_HTTPHEADER => array(
        'postman-token: b4938777-800c-1fb1-b127-aefda436e223',
        'cache-control: no-cache',
        'content-type: application/json'
    ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    //echo $response;

    $datos = json_decode($response, true);

    $cadena_para_codigo_qr = $datos["cadena_para_codigo_qr"];
    $cdr_sunat = $datos["cdr_sunat"];
    $codigo_hash = $datos["codigo_hash"];
    $correlativo_cpe = $datos["correlativo_cpe"];
    $errors = $datos["errors"];
    $estado_documento = $datos["estado_documento"];
    $pdf_bytes = $datos["pdf_bytes"];
    $serie_cpe = $datos["serie_cpe"];
    $sunat_description = $datos["sunat_description"];
    $sunat_note = $datos["sunat_note"];
    $sunat_responsecode = $datos["sunat_responsecode"];
    $ticket_sunat = $datos["ticket_sunat"];
    $tipo_cpe = $datos["tipo_cpe"];
    $url = $datos["url"];
    $xml_enviado = $datos["xml_enviado"];
   
    if(!empty($errors)){ 
        
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo emitir la Factura. Detalle del error : '.$errors;

    } else {    

        //GRABAR TABLA COMPROBANTE CABECERA
       $insertar_cabecera = mysqli_query($conection, "INSERT INTO fac_comprobante_cab(COD_TIP_NIF_EMIS, NUM_NIF_EMIS, NOM_RZN_SOC_EMIS, NOM_COMER_EMIS, COD_UBI_EMIS, TXT_DMCL_FISC_EMIS, COD_TIP_NIF_RECP, NUM_NIF_RECP,NOM_RZN_SOC_RECP, TXT_DMCL_FISC_RECEP, FEC_EMIS, FEC_VENCIMIENTO, COD_TIP_CPE, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_MND, TIP_CAMBIO, MAIL_ENVIO, COD_PRCD_CARGA, MNT_TOT_GRAVADO, MNT_TOT_TRIB_IGV, MNT_TOT, COD_PTO_VENTA, ENVIAR_A_SUNAT, RETORNA_XML_ENVIO, RETORNA_XML_CDR, RETORNA_PDF, COD_FORM_IMPR, TXT_VERS_UBL, TXT_VERS_ESTRUCT_UBL, COD_ANEXO_EMIS, COD_TIP_OPE_SUNAT, ID_SEDE, MNT_TOT_INAFECTO) VALUES ('6','$ruc','$razon_social','$nombre_comercial','$ubigeo','$direccion','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$txtDireccionCliente','$txtFechaEmision','$txtFechaVencimiento','01','$desc_serie','$desc_correlativo','$cbxTipoMoneda','1.000','$correo','001', '$op_gravada', '$igv', '$total', 'jmifact', 'true', 'true', 'true', 'true','001','2.1','2.0','0000','0101','00001','$op_inafecta')");

       if($insertar_cabecera){

            //GRABAR TABLA COMPROBANTE DETALLE
            $buscar_detalle = mysqli_query($conection, "SELECT
            idfacturador as codigo,
            valor_unitario as unitario,
            valor_igv as igv,
            importe_venta as total,
            descripcion as descripcion,
            cantidad as cantidad,
            idpago as idpago,
            inafecto as inafecto
            FROM temporal_facturador
            WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

            if($buscar_detalle->num_rows > 0){
                while($row = $buscar_detalle->fetch_assoc()) {

                    $codigo = $row['codigo'];
                    $cantidad = $row['cantidad'];
                    $unitario = $row['unitario'];
                    $total = $row['total'];
                    $igv = $row['igv'];
                    $descripcion = $row['descripcion'];
                    $idpago = $row['idpago'];
                    $inafecto = $row['inafecto'];
                    
                    if($inafecto=='1'){
                        $inafecto = 30;
                    }else{
                        $inafecto = 10;
                    }

                    $insertar_detalle = mysqli_query($conection,"INSERT INTO fac_comprobante_det(COD_ITEM, FEC_EMIS, FEC_VENCIMIENTO, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_UNID_ITEM, CANT_UNID_ITEM, VAL_UNIT_ITEM, PRC_VTA_UNIT_ITEM, VAL_VTA_ITEM, MNT_BRUTO, MNT_PV_ITEM, COD_TIP_PRC_VTA,COD_TIP_AFECT_IGV_ITEM,COD_TRIB_IGV_ITEM,POR_IGV_ITEM,MNT_IGV_ITEM, TXT_DESC_ITEM,DET_VAL_ADIC01,DET_VAL_ADIC02,DET_VAL_ADIC03,DET_VAL_ADIC04, idpago_detalle) VALUES ('$codigo','$txtFechaEmision','$txtFechaVencimiento','$desc_serie','$desc_correlativo','ARE','$cantidad','$unitario','$total','$unitario','$unitario','$total','01','$inafecto','1000','18','$igv','$descripcion','PROYECTO LAGUNA BEACH','','','','$idpago')");

                    //INGRESAR DATOS DE COMPROBANTE EN TABLA PAGOS DETALLE
                    $actualiza_datos_pago = mysqli_query($conection, "INSERT INTO gp_pagos_detalle_comprobante(idpago_detalle, serie, numero, cliente_tipodoc, cliente_doc, cliente_datos, pagado, fecha_emision, fecha_vencimiento, comprobante_url, tipo_moneda, id_concepto, debe_haber,tipo_comprobante_sunat) VALUES ('$idpago','$desc_serie','$desc_correlativo','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$total','$txtFechaEmision','$txtFechaVencimiento','$url','$cbxTipoMoneda','03','H','01')");

                    //totales en tablas pagos detalle
                    $consultar_total_1 = mysqli_query($conection, "SELECT round(pagado,2) as pagado FROM gp_pagos_detalle WHERE idpago_detalle='$idpago'");
                    $respuesta_total_1 = mysqli_fetch_assoc($consultar_total_1);
                    $total_1 = $respuesta_total_1['pagado'];

                    $consultar_total_2 = mysqli_query($conection, "SELECT round(SUM(pagado),2) as pagado FROM gp_pagos_detalle_comprobante WHERE idpago_detalle='$idpago'");
                    $respuesta_total_2 = mysqli_fetch_assoc($consultar_total_2);
                    $total_2 = $respuesta_total_2['pagado'];

                    if($total_1 == $total_2){

                        $actualiza_datos_pago = mysqli_query($conection, "UPDATE gp_pagos_detalle
                        SET estado_facturacion='1'
                        WHERE idpago_detalle='$idpago'");   

                    }
                    
                }     
                    //===========================================
                    
                    $id = $idpago;
                    
                   $consulta_agencia = mysqli_query($conection, "SELECT 
					if(gppc.moneda_pago = '15381', cd.texto2, cd.texto3) as CuentaContable
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.agencia_bancaria AND cd.codigo_tabla='_BANCOS'
					WHERE gppd.idpago_detalle='$id'");														
            		$respuesta_consulta_agencia = mysqli_fetch_assoc($consulta_agencia);
                    $respuesta_agencia = $respuesta_consulta_agencia['CuentaContable'];
			
                    $query2 = mysqli_query($conection, "UPDATE 
					gp_pagos_cabecera gppc 
					INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
					SET gppc.cuenta_contable='$respuesta_agencia'
					WHERE gppd.idpago_detalle='$id'");
                   
        
        			/*************** CONSULTA PAGO CABECERA *****************/
        			$consultar_pago = mysqli_query($conection, "SELECT 
					gppc.idpago as idpago,
					gppd.idpago_detalle as iddetalle,
					gppc.sede as Sede,                                    
					date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
					cd.texto1 as Moneda,
					gppd.tipo_cambio as TipoCambio,
					gppc.glosa as Glosa,
					gppd.importe_pago as TotalImporte,
					if(cd.texto1='USD',cdx.texto2,cdx.texto3) as CuentaContable,
					gppc.operacion as Operacion,
					gppd.nro_operacion as Numero,
					gppc.accion as Accion,
					gppc.flujo_caja as Flujo,
					gppd.debe_haber as DebHab
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
					INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.agencia_bancaria AND cdx.codigo_tabla='_BANCOS'
					WHERE gppd.esta_borrado=0 AND gppd.idpago_detalle='$id'");
        						
					$result = mysqli_num_rows($consultar_pago);
						
					if ($result>0){

						/*inicio de numfilas_consultapago*/	
						
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
							
							if($tipo_cambio=='0'){
							    
							    $consultar_mx = mysqli_query($conection, "SELECT max(idconfig_tipo_cambio) as max FROM configuracion_tipo_cambio");
                                $respuesta_mx = mysqli_fetch_assoc($consultar_mx);
                                $max = $respuesta_mx['max'];
							    
							    $consultar_tc = mysqli_query($conection, "SELECT valor FROM configuracion_tipo_cambio WHERE idconfig_tipo_cambio='$max'");
                                $respuesta_tc = mysqli_fetch_assoc($consultar_tc);
                                
                                $tipo_cambio = $respuesta_tc['valor'];
							}
							
							//COMPLEMENTAR GLOSA CON NOMBRE DE CLIENTE
							$consultar_nombre = mysqli_query($conection, "SELECT 
							concat(dc.apellido_paterno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as nombre
							FROM gp_pagos_detalle gppd
							INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
							INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
							INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
							WHERE gppd.idpago_detalle='$id'");
							$respuesta_nombre = mysqli_fetch_assoc($consultar_nombre);
							$nombre = $respuesta_nombre['nombre'];
							
							$glosa = $nombre.' - '.$glosa;
							$contador = "";
							if($debe_haber == "H"){		
							
								$consulta_id = mysqli_query($conection,"SELECT MAX(Id_Cabecera) AS contador FROM ingresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								//consultar si ya se registro pago
								$consultar_cabecera = mysqli_query($conection, "SELECT idingresos_cabecera as id, Id_Cabecera as codigo, Total as importe, Numero FROM ingresos_cabecera WHERE identificador='$iddetalle' AND Numero='$numero' AND Moneda='$moneda_pago'");
								$respuesta_cabecera = mysqli_num_rows($consultar_cabecera);
								
								if($respuesta_cabecera>0){
								    
								    //OBTENER ID DE INGRESOS CABECERA
								    $consultar_id = mysqli_fetch_assoc($consultar_cabecera);
								    $idingresos_cab = $consultar_id['id'];
								    $cod_cabecera = $consultar_id['codigo'];
								    
								    //ACTUALIZAR TOTAL
								    $insertar_pagoCabHab = mysqli_query($conection, "SELECT * FROM ingresos_cabecera WHERE idingresos_cabecera='$idingresos_cab'");
								    
								}else{
								 
    								$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, id_pago, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, flujo_caja) 
    								VALUES ('$contador','$sede','$iddetalle','$idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$flujo')");
    								$cod_cabecera = $contador;
    								
								}
								if($insertar_pagoCabHab){
									 /***********Insertar detalle ingreso ********/
									$detalle = 0;
									$consultar_detalle = mysqli_query($conection, "SELECT
									gppd.idpago as id,
									gppd.idpago_detalle as id_detalle,
									gppdc.tipo_comprobante_sunat as TipoComp,
									gppdc.serie as Serie,
									gppdc.numero as Numero,
									if(gppdc.tipo_moneda='USD',gppdc.pagado,(gppdc.pagado * gppd.tipo_cambio)) as TotalImporte,
									if(gppdc.tipo_moneda='USD', cdx.texto2, cdx.texto3) as CuentaContable,
									gppdc.tipo_moneda as moneda,
									cdx.texto5 as CentroCosto,
									gppdc.cliente_doc as DniRuc,
									gppdc.cliente_datos as RazonSocial,
									date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									gppd.debe_haber as DebHab,
									gppd.nro_operacion as nro_operacion
									FROM gp_pagos_detalle_comprobante gppdc
									INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
									INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
									INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppdc.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
									WHERE gppd.esta_borrado=0 AND gppdc.idpago_detalle='$id' AND gppd.nro_operacion='$numero'
									GROUP BY Serie, Numero");
								
									$detalle = mysqli_num_rows($consultar_detalle);
									
									for ($c=1; $c <= $detalle ; $c++){
									
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
										$nro_operacion = $respuesta_detalle['nro_operacion'];
										
										
										//CONSULTA SI EXISTE REGISTRO DETALLE CON LA SERIE Y NUMERO DEL COMPROBANTE
										$consultar_regdet = mysqli_query($conection, "SELECT 
										if(cdx.texto1='USD',SUM(gppdc.pagado),(gppd.importe_pago)) as importePago
										FROM gp_pagos_detalle_comprobante gppdc
										INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
										INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
										WHERE gppdc.serie='$serie' AND gppdc.numero='$numero' AND gppd.nro_operacion='$nro_operacion'");
										$respuesta_regdet = mysqli_num_rows($consultar_regdet);
										
										if($respuesta_regdet>0){
    										
    										 $row = mysqli_fetch_assoc($consultar_regdet);
										    $total_pagado = $row['importePago'];
    										
    										//consultar si ya se registro el detalle con la serie y numero
    										$consultar_detalles = mysqli_query($conection, "SELECT idingresos_detalle FROM ingresos_detalle WHERE Serie='$serie' AND Numero='$numero'");
    										$respuesta_detalles = mysqli_num_rows($consultar_detalles);
    										
    										if($respuesta_detalles<=0){
    										
        										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
        										VALUES ('$cod_cabecera','$sede','$iddetalle','$c', '$tipo_comprobante','$serie','$numero','$total_pagado','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','',NULL,'$debe_haber')");
    										
    										}
										}
										
										$VARIABLE = $detalle;
									}
									
								}
							
							    
							}
						//$data = $respuesta_pago;			
						
					}
					
                    
                    
                    //============================================
                    


                
                    
                //============================================VENTAS
                
                    $serie_filtro = $desc_serie;
                    $numero_filtro = $desc_correlativo;
                    
                    /****** CONSULTA VENTA ******/
                    
                            $consultar_iddet = mysqli_query($conection, "SELECT idpago_detalle as id FROM fac_comprobante_det");
                    
                            $consultar_iddet = mysqli_query($conection, "SELECT max(idpago_detalle) as id FROM fac_comprobante_det WHERE NUM_SERIE_CPE='$serie_filtro' AND NUM_CORRE_CPE='$numero_filtro'");
                            $respuesta_iddet = mysqli_fetch_assoc($consultar_iddet);
                            
                            $idpago_detalle=$respuesta_iddet['id'];
                            
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
                    
							$consultar_pagoVC = mysqli_query($conection,"SELECT
																'1' AS Identificador,
																fcc.NOM_RZN_SOC_RECP AS razon_social,
																CONCAT(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',1),' ',SUBSTRING_INDEX(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',3),' ',-1)) as glosaa,
																fcc.NUM_NIF_RECP AS Ruc_Dni,
																date_format(fcc.FEC_EMIS, '%Y-%m-%d') AS Fecha,
																date_format(fcc.FEC_VENCIMIENTO, '%Y-%m-%d') AS FechaVencimiento,
																fcc.MNT_TOT_DESCUENTO AS Descuento,
																fcc.MNT_TOT AS TotalImporte,
																fcc.MNT_TOT_OTR_CGO AS Servicio,
																fcc.ID_SEDE AS Sede,
																fcc.NUM_SERIE_CPE AS Serie,
																fcc.NUM_CORRE_CPE AS Numero,
																fcc.COD_TIP_CPE AS tipoCsun,
																fcc.MNT_TOT_TRIB_IGV AS IGV,
																fcc.COD_MND AS Moneda,
																fcc.TIP_CAMBIO AS TipoCambio,
																'' AS Accion,
																'' AS TipoR,
																'' AS SerieR,
																'' AS NumeroR,
																'' AS FechaR,
																'' AS Propina, 
																'VENTAS INTERFACE LAGUNA' AS Glosa
																FROM fac_comprobante_cab fcc
																WHERE fcc.NUM_SERIE_CPE='$serie_filtro' AND fcc.NUM_CORRE_CPE='$numero_filtro'
																order by fcc.FEC_EMIS");     
										
                            $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                            $idpagoVC = $respuesta_pago2['idpago'];
                            $iddetalleVC = $respuesta_pago2['Identificador'];
                            $rsVC=$respuesta_pago2['razon_social'];
                            $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                            $fecha_pVC = $respuesta_pago2['Fecha']; 
                            $fecha_VencimientoVC = $respuesta_pago2['FechaVencimiento']; 
                            $desc_pVC = $respuesta_pago2['Descuento']; 							
                            $importeTVC = $respuesta_pago2['TotalImporte'];
                            $servcVC = $respuesta_pago2['Servicio'];
                            $sedeVC = $respuesta_pago2['Sede'];
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
                            $nombre_glosa = $respuesta_pago2['glosaa'];
                            $glosaVC = $nombre_glosa.' - '.$glosa;
							
							//id para venta cabecera
                            $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                            $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
							$contadorVC = $consultaVC['contador'];
							$contadorVC = $contadorVC + 1;
							
							/****insertar datos en ventas cabecera****/
                            $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Fecha_Vencimiento, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                            VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC', '$fecha_VencimientoVC','$desc_pVC','$importeTVC','$servcVC','$sedeVC','$serieVC','$numVC','$tipo_codsunatVC','$igvVC', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC',NULL,'0','$glosaVC')");
							
							 /***********Insertar detalle ventas ********/
							if($insertar_pagoCabVentas){
								
								$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																		gppd.idpago_detalle as iddetalle,                                   						
																		fcc.ID_SEDE as Sede,                                   						
																		date_format(fcd.FEC_EMIS, '%Y-%m-%d %H:%i:%s') as Fecha,
																		fcc.MNT_TOT_DESCUENTO as Descuento,
																		fcd.MNT_PV_ITEM as ImportePago,
																		fcc.MNT_TOT_OTR_CGO as Servicio,   
																		fcd.NUM_SERIE_CPE as Serie,    
																		fcd.NUM_CORRE_CPE as Numero,  
																		fcc.COD_TIP_CPE  as TipoCS,
																		cdtx.texto1 CtaContable,    
																		fcd.MNT_IGV_ITEM  as IGV,
																		cdtx.texto2 as CentroCosto
																		FROM fac_comprobante_det fcd
																		INNER JOIN fac_comprobante_cab AS fcc ON fcc.NUM_CORRE_CPE=fcd.NUM_CORRE_CPE AND fcc.NUM_SERIE_CPE=fcd.NUM_SERIE_CPE
																		INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=fcd.idpago_detalle
																		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
																		INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
																		INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																		WHERE fcc.NUM_CORRE_CPE='$numero_filtro' AND fcc.NUM_SERIE_CPE='$serie_filtro'
																		GROUP BY fcd.idcomprobante_det
																		ORDER BY fcd.FEC_EMIS");
																
                                    $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
									
                                    for ($j=1; $j <= $detalleVC ; $j++) {
									
                                        $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                        $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                        $sedeDVC= $respuesta_detalleVC['Sede'];
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
												
										$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')");
                                        
                                        
                                    }										
							}
							
				//============================================ FIN VENTAS
            }


            $cdr_sunat = str_replace(array(",","'"), ' ', $cdr_sunat);
            $sunat_description = str_replace(array(","), ' ', $sunat_description);
            $sunat_description = str_replace(array("'"), ' ', $sunat_description);
            $sunat_description = ltrim($sunat_description, '"');

            //GRABAR TABLA COMPROBANTE IMPRESION
            $insertar_comprobante = mysqli_query($conection, "INSERT INTO fac_comprobante_impr(cadena, cdr_sunat, codigo_hash, correlativo_cpe, errors, estado_documento, pdf_bytes, serie_cpe, sunat_descripcion, sunat_note, sunat_responsecode, ticket_sunat, tipo_cpe, url_valor, xml_enviado, control_usuario, cliente, idlote, numero, serie, fecha_emision, tipo_comprobante_sunat) VALUES ('$cadena_para_codigo_qr','$cdr_sunat','$codigo_hash','$correlativo_cpe','$errors','$estado_documento','$pdf_bytes','$serie_cpe','$sunat_description','$sunat_note','$sunat_responsecode','$ticket_sunat','$tipo_cpe','$url', '$xml_enviado','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$desc_correlativo','$desc_serie','$txtFechaEmision','01')");

            $query_ins = "INSERT INTO fac_comprobante_impr(cadena, cdr_sunat, codigo_hash, correlativo_cpe, errors, estado_documento, pdf_bytes, serie_cpe, sunat_descripcion, sunat_note, sunat_responsecode, ticket_sunat, tipo_cpe, url_valor, xml_enviado, control_usuario, cliente, idlote, numero, serie, fecha_emision, tipo_comprobante_sunat) VALUES ('$cadena_para_codigo_qr','$cdr_sunat','$codigo_hash','$correlativo_cpe','$errors','$estado_documento','$pdf_bytes','$serie_cpe','$sunat_description','$sunat_note','$sunat_responsecode','$ticket_sunat','$tipo_cpe','$url', '$xml_enviado','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$desc_correlativo','$desc_serie','$txtFechaEmision','01')";

            if($insertar_comprobante){
                $consulta_correlativo = mysqli_query($conection, "SELECT
                correlativo as correlativo
                FROM fac_correlativo
                WHERE tipo_documento='FAC' AND estado='1'");
                $respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);
                $correlativo = $respuesta_correlativo['correlativo'];
                $correlativo = ($correlativo + 1);

                if($consulta_correlativo){
                    //ACTUALIZAR EL CORRELATIVO
                    $actualiza_correlativo = mysqli_query($conection, "UPDATE fac_correlativo
                    SET correlativo='$correlativo', user_registro='$idusuario'
                    WHERE tipo_documento='FAC' AND estado='1'");

                    $data['status'] = 'ok';
                    $data['data'] = 'El comprobante fue emitido con exito.';
                    $data['serie'] = $desc_serie;
                    $data['numero'] = $desc_correlativo;
                    $data['fecha_emision'] = $txtFechaEmision;

                    $data['cliente'] = $txtFiltroCliente;
                    $data['propiedad'] = $txtFiltroPropiedad;
                    $data['tipodoc'] = '01';
                }   
            }                     
       }
                
    }
    $data['consulta'] = $query_ins;
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnBuscarDocumentoFac'])) {

    $txtRucFac = $_POST['txtRucFac'];  
    
    $nombre="";
    $direccion="";
    $val_sunat="";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero='.$txtRucFac,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: http://apis.net.pe/api-ruc',
            'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
        ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        //echo $response;        
        $datos = json_decode($response, true);        

        $operacion = 1;
        if(empty($datos["error"])){  
            $val_sunat = "ok";
            $nombre = $datos["nombre"];
            $direccion = $datos["direccion"];            
        }else{
            $val_sunat = "bad";
            $nombre="";
            $direccion="";
            $error_api="(Error: ".$datos["error"].")";  
        }

     //CONSULTAR DATOS EN HISTORICO
     $consultar_datos = mysqli_query($conection, "SELECT direccion as direc FROM fac_clientes WHERE documento='$txtRucFac'");
     $conteo_consulta = mysqli_num_rows($consultar_datos);
     $val_direccion = "";
     if($conteo_consulta>0){
         $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
         $val_direccion = $respuesta_datos['direc'];
     }
 
     if((empty($direccion) || ($direccion=="-")) && !empty($val_direccion)){
         $direccion = $val_direccion;
     }    
    
    if ($val_sunat=="ok") {
        $data['status'] = 'ok';
        $data['cliente'] = $nombre;
        $data['direccion'] = $direccion;
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'No se encontraron resultados para el nro documento ingresado. '.$error_api;
        $data['cliente'] = $nombre;
        $data['direccion'] = $direccion;
    }  
     
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnCargarPagosNotaCredito'])){

    
    $idRegistro = isset($_POST['idRegistro']) ? $_POST['idRegistro'] : Null;
    $idRegistror = trim($idRegistro);

    //CONSULTAR DATOS
    $consultar_datos = mysqli_query($conection, "SELECT 
    NUM_SERIE_CPE as serie,
    NUM_CORRE_CPE as numero,
    FEC_EMIS as fecha_emision
    FROM fac_comprobante_cab 
    WHERE idcomprobante_cab='$idRegistror'");
    $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
    $serie = $respuesta_datos['serie'];
    $numero  = $respuesta_datos['numero'];
    $fec_emision = $respuesta_datos['fecha_emision'];
   
    $query = mysqli_query($conection,"SELECT 
            facdet.CANT_UNID_ITEM as cantidad,
            facdet.TXT_DESC_ITEM as descripcion,
            format(facdet.VAL_UNIT_ITEM,2) as valor_unitario,
            format(facdet.MNT_PV_ITEM,2)as valor_venta
            FROM fac_comprobante_det facdet
            WHERE FEC_EMIS='$fec_emision' AND NUM_SERIE_CPE='$serie' AND NUM_CORRE_CPE='$numero'"); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'cantidad' => $row['cantidad'],
                'descripcion' => $row['descripcion'],
                'valor_unitario' => $row['valor_unitario'],
                'valor_venta' => $row['valor_venta'],
                'unidad' => 'UNIDAD'
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

if (isset($_POST['btnBuscarDatosComprobanteNC'])) {

    $idRegistro = $_POST['idRegistro'];

    //CONSULTAR DATOS
    $consultar_datos = mysqli_query($conection, "SELECT 
    NUM_SERIE_CPE as serie,
    NUM_CORRE_CPE as numero,
    FEC_EMIS as fecha_emision,
    concat(NUM_SERIE_CPE,' - ',NUM_CORRE_CPE) as dato_ser_num,
    if(COD_TIP_CPE='03','BOLETA','FACTURA') as denominacion,
    NUM_NIF_RECP as documento,
    NOM_RZN_SOC_RECP as dato_cliente
    FROM fac_comprobante_cab 
    WHERE idcomprobante_cab='$idRegistro'");

    $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
    $serie = $respuesta_datos['serie'];
    $numero  = $respuesta_datos['numero'];
    $fec_emision = $respuesta_datos['fecha_emision'];
    $dato_ser_num = $respuesta_datos['dato_ser_num'];
    $denominacion = $respuesta_datos['denominacion'];
    $documento = $respuesta_datos['documento'];
    $dato_cliente = $respuesta_datos['dato_cliente'];

    $query = mysqli_query($conection, "SELECT 
    format(MNT_TOT_GRAVADO,2) as op_gravada,
    format(MNT_TOT_TRIB_IGV,2) as igv,
    format(MNT_TOT_INAFECTO,2) as inafecto,
    format(MNT_TOT,2) as importe_total
    FROM fac_comprobante_cab
    WHERE FEC_EMIS='$fec_emision' AND NUM_SERIE_CPE='$serie' AND NUM_CORRE_CPE='$numero'");

    
    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';        
        $data['data'] = $resultado;
        $data['fec_emision'] = $fec_emision;
        $data['dato_ser_num'] = $dato_ser_num;
        $data['denominacion'] = $denominacion;
        $data['documento'] = $documento;
        $data['dato_cliente'] = $dato_cliente;
        
        $data['serie'] = $serie;
        $data['numero'] = $numero;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnEmitirNotaCredito'])) {

    $txtUsuario = $_POST['txtUsuario'];  
    $txtFechaEmision = $_POST['txtFechaEmision'];  
    $txtFechaVencimiento = $_POST['txtFechaVencimiento'];
    $cbxTipoMoneda = $_POST['cbxTipoMoneda'];     

    $cbxTipoDocumento = $_POST['cbxTipoDocumento'];  
    $txtNroDocumento = $_POST['txtNroDocumento'];  
    $txtdatos = $_POST['txtdatos'];  

    $txtSerieControlNC = $_POST['txtSerieControlNC'];
    $txtNumeroControlNC = $_POST['txtNumeroControlNC'];
    $txtFechaModificaCom = $_POST['txtFechaModificaCom'];
    $txtSustentoNC = $_POST['txtSustentoNC'];
    
    $txtDenominacion = $_POST['txtDenominacion'];
    
    $txtSerieControlDNC = $_POST['txtSerieControlDNC'];
    $txtNumeroControlDNC = $_POST['txtNumeroControlDNC'];
    
    $cbxTipoNotaCredito = $_POST['cbxTipoNotaCredito'];
    
    if($txtDenominacion == "BOLETA"){
        $denominacion = "NCB";
    }else{
        $denominacion = "NCF";
    }

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    //DATOS EMPRESA
    $empresa = mysqli_query($conection, "SELECT 
        token_facturacion as token,
        ruc as ruc,
        razon_social as razon_social,
        nombre_comercial as nombre_comercial,
        ubigeo_inei as ubigeo,
        direccion as direccion,
        url_facturacion as url
        FROM datos_empresa
        WHERE ESTADO='1'");
    $resp_empresa = mysqli_fetch_assoc($empresa);

    $token = $resp_empresa['token'];
    $ruc = $resp_empresa['ruc'];
    $razon_social = $resp_empresa['razon_social'];
    $nombre_comercial = $resp_empresa['nombre_comercial'];
    $ubigeo = $resp_empresa['ubigeo'];
    $direccion = $resp_empresa['direccion'];
    $URL = $resp_empresa['url'];

    //DATOS CLIENTE
    $contar_td = strlen($txtNroDocumento); 
    if($contar_td==8){
        $tdoc = "1";
    }else{
        if($contar_td==11){
            $tdoc = "6";
        }else{
            $tdoc = "4";
        }
    }
    $tipo_documento = $tdoc;
    $documento = $txtNroDocumento;
    $datos_cliente = $txtdatos;
    $nombre_via = '';
    
    
    $correo = "admn.gpro@gmail.com";

    //SERIE Y NUMERO
   $anio = date('Y');
    $consulta_sn = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='$denominacion' AND anio='$anio'");
    $resp_sn = mysqli_fetch_assoc($consulta_sn);
    $numero = $resp_sn['num'];
    $serie = $resp_sn['serie'];
    $correlativo = $resp_sn['correlativo'];

    $desc_serie = "";
    if ($numero > 0 && $numero < 10) {
        $desc_serie = $serie . "0" . $numero;
    } else{
        if ($numero >= 10 && $numero < 100) {
            $desc_serie = $serie.$numero;
        }
    }

    $desc_correlativo = "";
    if ($correlativo > 0 && $correlativo < 10) {
        $desc_correlativo = "0000000" . $correlativo;
    } else {
        if ($correlativo >= 10 && $correlativo < 100) {
            $desc_correlativo = "000000" . $correlativo;
        } else {
            if ($correlativo >= 100 && $correlativo < 1000) {
                $desc_correlativo = "00000" . $correlativo;
            } else {
                if ($correlativo >= 1000 && $correlativo < 10000) {
                    $desc_correlativo = "0000" . $correlativo;
                } else {
                    if ($correlativo >= 10000 && $correlativo < 100000) {
                        $desc_correlativo = "000" . $correlativo;
                    } else {
                        if ($correlativo >= 100000 && $correlativo < 1000000) {
                            $desc_correlativo = "00" . $correlativo;
                        } else {
                            if ($correlativo >= 1000000 && $correlativo < 10000000) {
                                $desc_correlativo = "0" . $correlativo;
                            } else {
                                $desc_correlativo = $correlativo;
                            }
                        }
                    }
                }
            }
        }
    }

    //totales
    $consulta_totales = mysqli_query($conection, "SELECT 
    SUM(VAL_UNIT_ITEM) as op_gravada,
    SUM(MNT_IGV_ITEM) as igv,
    SUM(MNT_PV_ITEM) as total
    FROM fac_comprobante_det
    WHERE FEC_EMIS='$txtFechaModificaCom' AND NUM_SERIE_CPE='$txtSerieControlDNC' AND NUM_CORRE_CPE='$txtNumeroControlDNC'");
    $resp_totales = mysqli_fetch_assoc($consulta_totales);

    $op_gravada = $resp_totales['op_gravada'];
    $igv = $resp_totales['igv'];
    $total = $resp_totales['total'];

    $buscar = mysqli_query($conection, "SELECT
    idcomprobante_det as codigo,
    round(VAL_UNIT_ITEM,2) as unitario,
    MNT_IGV_ITEM as igv,
    MNT_PV_ITEM as total,
    TXT_DESC_ITEM as descripcion,
    CANT_UNID_ITEM as cantidad,
    COD_TIP_AFECT_IGV_ITEM as inafecto
    FROM fac_comprobante_det
    WHERE FEC_EMIS='$txtFechaModificaCom' AND NUM_SERIE_CPE='$txtSerieControlDNC' AND NUM_CORRE_CPE='$txtNumeroControlDNC'");
    
    while($row = $buscar->fetch_assoc()) {
        
        
        array_push($dataList,
            '{        
            "COD_ITEM": "'.$row['codigo'].'",
            "COD_UNID_ITEM": "ARE",
            "CANT_UNID_ITEM": "'.$row['cantidad'].'",
            "VAL_UNIT_ITEM": "'.$row['unitario'].'",      
            "PRC_VTA_UNIT_ITEM": "'.$row['total'].'",
            "VAL_VTA_ITEM": "'.$row['unitario'].'",
            "MNT_BRUTO": "'.$row['unitario'].'",
            "MNT_PV_ITEM": "'.$row['total'].'",
            "COD_TIP_PRC_VTA": "01",
            "COD_TIP_AFECT_IGV_ITEM":"'.$row['inafecto'].'",
            "COD_TRIB_IGV_ITEM": "1000",
            "POR_IGV_ITEM": "18",
            "MNT_IGV_ITEM": "'.$row['igv'].'",      
            "TXT_DESC_ITEM": "'.$row['descripcion'].'",                  
            "DET_VAL_ADIC01": "PROYECTO LAGUNA BEACH",
            "DET_VAL_ADIC02": "",
            "DET_VAL_ADIC03": "",
            "DET_VAL_ADIC04": "",
            "MNT_DSCTO_ITEM": "0.00",
            "MNT_RECGO_ITEM": "0.00"
            }'
        );
    }

    $array = implode(",",$dataList);
    
    //CONSULTAR CODIGO COMPROBANTE QUE MODIFICA
    $consulta_tipocom = mysqli_query($conection, "SELECT COD_TIP_CPE as codigo, COD_TIP_NIF_RECP as tipodoc, MNT_TOT_INAFECTO as totalInafecto, MNT_TOT_GRAVADO as totalGravado FROM fac_comprobante_cab WHERE NUM_SERIE_CPE='$txtSerieControlDNC' AND NUM_CORRE_CPE='$txtNumeroControlDNC' AND ID_SEDE='00001' AND FEC_EMIS='$txtFechaModificaCom'");
    $respuesta_tipocom = mysqli_fetch_assoc($consulta_tipocom);
    $codigo_tipocom = $respuesta_tipocom['codigo'];
    $cbxTipoDocumento = $respuesta_tipocom['tipodoc'];
    $totalInafecto = $respuesta_tipocom['totalInafecto'];
    $totalGravado = $respuesta_tipocom['totalGravado'];
    
    $VARIABLE = "SELECT COD_TIP_CPE as codigo, COD_TIP_NIF_RECP as tipodoc, MNT_TOT_INAFECTO as totalInafecto FROM fac_comprobante_cab WHERE NUM_SERIE_CPE='$txtSerieControlDNC' AND NUM_CORRE_CPE='$txtNumeroControlDNC' AND ID_SEDE='00001' AND FEC_EMIS='$txtFechaModificaCom'";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$URL.'',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
         "TOKEN":"'.$token.'",
        "COD_TIP_NIF_EMIS": "6",
        "NUM_NIF_EMIS": "'.$ruc.'",
        "NOM_RZN_SOC_EMIS": "'.$razon_social.'",
        "NOM_COMER_EMIS": "'.$nombre_comercial.'",
        "COD_UBI_EMIS": "'.$ubigeo.'",
        "TXT_DMCL_FISC_EMIS": "'.$direccion.'",
        "COD_TIP_NIF_RECP": "'.$tipo_documento.'",
        "NUM_NIF_RECP": "'.$documento.'",
        "NOM_RZN_SOC_RECP": "'.$txtdatos.'",
        "TXT_DMCL_FISC_RECEP": "",
        "FEC_EMIS": "'.$txtFechaEmision.'",
        "FEC_VENCIMIENTO": "'.$txtFechaVencimiento.'",
        "COD_TIP_CPE": "07",
        "NUM_SERIE_CPE": "'.$desc_serie.'",
        "NUM_CORRE_CPE": "'.$desc_correlativo.'",
        "COD_MND": "'.$cbxTipoMoneda.'",
        "MailEnvio": "'.$correo.'",
        "COD_PRCD_CARGA": "001",
        "MNT_TOT_GRAVADO": "'.$totalGravado.'",     
        "MNT_TOT_TRIB_IGV": "'.$igv.'", 
        "MNT_TOT": "'.$total.'",
        "MNT_TOT_INAFECTO": "'.$totalInafecto.'",
        "COD_PTO_VENTA": "jmifact",
        "ENVIAR_A_SUNAT": "true",
        "RETORNA_XML_ENVIO": "true",
        "RETORNA_XML_CDR": "false",
        "RETORNA_PDF": "false",
          "COD_FORM_IMPR":"001",
          "TXT_VERS_UBL":"2.1",
          "TXT_VERS_ESTRUCT_UBL":"2.0",
          "COD_ANEXO_EMIS":"0000",
          "COD_TIP_OPE_SUNAT": "0101",
          "COD_TIP_NC": "'.$cbxTipoNotaCredito.'",
          "TXT_DESC_MTVO": "'.$txtSustentoNC.'",
        "items": [
            '.$array.'
        ],
          "docs_referenciado": [
              {
                    "COD_TIP_DOC_REF": "'.$codigo_tipocom.'",
                    "NUM_SERIE_CPE_REF": "'.$txtSerieControlDNC.'",
                    "NUM_CORRE_CPE_REF":"'.$txtNumeroControlDNC.'",
                    "FEC_DOC_REF":"'.$txtFechaModificaCom.'"
              }
        ]
      }',
        CURLOPT_HTTPHEADER => array(
          'postman-token: b4938777-800c-1fb1-b127-aefda436e223',
          'cache-control: no-cache',
          'content-type: application/json'
        ),
      ));
      

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    //echo $response;

    $datos = json_decode($response, true);

    $cadena_para_codigo_qr = $datos["cadena_para_codigo_qr"];
    $cdr_sunat = $datos["cdr_sunat"];
    $codigo_hash = $datos["codigo_hash"];
    $correlativo_cpe = $datos["correlativo_cpe"];
    $errors = $datos["errors"];
    $estado_documento = $datos["estado_documento"];
    $pdf_bytes = $datos["pdf_bytes"];
    $serie_cpe = $datos["serie_cpe"];
    $sunat_description = $datos["sunat_description"];
    $sunat_note = $datos["sunat_note"];
    $sunat_responsecode = $datos["sunat_responsecode"];
    $ticket_sunat = $datos["ticket_sunat"];
    $tipo_cpe = $datos["tipo_cpe"];
    $url = $datos["url"];
    $xml_enviado = $datos["xml_enviado"];
   
    if(!empty($errors)){ 

        $data['status'] = 'bad';
        $data['data'] = 'No se pudo emitir la Nota de Credito. Detalle del error : '.$errors;

    } else {    
            

        //GRABAR TABLA COMPROBANTE CABECERA
       $insertar_cabecera = mysqli_query($conection, "INSERT INTO fac_comprobante_cab(COD_TIP_NIF_EMIS, NUM_NIF_EMIS, NOM_RZN_SOC_EMIS, NOM_COMER_EMIS, COD_UBI_EMIS, TXT_DMCL_FISC_EMIS, COD_TIP_NIF_RECP, NUM_NIF_RECP,NOM_RZN_SOC_RECP, TXT_DMCL_FISC_RECEP, FEC_EMIS, FEC_VENCIMIENTO, COD_TIP_CPE, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_MND, TIP_CAMBIO, MAIL_ENVIO, COD_PRCD_CARGA, MNT_TOT_GRAVADO, MNT_TOT_TRIB_IGV, MNT_TOT, COD_PTO_VENTA, ENVIAR_A_SUNAT, RETORNA_XML_ENVIO, RETORNA_XML_CDR, RETORNA_PDF, COD_FORM_IMPR, TXT_VERS_UBL, TXT_VERS_ESTRUCT_UBL, COD_ANEXO_EMIS, COD_TIP_OPE_SUNAT, ID_SEDE, COD_TIP_DOC_REF, NUM_SERIE_CPE_REF, NUM_CORRE_CPE_REF, FEC_DOC_REF, TXT_DESC_MTVO, MNT_TOT_INAFECTO) VALUES ('6','$ruc','$razon_social','$nombre_comercial','$ubigeo','$direccion','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$txtDireccionCliente','$txtFechaEmision','$txtFechaVencimiento','07','$desc_serie','$desc_correlativo','$cbxTipoMoneda','1.000','$correo','001', '$op_gravada', '$igv', '$total', 'jmifact', 'true', 'true', 'true', 'true','001','2.1','2.0','0000','0101','00001','$codigo_tipocom','$txtSerieControlDNC','$txtNumeroControlDNC','$txtFechaModificaCom','$txtSustentoNC','$totalInafecto')");
        //$insertar_cabecera=0;
       if($insertar_cabecera){

            //GRABAR TABLA COMPROBANTE DETALLE
            $buscar_detalle = mysqli_query($conection, "SELECT
            idcomprobante_det as codigo,
            VAL_UNIT_ITEM as unitario,
            MNT_IGV_ITEM as igv,
            MNT_PV_ITEM as total,
            TXT_DESC_ITEM as descripcion,
            CANT_UNID_ITEM as cantidad,
            idpago_detalle as idpago
            FROM fac_comprobante_det
            WHERE FEC_EMIS='$txtFechaModificaCom' AND NUM_SERIE_CPE='$txtSerieControlDNC' AND NUM_CORRE_CPE='$txtNumeroControlDNC'");

            if($buscar_detalle->num_rows > 0){
                while($row = $buscar_detalle->fetch_assoc()) {

                    $codigo = $row['codigo'];
                    $cantidad = $row['cantidad'];
                    $unitario = $row['unitario'];
                    $total = $row['total'];
                    $igv = $row['igv'];
                    $descripcion = $row['descripcion'];
                    $idpago = $row['idpago'];

                    $insertar_detalle = mysqli_query($conection,"INSERT INTO fac_comprobante_det(COD_ITEM, FEC_EMIS, FEC_VENCIMIENTO, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_UNID_ITEM, CANT_UNID_ITEM, VAL_UNIT_ITEM, PRC_VTA_UNIT_ITEM, VAL_VTA_ITEM, MNT_BRUTO, MNT_PV_ITEM, COD_TIP_PRC_VTA,COD_TIP_AFECT_IGV_ITEM,COD_TRIB_IGV_ITEM,POR_IGV_ITEM,MNT_IGV_ITEM, TXT_DESC_ITEM,DET_VAL_ADIC01,DET_VAL_ADIC02,DET_VAL_ADIC03,DET_VAL_ADIC04,idpago_detalle) VALUES ('$codigo','$txtFechaEmision','$txtFechaVencimiento','$desc_serie','$desc_correlativo','ARE','$cantidad','$unitario','$total','$unitario','$unitario','$total','01','10','1000','18','$igv','$descripcion','PROYECTO LAGUNA BEACH','','','','$idpago')");

                    //INGRESAR DATOS DE COMPROBANTE EN TABLA PAGOS DETALLE
                   $actualiza_datos_pago = mysqli_query($conection, "INSERT INTO gp_pagos_detalle_comprobante(idpago_detalle, serie, numero, cliente_tipodoc, cliente_doc, cliente_datos, pagado, fecha_emision, fecha_vencimiento, comprobante_url, tipo_moneda, id_concepto, debe_haber, tipo_comprobante_sunat) VALUES ('$idpago','$desc_serie','$desc_correlativo','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$total','$txtFechaEmision','$txtFechaVencimiento','$url','$cbxTipoMoneda','04','D','07')");

                }
            }
            
            $var_idpago = $idpago;
            
            $consulta_agencia = mysqli_query($conection, "SELECT 
														if(gppc.moneda_pago = '15381', cd.texto2, cd.texto3) as CuentaContable
														FROM gp_pagos_detalle gppd
														INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
														INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.agencia_bancaria AND cd.codigo_tabla='_BANCOS'
														WHERE gppd.idpago_detalle='$var_idpago'");														
            		$respuesta_consulta_agencia = mysqli_fetch_assoc($consulta_agencia);
                    $respuesta_agencia = $respuesta_consulta_agencia['CuentaContable'];
			
                    $query2 = mysqli_query($conection, "UPDATE 
								gp_pagos_cabecera gppc 
								INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
								SET gppc.cuenta_contable='$respuesta_agencia'
								WHERE gppd.idpago_detalle='$var_idpago'");
                   
                    
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
        														'D' as DebHab
        														FROM gp_pagos_cabecera gppc
        														INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
        														INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
        														INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=gppd.idpago_detalle								
        														WHERE gppc.esta_borrado=0 AND gppdc.idpago_detalle='$var_idpago'
        														ORDER BY gppc.fecha_pago");
        						
						$result = mysqli_num_rows($consultar_pago);
						
					if ($result>0){

						/*inicio de numfilas_consultapago*/	
						
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
							
							if($tipo_cambio=='0'){
							    $consultar_mx = mysqli_query($conection, "SELECT max(idconfig_tipo_cambio) as max FROM configuracion_tipo_cambio");
                                $respuesta_mx = mysqli_fetch_assoc($consultar_mx);
                                $max = $respuesta_mx['max'];
							    
							    $consultar_tc = mysqli_query($conection, "SELECT valor FROM configuracion_tipo_cambio WHERE idconfig_tipo_cambio='$max'");
                                $respuesta_tc = mysqli_fetch_assoc($consultar_tc);
                                
                                $tipo_cambio = $respuesta_tc['valor'];
							}
							
							//COMPLEMENTAR GLOSA CON NOMBRE DE CLIENTE
							$consultar_nombre = mysqli_query($conection, "SELECT 
							concat(dc.apellido_paterno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as nombre
							FROM gp_pagos_detalle gppd
							INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
							INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
							INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
							WHERE gppd.idpago_detalle='$var_idpago'");
							$respuesta_nombre = mysqli_fetch_assoc($consultar_nombre);
							$nombre = $respuesta_nombre['nombre'];
							
							$glosa = $nombre.' - '.$glosa;
							
							if($debe_haber == "D"){	
            
            
                                $consulta_id = mysqli_query($conection,"SELECT if(MAX(Id_Cabecera) is null ,0,MAX(Id_Cabecera)) AS contador FROM egresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								//consultar si ya se registro pago
								$consultar_cabecera = mysqli_query($conection, "SELECT Id_Cabecera as id FROM egresos_cabecera WHERE identificador='$iddetalle'");
								$respuesta_cabecera = mysqli_num_rows($consultar_cabecera);
								
								if($respuesta_cabecera>0){
								    
								    $insertar_pagoCabDeb = mysqli_query($conection, "SELECT Id_Cabecera as id FROM egresos_cabecera WHERE identificador='$var_idpago'");
								    
								}else{
								
								    $insertar_pagoCabDeb = mysqli_query($conection,"INSERT INTO egresos_cabecera(Id_Cabecera, Sede, identificador, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, id_pago) 
							    	VALUES ('$contador','$sede','$var_idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$var_idpago')");
								}
								if($insertar_pagoCabDeb){
									
									$consultar_detalle = mysqli_query($conection, "SELECT
									gppd.idpago as id,
									gppd.idpago_detalle as id_detalle,
									gppdc.tipo_comprobante_sunat as TipoComp,
									gppdc.serie as Serie,
									gppdc.numero as Numero,
									gppdc.pagado as TotalImporte,
									if(gppdc.tipo_moneda='USD', cdx.texto2, cdx.texto3) as CuentaContable,
									gppdc.tipo_moneda as moneda,
									cdx.texto5 as CentroCosto,
									gppdc.cliente_doc as DniRuc,
									gppdc.cliente_datos as RazonSocial,
									date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									'D' as DebHab
									FROM gp_pagos_detalle_comprobante gppdc
									INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppc.id_venta
									INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
									INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppdc.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
									WHERE gppd.esta_borrado=0 AND gppdc.idpago_detalle='$var_idpago' AND gppdc.debe_haber='D'
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
										
										//CONSULTA SI EXISTE REGISTRO DETALLE CON LA SERIE Y NUMERO DEL COMPROBANTE
										$consultar_regdet = mysqli_query($conection, "SELECT idpago_comprobante FROM gp_pagos_detalle_comprobante WHERE serie='$serie' AND numero='$numero'");
										$respuesta_regdet = mysqli_num_rows($consultar_regdet);
										
										if($respuesta_regdet > 0){
    										//CONSULTA SI EXISTEN MAS REGISTROS CON LA MISMA SERIE Y NUMERO, PARA SUMAR LOS TOTALES.
    										$consultar_reg = mysqli_query($conection, "SELECT sum(pagado) as total FROM gp_pagos_detalle_comprobante WHERE serie='$serie' AND numero='$numero'");
    										$respuesta_reg = mysqli_Fetch_assoc($consultar_reg);
    										$total_detalle = $respuesta_reg['total'];
    										
    										//consultar si ya se registro el detalle con la serie y numero
    										$consultar_detalles = mysqli_query($conection, "SELECT idegresos_detalle FROM egresos_detalle WHERE Serie='$serie' AND Numero='$numero'");
    										$respuesta_detalles = mysqli_num_rows($consultar_detalles);
    										
    										if($respuesta_detalles<=0){
        										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO egresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
        										VALUES ('$contador','$sede','$iddetalle','$cont', '$tipo_comprobante','$serie','$numero','$total_detalle','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','$codigo_tipocom','$txtSerieControlDNC','$txtNumeroControlDNC','$txtFechaModificaCom','$debe_haber')");
    										}
										}							
										
										
									}	
					            }
							}
					}
					
					//============================================VENTAS
                
                    $serie_filtro = $desc_serie;
                    $numero_filtro = $desc_correlativo;
                    
                    /****** CONSULTA VENTA ******/
                    
                            $consultar_iddet = mysqli_query($conection, "SELECT idpago_detalle as id FROM fac_comprobante_det");
                    
                            $consultar_iddet = mysqli_query($conection, "SELECT max(idpago_detalle) as id FROM fac_comprobante_det WHERE NUM_SERIE_CPE='$serie_filtro' AND NUM_CORRE_CPE='$numero_filtro'");
                            $respuesta_iddet = mysqli_fetch_assoc($consultar_iddet);
                            
                            $idpago_detalle=$respuesta_iddet['id'];
                            
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
                    
							$consultar_pagoVC = mysqli_query($conection,"SELECT
																'1' AS Identificador,
																fcc.NOM_RZN_SOC_RECP AS razon_social,
																CONCAT(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',1),' ',SUBSTRING_INDEX(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',3),' ',-1)) as glosaa,
																fcc.NUM_NIF_RECP AS Ruc_Dni,
																date_format(fcc.FEC_EMIS, '%Y-%m-%d') AS Fecha,
																date_format(fcc.FEC_VENCIMIENTO, '%Y-%m-%d') AS FechaVencimiento,
																fcc.MNT_TOT_DESCUENTO AS Descuento,
																fcc.MNT_TOT AS TotalImporte,
																fcc.MNT_TOT_OTR_CGO AS Servicio,
																fcc.ID_SEDE AS Sede,
																fcc.NUM_SERIE_CPE AS Serie,
																fcc.NUM_CORRE_CPE AS Numero,
																fcc.COD_TIP_CPE AS tipoCsun,
																fcc.MNT_TOT_TRIB_IGV AS IGV,
																fcc.COD_MND AS Moneda,
																fcc.TIP_CAMBIO AS TipoCambio,
																'' AS Accion,
																fcc.COD_TIP_DOC_REF AS TipoR,
																fcc.NUM_SERIE_CPE_REF AS SerieR,
																fcc.NUM_CORRE_CPE_REF AS NumeroR,
																fcc.FEC_DOC_REF AS FechaR,
																'' AS Propina, 
																'VENTAS INTERFACE LAGUNA' AS Glosa
																FROM fac_comprobante_cab fcc
																WHERE fcc.NUM_SERIE_CPE='$serie_filtro' AND fcc.NUM_CORRE_CPE='$numero_filtro'
																order by fcc.FEC_EMIS");     
										
                            $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                            $idpagoVC = $respuesta_pago2['idpago'];
                            $iddetalleVC = $respuesta_pago2['Identificador'];
                            $rsVC=$respuesta_pago2['razon_social'];
                            $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                            $fecha_pVC = $respuesta_pago2['Fecha'];
                            $fecha_venciC = $respuesta_pago2['FechaVencimiento'];
                            $desc_pVC = $respuesta_pago2['Descuento']; 							
                            $importeTVC = $respuesta_pago2['TotalImporte'];
                            $servcVC = $respuesta_pago2['Servicio'];
                            $sedeVC = $respuesta_pago2['Sede'];
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
                            $nombre_glosa = $respuesta_pago2['glosaa'];
                            $glosaVC = $nombre_glosa.' - '.$glosa;
							
							//id para venta cabecera
                            $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                            $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
							$contadorVC = $consultaVC['contador'];
							$contadorVC = $contadorVC + 1;
							
							/****insertar datos en ventas cabecera****/
                            $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Fecha_Vencimiento, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                            VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC','$fecha_venciC','$desc_pVC','$importeTVC','$servcVC','$sedeVC','$serieVC','$numVC','$tipo_codsunatVC','$igvVC', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC','$fecha_rVC','0','$glosaVC')");
							
							 /***********Insertar detalle ventas ********/
							if($insertar_pagoCabVentas){
								
								$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																		gppd.idpago_detalle as iddetalle,                                   						
																		fcc.ID_SEDE as Sede,                                   						
																		date_format(fcd.FEC_EMIS, '%Y-%m-%d %H:%i:%s') as Fecha,
																		fcc.MNT_TOT_DESCUENTO as Descuento,
																		fcd.MNT_PV_ITEM as ImportePago,
																		fcc.MNT_TOT_OTR_CGO as Servicio,   
																		fcd.NUM_SERIE_CPE as Serie,    
																		fcd.NUM_CORRE_CPE as Numero,  
																		fcc.COD_TIP_CPE  as TipoCS,
																		cdtx.texto1 CtaContable,    
																		fcd.MNT_IGV_ITEM  as IGV,
																		cdtx.texto2 as CentroCosto
																		FROM fac_comprobante_det fcd
																		INNER JOIN fac_comprobante_cab AS fcc ON fcc.NUM_CORRE_CPE=fcd.NUM_CORRE_CPE AND fcc.NUM_SERIE_CPE=fcd.NUM_SERIE_CPE
																		INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=fcd.idpago_detalle
																		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
																		INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
																		INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																		WHERE fcc.NUM_CORRE_CPE='$numero_filtro' AND fcc.NUM_SERIE_CPE='$serie_filtro'
																		GROUP BY fcd.idcomprobante_det
																		ORDER BY fcd.FEC_EMIS");
																
                                    $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
									
                                    for ($j=1; $j <= $detalleVC ; $j++) {
									
                                        $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                        $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                        $sedeDVC= $respuesta_detalleVC['Sede'];
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
												
										$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')");
                                        
                                        
                                    }										
							}
							
				//============================================ FIN VENTAS

            //ACTUALIZAR ESTAO DE PAGO DETALLE COMPROBANTE PARA COMPROBANTE SOBRE EL QUE SE APLICO LA NOTA DE CREDITO
            
            $actualizar_pago_comprobante = mysqli_query($conection, "UPDATE gp_pagos_detalle_comprobante SET esta_borrado='1' WHERE idpago_detalle='$var_idpago' AND debe_haber='H'");

            $cdr_sunat = "";
            $sunat_description = str_replace(array(","), ' ', $sunat_description);

            $consulta_dat = mysqli_query($conection, "SELECT cliente as cliente, idlote as idlote FROM fac_comprobante_impr WHERE serie='$txtSerieControlDNC' AND numero='$txtNumeroControlDNC' AND fecha_emision='$txtFechaModificaCom'");
            $respuesta_dat = mysqli_fetch_assoc($consulta_dat);
            $txtFiltroCliente = $respuesta_dat['cliente'];
            $txtFiltroPropiedad = $respuesta_dat['idlote'];

            //GRABAR TABLA COMPROBANTE IMPRESION
            $insertar_comprobante = mysqli_query($conection, "INSERT INTO fac_comprobante_impr(cadena, codigo_hash, correlativo_cpe, errors, estado_documento, serie_cpe, sunat_descripcion, sunat_note, sunat_responsecode, ticket_sunat, tipo_cpe, url_valor, xml_enviado, control_usuario, cliente, idlote, numero, serie, fecha_emision, tipo_comprobante_sunat) VALUES ('$cadena_para_codigo_qr','$codigo_hash','$correlativo_cpe','$errors','$estado_documento','$serie_cpe','$sunat_description','$sunat_note','$sunat_responsecode','$ticket_sunat','$tipo_cpe','$url', '$xml_enviado','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$desc_correlativo','$desc_serie','$txtFechaEmision','07')");

            
                $consulta_correlativo = mysqli_query($conection, "SELECT
                correlativo as correlativo
                FROM fac_correlativo
                WHERE tipo_documento='$denominacion' AND estado='1' AND anio='$anio'");
                $respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);
                $correlativo = $respuesta_correlativo['correlativo'];
                $correlativo = ($correlativo + 1);

                
                    //ACTUALIZAR EL CORRELATIVO
                    $actualiza_correlativo = mysqli_query($conection, "UPDATE fac_correlativo
                    SET correlativo='$correlativo', user_registro='$idusuario'
                    WHERE tipo_documento='$denominacion' AND estado='1' AND anio='$anio'");
                    
                    $data['status'] = 'ok';
                    $data['data'] = 'El comprobante fue emitido con exito.';
                    $data['serie'] = $desc_serie;
                    $data['numero'] = $desc_correlativo;
                    $data['fecha_emision'] = $txtFechaEmision;
                 
                              
       }
                
   }
    $data['Variable'] = $VARIABLE;
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/******************* INSERCION INGRESOS Y EGRESOS ********************/
if (isset($_POST['btnProcesarIngEg'])) {
 
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
						for ($i = 1; $i <= $result; $i++){		
						
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

										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
										VALUES ('$contador','$sede','$iddetalle','$cont', '$tipo_comprobante','$serie','$numero','$importe_pago','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','','$fecha','$debe_haber')");
																	
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
						}
					}
						header('Content-type: text/javascript');
						echo json_encode($data, JSON_PRETTY_PRINT);
}
/******************* INSERCION ********************/

/******************* INSERCION ********************/
if (isset($_POST['btnProcesarVentas'])) {
 
    $serie = $_POST['serie'];
    $numero = $_POST['numero'];
    
    	/****** CONSULTA VENTA ******/							
							$consultar_pagoVC = mysqli_query($conection,"SELECT
																fcc.NOM_RZN_SOC_RECP AS razon_social,
																fcc.NUM_NIF_RECP AS Ruc_Dni,
																date_format(fcc.FEC_EMIS, '%Y-%m-%d %H:%i:%s') AS Fecha,
																fcc.MNT_TOT_DESCUENTO AS Descuento,
																fcc.MNT_TOT AS TotalImporte,
																fcc.MNT_TOT_OTR_CGO AS Servicio,
																fcc.ID_SEDE AS Sede,
																fcc.NUM_SERIE_CPE AS Serie,
																fcc.NUM_CORRE_CPE AS Numero,
																fcc.COD_TIP_CPE AS tipoCsun,
																fcc.MNT_TOT_TRIB_IGV AS IGV,
																fcc.COD_MND AS Moneda,
																fcc.TIP_CAMBIO AS TipoCambio,
																'' AS Accion,
																'' AS TipoR,
																'' AS SerieR,
																'' AS NumeroR,
																'' AS FechaR,
																'' AS Propina, 
																'VENTAS INTERFACE LAGUNA' AS Glosa
																FROM fac_comprobante_cab fcc
																WHERE fcc.NUM_CORRE_CPE='$numero' AND fcc.NUM_SERIE_CPE='$serie'
																order by fcc.FEC_EMIS");     
							/*inicio de numfilas_consulta ventas*/				
                            $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                            $idpagoVC = $respuesta_pago2['idpago'];
                            //$iddetalleVC = $respuesta_pago2['Identificador'];
                            $rsVC=$respuesta_pago2['razon_social'];
                            $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                            $fecha_pVC = $respuesta_pago2['Fecha']; 
                            $desc_pVC = $respuesta_pago2['Descuento']; 							
                            $importeTVC = $respuesta_pago2['TotalImporte'];
                            $servcVC = $respuesta_pago2['Servicio'];
                            $sedeVC = $respuesta_pago2['Sede'];
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
                            $glosaVC = $respuesta_pago2['Glosa'];
							
							//id para venta cabecera
                            $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                            $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
							$contadorVC = $consultaVC['contador'];
							$contadorVC = $contadorVC + 1;
							
							/****insertar datos en ventas cabecera****/
                            $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                            VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC','desc_pVC','$importeTVC','servcVC','$sedeVC','$serieVC','$numVC','$tipo_codsunatVC','igvVC', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC','$fecha_rVC','0','$glosaVC')");
							
							 /***********Insertar detalle ventas ********/
							 
							if($insertar_pagoCabVentas){
								
								$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																		gppd.idpago_detalle as iddetalle,                                   						
																		fcc.ID_SEDE as Sede,                                   						
																		date_format(fcd.FEC_EMIS, '%Y-%m-%d %H:%i:%s') as Fecha,
																		fcc.MNT_TOT_DESCUENTO as Descuento,
																		fcd.MNT_PV_ITEM as ImportePago,
																		fcc.MNT_TOT_OTR_CGO as Servicio,   
																		fcd.NUM_SERIE_CPE as Serie,    
																		fcd.NUM_CORRE_CPE as Numero,  
																		fcc.COD_TIP_CPE  as TipoCS,
																		cdtx.texto1 CtaContable,    
																		fcd.MNT_IGV_ITEM  as IGV,
																		cdtx.texto2 as CentroCosto
																		FROM fac_comprobante_det fcd
																		INNER JOIN fac_comprobante_cab AS fcc ON fcc.NUM_CORRE_CPE=fcd.NUM_CORRE_CPE AND fcc.NUM_SERIE_CPE=fcd.NUM_SERIE_CPE
																		INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=fcd.idpago_detalle
																		INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
																		INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
																		INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																		WHERE fcc.NUM_CORRE_CPE='$numero' AND fcc.NUM_SERIE_CPE='$serie'
																		ORDER BY fcd.FEC_EMIS");
																
                                    $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
									
                                    for ($j=1; $j <= $detalleVC ; $j++) {
									
                                        $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                        $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                        $sedeDVC= $respuesta_detalleVC['Sede'];
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
												
										$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')");
                                        
                                        $consulta = "INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                        VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')";
                                        
                                        array_push($dataList, $consulta);
									
                                    }									
							}
							
							
	$data['contador'] = $detalleVC;						
	$data['consulta'] = $dataList;
		
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



















/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= OTROS CONCEPTOS A EMITIR ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/
/*================================================================= ========================= ==============================================================================*/



if (isset($_POST['btnConsultarVistaComprobanteOC'])) {

    $txtFiltroTipoComprobante = $_POST['txtFiltroTipoComprobante'];
    $txtFiltroPropiedad = '0';
    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    
     $query_reg = mysqli_query($conection,"SELECT
            gppd.idpago_detalle as id,
            gppd.fecha_pago as fecha_pago,
            gpcr.item_letra as letra,
            cdx.texto1 as tipo_moneda,
            format(gppd.importe_pago,2) as importe_pago,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.pagado,2) as pagado,
            cddx.nombre_corto as medio_pago,
            gppd.nro_operacion as nro_operacion,
            if(gppd.estado_facturacion='0','PENDIENTE','FACTURADO') as estado_fac,
            if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle)>0,(select format(sum(pagado),2) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle),'0.00') as facturado,
            format((gppd.pagado - (if((select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle)>0,(select sum(pagado) from gp_pagos_detalle_comprobante where idpago_detalle=gppd.idpago_detalle),'0.00'))),2) as saldo
            FROM gp_pagos_detalle gppd
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gppd.medio_pago AND cddx.codigo_tabla='_MEDIO_PAGO'
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppd.id_venta
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_cronograma AS gpcr ON gpcr.id_venta=gpv.id_venta AND gpcr.correlativo=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id = gpv.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
            WHERE gppd.esta_borrado=0 AND gppd.estado_cierre='1' AND gppd.estado='2'
            AND dc.documento='$txtFiltroCliente'
            AND gpl.idlote='$txtFiltroPropiedad'
            ORDER BY gppd.fecha_pago ASC"); 
    
    $respuesta = mysqli_num_rows($query_reg);
    
    $registros = 0;
    if($respuesta>0){
        $registros = 1;
    }else{
        $registros = 0;
    }
    
    
    $query = mysqli_query($conection, "SELECT 
        codigo_item as codigo
        FROM configuracion_detalle gppd
        WHERE codigo_sunat='$txtFiltroTipoComprobante' AND codigo_tabla='_TIPO_COMPROBANTE_SUNAT'");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['registro'] = $registros;
    } else {
        $data['status'] = 'bad';
        $data['registro'] = $registros;
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}   

if(isset($_POST['btnLimpiarTemporal'])){

    $txtUsuario = $_POST['txtUsuario'];

    //$txtUsuario = decrypt($txtUsuario, "123");    
    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];


    $query = mysqli_query($conection, "DELETE
            FROM temporal_facturador
            WHERE estado='1'
            AND idlote='0'
            AND iduser='$idusuario'");
    
    $query = mysqli_query($conection, "DELETE
            FROM temporal_facturador_totales
            WHERE idlote='0'
            AND iduser='$idusuario'");

    $data['data'] = 'ok';
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);      
    
}

if (isset($_POST['btnBuscarDocumentoExiste'])) {

    $txtNroDocumento = $_POST['txtNroDocumento'];    

    //CONSULTAR DATOS EN HISTORICO
    $consultar_datos = mysqli_query($conection, "SELECT concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
    concat(dc.nombre_via,' ',dc.nro_via) as direccion,
    concat(udi.nombre,' - ',upr.nombre,' - ',ure.nombre) as domicilio,
    cddx.codigo_sunat as tipo_documento,
    dc.email as correo
    FROM datos_cliente dc
    INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=dc.tipo_via AND cdx.codigo_tabla='_VIA'
    INNER JOIN ubigeo_distrito AS udi ON udi.codigo=dc.id_dom_distrito
    INNER JOIN ubigeo_provincia AS upr ON upr.codigo=dc.id_dom_provincia
    INNER JOIN ubigeo_region AS ure ON ure.codigo=dc.id_dom_departamento
    INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=dc.tipodocumento AND cddx.codigo_tabla='_TIPO_DOCUMENTO'
    WHERE dc.documento='$txtNroDocumento'");
    $conteo_consulta = mysqli_num_rows($consultar_datos);
    
    $nombre = "";
    $direccion = "";
    $domicilio  = "";
    $correo="";
    $tipo_documento  = "";

    if ($conteo_consulta > 0) {

        $row = mysqli_fetch_assoc($consultar_datos);
        $nombre = $row['cliente'];
        $direccion = $row['direccion'];
        $domicilio = $row['domicilio'];
        $tipo_documento = $row['tipo_documento'];
        $correo = $row['correo'];

        if(!empty($domicilio)){
           $domicilio = ', '.$domicilio;     
        }

        $data['status'] = 'ok';
        $data['cliente'] = $nombre;
        $data['direccion'] = $direccion.$domicilio;
        $data['tipo_documento'] = $tipo_documento;
        $data['correo'] = $correo;
    
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'No se encontraron resultados para el nro documento ingresado.';
        $data['cliente'] = $nombre;
        $data['direccion'] = $txtNroDocumento;
        $data['tipo_documento'] = $tipo_documento;
    }  

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnListarMotivosConceptos'])) {

    $idconcepto = $_POST['idconcepto'];
    
    $query = mysqli_query($conection, "SELECT 
        cdx.idconfig_detalle as valor,
        cdx.nombre_corto as texto
        FROM configuracion_detalle cdx 
        WHERE cdx.texto1='$idconcepto' AND cdx.codigo_tabla='_CONCEPTOS_VENTAS_MOTIVOS'");

   /* array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);*/

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['texto'],
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

if (isset($_POST['btnBuscarDocumentoOC'])) {

    $cbxTipoDocumento = $_POST['cbxTipoDocumento']; 
    $txtNroDocumento = $_POST['txtNroDocumento'];   

    $val_sunat = "";
    $val_reniec = "";
    $nombre="";
    $direccion=""; 
    $error_api="";
    $operacion = 0; // 1 : Sunat , 2 : Reniec

    if($cbxTipoDocumento=='6'){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero='.$txtNroDocumento,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Referer: http://apis.net.pe/api-ruc',
            'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
        ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        //echo $response;  
        $datos = json_decode($response, true);      

        $operacion = 1;
        if(empty($datos["error"])){  
            $val_sunat = "ok";
            $nombre = $datos["nombre"];
            $direccion = $datos["direccion"];            
        }else{
            $val_sunat = "bad";
            $nombre="";
            $direccion="";
            $error_api="(Error: ".$datos["error"].")";  
        }

    }else{

        if($cbxTipoDocumento=='1'){

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero='.$txtNroDocumento,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
            ),
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);
            //echo $response;    
            $datos = json_decode($response, true);     

            $operacion = 2;
            if(empty($datos["error"])){            
                $val_reniec = "ok";
                $nombre = $datos["nombre"];
                $direccion = $datos["direccion"];            
            }else{
                $val_reniec = "bad";
                $nombre="";
                $direccion="";
                $error_api="(Error: ".$datos["error"].")";    
            }
        }else{
            $operacion = 3;
        }
    }

    //CONSULTAR DATOS EN HISTORICO
    $consultar_datos = mysqli_query($conection, "SELECT direccion as direc FROM fac_clientes WHERE tipodocumento='$cbxTipoDocumento' AND documento='$txtNroDocumento'");
    $conteo_consulta = mysqli_num_rows($consultar_datos);
    $val_direccion = "";
    if($conteo_consulta>0){
        $respuesta_datos = mysqli_fetch_assoc($consultar_datos);
        $val_direccion = $respuesta_datos['direc'];
    }

    if((empty($direccion) || ($direccion=="-")) && !empty($val_direccion)){
        $direccion = $val_direccion;
    }

    if ($operacion == "1") {
        if($val_sunat == "ok"){
            $data['status'] = 'ok';
            $data['cliente'] = $nombre;
            $data['direccion'] = $direccion;
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'No se encontraron resultados para el nro documento ingresado. '.$error_api;
            $data['cliente'] = $nombre;
            $data['direccion'] = $direccion;
        }  
        
    } else {
        if($operacion == "2"){
            if($val_reniec == "ok"){
                $data['status'] = 'ok';
                $data['cliente'] = $nombre;
                $data['direccion'] = $direccion;
            }else{
                $data['status'] = 'bad';
                $data['data'] = 'No se encontraron resultados para el nro documento ingresado. '.$error_api;
                $data['cliente'] = $nombre;
                $data['direccion'] = $direccion;
             }  
        }else{
            if($operacion == "3"){
                
                $data['status'] = 'regular';
                $data['data'] = 'No se encontraron resultados para el nro documento ingresado. Sin embargo puede ingresar los datos del cliente de forma manual.';
                
            }
            
        }
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*============== BOLETA OTROS CONCEPTOS ======================*/

if (isset($_POST['btnAgregarPagoOC'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = '0';
    $txtUsuario = $_POST['txtUsuario'];
    $txtFechaEmision = $_POST['txtFechaEmision'];
    $txtSerieControlFac = $_POST['txtSerieControlFac'];
    $txtNumeroControlFac = $_POST['txtNumeroControlFac'];
    $TipoDoc = $_POST['txtFiltroTipoComprobante'];
    $txtdatosOC = $_POST['txtdatosOC'];
    $cadena = explode(" ", $txtdatosOC);
    $nom_cliente = $cadena[0].' '.$cadena[2];
    
    $txtCamCantidadOC = $_POST['txtCamCantidadOC'];
    $txtCamUnidadOC = $_POST['txtCamUnidadOC'];
    $txtCamDescripcionOC = $_POST['txtCamDescripcionOC'];
    $txtCamValorUnitOC = $_POST['txtCamValorUnitOC'];
    $txtCamDescOC = $_POST['txtCamDescOC'];
    
    $cbxInafectoOC = $_POST['cbxInafectoOC'];
    $txtfiltroConceptoVentaOC = $_POST['txtfiltroConceptoVentaOC'];
    
    //$txtUsuario = decrypt($txtUsuario,"123");
    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];
    
    $IdRegistro = $_POST['idpago'];

    $query_documento="";
    if(!empty($txtFiltroCliente)){
        $query_documento = "AND doc_cliente='$txtFiltroCliente'";
    }

    $pago = str_replace(',', '',$txtCamValorUnitOC);
    $dscto = str_replace(',', '',$txtCamDescOC);

        if($pago>0){

            $monto_emitir = $pago;
            $valor_dscto = 0;

            if($txtfiltroConceptoVentaOC=="01"){

                if($txtCamDescOC>0){
                    $valor_dscto = ($dscto / 2);
                }
                $precio_unitario = round(($pago / 2.18),2);
                $igv = round(($precio_unitario * 0.18),2);
                $precio_unitario_venta = round((($precio_unitario - $valor_dscto) + $igv),2);
                $precio_unitario_v = ($precio_unitario + $igv);
                $inafecto = round((($monto_emitir - $valor_dscto) - $precio_unitario_v),2);

                $txtCamDescripcionOC = $nom_cliente.' - '.$txtCamDescripcionOC;
                
                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtCamCantidadOC','UNIDAD','$txtCamDescripcionOC', '$precio_unitario','0.00','$igv','0.00','$precio_unitario_venta','1','0', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac')");
                
                $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie) VALUES
                ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtCamCantidadOC','UNIDAD','$txtCamDescripcionOC','$inafecto','0.00','0.00','0.00','$inafecto','0','1', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac')");

                
                //consulta si existen totales temporal
                $consultar_tot = mysqli_query($conection, "SELECT 
                idfacturador_total as id,
                op_gravada as op_gravada,
                op_inafecta as op_inafecta,
                igv as igv,
                importe_total as total
                FROM temporal_facturador_totales
                WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                $respuesta_tot = mysqli_num_rows($consultar_tot);
                $resp = 0;
                if($respuesta_tot>0){
                
                    //SUMAR DETALLES
                        //CONSULTAR TOTALES
                        $op_gravada= 0;
                        $igv =0;
                        $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                        $op_gravada = $respuesta_op_gravada['subtotal'];
                        $igv = $respuesta_op_gravada['igv'];
    
                        $op_inafecta = 0;
                        $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE inafecto='1' AND estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
                        $op_inafecta = $respuesta_op_inafecta['total'];
    
                        $total =0;
                        $consultar_totales = mysqli_query($conection, "SELECT  ROUND(if(SUM(importe_venta)>0,SUM(importe_venta),'0'),2) as total FROM temporal_facturador WHERE estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                        $total = $respuesta_totales['total'];
    
                        //ACTUALIZAR TOTALES
    
                        $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
                        op_gravada='$op_gravada',
                        op_inafecta='$op_inafecta',
                        igv='$igv',
                        importe_total='$total'
                        WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    
                        $resp = 1;
                    
                }else{
    
                    //INGRESAR NUEVO TOTAL
                        $op_gravada= 0;
                        $igv =0;
                        $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                        $op_gravada = $respuesta_op_gravada['subtotal'];
                        $igv = $respuesta_op_gravada['igv'];
    
                        $op_inafecta = 0;
                        $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE inafecto='1' AND estado='1' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
                        $op_inafecta = $respuesta_op_inafecta['total'];
    
                        $total =0;
                        $consultar_totales = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                        $total = $respuesta_totales['total'] + $op_inafecta;
    
                        //ACTUALIZAR TOTALES
                        $atotal = mysqli_query($conection, "INSERT INTO temporal_facturador_totales(op_gravada, op_inafecta, igv, importe_total, iduser, doc_cliente,idlote, fecha_emision, op, exonerada, isc, otros_cargos, otros_tributos, monto_redondeo) VALUES
                        ('$op_gravada','$op_inafecta','$igv','$total','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtFechaEmision','0.00','0.00','0.00','0.00','0.00','0.00')");    
                      
                        $resp = 1;
    
                }



            }else{
                if($cbxInafectoOC == 1){
                    
                    $dato_inafecto = "1";
                    $dato_igv = "0";
                    
                    if($txtCamDescOC>0){
                        $valor_dscto = $dscto;
                        $precio_unitario = round(($monto_emitir/$txtCamCantidadOC),2);
                        $precio_unitario_venta = round((($txtCamCantidadOC * ($monto_emitir/$txtCamCantidadOC)) - $dscto),2);
                        $igv = "0.00";
                        $valor_inafecto = round(($monto_emitir - $dscto),2);
                    }else{
                        $valor_dscto = 0;
                        $precio_unitario = round(($monto_emitir),2);
                        $precio_unitario_venta = round((($monto_emitir/$txtCamCantidadOC) * $txtCamCantidadOC),2);
                        $igv = "0.00";
                        $valor_inafecto = round($monto_emitir,2);
                    }
                
                    $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie) VALUES
                    ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtCamCantidadOC','$txtCamUnidadOC','$txtCamDescripcionOC', '$precio_unitario','$valor_inafecto','$igv','$valor_dscto','$precio_unitario_venta','$dato_igv','$dato_inafecto', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac')");

                }else{
                    if($txtCamDescOC>0){
                        $dato_inafecto = "0";
                        $dato_igv = "1";
                        $valor_dscto = $dscto;
                        $precio_unitario = round((($monto_emitir / $txtCamCantidadOC)/1.18),2);
                        $igv_uni = round(($precio_unitario * 0.18),2);
                        $igv = round((($precio_unitario * $txtCamCantidadOC) * 0.18),2);
                        $precio_unitario_venta = round(((($precio_unitario * $txtCamCantidadOC) + $igv) - $dscto),2);
                        $precio_unitario = $precio_unitario + $igv_uni;
                        $valor_inafecto = "0.00";
                    }else{
                        $dato_inafecto = "0";
                        $dato_igv = "1";
                        $valor_dscto = "0.00";
                        $precio_unitario = (($monto_emitir/$txtCamCantidadOC)/1.18);
                        $VAL_UNIT_ITEM = (($monto_emitir/$txtCamCantidadOC)/1.18);
                        $VAL_VTA_ITEM = ($VAL_UNIT_ITEM * $txtCamCantidadOC);
                        $igv_uni = ($precio_unitario * 0.18);
                        $igv = (($precio_unitario * $txtCamCantidadOC) * 0.18);
                        $precio_unitario_venta = (($precio_unitario * $txtCamCantidadOC) + $igv);
                        $precio_unitario = $precio_unitario + $igv_uni;
                        $valor_inafecto = "0.00";
                    }
                
                    $query = mysqli_query($conection, "INSERT INTO temporal_facturador(iduser, doc_cliente, idlote, cantidad, medida, descripcion, valor_unitario, valor_inafecto, valor_igv, descuento, importe_venta, igv, inafecto, idpago, fecha_emision, tipo_doc_sunat, numero, serie, VAL_UNIT_ITEM, VAL_VTA_ITEM) VALUES
                    ('$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtCamCantidadOC','$txtCamUnidadOC','$txtCamDescripcionOC', '$precio_unitario','$valor_inafecto','$igv','$valor_dscto','$precio_unitario_venta','$dato_igv','$dato_inafecto', '$IdRegistro','$txtFechaEmision','$TipoDoc','$txtNumeroControlFac','$txtSerieControlFac','$VAL_UNIT_ITEM','$VAL_VTA_ITEM')");

                    
                }
                
                
                //consulta si existen totales temporal
                $consultar_tot = mysqli_query($conection, "SELECT 
                idfacturador_total as id,
                op_gravada as op_gravada,
                op_inafecta as op_inafecta,
                igv as igv,
                importe_total as total
                FROM temporal_facturador_totales
                WHERE iduser='$idusuario' $query_documento AND idlote='0' AND fecha_emision='$txtFechaEmision'");
                $respuesta_tot = mysqli_num_rows($consultar_tot);
                $resp = 0;
                if($respuesta_tot>0){
                
                    //SUMAR DETALLES
                        //CONSULTAR TOTALES
                        $op_gravada= 0;
                        $igv =0;
                        $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_igv) as igv, SUM(importe_venta - valor_igv) as ope_gravada FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                        $op_gravada = $respuesta_op_gravada['ope_gravada'];
                        $igv = $respuesta_op_gravada['igv'];
    
                       $op_inafecta = 0;
                        $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total, cantidad as cantidad FROM temporal_facturador WHERE inafecto='1' AND estado='1' AND iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
                        $op_inafecta = $respuesta_op_inafecta['total'];
                        //$cantidadd = $respuesta_op_inafecta['cantidad'];
                       // $op_inafecta = $op_inafecta * $cantidadd;
    
                        $total =0;
                        $consultar_totales = mysqli_query($conection, "SELECT  ROUND(if(SUM(importe_venta)>0,SUM(importe_venta),'0'),2) as total FROM temporal_facturador WHERE estado='1' AND iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                        $total = $respuesta_totales['total'];
    
                        //ACTUALIZAR TOTALES
    
                        $atotal = mysqli_query($conection, "UPDATE temporal_facturador_totales SET
                        op_gravada='$op_gravada',
                        op_inafecta='$op_inafecta',
                        igv='$igv',
                        importe_total='$total'
                        WHERE iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    
                        $resp = 1;
                    
                }else{
    
                    //INGRESAR NUEVO TOTAL
                        $op_gravada= 0;
                        $igv =0;
                        $consultar_op_gravada = mysqli_query($conection, "SELECT  SUM(valor_unitario) as subtotal ,SUM(valor_igv) as igv, cantidad as cantidad FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_gravada = mysqli_fetch_assoc($consultar_op_gravada);
                        $op_gravada = $respuesta_op_gravada['subtotal'];
                        $cantidad = $respuesta_op_gravada['cantidad'];
                        $igv = $respuesta_op_gravada['igv'];
                        $op_gravada = (($op_gravada * $cantidad) - $igv);
    
                        $op_inafecta = 0;
                        $consultar_op_inafecta = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total, cantidad as cantidad FROM temporal_facturador WHERE inafecto='1' AND estado='1' AND iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_op_inafecta = mysqli_fetch_assoc($consultar_op_inafecta);
                        $op_inafecta = $respuesta_op_inafecta['total'];
                        //$cantidadd = $respuesta_op_inafecta['cantidad'];
                        //$op_inafecta = $op_inafecta * $cantidadd;
    
                        $total =0;
                        $consultar_totales = mysqli_query($conection, "SELECT  if(SUM(importe_venta)>0,SUM(importe_venta),'0') as total FROM temporal_facturador WHERE estado='1' AND inafecto='0' AND iduser='$idusuario' $query_documento AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
                        $respuesta_totales = mysqli_fetch_assoc($consultar_totales);
                        $total = $respuesta_totales['total'] + $op_inafecta;
    
                        //ACTUALIZAR TOTALES
                        $atotal = mysqli_query($conection, "INSERT INTO temporal_facturador_totales(op_gravada, op_inafecta, igv, importe_total, iduser, doc_cliente,idlote, fecha_emision, op, exonerada, isc, otros_cargos, otros_tributos, monto_redondeo) VALUES
                        ('$op_gravada','$op_inafecta','$igv','$total','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$txtFechaEmision','0.00','0.00','0.00','0.00','0.00','0.00')");    
                      
                        $resp = 1;
    
                }
                    
                
            }

            


            if ($resp == 1) {
                $data['status'] = 'ok';
                $data['data'] = "Se agrego el pago al comprobante.";
            } else {
                $data['status'] = 'bad';
                $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
            }
        }  else {
            $data['status'] = 'bad';
            $data['data'] = 'Ingresar un valor unitario mayor a cero.';
        }  

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnListarItemsBoletaOC'])){

    
    $txtFiltroCliente = isset($_POST['txtFiltroCliente']) ? $_POST['txtFiltroCliente'] : Null;
    $txtFiltroClienter = trim($txtFiltroCliente);

    $query_documento = "";
    if(!empty($txtFiltroClienter)){
        $query_documento = "AND doc_cliente='$txtFiltroClienter'";
    }

    $txtUsuario = $_POST['txtUsuario'];
    //$txtUsuario = decrypt($txtUsuario, "123");
    
    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    $query = mysqli_query($conection, "SELECT
            idfacturador as id,
            cantidad as cantidad,
            medida as medida,
            descripcion as descripcion,
            format(valor_unitario,2) as valor_unitario,
            format(descuento,2) as descuento,
            format(importe_venta,2) as importe_venta,
            igv as igv,
            inafecto as inafecto,
            valor_inafecto as valor_inafecto
            FROM temporal_facturador
            WHERE estado='1'
            $query_documento
            AND idlote='0'
            AND iduser='$idusuario'");

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            //Campos para llenar Tabla
            array_push($dataList, [
                'id' => $row['id'],
                'cantidad' => $row['cantidad'],
                'medida' => $row['medida'],
                'descripcion' => $row['descripcion'],
                'valor_unitario' => $row['valor_unitario'],
                'descuento' => $row['descuento'],
                'importe_venta' => $row['importe_venta'],
                'igv' => $row['igv'],
                'inafecto' => $row['inafecto'],
                'valor_inafecto' => $row['valor_inafecto']
            ]);
        }

        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {

        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
   
     
}

if (isset($_POST['btnCargarTotalesComprobanteOC'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtUsuario = $_POST['txtUsuario'];
    $txtFechaEmision = $_POST['txtFechaEmision'];

    $query_documento = "";
    if(!empty($txtFiltroCliente)){
        $query_documento = "AND doc_cliente='$txtFiltroCliente'";
    }

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    $query = mysqli_query($conection, "SELECT format(op_gravada,2) as op_gravada,
    format(op,2) as op,
    format(exonerada,2) as exonerada,
    format(op_inafecta,2) as op_inafecta,
    format(isc,2) as isc,
    format(igv,2) as igv,
    format(otros_cargos,2) as otros_cargos,
    format(otros_tributos,2) as otros_tributos,
    format(monto_redondeo,2) as monto_redondeo,
    format(importe_total,2) as importe_total
    FROM temporal_facturador_totales
    WHERE iduser='$idusuario' $query_documento  AND idlote='0' AND fecha_emision='$txtFechaEmision'");
    
    if ($query) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';        
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurri車 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnEmitirBoletaOC'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];    
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];    
    $txtFechaEmision = $_POST['txtFechaEmision'];  
    $txtFechaVencimiento = $_POST['txtFechaVencimiento'];
    $cbxTipoMoneda = $_POST['cbxTipoMoneda'];  

    $cbxTipoDocumento = $_POST['cbxTipoDocumento'];  
    $txtNroDocumento = $_POST['txtNroDocumento'];  
    $txtdatos = $_POST['txtdatos'];
    $txtDireccionCliente = $_POST['txtDireccionCliente'];
    $txtCorreoClienteOC = $_POST['txtCorreoClienteOC'];  

    $dato_nombre = explode(" ", $txtdatos);
    $dato_nombre = $dato_nombre[0].' '.$dato_nombre[2];

    $txtCamCantidadOC = $_POST['txtCamCantidadOC'];
    $txtCamDescripcionOC = $dato_nombre.' - '.$_POST['txtCamDescripcionOC'];
    $txtCamValorUnitOC = $_POST['txtCamValorUnitOC'];
    $txtCamDescOC = $_POST['txtCamDescOC'];

    $txtSerieControlBolOC = $_POST['txtSerieControlBolOC'];
    $txtNumeroControlBolOC = $_POST['txtNumeroControlBolOC'];

    $txtfiltroConceptoVentaOC = $_POST['txtfiltroConceptoVentaOC'];
    
    $txtUsuario = $_POST['txtUsuario']; ;

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    //DATOS EMPRESA
    $empresa = mysqli_query($conection, "SELECT 
    token_facturacion as token,
    ruc as ruc,
    razon_social as razon_social,
    nombre_comercial as nombre_comercial,
    ubigeo_inei as ubigeo,
    direccion as direccion,
    url_facturacion as url
    FROM datos_empresa
    WHERE ESTADO='1'");
    $resp_empresa = mysqli_fetch_assoc($empresa);

    $token = $resp_empresa['token'];
    $ruc = $resp_empresa['ruc'];
    $razon_social = $resp_empresa['razon_social'];
    $nombre_comercial = $resp_empresa['nombre_comercial'];
    $ubigeo = $resp_empresa['ubigeo'];
    $direccion = $resp_empresa['direccion'];
    $URL = $resp_empresa['url'];


    //DATOS CLIENTE
    $tipo_documento = $cbxTipoDocumento;
    $documento = $txtNroDocumento;
    $datos_cliente = $txtdatos;
    $nombre_via = $txtDireccionCliente;
    $correo = $txtCorreoClienteOC;
    if(empty($txtCorreoClienteOC)){
        $correo = "admn.gpro@gmail.com";
    }
  
    //SERIE Y NUMERO
    $anio = date('Y');

    $consulta_sn = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='BOL' AND anio='$anio'");
    $resp_sn = mysqli_fetch_assoc($consulta_sn);
    $numero = $resp_sn['num'];
    $serie = $resp_sn['serie'];
    $correlativo = $resp_sn['correlativo'];

    $desc_serie = "";
    if ($numero > 0 && $numero < 10) {
        $desc_serie = $serie . "00" . $numero;
    } else {
        if ($numero > 10 && $numero < 100) {
            $desc_serie = $serie . "0" . $numero;
        } else {
            $desc_serie = $serie . $numero;
        }
    }

    $desc_correlativo = "";
    if ($correlativo > 0 && $correlativo < 10) {
        $desc_correlativo = "0000000" . $correlativo;
    } else {
        if ($correlativo >= 10 && $correlativo < 100) {
            $desc_correlativo = "000000" . $correlativo;
        } else {
            if ($correlativo >= 100 && $correlativo < 1000) {
                $desc_correlativo = "00000" . $correlativo;
            } else {
                if ($correlativo >= 1000 && $correlativo < 10000) {
                    $desc_correlativo = "0000" . $correlativo;
                } else {
                    if ($correlativo >= 10000 && $correlativo < 100000) {
                        $desc_correlativo = "000" . $correlativo;
                    } else {
                        if ($correlativo >= 100000 && $correlativo < 1000000) {
                            $desc_correlativo = "00" . $correlativo;
                        } else {
                            if ($correlativo >= 1000000 && $correlativo < 10000000) {
                                $desc_correlativo = "0" . $correlativo;
                            } else {
                                $desc_correlativo = $correlativo;
                            }
                        }
                    }
                }
            }
        }
    }

    //totales
    $consulta_totales = mysqli_query($conection, "SELECT
    op_gravada as op_gravada,
    igv as igv,
    importe_total as total,
    op_inafecta as op_inafecta
    FROM temporal_facturador_totales
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    $resp_totales = mysqli_fetch_assoc($consulta_totales);

    $op_gravada = $resp_totales['op_gravada'];
    $op_inafecta = $resp_totales['op_inafecta'];
    $igv = $resp_totales['igv'];
    $total = $resp_totales['total'];

    $buscar = mysqli_query($conection, "SELECT
    idfacturador as codigo,
    round(valor_unitario,2) as unitario,
    valor_igv as igv,
    importe_venta as total,
    descripcion as descripcion,
    cantidad as cantidad,
    inafecto as inafecto,
    VAL_UNIT_ITEM as val_uni,
    VAL_VTA_ITEM as val_vta
    FROM temporal_facturador
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

    while($row = $buscar->fetch_assoc()) {
        
        
        $inafecto = $row['inafecto'];
        $var_inafecto = 10;
        if($inafecto == 1){
            $var_inafecto = 30;
        }
        
        $val_uni = "";
        $val_vta = "";
        
        $uni = $row['val_uni'];
        $vta = $row['val_vta'];
        
        if(!empty($uni)){
            $val_uni = $uni;
            if(!empty($vta)){
                $val_vta = $vta;
            }else{
                $val_uni = $row['unitario'];
                $val_vta = $row['unitario'];
            }
        }else{
            if(!empty($vta)){
                $val_vta = $vta;
            }else{
                $val_uni = $row['unitario'];
                $val_vta = $row['unitario'];
            }
        }
        
    if($txtfiltroConceptoVentaOC!="01"){
        array_push($dataList,
            '{        
            "COD_ITEM": "'.$row['codigo'].'",
            "COD_UNID_ITEM": "ARE",
            "CANT_UNID_ITEM": "'.$row['cantidad'].'",
            "VAL_UNIT_ITEM": "'.$val_uni.'",      
            "PRC_VTA_UNIT_ITEM": "'.$row['unitario'].'",
            "VAL_VTA_ITEM": "'.$val_vta.'",
            "MNT_PV_ITEM": "'.$row['total'].'",
            "COD_TIP_PRC_VTA": "01",
            "COD_TIP_AFECT_IGV_ITEM":"'.$var_inafecto.'",
            "COD_TRIB_IGV_ITEM": "1000",
            "POR_IGV_ITEM": "18",
            "MNT_IGV_ITEM": "'.$row['igv'].'",      
            "TXT_DESC_ITEM": "'.$row['descripcion'].'",                  
            "DET_VAL_ADIC01": "PROYECTO LAGUNA BEACH",
            "DET_VAL_ADIC02": "",
            "DET_VAL_ADIC03": "",
            "DET_VAL_ADIC04": ""
            }'
        );
    }else{  
        
        array_push($dataList,
            '{        
            "COD_ITEM": "'.$row['codigo'].'",
            "COD_UNID_ITEM": "ARE",
            "CANT_UNID_ITEM": "'.$row['cantidad'].'",
            "VAL_UNIT_ITEM": "'.$row['unitario'].'",      
            "PRC_VTA_UNIT_ITEM": "'.$row['total'].'",
            "VAL_VTA_ITEM": "'.$row['unitario'].'",
            "MNT_BRUTO": "'.$row['unitario'].'",
            "MNT_PV_ITEM": "'.$row['total'].'",
            "COD_TIP_PRC_VTA": "01",
            "COD_TIP_AFECT_IGV_ITEM":"'.$var_inafecto.'",
            "COD_TRIB_IGV_ITEM": "1000",
            "POR_IGV_ITEM": "18",
            "MNT_IGV_ITEM": "'.$row['igv'].'",      
            "TXT_DESC_ITEM": "'.$row['descripcion'].'",                  
            "DET_VAL_ADIC01": "PROYECTO LAGUNA BEACH",
            "DET_VAL_ADIC02": "",
            "DET_VAL_ADIC03": "",
            "DET_VAL_ADIC04": ""
            }'
        );
    }    
        
        
    }

    $array = implode(",",$dataList);
    
   
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$URL.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "TOKEN":"'.$token.'",
    "COD_TIP_NIF_EMIS": "6",
    "NUM_NIF_EMIS": "'.$ruc.'",
    "NOM_RZN_SOC_EMIS": "'.$razon_social.'",
    "NOM_COMER_EMIS": "'.$nombre_comercial.'",
    "COD_UBI_EMIS": "'.$ubigeo.'",
    "TXT_DMCL_FISC_EMIS": "'.$direccion.'",
    "COD_TIP_NIF_RECP": "'.$cbxTipoDocumento.'",
    "NUM_NIF_RECP": "'.$txtNroDocumento.'",
    "NOM_RZN_SOC_RECP": "'.$txtdatos.'",
    "TXT_DMCL_FISC_RECEP": "'.$txtDireccionCliente.'",
    "FEC_EMIS": "'.$txtFechaEmision.'",
    "FEC_VENCIMIENTO": "'.$txtFechaVencimiento.'",
    "COD_TIP_CPE": "03",
    "NUM_SERIE_CPE": "'.$desc_serie.'",
    "NUM_CORRE_CPE": "'.$desc_correlativo.'",
    "COD_MND": "'.$cbxTipoMoneda.'",
    "TIP_CAMBIO":"1.000",
    "MailEnvio": "'.$correo.'",
    "COD_PRCD_CARGA": "001",
    "MNT_TOT_GRAVADO": "'.$op_gravada.'",     
    "MNT_TOT_TRIB_IGV": "'.$igv.'", 
    "MNT_TOT": "'.$total.'",
    "MNT_TOT_INAFECTO": "'.$op_inafecta.'", 
    "COD_PTO_VENTA": "jmifact",
    "ENVIAR_A_SUNAT": "true",
    "RETORNA_XML_ENVIO": "true",
    "RETORNA_XML_CDR": "true",
    "RETORNA_PDF": "true",
      "COD_FORM_IMPR":"001",
      "TXT_VERS_UBL":"2.1",
      "TXT_VERS_ESTRUCT_UBL":"2.0",
      "COD_ANEXO_EMIS":"0000",
      "COD_TIP_OPE_SUNAT": "0101",
      
    "items": [
        '.$array.'
    ]
        
    }',
    CURLOPT_HTTPHEADER => array(
        'postman-token: b4938777-800c-1fb1-b127-aefda436e223',
        'cache-control: no-cache',
        'content-type: application/json'
    ),
    ));
    
    $dato_salida = '{
    "TOKEN":"'.$token.'",
    "COD_TIP_NIF_EMIS": "6",
    "NUM_NIF_EMIS": "'.$ruc.'",
    "NOM_RZN_SOC_EMIS": "'.$razon_social.'",
    "NOM_COMER_EMIS": "'.$nombre_comercial.'",
    "COD_UBI_EMIS": "'.$ubigeo.'",
    "TXT_DMCL_FISC_EMIS": "'.$direccion.'",
    "COD_TIP_NIF_RECP": "'.$cbxTipoDocumento.'",
    "NUM_NIF_RECP": "'.$txtNroDocumento.'",
    "NOM_RZN_SOC_RECP": "'.$txtdatos.'",
    "TXT_DMCL_FISC_RECEP": "'.$txtDireccionCliente.'",
    "FEC_EMIS": "'.$txtFechaEmision.'",
    "FEC_VENCIMIENTO": "'.$txtFechaVencimiento.'",
    "COD_TIP_CPE": "03",
    "NUM_SERIE_CPE": "'.$desc_serie.'",
    "NUM_CORRE_CPE": "'.$desc_correlativo.'",
    "COD_MND": "'.$cbxTipoMoneda.'",
    "TIP_CAMBIO":"1.000",
    "MailEnvio": "'.$correo.'",
    "COD_PRCD_CARGA": "001",
    "MNT_TOT_GRAVADO": "'.$op_gravada.'",     
    "MNT_TOT_TRIB_IGV": "'.$igv.'", 
    "MNT_TOT": "'.$total.'",
    "MNT_TOT_INAFECTO": "'.$op_inafecta.'", 
    "COD_PTO_VENTA": "jmifact",
    "ENVIAR_A_SUNAT": "true",
    "RETORNA_XML_ENVIO": "true",
    "RETORNA_XML_CDR": "true",
    "RETORNA_PDF": "true",
      "COD_FORM_IMPR":"001",
      "TXT_VERS_UBL":"2.1",
      "TXT_VERS_ESTRUCT_UBL":"2.0",
      "COD_ANEXO_EMIS":"0000",
      "COD_TIP_OPE_SUNAT": "0101",
      
    "items": [
        '.$array.'
    ]
        
    }';
    
   
    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    //echo $response;

    $datos = json_decode($response, true);

    $cadena_para_codigo_qr = $datos["cadena_para_codigo_qr"];
    $cdr_sunat = $datos["cdr_sunat"];
    $codigo_hash = $datos["codigo_hash"];
    $correlativo_cpe = $datos["correlativo_cpe"];
    $errors = $datos["errors"];
    $estado_documento = $datos["estado_documento"];
    $pdf_bytes = $datos["pdf_bytes"];
    $serie_cpe = $datos["serie_cpe"];
    $sunat_description = $datos["sunat_description"];
    $sunat_note = $datos["sunat_note"];
    $sunat_responsecode = $datos["sunat_responsecode"];
    $ticket_sunat = $datos["ticket_sunat"];
    $tipo_cpe = $datos["tipo_cpe"];
    $url = $datos["url"];
    $xml_enviado = $datos["xml_enviado"];
   
    if(!empty($errors)){ 
        
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo emitir la boleta. Detalle del error : '.$errors.$valor_api;
        $data['detalle'] = $dato_salida;

    } else {    

        //GRABAR TABLA COMPROBANTE CABECERA
       $insertar_cabecera = mysqli_query($conection, "INSERT INTO fac_comprobante_cab(COD_TIP_NIF_EMIS, NUM_NIF_EMIS, NOM_RZN_SOC_EMIS, NOM_COMER_EMIS, COD_UBI_EMIS, TXT_DMCL_FISC_EMIS, COD_TIP_NIF_RECP, NUM_NIF_RECP,NOM_RZN_SOC_RECP, TXT_DMCL_FISC_RECEP, FEC_EMIS, FEC_VENCIMIENTO, COD_TIP_CPE, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_MND, TIP_CAMBIO, MAIL_ENVIO, COD_PRCD_CARGA, MNT_TOT_GRAVADO, MNT_TOT_TRIB_IGV, MNT_TOT, COD_PTO_VENTA, ENVIAR_A_SUNAT, RETORNA_XML_ENVIO, RETORNA_XML_CDR, RETORNA_PDF, COD_FORM_IMPR, TXT_VERS_UBL, TXT_VERS_ESTRUCT_UBL, COD_ANEXO_EMIS, COD_TIP_OPE_SUNAT, ID_SEDE, MNT_TOT_INAFECTO) VALUES ('6','$ruc','$razon_social','$nombre_comercial','$ubigeo','$direccion','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$txtDireccionCliente','$txtFechaEmision','$txtFechaVencimiento','03','$desc_serie','$desc_correlativo','$cbxTipoMoneda','1.000','$correo','001', '$op_gravada', '$igv', '$total', 'jmifact', 'true', 'true', 'true', 'true','001','2.1','2.0','0000','0101','00001','$op_inafecta')");

       if($insertar_cabecera){

            //GRABAR TABLA COMPROBANTE DETALLE
            $buscar_detalle = mysqli_query($conection, "SELECT
            idfacturador as codigo,
            valor_unitario as unitario,
            valor_igv as igv,
            importe_venta as total,
            descripcion as descripcion,
            cantidad as cantidad,
            idpago as idpago,
            inafecto as inafecto
            FROM temporal_facturador
            WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

            if($buscar_detalle->num_rows > 0){
                while($row = $buscar_detalle->fetch_assoc()) {

                    $codigo = $row['codigo'];
                    $cantidad = $row['cantidad'];
                    $unitario = $row['unitario'];
                    $total = $row['total'];
                    $igv = $row['igv'];
                    $descripcion = $row['descripcion'];
                    $idpago = $row['idpago'];
                    $inafecto = $row['inafecto'];
                    
                    if($inafecto=='1'){
                        $inafecto = 30;
                    }else{
                        $inafecto = 10;
                    }

                    $insertar_detalle = mysqli_query($conection,"INSERT INTO fac_comprobante_det(COD_ITEM, FEC_EMIS, FEC_VENCIMIENTO, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_UNID_ITEM, CANT_UNID_ITEM, VAL_UNIT_ITEM, PRC_VTA_UNIT_ITEM, VAL_VTA_ITEM, MNT_BRUTO, MNT_PV_ITEM, COD_TIP_PRC_VTA,COD_TIP_AFECT_IGV_ITEM,COD_TRIB_IGV_ITEM,POR_IGV_ITEM,MNT_IGV_ITEM, TXT_DESC_ITEM,DET_VAL_ADIC01,DET_VAL_ADIC02,DET_VAL_ADIC03,DET_VAL_ADIC04,idpago_detalle) VALUES ('$codigo','$txtFechaEmision','$txtFechaVencimiento','$desc_serie','$desc_correlativo','ARE','$cantidad','$unitario','$total','$unitario','$unitario','$total','01','$inafecto','1000','18','$igv','$descripcion','PROYECTO LAGUNA BEACH','','','','$idpago')");

                    //INGRESAR DATOS DE COMPROBANTE EN TABLA PAGOS DETALLE
                    $actualiza_datos_pago = mysqli_query($conection, "INSERT INTO gp_pagos_detalle_comprobante(idpago_detalle, serie, numero, cliente_tipodoc, cliente_doc, cliente_datos, pagado, fecha_emision, fecha_vencimiento, comprobante_url, tipo_moneda, id_concepto, debe_haber,tipo_comprobante_sunat) VALUES ('$idpago','$desc_serie','$desc_correlativo','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$total','$txtFechaEmision','$txtFechaVencimiento','$url','$cbxTipoMoneda','$txtfiltroConceptoVentaOC', 'H','03')");
                    
                }  
                
                
            }
            
            //============================================ INGRESOS
            if($txtfiltroConceptoVentaOC=="01"){
                
                $id = $idpago;
                 
                if(!empty($id)){
                    
                   $consulta_agencia = mysqli_query($conection, "SELECT 
					if(gppc.moneda_pago = '15381', cd.texto2, cd.texto3) as CuentaContable
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.agencia_bancaria AND cd.codigo_tabla='_BANCOS'
					WHERE gppd.idpago_detalle='$id'");														
            		$respuesta_consulta_agencia = mysqli_fetch_assoc($consulta_agencia);
                    $respuesta_agencia = $respuesta_consulta_agencia['CuentaContable'];
			
                    $query2 = mysqli_query($conection, "UPDATE 
					gp_pagos_cabecera gppc 
					INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
					SET gppc.cuenta_contable='$respuesta_agencia'
					WHERE gppd.idpago_detalle='$id'");
                   
        
        			/*************** CONSULTA PAGO CABECERA *****************/
        			$consultar_pago = mysqli_query($conection, "SELECT 
					gppc.idpago as idpago,
					gppd.idpago_detalle as iddetalle,
					gppc.sede as Sede,                                    
					date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
					cd.texto1 as Moneda,
					gppd.tipo_cambio as TipoCambio,
					gppc.glosa as Glosa,
					gppd.importe_pago as TotalImporte,
					if(cd.texto1='USD',cdx.texto2,cdx.texto3) as CuentaContable,
					gppc.operacion as Operacion,
					gppd.nro_operacion as Numero,
					gppc.accion as Accion,
					gppc.flujo_caja as Flujo,
					gppd.debe_haber as DebHab
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
					INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.agencia_bancaria AND cdx.codigo_tabla='_BANCOS'
					WHERE gppd.esta_borrado=0 AND gppd.idpago_detalle='$id'");
        						
					$result = mysqli_num_rows($consultar_pago);
						
					if ($result>0){

						/*inicio de numfilas_consultapago*/	
						
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
							
							if($tipo_cambio=='0'){
							    
							    $consultar_mx = mysqli_query($conection, "SELECT max(idconfig_tipo_cambio) as max FROM configuracion_tipo_cambio");
                                $respuesta_mx = mysqli_fetch_assoc($consultar_mx);
                                $max = $respuesta_mx['max'];
							    
							    $consultar_tc = mysqli_query($conection, "SELECT valor FROM configuracion_tipo_cambio WHERE idconfig_tipo_cambio='$max'");
                                $respuesta_tc = mysqli_fetch_assoc($consultar_tc);
                                
                                $tipo_cambio = $respuesta_tc['valor'];
							}
							
							//COMPLEMENTAR GLOSA CON NOMBRE DE CLIENTE
							$consultar_nombre = mysqli_query($conection, "SELECT 
							concat(dc.apellido_paterno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as nombre
							FROM gp_pagos_detalle gppd
							INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
							INNER JOIN gp_reservacion AS gpr ON gpr.id_lote=gppc.id_cronograma AND gppc.id_venta='0'
							INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
							WHERE gppd.idpago_detalle='$id'");
							$respuesta_nombre = mysqli_fetch_assoc($consultar_nombre);
							$nombre = $respuesta_nombre['nombre'];
							
							$glosa = $nombre.' - '.$glosa;
							$contador = "";
							if($debe_haber == "H"){		
							
								$consulta_id = mysqli_query($conection,"SELECT MAX(Id_Cabecera) AS contador FROM ingresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								//consultar si ya se registro pago
								$consultar_cabecera = mysqli_query($conection, "SELECT idingresos_cabecera as id, Id_Cabecera as codigo, Total as importe, Numero FROM ingresos_cabecera WHERE identificador='$iddetalle' AND Numero='$numero' AND Moneda='$moneda_pago'");
								$respuesta_cabecera = mysqli_num_rows($consultar_cabecera);
								
								if($respuesta_cabecera>0){
								    
								    //OBTENER ID DE INGRESOS CABECERA
								    $consultar_id = mysqli_fetch_assoc($consultar_cabecera);
								    $idingresos_cab = $consultar_id['id'];
								    $cod_cabecera = $consultar_id['codigo'];
								    $insertar_pagoCabHab = mysqli_query($conection, "SELECT * FROM ingresos_cabecera WHERE idingresos_cabecera='$idingresos_cab'");
								    
								}else{
								 
    								$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, id_pago, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, flujo_caja) 
    								VALUES ('$contador','$sede','$iddetalle','$idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$flujo')");
    								$cod_cabecera = $contador;
								}
								if($insertar_pagoCabHab){
									 /***********Insertar detalle ingreso ********/
									$detalle = 0;
									$consultar_detalle = mysqli_query($conection, "SELECT
									gppd.idpago as id,
									gppd.idpago_detalle as id_detalle,
									gppdc.tipo_comprobante_sunat as TipoComp,
									gppdc.serie as Serie,
									gppdc.numero as Numero,
									if(gppdc.tipo_moneda='USD',gppdc.pagado,(gppdc.pagado * gppd.tipo_cambio)) as TotalImporte,
									if(gppdc.tipo_moneda='USD', cdx.texto2, cdx.texto3) as CuentaContable,
									gppdc.tipo_moneda as moneda,
									cdx.texto5 as CentroCosto,
									gppdc.cliente_doc as DniRuc,
									gppdc.cliente_datos as RazonSocial,
									date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									gppd.debe_haber as DebHab
									FROM gp_pagos_detalle_comprobante gppdc
									INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									INNER JOIN gp_reservacion AS gpr ON gpr.id_lote=gppc.id_cronograma AND gppc.id_venta='0'
									INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
									INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppdc.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
									WHERE gppd.esta_borrado=0 AND gppdc.idpago_detalle='$id' AND gppd.nro_operacion='$numero'
									GROUP BY Serie, Numero");
								
									$detalle = mysqli_num_rows($consultar_detalle);
									
									for ($c=1; $c <= $detalle ; $c++){
									
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
										$consultar_regdet = mysqli_query($conection, "SELECT 
										if(cdx.texto1='USD',SUM(gppdc.pagado),(SUM(gppdc.pagado) * gppd.tipo_cambio)) as importePago
										FROM gp_pagos_detalle_comprobante gppdc
										INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
										INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
										WHERE gppdc.serie='$serie' AND gppdc.numero='$numero'");
										$respuesta_regdet = mysqli_num_rows($consultar_regdet);
										
										if($respuesta_regdet>0){
										    
										    $row = mysqli_fetch_assoc($consultar_regdet);
										    $total_pagado = $row['importePago'];
										    
    										//consultar si ya se registro el detalle con la serie y numero
    										$consultar_detalles = mysqli_query($conection, "SELECT idingresos_detalle FROM ingresos_detalle WHERE Serie='$serie' AND Numero='$numero'");
    										$respuesta_detalles = mysqli_num_rows($consultar_detalles);
    										
    										if($respuesta_detalles<=0){
    										
        										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
        										VALUES ('$cod_cabecera','$sede','$iddetalle','$c', '$tipo_comprobante','$serie','$numero','$total_pagado','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','',NULL,'$debe_haber')");
    										
    										}
										}
										
										$VARIABLE = $detalle;
									}
									
								}
							
							    
							}
						//$data = $respuesta_pago;			
						
					}
                
                 }
                
            }
            
            
            //============================================ VENTAS
                
                    $serie_filtro = $desc_serie;
                    $numero_filtro = $desc_correlativo;
                    
                    /****** CONSULTA VENTA ******/
                    
                    $consultar_iddet = mysqli_query($conection, "SELECT idpago_detalle as id FROM fac_comprobante_det");
            
                    $consultar_iddet = mysqli_query($conection, "SELECT max(idpago_detalle) as id FROM fac_comprobante_det WHERE NUM_SERIE_CPE='$serie_filtro' AND NUM_CORRE_CPE='$numero_filtro'");
                    $respuesta_iddet = mysqli_fetch_assoc($consultar_iddet);
                    
                    $idpago_detalle=$respuesta_iddet['id'];
                    
                    
                    $glosa = $descripcion;
            
					$consultar_pagoVC = mysqli_query($conection,"SELECT
														'1' AS Identificador,
														fcc.NOM_RZN_SOC_RECP AS razon_social,
														CONCAT(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',1),' ',SUBSTRING_INDEX(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',3),' ',-1)) as glosaa,
														fcc.NUM_NIF_RECP AS Ruc_Dni,
														date_format(fcc.FEC_EMIS, '%Y-%m-%d %H:%i:%s') AS Fecha,
														date_format(fcc.FEC_VENCIMIENTO, '%Y-%m-%d %H:%i:%s') AS FechaVencimiento,
														fcc.MNT_TOT_DESCUENTO AS Descuento,
														fcc.MNT_TOT AS TotalImporte,
														fcc.MNT_TOT_OTR_CGO AS Servicio,
														fcc.ID_SEDE AS Sede,
														fcc.NUM_SERIE_CPE AS Serie,
														fcc.NUM_CORRE_CPE AS Numero,
														fcc.COD_TIP_CPE AS tipoCsun,
														fcc.MNT_TOT_TRIB_IGV AS IGV,
														fcc.COD_MND AS Moneda,
														fcc.TIP_CAMBIO AS TipoCambio,
														'' AS Accion,
														'' AS TipoR,
														'' AS SerieR,
														'' AS NumeroR,
														'' AS FechaR,
														'' AS Propina, 
														'VENTAS INTERFACE LAGUNA' AS Glosa
														FROM fac_comprobante_cab fcc
														WHERE fcc.NUM_SERIE_CPE='$serie_filtro' AND fcc.NUM_CORRE_CPE='$numero_filtro'
														order by fcc.FEC_EMIS");     
								
                    $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                   // $idpagoVC = $respuesta_pago2['idpago'];
                    $iddetalleVC = $respuesta_pago2['Identificador'];
                    $rsVC=$respuesta_pago2['razon_social'];
                    $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                    $fecha_pVC = $respuesta_pago2['Fecha'];
                    $fecha_VencimientoVC = $respuesta_pago2['FechaVencimiento']; 
                    $desc_pVC = $respuesta_pago2['Descuento']; 							
                    $importeTVC = $respuesta_pago2['TotalImporte'];
                    $servcVC = $respuesta_pago2['Servicio'];
                    $sedeVC = $respuesta_pago2['Sede'];
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
                    $nombre_glosa = $respuesta_pago2['glosaa'];
                    $glosaVC = $glosa;
					
					//id para venta cabecera
                    $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                    $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
					$contadorVC = $consultaVC['contador'];
					$contadorVC = $contadorVC + 1;
					
					/****insertar datos en ventas cabecera****/
                    $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Fecha_Vencimiento, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                    VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC', '$fecha_VencimientoVC','$desc_pVC','$importeTVC','$servcVC','$sedeVC','$serieVC','$numVC','$tipo_codsunatVC','$igvVC', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC',NULL,'0','$glosaVC')");
					
					 //===========================================INGRESOS
        
        		/*
        				$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, id_pago, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, flujo_caja) 
        				VALUES ('$contador','$sede','$iddetalle','$idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$flujo')");
        		
        				$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
        				VALUES ('$contador','$sede','$iddetalle','$c', '$tipo_comprobante','$serie','$numero','$total_pagado','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','','$fecha','$debe_haber')");
            	*/								
            							
                    //============================================
					
					
					 /***********Insertar detalle ventas ********/
					if($insertar_pagoCabVentas){
						$detalleVC = 0;
						$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																gppdc.idpago_detalle as iddetalle,                                   						
																fcc.ID_SEDE as Sede,                                   						
																date_format(fcd.FEC_EMIS, '%Y-%m-%d %H:%i:%s') as Fecha,
																fcc.MNT_TOT_DESCUENTO as Descuento,
																fcd.MNT_PV_ITEM as ImportePago,
																fcc.MNT_TOT_OTR_CGO as Servicio,   
																fcd.NUM_SERIE_CPE as Serie,    
																fcd.NUM_CORRE_CPE as Numero,  
																fcc.COD_TIP_CPE  as TipoCS,
																cdtx.texto1 as CtaContable,    
																fcd.MNT_IGV_ITEM  as IGV,
																cdtx.texto2 as CentroCosto
																FROM fac_comprobante_det fcd
																INNER JOIN fac_comprobante_cab AS fcc ON fcc.NUM_CORRE_CPE=fcd.NUM_CORRE_CPE AND fcc.NUM_SERIE_CPE=fcd.NUM_SERIE_CPE
																INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=fcd.idpago_detalle
																INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																WHERE fcc.NUM_CORRE_CPE='$numero_filtro' AND fcc.NUM_SERIE_CPE='$serie_filtro'
																GROUP BY fcd.idcomprobante_det
																ORDER BY fcd.FEC_EMIS");
														
                            $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
							
                            for ($j=1; $j <= $detalleVC ; $j++) {
							
                                $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                $sedeDVC= $respuesta_detalleVC['Sede'];
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
										
								$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')");
                                
                                $consulta = "INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')";
                                
                                array_push($dataList, $consulta);
							
                            }										
					}
							
			//============================================ FIN VENTAS
            
            
            


            $cdr_sunat = str_replace(array(",","'"), ' ', $cdr_sunat);
            $sunat_description = str_replace(array(","), ' ', $sunat_description);

            //GRABAR TABLA COMPROBANTE IMPRESION
            $insertar_comprobante = mysqli_query($conection, "INSERT INTO fac_comprobante_impr(cadena, cdr_sunat, codigo_hash, correlativo_cpe, errors, estado_documento, pdf_bytes, serie_cpe, sunat_descripcion, sunat_note, sunat_responsecode, ticket_sunat, tipo_cpe, url_valor, xml_enviado, control_usuario, cliente, idlote, numero, serie, fecha_emision, tipo_comprobante_sunat) VALUES ('$cadena_para_codigo_qr','$cdr_sunat','$codigo_hash','$correlativo_cpe','$errors','$estado_documento','$pdf_bytes','$serie_cpe','$sunat_description','$sunat_note','$sunat_responsecode','$ticket_sunat','$tipo_cpe','$url', '$xml_enviado','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$desc_correlativo','$desc_serie','$txtFechaEmision','03')");

            if($insertar_comprobante){
                $consulta_correlativo = mysqli_query($conection, "SELECT
                correlativo as correlativo
                FROM fac_correlativo
                WHERE tipo_documento='BOL' AND estado='1'");
                $respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);
                $correlativo = $respuesta_correlativo['correlativo'];
                $correlativo = ($correlativo + 1);

                if($consulta_correlativo){
                    //ACTUALIZAR EL CORRELATIVO
                    $actualiza_correlativo = mysqli_query($conection, "UPDATE fac_correlativo
                    SET correlativo='$correlativo', user_registro='$idusuario'
                    WHERE tipo_documento='BOL' AND estado='1'");

                    $data['status'] = 'ok';
                    $data['data'] = 'El comprobante fue emitido con exito.';
                    $data['serie'] = $desc_serie;
                    $data['numero'] = $desc_correlativo;
                    $data['fecha_emision'] = $txtFechaEmision;
                }   
            }                     
       }
                
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if(isset($_POST['btnListarTablaComprobantesEmitidosOC'])){

    
    $serie = isset($_POST['serie']) ? $_POST['serie'] : Null;
    $serier = trim($serie);
    
    $numero = isset($_POST['numero']) ? $_POST['numero'] : Null;
    $numeror = trim($numero);
    
    $fechaEmision = isset($_POST['fechaEmision']) ? $_POST['fechaEmision'] : Null;
    $fechaEmisionr = trim($fechaEmision);
       
   
    $query = mysqli_query($conection,"SELECT
            fac.fecha_emision as fecha_emision,
            fac.serie as serie,
            fac.numero as numero,
            fcab.NOM_RZN_SOC_RECP as datos,
            fcab.MNT_TOT as total,
            fac.url_valor as url_valor
            FROM fac_comprobante_impr fac
            INNER JOIN fac_comprobante_cab AS fcab ON fcab.NUM_SERIE_CPE=fac.serie_cpe AND fcab.NUM_CORRE_CPE=fac.correlativo_cpe
            WHERE fac.serie='$serier' AND numero='$numeror' AND fecha_emision='$fechaEmisionr'" ); 

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'fecha_emision' => $row['fecha_emision'],
                'serie' => $row['serie'],
                'numero' => $row['numero'],
                'datos' => $row['datos'],
                'total' => $row['total'],
                'url_valor' => $row['url_valor']
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


if(isset($_POST['btnListarPagosReservas'])){

    
    $txtFiltroCliente = isset($_POST['txtFiltroCliente']) ? $_POST['txtFiltroCliente'] : Null;
    $txtFiltroClienter = trim($txtFiltroCliente);

    $query = mysqli_query($conection, "SELECT
            gppd.idpago_detalle as id,
            date_format(gpre.fecha_inicio_reserva, '%d%m%Y') as fecini,
            date_format(gpre.fecha_fin_reserva, '%d%m%Y') as fecfin,
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            cdx.texto1 as tipo_moneda,
            gppd.tipo_cambio as tipo_cambio,
            format(gppd.importe_pago,3) as importe_pago,
            format(gppd.pagado,2) as pagado,
            if(gppd.estado_facturacion='1', 'EMITIDO','POR EMITIR') as estado
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_reservacion AS gpre ON gpre.id_lote=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id=gpre.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpre.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            WHERE dc.documento='$txtFiltroClienter' AND gppd.id_venta='0'");

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            //Campos para llenar Tabla
            array_push($dataList, [
                'id' => $row['id'],
                'fecini' => $row['fecini'],
                'fecfin' => $row['fecfin'],
                'cliente' => $row['cliente'],
                'lote' => $row['lote'],
                'tipo_moneda' => $row['tipo_moneda'],
                'tipo_cambio' => $row['tipo_cambio'],
                'importe_pago' => $row['importe_pago'],
                'pagado' => $row['pagado'],
                'estado' => $row['estado']
            ]);
        }
        

        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {

        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
   
     
}


if(isset($_POST['btnBuscarReservasVal'])){

    $txtFiltroCliente = $_POST['txtFiltroClienteOC'];


    $query = mysqli_query($conection, "SELECT
            gppd.idpago_detalle as id,
            date_format(gpre.fecha_inicio_reserva, '%d%m%Y') as fecini,
            date_format(gpre.fecha_fin_reserva, '%d%m%Y') as fecfin,
            concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
            concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
            cdx.texto1 as tipo_moneda,
            gppd.tipo_cambio as tipo_cambio,
            gppd.importe_pago as importe_pago,
            gppd.pagado as pagado,
            if(gppd.estado_facturacion='1', 'EMITIDO','POR EMITIR') as estado
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_reservacion AS gpre ON gpre.id_lote=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id=gpre.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpre.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            WHERE dc.documento='$txtFiltroCliente' AND gppd.id_venta='0'");
            
    //consultar total registros
    $total = mysqli_query($conection, "SELECT
            count(gppd.idpago_detalle) as tot
            FROM gp_pagos_detalle gppd
            INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
            INNER JOIN gp_reservacion AS gpre ON gpre.id_lote=gppc.id_cronograma
            INNER JOIN datos_cliente AS dc ON dc.id=gpre.id_cliente
            INNER JOIN gp_lote AS gpl ON gpl.idlote=gpre.id_lote
            INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
            INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
            INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
            WHERE dc.documento='$txtFiltroCliente' AND gppd.id_venta='0'");
    $resultado = mysqli_fetch_assoc($total);
    $total = $resultado['tot'];
  
  if ($query) {
     
        $row = mysqli_fetch_assoc($query);
        $reserva = $row['pagado'];
        $id = $row['id'];
        
        $data['status'] = 'ok';
        $data['reserva'] = $reserva;
        $data['id'] = $id;
        $data['total'] = $total;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
        
    } else {
        $data['status'] = 'bad';
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}


if (isset($_POST['btnEmitirFacturaOC'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];    
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];    
    $txtFechaEmision = $_POST['txtFechaEmision'];  
    $txtFechaVencimiento = $_POST['txtFechaVencimiento'];
    $cbxTipoMoneda = $_POST['cbxTipoMoneda'];  

    $cbxTipoDocumento = $_POST['cbxTipoDocumento'];  
    $txtNroDocumento = $_POST['txtNroDocumento'];  
    $txtdatos = $_POST['txtdatos'];
    $txtDireccionCliente = $_POST['txtDireccionCliente'];
    $txtCorreoClienteOC = $_POST['txtCorreoClienteOC'];  

    $dato_nombre = explode(" ", $txtdatos);
    $dato_nombre = $dato_nombre[0].' '.$dato_nombre[2];

    $txtCamCantidadOC = $_POST['txtCamCantidadOC'];
    $txtCamDescripcionOC = $dato_nombre.' - '.$_POST['txtCamDescripcionOC'];
    $txtCamValorUnitOC = $_POST['txtCamValorUnitOC'];
    $txtCamDescOC = $_POST['txtCamDescOC'];

    $txtSerieControlBolOC = $_POST['txtSerieControlBolOC'];
    $txtNumeroControlBolOC = $_POST['txtNumeroControlBolOC'];

    $txtfiltroConceptoVentaOC = $_POST['txtfiltroConceptoVentaOC'];
    
    $txtUsuario = $_POST['txtUsuario']; ;

    $consultar_id = mysqli_query($conection,"SELECT idusuario FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_id = mysqli_fetch_assoc($consultar_id);
    $idusuario = $respuesta_id['idusuario'];

    //DATOS EMPRESA
    $empresa = mysqli_query($conection, "SELECT 
    token_facturacion as token,
    ruc as ruc,
    razon_social as razon_social,
    nombre_comercial as nombre_comercial,
    ubigeo_inei as ubigeo,
    direccion as direccion,
    url_facturacion as url
    FROM datos_empresa
    WHERE ESTADO='1'");
    $resp_empresa = mysqli_fetch_assoc($empresa);

    $token = $resp_empresa['token'];
    $ruc = $resp_empresa['ruc'];
    $razon_social = $resp_empresa['razon_social'];
    $nombre_comercial = $resp_empresa['nombre_comercial'];
    $ubigeo = $resp_empresa['ubigeo'];
    $direccion = $resp_empresa['direccion'];
    $URL = $resp_empresa['url'];


    //DATOS CLIENTE
    $tipo_documento = $cbxTipoDocumento;
    $documento = $txtNroDocumento;
    $datos_cliente = $txtdatos;
    $nombre_via = $txtDireccionCliente;
    $correo = $txtCorreoClienteOC;
    if(empty($txtCorreoClienteOC)){
        $correo = "admn.gpro@gmail.com";
    }
  
    //SERIE Y NUMERO
   $anio = date('Y');

    $consulta_sn = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='FAC' AND anio='$anio'");
    $resp_sn = mysqli_fetch_assoc($consulta_sn);
    $numero = $resp_sn['num'];
    $serie = $resp_sn['serie'];
    $correlativo = $resp_sn['correlativo'];

    $desc_serie = "";
    if ($numero > 0 && $numero < 10) {
        $desc_serie = $serie . "00" . $numero;
    } else {
        if ($numero > 10 && $numero < 100) {
            $desc_serie = $serie . "0" . $numero;
        } else {
            $desc_serie = $serie . $numero;
        }
    }

    $desc_correlativo = "";
    if ($correlativo > 0 && $correlativo < 10) {
        $desc_correlativo = "0000000" . $correlativo;
    } else {
        if ($correlativo >= 10 && $correlativo < 100) {
            $desc_correlativo = "000000" . $correlativo;
        } else {
            if ($correlativo >= 100 && $correlativo < 1000) {
                $desc_correlativo = "00000" . $correlativo;
            } else {
                if ($correlativo >= 1000 && $correlativo < 10000) {
                    $desc_correlativo = "0000" . $correlativo;
                } else {
                    if ($correlativo >= 10000 && $correlativo < 100000) {
                        $desc_correlativo = "000" . $correlativo;
                    } else {
                        if ($correlativo >= 100000 && $correlativo < 1000000) {
                            $desc_correlativo = "00" . $correlativo;
                        } else {
                            if ($correlativo >= 1000000 && $correlativo < 10000000) {
                                $desc_correlativo = "0" . $correlativo;
                            } else {
                                $desc_correlativo = $correlativo;
                            }
                        }
                    }
                }
            }
        }
    }

    //totales
    $consulta_totales = mysqli_query($conection, "SELECT
    op_gravada as op_gravada,
    igv as igv,
    importe_total as total,
    op_inafecta as op_inafecta
    FROM temporal_facturador_totales
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");
    $resp_totales = mysqli_fetch_assoc($consulta_totales);

    $op_gravada = $resp_totales['op_gravada'];
    $op_inafecta = $resp_totales['op_inafecta'];
    $igv = $resp_totales['igv'];
    $total = $resp_totales['total'];

    $buscar = mysqli_query($conection, "SELECT
    idfacturador as codigo,
    round(valor_unitario,2) as unitario,
    valor_igv as igv,
    importe_venta as total,
    descripcion as descripcion,
    cantidad as cantidad,
    inafecto as inafecto
    FROM temporal_facturador
    WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

    while($row = $buscar->fetch_assoc()) {
        
        $inafecto = $row['inafecto'];
        $var_inafecto = 10;
        if($inafecto == 1){
            $var_inafecto = 30;
        }
    
        array_push($dataList,
            '{        
            "COD_ITEM": "'.$row['codigo'].'",
            "COD_UNID_ITEM": "ARE",
            "CANT_UNID_ITEM": "'.$row['cantidad'].'",
            "VAL_UNIT_ITEM": "'.$row['unitario'].'",      
            "PRC_VTA_UNIT_ITEM": "'.$row['total'].'",
            "VAL_VTA_ITEM": "'.$row['unitario'].'",
            "MNT_BRUTO": "'.$row['unitario'].'",
            "MNT_PV_ITEM": "'.$row['total'].'",
            "COD_TIP_PRC_VTA": "01",
            "COD_TIP_AFECT_IGV_ITEM":"'.$var_inafecto.'",
            "COD_TRIB_IGV_ITEM": "1000",
            "POR_IGV_ITEM": "18",
            "MNT_IGV_ITEM": "'.$row['igv'].'",      
            "TXT_DESC_ITEM": "'.$row['descripcion'].'",                  
            "DET_VAL_ADIC01": "PROYECTO LAGUNA BEACH",
            "DET_VAL_ADIC02": "",
            "DET_VAL_ADIC03": "",
            "DET_VAL_ADIC04": ""
            }'
        );
    }

    $array = implode(",",$dataList);
    
   
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$URL.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "TOKEN":"'.$token.'",
    "COD_TIP_NIF_EMIS": "6",
    "NUM_NIF_EMIS": "'.$ruc.'",
    "NOM_RZN_SOC_EMIS": "'.$razon_social.'",
    "NOM_COMER_EMIS": "'.$nombre_comercial.'",
    "COD_UBI_EMIS": "'.$ubigeo.'",
    "TXT_DMCL_FISC_EMIS": "'.$direccion.'",
    "COD_TIP_NIF_RECP": "'.$cbxTipoDocumento.'",
    "NUM_NIF_RECP": "'.$txtNroDocumento.'",
    "NOM_RZN_SOC_RECP": "'.$txtdatos.'",
    "TXT_DMCL_FISC_RECEP": "'.$txtDireccionCliente.'",
    "FEC_EMIS": "'.$txtFechaEmision.'",
    "FEC_VENCIMIENTO": "'.$txtFechaVencimiento.'",
    "COD_TIP_CPE": "01",
    "NUM_SERIE_CPE": "'.$desc_serie.'",
    "NUM_CORRE_CPE": "'.$desc_correlativo.'",
    "COD_MND": "'.$cbxTipoMoneda.'",
    "TIP_CAMBIO":"1.000",
    "MailEnvio": "'.$correo.'",
    "COD_PRCD_CARGA": "001",
    "MNT_TOT_GRAVADO": "'.$op_gravada.'",     
    "MNT_TOT_TRIB_IGV": "'.$igv.'", 
    "MNT_TOT": "'.$total.'",
    "MNT_TOT_INAFECTO": "'.$op_inafecta.'", 
    "COD_PTO_VENTA": "jmifact",
    "ENVIAR_A_SUNAT": "true",
    "RETORNA_XML_ENVIO": "true",
    "RETORNA_XML_CDR": "true",
    "RETORNA_PDF": "true",
      "COD_FORM_IMPR":"001",
      "TXT_VERS_UBL":"2.1",
      "TXT_VERS_ESTRUCT_UBL":"2.0",
      "COD_ANEXO_EMIS":"0000",
      "COD_TIP_OPE_SUNAT": "0101",
      
    "items": [
        '.$array.'
    ]
        
    }',
    CURLOPT_HTTPHEADER => array(
        'postman-token: b4938777-800c-1fb1-b127-aefda436e223',
        'cache-control: no-cache',
        'content-type: application/json'
    ),
    ));
    
   
    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
    //echo $response;

    $datos = json_decode($response, true);

    $cadena_para_codigo_qr = $datos["cadena_para_codigo_qr"];
    $cdr_sunat = $datos["cdr_sunat"];
    $codigo_hash = $datos["codigo_hash"];
    $correlativo_cpe = $datos["correlativo_cpe"];
    $errors = $datos["errors"];
    $estado_documento = $datos["estado_documento"];
    $pdf_bytes = $datos["pdf_bytes"];
    $serie_cpe = $datos["serie_cpe"];
    $sunat_description = $datos["sunat_description"];
    $sunat_note = $datos["sunat_note"];
    $sunat_responsecode = $datos["sunat_responsecode"];
    $ticket_sunat = $datos["ticket_sunat"];
    $tipo_cpe = $datos["tipo_cpe"];
    $url = $datos["url"];
    $xml_enviado = $datos["xml_enviado"];
   
    if(!empty($errors)){ 
        
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo emitir la boleta. Detalle del error : '.$errors.$valor_api;
        $data['detalle'] = $array;

    } else {    

        //GRABAR TABLA COMPROBANTE CABECERA
       $insertar_cabecera = mysqli_query($conection, "INSERT INTO fac_comprobante_cab(COD_TIP_NIF_EMIS, NUM_NIF_EMIS, NOM_RZN_SOC_EMIS, NOM_COMER_EMIS, COD_UBI_EMIS, TXT_DMCL_FISC_EMIS, COD_TIP_NIF_RECP, NUM_NIF_RECP,NOM_RZN_SOC_RECP, TXT_DMCL_FISC_RECEP, FEC_EMIS, FEC_VENCIMIENTO, COD_TIP_CPE, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_MND, TIP_CAMBIO, MAIL_ENVIO, COD_PRCD_CARGA, MNT_TOT_GRAVADO, MNT_TOT_TRIB_IGV, MNT_TOT, COD_PTO_VENTA, ENVIAR_A_SUNAT, RETORNA_XML_ENVIO, RETORNA_XML_CDR, RETORNA_PDF, COD_FORM_IMPR, TXT_VERS_UBL, TXT_VERS_ESTRUCT_UBL, COD_ANEXO_EMIS, COD_TIP_OPE_SUNAT, ID_SEDE, MNT_TOT_INAFECTO) VALUES ('6','$ruc','$razon_social','$nombre_comercial','$ubigeo','$direccion','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$txtDireccionCliente','$txtFechaEmision','$txtFechaVencimiento','01','$desc_serie','$desc_correlativo','$cbxTipoMoneda','1.000','$correo','001', '$op_gravada', '$igv', '$total', 'jmifact', 'true', 'true', 'true', 'true','001','2.1','2.0','0000','0101','00001','$op_inafecta')");

       if($insertar_cabecera){

            //GRABAR TABLA COMPROBANTE DETALLE
            $buscar_detalle = mysqli_query($conection, "SELECT
            idfacturador as codigo,
            valor_unitario as unitario,
            valor_igv as igv,
            importe_venta as total,
            descripcion as descripcion,
            cantidad as cantidad,
            idpago as idpago,
            inafecto as inafecto
            FROM temporal_facturador
            WHERE iduser='$idusuario' AND doc_cliente='$txtFiltroCliente' AND idlote='$txtFiltroPropiedad' AND fecha_emision='$txtFechaEmision'");

            if($buscar_detalle->num_rows > 0){
                while($row = $buscar_detalle->fetch_assoc()) {

                    $codigo = $row['codigo'];
                    $cantidad = $row['cantidad'];
                    $unitario = $row['unitario'];
                    $total = $row['total'];
                    $igv = $row['igv'];
                    $descripcion = $row['descripcion'];
                    $idpago = $row['idpago'];
                    $inafecto = $row['inafecto'];
                    
                    if($inafecto=='1'){
                        $inafecto = 30;
                    }else{
                        $inafecto = 10;
                    }

                    $insertar_detalle = mysqli_query($conection,"INSERT INTO fac_comprobante_det(COD_ITEM, FEC_EMIS, FEC_VENCIMIENTO, NUM_SERIE_CPE, NUM_CORRE_CPE, COD_UNID_ITEM, CANT_UNID_ITEM, VAL_UNIT_ITEM, PRC_VTA_UNIT_ITEM, VAL_VTA_ITEM, MNT_BRUTO, MNT_PV_ITEM, COD_TIP_PRC_VTA,COD_TIP_AFECT_IGV_ITEM,COD_TRIB_IGV_ITEM,POR_IGV_ITEM,MNT_IGV_ITEM, TXT_DESC_ITEM,DET_VAL_ADIC01,DET_VAL_ADIC02,DET_VAL_ADIC03,DET_VAL_ADIC04,idpago_detalle) VALUES ('$codigo','$txtFechaEmision','$txtFechaVencimiento','$desc_serie','$desc_correlativo','ARE','$cantidad','$unitario','$total','$unitario','$unitario','$total','01','$inafecto','1000','18','$igv','$descripcion','PROYECTO LAGUNA BEACH','','','','$idpago')");

                    //INGRESAR DATOS DE COMPROBANTE EN TABLA PAGOS DETALLE
                    $actualiza_datos_pago = mysqli_query($conection, "INSERT INTO gp_pagos_detalle_comprobante(idpago_detalle, serie, numero, cliente_tipodoc, cliente_doc, cliente_datos, pagado, fecha_emision, fecha_vencimiento, comprobante_url, tipo_moneda, id_concepto, debe_haber,tipo_comprobante_sunat) VALUES ('$idpago','$desc_serie','$desc_correlativo','$cbxTipoDocumento','$txtNroDocumento','$txtdatos','$total','$txtFechaEmision','$txtFechaVencimiento','$url','$cbxTipoMoneda','$txtfiltroConceptoVentaOC', 'H','01')");
                    
                }  
                
                
            }

             //============================================ INGRESOS
            if($txtfiltroConceptoVentaOC=="01"){
                
                $id = $idpago;
                 
                if(!empty($id)){
                    
                   $consulta_agencia = mysqli_query($conection, "SELECT 
					if(gppc.moneda_pago = '15381', cd.texto2, cd.texto3) as CuentaContable
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago 
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppc.agencia_bancaria AND cd.codigo_tabla='_BANCOS'
					WHERE gppd.idpago_detalle='$id'");														
            		$respuesta_consulta_agencia = mysqli_fetch_assoc($consulta_agencia);
                    $respuesta_agencia = $respuesta_consulta_agencia['CuentaContable'];
			
                    $query2 = mysqli_query($conection, "UPDATE 
					gp_pagos_cabecera gppc 
					INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago=gppc.idpago 
					SET gppc.cuenta_contable='$respuesta_agencia'
					WHERE gppd.idpago_detalle='$id'");
                   
        
        			/*************** CONSULTA PAGO CABECERA *****************/
        			$consultar_pago = mysqli_query($conection, "SELECT 
					gppc.idpago as idpago,
					gppd.idpago_detalle as iddetalle,
					gppc.sede as Sede,                                    
					date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as Fecha,
					cd.texto1 as Moneda,
					gppd.tipo_cambio as TipoCambio,
					gppc.glosa as Glosa,
					gppd.importe_pago as TotalImporte,
					if(cd.texto1='USD',cdx.texto2,cdx.texto3) as CuentaContable,
					gppc.operacion as Operacion,
					gppd.nro_operacion as Numero,
					gppc.accion as Accion,
					gppc.flujo_caja as Flujo,
					gppd.debe_haber as DebHab
					FROM gp_pagos_detalle gppd
					INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
					INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gppd.moneda_pago AND cd.codigo_tabla='_TIPO_MONEDA'
					INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.agencia_bancaria AND cdx.codigo_tabla='_BANCOS'
					WHERE gppd.esta_borrado=0 AND gppd.idpago_detalle='$id'");
        						
					$result = mysqli_num_rows($consultar_pago);
						
					if ($result>0){

						/*inicio de numfilas_consultapago*/	
						
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
							
							if($tipo_cambio=='0'){
							    
							    $consultar_mx = mysqli_query($conection, "SELECT max(idconfig_tipo_cambio) as max FROM configuracion_tipo_cambio");
                                $respuesta_mx = mysqli_fetch_assoc($consultar_mx);
                                $max = $respuesta_mx['max'];
							    
							    $consultar_tc = mysqli_query($conection, "SELECT valor FROM configuracion_tipo_cambio WHERE idconfig_tipo_cambio='$max'");
                                $respuesta_tc = mysqli_fetch_assoc($consultar_tc);
                                
                                $tipo_cambio = $respuesta_tc['valor'];
							}
							
							//COMPLEMENTAR GLOSA CON NOMBRE DE CLIENTE
							$consultar_nombre = mysqli_query($conection, "SELECT 
							concat(dc.apellido_paterno,' ',SUBSTRING_INDEX(dc.nombres,' ',1)) as nombre
							FROM gp_pagos_detalle gppd
							INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
							INNER JOIN gp_reservacion AS gpr ON gpr.id_lote=gppc.id_cronograma AND gppc.id_venta='0'
							INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
							WHERE gppd.idpago_detalle='$id'");
							$respuesta_nombre = mysqli_fetch_assoc($consultar_nombre);
							$nombre = $respuesta_nombre['nombre'];
							
							$glosa = $nombre.' - '.$glosa;
							$contador = "";
							if($debe_haber == "H"){		
							
								$consulta_id = mysqli_query($conection,"SELECT MAX(Id_Cabecera) AS contador FROM ingresos_cabecera");
								$consulta = mysqli_fetch_assoc($consulta_id);								
								$contador = $consulta['contador'];
								$contador = $contador + 1;
								
								//consultar si ya se registro pago
								$consultar_cabecera = mysqli_query($conection, "SELECT idingresos_cabecera as id, Id_Cabecera as codigo, Total as importe, Numero FROM ingresos_cabecera WHERE identificador='$iddetalle' AND Numero='$numero' AND Moneda='$moneda_pago'");
								$respuesta_cabecera = mysqli_num_rows($consultar_cabecera);
								
								if($respuesta_cabecera>0){
								    
								    //OBTENER ID DE INGRESOS CABECERA
								    $consultar_id = mysqli_fetch_assoc($consultar_cabecera);
								    $idingresos_cab = $consultar_id['id'];
								    $cod_cabecera = $consultar_id['codigo'];
								    $insertar_pagoCabHab = mysqli_query($conection, "SELECT * FROM ingresos_cabecera WHERE idingresos_cabecera='$idingresos_cab'");
								    
								}else{
								 
    								$insertar_pagoCabHab = mysqli_query($conection,"INSERT INTO ingresos_cabecera(Id_Cabecera, Sede, identificador, id_pago, Fecha, Moneda, TipoCambio, Glosa, Total, Cuenta_Contable, Operacion, Numero, Accion, flujo_caja) 
    								VALUES ('$contador','$sede','$iddetalle','$idpago','$fecha_pago', '$moneda_pago','$tipo_cambio','$glosa','$importe_pago','$cuenta_contable', '$operacion','$numero','$accion','$flujo')");
    								$cod_cabecera = $contador;
								}
								if($insertar_pagoCabHab){
									 /***********Insertar detalle ingreso ********/
									$detalle = 0;
									$consultar_detalle = mysqli_query($conection, "SELECT
									gppd.idpago as id,
									gppd.idpago_detalle as id_detalle,
									gppdc.tipo_comprobante_sunat as TipoComp,
									gppdc.serie as Serie,
									gppdc.numero as Numero,
									if(gppdc.tipo_moneda='USD',gppdc.pagado,(gppdc.pagado * gppd.tipo_cambio)) as TotalImporte,
									if(gppdc.tipo_moneda='USD', cdx.texto2, cdx.texto3) as CuentaContable,
									gppdc.tipo_moneda as moneda,
									cdx.texto5 as CentroCosto,
									gppdc.cliente_doc as DniRuc,
									gppdc.cliente_datos as RazonSocial,
									date_format(gppd.fecha_pago, '%Y-%m-%d %H:%i:%s') as FechaR,
									gppd.debe_haber as DebHab
									FROM gp_pagos_detalle_comprobante gppdc
									INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
									INNER JOIN gp_pagos_cabecera AS gppc ON gppc.idpago=gppd.idpago
									INNER JOIN gp_reservacion AS gpr ON gpr.id_lote=gppc.id_cronograma AND gppc.id_venta='0'
									INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
									INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_sunat=gppdc.tipo_comprobante_sunat AND cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT'
									WHERE gppd.esta_borrado=0 AND gppdc.idpago_detalle='$id' AND gppd.nro_operacion='$numero'
									GROUP BY Serie, Numero");
								
									$detalle = mysqli_num_rows($consultar_detalle);
									
									for ($c=1; $c <= $detalle ; $c++){
									
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
										$consultar_regdet = mysqli_query($conection, "SELECT 
										if(cdx.texto1='USD',SUM(gppdc.pagado),(SUM(gppdc.pagado) * gppd.tipo_cambio)) as importePago
										FROM gp_pagos_detalle_comprobante gppdc
										INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gppdc.idpago_detalle
										INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gppd.moneda_pago AND cdx.codigo_tabla='_TIPO_MONEDA'
										WHERE gppdc.serie='$serie' AND gppdc.numero='$numero'");
										$respuesta_regdet = mysqli_num_rows($consultar_regdet);
										
										if($respuesta_regdet>0){
										    
										    $row = mysqli_fetch_assoc($consultar_regdet);
										    $total_pagado = $row['importePago'];
										    
    										//consultar si ya se registro el detalle con la serie y numero
    										$consultar_detalles = mysqli_query($conection, "SELECT idingresos_detalle FROM ingresos_detalle WHERE Serie='$serie' AND Numero='$numero'");
    										$respuesta_detalles = mysqli_num_rows($consultar_detalles);
    										
    										if($respuesta_detalles<=0){
    										
        										$insertar_pagoDet = mysqli_query($conection,"INSERT INTO ingresos_detalle(Id_Cabecera, Sede, identificador, Id_Detalle, Tipo, Serie, Numero, Total, Cuenta_Contable, Centro_Costo, DniRuc, RazonSocial, TipoR, SerieR, NumeroR, FechaR, DebHab)
        										VALUES ('$cod_cabecera','$sede','$iddetalle','$c', '$tipo_comprobante','$serie','$numero','$total_pagado','$cuenta_contable', '$centro_costo','$dni_ruc','$razon_social','','','',NULL,'$debe_haber')");
    										
    										}
										}
										
										$VARIABLE = $detalle;
									}
									
								}
							
							    
							}
						//$data = $respuesta_pago;			
						
					}
                
                 }
                
            }
               



             //============================================ VENTAS
                
                    $serie_filtro = $desc_serie;
                    $numero_filtro = $desc_correlativo;
                    
                    /****** CONSULTA VENTA ******/
                    
                    $consultar_iddet = mysqli_query($conection, "SELECT idpago_detalle as id FROM fac_comprobante_det");
            
                    $consultar_iddet = mysqli_query($conection, "SELECT max(idpago_detalle) as id FROM fac_comprobante_det WHERE NUM_SERIE_CPE='$serie_filtro' AND NUM_CORRE_CPE='$numero_filtro'");
                    $respuesta_iddet = mysqli_fetch_assoc($consultar_iddet);
                    
                    $idpago_detalle=$respuesta_iddet['id'];
                    
                    
                    $glosa = $descripcion;
            
					$consultar_pagoVC = mysqli_query($conection,"SELECT
														'1' AS Identificador,
														fcc.NOM_RZN_SOC_RECP AS razon_social,
														CONCAT(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',1),' ',SUBSTRING_INDEX(SUBSTRING_INDEX(fcc.NOM_RZN_SOC_RECP,' ',3),' ',-1)) as glosaa,
														fcc.NUM_NIF_RECP AS Ruc_Dni,
														date_format(fcc.FEC_EMIS, '%Y-%m-%d %H:%i:%s') AS Fecha,
														date_format(fcc.FEC_VENCIMIENTO, '%Y-%m-%d %H:%i:%s') AS FechaVencimiento,
														fcc.MNT_TOT_DESCUENTO AS Descuento,
														fcc.MNT_TOT AS TotalImporte,
														fcc.MNT_TOT_OTR_CGO AS Servicio,
														fcc.ID_SEDE AS Sede,
														fcc.NUM_SERIE_CPE AS Serie,
														fcc.NUM_CORRE_CPE AS Numero,
														fcc.COD_TIP_CPE AS tipoCsun,
														fcc.MNT_TOT_TRIB_IGV AS IGV,
														fcc.COD_MND AS Moneda,
														fcc.TIP_CAMBIO AS TipoCambio,
														'' AS Accion,
														'' AS TipoR,
														'' AS SerieR,
														'' AS NumeroR,
														'' AS FechaR,
														'' AS Propina, 
														'VENTAS INTERFACE LAGUNA' AS Glosa
														FROM fac_comprobante_cab fcc
														WHERE fcc.NUM_SERIE_CPE='$serie_filtro' AND fcc.NUM_CORRE_CPE='$numero_filtro'
														order by fcc.FEC_EMIS");     
								
                    $respuesta_pago2 = mysqli_fetch_assoc($consultar_pagoVC);						
                   // $idpagoVC = $respuesta_pago2['idpago'];
                    $iddetalleVC = $respuesta_pago2['Identificador'];
                    $rsVC=$respuesta_pago2['razon_social'];
                    $rdVC=$respuesta_pago2['Ruc_Dni'];                            
                    $fecha_pVC = $respuesta_pago2['Fecha'];
                    $fecha_VencimientoVC = $respuesta_pago2['FechaVencimiento']; 
                    $desc_pVC = $respuesta_pago2['Descuento']; 							
                    $importeTVC = $respuesta_pago2['TotalImporte'];
                    $servcVC = $respuesta_pago2['Servicio'];
                    $sedeVC = $respuesta_pago2['Sede'];
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
                    $nombre_glosa = $respuesta_pago2['glosaa'];
                    $glosaVC = $glosa;
					
					//id para venta cabecera
                    $consulta_idVC=mysqli_query($conection,"SELECT if(MAX(id_ventac) is null ,0,MAX(id_ventac)) AS contador FROM ventas_cabecera");
                    $consultaVC = mysqli_fetch_assoc($consulta_idVC);                               
					$contadorVC = $consultaVC['contador'];
					$contadorVC = $contadorVC + 1;
					
					/****insertar datos en ventas cabecera****/
                    $insertar_pagoCabVentas = mysqli_query($conection,"INSERT INTO ventas_cabecera (Id_VentaC, Razon_Social, Ruc_DNI, Fecha, Fecha_Vencimiento, Descuento, Total, Servicio, Sede, Serie, Numero, Tipo, IGV, Moneda, TipoCambio, Accion, TipoR, SerieR, NumeroR, FechaR, Propina, Glosa)
                    VALUES ('$contadorVC','$rsVC','$rdVC','$fecha_pVC', '$fecha_VencimientoVC','$desc_pVC','$importeTVC','$servcVC','$sedeVC','$serieVC','$numVC','$tipo_codsunatVC','$igvVC', '$monedaVC','$tipo_cambVC','$accionVC','$tipo_rVC','$serie_rVC','$numero_rVC',NULL,'0','$glosaVC')");
					
					 /***********Insertar detalle ventas ********/
					if($insertar_pagoCabVentas){
						$detalleVC = 0;
						$consultar_detalleVentaC = mysqli_query($conection,"SELECT 	
																gppdc.idpago_detalle as iddetalle,                                   						
																fcc.ID_SEDE as Sede,                                   						
																date_format(fcd.FEC_EMIS, '%Y-%m-%d %H:%i:%s') as Fecha,
																fcc.MNT_TOT_DESCUENTO as Descuento,
																fcd.MNT_PV_ITEM as ImportePago,
																fcc.MNT_TOT_OTR_CGO as Servicio,   
																fcd.NUM_SERIE_CPE as Serie,    
																fcd.NUM_CORRE_CPE as Numero,  
																fcc.COD_TIP_CPE  as TipoCS,
																cdtx.texto1 as CtaContable,    
																fcd.MNT_IGV_ITEM  as IGV,
																cdtx.texto2 as CentroCosto
																FROM fac_comprobante_det fcd
																INNER JOIN fac_comprobante_cab AS fcc ON fcc.NUM_CORRE_CPE=fcd.NUM_CORRE_CPE AND fcc.NUM_SERIE_CPE=fcd.NUM_SERIE_CPE
																INNER JOIN gp_pagos_detalle_comprobante AS gppdc ON gppdc.idpago_detalle=fcd.idpago_detalle
																INNER JOIN configuracion_detalle AS cdtx ON cdtx.codigo_sunat=gppdc.id_concepto AND cdtx.codigo_tabla='_CONCEPTOS_VENTAS'
																WHERE fcc.NUM_CORRE_CPE='$numero_filtro' AND fcc.NUM_SERIE_CPE='$serie_filtro'
																GROUP BY fcd.idcomprobante_det
																ORDER BY fcd.FEC_EMIS");
														
                            $detalleVC = mysqli_num_rows($consultar_detalleVentaC);
							
                            for ($j=1; $j <= $detalleVC ; $j++) {
							
                                $respuesta_detalleVC = mysqli_fetch_assoc($consultar_detalleVentaC);
                                $iddetalleDVC = $respuesta_detalleVC['iddetalle'];
                                $sedeDVC= $respuesta_detalleVC['Sede'];
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
										
								$insertar_VentaDetall=mysqli_query($conection,"INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')");
                                
                                $consulta = "INSERT INTO ventas_detalle (Id_ventaC, Identificador, Sede, Descuento, Total, Servicio, Serie, Numero, Tipo, Cuenta_Contable, IGV, Centro_Costo)
                                VALUES('$contadorVC', '$iddetalleDVC', '$sedeDVC','$descuentoDVC', '$importeDVC','$servicioDVC', '$serieDVC', '$numDVC','$tipoDVC', '$cuentcDVC', '$igvDVC', '$centrocDVC')";
                                
                                array_push($dataList, $consulta);
							
                            }										
					}
							
			//============================================ FIN VENTAS





            $cdr_sunat = str_replace(array(",","'"), ' ', $cdr_sunat);
            $sunat_description = str_replace(array(","), ' ', $sunat_description);

            //GRABAR TABLA COMPROBANTE IMPRESION
            $insertar_comprobante = mysqli_query($conection, "INSERT INTO fac_comprobante_impr(cadena, cdr_sunat, codigo_hash, correlativo_cpe, errors, estado_documento, pdf_bytes, serie_cpe, sunat_descripcion, sunat_note, sunat_responsecode, ticket_sunat, tipo_cpe, url_valor, xml_enviado, control_usuario, cliente, idlote, numero, serie, fecha_emision, tipo_comprobante_sunat) VALUES ('$cadena_para_codigo_qr','$cdr_sunat','$codigo_hash','$correlativo_cpe','$errors','$estado_documento','$pdf_bytes','$serie_cpe','$sunat_description','$sunat_note','$sunat_responsecode','$ticket_sunat','$tipo_cpe','$url', '$xml_enviado','$idusuario','$txtFiltroCliente','$txtFiltroPropiedad','$desc_correlativo','$desc_serie','$txtFechaEmision','01')");

            if($insertar_comprobante){
                $consulta_correlativo = mysqli_query($conection, "SELECT
                correlativo as correlativo
                FROM fac_correlativo
                WHERE tipo_documento='FAC' AND estado='1'");
                $respuesta_correlativo = mysqli_fetch_assoc($consulta_correlativo);
                $correlativo = $respuesta_correlativo['correlativo'];
                $correlativo = ($correlativo + 1);

                if($consulta_correlativo){
                    //ACTUALIZAR EL CORRELATIVO
                    $actualiza_correlativo = mysqli_query($conection, "UPDATE fac_correlativo
                    SET correlativo='$correlativo', user_registro='$idusuario'
                    WHERE tipo_documento='BOL' AND estado='1'");

                    $data['status'] = 'ok';
                    $data['data'] = 'El comprobante fue emitido con exito.';
                    $data['serie'] = $desc_serie;
                    $data['numero'] = $desc_correlativo;
                    $data['fecha_emision'] = $txtFechaEmision;
                }   
            }                     
       }
                
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnCargarDatosFacturaOC'])) {

    $txtFiltroCliente = $_POST['txtFiltroCliente'];
    $txtFiltroPropiedad = $_POST['txtFiltroPropiedad'];

    //DATOS EMPRESA
    $consultar_datos = mysqli_query($conection, "SELECT 
    de.razon_social as razon_social,
    de.direccion as direccion,
    concat(ud.nombre,' - ',up.nombre,' - ',ur.nombre) as lugar,
    de.ruc as ruc
    FROM datos_empresa de
    INNER JOIN ubigeo_distrito AS ud ON ud.codigo=de.ubigeo_distrito
    INNER JOIN ubigeo_provincia as up ON up.codigo=de.ubigeo_provincia
    INNER JOIN ubigeo_region as ur ON ur.codigo=de.ubigeo_region
    WHERE de.estado='1'");
    $respuesta = mysqli_fetch_assoc($consultar_datos);
    $razon_social = $respuesta['razon_social'];
    $direccion = $respuesta['direccion'];
    $lugar = $respuesta['lugar'];
    $ruc = $respuesta['ruc'];

    $anio = date('Y');

    //NUMERO COMPROBANTE FACTURA
    $consultar_numerofac = mysqli_query($conection, "SELECT
    serie_numero as num,
    serie_desc as serie,
    correlativo as correlativo
    FROM fac_correlativo
    WHERE estado='1' AND tipo_documento='FAC' AND anio='$anio'");
    $respuesta_numerofac = mysqli_fetch_assoc($consultar_numerofac);
    $numerofac = $respuesta_numerofac['num'];
    $seriefac = $respuesta_numerofac['serie'];
    $correlativofac = $respuesta_numerofac['correlativo'];

    $desc_serie_fac="";
    if($numerofac>0 && $numerofac<10){
        $desc_serie_fac=$seriefac."00".$numerofac;
    }else{
        if($numerofac>10 && $numerofac<100){
            $desc_serie_fac=$seriefac."0".$numerofac;
        }else{
            $desc_serie_fac=$seriefac.$numerofac;  
        }  
    }

    $correlativofac = $correlativofac;
    $desc_correlativofac="";
    $cont_correlativo = strlen($correlativofac);
    if($cont_correlativo>0 && $cont_correlativo<2){
        $desc_correlativofac="0000000".$correlativofac;
    }else{
       if($cont_correlativo>1 && $cont_correlativo<3){
            $desc_correlativofac="000000".$correlativofac;
       }else{
           if($cont_correlativo>2 && $cont_correlativo<4){
                $desc_correlativofac="00000".$correlativofac;
           }else{
                if($cont_correlativo>3 && $cont_correlativo<5){
                    $desc_correlativofac="0000".$correlativofac;
                }else{
                    if($cont_correlativo>4 && $cont_correlativo<6){
                        $desc_correlativofac="000".$correlativofac;
                    }else{
                        if($cont_correlativo>5 && $cont_correlativo<7){
                            $desc_correlativofac="00".$correlativofac;
                       }else{
                            if($cont_correlativo>6 && $cont_correlativo<8){
                                $desc_correlativofac="0".$correlativofac;
                            }else{
                                $desc_correlativofac=$correlativofac;
                            }
                       }
                    }
                }
           }
       }
    }
    
    $descrip_serie_fac = $desc_serie_fac." - ".$desc_correlativofac;

   
    $data['status'] = 'ok';  

    $data['fecha'] = $fecha_hoy;
    $data['razon_social'] = $razon_social;
    $data['direccion'] = $direccion;
    $data['lugar'] = $lugar;
    $data['ruc'] = $ruc;
    $data['seriefac'] = $descrip_serie_fac;

    $data['num_control'] = $desc_correlativofac;
    $data['serie_control'] = $desc_serie_fac;

   
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
















?>