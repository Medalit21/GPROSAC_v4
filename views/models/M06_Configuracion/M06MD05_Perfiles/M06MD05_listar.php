<?php
session_start(); 
   
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";

/*$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);*/
$IdUser=1;

$data = array();
$dataList = array();


if(isset($_POST['ListarPerfiles'])){

    $ColumnaOrden=$_POST['columns'][$_POST['order']['0']['column']]['data'].$_POST['order']['0']['dir'];

     $Start=intval($_POST['start']);
     $Length=intval($_POST['length']);
     if ($Length > 0)
     {
         $Start = (($Start / $Length) + 1);
     }
     if($Start==0){
         $Start=1;
    }

    $query = mysqli_query($conection,"call gprosac_sp_listar_perfiles('$Start','$Length','$ColumnaOrden')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,[
                'id' => $row['id'],
                'perfil'=> $row['perfil'],
                'registro' => $row['registro'],
                'area' => $row['area'],
                'estado'=> $row['estado'],
                'contador'=> $row['contador']
            ]);
		}
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