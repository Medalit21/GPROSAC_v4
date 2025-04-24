<?php
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d');
   
   $IdUser=1;
   
   $data = array();
   $dataList = array();


if(isset($_POST['btnSeleccionarRegistro'])){
    $IdReg=$_POST['IdRegistro'];
   $query = mysqli_query($conection,"SELECT 
        idproyecto as id,
        nombre as nombre,
        codigo as codigo,
        correlativo as correlativo,
        direccion as direccion,
        departamento as departamento,
        provincia as provincia,
        distrito as distrito,
        nro_zonas as nro_zonas,
        ROUND(area,2) as area,
        responsable as responsable
   from gp_proyecto where idproyecto='$IdReg'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}


if(isset($_POST['btnSeleccionarZona'])){
    $IdReg=$_POST['IdRegistro'];
   $query = mysqli_query($conection,"SELECT 
        idzona as id,
        nombre as nombre,
        nro_manzanas as nro_manzanas,
        ROUND(area,2) as area
       FROM gp_zona 
       WHERE idzona='$IdReg' AND estado='1'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}


if(isset($_POST['btnSeleccionarManzana'])){
    $IdReg=$_POST['IdRegistro'];
   $query = mysqli_query($conection,"SELECT 
        idmanzana as id,
        nombre as nombre,
        nro_lotes as nro_lotes,
        ROUND(area,2) as area
       FROM gp_manzana
       WHERE idmanzana='$IdReg' AND estado='1'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if(isset($_POST['btnSeleccionarLote'])){
    $IdReg=$_POST['IdRegistro'];
   $query = mysqli_query($conection,"SELECT 
        gpl.idlote as id,
        gpl.nombre as nombre,
        gpl.tipo_moneda as tipo_moneda,
        gpl.valor_con_casa as valor_con_casa,
        gpl.valor_sin_casa as valor_sin_casa,
        ROUND(gpl.area,2) as area
       FROM gp_lote gpl
       WHERE gpl.idlote='$IdReg' AND gpl.esta_borrado='0'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}


if(isset($_POST['btnEditarTipoCasa'])){
    $IdReg=$_POST['IdRegistro'];
	
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
		WHERE gpmtc.idmz_tipocasa='$IdReg'");
	   
	   
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

if (isset($_POST['btnEliminarTipCasa'])) {
    $IdReg = $_POST['idTipCasa'];

    $query = mysqli_query($conection, "
        UPDATE gp_manzana_tipocasa 
        SET estado = '0'
        WHERE idmz_tipocasa = '$IdReg'
    ");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Registro eliminado correctamente.';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al eliminar el registro.';
    }

    header('Content-type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


 ?> 