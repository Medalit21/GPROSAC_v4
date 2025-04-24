<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 

   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];



/****************Consumiendo API ALOJAR FIRMA A SERVER************************** */
if (isset($_POST['API_SUBIR_ARCHIVO'])) {

    $cfile = new CURLFile($_FILES['fileSubirFirma']['tmp_name'], $_FILES['fileSubirFirma']['type'], $_FILES['fileSubirFirma']['name']);

    $dataPost = array(
        'file' => $cfile
    );

    $ch = curl_init($UrlApiNominas . 'api/Nominas/Archivo/Subir');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPost);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    $response = curl_exec($ch);
    $object = new stdClass();
    $object = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $Status = json_decode(json_encode($object), true);
    $Resultado['status'] = $Status;
    $Resultado['data'] = json_decode($response);

    if (intval($Resultado['status']) === 200) {
        $data['status'] = "ok";
        $data['mensaje'] = $Resultado['data']->id;
    } else {
        $data['status'] = "bad";
        $data['mensaje'] = $Resultado['data']->mensajes;
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
?>