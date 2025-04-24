<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 

   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

if(!empty($_POST)){

    if(isset($_POST['btnRegistrarZona'])){

        $cbxManzanaslt = isset($_POST['cbxManzanaslt']) ? $_POST['cbxManzanaslt'] : Null;
        $cbxManzanasltr = trim($cbxManzanaslt);     
       
        $consultar_zona = mysqli_query($conection, "SELECT nro_lotes as numero, area as area FROM gp_manzana WHERE idmanzana='$cbxManzanasltr'");
        $respuesta_zona = mysqli_fetch_assoc($consultar_zona);
        
        $numero = $respuesta_zona['numero'];
        $area = $respuesta_zona['area'];
        
        $data['status'] = "ok";
        $data['numero'] = $numero;
        $data['area'] = $area;                                      

    }

    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

?>