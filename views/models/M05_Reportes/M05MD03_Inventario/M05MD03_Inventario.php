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

if(isset($_POST['btnListarLotesInventario'])){

	$bxFiltroProyectoInventario = isset($_POST['bxFiltroProyectoInventario']) ? $_POST['bxFiltroProyectoInventario'] : Null;
	$bxFiltroProyectoInventarior = trim($bxFiltroProyectoInventario);
	
	$bxFiltroZonaInventario = isset($_POST['bxFiltroZonaInventario']) ? $_POST['bxFiltroZonaInventario'] : Null;
	$bxFiltroZonaInventarior = trim($bxFiltroZonaInventario);
	
	$bxFiltroManzanaInventario = isset($_POST['bxFiltroManzanaInventario']) ? $_POST['bxFiltroManzanaInventario'] : Null;
	$bxFiltroManzanaInventario = trim($bxFiltroManzanaInventario);
	
	$bxFiltroLoteInventario = isset($_POST['bxFiltroLoteInventario']) ? $_POST['bxFiltroLoteInventario'] : Null;
	$bxFiltroLoteInventarior = trim($bxFiltroLoteInventario);
	
	$bxFiltroEstadoInventario = isset($_POST['bxFiltroEstadoInventario']) ? $_POST['bxFiltroEstadoInventario'] : Null;
	$bxFiltroEstadoInventarior = trim($bxFiltroEstadoInventario);
	
	$bxFiltroMotivoInventario = isset($_POST['bxFiltroMotivoInventario']) ? $_POST['bxFiltroMotivoInventario'] : Null;
	$bxFiltroMotivoInventarior = trim($bxFiltroMotivoInventario);
	
	$bxFiltroVendedorInventario = isset($_POST['bxFiltroVendedorInventario']) ? $_POST['bxFiltroVendedorInventario'] : Null;
	$bxFiltroVendedorInventarior = trim($bxFiltroVendedorInventario);
	
	$query_proyecto = "";
	$query_zona	  = "";
	$query_manzana = "";
	$query_lote = "";
	$query_estado = "";
	$query_motivo = "";
	$query_vendedor = "";
	
	
	
	if(!empty($bxFiltroProyectoInventarior)){
	   $query_proyecto = "AND gpp.idproyecto='$bxFiltroProyectoInventarior'"; 
	}
	
	if(!empty($bxFiltroZonaInventarior)){
	   $query_zona = "AND gpz.idzona='$bxFiltroZonaInventarior'"; 
	}
	
	if(!empty($bxFiltroManzanaInventario)){
	   $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaInventario'"; 
	}
	
	if(!empty($bxFiltroLoteInventarior)){
	   $query_lote = "AND gpl.idlote='$bxFiltroLoteInventarior'"; 
	}
	
	if(!empty($bxFiltroEstadoInventarior)){
	   $query_estado = "AND gpl.estado='$bxFiltroEstadoInventarior'"; 
	}
	
	if(!empty($bxFiltroMotivoInventarior)){
	    if($bxFiltroMotivoInventarior=="ninguno"){
	        $query_motivo = "AND gpl.motivo='0' AND gpl.bloqueo_estado='0'"; 
	    }else{
    	    if($bxFiltroMotivoInventarior=='7'){
    	        $query_motivo = "AND gpl.bloqueo_estado='$bxFiltroMotivoInventarior'"; 
    	    }else{
    	        $query_motivo = "AND gpl.motivo='$bxFiltroMotivoInventarior'"; 
    	    }
	    }
	}else{
	    if($bxFiltroMotivoInventarior=="ninguno"){
	        $query_motivo = "AND gpl.motivo='0' AND gpl.bloqueo_estado='0'"; 
	    }
	}
	
	if(!empty($bxFiltroVendedorInventarior)){
	   $query_vendedor = "AND gpr.id_usuario_crea='$bxFiltroVendedorInventarior'"; 
	}
	

		$query = mysqli_query($conection,"SELECT ROW_NUMBER() OVER(
            ORDER BY gpl.idlote) AS fila, 
			gpl.idlote as id,
			gpz.nombre as zona,
			concat('Mz. ',SUBSTRING(gpm.nombre,9,2)) as manzana,
			concat('Lt. ',SUBSTRING(gpl.nombre,6,2)) as lote,
			format(gpl.area,2) as area,
			cd.nombre_corto as tipo_casa,
			format(gpl.valor_sin_casa,2) as valor_terreno,
			format(gpl.valor_con_casa,2) as valor_casa,
			if((select total from gp_venta where id_lote=gpl.idlote AND devolucion='0')>0, (select format(gpv.total, 2) from gp_venta gpv where gpv.id_lote=gpl.idlote AND gpv.devolucion='0'), format(gpl.valor_con_casa,2)) as valor_venta,
			CONCAT(cd_estado.nombre_corto,'',if(gpl.bloqueo_estado>0,
			CONCAT('(',(SELECT cnf.nombre_corto FROM configuracion_detalle cnf 
			WHERE cnf.codigo_item=gpl.bloqueo_estado AND cnf.codigo_tabla='_ESTADO_LOTE'),')'),'')) AS Estado,
			cddx.nombre_corto as descEstado,
			cdddx.nombre_corto as descMotivo,
			if(cdddx.nombre_corto='',cddx.nombre_corto, concat(cddx.nombre_corto,' (',cdddx.nombre_corto,')')) as estado_reporte,
			cddx.texto1 as colorEstado,
			cdddx.texto1 as colorMotivo,
			if(gpl.estado=1,'Si','No') as disponibilidad,
			if((select count(id_venta) from gp_venta where id_lote=gpl.idlote AND devolucion='0')>0, (select concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) from gp_venta gpv, datos_cliente dc where gpv.id_cliente=dc.id AND gpv.id_lote=gpl.idlote AND gpv.devolucion='0'),'NINGUNO') as propietario,
			if((select count(id_venta) from gp_venta where id_lote=gpl.idlote AND devolucion='0')>0, (select concat(SUBSTRING_INDEX(per.nombre,' ',1),' ',SUBSTRING_INDEX(per.apellido,' ',1)) from gp_venta gpv, persona per where gpv.id_vendedor=per.idusuario AND gpv.id_lote=gpl.idlote AND gpv.devolucion='0'),'NINGUNO') as vendedor,
			gpl.bloqueo_estado as bloqueo,
			cddddx.nombre_corto as tipo_moneda
			FROM gp_lote gpl
			INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana 
			INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona 
			INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
			INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpm.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
			INNER JOIN configuracion_detalle AS cd_estado ON cd_estado.codigo_item=gpl.estado AND cd_estado.codigo_tabla='_ESTADO_LOTE'
			INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gpl.estado AND cddx.codigo_tabla='_ESTADO_LOTE'
			INNER JOIN configuracion_detalle AS cdddx ON (cdddx.codigo_item=gpl.bloqueo_estado OR gpl.bloqueo_estado=0) AND cdddx.codigo_tabla='_ESTADO_LOTE' 
			INNER JOIN configuracion_detalle AS cddddx ON cddddx.codigo_item=gpl.tipo_moneda AND cddddx.codigo_tabla='_TIPO_MONEDA'
			WHERE gpl.esta_borrado=0
			$query_proyecto
			$query_zona
			$query_manzana
			$query_lote
			$query_estado
			$query_motivo
			GROUP BY gpl.idlote
			"); 	
	
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'fila' => $row['fila'],
                'id' => $row['id'],
                'zona' => $row['zona'],
                'manzana' => $row['manzana'],
                'lote' => $row['lote'],
                'area' => $row['area'],
				'tipo_casa' => $row['tipo_casa'],
				'valor_terreno' => $row['valor_terreno'],
				'valor_casa' => $row['valor_casa'],
				'valor_venta' => $row['valor_venta'],
				'tipo_moneda' => $row['tipo_moneda'],
				'Estado' => $row['Estado'],
                'descEstado' => $row['descEstado'],
				'descMotivo' => $row['descMotivo'],
				'colorEstado' => $row['colorEstado'],
				'colorMotivo' => $row['colorMotivo'],
				'disponibilidad' => $row['disponibilidad'],
				'propietario' => $row['propietario'],
				'bloqueo' => $row['bloqueo'],
                'vendedor' => $row['vendedor'],
                'estado_reporte' => $row['estado_reporte']
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

