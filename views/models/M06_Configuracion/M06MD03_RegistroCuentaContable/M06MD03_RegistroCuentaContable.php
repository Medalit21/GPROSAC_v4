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
if (isset($_POST['ReturnGuardarRegCuent'])) {
	
    $descripcionC= $_POST['txtDescripCorta'];
    $descripcionL= $_POST['txtDescripLarga'];
    $CuentaContableUSD = $_POST['txtCuentaContableUSD']; 
	$CuentaContablePEN = $_POST['txtCuentaContablePEN'];
	$estado = $_POST['cbxEstado'];
	$empresa = $_POST['txtempresa'];
	$codigotabla = $_POST['txtcodigotabla'];

    $query = mysqli_query($conection, "call gprosac_sp_insertar_cuentacont( 
    '$descripcionC',
    '$descripcionL',
    '$CuentaContableUSD',
	'$CuentaContablePEN',
	'$estado',
	'$empresa',
	'$codigotabla')");

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


/**************************BUSCAR EXISTENCIA DOCUMENTO******************* */

if (isset($_POST['ReturnVerificaExixtencia'])) {

    $Descripcion = $_POST['descripcion'];
    $CuentaContableUSD = $_POST['cuentacontusd'];
    $CuentaContablePEN = $_POST['cuentacontpen'];
    
    $query = mysqli_query($conection, "SELECT 
		cd.idconfig_detalle as id, 
		ifnull(cd.nombre_corto ,'') as DescrCort, 
		ifnull(cd.nombre_largo, '') as DescrLarg, 
		ifnull(cd.texto2, '') as CuentaContableUSD,
		ifnull(cd.texto3, '') as CuentaContablePEN,
		ifnull(cd.texto1, '') as estado
		FROM configuracion_detalle  cd
		WHERE cd.codigo_tabla='_BANCOS' AND cd.nombre_corto='Descripcion'");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************LISTA PAGINADA DE CLIENTES******************* */
if(isset($_POST['ReturnCuentContListaPaginada'])){
	$descripcionFiltro=$_POST['txtDescripCortaFiltro'];
    

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

    $query = mysqli_query($conection,"call gprosac_sp_listar_cuentacont($Start,$Length,'$ColumnaOrden','$descripcionFiltro')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,[
                'idconfig_detalle'=> $row['id'],
                'nombre_corto' => $row['DescrCort'],
                'texto2' => $row['CuentaContableUSD'],
                'texto3' => $row['CuentaContablePEN']
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

/**************************OBTENER DETALLE REGISTRO CLIENTE******************* */

if (isset($_POST['ReturnDetalleRegistroCuent'])) {
    $IdReg = $_POST['IdRegistro'];
    
	$query = mysqli_query($conection, "SELECT cd.idconfig_detalle as id, 
	ifnull(cd.nombre_corto, '') as DescrCort, 
	ifnull(cd.nombre_largo, '') as DescrLarg, 
	ifnull(cd.texto2, '') as CuentaContableUSD,
	ifnull(cd.texto3, '') as CuentaContablePEN,
	ifnull(cd.texto1, '') as estado
	FROM configuracion_detalle  cd
	WHERE cd.codigo_tabla='_BANCOS' AND cd.idconfig_detalle=$IdReg");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrió un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/**************************GUARDAR ACTUALIZAR REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnActualizarRegCliente'])) {

    /*****************ID REGISTROS PRINCIPALES****************** */
    $Id = $_POST['id'];
    /********************DATOS PERSONALES******************** */
	
	$DescrCort_a = $_POST['txtDescripCorta'];
    $DescrLarg_a = $_POST['txtDescripLarga']; 
	$CuentaContableUSD_a = $_POST['txtCuentaContableUSD'];
	$CuentaContablePEN_a = $_POST['txtCuentaContablePEN'];
	$estado_a = $_POST['cbxEstado'];
   

    $query = mysqli_query($conection, "call gprosac_sp_actualizar_cuentacont(
    '$Id',
	'$DescrCort_a',
    '$DescrLarg_a',
    '$CuentaContableUSD_a',
    '$CuentaContablePEN_a',
	'$estado_a')");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se actualizó con éxito';
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

/**************************ELIMINAR REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnEliminarRegCliente'])) {
    $Id = $_POST['id'];
    $query = mysqli_query($conection, "UPDATE configuracion_detalle
	SET estado= 'INAC'
	WHERE idconfig_detalle= $Id");
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

/**************************REESTABLECER REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnDarAltaRegCliente'])) {

    $Id= $_POST['id'];

    $query = mysqli_query($conection, "UPDATE configuracion_detalle
	SET estado= 'INAC'
	WHERE idconfig_detalle= $Id");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'El registro fue Restablecido con éxito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al dar de alta el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

