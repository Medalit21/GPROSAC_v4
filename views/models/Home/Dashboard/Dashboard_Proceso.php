<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

/* $nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);*/
$IdUser = 1;

$data = array();
$dataList = array();

/**************************RETORNAR TOTALES******************* */
if (isset($_POST['ReturnTotales'])) {

    $idProyec = $_POST['idProy'];
    $valor_id = "";
    if(empty($idProyec)){
        $valor_id = 1;
    }else{
        $valor_id = $idProyec;
    }

    $query = mysqli_query($conection, "SELECT gpp.nombre,
    (SELECT count(gpz.idzona) FROM gp_zona gpz WHERE gpz.idproyecto=gpp.idproyecto) as totZonas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz WHERE gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totManzanas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLotes,
    (SELECT count(dc.id) FROM datos_cliente dc WHERE dc.esta_borrado=0) as totClientes,
    (SELECT count(gpv.id_venta) FROM gp_venta gpv WHERE esta_borrado=0) as totVentas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='1' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) - (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLibres,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='2' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totReservados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='3' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totPorVencer,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='4' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVencidos,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='5' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosT,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='6' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) - (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='8' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosTC,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totBloqueados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.motivo='8' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totCanjes,
    gpp.nombre as nombre_proyecto,
    format(gpp.area,2) as area_proyecto,
    gpp.inicio_proyecto as inicio_proyecto,
    gpp.direccion as direccion_proyecto,
    gpp.departamento as departamento_proyecto,
    gpp.provincia as provincia_proyecto,
    gpp.distrito as distrito_proyecto
    FROM gp_proyecto gpp
    WHERE gpp.idproyecto=$valor_id
    ");

    if ($query) {
        if ($query->num_rows > 0) {
            $resultado = $query->fetch_assoc();
            $data['status'] = 'ok';
            $data['data'] = $resultado;
        }
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurri¨® un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************RETORNA LISTA DE CORDENADAS DE PROYECTOS****************************** */
if (isset($_POST['ReturnListaCoordenada'])) {
    $query = mysqli_query($conection, "SELECT latitud,longitud,descripcion FROM datos_proyecto where esta_borrado=0");
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data['status'] = 'ok';
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['ReturnListaProyectos'])) {

    $query = mysqli_query($conection, "SELECT 
        gp.idproyecto as id, 
        gp.nombre as nombre, 
        gp.area as area, 
        gp.nro_zonas as zonas, 
        (select nombre as nom FROM ubigeo_distrito WHERE codigo=gp.distrito) as direccion,
        (select count(idzona) from gp_zona where idproyecto = gp.idproyecto) as zonas,
        count(gpm.idmanzana) as manzanas,
        sum((select count(idlote) from gp_lote where idmanzana=gpm.idmanzana)) as lotes
        FROM gp_proyecto gp, gp_zona gpz, gp_manzana gpm
        WHERE gp.estado='1' AND gpz.idproyecto = gp.idproyecto AND gpm.idzona = gpz.idzona");
    $resp = mysqli_num_rows($query);

    if ($resp > 0) {

        $data['status'] = 'ok';
        while ($row = $query->fetch_assoc()) {

            $IdEncriptado=encrypt($row['id'],"123"); 
            array_push($dataList, [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'area' => $row['area'],
                'zonas' => $row['zonas'],
                'direccion' => $row['direccion'],
                'manzanas' => $row['manzanas'],
                'lotes' => $row['lotes'],
                'url' => $NAME_SERVER."views/M01_Proyecto/M01SM05_Inventario/M01_SM05_Inventario.php?i=".$IdEncriptado
            ]);
        }
       
        $data['data'] = $dataList;
       // $data['data'] = "correcto";

    } else {

        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'No existen proyectos registrados.';

    }

    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}


if (isset($_POST['ReturnColoresEstados'])) {

    $query = mysqli_query($conection, "SELECT idconfig_detalle FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI'");

    $color_estado_1 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='1'");
    $respuesta_color_1 = mysqli_fetch_assoc($color_estado_1);
    $color_1 = $respuesta_color_1['color'];

    $color_estado_2 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='2'");
    $respuesta_color_2 = mysqli_fetch_assoc($color_estado_2);
    $color_2 = $respuesta_color_2['color'];

    $color_estado_3 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='3'");
    $respuesta_color_3 = mysqli_fetch_assoc($color_estado_3);
    $color_3 = $respuesta_color_3['color'];

    $color_estado_4 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='4'");
    $respuesta_color_4 = mysqli_fetch_assoc($color_estado_4);
    $color_4 = $respuesta_color_4['color'];

    $color_estado_5 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='5'");
    $respuesta_color_5 = mysqli_fetch_assoc($color_estado_5);
    $color_5 = $respuesta_color_5['color'];

    $color_estado_6 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='6'");
    $respuesta_color_6 = mysqli_fetch_assoc($color_estado_6);
    $color_6 = $respuesta_color_6['color'];

    $color_estado_7 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='7'");
    $respuesta_color_7 = mysqli_fetch_assoc($color_estado_7);
    $color_7 = $respuesta_color_7['color'];

    $color_estado_8 = mysqli_query($conection, "SELECT texto1 as color FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND codigo_item='8'");
    $respuesta_color_8 = mysqli_fetch_assoc($color_estado_8);
    $color_8 = $respuesta_color_8['color'];

    if ($query) {
        if ($query->num_rows > 0) {
            $resultado = $query->fetch_assoc();
            $data['status'] = 'ok';
            $data['data'] = $resultado;
            $data['color1'] = $color_1;
            $data['color2'] = $color_2;
            $data['color3'] = $color_3;
            $data['color4'] = $color_4;
            $data['color5'] = $color_5;
            $data['color6'] = $color_6;
            $data['color7'] = $color_7;
            $data['color8'] = $color_8;
        }
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurri¨® un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnListaZonasProyecto'])) {

    $idproyecto = $_POST['idProyecto'];
    $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idproyecto' AND estado='1' ORDER BY nombre");

    array_push($dataList, [
        'valor' => 'todos',
        'texto' => 'Todos',
    ]);

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['valor'],
                'texto' => $row['texto'],
            ]);}
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}


if (isset($_POST['ReturnListaManzanas'])) {

    $idzona = $_POST['idZona'];
    $query = mysqli_query($conection, "SELECT idmanzana as valor, nombre as texto FROM gp_manzana WHERE idzona='$idzona' AND estado='1' ORDER BY nombre");

    array_push($dataList, [
        'valor' => 'todos',
        'texto' => 'Todos',
    ]);

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['valor'],
                'texto' => $row['texto'],
            ]);}
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}


if (isset($_POST['ReturnTotalesManzana'])) {

    $idManzana = $_POST['idManzan'];
    $idproyect = $_POST['idpro'];
    $idzon = $_POST['idzon'];
    $valor_idManzana = "";
    $valor_idzona = "";
    if(empty($idManzana) || $idManzana=="todos"){
        $valor_idManzana = "";
        $valor_idzona = "AND gpm.idzona='".$idzon."'";
    }else{
        $valor_idManzana = "AND gpl.idmanzana='".$idManzana."'";
    }
 
    $query = mysqli_query($conection, "SELECT 
	(SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado!='0' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLotes,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='1' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLibres,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='2' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totReservados,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='3' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totPorVencer,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='4' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVencidos,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='5' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosT,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='6' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosTC,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totBloqueados,
    (SELECT count(gpl.idlote) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='8' $valor_idManzana AND gpl.idmanzana=gpm.idmanzana $valor_idzona AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totCanjes
    FROM gp_proyecto gpp
    WHERE gpp.idproyecto=$idproyect
    ");
 
    if ($query) {
        if ($query->num_rows > 0) {
            $resultado = $query->fetch_assoc();
            $data['status'] = 'ok';
            $data['data'] = $resultado;
        }
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurri¨® un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
