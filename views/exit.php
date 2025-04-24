<?php
    session_start();
    date_default_timezone_set('America/Lima');
    include_once "../config/configuracion.php";
    include_once "../config/conexion_2.php";
    $time = time();
    $fecha = date('Y-m-d');
    $hora = date("H:i:s", $time);

    $idusuario = $_SESSION['usu'];

    //consultar la session actual
    $consultar_sesion = mysqli_query($conection, "SELECT max(id) as id FROM system_loginusuario WHERE user='$idusuario'");
    $respuesta_session = mysqli_fetch_assoc($consultar_sesion);

    $idmax = $respuesta_session['id'];

    //actualizar cierre session
    $actualiza_session = mysqli_query($conection, "UPDATE system_loginusuario SET fecha_cierre='$fecha', hora_cierre='$hora', estado='0' WHERE id='$idmax'");

    //session_destroy();
    //mysqli_close($conection);
	$_SESSION['usu'] = "";
    //header('location: http://localhost/NominasAcg/');
    header('location: '.$NAME_SERVER.'');
  
?>