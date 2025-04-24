<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $control = $fecha." - ".$hora;
   
   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

  
if(!empty($_POST)){

    if(isset($_POST['btnActualizarEmpresa'])){

        $Parametro = isset($_POST['Parametro']) ? $_POST['Parametro'] : Null;
        $Parametror = trim($Parametro);
         
        $query = mysqli_query($conection,"UPDATE configuracion_empresas SET update_user='$IdUser'"); 

        //Consultar Nuevo Ingreso
        $consultar = mysqli_query($conection, "SELECT * FROM configuracion_empresas WHERE (nombre='$txtnombre' AND razon_social='$txtrazon_social') OR ruc='$txtruc'");
        $consultar_registro = mysqli_num_rows($consultar);

        if($consultar_registro > 0){
                        
            $data['status'] = "ok";
            $data['data'] = "Mensaje de Operacion Correcta";

        }else{

            $data['status'] = "bad";
            $data['data'] = "Mensaje de Error";

        }

        

    }

    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

?>