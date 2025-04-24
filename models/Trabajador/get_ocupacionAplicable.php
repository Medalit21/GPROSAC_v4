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

 $categ ="";
 $dato ="";   
$categoria_id = filter_input(INPUT_POST, 'categoria_id'); //obtenemos el parametro que viene de ajax

if($categoria_id != ''){ //verificamos nuevamente que sea una opcion valida

  $ver_categoria = mysqli_query($conection, "SELECT nombre_corto as nombre FROM Configuracion_detalle WHERE idconfig_detalle='$categoria_id'");
  $ver_categoriar = mysqli_fetch_assoc($ver_categoria);

  $categ = $ver_categoriar['nombre'];

  if($categ=="EJECUTIVO"){$dato="valor1";}else{if($categ=="OBRERO"){$dato="valor3";}else{if($categ=="EMPLEADO"){$dato="valor2";}}}

  $sql = "SELECT idconfig_detalle as ID, nombre_corto as nombre FROM Configuracion_detalle WHERE codigo_tabla='_OCUPACION_SP' AND $dato='1' ORDER BY nombre";  
  $query = mysqli_query($conection, $sql);
  $filas = mysqli_fetch_all($query, MYSQLI_ASSOC); 
}


?>
<?php foreach($filas as $op): //creamos las opciones a partir de los datos obtenidos ?>
<option class="f-box" value="<?= $op['ID'] ?>"><?= $op['nombre'] ?></option>
<?php endforeach; ?>