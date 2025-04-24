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
	
	$bxFiltroTipoCasa = isset($_POST['bxFiltroTipoCasa']) ? $_POST['bxFiltroTipoCasa'] : Null;
	$bxFiltroTipoCasas = trim($bxFiltroTipoCasa);
	
	$bxFiltroLote = isset($_POST['bxFiltroLote']) ? $_POST['bxFiltroLote'] : Null;
	$bxFiltroLotes = trim($bxFiltroLote);
	
	$bxFiltroVendedor = isset($_POST['bxFiltroVendedor']) ? $_POST['bxFiltroVendedor'] : Null;
	$bxFiltroVendedores = trim($bxFiltroVendedor);

    $txtdocumentoFiltro = isset($_POST['txtdocumentoFiltro']) ? $_POST['txtdocumentoFiltro'] : Null;
	$txtdocumentoFiltros = trim($txtdocumentoFiltro);
	
	$query_inicio = "";
	$query_fin	  = "";
	$query_estado = "";
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

    if(!empty($txtdocumentoFiltros)){
        $query_documento = "AND dc.documento='$txtdocumentoFiltros'"; 
     }


    $query = mysqli_query($conection,"SELECT 
		gpv.id_venta as id, 
        gpv.fecha_venta as fecha_venta,
        gpz.nombre as zona,
        gpm.nombre as manzana,
        gpl.nombre as lote,
        concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as resumen_lote,
        format(gpl.area, 2) as area_lote,
        if(gpv.tipo_casa>0, 'Si', 'No') as casa,
        cdx.nombre_corto as tipo_casa,
        gpl.estado as estado,
        cddx.nombre_corto as descEstado,
        cddx.texto1 as color_estado,
        gpl.motivo as motivo,
        cdddx.nombre_corto as descMotivo,
        cdddx.texto1 as color_motivo,
        CONCAT(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) AS vendedor,
        format(gpv.monto_cuota_inicial, 2) as cuota_inicial,
        format(gpl.valor_sin_casa,2) as valor_terreno,
        format((gpl.valor_con_casa - gpl.valor_sin_casa),2) as valor_casa,
        format(gpl.valor_con_casa,2) as total,
        CONCAT(SUBSTRING_INDEX(dc.nombres,' ',1),' ',dc.apellido_paterno,' ',dc.apellido_materno) as cliente,
        cddddx.texto1 as tipo_moneda
        FROM gp_venta gpv
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gpv.tipo_casa AND cdx.codigo_tabla='_TIPO_CASA'
        INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gpl.estado AND cddx.codigo_tabla='_ESTADO_LOTE'
        INNER JOIN configuracion_detalle AS cdddx ON (cdddx.codigo_item=gpl.motivo OR gpl.motivo=0) AND cdddx.codigo_tabla='_ESTADO_LOTE'
        INNER JOIN persona AS per ON per.idusuario=gpv.id_vendedor
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente 
        INNER JOIN configuracion_detalle AS cddddx ON cddddx.idconfig_detalle=gpv.tipo_moneda AND cddddx.codigo_tabla='_TIPO_MONEDA'
		WHERE gpv.esta_borrado=0 AND gpv.devolucion='1'
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
                'resumen_lote' => $row['resumen_lote'],
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
                'cliente' => $row['cliente'],
				'tipo_moneda' => $row['tipo_moneda'],
                'fecha_venta' => $row['fecha_venta']
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
