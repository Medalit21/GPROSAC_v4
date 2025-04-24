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
	
	$bxFiltroMotivo = isset($_POST['bxFiltroMotivo']) ? $_POST['bxFiltroMotivo'] : Null;
	$bxFiltroMotivos = trim($bxFiltroMotivo);
	
	$bxFiltroMedio = isset($_POST['bxFiltroMedio']) ? $_POST['bxFiltroMedio'] : Null;
	$bxFiltroMediosCap = trim($bxFiltroMedio);
	
	$bxFiltroLote = isset($_POST['bxFiltroLote']) ? $_POST['bxFiltroLote'] : Null;
	$bxFiltroLotes = trim($bxFiltroLote);
	
	$bxFiltroVendedor = isset($_POST['bxFiltroVendedor']) ? $_POST['bxFiltroVendedor'] : Null;
	$bxFiltroVendedores = trim($bxFiltroVendedor);
	
	$query_inicio = "";
	$query_fin	  = "";
	$query_motivo = "";
	$query_medio = "";
	$query_lote = "";
	$query_vendedor = "";
	
	if(!empty($bxFiltrotxtDesde)){
	   $query_inicio = "AND gpr.fecha_inicio_reserva='$bxFiltrotxtDesde'"; 
	}
	
	if(!empty($bxFiltrotxtHasta)){
	   $query_fin = "AND gpr.fecha_fin_reserva='$bxFiltrotxtHasta'"; 
	}

	if(!empty($bxFiltroMotivos)){
	   $query_motivo = "AND gpl.bloqueo_estado='$bxFiltroMotivos'"; 
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


    $query = mysqli_query($conection,"SELECT 
		gpv.id_venta as id, 
		gpz.nombre as Zona,
		gpm.nombre as Manzana,
		gpl.nombre AS Lote, 
		gpl.area AS Area, 
		if(gpv.tipo_casa>0,'Si','No') AS Casa,
		if(gpv.tipo_casa>0,(SELECT cfg.nombre_corto FROM configuracion_detalle cfg WHERE cfg.idconfig_detalle=gpv.tipo_casa), 'Ninguno') AS TipoCasa,
		CONCAT(cd_estado.nombre_corto,'',if(gpl.bloqueo_estado>0,
		CONCAT('(',(SELECT cnf.nombre_corto FROM configuracion_detalle cnf 
		WHERE cnf.codigo_item=gpl.bloqueo_estado AND cnf.codigo_tabla='_ESTADO_LOTE'),')'),'')) AS Estado,
		CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) AS Vendedor,
		cddd.nombre_corto as MedioCaptacion,
		gpv.monto_cuota_inicial as CuotaInicial,
		FORMAT(gpl.valor_sin_casa,2) AS ValorTerreno,
		FORMAT(gpl.valor_con_casa - gpl.valor_sin_casa,2) AS ValorCasa,
		FORMAT((gpl.valor_con_casa - gpl.valor_sin_casa)+gpl.valor_sin_casa,2) AS Total,
		CONCAT(SUBSTRING_INDEX(dc.nombres,' ',1),' ',dc.apellido_paterno) AS Cliente,
		(select nombre_corto as nom FROM configuracion_detalle WHERE idconfig_detalle=gpv.tipo_moneda) as Tipomoneda,
		gpr.fecha_inicio_reserva AS InicioFecha,
		gpr.fecha_fin_reserva AS FinFecha
		FROM gp_venta gpv
		INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
		INNER JOIN gp_reservacion AS gpr ON gpl.idlote=gpr.id_lote
		INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
		INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
		INNER JOIN configuracion_detalle AS cd_estado ON cd_estado.codigo_item=gpv.estado AND cd_estado.codigo_tabla='_ESTADO_LOTE'
		INNER JOIN persona AS per ON per.idusuario=gpv.id_usuario_crea
		INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
		INNER JOIN configuracion_detalle AS cddd ON cddd.codigo_item=gpr.medio_captacion AND cddd.codigo_tabla='_MEDIO_CAPTACION'
		$query_inicio
		$query_fin
		$query_motivo
		$query_medio
		$query_lote
		$query_vendedor
	
	
	"); 
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'zona' => $row['Zona'],
                'manzana' => $row['Manzana'],
                'lote' => $row['Lote'],
                'area' => $row['Area'],
                'casa' => $row['Casa'],
				'tipo_casa' => $row['TipoCasa'],
				'estado' => $row['Estado'],
				'vendedor' => $row['Vendedor'],
				'MedioCaptacion' => $row['MedioCaptacion'],
				'cuota_inicial' => $row['CuotaInicial'],
                'valor_terreno' => $row['ValorTerreno'],
                'valor_casa' => $row['ValorCasa'],
                'total' => $row['Total'],
                'cliente' => $row['Cliente'],
				'tipo_moneda' => $row['Tipomoneda'],
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
