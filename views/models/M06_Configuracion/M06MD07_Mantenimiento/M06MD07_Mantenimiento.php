<?php

session_start(); 
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";


$IdUser=1;
$hora = date("H:i:s", time());
$fecha = date('Y-m-d'); 
$data = array();
$dataList = array();

/**************************INSERTAR NUEVO REGISTRO TRABAJADOR******************* */
if (isset($_POST['ReturnGuardarRegCliente'])) {

    $Categoria = $_POST['cbxCategoria'];
    $Codigo = $_POST['txtCoditem'];
    $Nombres= $_POST['txtNombres'];
    $Abreviatura = $_POST['txtAbreviat']; 
	$Texto1 = $_POST['txtTexto1'];
	$Texto2 = $_POST['txtTexto2'];
	$Texto3 = $_POST['txtTexto3'];
	$Texto4 = $_POST['txtTexto4'];
	$Texto5 = $_POST['txtTexto5'];
	$cbxEstado = $_POST['cbxEstado'];
    
    $actualiza = $fecha.' '.$hora;
    
    //INSERTAR DATOS USUARIO
    $query = mysqli_query($conection, "INSERT INTO configuracion_detalle(empresa, codigo_tabla, codigo_sunat, codigo_item, nombre_largo, nombre_corto, texto1, texto2, texto3, texto4, texto5, estado) VALUES
   
   ('000',
   '$Categoria',
    '$Codigo',
    '$Codigo',
    '$Nombres',
    '$Abreviatura',
    '$Texto1',
    '$Texto2',
    '$Texto3',
    '$Texto4',
    '$Texto5',
    'ACTI')");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se guardaron los datos del servicio.';
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


if (isset($_POST['btnBuscarDocumento'])) {

    $cbxTipoDocumento = $_POST['cbxTipoDocumento']; 
    $txtNroDocumento = $_POST['txtNroDocumento'];   

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero='.$txtNroDocumento,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Referer: http://apis.net.pe/api-ruc',
        'Authorization: Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N'
    ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    //echo $response;    
    $datos = json_decode($response, true);     

    $operacion = 2;
    if(empty($datos["error"])){            
        $val_reniec = "ok";
        $apellidos = $datos["apellidoPaterno"].' '.$datos["apellidoMaterno"];
        $nombres = $datos["nombres"];            
    }else{
        $val_reniec = "bad";
        $apellidos = $datos["apellidoPaterno"].' '.$datos["apellidoMaterno"];
        $nombres = $datos["nombres"];
        $error_api="(Error: ".$datos["error"].")";    
    }

    if($val_reniec == "ok"){
        $data['status'] = 'ok';
        $data['apellidos'] = $apellidos;
        $data['nombres'] = $nombres;
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'No se encontraron resultados para el nro documento ingresado. '.$error_api;
        $data['apellidos'] = $apellidos;
        $data['nombres'] = $nombres;
     }  
    

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************BUSCAR EXISTENCIA DOCUMENTO******************* */
if (isset($_POST['ReturnVerificaExixtencia'])) {

    $TipoDocumento = $_POST['tipoDocumento'];
    $Documento = $_POST['documento'];
    
    $query = mysqli_query($conection, "SELECT
	   idpersona,
	   ifnull( pers.DNI ,'') as documento,
	   ifnull(pers.apellido,'') as apellidos,
	   ifnull(pers.nombre ,'') as nombres,
	   pers.estatus
	   FROM persona pers
	   WHERE pers.tipodocumento=$TipoDocumento AND pers.DNI='$Documento'  limit 1;");
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
if(isset($_POST['ReturnPersonalListaPaginada'])){
    
	$cbxFiltroTrabajador=$_POST['cbxFiltroTrabajador'];
	
    $cbxFiltroEstado=$_POST['cbxFiltroEstado'];
    

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

    $query = mysqli_query($conection,"call gppa_listar_Mantenimiento($Start,$Length,'$ColumnaOrden', '$cbxFiltroTrabajador', '$cbxFiltroEstado')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,[
                'idconfig_detalle'=> $row['idconfig_detalle'],
                'categoria'=> $row['categoria'],
                'codigo_tabla' => $row['codigo_tabla'],
                'nombre_largo' => $row['nombre_largo'],
                'nombre_corto' => $row['nombre_corto'],
                'estado'=>  $row['estado']
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

if(isset($_POST['btnMostrarAdjunto'])){
    
    $idRegistro = $_POST['idRegistro'];

    $consultar_adjunto = mysqli_query($conection, "SELECT file_documento as adjunto FROM persona WHERE idpersona='$idRegistro'");
    $respuesta_adjunto = mysqli_fetch_assoc($consultar_adjunto);

    $nom_adjunto = $respuesta_adjunto['adjunto'];

    $cadena = explode(".", $nom_adjunto);
    $formato = $cadena[1];
    
    $data['status'] = 'ok';
    $data['formato'] = $formato;
    $data['adjunto'] = $nom_adjunto;
    $data['id'] = $idRegistro;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

/**************************OBTENER DETALLE MANTENIMIENTO******************* */
if (isset($_POST['ReturnDetalleRegistroCliente'])) {
    $IdReg = $_POST['IdRegistro'];
    $query = mysqli_query($conection, "SELECT 
		det.idconfig_detalle AS idconfig_detalle, 
	cab.iddcategoria AS iddcategoria, 
	cab.nombre_largo AS categoria, 
	det.codigo_tabla AS codigo_tabla,
	det.nombre_largo AS nombre_largo,
	det.nombre_corto AS nombre_corto,
	det.codigo_item AS codigo_item,
	det.texto1 AS texto1,
	det.texto2 AS texto2,
	det.texto3 AS texto3,
	det.texto4 AS texto4,
	det.texto5 AS texto5,
	det.estado AS estado,
	det.fecha_registro AS fecha_registro
	FROM configuracion_detalle det
	JOIN configuracion_cabecera cab ON det.codigo_tabla = cab.codigo_tabla
    WHERE det.idconfig_detalle='$IdReg';
	");
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
/*if (isset($_POST['ReturnActualizarRegCliente'])) {

    $Id = $_POST['id'];

	$Nombres= $_POST['txtNombres'];
    $Abreviatura = $_POST['txtAbreviat']; 
	$Texto1 = $_POST['txtTexto1'];
	$Texto2 = $_POST['txtTexto2'];
	$Texto3 = $_POST['txtTexto3'];
	$Texto4 = $_POST['txtTexto4'];
	$Texto5 = $_POST['txtTexto5'];
	$cbxEstado = $_POST['cbxEstado'];

    $query = mysqli_query($conection, "UPDATE configuracion_detalle SET
    nombre_largo='$Nombres',
    nombre_corto='$Abreviatura',
    texto1='$Texto1',
    texto2='$Texto2',
    texto3='$Texto3',
    texto4='$Texto4',
    texto5='$Texto5',
    estado='$cbxEstado'
	
    WHERE idconfig_detalle='$Id'");

	if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se actualizo el registro con éxito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}*/


/******* new *******/
if (isset($_POST['ReturnActualizarRegCliente'])) {
    $Id = $_POST['id'];
    $CodigoSunat = $_POST['codigo_sunat'];  // Example for additional fields
    $CodigoItem = $_POST['codigo_item'];
    $NombreCorto = $_POST['txtAbreviat'];
    $NombreLargo = $_POST['txtNombres'];
    $Texto1 = $_POST['txtTexto1'];
    $Texto2 = $_POST['txtTexto2'];
    $Texto3 = $_POST['txtTexto3'];
    $Texto4 = $_POST['txtTexto4'];
    $Texto5 = $_POST['txtTexto5'];
    $Estado = $_POST['cbxEstado'];
    $UsuarioModifica = 'some_user_id';  // Assuming you fetch this from session or similar
    $FechaModifica = date('Y-m-d H:i:s');  // Current timestamp

    $sql = "UPDATE configuracion_detalle SET
        codigo_sunat='$CodigoSunat',
        codigo_item='$CodigoItem',
        nombre_corto='$NombreCorto',
        nombre_largo='$NombreLargo',
        texto1='$Texto1',
        texto2='$Texto2',
        texto3='$Texto3',
        texto4='$Texto4',
        texto5='$Texto5',
        estado='$Estado',
        usuario_modifica='$UsuarioModifica',
        fecha_modifica='$FechaModifica'
        WHERE idconfig_detalle='$Id'";

    $query = mysqli_query($conection, $sql);

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se actualizo el registro con éxito';
    } else {
        $data['dataDB'] = mysqli_error($conection);
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro: ' . $data['dataDB'];
    }
    header('Content-type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/******* new *******/

/**************************ELIMINAR REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnEliminarRegPersonal'])) {
    $Id = $_POST['id'];
    $query = mysqli_query($conection, "UPDATE persona
    SET estatus= 'Inactivo'
    WHERE idpersona= $Id");
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

/**************************LISTA PAGINADA DE CLIENTES EN BAJA******************* */
if(isset($_POST['ReturnClienteEnBajaListaPaginada'])){
    $DocumentoFiltro=$_POST['txtDniFiltro'];
    $NombresApellidos=$_POST['txtApeNomFiltro'];

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

    $query = mysqli_query($conection,"call pa_datos_cliente_en_baja_lista_paginada($Start,$Length,'$ColumnaOrden','$DocumentoFiltro','$NombresApellidos')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,[
                'id' => $row['id'],
                'documento'=> $row['documento'],
                'documentoCadena' => $row['documentoCadena'],
                'apellidos' => $row['apellidos'],
                'nombres'=> $row['nombres'],
                'fechaNacimiento' => $row['fechaNacimiento'],
                'email' => $row['email'],
                'celularTelefono'=>  $row['celularTelefono']
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

/**************************REESTABLECER REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnDarAltaRegCliente'])) {

    $Id= $_POST['id'];

    $query = mysqli_query($conection, 
	"UPDATE persona
    SET estatus= 'Inactivo',
	idusuario= $IdUser
    WHERE idpersona= $Id");

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

/**************************OBTENER EL CODIGO ITEM CAT ******************* */
if (isset($_POST['ReturnTraerCodItem'])) {
    $IdReg = $_POST['IdRegistro'];
    $query = mysqli_query($conection, "SELECT COUNT(det.idconfig_detalle)+1 AS total
		FROM configuracion_detalle det
		JOIN configuracion_cabecera cab ON det.codigo_tabla = cab.codigo_tabla
		WHERE cab.codigo_tabla = '$IdReg';
	");
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

/**************************OBTENER EL CODIGO ITEM CAT ******************* */
