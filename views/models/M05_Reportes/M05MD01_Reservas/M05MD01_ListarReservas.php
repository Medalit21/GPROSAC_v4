<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');
   


   $data = array();
   $dataList = array();

if(!empty($_POST)){

	$txtDesdeFiltro = isset($_POST['txtDesdeFiltro']) ? $_POST['txtDesdeFiltro'] : Null;
	$bxFiltrotxtDesde = trim($txtDesdeFiltro);
	
	$txtHastaFiltro = isset($_POST['txtHastaFiltro']) ? $_POST['txtHastaFiltro'] : Null;
	$bxFiltrotxtHasta = trim($txtHastaFiltro);
	
	$bxFiltroEstado = isset($_POST['bxFiltroEstado']) ? $_POST['bxFiltroEstado'] : Null;
	$bxFiltroEstados = trim($bxFiltroEstado);
	
	$bxFiltroMotivo = isset($_POST['bxFiltroMotivo']) ? $_POST['bxFiltroMotivo'] : Null;
	$bxFiltroMotivos = trim($bxFiltroMotivo);
	
	$bxFiltroMedio = isset($_POST['bxFiltroMedio']) ? $_POST['bxFiltroMedio'] : Null;
	$bxFiltroMediosCap = trim($bxFiltroMedio);
	
	$bxFiltroLote = isset($_POST['bxFiltroLote']) ? $_POST['bxFiltroLote'] : Null;
	$bxFiltroLotes = trim($bxFiltroLote);
	
	$bxFiltroVendedor = isset($_POST['bxFiltroVendedor']) ? $_POST['bxFiltroVendedor'] : Null;
	$bxFiltroVendedores = trim($bxFiltroVendedor);

	$txtDocumentoFiltro = isset($_POST['txtDocumentoFiltro']) ? $_POST['txtDocumentoFiltro'] : Null;
	$txtDocumentoFiltros = trim($txtDocumentoFiltro);
	
	$query_inicio = "";
	$query_fin	  = "";
	$query_estado = "";
	$query_motivo = "";
	$query_medio = "";
	$query_lote = "";
	$query_vendedor = "";
	$query_documento = "";
	
	
	
	if(!empty($bxFiltrotxtDesde) && empty($bxFiltrotxtHasta)){
	   $query_inicio = "AND gpr.fecha_inicio_reserva='$bxFiltrotxtDesde'"; 
	}else{
	    if(empty($bxFiltrotxtDesde) && !empty($bxFiltrotxtHasta)){
	        $query_fin = "AND gpr.fecha_fin_reserva='$bxFiltrotxtHasta'"; 
	    }else{
	        if(!empty($bxFiltrotxtDesde) && !empty($bxFiltrotxtHasta)){
	           $query_inicio = "AND gpr.fecha_inicio_reserva BETWEEN '$bxFiltrotxtDesde' AND '$bxFiltrotxtHasta'"; 
	        }
	    }
	}
	
	if(!empty($bxFiltroEstados)){
	   $query_estado = "AND gpr.estado='$bxFiltroEstados'"; 
	   $query_estado_2 = "AND gpl.estado='$bxFiltroEstados'"; 
	}
	
	if(!empty($bxFiltroMotivos)){
	    if($bxFiltroMotivos=="niguno"){
	        $query_motivo = "AND gpl.motivo='0'"; 
	    }else{
	        if($bxFiltroMotivos==7){
	             $query_motivo = "AND gpl.bloqueo_estado='$bxFiltroMotivos'"; 
	        }else{
	            $query_motivo = "AND gpl.motivo='$bxFiltroMotivos'"; 
	        }
	    }
	}
	
	if(!empty($bxFiltroMediosCap)){
	   $query_medio = "AND gpr.medio_captacion='$bxFiltroMediosCap'"; 
	}
	
	if(!empty($bxFiltroLotes)){
	   $query_lote = "AND gpl.nombre='$bxFiltroLotes'"; 
	}
	
	if(!empty($bxFiltroVendedores)){
	   $query_vendedor = "AND gpr.id_usuario_crea='$bxFiltroVendedores'"; 
	}

	if(!empty($txtDocumentoFiltros)){
		$query_documento = "AND dc.documento='$txtDocumentoFiltros'"; 
	 }
	
	
	$query = mysqli_query($conection,"SELECT 
	gpr.id_reservacion as id,
	gpz.nombre AS Zona,
	gpm.nombre AS Manzana,
	gpl.nombre AS Lote,
	gpl.area AS AreaLote,
	if(gpr.tipo_casa>0,'Si','No') AS Casa,
	if(gpr.tipo_casa>0,(SELECT cfg.nombre_corto FROM configuracion_detalle cfg WHERE cfg.idconfig_detalle=gpr.tipo_casa), 'Ninguno') AS TipoCasa,
	gpl.estado as Estado,
	gpl.bloqueo_estado as bloqueo,
	cddx.nombre_corto as descEstado,
	(if(gpl.bloqueo_estado>0, (select nombre_corto from configuracion_detalle where codigo_item=gpl.bloqueo_estado AND codigo_tabla='_ESTADO_LOTE'), '')) as descMotivo,
	cddx.texto1 as colorEstado,
	(if(gpl.bloqueo_estado>0, (select texto1 from configuracion_detalle where codigo_item=gpl.bloqueo_estado AND codigo_tabla='_ESTADO_LOTE'), '')) as colorMotivo,
	CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) AS Vendedor,
	cdd.nombre_corto AS MedioCaptacion,
	FORMAT(gpr.monto_reservado,2) AS ImporteReserva,
	FORMAT(gpl.valor_sin_casa,2) AS ValorTerreno,
	FORMAT(gpl.valor_con_casa - gpl.valor_sin_casa,2) AS ValorCasa,
	FORMAT((gpl.valor_con_casa - gpl.valor_sin_casa)+gpl.valor_sin_casa,2) AS Total,
	dc.documento as documento,
	CONCAT(SUBSTRING_INDEX(dc.nombres,' ',1),' ',dc.apellido_paterno) AS Cliente,
	cddd.nombre_corto AS TipoMoneda,
	gpr.fecha_inicio_reserva AS InicioFecha,
	gpr.fecha_fin_reserva AS FinFecha
	FROM gp_reservacion gpr
	INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
	INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
	INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
	INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gpl.estado AND cddx.codigo_tabla='_ESTADO_LOTE'
	INNER JOIN persona AS per ON per.idusuario=gpr.id_usuario_crea
	INNER JOIN configuracion_detalle AS cdd ON cdd.codigo_item=gpr.medio_captacion AND cdd.codigo_tabla='_MEDIO_CAPTACION'
	INNER JOIN datos_cliente AS dc ON dc.id=gpr.id_cliente
	INNER JOIN configuracion_detalle AS cddd ON cddd.idconfig_detalle=gpr.tipo_moneda_reserva
	WHERE gpr.esta_borrado=0 
	AND gpl.estado IN (2,5,6)
	$query_inicio
	$query_fin
	$query_motivo
	$query_medio
	$query_lote
	$query_vendedor
	$query_documento
	GROUP BY gpr.id_reservacion
	ORDER BY Estado ASC"); 
		
		
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            /*$data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);*/

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'zona' => $row['Zona'],
                'manzana' => $row['Manzana'],
                'lote' => $row['Lote'],
                'area' => $row['AreaLote'],
                'casa' => $row['Casa'],
				'tipo_casa' => $row['TipoCasa'],
				'estado' => $row['Estado'],
				'vendedor' => $row['Vendedor'],
				'MedioCaptacion' => $row['MedioCaptacion'],
				'importe_reserva' => $row['ImporteReserva'],
                'valor_terreno' => $row['ValorTerreno'],
                'valor_casa' => $row['ValorCasa'],
                'total' => $row['Total'],
				'documento' => $row['documento'],
                'cliente' => $row['Cliente'],
				'tipo_moneda' => $row['TipoMoneda'],
                'descEstado' => $row['descEstado'],
				'descMotivo' => $row['descMotivo'],
				'colorEstado' => $row['colorEstado'],
				'colorMotivo' => $row['colorMotivo'],
				'bloqueo' => $row['bloqueo'],
				'inicio' => $row['InicioFecha'],
                'fin' => $row['FinFecha']
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
