<?php
//session_start();

include_once "../../config/configuracion.php";
include_once "../../config/conexion_2.php";

$data = array();
$dataList = array();

if (isset($_POST['ReturnListaCarrera'])) {
    $IdInstitucion = $_POST['CodigoInstitucion'];

    $query = mysqli_query($conection, "SELECT codigo as valor, nombre as texto FROM carrera WHERE codigo_institucion=$IdInstitucion;");

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
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
