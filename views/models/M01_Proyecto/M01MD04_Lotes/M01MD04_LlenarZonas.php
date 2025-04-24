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

    if(isset($_POST['LlenarZonas'])){

        $txtidProyectozlt = $_POST['txtidProyectozlt'];   

        $query = mysqli_query($conection, "SELECT idzona as id, nombre as nombre FROM gp_zona WHERE idproyecto='$txtidProyectozlt' order by nombre");

        if ($query->num_rows > 0) {
            array_push($dataList, [
                'valor' => '',
                'texto' => 'Seleccione',
            ]);
            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['id'],
                    'texto' => $row['nombre'],
                ]);}
            $data['data'] = $dataList;
        } else {
            $data['data'] = $dataList;
        }
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);

    }

    


?>