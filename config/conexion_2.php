<?php
	//CONEXION SERVIDOR gprosac.acg-soft.com
	
	$host = '216.246.46.167';
	$user = 'acgsoft_gpros4c25';
	$password = '5[_9EJ|hI>v2';
	$db = 'acgsoft_gpros4c_gprosac';
	
   /*
	$host = '216.246.46.167';
	$user = 'acgsoft_gprosac';
	$password = 'eDew&)usIF3Y';
	$db = 'acgsoft_gprosac';
     */
    
    //session_start();
    $_SESSION['host'] = $host;
    $_SESSION['user'] = $user;
    $_SESSION['password'] = $password;
    $_SESSION['db'] = $db;
    
	$conection = mysqli_connect($host,$user,$password,$db);
	$conection->set_charset("utf8");


	if(!$conection){
		echo "Error en la conexion";
	}

?>