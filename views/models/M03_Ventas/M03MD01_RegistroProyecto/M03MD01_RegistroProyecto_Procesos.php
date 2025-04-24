<?php
   session_start(); 
   
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";

  /* $nom_user = $_SESSION['variable_user'];
   $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
   $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);*/
$IdUser=1;

$data = array();
$dataList = array();

/**************************INSERTAR NUEVO REGISTRO TRABAJADOR******************* */
if (isset($_POST['ReturnGuardarRegProyecto'])) {

    $Descripcion = $_POST['txtDescripcion'];
    $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

    $TipoPropiedad = $_POST['cbxTipoPropiedad'];
    $TipoPropiedad = !empty($TipoPropiedad) ? "'$TipoPropiedad'" : "NULL";

    $Departamento = $_POST['cbxDepartamentoDir'];
    $Departamento = !empty($Departamento) ? "'$Departamento'" : "NULL";

    $Provincia = $_POST['cbxProvinciaDir'];
    $Provincia = !empty($Provincia) ? "'$Provincia'" : "NULL";

    $Distrito = $_POST['cbxDistritoDir'];
    $Distrito = !empty($Distrito) ? "'$Distrito'" : "NULL";

    $TipoVia = $_POST['cbxTipoVia'];
    $TipoVia = !empty($TipoVia) ? "'$TipoVia'" : "NULL";

    $NombreVia = $_POST['txtNombreVia'];
    $NombreVia = !empty($NombreVia) ? "'$NombreVia'" : "NULL";

    $NroVia = $_POST['txtNroVia'];
    $NroVia = !empty($NroVia) ? "'$NroVia'" : "NULL";

    $NroDpto = $_POST['txtNroDpto'];
    $NroDpto = !empty($NroDpto) ? "'$NroDpto'" : "NULL";

    $Interior = $_POST['txtInterior'];
    $Interior = !empty($Interior) ? "'$Interior'" : "NULL";

    $Mz = $_POST['txtMz'];
    $Mz = !empty($Mz) ? "'$Mz'" : "NULL";

    $Lt = $_POST['txtLt'];
    $Lt = !empty($Lt) ? "'$Lt'" : "NULL";

    $Km = $_POST['txtKm'];
    $Km = !empty($Km) ? "'$Km'" : "NULL";

    $Block = $_POST['txtBlock'];
    $Block = !empty($Block) ? "'$Block'" : "NULL";

    $Etapa = $_POST['txtEtapa'];
    $Etapa = !empty($Etapa) ? "'$Etapa'" : "NULL";

    $TipoZona = $_POST['cbxTipoZona'];
    $TipoZona = !empty($TipoZona) ? "'$TipoZona'" : "NULL";

    $NombreZona = $_POST['txtNombreZona'];
    $NombreZona = !empty($NombreZona) ? "'$NombreZona'" : "NULL";

    $Referencia = $_POST['txtReferencia'];
    $Referencia = !empty($Referencia) ? "'$Referencia'" : "NULL";
   
    $AreaTotal = $_POST['txtAreaTotal'];
    $AreaTotal = !empty($AreaTotal) ? "'$AreaTotal'" : "NULL";

    $AreaContruida = $_POST['txtAreaConstruida'];
    $AreaContruida = !empty($AreaContruida) ? "'$AreaContruida'" : "NULL";

    $Precio = $_POST['txtPrecio'];
    $Precio = !empty($Precio) ? "'$Precio'" : "NULL";

    $Moneda = $_POST['cbxMoneda'];
    $Moneda = !empty($Moneda) ? "'$Moneda'" : "NULL";

    $Planta = $_POST['txtPlantas'];
    $Planta = !empty($Planta) ? "'$Planta'" : "NULL";

    $Dormitorio = $_POST['txtDormitorio'];
    $Dormitorio = !empty($Dormitorio) ? "'$Dormitorio'" : "NULL";

    $Banios = $_POST['txtBanios'];
    $Banios = !empty($Banios) ? "'$Banios'" : "NULL";

    $Cocheras = $_POST['txtCocheras'];
    $Cocheras = !empty($Cocheras) ? "'$Cocheras'" : "NULL";

   $Latitud = $_POST['latitud'];
    $Latitud = !empty($Latitud) ? "'$Latitud'" : "NULL";

    $Longitud = $_POST['longitud'];
    $Longitud = !empty($Longitud) ? "'$Longitud'" : "NULL";


    $query = mysqli_query($conection, "call pa_datos_proyecto_insertar(
    $Descripcion,
    $TipoPropiedad,
    $Distrito,
    $Provincia,
    $Departamento,
    $TipoVia,
    $NombreVia,
    $NroVia,
    $NroDpto,
    $Interior,
    $Mz,
    $Lt,
    $Km,
    $Block,
    $Etapa,
    $TipoZona,
    $NombreZona,
    $Referencia,
    $Latitud,
    $Longitud,
    $AreaTotal,
    $AreaContruida,
    $Precio,
    $Moneda,
    $Planta,
    $Dormitorio,
    $Banios,
    $Cocheras,
    '$IdUser'
)");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se guardó con éxito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************ACTUALIZAR REGISTRO TRABAJADOR******************* */
if (isset($_POST['ReturnActualizarRegProyecto'])) {

    $IdReg = $_POST['id'];
    $IdReg = !empty($IdReg) ? "'$IdReg'" : "NULL";

    $Descripcion = $_POST['txtDescripcion'];
    $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

    $TipoPropiedad = $_POST['cbxTipoPropiedad'];
    $TipoPropiedad = !empty($TipoPropiedad) ? "'$TipoPropiedad'" : "NULL";

    $Departamento = $_POST['cbxDepartamentoDir'];
    $Departamento = !empty($Departamento) ? "'$Departamento'" : "NULL";

    $Provincia = $_POST['cbxProvinciaDir'];
    $Provincia = !empty($Provincia) ? "'$Provincia'" : "NULL";

    $Distrito = $_POST['cbxDistritoDir'];
    $Distrito = !empty($Distrito) ? "'$Distrito'" : "NULL";

    $TipoVia = $_POST['cbxTipoVia'];
    $TipoVia = !empty($TipoVia) ? "'$TipoVia'" : "NULL";

    $NombreVia = $_POST['txtNombreVia'];
    $NombreVia = !empty($NombreVia) ? "'$NombreVia'" : "NULL";

    $NroVia = $_POST['txtNroVia'];
    $NroVia = !empty($NroVia) ? "'$NroVia'" : "NULL";

    $NroDpto = $_POST['txtNroDpto'];
    $NroDpto = !empty($NroDpto) ? "'$NroDpto'" : "NULL";

    $Interior = $_POST['txtInterior'];
    $Interior = !empty($Interior) ? "'$Interior'" : "NULL";

    $Mz = $_POST['txtMz'];
    $Mz = !empty($Mz) ? "'$Mz'" : "NULL";

    $Lt = $_POST['txtLt'];
    $Lt = !empty($Lt) ? "'$Lt'" : "NULL";

    $Km = $_POST['txtKm'];
    $Km = !empty($Km) ? "'$Km'" : "NULL";

    $Block = $_POST['txtBlock'];
    $Block = !empty($Block) ? "'$Block'" : "NULL";

    $Etapa = $_POST['txtEtapa'];
    $Etapa = !empty($Etapa) ? "'$Etapa'" : "NULL";

    $TipoZona = $_POST['cbxTipoZona'];
    $TipoZona = !empty($TipoZona) ? "'$TipoZona'" : "NULL";

    $NombreZona = $_POST['txtNombreZona'];
    $NombreZona = !empty($NombreZona) ? "'$NombreZona'" : "NULL";

    $Referencia = $_POST['txtReferencia'];
    $Referencia = !empty($Referencia) ? "'$Referencia'" : "NULL";
   
    $AreaTotal = $_POST['txtAreaTotal'];
    $AreaTotal = !empty($AreaTotal) ? "'$AreaTotal'" : "NULL";

    $AreaContruida = $_POST['txtAreaConstruida'];
    $AreaContruida = !empty($AreaContruida) ? "'$AreaContruida'" : "NULL";

    $Precio = $_POST['txtPrecio'];
    $Precio = !empty($Precio) ? "'$Precio'" : "NULL";

    $Moneda = $_POST['cbxMoneda'];
    $Moneda = !empty($Moneda) ? "'$Moneda'" : "NULL";

    $Planta = $_POST['txtPlantas'];
    $Planta = !empty($Planta) ? "'$Planta'" : "NULL";

    $Dormitorio = $_POST['txtDormitorio'];
    $Dormitorio = !empty($Dormitorio) ? "'$Dormitorio'" : "NULL";

    $Banios = $_POST['txtBanios'];
    $Banios = !empty($Banios) ? "'$Banios'" : "NULL";

    $Cocheras = $_POST['txtCocheras'];
    $Cocheras = !empty($Cocheras) ? "'$Cocheras'" : "NULL";

   $Latitud = $_POST['latitud'];
    $Latitud = !empty($Latitud) ? "'$Latitud'" : "NULL";

    $Longitud = $_POST['longitud'];
    $Longitud = !empty($Longitud) ? "'$Longitud'" : "NULL";


    $query = mysqli_query($conection, "call pa_datos_proyecto_actulizar(
    $IdReg,
    $Descripcion,
    $TipoPropiedad,
    $Distrito,
    $Provincia,
    $Departamento,
    $TipoVia,
    $NombreVia,
    $NroVia,
    $NroDpto,
    $Interior,
    $Mz,
    $Lt,
    $Km,
    $Block,
    $Etapa,
    $TipoZona,
    $NombreZona,
    $Referencia,
    $Latitud,
    $Longitud,
    $AreaTotal,
    $AreaContruida,
    $Precio,
    $Moneda,
    $Planta,
    $Dormitorio,
    $Banios,
    $Cocheras,
    '$IdUser'
)");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se actualizo con éxito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
/**************************LISTA PAGINADA PROYECTOS******************* */
if(isset($_POST['ReturnProyectoPag'])){
    
    $Descripcion=$_POST['descripcion'];
    $Tipo=$_POST['tipo'];
    $Departamento=$_POST['departamento'];
    $Provincia=$_POST['provincia'];
    $Distrito=$_POST['distrito'];
   

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
    $query = mysqli_query($conection,"call pa_datos_proyecto_lista_paginada($Start,$Length,'$ColumnaOrden',
    '$Descripcion','$Tipo','$Departamento','$Provincia','$Distrito')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,$row);
        }
        $data['data'] = $dataList;
    }else{
        $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT) ;
} 

/**************************ELIMINAR REGISTRO PROYECTO******************* */
if (isset($_POST['ReturnEliminarRegProyecto'])) {
    $IdReg = $_POST['id'];
    $query = mysqli_query($conection, "UPDATE datos_proyecto
    SET  esta_borrado=1,borrado=NOW(),id_usuario_auditoria=$IdUser
    WHERE id=$IdReg");
    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se elimino con éxito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}