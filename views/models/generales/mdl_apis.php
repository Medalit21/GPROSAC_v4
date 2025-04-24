<?php
session_start(); 

//RECURSOS DE CONEXION Y CONFIGURACION
include_once "../../../config/configuracion.php";
include_once "../../../config/conexion_2.php";
include_once "../../../config/codificar.php";

//INICIALIZANDO VARIABLES Y ARREGLOS.
$data = array();
$dataList = array();
$hora = date("H:i:s", time());
$fecha = date('Y-m-d'); 


if(isset($_POST['btnSeleccionReniec'])){

  $NroDocumento = $_POST['NroDocumento'];

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero='.$NroDocumento.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Referer: http://apis.net.pe/api-ruc',
      'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  //echo $response;
  $datos = json_decode($response, true);

  if(empty($datos['error'])){
    $data['status'] = 'ok';
    $data['apellido_pat'] = $datos['apellidoPaterno'];
    $data['apellido_mat'] = $datos['apellidoMaterno'];
    $data['nombres'] = $datos['nombres'];
    $data['doc'] = $datos['numeroDocumento'];
  }else{
    $data['status'] = 'bad';
  }

  header('Content-type: text/javascript');
  echo json_encode($data, JSON_PRETTY_PRINT);  

}


if(isset($_POST['btnSeleccionSunat'])){

  $NroDocumento = $_POST['NroDocumento'];

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero='.$NroDocumento.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Referer: http://apis.net.pe/api-ruc',
      'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  //echo $response;
  $datos = json_decode($response, true);

  if(empty($datos['error'])){
    $data['status'] = 'ok';
    $data['nombre'] = $datos['nombre'];
  }else{
    $data['status'] = 'bad';
  }
  header('Content-type: text/javascript');
  echo json_encode($data, JSON_PRETTY_PRINT);  
}



if(isset($_POST['btnSeleccionTCSunat'])){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  //echo $response;
  $datos = json_decode($response, true);

  if(empty($datos['error'])){
    $data['status'] = 'ok';
    $data['nombre'] = $datos['venta'];
  }else{
    $data['status'] = 'bad';
  }
  header('Content-type: text/javascript');
  echo json_encode($data, JSON_PRETTY_PRINT);  
}






?>