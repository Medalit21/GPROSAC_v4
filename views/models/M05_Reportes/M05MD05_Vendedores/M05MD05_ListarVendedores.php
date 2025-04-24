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
	$bxFiltroNombres = trim($txtNombresFiltro);
	
	$txtApellidoFiltro = isset($_POST['txtApellidoFiltro']) ? $_POST['txtApellidoFiltro'] : Null;
	$bxFiltroApellidos  = trim($txtApellidoFiltro);
	
	$bxFiltroPeriodo = isset($_POST['bxFiltroPeriodo']) ? $_POST['bxFiltroPeriodo'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo);

	$query_documento = "";
	$query_nombres = "";
	$query_apellido = "";
	
	if(!empty($bxFiltrodocumento)){
	   $query_documento = "AND gpp.DNI='$bxFiltrodocumento'"; 
	}
	
	if(!empty($bxFiltroNombres)){
	   $query_nombres = "AND gpp.nombre like '%$bxFiltroNombres%'"; 
	}
	
	if(!empty($bxFiltroApellidos)){
	   $query_apellido = "AND gpp.apellido like '%$bxFiltroApellidos%'"; 
	}
	
	
    $query = mysqli_query($conection,"SELECT 
		gpp.idpersona as id,
		concat(gpp.DNI,' - ',concat(gpp.nombre,' ',gpp.apellido)) as vendedor,
		gpp.Telefono as Telefono,
		gpp.DNI as DNI,
		cd.nombre_corto as genero,
		cdd.nombre_corto as area,
		gpp.direccion as direccion,
		(SELECT if(COUNT(gpv1.total)>0,FORMAT(SUM(gpv1.total),2),'0') FROM gp_venta gpv1 WHERE gpv1.id_vendedor=gpp.idusuario AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos') as monto_enero,
		(SELECT if(COUNT(gpv2.total)>0,FORMAT(SUM(gpv2.total),2),'0')  FROM gp_venta gpv2 WHERE gpv2.id_vendedor=gpp.idusuario AND MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos') as monto_febrero,
		(SELECT if(COUNT(gpv3.total)>0,FORMAT(SUM(gpv3.total),2),'0')  FROM gp_venta gpv3 WHERE gpv3.id_vendedor=gpp.idusuario AND MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos') as monto_marzo,
		(SELECT if(COUNT(gpv4.total)>0,FORMAT(SUM(gpv4.total),2),'0')  FROM gp_venta gpv4 WHERE gpv4.id_vendedor=gpp.idusuario AND MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos') as monto_abril,
		(SELECT if(COUNT(gpv5.total)>0,FORMAT(SUM(gpv5.total),2),'0')  FROM gp_venta gpv5 WHERE gpv5.id_vendedor=gpp.idusuario AND MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos') as monto_mayo,
		(SELECT if(COUNT(gpv6.total)>0,FORMAT(SUM(gpv6.total),2),'0')  FROM gp_venta gpv6 WHERE gpv6.id_vendedor=gpp.idusuario AND MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos') as monto_junio,
		(SELECT if(COUNT(gpv7.total)>0,FORMAT(SUM(gpv7.total),2),'0')  FROM gp_venta gpv7 WHERE gpv7.id_vendedor=gpp.idusuario AND MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos') as monto_julio,
		(SELECT if(COUNT(gpv8.total)>0,FORMAT(SUM(gpv8.total),2),'0')  FROM gp_venta gpv8 WHERE gpv8.id_vendedor=gpp.idusuario AND MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos') as monto_agosto,
		(SELECT if(COUNT(gpv9.total)>0,FORMAT(SUM(gpv9.total),2),'0')  FROM gp_venta gpv9 WHERE gpv9.id_vendedor=gpp.idusuario AND MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos') as monto_septiembre,
		(SELECT if(COUNT(gpv10.total)>0,FORMAT(SUM(gpv10.total),2),'0')  FROM gp_venta gpv10 WHERE gpv10.id_vendedor=gpp.idusuario AND MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos') as monto_octubre,
		(SELECT if(COUNT(gpv11.total)>0,FORMAT(SUM(gpv11.total),2),'0')  FROM gp_venta gpv11 WHERE gpv11.id_vendedor=gpp.idusuario AND MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos') as monto_noviembre,
		(SELECT if(COUNT(gpv12.total)>0,FORMAT(SUM(gpv12.total),2),'0')  FROM gp_venta gpv12 WHERE gpv12.id_vendedor=gpp.idusuario AND MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos') as monto_diciembre,
		FORMAT((
		(SELECT if(COUNT(gpv1.total)>0,SUM(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.id_vendedor=gpp.idusuario AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv2.total)>0,SUM(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.id_vendedor=gpp.idusuario AND MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv3.total)>0,SUM(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.id_vendedor=gpp.idusuario AND MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv4.total)>0,SUM(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.id_vendedor=gpp.idusuario AND MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv5.total)>0,SUM(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.id_vendedor=gpp.idusuario AND MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv6.total)>0,SUM(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.id_vendedor=gpp.idusuario AND MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv7.total)>0,SUM(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.id_vendedor=gpp.idusuario AND MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv8.total)>0,SUM(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.id_vendedor=gpp.idusuario AND MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv9.total)>0,SUM(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.id_vendedor=gpp.idusuario AND MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv10.total)>0,SUM(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.id_vendedor=gpp.idusuario AND MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv11.total)>0,SUM(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.id_vendedor=gpp.idusuario AND MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv12.total)>0,SUM(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.id_vendedor=gpp.idusuario AND MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos')),2) as total
		FROM persona gpp
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpp.idsexo AND cd.codigo_tabla='_GENERO'
		INNER JOIN configuracion_detalle AS cdd ON cdd.codigo_item=gpp.idArea AND cdd.codigo_tabla='_AREA'
		WHERE gpp.estatus='Activo' AND gpp.idArea='4' AND gpp.idCargo='3'
		$query_documento
		$query_nombres
		$query_apellido
		ORDER BY gpp.nombre
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'vendedor' => $row['vendedor'],
                'Telefono' => $row['Telefono'],
                'DNI' => $row['DNI'],
                'genero' => $row['genero'],
                'area' => $row['area'],
				'direccion' => $row['direccion'],
				'monto_enero' => $row['monto_enero'],
				'monto_febrero' => $row['monto_febrero'],
				'monto_marzo' => $row['monto_marzo'],
				'monto_abril' => $row['monto_abril'],
				'monto_mayo' => $row['monto_mayo'],
				'monto_junio' => $row['monto_junio'],
				'monto_julio' => $row['monto_julio'],
				'monto_agosto' => $row['monto_agosto'],
				'monto_septiembre' => $row['monto_septiembre'],
				'monto_octubre' => $row['monto_octubre'],
				'monto_noviembre' => $row['monto_noviembre'],
				'monto_diciembre' => $row['monto_diciembre'],
				'total' => $row['total']
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


if(isset($_POST['ReturnListaClientesConteo'])){
	
	$txtdocumentoFiltro = isset($_POST['txtdocumentoFiltro']) ? $_POST['txtdocumentoFiltro'] : Null;
	$bxFiltrodocumento = trim($txtdocumentoFiltro);
	
	$txtNombresFiltro = isset($_POST['txtNombresFiltro']) ? $_POST['txtNombresFiltro'] : Null;
	$bxFiltroNombres = trim($txtNombresFiltro);
	
	$txtApellidoFiltro = isset($_POST['txtApellidoFiltro']) ? $_POST['txtApellidoFiltro'] : Null;
	$bxFiltroApellidos  = trim($txtApellidoFiltro);
	
	$bxFiltroPeriodo = isset($_POST['bxFiltroPeriodo']) ? $_POST['bxFiltroPeriodo'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo);

	$query_documento = "";
	$query_nombres = "";
	$query_apellido = "";
	
	if(!empty($bxFiltrodocumento)){
	   $query_documento = "AND gpp.DNI like '%$bxFiltrodocumento%'"; 
	}
	
	if(!empty($bxFiltroNombres)){
	   $query_nombres = "AND gpp.nombre like '%$bxFiltroNombres%'"; 
	}
	
	if(!empty($bxFiltroApellidos)){
	   $query_apellido = "AND gpp.apellido like '%$bxFiltroApellidos%'"; 
	}
	
	
    $query = mysqli_query($conection,"SELECT 
		gpp.idpersona as id,
		concat(gpp.DNI,' - ',concat(gpp.nombre,' ',gpp.apellido)) as vendedor,
		gpp.Telefono as Telefono,
		gpp.DNI as DNI,
		cd.nombre_corto as genero,
		cdd.nombre_corto as area,
		gpp.direccion as direccion,
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.id_vendedor=gpp.idusuario AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos') as monto_enero,
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.id_vendedor=gpp.idusuario AND MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos') as monto_febrero,
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.id_vendedor=gpp.idusuario AND MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos') as monto_marzo,
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.id_vendedor=gpp.idusuario AND MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos') as monto_abril,
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.id_vendedor=gpp.idusuario AND MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos') as monto_mayo,
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.id_vendedor=gpp.idusuario AND MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos') as monto_junio,
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.id_vendedor=gpp.idusuario AND MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos') as monto_julio,
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.id_vendedor=gpp.idusuario AND MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos') as monto_agosto,
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.id_vendedor=gpp.idusuario AND MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos') as monto_septiembre,
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.id_vendedor=gpp.idusuario AND MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos') as monto_octubre,
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.id_vendedor=gpp.idusuario AND MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos') as monto_noviembre,
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.id_vendedor=gpp.idusuario AND MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos') as monto_diciembre,
		(
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.id_vendedor=gpp.idusuario AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.id_vendedor=gpp.idusuario AND MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.id_vendedor=gpp.idusuario AND MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.id_vendedor=gpp.idusuario AND MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.id_vendedor=gpp.idusuario AND MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.id_vendedor=gpp.idusuario AND MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.id_vendedor=gpp.idusuario AND MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.id_vendedor=gpp.idusuario AND MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.id_vendedor=gpp.idusuario AND MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.id_vendedor=gpp.idusuario AND MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.id_vendedor=gpp.idusuario AND MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.id_vendedor=gpp.idusuario AND MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos')) as total
		FROM persona gpp
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpp.idsexo AND cd.codigo_tabla='_GENERO'
		INNER JOIN configuracion_detalle AS cdd ON cdd.codigo_item=gpp.idArea AND cdd.codigo_tabla='_AREA'
		WHERE gpp.estatus='Activo' AND gpp.idArea='4' AND gpp.idCargo='3'
		AND idArea = 4
		$query_documento
		$query_nombres
		$query_apellido
		ORDER BY gpp.nombre
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'vendedor' => $row['vendedor'],
                'Telefono' => $row['Telefono'],
                'DNI' => $row['DNI'],
                'genero' => $row['genero'],
                'area' => $row['area'],
				'direccion' => $row['direccion'],
				'monto_enero' => $row['monto_enero'],
				'monto_febrero' => $row['monto_febrero'],
				'monto_marzo' => $row['monto_marzo'],
				'monto_abril' => $row['monto_abril'],
				'monto_mayo' => $row['monto_mayo'],
				'monto_junio' => $row['monto_junio'],
				'monto_julio' => $row['monto_julio'],
				'monto_agosto' => $row['monto_agosto'],
				'monto_septiembre' => $row['monto_septiembre'],
				'monto_octubre' => $row['monto_octubre'],
				'monto_noviembre' => $row['monto_noviembre'],
				'monto_diciembre' => $row['monto_diciembre'],
				'total' => $row['total']
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
