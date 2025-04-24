<?php
    date_default_timezone_set('America/Lima');
    include_once "conexion_2.php";
    $user = $_SESSION['usu'];
    $time = time();
    $fecha = date('Y-m-d');
    $hora = date("H:i:s", $time);

    //consultar id usuario
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$user'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $idusuario = $respuesta_idusuario['id'];
    $_SESSION['idusuario'] = $idusuario;
    //Insertar Usuario
    $consultar_usuario = mysqli_query($conection, "INSERT INTO system_loginusuario(user,idempresa,fecha,hora) VALUES ('$idusuario','','$fecha','$hora')");
    
?>