if(isset($_POST['IndicadoresInventario'])){
/*
	$bxFiltroProyectoInventario = isset($_POST['bxFiltroProyectoInventario']) ? $_POST['bxFiltroProyectoInventario'] : Null;
	$bxFiltroProyectoInventarior = trim($bxFiltroProyectoInventario);*/

	$query = mysqli_query($conection,"SELECT gpp.nombre,
    (SELECT count(gpz.idzona) FROM gp_zona gpz WHERE gpz.idproyecto=gpp.idproyecto) as totZonas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz WHERE gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totManzanas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLotes,
    (SELECT count(dc.id) FROM datos_cliente dc WHERE dc.esta_borrado=0) as totClientes,
    (SELECT count(gpv.id_venta) FROM gp_venta gpv WHERE esta_borrado=0) as totVentas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='1' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLibres,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='2' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totReservados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='3' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totPorVencer,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='4' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVencidos,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='5' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosT,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='6' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosTC,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totBloqueados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='8' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totCanjes,
    gpp.nombre as nombre_proyecto,
    format(gpp.area,2) as area_proyecto,
    gpp.inicio_proyecto as inicio_proyecto,
    gpp.direccion as direccion_proyecto,
    gpp.departamento as departamento_proyecto,
    gpp.provincia as provincia_proyecto,
    gpp.distrito as distrito_proyecto
    FROM gp_proyecto gpp
    WHERE gpp.idproyecto=1"); 

	
     if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurri贸 un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if (isset($_POST['ListarMotivos'])) {

        $bxFiltroEstadoInventario = $_POST['bxFiltroEstadoInventario'];
        if($bxFiltroEstadoInventario==1){
            array_push($dataList, [
                'valor' => 'ninguno',
                'texto' => 'NINGUNO',
            ],[
                'valor' => '7',
                'texto' => 'BLOQUEADO',
            ],[
                'valor' => '',
                'texto' => 'TODOS',
            ]);
        }else{
            if($bxFiltroEstadoInventario==2){
                array_push($dataList, [
                    'valor' => 'ninguno',
                    'texto' => 'NINGUNO',
                ],[
                    'valor' => '7',
                    'texto' => 'BLOQUEADO',
                ],[
                    'valor' => '3',
                    'texto' => 'POR VENCER',
                ],[
                    'valor' => '4',
                    'texto' => 'VENCIDO',
                ],[
                    'valor' => '',
                    'texto' => 'TODOS',
                ]);
            }else{
                if($bxFiltroEstadoInventario==5 || $bxFiltroEstadoInventario==6){
                    array_push($dataList, [
                        'valor' => 'ninguno',
                        'texto' => 'NINGUNO',
                    ],[
                        'valor' => '7',
                        'texto' => 'BLOQUEADO',
                    ],[
                        'valor' => '8',
                        'texto' => 'CANJE',
                    ],[
                        'valor' => '',
                        'texto' => 'TODOS',
                    ]);
                }else{
                    array_push($dataList, [
                        'valor' => 'ninguno',
                        'texto' => 'NINGUNO',
                    ],[
                        'valor' => '7',
                        'texto' => 'BLOQUEADO',
                    ],[
                        'valor' => '8',
                        'texto' => 'CANJE',
                    ],[
                        'valor' => '3',
                        'texto' => 'POR VENCER',
                    ],[
                        'valor' => '4',
                        'texto' => 'VENCIDO',
                    ],[
                        'valor' => '',
                        'texto' => 'TODOS',
                    ]);
                }
            }
        }

     
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
      
    }