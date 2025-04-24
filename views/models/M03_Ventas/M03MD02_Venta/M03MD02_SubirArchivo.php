<?php

	include_once "../../../../config/configuracion.php";
	include_once "../../../../config/conexion_2.php";


	/*$file_name = $_FILES['fichero']['name'];
	$file_tmp = $_FILES['fichero']['tmp_name'];

	$route = "../../archivos/".$file_name;

	move_uploaded_file($file_tmp, $route);
*/

	$desc_codigo="file-";
	$name_file = "file";

	$consultar_idarchivo = mysqli_query($conection, "SELECT max(id) as id FROM gp_archivo_venta");
	$respuesta_idarchivo = mysqli_num_rows($consultar_idarchivo);

	if($respuesta_idarchivo>0){
		$max_idarchivo = mysqli_fetch_assoc($consultar_idarchivo);
		$idfile = $max_idarchivo['id'];
		$name_file = $desc_codigo.$idfile.".pdf";
	}else{
		$name_file = $desc_codigo."1.pdf";
	}

	if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], '../../../M03_Ventas/M03SM02_Venta/archivos/'.$name_file);
    
	}

?>