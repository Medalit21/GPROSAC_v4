<?php
//session_start();

include_once "../../config/configuracion.php";
include_once "../../config/conexion_2.php";
$hora = date("H:i:s", time());
$fecha = date('Y-m-d');
$data = array();
$dataList = array();

/**********************RETORNAR EL SERCTOR DE LA EMPRESA******************************** */

if (isset($_POST['ReturnSectorEmpresa'])) {
    $consulta_sec = mysqli_query($conection, "SELECT valor as val FROM datos_empresa WHERE codigo=1");
    if ($consulta_sec->num_rows > 0) {
        $resultado = $consulta_sec->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
