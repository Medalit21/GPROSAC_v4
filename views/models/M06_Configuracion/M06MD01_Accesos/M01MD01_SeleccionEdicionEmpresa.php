<?php
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d');
   
   $IdUser=1;
   
   $data = array();
   $dataList = array();


if(isset($_POST['btnSeleccionarRegistro'])){
    $IdReg=$_POST['IdRegistro'];
   $query = mysqli_query($conection,"SELECT 
        id as id
   from configuracion_empresas where id='$IdReg'");
   if($query->num_rows > 0){
       $resultado = $query->fetch_assoc();
       $data['status'] = 'ok';
       $data['data'] = $resultado;
   }else{
       $data['status'] = 'bad';
       $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
   }
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

 ?>