<?php
   session_start();
   //setlocale(LC_MONETARY, 'en_US');
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";
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
	   $query_documento = "AND gpp.DNI like '%$bxFiltrodocumento%'"; 
	}
	
	if(!empty($bxFiltroNombres)){
	   $query_nombres = "AND gpp.nombre like '%$bxFiltroNombres%'"; 
	}
	
	if(!empty($bxFiltroApellidos)){
	   $query_apellido = "AND gpp.apellido like '%$bxFiltroApellidos%'"; 
	}
	
	
    $query = mysqli_query($conection,"SELECT 
		cdd.nombre_corto as tipo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0.00') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_enero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0.00')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_febrero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0.00')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_marzo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0.00')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_abril,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0.00')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_mayo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0.00')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_junio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0.00')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_julio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0.00')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_agosto,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0.00')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_septiembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0.00')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_octubre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0.00')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_noviembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0.00')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_diciembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(
		(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0.00') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0.00')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0.00')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0.00')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0.00')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0.00')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0.00')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0.00')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0.00')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0.00')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0.00')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0.00')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item)), '0.00') as total
		FROM configuracion_detalle cdd
        WHERE cdd.codigo_tabla='_TIPO_INMUEBLE'
		
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
				'monto_enero' => money_format('%i',$row['monto_enero']),
				'monto_febrero' => money_format('%i',$row['monto_febrero']),
				'monto_marzo' => money_format('%i',$row['monto_marzo']),
				'monto_abril' => money_format('%i',$row['monto_abril']),
				'monto_mayo' => money_format('%i',$row['monto_mayo']),
				'monto_junio' => money_format('%i',$row['monto_junio']),
				'monto_julio' => money_format('%i',$row['monto_julio']),
				'monto_agosto' => money_format('%i',$row['monto_agosto']),
				'monto_septiembre' => money_format('%i',$row['monto_septiembre']),
				'monto_octubre' => money_format('%i',$row['monto_octubre']),
				'monto_noviembre' => money_format('%i',$row['monto_noviembre']),
				'monto_diciembre' => money_format('%i',$row['monto_diciembre']),
				'total' => money_format('%i', $row['total'])
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

if(isset($_POST['ReturnListaClientes1'])){
	
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
		cdd.nombre_corto as tipo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0.00') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_enero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0.00')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_febrero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0.00')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_marzo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0.00')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_abril,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0.00')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_mayo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0.00')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_junio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0.00')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_julio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0.00')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_agosto,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0.00')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_septiembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0.00')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_octubre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0.00')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_noviembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0.00')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item), '0.00') as monto_diciembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(
		(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0.00') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0.00')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0.00')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0.00')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0.00')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0.00')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0.00')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0.00')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0.00')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0.00')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0.00')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0.00')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item)), '0.00') as total
		FROM configuracion_detalle cdd
        WHERE cdd.codigo_tabla='_TIPO_INMUEBLE'
		
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
				'monto_enero' => intval($row['monto_enero']),
				'monto_febrero' => intval($row['monto_febrero']),
				'monto_marzo' => intval($row['monto_marzo']),
				'monto_abril' => intval($row['monto_abril']),
				'monto_mayo' => intval($row['monto_mayo']),
				'monto_junio' => intval($row['monto_junio']),
				'monto_julio' => intval($row['monto_julio']),
				'monto_agosto' => intval($row['monto_agosto']),
				'monto_septiembre' => intval($row['monto_septiembre']),
				'monto_octubre' => intval($row['monto_octubre']),
				'monto_noviembre' => intval($row['monto_noviembre']),
				'monto_diciembre' => intval($row['monto_diciembre']),
				'total' => intval($row['total'])
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
		cdd.nombre_corto as tipo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_enero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_febrero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_marzo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_abril,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_mayo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_junio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_julio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_agosto,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_septiembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_octubre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_noviembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_diciembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item)), '0') as total
		FROM configuracion_detalle cdd
        WHERE cdd.codigo_tabla='_TIPO_INMUEBLE'
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
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

