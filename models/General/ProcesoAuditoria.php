<?php
session_start();
date_default_timezone_set('America/Lima');
include_once "../../config/configuracion.php";
include_once "../../config/conexion_2.php";

$hora = date("H:i:s", time());
$fecha = date('Y-m-d');

$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
$IdUser = $respuesta_idusu['id'];
$IdEmpresa = $_SESSION['id_empresa'];

$data = array();
$dataList = array();

if (isset($_POST['ReturnInsertarAuditoria'])) {
    $Seccion = $_POST['seccion'];
    $Seccion = !empty($Seccion) ? "'$Seccion'" : "NULL";
    $TipoAccion = $_POST['tipoAccion'];
    $TipoAccion = !empty($TipoAccion) ? "'$TipoAccion'" : "NULL";

    $Valor1 = "NULL";
    if (isset($_POST['valor1'])) {
        $Valor1 = $_POST['valor1'];
        $Valor1 = !empty($Valor1) ? "'$Valor1'" : "NULL";
    }

    $Valor2 = "NULL";
    if (isset($_POST['valor2'])) {
        $Valor2 = $_POST['valor2'];
        $Valor2 = !empty($Valor2) ? "'$Valor2'" : "NULL";
    }
    $Valor3 = "NULL";
    if (isset($_POST['valor3'])) {
        $Valor3 = $_POST['valor3'];
        $Valor3 = !empty($Valor3) ? "'$Valor3'" : "NULL";
    }
    $Valor4 = "NULL";
    if (isset($_POST['valor4'])) {
        $Valor4 = $_POST['valor4'];
        $Valor4 = !empty($Valor4) ? "'$Valor4'" : "NULL";
    }
    $Valor5 = "NULL";
    if (isset($_POST['valor5'])) {
        $Valor5 = $_POST['valor5'];
        $Valor5 = !empty($Valor5) ? "'$Valor5'" : "NULL";
    }
    $query = mysqli_query($conection, "INSERT INTO  configuracion_auditoria
    ( aplicacion ,seccion ,tipoaccion ,usuario , hora ,fecha ,valor1 , valor2 ,valor3 ,valor4 ,valor5 )
    VALUES
    ('$NombreAplicacion', $Seccion , $TipoAccion ,'$IdUser','$hora','$fecha',$Valor1,$Valor2,$Valor3,$Valor4,$Valor5)");
    if ($query) {
        $data['status'] = "ok";
        $data['mensaje'] = "Correcto";
    } else {
        $data['status'] = "bad";
         if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['mensaje'] = "error".$TipoAccion;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
