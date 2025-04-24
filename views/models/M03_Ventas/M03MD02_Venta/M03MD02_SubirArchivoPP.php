<?php

	include_once "../../../../config/configuracion.php";
	include_once "../../../../config/conexion_2.php";



	$desc_codigo="pago-";
	$name_file = "pago";

	$consultar_idarchivo = mysqli_query($conection, "SELECT max(correlativo) as conteo FROM gp_pagos_venta");
	$respuesta_idarchivo = mysqli_num_rows($consultar_idarchivo);

	if($respuesta_idarchivo>0){
		$max_idarchivo = mysqli_fetch_assoc($consultar_idarchivo);
		$idfile = $max_idarchivo['conteo'];
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