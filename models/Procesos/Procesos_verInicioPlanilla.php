<?php
    session_start();
    include "../../config/conexion_2.php";
    $año = date("Y");

$mes_id = filter_input(INPUT_POST, 'mes_id'); //obtenemos el parametro que viene de ajax

if($mes_id != ''){ //verificamos nuevamente que sea una opcion valida

  $sqll = mysqli_query($conection, "SELECT * FROM planilla_proceso WHERE año='$año'");
  $sqllr = mysqli_num_rows($sqll);
  
  if($sqllr>0){

  $sql = mysqli_query($conection, "SELECT * FROM planilla_proceso WHERE año='$año' AND mes='$mes_id'");
  $sqlr = mysqli_num_rows($sql);
  
  if($sqlr>0){
    ?>
    <option class="f-box"  value="0">NO HABILITADO</option>
    <?php
    $diseño = "color: red";
  }else{
      ?>
    <option class="f-box"  value="1">HABILITADO</option>
    <?php
  }
 }else{

    $vermes = mysqli_query($conection, "SELECT nombre_corto as Nombre FROM configuracion_detalle WHERE codigo_tabla='_MES' AND estado='ACTI' AND idconfig_detalle='$mes_id'");
    $vermesr = mysqli_fetch_assoc($vermes);

    $nombre_mes = $vermesr['Nombre'];

    if($nombre_mes!="ENERO"){
        ?>
        <option class="f-box"  value="0">NO HABILITADO</option>
        <?php
        $diseño = "color: red";
      }else{
          ?>
        <option class="f-box"  value="1">HABILITADO</option>
        <?php
        $diseño = "color: green";
    } 
 }
}

?>
    
