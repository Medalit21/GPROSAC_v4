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

    $TipoDocumento = $_POST['cbxTipoDocumento'];
    $Documento = $_POST['txtDocumento'];
    $Apellidos= $_POST['txtApellidos'];
    $Nombres = $_POST['txtNombres']; 
	$Sexo = $_POST['cbxSexo'];
	$FechaNacimiento = $_POST['txtFechaNacimiento'];
	$txtTelefono = $_POST['txtTelefono'];
	$txtemail = $_POST['txtemail'];
	$txtDireccion = $_POST['txtDireccion'];
	$txtUsuario = $_POST['txtUsuario'];
	$txtDatoUser = $_POST['txtDatoUser'];
	$txtpassword = $_POST['txtpassword'];
	$txtpassword2 = $_POST['txtpassword2'];
	$cbxEstado = $_POST['cbxEstado'];
	$cbxCargo = $_POST['cbxCargo'];
	$cbxArea = $_POST['cbxArea'];
	$cbxPerfilUsu = $_POST['cbxPerfilUsu'];
    $JefeInm = $_POST['cbxJefeInmed'];
    
    $constancia = $_POST['constancia'];
    
    $txtUsuario = decrypt($txtUsuario, "123");
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $val_idusuario = $respuesta_idusuario['id'];
    
    $actualiza = $fecha.' '.$hora;
    
    //INSERTAR DATOS USUARIO
    $insertar_usuario = mysqli_query($conection, "INSERT INTO usuario(usuario, clave, rol, estado, estatus, idPerfil, fecha, hora, MotivoEstado, FecIniEstado, DNI, control_usuario) VALUES
    ('$txtDatoUser',
    '$txtpassword',
    '1',
    '$cbxEstado',
    'Activo',
    '$cbxPerfilUsu',
    '$fecha',
    '$hora',
    '7',
    '$fecha',
    '$Documento','$val_idusuario')");
    
    $query_file = "NULL";
    if(!empty($constancia)){
        $path = $constancia;
        $file = new SplFileInfo($path);
        $extension  = $file->getExtension();
        $desc_codigo="documento-";
        $name_file = "documento";
        if(!empty($constancia)){
            $name_file = $desc_codigo.$TipoDocumento.$Documento.".".$extension;
        }
        
        $query_file = "'$name_file'";
    }
    
    //CONSULTAR ID USUARIO INGRESADO
    $consultar_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtDatoUser' AND clave='$txtpassword' AND DNI='$Documento'");
    $respuesta_idusu = mysqli_fetch_assoc($consultar_idusu);
    $id_user = $respuesta_idusu['id'];
    
    //INSERTAR DATOS DE PERSONAL
    $query = mysqli_query($conection, "INSERT INTO persona(tipodocumento, DNI, apellido, nombre, direccion, Telefono, correo, FechaNacimiento, idsexo, idCargo, idArea, idusuario, idJefeInmediato, estado, estatus, control_usuario, file_documento) VALUES
    ('$TipoDocumento',
    '$Documento',
    '$Apellidos',
    '$Nombres',
    '$txtDireccion',
    '$txtTelefono',
    '$txtemail',
    '$FechaNacimiento',
    '$Sexo',
    '$cbxCargo',
    '$cbxArea',
    '$id_user',
    '$JefeInm',
    '$cbxEstado',
    'Activo',
    '$val_idusuario',
    $query_file)");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se guardaron los datos del usuario.';
        $data['name_file'] = $name_file;
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

    $query = mysqli_query($conection,"call gppa_listar_RegPersonal($Start,$Length,'$ColumnaOrden', '$cbxFiltroTrabajador', '$cbxFiltroEstado')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,[
                'id' => $row['id'],
                'DNI'=> $row['DNI'],
                'documentoCadena' => $row['documentoCadena'],
                'apellido' => $row['apellido'],
                'nombre'=> $row['nombre'],
                'FechaNacimiento' => $row['FechaNacimiento'],
                'Telefono'=>  $row['Telefono'],
                'adjunto'=>  $row['adjunto'],
                'Area'=>  $row['Area'],
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

/**************************OBTENER DETALLE REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnDetalleRegistroCliente'])) {
    $IdReg = $_POST['IdRegistro'];
    $query = mysqli_query($conection, "SELECT idpersona as id,
    ifnull(pers.tipodocumento ,'') as tipoDocumento,
    ifnull(pers.DNI ,'') as documento,
    ifnull(pers.apellido,'') as apellidos,
    ifnull(pers.nombre ,'') as nombres,
    ifnull(pers.direccion,'' ) as direccion,
    ifnull(pers.telefono ,'') as telefono,
    ifnull(pers.FechaNacimiento ,'') as FechaNacimiento,
    ifnull(pers.idsexo ,'') as sexo,
    ifnull(pers.idusuario ,'') as usuario,
	ifnull(pers.estatus ,'') as estado,
	ifnull(pers.correo ,'') as correo,
	
	ifnull(us.usuario ,'') as usu,
	ifnull(us.clave ,'') as clave,
	ifnull(us.estado ,'') as estado_usu,
	ifnull(pers.idCargo ,'') as cargo,
	ifnull(pers.idArea ,'') as area,
	ifnull(us.idPerfil ,'') as perfil,
	ifnull(pers.idJefeInmediato ,'' ) as idJefeInm
    FROM persona pers
	INNER JOIN usuario AS us ON us.idusuario=pers.idusuario
    WHERE pers.idpersona=$IdReg;
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
if (isset($_POST['ReturnActualizarRegPersonal'])) {

    /*****************ID REGISTROS PRINCIPALES****************** */
    $Id = $_POST['id'];
    /********************DATOS PERSONALES******************** */
    $TipoDocumento_a = $_POST['cbxTipoDocumento'];
    $Documento_a = $_POST['txtDocumento'];
    $Apellidos_a = $_POST['txtApellidos'];
    $Nombres_a = $_POST['txtNombres']; 
	$Sexo_a = $_POST['cbxSexo'];
	$FechaNacimiento_a = $_POST['txtFechaNacimiento'];
	$Telefono_a = $_POST['txtTelefono'];
	$email_a = $_POST['txtemail'];
	$Direccion_a = $_POST['txtDireccion'];
	
	/********************DATOS USUARIO******************** */
	$Usuario_a = $_POST['txtDatoUser'];
    $Clave_a = $_POST['txtpassword'];
    $Clave2_a = $_POST['txtpassword2'];
    $Estado_a = $_POST['cbxEstado'];
    $Cargo_a = $_POST['cbxCargo'];
    $Area_a = $_POST['cbxArea'];
    $Perfil_a = $_POST['cbxPerfilUsu'];
    $cbxJefeInmed_a = $_POST['cbxJefeInmed'];
    $constancia = $_POST['constancia'];
    
    $txtUsuario = $_POST['txtUsuario'];
	$actualiza = $fecha.' '.$hora;
    $query_file = "";
    
    $txtUsuario = decrypt($txtUsuario, "123");
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtUsuario'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $val_idusuario = $respuesta_idusuario['id'];

    if(!empty($constancia)){
        
        $path = $constancia;
        $file = new SplFileInfo($path);
        $extension  = $file->getExtension();
        $desc_codigo="documento-";
        $name_file = "documento";
        if(!empty($constancia)){
            $name_file = $desc_codigo.$TipoDocumento_a.$Documento_a.".".$extension;
        }
        
        $query_file = ",file_documento='$name_file'";
    }

    $query = mysqli_query($conection, "UPDATE persona SET
    tipodocumento='$TipoDocumento_a',
    DNI='$Documento_a',
    apellido='$Apellidos_a',
    nombre='$Nombres_a',
    direccion='$Direccion_a',
    Telefono='$Telefono_a',
    FechaNacimiento='$FechaNacimiento_a',
    correo='$email_a',
    idsexo='$Sexo_a',
    idCargo='$Cargo_a',
    idArea='$Area_a',
    idJefeInmediato='$cbxJefeInmed_a',
    estado='$Estado_a',
    actualiza_registro='$actualiza',
    actualiza_usuario='$val_idusuario'
    $query_file
    WHERE idpersona='$Id'");

    if ($query) {
        
        $consultar_idusu = mysqli_query($conection, "SELECT idusuario as idusu FROM persona WHERE idpersona='$Id'");
        $respuesta_idusu = mysqli_fetch_assoc($consultar_idusu);
        $idusu = $respuesta_idusu['idusu'];
        
        if($Clave_a == $Clave2_a){
            
            $actualiza_usu = mysqli_query($conection, "UPDATE usuario SET 
            usuario='$Usuario_a',
            clave='$Clave_a',
            idPerfil='$Perfil_a',
            estado='$Estado_a',
            actualiza_registro='$actualiza',
            actualiza_usuario='$val_idusuario'
            WHERE idusuario='$idusu'");
            
            if($actualiza_usu){
                
                $data['status'] = 'ok';
                $data['data'] = 'Se actualizó con éxito';
                $data['name_file'] = $name_file;
                
            }else {
                if (!$actualiza_usu) {
                    $data['dataDB'] = mysqli_error($conection);
                }
                $data['status'] = 'bad';
                $data['data'] = 'Ocurrió un problema al guardar el registro.';
            }
        
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'La clave ingresada debe ser igual en ambos campos (Clave y Repetir Clave). Verificar';
        }
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

