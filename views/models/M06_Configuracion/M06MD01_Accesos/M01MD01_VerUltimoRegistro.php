<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 

   $valor_user = $_SESSION['variable_user'];
    //consultar id usuario
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$valor_user'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $idusuario = $respuesta_idusuario['id'];

    $data = array();
    $dataList = array();
    //$varlor=$_POST['nombre'];
    if(!empty($_POST)){

        $Start=intval($_POST['start']);
        $Length=intval($_POST['length']);
        if ($Length > 0)
        {
            $Start = (($Start / $Length) + 1);
        }
        if($Start==0){
            $Start=1;
            }

        //Consultar ultima empresa registrada
        $consultar_empresa = mysqli_query($conection, "SELECT max(id) as id FROM configuracion_empresas WHERE user_registro='$idusuario'");
        $respuesta_empresa = mysqli_fetch_assoc($consultar_empresa);

        $idempresa = $respuesta_empresa['id'];

        //echo json_encode($data);
        $query = mysqli_query($conection,"call pa_listar_EmpresasUltimo($Start,$Length,'','$idempresa')"); 

        if($query->num_rows > 0){
        
            while($row = $query->fetch_assoc()) {
                
                $data['recordsTotal'] = intval($row["TotalRegistros"]);
                $data['recordsFiltered'] = intval($row["TotalRegistros"]);

                array_push($dataList,[
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'ruc' => $row['ruc'],
                    'responsable' => $row['responsable']
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
?>