<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

$IdUser = 1;
$data = array();
$dataList = array();

if(isset($_POST['ReturnControlSesion'])){

    $dato_usuario = $_POST['d_u_sn'];
    $dato_usuario = decrypt($dato_usuario, "123");

    $consultar_usuario = mysqli_query($conection, "SELECT idusuario FROM usuario WHERE usuario='$dato_usuario'");
    $respuesta_usuario = mysqli_fetch_assoc($consultar_usuario);

    if($respuesta_usuario<=0){
        $data['status'] = 'ok';
        $data['data'] = 'El tiempo de su sesi贸n a terminado. Vuelva a ingresar sus credenciales de acceso. Gracias';
        $data['url'] = $NAME_SERVER;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
?>