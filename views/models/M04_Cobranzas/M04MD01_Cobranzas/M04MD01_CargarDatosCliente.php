<?php
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d');
   
  $tipodoc = '1'; 
  $doc = '40524285';
  $nac = '200';

   
   $data = array();
   $dataList = array();


if(isset($_POST['btnCargarDatosCliente'])){
    
    $valor_documento = $_POST['documento'];
    $valor_idlote = $_POST['idlote'];
    
    $query_documento = "";
    $query_lote = "";
    
    if(!empty($valor_documento)){
        $query_documento = "AND dc.documento='$valor_documento'";
    }
    
    if(!empty($valor_idlote)){
        $query_lote = "AND gpv.id_lote='$valor_idlote'";
    }
   
           $query = mysqli_query($conection,"SELECT 
           dc.id as id, 
           dc.tipodocumento as tp, 
           dc.nacionalidad as nac, 
           dc.documento as doc, 
           dc.apellido_paterno as ap, 
           dc.apellido_materno as am, 
           dc.nombres as nom,
           gpv.id_venta as idventa
           FROM datos_cliente dc
           INNER JOIN gp_venta AS gpv ON gpv.id_cliente=dc.id
           $query_documento
           $query_lote
       ");
       if($query->num_rows > 0){
           $resultado = $query->fetch_assoc();
           $data['status'] = 'ok';
           $data['data'] = $resultado;
       }else{
           $data['status'] = 'bad';
           $data['data'] = 'No se encontraron registros del cliente seleccionado.';
       }
    
   header('Content-type: text/javascript');
   echo json_encode($data,JSON_PRETTY_PRINT);
}

 ?>