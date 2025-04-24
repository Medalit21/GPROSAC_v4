<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 

   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];


if(!empty($_POST)){


    if (isset($_POST['btnRegistrarEmpresa'])) {
        
        $query = mysqli_query($conection, "call Nombre_Procedimiento_Almacenado + parametros");

        //Consulta
        $consulta = mysqli_query($conection, "SELECT * FROM nombre_tabla");
        $consulta_registro = mysqli_num_rows($consulta);

        if ($consulta_registro > 0) {
            $data['status'] = "ok";
            $data['data'] = "Mensaje Respuesta Correcta";
        } else {
            $data['status'] = "bad";
            $data['data'] = "Mensaje Respuesta Error";
        }

        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

?>