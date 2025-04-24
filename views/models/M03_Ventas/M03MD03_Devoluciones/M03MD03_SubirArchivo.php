<?php

	include_once "../../../../config/configuracion.php";
	include_once "../../../../config/conexion_2.php";


	/*$file_name = $_FILES['fichero']['name'];
	$file_tmp = $_FILES['fichero']['tmp_name'];

	$route = "../../archivos/".$file_name;

	move_uploaded_file($file_tmp, $route);
*/

$desc_codigo="adenda-";
$name_file = "adenda";

$nombre = $_POST['data'];

if ( 0 < $_FILES['file']['error'] ) {
	echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
        move_uploaded_file($_FILES['file']['tmp_name'], '../../../M03_Ventas/M03SM03_Devoluciones/archivos/'.$nombre);
    
	}

?>