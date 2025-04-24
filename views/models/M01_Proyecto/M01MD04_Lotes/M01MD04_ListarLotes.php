<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   
   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

   $data = array();
   $dataList = array();
   //$varlor=$_POST['nombre'];
if(!empty($_POST)){


    $cbxManzanaslt = isset($_POST['cbxManzanaslt']) ? $_POST['cbxManzanaslt'] : Null;
    $cbxManzanasltr = trim($cbxManzanaslt);

     $Start=intval($_POST['start']);
     $Length=intval($_POST['length']);
     if ($Length > 0)
     {
         $Start = (($Start / $Length) + 1);
     }
     if($Start==0){
         $Start=1;
        }
    
        
    if(empty($cbxManzanasltr)){ 
        $dato_id = 0; }
    else{ 
        $dato_id = $cbxManzanasltr; }

    //echo json_encode($data);
    $query = mysqli_query($conection,"call gppa_listar_lotes('$dato_id',$Start, $Length, '')"); 

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            array_push($dataList,[
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'tipo_moneda' => $row['tipo_moneda'],
                'area' => $row['area'],
                'vcasa' => $row['vcasa'],
                'vscasa' => $row['vscasa']
            ]);}
            
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
        $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}
