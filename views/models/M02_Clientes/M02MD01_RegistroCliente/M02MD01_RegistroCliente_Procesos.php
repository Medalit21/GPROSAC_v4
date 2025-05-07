<?php
session_start(); 

//RECURSOS DE CONEXION Y CONFIGURACION
include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

//INICIALIZANDO VARIABLES Y ARREGLOS.
$data = array();
$dataList = array();
$hora = date("H:i:s", time());
$fecha = date('Y-m-d'); 

/**************************REDIRECCIONAMIENTO RESERVAS Y VENTAS******************* */
if (isset($_POST['ReturnIrReserva'])) {

    $idcliente = $_POST['idRegistro'];
    $idcliente = encrypt($idcliente, "123");

    $iduser = $_POST['iduser'];

    $data['status'] = 'ok';
    $data['valor'] = $iduser;
    $data['ruta'] = $NAME_SERVER."views/M03_Ventas/M03SM01_Reservacion/M03SM01_Reservacion?Vsr=".$iduser."&c=".$idcliente;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}
if (isset($_POST['ReturnIrVenta'])) {

    $idcliente = $_POST['idRegistro'];
    $idcliente = encrypt($idcliente, "123");
    
    $iduser = $_POST['iduser'];

    $data['status'] = 'ok';
    $data['valor'] = $idcliente;
    $data['ruta'] = $NAME_SERVER."views/M03_Ventas/M03SM02_Venta/M03SM02_Venta?Vsr=".$iduser."&c=".$idcliente;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

/**************************GENERAR CODIGO CLIENTE******************* */
if (isset($_POST['btnGenerarCodigoCliente'])) {

    $anio = date('Y'); 
    $consultar_correlativo = mysqli_query($conection, "SELECT max(codigo_correlativo) as correlativo FROM datos_cliente WHERE codigo_anio='$anio' AND esta_borrado=0");
    $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
    $dato_correlativo = $respuesta_correlativo['correlativo'];
    $dato_correlativo = $dato_correlativo + 1;

    $dato_codigo_desc = "";
    $length = 6;
    $dato_codigo_desc = substr(str_repeat(0, $length).$dato_correlativo, - $length);
    $nom_codigo = $anio.$dato_codigo_desc;

    $data['status'] = 'ok';
    $data['codigo'] = $nom_codigo;
    $data['anio'] = $anio;
    $data['correlativo'] = $dato_correlativo;

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************INSERTAR NUEVO REGISTRO TRABAJADOR******************* */
if (isset($_POST['ReturnGuardarRegCliente'])) {

    $TipoDocumento = $_POST['cbxTipoDocumento'];
    $Documento = $_POST['txtDocumento'];
    $Nacionalidad = $_POST['cbxNacionalidad'];
    $PaisEmisorDocumento = $_POST['cbxPaisEmisorDocumento'];
    $ApellidoPaterno = $_POST['txtApellidoPaterno'];
    $ApellidoMaterno = $_POST['txtApellidoMaterno'];
    $Nombres = $_POST['txtNombres'];
    $DepartamentoNacimiento = $_POST['cbxDepartamentoNacimiento'];
    $ProvinciaNacimiento = $_POST['cbxProvinciaNacimiento'];
    $PaisNacimiento = $_POST['cbxPaisNacimiento'];
    $FechaNacimiento = $_POST['txtFechaNacimineto'];
    $Sexo = $_POST['cbxSexo'];
    $TipoVia = $_POST['cbxTipoVia'];
    $NombreVia = $_POST['txtNombreVia'];
    $NroVia = $_POST['txtNroVia'];
    $NroDpto = $_POST['txtNroDpto'];
    $Interior = $_POST['txtInterior'];
    $Mz = $_POST['txtMz'];
    $Lt = $_POST['txtLt'];
    $Km = $_POST['txtKm'];
    $Block = $_POST['txtBlock'];
    $Etapa = $_POST['txtEtapa'];
    $TipoZona = $_POST['cbxTipoZona'];
    $NombreZona = $_POST['txtNombreZona'];
    $Referencia = $_POST['txtReferencia'];
    $DistritoDireccion = $_POST['cbxDistritoDir'];
    $ProvinciaDireccion = $_POST['cbxProvinciaDir'];
    $DepartamentoDireccion = $_POST['cbxDepartamentoDir'];
   
    $Telefono = $_POST['txtTelefono'];
    $Celular = $_POST['txtCelular'];
    $Celular2 = $_POST['txtCelular2'];
    $Correo = $_POST['txtCorreo'];
    $EstadoCivil = $_POST['cbxEstadoCivil'];
    $SituacionDomiciliaria = $_POST['cbxSituacionDomiciliaria'];
    $documentoCliente = $_POST['documentoCliente'];
    //$txtCodigoCliente = $_POST['txtCodigoCliente'];
    // $txtCodigoAnio = $_POST['txtCodigoAnio'];
    // $txtCodigoCorrelativo = $_POST['txtCodigoCorrelativo'];

    $__ID_USER = $_POST['__ID_USER'];

    $idUsuario = decrypt($__ID_USER,"123");
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$idUsuario'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $idUsuario=$respuesta_idusu['id'];
    
    $name_file = "";
    $path = $documentoCliente;
    $file = new SplFileInfo($path);
    $extension  = $file->getExtension();
    $desc_codigo="documento-";
    $name_file = "documento";
    if(!empty($documentoCliente)){
        $name_file = $desc_codigo.$Documento.".".$extension;
    }else{
        $name_file = "Ninguno";
	}

    //CODIGO Y CORRELATIVO DEL CLIENTE
    $anio = date('Y'); 
    $consultar_correlativo = mysqli_query($conection, "SELECT max(codigo_correlativo) as correlativo FROM datos_cliente WHERE codigo_anio='$anio' AND esta_borrado=0");
    $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
    $dato_correlativo = $respuesta_correlativo['correlativo'];
    $dato_correlativo = $dato_correlativo + 1;

    $dato_codigo_desc = "";
    $length = 6;
    $dato_codigo_desc = substr(str_repeat(0, $length).$dato_correlativo, - $length);
    $nom_codigo = $anio.$dato_codigo_desc;

    $txtCodigoCliente = $nom_codigo;
    $txtCodigoAnio = $anio;
    $txtCodigoCorrelativo = $dato_correlativo;
    // ======================

    $query = mysqli_query($conection, "INSERT INTO datos_cliente( 
    tipodocumento, 
    documento, 
    nacionalidad, 
    pais_emisor_doc, 
    apellido_paterno, 
    apellido_materno, 
    nombres, 
    id_fndepartamento, 
    id_fnprovincia, 
    id_fnpais, 
    fec_nacimiento, 
    id_sexo, 
    tipo_via, 
    nombre_via, 
    nro_via, 
    nro_dpto, 
    interior, 
    manzana, 
    lote, 
    km, 
    block_dir, 
    etapa, 
    tipo_zona, 
    nombre_zona, 
    referencia, 
    id_dom_distrito, 
    id_dom_provincia, 
    id_dom_departamento, 
    telefono, 
    celular_1,
    celular_2, 
    email, 
    id_estado_civil, 
    situacion_domiciliaria,
    id_usuario,
    id_usuario_auditoria,
    id_vendedor,
    adjunto_dni, 
    codigo, 
    codigo_anio, 
    codigo_correlativo)
    VALUES (
    '$TipoDocumento',
    '$Documento',
    '$Nacionalidad',
    '$PaisEmisorDocumento',
    '$ApellidoPaterno',
    '$ApellidoMaterno',
    '$Nombres',
    '$DepartamentoNacimiento',
    '$ProvinciaNacimiento',
    '$PaisNacimiento',
    '$FechaNacimiento',
    '$Sexo',
    '$TipoVia',
    '$NombreVia',
    '$NroVia',
    '$NroDpto',
    '$Interior',
    '$Mz',
    '$Lt',
    '$Km',
    '$Block',
    '$Etapa',
    '$TipoZona',
    '$NombreZona',
    '$Referencia',
    '$DistritoDireccion',
    '$ProvinciaDireccion',
    '$DepartamentoDireccion',
    '$Telefono',
    '$Celular',
    '$Celular2',
    '$Correo',
    '$EstadoCivil',
    '$SituacionDomiciliaria',
    '$idUsuario',
    '$idUsuario',
    '$idUsuario',
    '$name_file',
    '$txtCodigoCliente',
    '$txtCodigoAnio',
    '$txtCodigoCorrelativo'    
     )");


    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se guardó con éxito';
        $data['nombre'] = $name_file;
        $data['path'] = $documentoCliente;
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrió un problema al guardar el registro.';
        $data['nombre'] = $name_file;
        $data['path'] = $documentoCliente;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************BUSCAR EXISTENCIA DOCUMENTO******************* */

if (isset($_POST['ReturnVerificaExixtencia'])) {

    $TipoDocumento = $_POST['tipoDocumento'];
    $Documento = $_POST['documento'];
    $Nacionalidad = $_POST['nacionalidad'];
    
    $query = mysqli_query($conection, "SELECT
   id
   ,ifnull( tbl1.documento ,'') as documento
   ,ifnull(tbl1.apellido_paterno,'') as apellidoPaterno
   ,ifnull(tbl1.apellido_materno,'') as apellidoMaterno
   ,ifnull(tbl1.nombres ,'') as nombres
   ,tbl1.esta_borrado
   FROM datos_cliente tbl1
    WHERE tbl1.tipodocumento=$TipoDocumento AND tbl1.documento='$Documento' AND tbl1.nacionalidad='$Nacionalidad'  limit 1;");
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
if(isset($_POST['ReturnClienteListaPaginada'])){
    $DocumentoFiltro=$_POST['txtDniFiltro'];
    $NombresApellidos=$_POST['txtApeNomFiltro'];
    $vendedor=$_POST['txtVendedorFiltro'];

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

    $query = mysqli_query($conection,"call pa_datos_cliente_lista_paginada($Start,$Length,'$ColumnaOrden','$DocumentoFiltro','$NombresApellidos','$vendedor')");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList,[
                'id' => $row['id'],
                'documento'=> $row['documento'],
                'codigo'=> $row['codigo'],
                'documentoCadena' => $row['documentoCadena'],
                'apellidos' => $row['apellidos'],
                'nombres'=> $row['nombres'],
                'datos'=> $row['apellidos']." ".$row['nombres'],
                'fechaNacimiento' => $row['fechaNacimiento'],
                'email' => $row['email'],
                'celularTelefono'=>  $row['celularTelefono'],
                'vendedor'=>  $row['vendedor'],
                'adjunto'=>  $row['adjunto'],
                'registro'=> $row['registro'],
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

/**************************OBTENER DETALLE REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnDetalleRegistroCliente'])) {
    $IdReg = $_POST['IdRegistro'];
    $query = mysqli_query($conection, "SELECT
    id
    ,ifnull(tbl1.tipodocumento ,'') as tipoDocumento
    ,ifnull( tbl1.documento ,'') as documento
    ,ifnull(tbl1.nacionalidad,'') as nacionalidad
    ,ifnull(tbl1.pais_emisor_doc,'') as paisEmisorDoc
    ,ifnull(tbl1.apellido_paterno,'') as apellidoPaterno
    ,ifnull(tbl1.apellido_materno,'') as apellidoMaterno
    ,ifnull(tbl1.nombres ,'') as nombres
    ,ifnull(tbl1.id_fndepartamento,'' ) as idDepartamentoNac
    , ifnull(tbl1.id_fnprovincia ,'') as idProvinciaNac
    ,ifnull(tbl1.id_fnpais ,'') as idPaisNac
    ,ifnull(tbl1.fec_nacimiento,'') as fecNacimiento
    ,ifnull(tbl1.id_sexo ,'') as sexo
    ,ifnull(tbl1.tipo_via,'') as tipoVia
    ,ifnull(tbl1.nombre_via,'') as nombreVia
    , ifnull(tbl1.nro_via ,'') as nroVia
    ,ifnull(tbl1.nro_dpto ,'') as nroDpto
    , ifnull(tbl1.interior ,'') as interior
    ,ifnull(tbl1.manzana,'') as mz
    , ifnull(tbl1.lote ,'') as lt
    , ifnull(tbl1.km,'') as km
    , ifnull(tbl1.block_dir ,'') as blockDir
    , ifnull(tbl1.etapa,'') as etapa
    , ifnull(tbl1.tipo_zona ,'') as tipoZona
    , ifnull(tbl1.nombre_zona,'') as nombreZona
    , ifnull(tbl1.referencia,'') as referencia
    , ifnull(tbl1.id_dom_distrito,'' ) as idDistritoDir
    , ifnull(tbl1.id_dom_provincia ,'') as idProvinciaDir
    , ifnull(tbl1.id_dom_departamento ,'') as idDepartamentoDir
    , ifnull(tbl1.telefono ,'') as telefono
    , ifnull(tbl1.celular_1,'') as celular1
     , ifnull(tbl1.celular_2 ,'') as celular2
    , ifnull(tbl1.email,'') as email
    , ifnull(tbl1.id_estado_civil ,'') as estadoCivil
    , ifnull(tbl1.situacion_domiciliaria ,'') as situacionDomiciliaria
    ,tbl1.codigo as codigo
    ,tbl1.codigo_anio as codigo_anio
    ,tbl1.codigo_correlativo as codigo_correlativo
    ,if(tbl1.creado<'2022-05-02','Si','No') as validacion
    FROM datos_cliente tbl1
    WHERE tbl1.id=$IdReg;");
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

    //ID REGISTROS PRINCIPALES
    $Id = $_POST['id'];
    //DATOS PERSONALES
    $TipoDocumento_a = $_POST['cbxTipoDocumento'];
    $Documento_a = $_POST['txtDocumento'];
    $Nacionalidad_a = $_POST['cbxNacionalidad'];
    $PaisEmisorDocumento_a = $_POST['cbxPaisEmisorDocumento'];
    $ApellidoPaterno_a = $_POST['txtApellidoPaterno'];
    $ApellidoMaterno_a = $_POST['txtApellidoMaterno'];
    $Nombres_a = $_POST['txtNombres'];
    $DepartamentoNacimiento_a = $_POST['cbxDepartamentoNacimiento'];
    $ProvinciaNacimiento_a = $_POST['cbxProvinciaNacimiento'];
    $PaisNacimiento_a = $_POST['cbxPaisNacimiento'];
    $FechaNacimiento_a = $_POST['txtFechaNacimineto'];
    $Sexo_a = $_POST['cbxSexo'];
    $TipoVia_a = $_POST['cbxTipoVia'];
    $NombreVia_a = $_POST['txtNombreVia'];
    $NroVia_a = $_POST['txtNroVia'];
    $NroDpto_a = $_POST['txtNroDpto'];
    $Interior_a = $_POST['txtInterior'];
    $Mz_a = $_POST['txtMz'];
    $Lt_a = $_POST['txtLt'];
    $Km_a = $_POST['txtKm'];
    $Block_a = $_POST['txtBlock'];
    $Etapa_a = $_POST['txtEtapa'];
    $TipoZona_a = $_POST['cbxTipoZona'];
    $NombreZona_a = $_POST['txtNombreZona'];
    $Referencia_a = $_POST['txtReferencia'];
    $DistritoDireccion_a = $_POST['cbxDistritoDir'];
    $ProvinciaDireccion_a = $_POST['cbxProvinciaDir'];
    $DepartamentoDireccion_a = $_POST['cbxDepartamentoDir'];
    $Telefono_a = $_POST['txtTelefono'];
    $Celular_a = $_POST['txtCelular'];
    $Celular2 = $_POST['txtCelular2'];
    $Correo_a = $_POST['txtCorreo'];
    $EstadoCivil_a = $_POST['cbxEstadoCivil'];
    $SituacionDomiciliaria_a = $_POST['cbxSituacionDomiciliaria'];
    $documentoCliente = $_POST['documentoCliente'];
    $txtCodigoCliente = $_POST['txtCodigoCliente'];

    $path = $documentoCliente;
    $file = new SplFileInfo($path);
    $extension  = $file->getExtension();
    $desc_codigo="documento-";
    $name_file = "documento";
    if(!empty($documentoCliente)){
        $name_file = $desc_codigo.$Documento_a.".".$extension;
    }else{
        $name_file = "Ninguno";
	}

    $consultar_codigos = mysqli_query($conection, "SELECT codigo as codigo FROM datos_cliente WHERE id='$Id' AND esta_borrado=0");
    $respuesta_codigos = mysqli_fetch_assoc($consultar_codigos);
    $respuesta_codigos = $respuesta_codigos['codigo'];

    if(empty($respuesta_codigos)){
        $consultar_codigo = mysqli_query($conection, "SELECT id FROM datos_cliente WHERE codigo='$txtCodigoCliente' AND esta_borrado=0");
        $respuesta_codigo = mysqli_num_rows($consultar_codigo);
    }else{
        $respuesta_codigo = 0;
    }

    if($respuesta_codigo<=0){

        $conteo_digitos = strlen($txtCodigoCliente);

        if($conteo_digitos==10){

            for($i=0;$i<=strlen($txtCodigoCliente);$i++){
                
                $dato_anio = $txtCodigoCliente[0].$txtCodigoCliente[1].$txtCodigoCliente[2].$txtCodigoCliente[3];
                $dato_correlativo = $txtCodigoCliente[4].$txtCodigoCliente[5].$txtCodigoCliente[6].$txtCodigoCliente[7].$txtCodigoCliente[8].$txtCodigoCliente[9];
            }

            $query = mysqli_query($conection, "call pa_datos_cliente_actualizar(
            '$Id',
            '$TipoDocumento_a',
            '$Documento_a',
            '$Nacionalidad_a',
            '$PaisEmisorDocumento_a',
            '$ApellidoPaterno_a',
            '$ApellidoMaterno_a',
            '$Nombres_a',
            '$DepartamentoNacimiento_a',
            '$ProvinciaNacimiento_a',
            '$PaisNacimiento_a',
            '$FechaNacimiento_a',
            '$Sexo_a',
            '$TipoVia_a',
            '$NombreVia_a',
            '$NroVia_a',
            '$NroDpto_a',
            '$Interior_a',
            '$Mz_a',
            '$Lt_a',
            '$Km_a',
            '$Block_a',
            '$Etapa_a',
            '$TipoZona_a',
            '$NombreZona_a',
            '$Referencia_a',
            '$DistritoDireccion_a',
            '$ProvinciaDireccion_a',
            '$DepartamentoDireccion_a',
            '$Telefono_a',
            '$Celular_a',
            '$Celular2',
            '$Correo_a',
            '$EstadoCivil_a',
            '$SituacionDomiciliaria_a',
            '$IdUser',
            '$name_file',
            '$txtCodigoCliente',
            '$dato_correlativo',
            '$dato_anio'
            )");

            if ($query) {
                $data['status'] = 'ok';
                $data['data'] = 'Se actualizó con éxito';
                $data['nombre'] = $name_file;
            } else {
                if (!$query) {
                    $data['dataDB'] = mysqli_error($conection);
                }
                $data['status'] = 'bad';
                $data['data'] = 'Ocurrió un problema al guardar el registro.';
            }
        }else {
            $data['status'] = 'bad';
            $data['data'] = 'El codigo ingresado se compone de 10 digitos, uniendo el año + el correlativo, por ejemplo: 2022000001';
        }

    }else {
        $data['status'] = 'bad';
        $data['data'] = 'El codigo ingresado ya fue asignado a un cliente en el sistema.';
    }


    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************ELIMINAR REGISTRO CLIENTE******************* */
if (isset($_POST['ReturnEliminarRegCliente'])) {

    $__ID_USER = $_POST['__ID_USER'];
    $idCliente = $_POST['id'];

    $idUsuario = decrypt($__ID_USER,"123");
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$idUsuario'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $idUsuario=$respuesta_idusu['id'];

    // VERIFICAR SI EL CLIENTE TIENE UN LOTO COMPRADO O RESERVADO
    $consulta_venta = mysqli_query($conection, "SELECT count(*) as cantidad FROM gp_venta WHERE esta_borrado=0 and id_cliente='$idCliente'");
    $respuesta_venta = mysqli_fetch_assoc($consulta_venta);
    $cant_venta=$respuesta_venta['cantidad'];

    $consulta_reserva = mysqli_query($conection, "SELECT count(*) as cantidad FROM gp_reservacion WHERE esta_borrado=0 and id_cliente='$idCliente'");
    $respuesta_reserva = mysqli_fetch_assoc($consulta_reserva);
    $cant_reserva=$respuesta_reserva['cantidad'];

    if($cant_venta>0){
        $data['status'] = 'info';
        $data['data'] = 'No se puede eliminar, el cliente tiene una venta asignada.';
    } else if($cant_reserva>0){
        $data['status'] = 'info';
        $data['data'] = 'No se puede eliminar, el cliente tiene una reserva asignada.';
    } else {

        $query = mysqli_query($conection, "UPDATE datos_cliente
        SET  esta_borrado=1,borrado=NOW(),id_usuario_auditoria=$idUsuario
        WHERE id=$idCliente");
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

    $query = mysqli_query($conection, "UPDATE datos_cliente
    SET  esta_borrado=0,borrado=NULL,actualizado=now(),id_usuario_auditoria=$IdUser
    WHERE id=$Id");

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

/**************************MOSTRAR DOCUMENTO CLIENTE******************* */
if (isset($_POST['btnMostrarDocumento'])) {

    $idRegistro= $_POST['idRegistro'];

    $query = mysqli_query($conection, "SELECT 
    adjunto_dni as documento
    FROM datos_cliente
    WHERE id=$idRegistro");

    if ($query) {
        $row = mysqli_fetch_assoc($query);
        $data['status'] = 'ok';
        $data['data'] = $row['documento'];
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


if (isset($_POST['VerClientesBusqueda'])) {

	$query = "SELECT documento as ID, 
					 CONCAT(apellido_paterno,' ',apellido_materno,' ',SUBSTRING_INDEX(nombres,' ',1)) as Nombre 
			  FROM datos_cliente 
			  WHERE esta_borrado='0' 
			  ORDER BY apellido_paterno ASC";
	
	$result = mysqli_query($conection, $query);
	
	$data = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = $row;
	}

	echo json_encode(["status" => "ok", "data" => $data], JSON_PRETTY_PRINT);
	exit; // Asegura que la respuesta no tenga más datos inesperados
}