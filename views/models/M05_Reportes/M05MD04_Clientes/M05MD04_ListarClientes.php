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

if(isset($_POST['ReturnListaClientes'])){
	
	$txtdocumentoFiltro = isset($_POST['txtdocumentoFiltro']) ? $_POST['txtdocumentoFiltro'] : Null;
	$bxFiltrodocumento = trim($txtdocumentoFiltro);
	
	$txtNombresFiltro = isset($_POST['txtNombresFiltro']) ? $_POST['txtNombresFiltro'] : Null;
	$txtNombresFiltros = trim($txtNombresFiltro);
	
	$txtApellidoFiltro = isset($_POST['txtApellidoFiltro']) ? $_POST['txtApellidoFiltro'] : Null;
	$bxFiltroApellidos  = trim($txtApellidoFiltro);
	
	$bxFiltroProyectoPropietarios = isset($_POST['bxFiltroProyectoPropietarios']) ? $_POST['bxFiltroProyectoPropietarios'] : Null;
	$bxFiltroProyectoPropietarioss  = trim($bxFiltroProyectoPropietarios);


    $bxFiltroZonaPropietarios = isset($_POST['bxFiltroZonaPropietarios']) ? $_POST['bxFiltroZonaPropietarios'] : Null;
	$bxFiltroZonaPropietarioss  = trim($bxFiltroZonaPropietarios);
	
	$bxFiltroManzanaPropietarios = isset($_POST['bxFiltroManzanaPropietarios']) ? $_POST['bxFiltroManzanaPropietarios'] : Null;
	$bxFiltroManzanaPropietarioss  = trim($bxFiltroManzanaPropietarios);


    $bxFiltroLotePropietarios = isset($_POST['bxFiltroLotePropietarios']) ? $_POST['bxFiltroLotePropietarios'] : Null;
	$bxFiltroLotePropietarioss  = trim($bxFiltroLotePropietarios);
	
	$bxFiltroEstadoPropietarios = isset($_POST['bxFiltroEstadoPropietarios']) ? $_POST['bxFiltroEstadoPropietarios'] : Null;
	$bxFiltroEstadoPropietarioss  = trim($bxFiltroEstadoPropietarios);


	$query_documento = "";
	$query_nombres = "";
	$query_apellido = "";
	$query_proyecto = "";
	$query_zona = "";
	$query_manzana = "";
	$query_lote = "";
	$query_estado = "";
	
	if(!empty($bxFiltrodocumento)){
	   $query_documento = "AND dc.documento='$bxFiltrodocumento'"; 
	}
	
	if(!empty($txtNombresFiltros)){
	   $query_nombres = "AND dc.nombres like '%$txtNombresFiltros%'"; 
	}
	if(!empty($bxFiltroApellidos)){

		//$query_apellido = "AND ((dc.apellido_paterno,' ',dc.apellido_materno) like concat('%',$bxFiltroApellidos,'%'))"; 
		$query_apellido = "AND(dc.apellido_paterno like concat('%$bxFiltroApellidos%') OR dc.apellido_materno like concat('%$bxFiltroApellidos%'))"; 
	}
	
	if(!empty($bxFiltroProyectoPropietarioss)){
	    $query_proyecto = "AND gpp.idproyecto='$bxFiltroProyectoPropietarioss'";
	}
	
	if(!empty($bxFiltroZonaPropietarioss)){
	    $query_zona = "AND gpz.idzona='$bxFiltroZonaPropietarioss'";
	}
	
	
	if(!empty($bxFiltroManzanaPropietarioss)){
	    $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaPropietarioss'";
	}
	
	
	if(!empty($bxFiltroLotePropietarioss)){
	    $query_lote = "AND gpl.idlote='$bxFiltroLotePropietarioss'";
	}
	
	
	if(!empty($bxFiltroEstadoPropietarioss)){
	    if($bxFiltroEstadoPropietarioss=='d'){
	       $query_estado = "AND gpv.devolucion='1'"; 
	    }else{
    	    if($bxFiltroEstadoPropietarioss==8){
    	        $query_estado = "AND gpl.motivo='$bxFiltroEstadoPropietarioss'";
    	    }else{
    	        $query_estado = "AND gpl.estado='$bxFiltroEstadoPropietarioss'";
    	    }
	    }
	}
	
	
	
    $query = mysqli_query($conection,"SELECT ROW_NUMBER() OVER(
    ORDER BY gpv.id_lote) AS fila, 
    gpl.idlote as id,
	dc.documento as documento,
	dc.nombres as nombres,
	concat(dc.apellido_paterno,' ',dc.apellido_materno) as apellidos,
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
	gpl.estado as estado,
    cd.nombre_corto as descEstado,
    cd.texto1 as color1,
    gpl.motivo as motivo,
    cdx.nombre_corto as descMotivo,
    cdx.texto1 as color2,
    if(gpv.devolucion='0',cd.nombre_corto,concat(cd.nombre_corto,' ( DEVOLUCIÓN )')) as estado_reporte,
    dc.celular_1 as celular,
    dc.email as correo,
    cddd.nombre_corto as nacionalidad,
    gpv.devolucion as devolucion
	FROM gp_venta gpv
	INNER JOIN gp_lote AS gpl ON gpv.id_lote=gpl.idlote
	INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona as gpz ON gpz.idzona=gpm.idzona
    INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
    INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpl.estado AND cd.codigo_tabla='_ESTADO_LOTE'
    INNER JOIN configuracion_detalle AS cdx ON (cdx.codigo_item=gpl.motivo OR gpl.motivo=0) AND cdx.codigo_tabla='_ESTADO_LOTE'
    INNER JOIN configuracion_detalle AS cddd ON cddd.idconfig_detalle=dc.nacionalidad AND cddd.codigo_tabla='_NACIONALIDAD'
	WHERE gpl.esta_borrado=0  
	$query_documento
	$query_proyecto
	$query_zona
	$query_manzana
	$query_lote
	$query_estado
	GROUP BY gpv.id_venta
	ORDER BY gpv.id_lote
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'fila' => $row['fila'],
                'id' => $row['id'],
                'documento' => $row['documento'],
                'nombres' => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'lote' => $row['lote'],
                'estado' => $row['estado'],
                'descEstado' => $row['descEstado'],
                'color1' => $row['color1'],
                'motivo' => $row['motivo'],
                'descMotivo' => $row['descMotivo'],
                'color2' => $row['color2'],
                'celular' => $row['celular'],
                'email' => $row['correo'],
				'nacionalidad' => $row['nacionalidad'],
				'devolucion' => $row['devolucion'],
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
