<?php

//PARAMETROS DE CONEXION
$host = 'localhost';
$user = 'gpros4c_gprosac';
$password = '9,.k0?[FNj[9';
$db = 'gpros4c_gprosac';

$conection = mysqli_connect($host,$user,$password,$db);
$conection->set_charset("utf8");

//CONEXION
if(!$conection){
	echo "Error en la conexion";
}else{
   
   $fecha = date('Y-m-d');
   
   //CONSULTAR LETRAS VENCIDAS
   $query = mysqli_query($conection, "SELECT id, fecha_vencimiento, estado FROM gp_cronograma WHERE fecha_vencimiento<'$fecha' AND estado='1'");
   if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            //VALOR PK LETRA
            $pkletra = $row['id'];
            $actualizar_estado = mysqli_query($conection, "UPDATE gp_cronograma SET estado='3' WHERE id='$pkletra'");
        }
   }
   
   //CONSULTAR LETRAS POSTERIORES AL A FECHA ACTUAL CON ESTADO VENCIDO
   $querys = mysqli_query($conection, "SELECT id, fecha_vencimiento, estado FROM gp_cronograma WHERE fecha_vencimiento>'$fecha' AND estado='3'");
   if ($querys->num_rows > 0) {
        while ($roow = $querys->fetch_assoc()) {
            //VALOR PK LETRA
            $pkletras = $roow['id'];
            $actualizar_estado = mysqli_query($conection, "UPDATE gp_cronograma SET estado='1' WHERE id='$pkletras'");
        }
   }
}





?>