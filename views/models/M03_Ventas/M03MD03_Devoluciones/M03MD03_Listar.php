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

if(isset($_POST['ReturnListaDevoluciones'])){

	$txtDesdeFiltro = isset($_POST['txtDesdeFiltro']) ? $_POST['txtDesdeFiltro'] : Null;
	$bxFiltrotxtDesde = trim($txtDesdeFiltro);
	
	$txtHastaFiltro = isset($_POST['txtHastaFiltro']) ? $_POST['txtHastaFiltro'] : Null;
	$bxFiltrotxtHasta = trim($txtHastaFiltro);
	
	$bxFiltroEstado = isset($_POST['bxFiltroEstado']) ? $_POST['bxFiltroEstado'] : Null;
	$bxFiltroEstados = trim($bxFiltroEstado);
	
	$bxFiltroTipoCasa = isset($_POST['bxFiltroTipoCasa']) ? $_POST['bxFiltroTipoCasa'] : Null;
	$bxFiltroTipoCasas = trim($bxFiltroTipoCasa);
	
	$bxFiltroLote = isset($_POST['bxFiltroLote']) ? $_POST['bxFiltroLote'] : Null;
	$bxFiltroLotes = trim($bxFiltroLote);
	
	$bxFiltroVendedor = isset($_POST['bxFiltroVendedor']) ? $_POST['bxFiltroVendedor'] : Null;
	$bxFiltroVendedores = trim($bxFiltroVendedor);
	
	$txtdocumentoFiltro = isset($_POST['txtdocumentoFiltro']) ? $_POST['txtdocumentoFiltro'] : Null;
	$txtdocumentoFiltror = trim($txtdocumentoFiltro);
	
	$bxFiltroEstadoValidacion = isset($_POST['bxFiltroEstadoValidacion']) ? $_POST['bxFiltroEstadoValidacion'] : Null;
	$bxFiltroEstadoValidacionr = trim($bxFiltroEstadoValidacion);
	
	$query_inicio = "";
	$query_fin	  = "";
	$query_estado = "";
	$query_estado_validacion = "";
	$query_tipoCasa = "";
	$query_lote = "";
	$query_vendedor = "";
	$query_documento = "";
	
	if(!empty($bxFiltrotxtDesde) && empty($bxFiltrotxtHasta)){
	   $query_inicio = "AND gpv.fecha_venta='$bxFiltrotxtDesde'"; 
	}
	
	if(!empty($bxFiltrotxtDesde) && !empty($bxFiltrotxtHasta)){
	   $query_inicio = "AND gpv.fecha_venta BETWEEN '$bxFiltrotxtDesde' AND '$bxFiltrotxtHasta'"; 
	}
	
	if(empty($bxFiltrotxtDesde) && !empty($bxFiltrotxtHasta)){
	   $query_inicio = "AND gpv.fecha_venta='$bxFiltrotxtHasta'"; 
	}
	


	if(!empty($bxFiltroEstados)){
	    if($bxFiltroEstados=='Devuelto'){
            $query_estado = "AND gpv.devolucion='1'";
	    }else{
	        $query_estado = "AND (gpl.estado='$bxFiltroEstados' OR gpl.bloqueo_estado='$bxFiltroEstados' OR gpl.motivo='$bxFiltroEstados')"; 
	    }
	}
	
	if(!empty($bxFiltroTipoCasas)){
	   $query_tipoCasa = "AND cddx.codigo_item='$bxFiltroTipoCasas'"; 
	}
	
	if(!empty($bxFiltroLotes)){
	   $query_lote = "AND gpl.nombre='$bxFiltroLotes'"; 
	}
	
	if(!empty($bxFiltroVendedores)){
	   $query_vendedor = "AND gpv.id_vendedor='$bxFiltroVendedores'"; 
	}
	
	if(!empty($txtdocumentoFiltror)){
	   $query_documento = "AND dc.documento='$txtdocumentoFiltror'"; 
	}
	
	if(!empty($bxFiltroEstadoValidacionr)){
	   $query_estado_validacion = "AND gpv.estado_devolucion='$bxFiltroEstadoValidacionr'"; 
	}



    $query = mysqli_query($conection,"SELECT 
		gpv.id_venta as id, 
		gpz.nombre as Zona,
		gpm.nombre as Manzana,
		gpl.nombre AS Lote,
		concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as nombreLote,
		gpl.area AS Area, 
		if(gpm.tipo_casa>0,'Si','No') AS Casa,
		if(gpm.tipo_casa>0,cddx.nombre_corto, 'Ninguno') AS TipoCasa,
		gpl.bloqueo_estado as bloqueo,
		cd_estado.nombre_corto AS Estado,
		cd_estado.texto1 as color,
		cdx.nombre_corto as motivo,
		cdx.texto1 as colorMotivo,
		gpl.estado as estado_lote,
		(select if(gpv.id_vendedor>0,(CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1))), 'No asignado') from persona AS per WHERE per.idusuario=gpv.id_vendedor) AS Vendedor,
		gpv.monto_cuota_inicial as CuotaInicial,
		FORMAT(gpl.valor_sin_casa,2) AS ValorTerreno,
		FORMAT(gpl.valor_con_casa - gpl.valor_sin_casa,2) AS ValorCasa,
		FORMAT((gpl.valor_con_casa - gpl.valor_sin_casa)+gpl.valor_sin_casa,2) AS Total,
		if(dc.codigo='','Ninguno',dc.codigo) as codigo_cliente,
		dc.documento as documento,
		CONCAT(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) AS Cliente,
		(select texto1 as nom FROM configuracion_detalle WHERE idconfig_detalle=gpv.tipo_moneda) as Tipomoneda,
		DATE_FORMAT(gpv.fecha_venta , '%d/%m/%Y') AS InicioFecha,
		gpv.devolucion as devolucion,
		cdddx.texto1 as color_devolucion,
		cdddx.nombre_corto as desc_devolucion,
		DATE_FORMAT(gpv.registro_devolucion , '%d/%m/%Y') AS FechaDevolucion
		FROM gp_venta gpv
		INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
		INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
		INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
		INNER JOIN configuracion_detalle AS cd_estado ON cd_estado.codigo_item=gpl.estado AND cd_estado.codigo_tabla='_ESTADO_LOTE'
		INNER JOIN configuracion_detalle AS cdx ON (cdx.codigo_item=gpl.bloqueo_estado OR gpl.bloqueo_estado=0) AND cdx.codigo_tabla='_ESTADO_LOTE'
		INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gpm.tipo_casa AND cddx.codigo_tabla='_TIPO_CASA'
		INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
		INNER JOIN configuracion_detalle AS cdddx ON cdddx.codigo_item=gpv.estado_devolucion AND cdddx.codigo_tabla='_ESTADO_VALIDA_DEVOLUCION'
		WHERE gpv.esta_borrado=0 
		$query_inicio
		$query_estado
		$query_estado_validacion
		$query_tipoCasa
		$query_lote
		$query_vendedor
		$query_documento
	    GROUP BY gpv.id_venta
	    ORDER BY cdddx.texto2 ASC, Cliente ASC"); 
	  
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'zona' => $row['Zona'],
                'manzana' => $row['Manzana'],
                'lote' => $row['Lote'],
                'area' => $row['Area'],
                'casa' => $row['Casa'],
				'tipo_casa' => $row['TipoCasa'],
				'bloqueo' => $row['bloqueo'],
				'estado' => $row['Estado'],
				'estado_lote' => $row['estado_lote'],
				'color' => $row['color'],
				'motivo' => $row['motivo'],
				'colorMotivo' => $row['colorMotivo'], 
				'vendedor' => $row['Vendedor'],
				'cuota_inicial' => $row['CuotaInicial'],
                'valor_terreno' => $row['ValorTerreno'],
                'valor_casa' => $row['ValorCasa'],
                'total' => $row['Total'],
                'codigo_cliente' => $row['codigo_cliente'],
                'documento' => $row['documento'],
                'cliente' => $row['Cliente'],
				'tipo_moneda' => $row['Tipomoneda'],
                'inicio' => $row['InicioFecha'],
                'nombreLote' => $row['nombreLote'],
                'devolucion' => $row['devolucion'],
                'color_devolucion' => $row['color_devolucion'],
                'desc_devolucion' => $row['desc_devolucion'],
                'fecha_devolucion' => $row['FechaDevolucion']
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