if(isset($_POST['ReturnListaClientesConteo2'])){
	
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
		cdd.nombre_corto as tipo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_enero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_febrero,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_marzo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_abril,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_mayo,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_junio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_julio,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_agosto,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_septiembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_octubre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_noviembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item), '0') as monto_diciembre,
		if((SELECT COUNT(tbl1.id_venta) FROM gp_venta tbl1 WHERE tbl1.tipo_inmobiliaria=cdd.codigo_item)>0,(
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos' AND gpv1.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos' AND gpv2.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos' AND gpv3.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos' AND gpv4.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos' AND gpv5.tipo_inmobiliaria=cdd.codigo_item)+
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos' AND gpv6.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos' AND gpv7.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos' AND gpv8.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos' AND gpv9.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos' AND gpv10.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos' AND gpv11.tipo_inmobiliaria=cdd.codigo_item)+ 
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos' AND gpv12.tipo_inmobiliaria=cdd.codigo_item)), '0') as total
		FROM configuracion_detalle cdd
        WHERE cdd.codigo_tabla='_TIPO_INMUEBLE'
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
				'monto_enero' => intval($row['monto_enero']),
				'monto_febrero' => intval($row['monto_febrero']),
				'monto_marzo' => intval($row['monto_marzo']),
				'monto_abril' => intval($row['monto_abril']),
				'monto_mayo' => intval($row['monto_mayo']),
				'monto_junio' => intval($row['monto_junio']),
				'monto_julio' => intval($row['monto_julio']),
				'monto_agosto' => intval($row['monto_agosto']),
				'monto_septiembre' => intval($row['monto_septiembre']),
				'monto_octubre' => intval($row['monto_octubre']),
				'monto_noviembre' => intval($row['monto_noviembre']),
				'monto_diciembre' => intval($row['monto_diciembre']),
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



if(isset($_POST['ReturnListaClientesCasa'])){
    
	$bxFiltroPeriodo2 = isset($_POST['bxFiltroPeriodo2']) ? $_POST['bxFiltroPeriodo2'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo2);
	
    $query = mysqli_query($conection,"SELECT 
		cd.nombre_corto as tipo,
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos') as monto_enero,
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos') as monto_febrero,
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos') as monto_marzo,
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos') as monto_abril,
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos') as monto_mayo,
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos') as monto_junio,
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos') as monto_julio,
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos') as monto_agosto,
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos') as monto_septiembre,
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos') as monto_octubre,
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos') as monto_noviembre,
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos') as monto_diciembre,
		(
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND  MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos')) as total
		FROM gp_venta gpv
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
		GROUP BY gpv.tipo_casa
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
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

if(isset($_POST['ReturnListaClientesCasa2'])){
    
	$bxFiltroPeriodo2 = isset($_POST['bxFiltroPeriodo2']) ? $_POST['bxFiltroPeriodo2'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo2);
	
    $query = mysqli_query($conection,"SELECT 
		cd.nombre_corto as tipo,
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos') as monto_enero,
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos') as monto_febrero,
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos') as monto_marzo,
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos') as monto_abril,
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos') as monto_mayo,
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos') as monto_junio,
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos') as monto_julio,
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos') as monto_agosto,
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos') as monto_septiembre,
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos') as monto_octubre,
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos') as monto_noviembre,
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos') as monto_diciembre,
		(
		(SELECT if(COUNT(gpv1.total)>0,COUNT(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND  MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv2.total)>0,COUNT(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv3.total)>0,COUNT(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv4.total)>0,COUNT(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv5.total)>0,COUNT(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(COUNT(gpv6.total)>0,COUNT(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv7.total)>0,COUNT(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv8.total)>0,COUNT(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv9.total)>0,COUNT(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv10.total)>0,COUNT(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv11.total)>0,COUNT(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(COUNT(gpv12.total)>0,COUNT(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos')) as total
		FROM gp_venta gpv
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
		GROUP BY gpv.tipo_casa
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
				'monto_enero' => intval($row['monto_enero']),
				'monto_febrero' => intval($row['monto_febrero']),
				'monto_marzo' => intval($row['monto_marzo']),
				'monto_abril' => intval($row['monto_abril']),
				'monto_mayo' => intval($row['monto_mayo']),
				'monto_junio' => intval($row['monto_junio']),
				'monto_julio' => intval($row['monto_julio']),
				'monto_agosto' => intval($row['monto_agosto']),
				'monto_septiembre' => intval($row['monto_septiembre']),
				'monto_octubre' => intval($row['monto_octubre']),
				'monto_noviembre' => intval($row['monto_noviembre']),
				'monto_diciembre' => intval($row['monto_diciembre']),
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





if(isset($_POST['ReturnListaCasa'])){
	
	
	$bxFiltroPeriodo2 = isset($_POST['bxFiltroPeriodo2']) ? $_POST['bxFiltroPeriodo2'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo2);

	
    $query = mysqli_query($conection,"SELECT 
		cd.nombre_corto as tipo,
		(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos') as monto_enero,
		(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos') as monto_febrero,
		(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos') as monto_marzo,
		(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos') as monto_abril,
		(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos') as monto_mayo,
		(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos') as monto_junio,
		(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos') as monto_julio,
		(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos') as monto_agosto,
		(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos') as monto_septiembre,
		(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos') as monto_octubre,
		(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos') as monto_noviembre,
		(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos') as monto_diciembre,
		(
		(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND  MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos')) as total
		FROM gp_venta gpv
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
		GROUP BY gpv.tipo_casa
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
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

if(isset($_POST['ReturnListaCasa2'])){
	
	
	$bxFiltroPeriodo2 = isset($_POST['bxFiltroPeriodo2']) ? $_POST['bxFiltroPeriodo2'] : Null;
	$bxFiltroPeriodos  = trim($bxFiltroPeriodo2);

	
    $query = mysqli_query($conection,"SELECT 
		cd.nombre_corto as tipo,
		(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos') as monto_enero,
		(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos') as monto_febrero,
		(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos') as monto_marzo,
		(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos') as monto_abril,
		(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos') as monto_mayo,
		(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos') as monto_junio,
		(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos') as monto_julio,
		(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos') as monto_agosto,
		(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos') as monto_septiembre,
		(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos') as monto_octubre,
		(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos') as monto_noviembre,
		(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos') as monto_diciembre,
		(
		(SELECT if(SUM(gpv1.total)>0,SUM(gpv1.total),'0') FROM gp_venta gpv1 WHERE gpv1.tipo_casa=gpv.tipo_casa AND  MONTH(gpv1.fecha_venta)='1' AND YEAR(gpv1.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv2.total)>0,SUM(gpv2.total),'0')  FROM gp_venta gpv2 WHERE gpv2.tipo_casa=gpv.tipo_casa AND  MONTH(gpv2.fecha_venta)='2' AND YEAR(gpv2.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv3.total)>0,SUM(gpv3.total),'0')  FROM gp_venta gpv3 WHERE gpv3.tipo_casa=gpv.tipo_casa AND  MONTH(gpv3.fecha_venta)='3' AND YEAR(gpv3.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv4.total)>0,SUM(gpv4.total),'0')  FROM gp_venta gpv4 WHERE gpv4.tipo_casa=gpv.tipo_casa AND  MONTH(gpv4.fecha_venta)='4' AND YEAR(gpv4.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv5.total)>0,SUM(gpv5.total),'0')  FROM gp_venta gpv5 WHERE gpv5.tipo_casa=gpv.tipo_casa AND  MONTH(gpv5.fecha_venta)='5' AND YEAR(gpv5.fecha_venta)='$bxFiltroPeriodos')+
		(SELECT if(SUM(gpv6.total)>0,SUM(gpv6.total),'0')  FROM gp_venta gpv6 WHERE gpv6.tipo_casa=gpv.tipo_casa AND  MONTH(gpv6.fecha_venta)='6' AND YEAR(gpv6.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv7.total)>0,SUM(gpv7.total),'0')  FROM gp_venta gpv7 WHERE gpv7.tipo_casa=gpv.tipo_casa AND  MONTH(gpv7.fecha_venta)='7' AND YEAR(gpv7.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv8.total)>0,SUM(gpv8.total),'0')  FROM gp_venta gpv8 WHERE gpv8.tipo_casa=gpv.tipo_casa AND  MONTH(gpv8.fecha_venta)='8' AND YEAR(gpv8.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv9.total)>0,SUM(gpv9.total),'0')  FROM gp_venta gpv9 WHERE gpv9.tipo_casa=gpv.tipo_casa AND  MONTH(gpv9.fecha_venta)='9' AND YEAR(gpv9.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv10.total)>0,SUM(gpv10.total),'0')  FROM gp_venta gpv10 WHERE gpv10.tipo_casa=gpv.tipo_casa AND  MONTH(gpv10.fecha_venta)='10' AND YEAR(gpv10.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv11.total)>0,SUM(gpv11.total),'0')  FROM gp_venta gpv11 WHERE gpv11.tipo_casa=gpv.tipo_casa AND  MONTH(gpv11.fecha_venta)='11' AND YEAR(gpv11.fecha_venta)='$bxFiltroPeriodos')+ 
		(SELECT if(SUM(gpv12.total)>0,SUM(gpv12.total),'0')  FROM gp_venta gpv12 WHERE gpv12.tipo_casa=gpv.tipo_casa AND  MONTH(gpv12.fecha_venta)='12' AND YEAR(gpv12.fecha_venta)='$bxFiltroPeriodos')) as total
		FROM gp_venta gpv
		INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpv.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
		GROUP BY gpv.tipo_casa
	"); 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //$data['recordsTotal'] = intval($row["TotalRegistros"]);
            //$data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'tipo' => $row['tipo'],
				'monto_enero' => intval($row['monto_enero']),
				'monto_febrero' => intval($row['monto_febrero']),
				'monto_marzo' => intval($row['monto_marzo']),
				'monto_abril' => intval($row['monto_abril']),
				'monto_mayo' => intval($row['monto_mayo']),
				'monto_junio' => intval($row['monto_junio']),
				'monto_julio' => intval($row['monto_julio']),
				'monto_agosto' => intval($row['monto_agosto']),
				'monto_septiembre' => intval($row['monto_septiembre']),
				'monto_octubre' => intval($row['monto_octubre']),
				'monto_noviembre' => intval($row['monto_noviembre']),
				'monto_diciembre' => intval($row['monto_diciembre']),
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


if(isset($_POST['Reporte1'])){

		$bxFiltroPeriodo = isset($_POST['bxFiltroPeriodo']) ? $_POST['bxFiltroPeriodo'] : Null;
		$bxFiltroPeriodor = trim($bxFiltroPeriodo);
		
        if(!empty($bxFiltroPeriodor)){
            $valor_periodo = encrypt($bxFiltroPeriodor,"123");
            $data['status']="ok";
            $data['param'] = $valor_periodo;
        }else{
            $data['status']="bad";
        }

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

}

