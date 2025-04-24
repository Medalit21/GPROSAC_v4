<?php
    //session_start();  

    //variable

    $usuario = $_SESSION['usu'];
    $clave = $_SESSION['psw'];
    $idperfil = "";
    $motivo = "";
 
    //Pefil 
    $consultar_perfil = mysqli_query($conection, "SELECT idPerfil as perfil FROM usuario WHERE usuario='$usuario' AND clave='$clave' AND estatus='Activo'");
    $cont_perfil = mysqli_num_rows($consultar_perfil);
    $respuesta_perfil = mysqli_fetch_assoc($consultar_perfil);   

    if ($cont_perfil > 0) {
        $idperfil = $respuesta_perfil['perfil'];
        $_SESSION['idperf'] = $idperfil;
        $_SESSION['Ruta'] = "views/M00_Home/M01_Home/home";
        /*if ($idperfil == '1') {           
            $_SESSION['Ruta'] = "views/asistencia/Modulos/SelectorAdmin.php";
        } else {
            if ($idperfil == '6') {           
                $_SESSION['Ruta'] = "views/M02_Clientes/M02SM03_EstadoCuenta/M02SM03_EstadoCuenta.php";
            } else {
                $_SESSION['Ruta'] = "views/asistencia/Modulos/SelectorIni.php";     
            }  
        }*/
    }else{
        $query2 = mysqli_query($conection,"SELECT me.motivo as motiv FROM usuario u, motivoestado me WHERE u.MotivoEstado=me.idME AND u.usuario= '$usuario' AND u.estatus = 'Inactivo'");
        $result2 = mysqli_num_rows($query2);
        $mostrar = mysqli_fetch_assoc($query2);       
        if($result2 > 0){
            $motivo=$mostrar['motiv'];
            $_SESSION['mensaje'] = 'Usted no puede acceder debido a que su cuenta se encuentra inactiva por '.$motivo;
         }else{
            //En caso de no encontrar registros semejantes en la base de datos saldra error de la conexion.
            $_SESSION['mensaje'] = ' Usuario o clave incorrectos';

            //final de la sesiиоn
            //session_destroy();	
        }
    }



?>