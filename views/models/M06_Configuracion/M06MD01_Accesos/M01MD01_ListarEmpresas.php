<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   
   $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
   $IdUser=$respuesta_idusu['id'];

   $data = array();
   $dataList = array();

if(!empty($_POST)){

    $Parametro = isset($_POST['Parametro']) ? $_POST['Parametro'] : Null;
    $Parametror = trim($Parametro);

    $Start=intval($_POST['start']);
    $Length=intval($_POST['length']);

    if ($Length > 0){$Start = (($Start / $Length) + 1);}
    if ($Start==0){$Start=1;}

    $query = mysqli_query($conection,"call Nombre_Procedimiento_Almacenado + parametros"); 

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'nombre_campo' => $row['nombre_campo']
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
