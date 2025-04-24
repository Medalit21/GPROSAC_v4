<?php
//session_start();

include_once "../../config/configuracion.php";
include_once "../../config/conexion_2.php";
$hora = date("H:i:s", time());
$fecha = date('Y-m-d');
$data = array();
$dataList= array();

if (isset($_POST['ReturnListaDepartamento'])) {
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
}

if (isset($_POST['ReturnListaProvincia'])) {
    $IdDep = intval($_POST['ubigeo']);

    $query = mysqli_query($conection, "SELECT codigo as valor, nombre as texto FROM ubigeo_provincia WHERE codigo_region=$IdDep;");

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

if (isset($_POST['ReturnListaDistritos'])) {
    $IdDep = intval($_POST['ubigeo']);

    $query = mysqli_query($conection, "SELECT codigo as valor, nombre as texto  FROM ubigeo_distrito WHERE codigo_provincia=$IdDep ORDER BY nombre ASC");

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
