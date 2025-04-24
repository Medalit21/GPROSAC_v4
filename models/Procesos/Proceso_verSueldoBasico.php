<?php
   //session_start();
    
   include_once "../../config/configuracion.php";
   include_once "../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d');   
   $data = array();
   if(empty($_POST)){

    $query = mysqli_query($conection,"SELECT valor FROM configuracion_valores WHERE id=1;");

    if($query->num_rows > 0){
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
	    echo json_encode($data);
    }
   }
   ?>