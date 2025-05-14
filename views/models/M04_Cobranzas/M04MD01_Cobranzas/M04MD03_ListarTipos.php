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


    if (isset($_POST['ListarZonas'])) {

        $idproyecto = $_POST['idproyecto'];
        $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idproyecto' AND estado='1' ORDER BY nombre");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
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

    if (isset($_POST['ListarManzanas'])) {

        $idzona = $_POST['idzona'];
        $query = mysqli_query($conection, "SELECT idmanzana as valor, nombre as texto FROM gp_manzana WHERE idzona='$idzona' AND estado='1' ORDER BY nombre");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
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

    if (isset($_POST['ListarLotes'])) {

        $idmanzana = $_POST['idmanzana'];
        $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idmanzana' AND estado IN (5,6) AND bloqueo_estado != 7 ORDER BY correlativo ASC");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
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

    if (isset($_POST['ListarProyectosDefecto'])) {

        $query = mysqli_query($conection, "SELECT gpp.idproyecto as valor, concat(gpp.nombre,' - ',ud.nombre) as texto FROM gp_proyecto gpp INNER JOIN ubigeo_distrito AS ud ON ud.codigo=gpp.distrito WHERE gpp.estado='1' ORDER BY gpp.idproyecto ASC");

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

    if (isset($_POST['ListarZonasDefecto'])) {

        $idproyectoo = $_POST['idproy'];
        if(empty($idproyectoo)){
            $idproyectoo = 1;
        }
        $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idproyectoo' AND estado='1' ORDER BY nombre");

        array_push($dataList, [
            'valor' => '',
            'texto' => 'Seleccionar...',
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



   ?>