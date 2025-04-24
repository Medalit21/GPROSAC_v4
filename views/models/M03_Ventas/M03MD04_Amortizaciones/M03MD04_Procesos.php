<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
$fecha = date('Y-m-d');
$IdUser = 1;

$data = array();
$dataList = array();

if(isset($_POST['btnCargarArchivo'])){

    $__ID_VENTA = $_POST['__ID_VENTA'];
    $cbxTipoDocumentoAdjunto = $_POST['cbxTipoDocumentoAdjunto'];
    $txtFechaSubidaAdjunto = $_POST['txtFechaSubidaAdjunto'];
    $txtNotariaAdjunto = $_POST['txtNotariaAdjunto'];
    $txtFechaFirmaAdjunto = $_POST['txtFechaFirmaAdjunto'];
    $txtTipoMonedaImporteInicial = $_POST['txtTipoMonedaImporteInicial'];
    $txtImporteInicialAdjunto = $_POST['txtImporteInicialAdjunto'];
    $txtTipoMonedaValorCerrado = $_POST['txtTipoMonedaValorCerrado'];
    $txtValorCerradoAdjunto = $_POST['txtValorCerradoAdjunto'];
    $txtDescripcionAdjunto = $_POST['txtDescripcionAdjunto'];
    $fichero = $_POST['fichero'];
    
    if(!empty($__ID_VENTA)){

        if(!empty($cbxTipoDocumentoAdjunto)){

            if(!empty($txtFechaSubidaAdjunto)){

                if(!empty($txtNotariaAdjunto)){

                    if(!empty($txtFechaFirmaAdjunto)){

                        if(!empty($txtTipoMonedaImporteInicial) && !empty($txtImporteInicialAdjunto)){

                            if(!empty($txtTipoMonedaValorCerrado) && !empty($txtValorCerradoAdjunto)){

                                if(!empty($fichero)){
                                
                                    $consultar_archivo = mysqli_query($conection, "SELECT MAX(gpav.id) as id FROM gp_archivo_venta gpav");
                                    $respuesta_archivo = mysqli_num_rows($consultar_archivo);

                                    if($respuesta_archivo > 0){

                                        $conteo = mysqli_fetch_assoc($consultar_archivo);
                                        $max_conteo = $conteo['id'] + 1;
                                        $name_file = "file-".$max_conteo.".pdf";
                                        $insertar_archivo = mysqli_query($conection, "INSERT INTO gp_archivo_venta(id_archivo, id_venta, id_tipo_documento, descripcion, fecha_adjunto, id_usuario_crea, notaria, fechafirma, tipomoneda_importeinicial, importe_inicial, tipomoneda_valorcerrado, valor_cerrado, nombre_adjunto) VALUES ('$max_conteo','$__ID_VENTA','$cbxTipoDocumentoAdjunto','$txtDescripcionAdjunto','$txtFechaSubidaAdjunto','1','$txtNotariaAdjunto','$txtFechaFirmaAdjunto','$txtTipoMonedaImporteInicial','$txtImporteInicialAdjunto','$txtTipoMonedaValorCerrado','$txtValorCerradoAdjunto', '$name_file')");

                                        $data['status'] = 'ok';
                                        $data['mensaje'] = "Se registro el adjunto correctamente.";

                                    }else{

                                        $name_file = "file-1.pdf";
                                        $insertar_archivo = mysqli_query($conection, "INSERT INTO gp_archivo_venta(id_archivo, id_venta, id_tipo_documento, descripcion, fecha_adjunto, id_usuario_crea, notaria, fechafirma, tipomoneda_importeinicial, importe_inicial, tipomoneda_valorcerrado, valor_cerrado, nombre_adjunto) VALUES ('$max_conteo','$__ID_VENTA','$cbxTipoDocumentoAdjunto','$txtDescripcionAdjunto','$txtFechaSubidaAdjunto','1','$txtNotariaAdjunto','$txtFechaFirmaAdjunto','$txtTipoMonedaImporteInicial','$txtImporteInicialAdjunto','$txtTipoMonedaValorCerrado','$txtValorCerradoAdjunto', '$name_file')");

                                        $data['status'] = 'ok';
                                        $data['mensaje'] = "Se registro el adjunto correctamente.";

                                    }
                                }else{
                                    $data['status'] = 'bad';
                                    $data['mensaje'] = "Seleccione el documento adjunto.";
                                }

                            }else{
                                $data['status'] = 'bad';
                                $data['mensaje'] = "Seleccione tipo de moneda e ingrese el monto con el que se concreto la venta de la propiedad.";
                            }


                        }else{
                            $data['status'] = 'bad';
                            $data['mensaje'] = "Seleccione tipo de moneda e ingrese el monto inicial de la propiedad.";
                        }


                    }else{
                        $data['status'] = 'bad';
                        $data['mensaje'] = "Ingrese fecha de la firma del documento adjunto.";
                    }


                }else{
                    $data['status'] = 'bad';
                    $data['mensaje'] = "Seleccione la notaria en relacion con el documento adjunto.";
                }

            }else{
                $data['status'] = 'bad';
                $data['mensaje'] = "El campo Fecha es obligatorio.";
            }

        }else{
            $data['status'] = 'bad';
            $data['mensaje'] = "Seleccione el tipo de documento que esta por adjuntar.";
        }

    }else{

        $data['status'] = 'bad';
        $data['mensaje'] = "No se ha encontrado el registro de venta, intente nuevamente.";
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnZonas'])) {
    $IdProyecto = $_POST['idProyecto'];

    $query = mysqli_query($conection, "SELECT idzona as id, nombre FROM gp_zona where esta_borrado=0 AND idproyecto='$IdProyecto' AND estado='1'");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnManzana'])) {
    $IdZona = $_POST['idZona'];

    $query = mysqli_query($conection, "select idmanzana as id , nombre from gp_manzana where esta_borrado=0 and idzona=$IdZona;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnLote'])) {
    $IdManzana = $_POST['idManzana'];

    $query = mysqli_query($conection, "select idlote as id , nombre from gp_lote where esta_borrado=0 and estado=1 and idmanzana=$IdManzana;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnLoteActualizable'])) {
    $IdManzana = $_POST['idManzana'];
    $IdLote = $_POST['idLote'];

    $query = mysqli_query($conection, "select idlote as id , nombre from gp_lote where esta_borrado=0 and (estado=1 or idlote='$IdLote') and idmanzana=$IdManzana;");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccione',
    ]);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['id'],
                'texto' => $row['nombre'],
            ]);}
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR CLIENTE POR  DNI************************** */
if (isset($_POST['ReturnBuscarCliente'])) {
    
    $TipoDocumento = $_POST['tipoDocumento'];
    $Documento = $_POST['documento'];
    $idlote = $_POST['idlote'];
    
    $resul_query = "";

    if(!empty($TipoDocumento) && !empty($Documento) && empty($idlote)){

        $contar_idcliente=0;
        $idcliente = 0;
        //CONSULTAR ID CLIENTE
        $consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE tipodocumento='$TipoDocumento' AND documento='$Documento'");
        $respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
        $contar_idcliente = mysqli_num_rows($consultar_idcliente);

        if($contar_idcliente>0){

            $idcliente = $respuesta_idcliente['id'];            
            //CONSULTAR SI TIENE RESERVACION
            $consultar_reservacion = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_cliente='$idcliente' AND estado='2' AND esta_borrado='0'");
            $respuesta_reservacion = mysqli_num_rows($consultar_reservacion);
            
            if($respuesta_reservacion>0){
                
                $aplica = "si";
                $resul_query = "valido";
                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.email as correo,
                gpl.idlote as idlote,
                gpm.idmanzana as idmanzana,
                gpz.idzona as idzona,
                gpp.idproyecto as idproyecto,
                format(gpl.area, 2) as area,
                cd.nombre_corto as lote_tipo_moneda,
                format(gpl.valor_con_casa,2) as lote_valor_casa,
                format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                gpr.monto_reservado as monto_reserva
                FROM datos_cliente dc
                INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                WHERE dc.esta_borrado=0 AND dc.id='$idcliente'");
                
            }else{                
                $aplica = "no";
                $resul_query = "valido";
                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.email as correo
                FROM datos_cliente dc
                WHERE dc.esta_borrado=0 AND dc.id='$idcliente'");                
            }

        }else{
            //REQUIERE NUEVO REGISTRO DE CLIENTE
            $resul_query = "NewRegister";
            $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.email as correo
                FROM datos_cliente dc
                WHERE dc.esta_borrado=0 AND dc.id='0'");
        }
        
    }else{
        if(empty($TipoDocumento) && empty($Documento) && !empty($idlote)){
            
            //CONSULTAR SI TIENE RESERVACION
            $consultar_reservacion = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_lote='$idlote' AND estado='2' AND esta_borrado='0'");
            $respuesta_reservacion = mysqli_num_rows($consultar_reservacion);
            
            if($respuesta_reservacion>0){
                
                $aplica = "si";
                $resul_query = "valido";
                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.email as correo,
                gpl.idlote as idlote,
                gpm.idmanzana as idmanzana,
                gpz.idzona as idzona,
                gpp.idproyecto as idproyecto,
                format(gpl.area, 2) as area,
                cd.nombre_corto as lote_tipo_moneda,
                format(gpl.valor_con_casa,2) as lote_valor_casa,
                format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                gpr.monto_reservado as monto_reserva
                FROM datos_cliente dc
                INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                WHERE dc.esta_borrado=0 AND gpl.idlote='$idlote'");
                
            }else{
                //REQUIERE NUEVO REGISTRO DE CLIENTE
                $resul_query = "NewRegister";
            }
        
        }else{
            
            if(!empty($TipoDocumento) && !empty($Documento) && !empty($idlote)){
                //CONSULTAR ID CLIENTE
                $consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE tipodocumento='$TipoDocumento' AND documento='$Documento'");
                $respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
                $idcliente = $respuesta_idcliente['id'];
                
                //CONSULTAR SI TIENE RESERVACION
                $consultar_reservacion = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_cliente='$idcliente' AND id_lote='$idlote' AND estado='2' AND esta_borrado='0'");
                $respuesta_reservacion = mysqli_num_rows($consultar_reservacion);
                
                if($respuesta_reservacion>0){
                    
                    $aplica = "si";
                    $resul_query = "valido";
                    $query = mysqli_query($conection, "SELECT
                    dc.id as id,
                    dc.nombres as nombres,
                    dc.apellido_paterno as apellidoPaterno,
                    dc.apellido_materno as apellidoMaterno,
                    dc.email as correo,
                    gpl.idlote as idlote,
                    gpm.idmanzana as idmanzana,
                    gpz.idzona as idzona,
                    gpp.idproyecto as idproyecto,
                    format(gpl.area, 2) as area,
                    cd.nombre_corto as lote_tipo_moneda,
                    format(gpl.valor_con_casa,2) as lote_valor_casa,
                    format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                    gpr.monto_reservado as monto_reserva
                    FROM datos_cliente dc
                    INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                    INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                    WHERE dc.esta_borrado=0 AND dc.id='$idcliente' AND gpl.idlote='$idlote'");
                    
                }else{
                    
                    $aplica = "no";
                    $resul_query = "valido";
                    $query = mysqli_query($conection, "SELECT
                    dc.id as id,
                    dc.nombres as nombres,
                    dc.apellido_paterno as apellidoPaterno,
                    dc.apellido_materno as apellidoMaterno,
                    dc.email as correo
                    FROM datos_cliente dc
                    WHERE dc.esta_borrado=0 AND dc.id='$idcliente'");
                    
                }
            }else{

                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.email as correo
                FROM datos_cliente dc
                WHERE dc.esta_borrado=0 AND dc.id='0'");
            }
        }
    }    
    

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['accion'] = $aplica;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos para el documento y/o lote seleccionado.';
        /*
        if($resul_query == "NewRegister"){

            $data['status'] = 'bad';
            $data['urlRegistroCliente'] = $NAME_SERVER."views/M02_Clientes/M02SM01_RegistroCliente/M02SM01_RegistroCliente.php";
            $data['data'] = 'No se encontro datos para el documento y/o lote seleccionado.';
        }*/

        
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR INFORMACION LOTE************************** */
if (isset($_POST['ReturnInfoLote'])) {
    $IdeLote = $_POST['idLote'];

    $query = mysqli_query($conection, "SELECT 
    tbl1.area, 
    tbl2.texto1 as moneda, 
    tbl1.valor_con_casa as valoLoteCasa , 
    tbl1.valor_sin_casa as valorLoteSolo 
    FROM gp_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl2 on tbl1.tipo_moneda=tbl2.idconfig_detalle
    where tbl1.idlote='$IdeLote' LIMIT 1");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR INFORMACION LOTE************************** */
if (isset($_POST['ReturnInfoLoteReservado'])) {
    $IdeLote = $_POST['idLote'];
    $MontoReserva = $_POST['montoReserva'];

    $query = mysqli_query($conection, "SELECT tbl1.area, tbl2.texto1 as moneda, tbl1.valor_con_casa as valoLoteCasa , tbl1.valor_sin_casa as valorLoteSolo FROM gp_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl2 on tbl1.tipo_moneda=tbl2.idconfig_detalle
    where tbl1.idlote='$IdeLote' LIMIT 1");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['montoReserva'] = $MontoReserva;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR INFORMACION LOTE SEGMENTADA************************** */
if (isset($_POST['ReturnLoteSegmentado'])) {
    $IdeLote = $_POST['idLote'];

    $query = mysqli_query($conection, "SELECT 
    tbl1.area, 
    tbl2.texto1 as moneda,
     tbl1.valor_con_casa as valoLoteCasa , 
     tbl1.valor_sin_casa as valorLoteSolo ,
     tbl1.idlote as idLote,
     tbl3.idmanzana as idManzana,
     tbl4.idzona as idZona,
     tbl5.idproyecto as idProyecto
     FROM gp_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl2 on tbl1.tipo_moneda=tbl2.idconfig_detalle
    inner join gp_manzana tbl3 on tbl1.idmanzana=tbl3.idmanzana
    inner join gp_zona tbl4 on tbl3.idzona=tbl4.idzona
    inner join gp_proyecto tbl5 on tbl5.idproyecto=tbl4.idproyecto
    where tbl1.idlote='$IdeLote' LIMIT 1");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR TIPO CASA************************** */
if (isset($_POST['ReturnTipoCasa'])) {
    $idmanzana = $_POST['idmanzana'];
    $propiedad = $_POST['propiedad'];

    $query = mysqli_query($conection, "SELECT cd.idconfig_detalle as ID, cd.nombre_corto as Nombre 
    FROM gp_manzana gpm
    INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpm.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
    WHERE gpm.idmanzana='$idmanzana'");

    if (($query->num_rows > 0) && $propiedad=='1' ) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['ID'],
                'texto' => $row['Nombre'],
            ]);}
        $data['data'] = $dataList;
        $data['respuesta'] = $dato;
    } else {
        array_push($dataList, [
            'valor' => '',
            'texto' => 'NINGUNO',
        ]);
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************INSERTAR NUEVO REGISTRO VENTA******************* */
if (isset($_POST['ReturnGuardarVenta'])) {

    $txtFechaRegistro = isset($_POST['txtFechaRegistro']) ? $_POST['txtFechaRegistro'] : Null;
    $txtFechaRegistro = trim($txtFechaRegistro);   

    $txtFechaInicio = isset($_POST['txtFechaInicio']) ? $_POST['txtFechaInicio'] : Null;
    $txtFechaInicio = trim($txtFechaInicio); 

    $fileAmortizacion = isset($_POST['fileAmortizacion']) ? $_POST['fileAmortizacion'] : Null;
    $fileAmortizacion = trim($fileAmortizacion);   

    $bxTipoAmortizacion = isset($_POST['bxTipoAmortizacion']) ? $_POST['bxTipoAmortizacion'] : Null;
    $bxTipoAmortizacion = trim($bxTipoAmortizacion); 
    
    $txtTipoMoneda = isset($_POST['txtTipoMoneda']) ? $_POST['txtTipoMoneda'] : Null;
    $txtTipoMoneda = trim($txtTipoMoneda); 
    
    $txtNuevoPrecioVenta = isset($_POST['txtNuevoPrecioVenta']) ? $_POST['txtNuevoPrecioVenta'] : Null;
    $txtNuevoPrecioVenta = trim($txtNuevoPrecioVenta); 
    
    $txtMontoInicialEntrante = isset($_POST['txtMontoInicialEntrante']) ? $_POST['txtMontoInicialEntrante'] : Null;
    $txtMontoInicialEntrante = trim($txtMontoInicialEntrante); 
    
    $txtNuevoMontoInicial = isset($_POST['txtNuevoMontoInicial']) ? $_POST['txtNuevoMontoInicial'] : Null;
    $txtNuevoMontoInicial = trim($txtNuevoMontoInicial); 
    
    $txtNuevoTotalInicial = isset($_POST['txtNuevoTotalInicial']) ? $_POST['txtNuevoTotalInicial'] : Null;
    $txtNuevoTotalInicial = trim($txtNuevoTotalInicial); 

    $txtCantidadLetras = isset($_POST['txtCantidadLetras']) ? $_POST['txtCantidadLetras'] : Null;
    $txtCantidadLetras = trim($txtCantidadLetras); 

    $txtNuevaTEA = isset($_POST['txtNuevaTEA']) ? $_POST['txtNuevaTEA'] : Null;
    $txtNuevaTEA = trim($txtNuevaTEA);
    
    $txtMontoPagado = isset($_POST['txtMontoPagado']) ? $_POST['txtMontoPagado'] : Null;
    $txtMontoPagado = trim($txtMontoPagado);

    $__ID_VENTA = isset($_POST['__ID_VENTA']) ? $_POST['__ID_VENTA'] : Null;
    $__ID_VENTA = trim($__ID_VENTA); 
    
    //CONSULTAR ULTIMA LETRA PAGADA
    
    $consultar_ultimaletra = mysqli_query($conection, "SELECT max(correlativo) as letra FROM gp_cronograma WHERE id_venta='$__ID_VENTA' AND estado='2' AND pago_cubierto='2'");
    $respuesta_ultimaletra = mysqli_fetch_assoc($consultar_ultimaletra);
    $ultima_letra = $respuesta_ultimaletra['letra'];
    
    $consultar_ultimalet = mysqli_query($conection, "SELECT item_letra as letra FROM gp_cronograma WHERE id_venta='$__ID_VENTA' AND correlativo='$ultima_letra'");
    $respuesta_ultimalet = mysqli_fetch_assoc($consultar_ultimalet);
    $ultima_let = $respuesta_ultimalet['letra'];
    if($ultima_let=='C.I.'){
        $ultima_let = 0;
    }
    
    $consultar_letras = mysqli_query($conection, "SELECT if(sum(gpcr.monto_letra),sum(gpcr.monto_letra),'0.00') as total FROM gp_cronograma gpcr WHERE gpcr.id_venta='$__ID_VENTA' AND gpcr.estado='2' AND gpcr.pago_cubierto='1'");
    $respuesta_letras = mysqli_fetch_assoc($consultar_letras);
    $suma_letras = $respuesta_letras['total'];
    
    $consultar_pagos = mysqli_query($conection, "SELECT if(sum(gpc.pagado),sum(gpc.pagado),'0.00') as total FROM gp_pagos_cabecera gpc WHERE gpc.id_venta='$__ID_VENTA'");
    $respuesta_pagos = mysqli_fetch_assoc($consultar_pagos);
    $suma_pagos = $respuesta_pagos['total'];
    
    $saldo = $suma_letras - $suma_pagos;
    
    $consultar_pendiente = mysqli_query($conection, "SELECT sum(gpcr.monto_letra) as total FROM gp_cronograma gpcr WHERE gpcr.id_venta='$__ID_VENTA' AND gpcr.estado='1' AND gpcr.pago_cubierto='0'");
    $respuesta_pendiente = mysqli_fetch_assoc($consultar_pendiente);
    $pendiente = $respuesta_pendiente['total'];
    
    if($saldo<0){
        $saldo = 0;
    }
    
    //consulta precio de venta
    $consulta_pventa = mysqli_query($conection, "SELECT total as pventa FROM gp_venta WHERE id_venta='$__ID_VENTA'");
    $respuesta_pventa= mysqli_fetch_assoc($consulta_pventa);
    $pventa = $respuesta_pventa['pventa'];
    
    //consultar cantidad letras iniciales
    $consultar_letrasiniciales = mysqli_query($conection, "SELECT count(id) as total FROM gp_cronograma WHERE id_venta='$__ID_VENTA' AND es_cuota_inicial='1'");
    $respuesta_letrasiniciales = mysqli_fetch_assoc($consultar_letrasiniciales);
    $letras_iniciales = $respuesta_letrasiniciales['total'];
    
    //consultar cantidad letras pagadas
    $consultar_letraspagadas = mysqli_query($conection, "SELECT count(id) as total FROM gp_cronograma WHERE id_venta='$__ID_VENTA' AND estado='2' AND pago_cubierto='2' AND es_cuota_inicial!='1'");
    $respuesta_letraspagadas = mysqli_fetch_assoc($consultar_letraspagadas);
    $letras_pagadas = $respuesta_letraspagadas['total'];
    
    //consultar cantidad letras 
    $consultar_letras = mysqli_query($conection, "SELECT count(id) as total FROM gp_cronograma WHERE id_venta='$__ID_VENTA' AND item_letra!='AMORTIZADO'");
    $respuesta_letras = mysqli_fetch_assoc($consultar_letras);
    $letras_total = $respuesta_letras['total'];
    
    $nro_letras = (($letras_total - $letras_iniciales) - $letras_pagadas);
    
    //CONVERTIR DATOS TEM
    $valor_TEA= $txtNuevaTEA/100;
    $valor_TEM = pow(( 1 + $valor_TEA),(30/360)) - 1;
    
    //fechas de pago
    $fecha_pago_cuota = $txtFechaRegistro;
    
    
    if($bxTipoAmortizacion=='2'){
        $txtCuotasr = $nro_letras;
        $capital_vivo = $pventa - ($txtMontoPagado + $suma_pagos);
        $capvivo = $capital_vivo;
    }else{
        $txtCuotasr = $nro_letras;
        $capital_vivo = $pventa - ($txtMontoPagado + $suma_pagos);
        $capvivo = $capital_vivo;
    }
    
    //CALCULO CUOTA MENSUAL
    $valor_cuota = (($capital_vivo * ($valor_TEM * pow((1 + $valor_TEM),$txtCuotasr)))/((pow((1+$valor_TEM),$txtCuotasr))-1));
    
    
    $siguiente_letra = $ultima_letra + 1;
    //ACTUALIZAR LOS CORRELATIVOS
    $consultar_cronograma = mysqli_query($conection, "SELECT id as id FROM gp_cronograma WHERE id_venta='$__ID_VENTA' AND correlativo>'$ultima_letra'");
    $respuesta_cronograma = mysqli_num_rows($consultar_cronograma);
    
    if($respuesta_cronograma>0){
        $ultima_letra = $siguiente_letra + 1;
        while($row = $consultar_cronograma->fetch_assoc()) {
            
            $capital_inicial = $capital_vivo;
            $cuota = $valor_cuota;
            $intereses = $capital_vivo * $valor_TEM;
            $amortizacion = $cuota - $intereses;
            $capital_vivo = $capital_vivo - $amortizacion;
            $capital_amortizado = $amortizacion;
        
            $id = $row['id'];
            $ultima_let = $ultima_let + 1;
            
            $actualizar_cronograma = mysqli_query($conection, "UPDATE gp_cronograma SET
            item_letra='$ultima_let', 
            correlativo='$ultima_letra', 
            fecha_vencimiento='$txtFechaInicio', 
            monto_letra='$cuota', 
            interes_amortizado='$intereses', 
            capital_amortizado='$amortizacion', 
            capital_vivo='$capital_vivo', 
            estado='1',
            pago_cubierto='0'
            WHERE id_venta='$__ID_VENTA' AND id='$id'");
            
            $ultima_letra = $ultima_letra + 1;
            $txtFechaInicio = date("Y-m-d",strtotime($txtFechaInicio."+ 1 month"));
            
        }
        
    }
    
    
    //ACTUALIZAR CRONOGRAMA
    $insertar_amortizacion = mysqli_query($conection, "INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado) VALUES
    ('$__ID_VENTA','AMORTIZADO','$siguiente_letra','$txtFechaRegistro','$txtMontoPagado','0.00','$txtMontoPagado','$capvivo','1')");
    
    //actualiza amortizado
    $actualiza_venta = mysqli_query($conection, "UPDATE gp_venta SET id_amortizazion='1' WHERE id_venta='$__ID_VENTA'");
      
    $data['status'] = 'ok';  
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************DATOS DE AMORTIZACION******************* */
if (isset($_POST['btnBuscarDatosAmortizacion'])) {

    $idventa = $_POST['idventa'];


    $query = mysqli_query($conection, "SELECT
        fecha_registro as fecha,
        fecha_inicio_pagos as fecha_inicio,
        tipo_amortizacion as tipo,
        format(precio_venta,2) as precio_venta,
        format(capital_inicial,2) as capital_inicial,
        monto_inicial as monto_inicial,
        format(total_inicial,2) as total_inicial,
        cantidad_letras as letras,
        tea as tea
        FROM gp_amortizacion
        WHERE id_venta='$idventa' AND esta_borrado='0'");
    $respuesta = mysqli_fetch_assoc($query);

    if ($query) {

        $data['status'] = "ok";

        $data['fecha'] = $respuesta['fecha'];
        $data['fecha_inicio'] = $respuesta['fecha_inicio'];
        $data['tipo'] = $respuesta['tipo'];
        $data['precio_venta'] = $respuesta['precio_venta'];
        $data['capital_inicial'] = $respuesta['capital_inicial'];
        $data['monto_inicial'] = $respuesta['monto_inicial'];
        $data['total_inicial'] = $respuesta['total_inicial'];
        $data['letras'] = $respuesta['letras'];
        $data['tea'] = $respuesta['tea'];
    } 

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/**************************DATOS DE AMORTIZACION******************* */
if (isset($_POST['btnNuevaAmortizacion'])) {

    $idventa = $_POST['idventa'];


    $query = mysqli_query($conection, "SELECT
        cdx.texto1 as tipo_moneda,
        format(total,2) as precio_venta,
        (select SUM(importe_pago) from gp_pagos_detalle WHERE id_venta='$idventa' AND estado='2') as total_pagado,
        (select COUNT(id) from gp_cronograma WHERE id_venta='$idventa' AND esta_borrado='0' AND item_letra!='C.I.') AS total_letras,
        FROM gp_venta gpv
        INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gpv.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
        WHERE id_venta='$idventa' AND esta_borrado='0'");
    $respuesta = mysqli_fetch_assoc($query);

    $consultar_cronograma = mysqli_query($conection, "SELECT max(id) as id FROM gp_cronograma WHERE id_venta='$idventa' AND esta_borrado='0' AND pago_cubierto='2'");
    $contar_cronograma = mysqli_num_rows($consultar_cronograma);
    $respuesta_cronograma = mysqli_fetch_assoc($consultar_cronograma);
    $idcron = $respuesta_cronograma['id'];

    if($contar_cronograma>=0){
        $consultar_letra = mysqli_query($conection, "SELECT item_letra AS item FROM gp_cronograma WHERE id='$idcron'");
        $respuesta_letra = mysqli_fetch_assoc($consultar_letra);
        $item = $respuesta_letra['item'];    
    }else{
        $item=0;
    }

    if ($query) {

        $data['status'] = "ok";

        $data['fecha'] = $fecha;
        $data['fecha_inicio'] = $fecha;
        $data['tipo_moneda'] = $respuesta['tipo_moneda'];
        $data['precio_venta'] = $respuesta['precio_venta'];
        $data['total_pagado'] = $respuesta['total_pagado'];
        $data['total_letras'] = $respuesta['total_letras'] - $item;
    } 

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}



/**************************ACTUALIZAR REGISTRO VENTA******************* */
if (isset($_POST['ReturnActualizarVenta'])) {

    $IdVenta = $_POST['idVenta'];
    $IdVenta = !empty($IdVenta) ? "'$IdVenta'" : "NULL";

    $IdCliente = $_POST['idCliente'];
    $IdCliente = !empty($IdCliente) ? "'$IdCliente'" : "NULL";

    $IdLote = $_POST['idLote'];
    $IdLote = !empty($IdLote) ? "'$IdLote'" : "NULL";

    $Descripcion = "";
    $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

    $TipoComprobante = $_POST['tipoComprobante'];
    $TipoComprobante = !empty($TipoComprobante) ? "'$TipoComprobante'" : "NULL";

    $Serie = $_POST['serie'];
    $Serie = !empty($Serie) ? "'$Serie'" : "NULL";

    $Numero = $_POST['numero'];
    $Numero = !empty($Numero) ? "'$Numero'" : "NULL";

    $FechaVenta = $_POST['fechaVenta'];
    $FechaVenta = !empty($FechaVenta) ? "'$FechaVenta'" : "NULL";

    $TipoInmobiliario = $_POST['tipoInmobiliario'];
    $TipoInmobiliario = !empty($TipoInmobiliario) ? "'$TipoInmobiliario'" : "NULL";

    $TipoCasa = $_POST['tipoCasa'];
    $TipoCasa = !empty($TipoCasa) ? "'$TipoCasa'" : "NULL";

    $Condicion = $_POST['condicion'];
    $Condicion = !empty($Condicion) ? "'$Condicion'" : "NULL";

    $TipoMoneda = $_POST['tipoMoneda'];
    $TipoMoneda = !empty($TipoMoneda) ? "'$TipoMoneda'" : "NULL";

    $DescuentoMonto = $_POST['descuentoMonto'];
    $DescuentoMonto = !empty($DescuentoMonto) ? "'$DescuentoMonto'" : "0";

    $Total = $_POST['total'];
    $Total = !empty($Total) ? "'$Total'" : "0";

    $TipoCredito = $_POST['tipoCredito'];
    $TipoCredito = !empty($TipoCredito) ? "'$TipoCredito'" : "NULL";

    $CantidadLetra = $_POST['cantidadLetra'];
    $CantidadLetra = !empty($CantidadLetra) ? "'$CantidadLetra'" : "NULL";

    $TEA = $_POST['tea'];
    $TEA = !empty($TEA) ? "'$TEA'" : "NULL";

    $PrimeraFechaPago = $_POST['primeraFechaPago'];
    $PrimeraFechaPago = !empty($PrimeraFechaPago) ? "'$PrimeraFechaPago'" : "NULL";

    $CoutaInicial = $_POST['cuotaInicial'];

    $MontoCuotaInical = $_POST['montoCuotaInicial'];
    $MontoCuotaInical = !empty($MontoCuotaInical) ? "'$MontoCuotaInical'" : "0";

    $IdReservacion = $_POST['idReservacion'];
    $IdReservacion = !empty($IdReservacion) ? "'$IdReservacion'" : "NULL";

    $query = mysqli_query($conection, "call pa_gp_venta_actualizar(
    $IdVenta,
    $IdCliente,
    $IdLote,
    $Descripcion,
    $TipoComprobante,
    $Serie,
    $Numero,
    $FechaVenta,
    $TipoInmobiliario,
    $TipoCasa,
    $Condicion,
    $TipoMoneda,
    $DescuentoMonto,
    0,
    0,
    $Total,
    $TipoCredito,
    $CantidadLetra,
    $TEA,
    $PrimeraFechaPago,
    $CoutaInicial,
    $MontoCuotaInical,
    $IdReservacion,
    $IdUser
    )");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se actualizo con exito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema al guardar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************ELIMINAR REGISTRO DE VENTA******************* */
if (isset($_POST['ReturnEliminarVenta'])) {

    $IdVenta = $_POST['idVenta'];
    $IdVenta = !empty($IdVenta) ? "'$IdVenta'" : "NULL";

    $query = mysqli_query($conection, "call pa_gp_venta_eliminar(
        $IdVenta
        )");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se elimino con exito';
    } else {
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema al eliminar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************LISTA PAGINADA VENTAS******************* */
if (isset($_POST['ReturnVentaPag'])) {

    $Documento = $_POST['documento'];
    $Condicion = $_POST['condicion'];
    $Desde = $_POST['desde'];
    $Hasta = $_POST['hasta'];

    $ColumnaOrden = $_POST['columns'][$_POST['order']['0']['column']]['data'] . $_POST['order']['0']['dir'];

    $Start = intval($_POST['start']);
    $Length = intval($_POST['length']);
    if ($Length > 0) {
        $Start = (($Start / $Length) + 1);
    }
    if ($Start == 0) {
        $Start = 1;
    }
    $query = mysqli_query($conection, "call pa_gp_venta_listar_paginado($Start,$Length,'$ColumnaOrden',
    '$Documento','$Condicion','$Desde','$Hasta')");

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {

            $data['recordsTotal'] = intval($row["TotalRegistros"]);
            $data['recordsFiltered'] = intval($row["TotalRegistros"]);
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
    } else {
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************LISTA PAGINADA VENTAS******************* */
if (isset($_POST['ReturnBuscarReservaCliente'])) {
    $TipoDocumento = $_POST['tipoDocumento'];
    $Documento = $_POST['documento'];
    $query = mysqli_query($conection, "call pa_gp_buscar_reservas_cliente('$Documento','$TipoDocumento')");

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
    } else {
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/******************GENERAR IDENTIFICADOR GLOBAL************** */
function getGUID(){
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12);
        //chr(123) // "{"

        // . chr(125); // "}"
        return $uuid;
    }
}
/******************SUBIR PDF DOCUMENTOS ADJUNTO VENTA********************************** */
$formatos = array('.pdf');

if (isset($_POST['ReturnSubirAdjuntoPdf'])) {
    $Archivo = $_FILES['fileSubirAdjuntoVenta']['name'];
    $ArchivoTemporal = $_FILES['fileSubirAdjuntoVenta']['tmp_name'];
    $ext = substr($Archivo, strrpos($Archivo, '.'));
    $NuevoNombre = getGUID();
    if ($_FILES['fileSubirAdjuntoVenta']['name'] != null) {
        if (in_array($ext, $formatos)) {

            $RutaAlojar = $RUTA_ARCHIVOS_ADJUNTOS . $NuevoNombre . $ext;

            $EsAlojado = move_uploaded_file($ArchivoTemporal, "$RutaAlojar");

            if ($EsAlojado) {

                $NombreArchivoAlojado = $NuevoNombre . $ext;


                $queryInsert = mysqli_query($conection, "INSERT INTO gp_archivo
                (nombre_real,nombre_generado,id_usuario_crea)
                VALUES
                ('$Archivo','$NombreArchivoAlojado','$IdUser');");
                
                $querySelect = mysqli_query($conection, "select max(id) as idArchivo from gp_archivo where id_usuario_crea=$IdUser");

                if ($querySelect->num_rows > 0) {
                    $resultado = $querySelect->fetch_assoc();
                    $data['status'] = 'ok';
                    $data['data'] = $resultado;
                    $data['nombreArchivo'] = $NombreArchivoAlojado;
                    $data['nombreArchivoReal'] = $Archivo;
                } else {
                    $data['status'] = 'bad';
                    $data['data'] = 'No se guardo el registro';
                }
            }
        } else {
            $data['status'] = "bad";
            $data['mensaje'] = "El archivo no tiene el formato permitido";
        }
    } else {
        $data['status'] = "bad";
        $data['mensaje'] = "Debe de seleccionar un archivo";
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************GUARDAR DOCUMENTO ADJUNTOS************************** */
if (isset($_POST['ReturnGuardarAdjuntoInfo'])) {

    $IdArchivo = $_POST['idArchivo'];
    $IdArchivo = !empty($IdArchivo) ? "'$IdArchivo'" : "NULL";

    $IdVenta = $_POST['idVenta'];
    $IdVenta = !empty($IdVenta) ? "'$IdVenta'" : "NULL";

    $IdTipoDocumento = $_POST['idTipoDocumento'];
    $IdTipoDocumento = !empty($IdTipoDocumento) ? "'$IdTipoDocumento'" : "NULL";

    $Descripcion = $_POST['descripcion'];
    $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

    $FechaAdjunto = $_POST['fechaAdjunto'];
    $FechaAdjunto = !empty($FechaAdjunto) ? "'$FechaAdjunto'" : "NULL";

    $notaria = $_POST['notaria'];
    $notaria = !empty($notaria) ? "'$notaria'" : "NULL";

    $fechafirma = $_POST['fechafirma'];
    $fechafirma = !empty($fechafirma) ? "'$fechafirma'" : "NULL";

    $importeInicial = $_POST['importeInicial'];
    $importeInicial = !empty($importeInicial) ? "'$importeInicial'" : "NULL";

    $valorCerrado = $_POST['valorCerrado'];
    $valorCerrado = !empty($valorCerrado) ? "'$valorCerrado'" : "NULL";
    
    $txtTipoMonedaImporteInicial = $_POST['txtTipoMonedaImporteInicial'];
    $txtTipoMonedaImporteInicial = !empty($txtTipoMonedaImporteInicial) ? "'$txtTipoMonedaImporteInicial'" : "NULL";
    
    $txtTipoMonedaValorCerrado = $_POST['txtTipoMonedaValorCerrado'];
    $txtTipoMonedaValorCerrado = !empty($txtTipoMonedaValorCerrado) ? "'$txtTipoMonedaValorCerrado'" : "NULL";

    $queryInsert = mysqli_query($conection, "INSERT INTO gp_archivo_venta
       (id_archivo,id_venta,id_tipo_documento,descripcion,fecha_adjunto,id_usuario_crea, notaria, fechafirma, tipomoneda_importeinicial,importe_inicial, tipomoneda_valorcerrado,valor_cerrado)
        VALUES
        ($IdArchivo,$IdVenta,$IdTipoDocumento,$Descripcion,$FechaAdjunto,$IdUser,$notaria, $fechafirma, $txtTipoMonedaImporteInicial, $importeInicial, $txtTipoMonedaValorCerrado, $valorCerrado);");
    if ($queryInsert) {
        $data['status'] = 'ok';
         $data['data'] = 'Se guardo la informacion del adjunto correctamente';
    } else {
        if (!$queryInsert) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'No se guardo el registro';
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
/*******************ACTUALIZAR DOCUMENTOS ADJUNTOS************************** */
if (isset($_POST['ReturnActualizarAdjuntoInfo'])) {

    $IdArchivoVenta = $_POST['idArchivoVenta'];
    $__ID_VENTA = $_POST['__ID_VENTA'];
    $cbxTipoDocumentoAdjunto = $_POST['cbxTipoDocumentoAdjunto'];
    $txtFechaSubidaAdjunto = $_POST['txtFechaSubidaAdjunto'];
    $txtNotariaAdjunto = $_POST['txtNotariaAdjunto'];
    $txtFechaFirmaAdjunto = $_POST['txtFechaFirmaAdjunto'];
    $txtTipoMonedaImporteInicial = $_POST['txtTipoMonedaImporteInicial'];
    $txtImporteInicialAdjunto = $_POST['txtImporteInicialAdjunto'];
    $txtTipoMonedaValorCerrado = $_POST['txtTipoMonedaValorCerrado'];
    $txtValorCerradoAdjunto = $_POST['txtValorCerradoAdjunto'];
    $txtDescripcionAdjunto = $_POST['txtDescripcionAdjunto'];
    $fichero = $_POST['fichero'];
    $query_file="";

    if(!empty($fichero)){
        $adjunto = "si";

        $consultar_archivo = mysqli_query($conection, "SELECT nombre_adjunto as adjunto FROM gp_archivo_venta WHERE id='$IdArchivoVenta'");
        $respuesta_archivo = mysqli_fetch_assoc($consultar_archivo);
        $name_adjunto = $respuesta_archivo['adjunto'];
        
        unlink('../../../M03_Ventas/M03SM02_Venta/archivos/'.$name_adjunto);

        $consultar_idarchivo = mysqli_query($conection, "SELECT max(id) as id FROM gp_archivo_venta");
        $max_idarchivo = mysqli_fetch_assoc($consultar_idarchivo);
        $idfile = $max_idarchivo['id'] + 1;
        $name_file = "file-".$idfile.".pdf";
        $query_file = ",nombre_adjunto='$name_file'";

    }else{
        $adjunto = "no";
        $query_file="";
    }

    $queryUpdate = mysqli_query($conection, "UPDATE gp_archivo_venta
       SET id_tipo_documento='$cbxTipoDocumentoAdjunto',
       fecha_adjunto='$txtFechaSubidaAdjunto',
       notaria='$txtNotariaAdjunto',
       fechafirma='$txtFechaFirmaAdjunto',
       tipomoneda_importeinicial='$txtTipoMonedaImporteInicial',
       importe_inicial='$txtImporteInicialAdjunto', 
       tipomoneda_valorcerrado='$txtTipoMonedaValorCerrado', 
       valor_cerrado='$txtValorCerradoAdjunto', 
       descripcion='$txtDescripcionAdjunto'
       $query_file
       WHERE id='$IdArchivoVenta'");

    if ($queryUpdate) {
        $data['status'] = 'ok';
         $data['data'] = 'Se guardo la informacion del adjunto correctamente';
         $data['adjunto'] = $adjunto;
    } else {
        if (!$queryUpdate) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'No se guardo el registro';
        $data['adjunto'] = $adjunto;
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************ELIMINAR DOCUMENTOS ADJUNTOS************************** */
if (isset($_POST['ReturnEliminarAdjuntoInfo'])) {

    $IdArchivoVenta = $_POST['idArchivoVenta'];

    $queryUpdate = mysqli_query($conection, "UPDATE gp_archivo_venta
       SET borrado=NOW(), esta_borrado=1
       where id=$IdArchivoVenta");
    if ($queryUpdate) {
        $data['status'] = 'ok';
         $data['data'] = 'Se elimino la informacion del adjunto correctamente';
    } else {
        if (!$queryUpdate) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['status'] = 'bad';
        $data['data'] = 'No se elimino el registro';
    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}
/*******************LISTA DE DOCUMENTOS  ADJUNTO DE VENTA************************** */
if (isset($_POST['ReturnListaDocuemntosAdjuntos'])) {
    
    $IdVenta = $_POST['idVenta'];

    $query = mysqli_query($conection, "SELECT 
    gpav.id as id,
    cddddx.nombre_corto as tipo_documento,
    gpav.fecha_adjunto as fecha,
    cdx.nombre_corto as notaria,
    gpav.fechafirma as fecha_firma,
    concat(cddx.texto1,' - ',format(gpav.importe_inicial,2)) as valor_inicial,
    concat(cdddx.texto1,' - ',format(gpav.valor_cerrado,2)) as valor_cerrado,
    gpav.descripcion as descripcion,
    gpav.nombre_adjunto as adjunto,
    concat(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as dato,
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote
    FROM gp_archivo_venta gpav
    INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpav.id_venta
    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gpav.notaria AND cdx.codigo_tabla='_NOTARIA'
    INNER JOIN configuracion_detalle AS cddx ON cddx.idconfig_detalle=gpav.tipomoneda_importeinicial AND cddx.codigo_tabla='_TIPO_MONEDA'
    INNER JOIN configuracion_detalle AS cdddx ON cdddx.idconfig_detalle=gpav.tipomoneda_valorcerrado AND cdddx.codigo_tabla='_TIPO_MONEDA'
    INNER JOIN configuracion_detalle AS cddddx ON cddddx.codigo_item=gpav.id_tipo_documento AND cddddx.codigo_tabla='_TIPO_DOCUMENTO_VENTA'
    WHERE gpav.id_venta='$IdVenta' AND gpav.esta_borrado=0
    ORDER BY gpav.fecha_adjunto DESC");

    if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            
            array_push($dataList,[
                'id' => $row['id'],
                'tipo_documento' => $row['tipo_documento'],
                'fecha' => $row['fecha'],
                'notaria' => $row['notaria'],
                'fecha_firma' => $row['fecha_firma'],
                'valor_inicial' => $row['valor_inicial'],
                'valor_cerrado' => $row['valor_cerrado'],
                'descripcion' => $row['descripcion'],
                'adjunto' =>$row['adjunto'],
                'URLadjunto' =>$RUTA_ARCHIVOS_ADJUNTOS.$row['adjunto'],
                'dato' =>$row['tipo_documento'].' '.$row['lote'].'_'.$row['dato'].'_'.$row['fecha_firma']
            ]);
        }
        
        $data['status'] = 'ok';
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        $data['data'] = $dataList;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************DETALLE DE DOCUMENTOS  ADJUNTO DE VENTA************************** */
if (isset($_POST['ReturnDetalleAdjuntoInfo'])) {
    
    $IdArchivoVenta = $_POST['idArchivoVenta'];

    $query = mysqli_query($conection, "SELECT 
    gpav.id as id,
    gpav.id_tipo_documento as tipodocumento,
    gpav.fecha_adjunto as fecha,
    gpav.notaria as notaria,
    gpav.fechafirma as fecha_firma,
    gpav.tipomoneda_importeinicial as tp_inicial,
    gpav.importe_inicial as importe_inicial,
    gpav.tipomoneda_valorcerrado as tp_valor_cerrado,
    gpav.valor_cerrado as valor_cerrado,
    gpav.descripcion as descripcion
    FROM gp_archivo_venta gpav
    INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpav.id_venta 
    WHERE gpav.id='$IdArchivoVenta'");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro resgistro';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************BUSCAR INFORMACION RESERVA************************** */
if (isset($_POST['ReturnBuscarInformacionReserva'])) {
    
    $IdReserva = $_POST['idReserva'];

    $query = mysqli_query($conection, "SELECT 
    tbl1.area, 
    tbl2.texto1 as moneda,
     tbl1.valor_con_casa as valoLoteCasa , 
     tbl1.valor_sin_casa as valorLoteSolo ,
     tbl1.idlote as idLote,
     tbl3.idmanzana as idManzana,
     tbl4.idzona as idZona,
     tbl5.idproyecto as idProyecto,
     tbl7.id as idCliente,
     tbl7.tipodocumento as tipoDocumento,
     tbl7.documento,
     tbl7.nombres,
     tbl7.apellido_paterno as apellidoPaterno,
     tbl7.apellido_materno as apellidoMaterno,
     CONCAT(ifnull(tbl11.nombre_corto,''),' ',ifnull(tbl7.nombre_via,''),' ',ifnull(tbl7.nro_via,''),' Lt ',ifnull(tbl7.lote,''),' Mz. ',ifnull(tbl7.manzana,''),' - ',tbl8.nombre,' ',tbl9.nombre,' ',tbl10.nombre) as direccion,
    tbl6.id_reservacion as idReserva,
    tbl6.tipo_casa as idTipoCasa,
    tbl6.tipo_moneda_reserva as idTipoMonedaReserva,
    tbl6.monto_reservado as montoReserva
    FROM gp_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl2 on tbl1.tipo_moneda=tbl2.idconfig_detalle
    inner join gp_manzana tbl3 on tbl1.idmanzana=tbl3.idmanzana
    inner join gp_zona tbl4 on tbl3.idzona=tbl4.idzona
    inner join gp_proyecto tbl5 on tbl5.idproyecto=tbl4.idproyecto
     LEFT JOIN (select * from gp_reservacion where esta_borrado=0 and estado =1)AS tbl6 on tbl1.idlote=tbl6.id_lote
     inner join datos_cliente tbl7 on tbl6.id_cliente=tbl7.id
     left join ubigeo_region tbl8 on tbl7.id_dom_departamento=tbl8.codigo
    left join ubigeo_provincia tbl9 on tbl7.id_dom_provincia=tbl9.codigo 
    left join ubigeo_distrito tbl10 on tbl7.id_dom_distrito=tbl10.codigo
    LEFT JOIN (select *  FROM configuracion_detalle WHERE codigo_tabla='_VIA' AND estado='ACTI') AS tbl11 on tbl7.tipo_via=tbl11.idconfig_detalle
    where tbl6.id_reservacion=$IdReserva LIMIT 1");

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        $data['status'] = 'bad';
        $data['urlRegistroCliente'] = 'No se encontro registro de reserva';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************VALIDAR TIPO MONEDA***********************************/
if (isset($_POST['ValidarTipoMoneda'])) {
    $TipoMoneda = $_POST['TipoMoneda'];

    $query = mysqli_query($conection, "SELECT nombre_corto as nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND idconfig_detalle='$TipoMoneda'");

    //CONSULTAR TIPO CAMBIO
    $consultar_tipocambio = mysqli_query($conection, "SELECT valor as valor FROM configuracion_valores WHERE descripcion='_TIPO_CAMBIO' AND  estado='1'");
    $respuesta_tipocambio = mysqli_fetch_assoc($consultar_tipocambio);
    $tipo_cambio = $respuesta_tipocambio['valor'];

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['tipo_cambio'] = $tipo_cambio;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ListarZonas'])) {

    $idProyecto = intval($_POST['idProyecto']);
    $query = mysqli_query($conection, "SELECT idzona as valor, nombre as texto FROM gp_zona WHERE idproyecto='$idProyecto'");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['valor'],
                'texto' => $row['texto'],
            ]);}
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

if (isset($_POST['ListarManzanas'])) {

    $idzona = intval($_POST['idZona']);
    $query = mysqli_query($conection, "SELECT idmanzana as valor, nombre as texto FROM gp_manzana WHERE idzona='$idzona'");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['valor'],
                'texto' => $row['texto'],
            ]);}
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

if (isset($_POST['ListarLotes'])) {

    $idManzana = intval($_POST['idManzana']);
    $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idManzana' AND estado NOT IN (5,6,7)");

    array_push($dataList, [
        'valor' => '',
        'texto' => 'Seleccionar',
    ]);

    if ($query->num_rows > 0) {

        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['valor'],
                'texto' => $row['texto'],
            ]);}
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}



/*******************IMPORTES DE VENTA************************** */
if (isset($_POST['btnBuscarImportesVentas'])) {

    $idventa = $_POST['idventa'];

    $consultar_idcronograma = mysqli_query($conection, "SELECT max(id) as id FROM gp_cronograma WHERE id_venta='$idventa' AND estado='2' AND esta_borrado='0' AND pago_cubierto='2'");
    $respuesta_idcronograma = mysqli_fetch_assoc($consultar_idcronograma);
    $idcronograma = $respuesta_idcronograma['id'];

    $query = mysqli_query($conection, "SELECT 
    gpv.id_cliente as idCliente,
    gpv.id_lote as idlote,
    dc.tipodocumento as tipoDocumento,
    dc.documento as documento,
    dc.nombres as nombres,
    dc.apellido_paterno as apellidoPaterno,
    dc.apellido_materno as apellidoMaterno,
    dc.nombre_via as direccion,
    gpl.idlote as idlote,
    gpm.idmanzana as idmanzana,
    gpz.idzona as idzona,
    gpp.idproyecto as idproyecto,
    gpv.id_amortizacion as idamortizacion,
    cdx.texto1 as tipo_moneda,
    gpv.fecha_venta as fecha_venta,
    concat('Zn: ',gpz.nombre,' - Mz: ',SUBSTRING(gpm.nombre,9,2), ' - Lt: ',SUBSTRING(gpl.nombre,6,2)) as propiedad,
    format(gpv.total,2) as precio_venta,
    format(gpv.monto_cuota_inicial,2) as monto_inicial,
    format(ROUND((SELECT (SUM(gppd.pagado) + if((select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1')>0,(select sum(monto_letra) from gp_cronograma where id_venta=gpv.id_venta AND dscto_acuerdo='1'),0)) FROM gp_pagos_cabecera pagoc, gp_pagos_detalle gppd, gp_cronograma gpcr 
            WHERE pagoc.id_cronograma=gpcr.correlativo AND gpcr.id_venta=gpv.id_venta AND pagoc.id_venta=gpv.id_venta AND pagoc.idpago=gppd.idpago AND gppd.esta_borrado='0' AND gpcr.estado=2 AND pagoc.esta_borrado='0' AND gpcr.esta_borrado='0'),2),2)  as total_pagado,
    if((SELECT item_letra FROM gp_cronograma WHERE id='$idcronograma') IS NULL, 0, (SELECT item_letra FROM gp_cronograma WHERE id='$idcronograma')) as ultima_letra,
    (SELECT COUNT(item_letra) FROM gp_cronograma WHERE id_venta='$idventa' AND esta_borrado='0' AND item_letra!='C.I.') as total_letras
    FROM gp_venta gpv
    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
    INNER JOIN configuracion_detalle AS cdx ON cdx.idconfig_detalle=gpv.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
    WHERE gpv.id_venta='$idventa'");
    $respuesta = mysqli_fetch_assoc($query);
    $id_lote = $respuesta['idlote'];

    $consultar_idreservacion = mysqli_query($conection, "SELECT id_reservacion as id FROM gp_reservacion WHERE id_lote='$id_lote'");
    $respuesta_idreservacion = mysqli_num_rows($consultar_idreservacion);
    $respuesta_id_reservacion = mysqli_fetch_assoc($consultar_idreservacion);
    $id_reservacion="";
    if($respuesta_idreservacion>0){
        $id_reservacion = $respuesta_id_reservacion['id'];
    } 

    if($query->num_rows > 0) {
        $data['status'] = "ok";

        $data['idventa'] = $idventa;
        $data['idCliente'] = $respuesta['idCliente'];
        $data['id_reservacion'] = $id_reservacion;
        $data['tipoDocumento'] = $respuesta['tipoDocumento'];
        $data['documento'] = $respuesta['documento'];
        $data['nombres'] = $respuesta['nombres'];
        $data['apellidoPaterno'] = $respuesta['apellidoPaterno'];
        $data['apellidoMaterno'] = $respuesta['apellidoMaterno'];
        $data['direccion'] = $respuesta['direccion'];
        $data['idlote'] = $respuesta['idlote'];
        $data['idmanzana'] = $respuesta['idmanzana'];
        $data['idzona'] = $respuesta['idzona'];
        $data['idproyecto'] = $respuesta['idproyecto'];
        $data['idamortizacion'] = $respuesta['idamortizacion'];

        $data['fecha_venta'] = $respuesta['fecha_venta'];
        $data['propiedad'] = $respuesta['propiedad'];

        $data['fecha_actual'] = $fecha;    
        $data['tipo_moneda'] = $respuesta['tipo_moneda'];
        $data['precio_venta'] = $respuesta['precio_venta'];
        $data['monto_inicial'] = $respuesta['monto_inicial'];
        $data['total_pagado'] = $respuesta['total_pagado'];
        $data['ultima_letra'] = $respuesta['ultima_letra']." / ".$respuesta['total_letras'];
    } else {
        $data['status'] = "bad";
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnLlenarTablaCronograma'])){

    $idventa = isset($_POST['idventa']) ? $_POST['idventa'] : Null;
    $idventar = trim($idventa);

        $query = mysqli_query($conection,"SELECT 
            gpcp.fecha_vencimiento as fecha,
            gpcp.item_letra as letra,
            format(gpcp.monto_letra,2) as monto,
            format(gpcp.interes_amortizado,2) as intereses,
            format(gpcp.capital_amortizado,2) as amortizacion,
            format(gpcp.capital_vivo,2) as capital_vivo,
            format(gpcp.monto_letra,2) as pagado,
            gpcp.estado as estado,
            cd.nombre_corto as descEstado,
            cd.texto1 as color
            FROM gp_cronograma gpcp
            INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcp.estado AND cd.codigo_tabla='_ESTADO_EC'
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcp.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            WHERE gpcp.esta_borrado=0 AND 
            gpcp.id_venta='$idventar'
            ORDER BY gpcp.correlativo ASC
            "); 

        
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'fecha' => $row['fecha'],
                'letra' => $row['letra'],
                'monto' => $row['monto'],
                'intereses' => $row['intereses'],
                'amortizacion' => $row['amortizacion'],
                'capital_vivo' => $row['capital_vivo'],
                'pagado' => $row['pagado'],
                'estado' => $row['estado'],
                'descEstado' => $row['descEstado'],
                'color' => $row['color']
                
            ]);
        }
            
       $data['data'] = $dataList;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;

    }else{
        
            $data['data'] = $dataList;
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    }
}


if (isset($_POST['btnBuscarLetras'])) {

    $idTipo = $_POST['idTipo'];
    $idVenta = $_POST['__ID_VENTA'];

    //Consultar idcronograma
    $consultar_maxLetra = mysqli_query($conection, "SELECT MAX(id) as id FROM gp_cronograma WHERE id_venta='$idVenta' AND estado='2' AND pago_cubierto='2'");
    $respuesta_maxLetra = mysqli_fetch_assoc($consultar_maxLetra);
    $id_crono = $respuesta_maxLetra['id'];

    //consultar ultima letra y letras totales
    $consultar_ultimaLetra = mysqli_query($conection, "SELECT item_letra FROM gp_cronograma WHERE id='$id_crono'");
    $respuesta_ultimaLetra = mysqli_fetch_assoc($consultar_ultimaLetra);
    $ulti_letra = $respuesta_ultimaLetra['item_letra'];

    $consultar_totalLetras = mysqli_query($conection,"SELECT COUNT(id) as total FROM gp_cronograma WHERE id_venta='$idVenta'");
    $respuesta_totalLetras = mysqli_fetch_assoc($consultar_totalLetras);
    $total_letra = $respuesta_totalLetras['total'];

    $resto_letras = $total_letra - $ulti_letra;

    if ($consultar_totalLetras->num_rows > 0) {

        $data['status'] = "ok";
        $data['letras'] = $resto_letras;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['status'] = "bad";
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}

if (isset($_POST['btnCalcularTotalInicial'])) {

    $montoInicial = $_POST['montoInicial'];
    $idVenta = $_POST['__ID_VENTA'];

    $consultar_totalLetras = mysqli_query($conection,"SELECT format((SUM(importe_pago) + '$montoInicial'),2) as total FROM gp_pagos_detalle WHERE id_venta='$idVenta' AND estado='2'");
    $respuesta_totalLetras = mysqli_fetch_assoc($consultar_totalLetras);
    $total = $respuesta_totalLetras['total'];

    if ($consultar_totalLetras->num_rows > 0) {

        $data['status'] = "ok";
        $data['total'] = $total;
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['status'] = "bad";
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}


if (isset($_POST['btnGenerarDatosVentasAmortizacion'])) {

    $idVenta = $_POST['__ID_VENTA'];

    $consultar_total_letras = mysqli_query($conection, "SELECT COUNT(id) as total FROM gp_cronograma WHERE id_venta='$idVenta' AND esta_borrado='0' AND item_letra!='C.I.'");
    $respuesta_total_letras = mysqli_fetch_assoc($consultar_total_letras);
    $total_letras = $respuesta_total_letras['total'];

    $consultar_max_letra = mysqli_query($conection, "SELECT MAX(id) as id FROM gp_cronograma WHERE id_venta='$idVenta' AND esta_borrado='0' AND pago_cubierto='2'");
    $contar_max_letra = mysqli_num_rows($consultar_max_letra);
    
    if($contar_max_letra>0){
        $id_max_letra=0;
        $respuesta_max_letra = mysqli_fetch_assoc($consultar_max_letra);
        $id_max_letra = $respuesta_max_letra['id'];

        $item="";
        if(!empty($id_max_letra)){
            $consultar_item = mysqli_query($conection, "SELECT item_letra as item FROM gp_cronograma WHERE esta_borrado='0' AND id = '$id_max_letra'");
            $respuesta_item = mysqli_fetch_assoc($consultar_item);
            $item = $respuesta_item['item'];
        

            if($item == 'C.I.'){
                $letra = 0;
            }else{
                $letra = $item;
            }
        }else{
            $letra = 0;
        }

    }else{
        $letra = 0;
    }    

    $consultar_datos_venta = mysqli_query($conection, "SELECT 
        format(gpv.total,2) as precio_venta,
        format((SELECT SUM(pagado) FROM gp_pagos_detalle WHERE id_venta=gpv.id_venta AND estado='2'),2) as capital_inicial
        FROM gp_venta gpv
        WHERE gpv.id_venta='$idVenta'");
    $respuesta_datos_venta = mysqli_fetch_assoc($consultar_datos_venta);

    if ($consultar_total_letras->num_rows > 0) {

        $data['status'] = "ok";

        $data['total'] = intval($total_letras) - intval($letra);
        $data['precio_venta'] = $respuesta_datos_venta['precio_venta'];
        $data['capital_inicial'] = $respuesta_datos_venta['capital_inicial'];
        $data['total_inicial'] = $respuesta_datos_venta['capital_inicial'];
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
        $data['status'] = "bad";
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}


