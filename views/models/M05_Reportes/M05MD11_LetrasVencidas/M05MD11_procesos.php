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

if(isset($_POST['btnIrReporte'])){
    
    $_TXTUSR = $_POST['_TXTUSR'];
    
    $cbxFiltroNumDocumento = $_POST['cbxFiltroNumDocumento'];
    $cbxFiltroNumDocumento = encrypt($cbxFiltroNumDocumento, "123");
    
    $txtDesdeFiltro = $_POST['txtDesdeFiltro'];
    $txtDesdeFiltro = encrypt($txtDesdeFiltro, "123");
    
    $txtHastaFiltro = $_POST['txtHastaFiltro'];
    $txtHastaFiltro = encrypt($txtHastaFiltro, "123");
    
    $cbxEstadoLetra = $_POST['cbxEstadoLetra'];
    $cbxEstadoLetra = encrypt($cbxEstadoLetra, "123");
    
    $bxFiltroProyecto = $_POST['bxFiltroProyecto'];
    $bxFiltroProyecto = encrypt($bxFiltroProyecto, "123");
    
    $bxFiltroZona = $_POST['bxFiltroZona'];
    $bxFiltroZona = encrypt($bxFiltroZona, "123");
    
    $bxFiltroManzana = $_POST['bxFiltroManzana'];
    $bxFiltroManzana = encrypt($bxFiltroManzana, "123");
    
    $bxFiltroLote = $_POST['bxFiltroLote'];
    $bxFiltroLote = encrypt($bxFiltroLote, "123");
      
    
    $data['status'] = 'ok';
    $data['usr'] = $_TXTUSR;
    $data['documento'] = $cbxFiltroNumDocumento;
    $data['fecini'] = $txtDesdeFiltro;
    $data['fecfin'] = $txtHastaFiltro;
    $data['estado'] = $cbxEstadoLetra;
    $data['idproyecto'] = $bxFiltroProyecto;
    $data['idzona'] = $bxFiltroZona;
    $data['idmanzana'] = $bxFiltroManzana;
    $data['idlote'] = $bxFiltroLote;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['btnValidarFechas'])){
    
    $idProyecto = $_POST['idProyecto'];
    
    $consultar_fec_ini = mysqli_query($conection, "SELECT inicio_proyecto as inicio FROM gp_proyecto WHERE idproyecto='$idProyecto' AND estado='1'");
    $respuesta_fec_ini = mysqli_fetch_assoc($consultar_fec_ini);
    $fec_ini = $respuesta_fec_ini['inicio'];
    
    $ultimo_dia = date('Y-m-d');  
    
    $data['status'] = 'ok';
    $data['primero'] = $fec_ini;
    $data['ultimo'] = $ultimo_dia;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['ReturnListaLetrasVencidas'])){

	$cbxFiltroNumDocumento = isset($_POST['cbxFiltroNumDocumento']) ? $_POST['cbxFiltroNumDocumento'] : Null;
	$cbxFiltroNumDocumento = trim($cbxFiltroNumDocumento);
	
	$txtDesdeFiltro = isset($_POST['txtDesdeFiltro']) ? $_POST['txtDesdeFiltro'] : Null;
	$txtDesdeFiltro = trim($txtDesdeFiltro);
	
	$txtHastaFiltro = isset($_POST['txtHastaFiltro']) ? $_POST['txtHastaFiltro'] : Null;
	$txtHastaFiltro = trim($txtHastaFiltro);
	
	$cbxEstadoLetra = isset($_POST['cbxEstadoLetra']) ? $_POST['cbxEstadoLetra'] : Null;
	$cbxEstadoLetra = trim($cbxEstadoLetra);
	
	$bxFiltroProyecto = isset($_POST['bxFiltroProyecto']) ? $_POST['bxFiltroProyecto'] : Null;
	$bxFiltroProyecto = trim($bxFiltroProyecto);
	
	$bxFiltroZona = isset($_POST['bxFiltroZona']) ? $_POST['bxFiltroZona'] : Null;
	$bxFiltroZona = trim($bxFiltroZona);
	
	$bxFiltroManzana = isset($_POST['bxFiltroManzana']) ? $_POST['bxFiltroManzana'] : Null;
	$bxFiltroManzana = trim($bxFiltroManzana);
	
	$bxFiltroLote = isset($_POST['bxFiltroLote']) ? $_POST['bxFiltroLote'] : Null;
	$bxFiltroLote = trim($bxFiltroLote);
	
	$query_documento = "";
	$query_fecha = "";
	$query_proyecto = "";
	$query_zona = "";
	$query_manzana = "";
	$query_lote = "";
	
	if(!empty($cbxFiltroNumDocumento)){
	   $query_documento = "AND dc.documento='$cbxFiltroNumDocumento'"; 
	}
	
	if(!empty($txtDesdeFiltro) && empty($txtHastaFiltro)){
	   $query_fecha = "AND gpcr.fecha_vencimiento='$txtDesdeFiltro'"; 
	}else{
	    if(empty($txtDesdeFiltro) && !empty($txtHastaFiltro)){
	        $query_fecha = "AND gpcr.fecha_vencimiento='$txtHastaFiltro'"; 
	    }else{
	        if(!empty($txtDesdeFiltro) && !empty($txtHastaFiltro)){
	           $query_fecha = "AND gpcr.fecha_vencimiento BETWEEN '$txtDesdeFiltro' AND '$txtHastaFiltro'"; 
	        }
	    }
	}
	
	if(!empty($bxFiltroProyecto)){
	   $query_proyecto = "AND gpy.idproyecto='$bxFiltroProyecto'"; 
	}
	
	if(!empty($bxFiltroZona)){
	   $query_zona = "AND gpz.idzona='$bxFiltroZona'"; 
	}
	
	if(!empty($bxFiltroManzana)){
	   $query_manzana = "AND gpm.idmanzana='$bxFiltroManzana'"; 
	}
	
	if(!empty($bxFiltroLote)){
	   $query_lote = "AND gpl.idlote='$bxFiltroLote'"; 
	}
	
	$query_estado = "";
    
    if($cbxEstadoLetra === '1'){
        $query_estado = "AND ((gpcr.estado='1') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1'))";
    }else{
        if($cbxEstadoLetra === '3'){
            $query_estado = "AND ((gpcr.estado='3') OR (gpcr.estado='2' AND gpcr.pago_cubierto='1'))";
        }else{
            $query_estado = "AND ((gpcr.estado in ('3','1')) OR (gpcr.estado='2' AND gpcr.pago_cubierto='1'))";
        }
    }
	


		$query = mysqli_query($conection,"SELECT 
			gpcr.id as id,
			date_format(gpcr.fecha_vencimiento, '%d/%m/%Y') as fecha,
			concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
			concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
			gpcr.item_letra as letra,
			format(SUM(gpcr.monto_letra - (if(gpcr.estado='2',(select pagado from gp_pagos_cabecera where id_venta=gpv.id_venta AND esta_borrado='0' AND id_cronograma=gpcr.correlativo),0))),2) as monto,
            SUM(if('".$fecha."'>gpcr.fecha_vencimiento,if((TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, '".$fecha."')>0),concat('-',TIMESTAMPDIFF(DAY,gpcr.fecha_vencimiento, '".$fecha."')),0),0)) as mora,
			if(gpcr.estado='3','VENCIDO', if(gpcr.estado='1','POR VENCER',if(gpcr.estado='2',if(gpcr.fecha_vencimiento < '$fecha','VENCIDO','POR VENCER'),'-'))) as estado,
            gpcr.estado as dato_estado,
			gpv.tna as tea,
            format((select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta and gppd.esta_borrado='0'),2) as total_cancelado
			FROM gp_cronograma gpcr
			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
			INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
			WHERE gpcr.esta_borrado=0 AND gpv.devolucion!='1' AND gpv.cancelado!='1'
			$query_estado
			$query_documento
        	$query_fecha
        	$query_proyecto
        	$query_zona
        	$query_manzana
        	$query_lote
			GROUP BY cliente, letra WITH ROLLUP
			"); 
			
		$query_total = mysqli_query($conection,"SELECT 
			count(gpcr.item_letra) as letras,
            SUM(if('".$fecha."'>gpcr.fecha_vencimiento,if((TIMESTAMPDIFF(DAY, gpcr.fecha_vencimiento, '".$fecha."')>0),concat('-',TIMESTAMPDIFF(DAY,gpcr.fecha_vencimiento, '".$fecha."')),0),0)) as mora,
            format(SUM(gpcr.monto_letra - (if(gpcr.estado='2',(select pagado from gp_pagos_cabecera where id_venta=gpv.id_venta AND esta_borrado='0' AND id_cronograma=gpcr.correlativo),0))),2) as total
			FROM gp_cronograma gpcr
			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
			INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
			INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
			WHERE gpcr.esta_borrado=0 AND gpv.devolucion!='1' AND gpv.cancelado!='1'
			$query_estado
			$query_documento
        	$query_fecha
        	$query_proyecto
        	$query_zona
        	$query_manzana
        	$query_lote
			");
			$respuesta_total = mysqli_fetch_assoc($query_total);
			$letras = $respuesta_total['letras'];
            $mora = $respuesta_total['mora'];
            $total = $respuesta_total['total'];

		
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'cliente' => $row['cliente'],
                'lote' => $row['lote'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
				'mora' => $row['mora'],
				'total_cancelado' => $row['total_cancelado'],
				'estado' => $row['estado'],
				'dato_estado' => $row['dato_estado'],
				'tea' => $row['tea']
            ]);
        }
            
       $data['data'] = $dataList;
       $data['letras'] = $letras;
       $data['mora'] = $mora;
       $data['total'] = $total;
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

if (isset($_POST['ReturnZonas'])) {
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

if (isset($_POST['ListarZonas'])) {

    $idProyecto = intval($_POST['idProyecto']);
    $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idProyecto'");

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

if (isset($_POST['ListarManzanas'])) {

    $idzona = intval($_POST['idZona']);
    $query = mysqli_query($conection, "SELECT idmanzana as valor, nombre as texto FROM gp_manzana WHERE idzona='$idzona'");

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

if (isset($_POST['ListarLotes'])) {

    $idManzana = intval($_POST['idManzana']);
    $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idManzana' AND estado IN (5,6)");

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
