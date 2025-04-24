<?php
 
   session_start();
   date_default_timezone_set('America/Lima');
   header("Content-Type: text/html;charset=utf-8");
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";

   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   
   $username = $_SESSION['usu'];
    $consulta_id = mysqli_query($conection, "SELECT idusuario FROM usuario WHERE usuario='$username'");
    $consulta_idr = mysqli_fetch_assoc($consulta_id);
    $ids = $consulta_idr['idusuario'];  

    $data = array();
 
 if(!empty($_POST)){

      $idlote = $_POST['data']; 
      $archivo = $_FILES["filePlantilla"];
      
      move_uploaded_file($_FILES['filePlantilla']['tmp_name'], '../../../M03_Ventas/M03SM02_Venta/temporal/plantilla.xlsx');

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apigprosac.acg-soft.com',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('file'=> new CURLFILE('../../../M03_Ventas/M03SM02_Venta/temporal/plantilla.xlsx'),'id_lote' => ''.$idlote.''),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: multipart/form-data'
          ),
        ));
        
        
       $response = curl_exec($curl);
        curl_close($curl);
        
        $resultado = json_decode($response, true);
        $status ="";
        $status = $resultado["status"];
        /*$codigo = $resultado["cod"];
        $mensaje = $resultado["mensaje error"];*/
        
        //CONSULTAR INFORMACION CARGADA Y ASIGNAR CORRELATIVO
        
        $consultar_informacion = mysqli_query($conection, "SELECT id as id, item_letra as letra FROM gp_cronograma_temporal WHERE id_lote='$idlote' ORDER BY id ASC");
        $respuesta_informacion = mysqli_num_rows($consultar_informacion);
        
        if($respuesta_informacion>0){
            $contador=1;
            while($row = $consultar_informacion->fetch_assoc()){
                
                $id_registro = $row['id'];
                $letra = $row['letra'];
                $ci = 0;
                
                if($letra=="C.I."){
                    $ci = 1;
                }else{
                    $ci = 0;
                }
                
                //AÑADIR EL CORRELATIVO
                $actualiza_cronograma = mysqli_query($conection, "UPDATE gp_cronograma_temporal SET correlativo='$contador', es_cuota_inicial='$ci' WHERE id='$id_registro'");
                $contador= $contador + 1;
            }
        }

     /*if($codigo == "200"){*/
         $data['status'] = "ok";
         $data['data'] = "Se completo la importacion del cronograma";
     /*}else{
         $data['status'] = "bad";
         $data['data'] = "No se encontraron registros el formato ingresado. Motivo : ".$mensaje;
      } */

     header('Content-type: text/javascript');
      echo json_encode($data,JSON_PRETTY_PRINT);
 } 
 ?>