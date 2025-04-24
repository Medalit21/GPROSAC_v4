<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $control = $fecha." ".$hora;
   
   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

if(!empty($_POST)){

    if(isset($_POST['btnEliminarEmpresa'])){

        $txtid = $_POST['txtid'];
    
        if(!empty($txtid)){

                //Consultar existencia de empresa
               $consultar_empresa = mysqli_query($conection, "SELECT * FROM configuracion_empresas WHERE id='$txtid'");
                $respuesta_empresa = mysqli_num_rows($consultar_empresa);

                if($respuesta_empresa > 0){

                    //echo json_encode($data);
                    $query = mysqli_query($conection,"UPDATE configuracion_empresas SET 
                    update_user='$IdUser',
                    update_control='$control',
                    estado='2'
                    WHERE id='$txtid'"); 

                    //Consultar Nuevo Ingreso
                    $consultar = mysqli_query($conection, "SELECT * FROM configuracion_empresas WHERE id='$txtid'");
                    $consultar_registro = mysqli_num_rows($consultar);

                    if($consultar_registro > 0){
                   
                            $data['status'] = "ok";
                            $data['data'] = "Se ha eliminado la empresa seleccionada.";

                    }else{

                            $data['status'] = "bad";
                            $data['data'] = "Error al eliminar! Intente nuevamente.";

                    }

                }else{

                    $data['status'] = "bad";
                    $data['data'] = "Error no se encontro algun registro.";
        
                }
        

        }else{

            $data['status'] = "bad";
            $data['data'] = "Error! Para eliminar seleccione un registro de la tabla.";

        }

    }

    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

?>