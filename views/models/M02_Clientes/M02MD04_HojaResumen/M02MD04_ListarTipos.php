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

    if (isset($_POST['ListarPropiedadesCliente'])) {

        $documento = $_POST['documento'];
        
        $query = mysqli_query($conection, "SELECT gpv.id_venta as valor, 
        concat('Mz.',SUBSTRING_INDEX(gpm.nombre,' ',-1),' - Lt.',SUBSTRING_INDEX(gpl.nombre,' ',-1), if(gpv.devolucion='1',' (DEVUELTO)',if(gpv.cancelado='1',' (CANCELADO)',''))) as texto 
        FROM gp_venta gpv
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        WHERE gpv.esta_borrado='0' 
        AND dc.documento='$documento'
        ORDER BY gpv.id_venta DESC");

        if ($query->num_rows > 1) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            $data['status'] = "ok";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
             $data['status'] = "bad";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }
    
    if (isset($_POST['ListarProyectosAuto'])) {

        $documento = $_POST['documento'];
        $idventa = $_POST['idventa'];
        
        $query_venta = "";
        if(!empty($idventa)){
            $query_venta = "AND gpv.id_venta='$idventa'";
        }
        
        
        $query = mysqli_query($conection, "SELECT gpv.id_venta as valor, 
        gpp.nombre as texto 
        FROM gp_proyecto gpp
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JION gp_venta AS gpv ON gpv.id_lote=gpl.id
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        WHERE gpv.esta_borrado='0' 
        AND gpz.idproyecto=gpp.idproyecto
        AND dc.documento='$documento'
        GROUP BY gpp.idproyecto
        ORDER BY gpv.id_venta DESC");

        if ($query->num_rows > 1) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            $data['status'] = "ok";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
             $data['status'] = "bad";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }
    
    if (isset($_POST['ListarZonasAuto'])) {

        $documento = $_POST['documento'];
        
        $query = mysqli_query($conection, "SELECT gpv.id_venta as valor, 
        concat('Mz.',SUBSTRING_INDEX(gpm.nombre,' ',-1),' - Lt.',SUBSTRING_INDEX(gpl.nombre,' ',-1), if(gpv.devolucion='1',' (DEVUELTO)',if(gpv.cancelado='1',' (CANCELADO)',''))) as texto 
        FROM gp_venta gpv
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        WHERE gpv.esta_borrado='0' 
        AND dc.documento='$documento'
        ORDER BY gpv.id_venta DESC");

        if ($query->num_rows > 1) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            $data['status'] = "ok";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
             $data['status'] = "bad";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }
    
    if (isset($_POST['ListarManzanasAuto'])) {

        $documento = $_POST['documento'];
        
        $query = mysqli_query($conection, "SELECT gpv.id_venta as valor, 
        concat('Mz.',SUBSTRING_INDEX(gpm.nombre,' ',-1),' - Lt.',SUBSTRING_INDEX(gpl.nombre,' ',-1), if(gpv.devolucion='1',' (DEVUELTO)',if(gpv.cancelado='1',' (CANCELADO)',''))) as texto 
        FROM gp_venta gpv
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        WHERE gpv.esta_borrado='0' 
        AND dc.documento='$documento'
        ORDER BY gpv.id_venta DESC");

        if ($query->num_rows > 1) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            $data['status'] = "ok";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
             $data['status'] = "bad";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }
    
    if (isset($_POST['ListarLotesAuto'])) {

        $documento = $_POST['documento'];
        
        $query = mysqli_query($conection, "SELECT gpv.id_venta as valor, 
        concat('Mz.',SUBSTRING_INDEX(gpm.nombre,' ',-1),' - Lt.',SUBSTRING_INDEX(gpl.nombre,' ',-1), if(gpv.devolucion='1',' (DEVUELTO)',if(gpv.cancelado='1',' (CANCELADO)',''))) as texto 
        FROM gp_venta gpv
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        WHERE gpv.esta_borrado='0' 
        AND dc.documento='$documento'
        ORDER BY gpv.id_venta DESC");

        if ($query->num_rows > 1) {

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);}
            $data['data'] = $dataList;
            $data['status'] = "ok";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $data['data'] = $dataList;
             $data['status'] = "bad";
            header('Content-type: text/javascript');
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    }
    
    
    
    


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
        $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idmanzana' AND estado IN (5,6) ORDER BY correlativo ASC");

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