<?php
   //session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d');

   $data = array();
   $dataList = array();
   //$varlor=$_POST['nombre'];

if(isset($_POST['ReturnListaProyectos'])){


    $txtFiltroNombre = isset($_POST['txtFiltroNombre']) ? $_POST['txtFiltroNombre'] : Null;
    $txtFiltroNombrer = trim($txtFiltroNombre);

    $bxFiltroDepartamento = isset($_POST['bxFiltroDepartamento']) ? $_POST['bxFiltroDepartamento'] : Null;
    $bxFiltroDepartamentor = trim($bxFiltroDepartamento);


    $bxFiltroProvincia = isset($_POST['bxFiltroProvincia']) ? $_POST['bxFiltroProvincia'] : Null;
    $bxFiltroProvinciar = trim($bxFiltroProvincia);


    $bxFiltroDistrito = isset($_POST['bxFiltroDistrito']) ? $_POST['bxFiltroDistrito'] : Null;
    $bxFiltroDistritor = trim($bxFiltroDistrito);


    $query_nombre="";
    $query_departamento="";
    $query_provincia="";
    $query_distrito="";

    if(!empty($txtFiltroNombrer)){
        $query_nombre="AND gpp.idproyecto ='".$txtFiltroNombrer."'";
    }

    if(!empty($bxFiltroDepartamentor)){
        $query_departamento="AND gpp.departamento='".$bxFiltroDepartamentor."'";
    }

    if(!empty($bxFiltroProvinciar)){
        $query_provincia="AND gpp.provincia='".$bxFiltroProvinciar."'";
    }

    if(!empty($bxFiltroDistritor)){
        $query_distrito="AND gpp.distrito='".$bxFiltroDistritor."'";
    }

    //echo json_encode($data);
    $query = mysqli_query($conection,"SELECT gpp.idproyecto as id, 
    gpp.nombre as nombre, 
    gpp.direccion as direccion,
    ur.nombre as departamento,
    up.nombre as provincia,
    ud.nombre as distrito,
    format(gpp.area,2) as area,
    gpp.nro_zonas as nro_zonas,
    (select count(gpm.idmanzana) 
    from gp_proyecto gpy
    inner join gp_zona as gpz on gpz.idproyecto=gpy.idproyecto
    inner join gp_manzana as gpm on gpm.idzona=gpz.idzona
    where gpy.idproyecto=gpp.idproyecto) as nro_manzanas,
    (select count(gpl.idlote) 
    from gp_proyecto gpy
    inner join gp_zona as gpz on gpz.idproyecto=gpy.idproyecto
    inner join gp_manzana as gpm on gpm.idzona=gpz.idzona
    inner join gp_lote as gpl on gpl.idmanzana=gpm.idmanzana
    where gpy.idproyecto=gpp.idproyecto) as nro_lotes
    FROM gp_proyecto gpp            
    INNER JOIN  ubigeo_region AS ur ON (ur.codigo=gpp.departamento OR gpp.departamento IS NULL)
    INNER JOIN ubigeo_provincia AS up ON (up.codigo=gpp.provincia OR gpp.provincia IS NULL)
    INNER JOIN ubigeo_distrito AS ud ON (ud.codigo=gpp.distrito OR gpp.distrito IS NULL)
    WHERE gpp.esta_borrado=0 
    AND gpp.estado=1 
    $query_nombre
    $query_departamento
    $query_provincia
    $query_distrito
    ORDER BY gpp.creado DESC"); 

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            /*$data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
*/
            array_push($dataList,[
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'direccion' => $row['direccion'],
                'departamento' => $row['departamento'],
                'provincia' => $row['provincia'],
                'distrito' => $row['distrito'],
                'area' => $row['area'],
                'nro_zonas' => $row['nro_zonas'],
                'nro_manzanas' => $row['nro_manzanas'],
                'nro_lotes' => $row['nro_lotes']
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
