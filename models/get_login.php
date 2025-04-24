<?php

session_start();
require_once "config/conexion.php";
require_once "config/configuracion.php";

if (!empty($_POST)) {
    if (isset($_POST['btnacceder'])) {       

            $user = $_POST['usuario'];
            $pass = $_POST['clave'];
			/*
            $consultar_dni = mysqli_query($conection, "SELECT idusuario as id FROM persona WHERE DNI='$user' AND estatus='Activo'");
            $respuesta_dni = mysqli_fetch_assoc($consultar_dni);
            $doc = $respuesta_dni['id'];
			*/
            $query = mysqli_query($conection, "SELECT idusuario, idPerfil FROM usuario WHERE usuario='$user' AND clave='$pass' AND estatus='Activo'");
            $resultado_1 = mysqli_num_rows($query);
            $resultado_2 = mysqli_fetch_assoc($query);

            $id_usuario = $resultado_2['idusuario'];
            //$rol_usuario = $resultado_2['rol'];
            $perfil_usuario = $resultado_2['idPerfil'];
            
            $_SESSION['perfil'] = $perfil_usuario;
            $_SESSION['usu']=$id_usuario;

            if ($resultado_1 > 0) {
               
                    $data['status'] = "ok";
                    $data['data'] = "Correcto";
                    
            } else {

                $data['status'] = "bad";
                $data['data'] = "Usuario o clave incorrectos!";

            
            }

        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT);
        
    }
}
?>

