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
    
    $fechas = new DateTime();
    $fechas->modify('first day of this month');
    $primer_dia = $fechas->format('Y-m-d');
    
    $fechas = new DateTime();
    $fechas->modify('last day of this month');
    $ultimo_dia = $fechas->format('Y-m-d'); 
    
    $data['status'] = 'ok';
    $data['primero'] = $primer_dia;
    $data['ultimo'] = $ultimo_dia;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}   
   

if(!empty($_POST['ReturnListaResevas'])){

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
	
	$txtDocumentoFiltro = isset($_POST['txtDocumentoFiltro']) ? $_POST['txtDocumentoFiltro'] : Null;
	$txtDocumentoFiltros = trim($txtDocumentoFiltro);
	
	$query_inicio = "";
	$query_fin	  = "";
	$query_estado = "";
	$query_tipoCasa = "";
	$query_lote = "";
	$query_vendedor = "";
	$query_documento="";
	
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
	   $query_estado = "AND (gpl.estado='$bxFiltroEstados' OR gpl.bloqueo_estado='$bxFiltroEstados' OR gpl.motivo='$bxFiltroEstados')"; 
	}
	
	if(!empty($bxFiltroTipoCasas)){
	   $query_tipoCasa = "AND gpv.tipo_casa='$bxFiltroTipoCasas'"; 
	}
	
	if(!empty($bxFiltroLotes)){
	   $query_lote = "AND gpl.nombre='$bxFiltroLotes'"; 
	}
	
	if(!empty($bxFiltroVendedores)){
	   $query_vendedor = "AND gpv.id_vendedor='$bxFiltroVendedores'"; 
	}
	
	if(!empty($txtDocumentoFiltros)){
	   $query_documento = "AND dc.documento='$txtDocumentoFiltros'"; 
	}


    $query = mysqli_query($conection,"SELECT 
		gpv.id_venta as id, 
        gpv.fecha_venta as fecha_venta,
        gpz.nombre as zona,
        gpm.nombre as manzana,
        gpl.nombre as lote,
        format(gpl.area, 2) as area_lote,
        if(gpv.tipo_casa>0, 'Si', 'No') as casa,
        cdx.nombre_corto as tipo_casa,
        gpl.estado as estado,
        cddx.nombre_corto as descEstado,
        cddx.texto1 as color_estado,
        gpl.motivo as motivo,
        cdddx.nombre_corto as descMotivo,
        cdddx.texto1 as color_motivo,
        if(gpv.id_vendedor>0,(select CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) from persona per where per.idusuario=gpv.id_vendedor),'Ninguno') AS vendedor,
        format(gpv.monto_cuota_inicial, 2) as cuota_inicial,
        format(gpl.valor_sin_casa,2) as valor_terreno,
        format((gpl.valor_con_casa - gpl.valor_sin_casa),2) as valor_casa,
        format(gpl.valor_con_casa,2) as total,
        dc.documento as documento,
        CONCAT(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
        cddddx.texto1 as tipo_moneda,
        
        format(gpv.total,2) as precio_pactado,
        format((gpv.total*(gpv.tna/100)),2) as interes,
        (select format(sum(gpcr.monto_letra),2) from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.esta_borrado='0') as total_lote,
        if((gpv.cantidad_letra/12)>0, concat(format((gpv.cantidad_letra/12),0),' AÑOS (',gpv.cantidad_letra,' MESES)'), concat(gpv.cantidad_letra,' MESES')) as financiamiento,
        format(((select sum(gpcr.monto_letra) from gp_cronograma gpcr WHERE gpcr.id_venta=gpv.id_venta AND gpcr.esta_borrado='0') - gpv.total),2) intereses
        
        FROM gp_venta gpv
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gpv.tipo_casa AND cdx.codigo_tabla='_TIPO_CASA'
        INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gpl.estado AND cddx.codigo_tabla='_ESTADO_LOTE'
        INNER JOIN configuracion_detalle AS cdddx ON (cdddx.codigo_item=gpl.motivo OR gpl.motivo=0) AND cdddx.codigo_tabla='_ESTADO_LOTE'
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente 
        INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gpv.tipo_moneda AND cddddx.codigo_tabla='_TIPO_MONEDA'
		WHERE gpv.esta_borrado=0 AND gpl.estado In ('2', '6', '5') AND gpv.devolucion='0'
		$query_inicio
		$query_estado
		$query_tipoCasa
		$query_lote
		$query_vendedor
		$query_documento
	    GROUP BY gpv.id_venta
        ORDER BY gpv.fecha_venta ASC
	"); 
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'zona' => $row['zona'],
                'manzana' => $row['manzana'],
                'lote' => $row['lote'],
                'area_lote' => $row['area_lote'],
                'casa' => $row['casa'],
				'tipo_casa' => $row['tipo_casa'],
				'estado' => $row['estado'],
				'descEstado' => $row['descEstado'],
				'color_estado' => $row['color_estado'],
				'motivo' => $row['motivo'],
				'descMotivo' => $row['descMotivo'],
				'color_motivo' => $row['color_motivo'], 
				'vendedor' => $row['vendedor'],
				'cuota_inicial' => $row['cuota_inicial'],
                'valor_terreno' => $row['valor_terreno'],
                'valor_casa' => $row['valor_terreno'],
                'total' => $row['total'],
                'documento' => $row['documento'],
                'cliente' => $row['cliente'],
				'tipo_moneda' => $row['tipo_moneda'],
                'fecha_venta' => $row['fecha_venta'],
                
                'precio_pactado' => $row['precio_pactado'],
                'interes' => $row['intereses'],
                'total_lote' => $row['total_lote'],
                'financiamiento' => $row['financiamiento']
                
                //'fin' => $row['FinFecha']
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
        //$data['recordsTotal'] = 0;
            //$data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}
