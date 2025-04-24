<?php
//session_start();

include_once "../../config/configuracion.php";
include_once "../../config/conexion_2.php";
$hora = date("H:i:s", time());
$fecha = date('Y-m-d');
$data = array();
$dataList= array();

/*if (isset($_POST['ReturnListaDepartamento'])) {
    $IdPais = intval($_POST['ubigeo']);

    $query = mysqli_query($conection, "SELECT codigo as valor, nombre as texto FROM ubigeo_region WHERE codigo_pais=$IdPais;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
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
}*/

if (isset($_POST['ReturnListaArea'])) {
    $idCarg = intval($_POST['cargo']);
    $idArea = intval($_POST['area']);
	//print_r($_POST['ubigeo']);

    $query = mysqli_query($conection, "SELECT idArea as ID, area as Area FROM area WHERE idcargo=$idCarg;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
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

if (isset($_POST['ReturnListaJefeInm'])) {
    $idCarg = intval($_POST['cargo']);
    $idArea = intval($_POST['area']);

    $query = mysqli_query($conection, "SELECT * FROM persona WHERE idcargo=$idCarg AND idArea=$idArea;");
	

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
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
