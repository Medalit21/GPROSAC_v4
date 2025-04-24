<?php
session_start();
	/*$host = 'localhost';
	$user = 'acgsoft_appVisitas';
	$password = 'adm2019acg';
	$db = 'acgsoft_Nominas';
   /**/
	$host = 'localhost';
	$user = 'root';
	$password = '';
	$db = 'acgsoft_nominas';
     
	$conection = mysqli_connect($host,$user,$password,$db);

	if(!$conection){
		echo "Error en la conexi贸n";
	}

$departamento_id = filter_input(INPUT_POST, 'departamento_id'); //obtenemos el parametro que viene de ajax

if($departamento_id != ''){ //verificamos nuevamente que sea una opcion valida

  $sql = "SELECT codigo as ID, nombre as nom FROM ubigeo_provincia WHERE codigo_region='$departamento_id' ORDER BY nombre";  
  $query = mysqli_query($conection, $sql);
  $filas = mysqli_fetch_all($query, MYSQLI_ASSOC); 
}


?>
<?php foreach($filas as $op): //creamos las opciones a partir de los datos obtenidos ?>
<option class="f-box" value="<?= $op['ID'] ?>"><?= $op['nom'] ?></option>
<?php endforeach; ?>