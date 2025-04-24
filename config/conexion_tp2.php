<?php
/*
    $host = 'localhost';
	$user = 'acgsoft_appVisitas';
	$password = 'adm2019acg';
	$db = 'acgsoft_Nominas';
	*/
	
	$host = '216.246.46.167';
	$user = 'acgsoft_gpros4c25';
	$password = '5[_9EJ|hI>v2';
	$db = 'acgsoft_gpros4c_gprosac';
     
     /*
    $host = 'localhost';
	$user = 'root';
	$password = '1234';
	$db = 'acgsoft_nominas';
     */
	$conection = mysqli_connect($host,$user,$password,$db);
	$conection->set_charset("utf8");


	if(!$conection){
		echo "Error en la conexi贸n";
	}

?>