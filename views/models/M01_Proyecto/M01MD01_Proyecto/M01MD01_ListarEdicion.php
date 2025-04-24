<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   
   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

   $data = array();
   $dataList = array();
   //$varlor=$_POST['nombre'];


if(isset($_POST['ListarZonas'])){

    $txtidProyector = $_POST['txtidProyectoZona'];
    //echo json_encode($data);
    $query = mysqli_query($conection,"SELECT 
        idzona as id, 
        nombre as nombre, 
        codigo as codigo, 
        nro_manzanas as nro_manzanas, 
        format(area,2) as area
        FROM gp_zona
        WHERE esta_borrado='0' AND estado='1' AND idproyecto='$txtidProyector'"); 

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row
            );}

        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);

}

if(isset($_POST['ListarManzanas'])){

    $txtidZona = $_POST['idZona'];

	 $query = mysqli_query($conection,"SELECT 
        gpm.idmanzana as id, 
        gpm.nombre as nombre, 
        gpm.codigo as codigo, 
        gpm.nro_lotes as nro_lotes, 
        format(gpm.area,2) as area,
        cdx.nombre_corto as tipo_casa
        FROM gp_manzana gpm
        INNER JOIN configuracion_detalle AS cdx ON (cdx.codigo_item=gpm.tipo_casa OR gpm.tipo_casa IS NULL) AND cdx.codigo_tabla='_TIPO_CASA'
        WHERE gpm.esta_borrado='0' AND gpm.estado='1' AND gpm.idzona='$txtidZona'
        ORDER BY gpm.nombre ASC"); 
	
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row
            );}

        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);

}

if(isset($_POST['ListarLotes'])){

    $txtidManzana = $_POST['idManzana'];
    //echo json_encode($data);
    $query = mysqli_query($conection,"SELECT 
        gpl.idlote as id, 
        gpl.nombre as nombre, 
        format(gpl.area,2) as area, 
        cd.texto1 as tipo_moneda, 
        format(gpl.valor_con_casa,2) as valorConCasa,
        format(gpl.valor_sin_casa,2) as valorSinCasa
        FROM gp_lote gpl
        INNER JOIN configuracion_detalle AS cd ON gpl.tipo_moneda=cd.codigo_item AND cd.codigo_tabla='_TIPO_MONEDA'
        WHERE gpl.esta_borrado='0' AND gpl.estado = '1' AND gpl.idmanzana='$txtidManzana'"); 

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row
            );}

        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);

}

if(isset($_POST['ListarTiposCasa'])){

    $txtidManzana = $_POST['idManzana'];
    //echo json_encode($data);
    $query = mysqli_query($conection,"SELECT 
        gpmtc.idmz_tipocasa as id,
        @i := @i + 1 as contador,
        gpz.nombre as zona,
        gpm.nombre as manzana,
        cd.nombre_corto as tipoCasa
        FROM gp_manzana_tipocasa gpmtc
        CROSS JOIN (select @i := 0) r
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpmtc.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpmtc.idzona
        INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpmtc.tipo_casa AND cd.codigo_tabla='_TIPO_CASA' AND cd.estado='ACTI'
        WHERE gpmtc.estado=1 AND gpmtc.idmanzana='$txtidManzana'"); 

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row
            );}

        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);

}


if(isset($_POST['ListarTiposCasaPopup'])){

    //echo json_encode($data);
    $query = mysqli_query($conection,"SELECT 
        idconfig_detalle as id,
        nombre_corto as nombre
        FROM configuracion_detalle
        WHERE codigo_tabla='_TIPO_CASA' AND estado='ACTI' ORDER BY nombre_corto ASC"); 
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row
            );}

        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);

}


