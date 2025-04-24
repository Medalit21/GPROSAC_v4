<?php
session_start();
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
$hora = date("H:i:s", time());
$fecha = date('Y-m-d');

$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
$IdUser = $respuesta_idusu['id'];

if (!empty($_POST)) {
    if (isset($_POST['btnCargarCodigoProy'])) {
       
        $CorreProyecto="";
        $SiglaProyecto ="";

        $consultar_Correproyecto = mysqli_query($conection, "SELECT max(correlativo) as correlativo FROM gp_proyecto WHERE estado='1'");
        $respuesta_Correproyecto = mysqli_fetch_assoc($consultar_Correproyecto);
        $CorreProyecto = $respuesta_Correproyecto['correlativo'];

        //Consultar Sigla de Proyecto
        $query = mysqli_query($conection, "SELECT nombre_corto as sigla FROM configuracion_detalle WHERE codigo_tabla='_SIGLA' AND nombre_largo='PROYECTO'");
        $respuesta_query = mysqli_fetch_assoc($query);
        $SiglaProyecto = $respuesta_query['sigla'];

        if (!empty($CorreProyecto)) {
            if ($CorreProyecto > 0) {
                $num = 0;
                if ($CorreProyecto>0 && $CorreProyecto<10) {
                    $num = "00";
                } else {
                    if ($CorreProyecto>10 && $CorreProyecto<100) {
                        $num = "0";
                    } else {
                        if ($CorreProyecto>100 && $CorreProyecto<1000) {
                            $num = "";
                        }
                    }
                }
                $new_num = "";

                $CorreProyecto = $CorreProyecto+1;
                $new_num = $num.$CorreProyecto;
                $ValorCodigo = $SiglaProyecto."-".$new_num;

                $data['status'] = "ok";
                $data['codigo'] = $ValorCodigo;
                $data['correlativo'] = $CorreProyecto;

            } else {
                $ValorCodigo = $SiglaProyecto."-001";

                $data['status'] = "ok";
                $data['codigo'] = $ValorCodigo;
                $data['correlativo'] = '1';
            }
        } else {
            $ValorCodigo = $SiglaProyecto."-001";

            $data['status'] = "ok";
            $data['codigo'] = $ValorCodigo;
            $data['correlativo'] = '1';
            
        }

        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

?>
