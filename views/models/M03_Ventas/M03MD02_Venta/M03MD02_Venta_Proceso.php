<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
$IdUser=$respuesta_idusu['id'];

$data = array();
$dataList = array();

if (isset($_POST['btnEliminarCronogramaTemporal'])) {
    $cbxLote = $_POST['cbxLote'];

    $query = mysqli_query($conection, "DELETE FROM gp_cronograma_temporal WHERE id_lote='$cbxLote'");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = 'Se eliminaron los datos del cronograma de pagos generado de forma manual para la venta.';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se encontro datos';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if(isset($_POST['btnConsultarCronogramaManual'])){

    $cbxLote = $_POST['cbxLote'];

    $query = mysqli_query($conection, "SELECT 
    id as id,
    date_format(fecha_vencimiento, '%d/%m/%Y') as fecha_vencimiento,
    item_letra as item_letra,
    format(monto_letra,2) as monto_letra,
    format(interes_amortizado,2) as interes_amortizado,
    format(capital_amortizado,2) as capital_amortizado,
    format(capital_vivo,2) as capital_vivo,
    creado as registro
    FROM gp_cronograma_temporal
    where id_lote='$cbxLote' ORDER BY correlativo ASC");

     
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
            
            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'fecha_vencimiento' => $row['fecha_vencimiento'],
                'item_letra' => $row['item_letra'],
                'monto_letra' => $row['monto_letra'],
                'interes_amortizado' => $row['interes_amortizado'],
                'capital_amortizado' => $row['capital_amortizado'],
                'capital_vivo' => $row['capital_vivo'],
                'registro' => $row['registro']
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

if (isset($_POST['btnValidarImportePag'])) {

    $txtTipoCambioPP = $_POST['txtTipoCambioPP'];
    $txtImportePagoPP = $_POST['txtImportePagoPP'];

    if (!empty($txtTipoCambioPP) && !empty($txtImportePagoPP)) {
        
        if($txtTipoCambioPP>0){
            $totales = round(($txtImportePagoPP/$txtTipoCambioPP),2);
        }else{
            $totales = $txtImportePagoPP;
        }
        
        $data['status'] = 'ok';
        $data['total'] = $totales;
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'Ocurrio un problema al eliminar el registro.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnValidarMonedaPP'])) {

    $cbxTipoMonedaPP = $_POST['cbxTipoMonedaPP'];
    $txtImportePagoPP = $_POST['txtImportePagoPP'];

    $query = mysqli_query($conection, "SELECT texto1 as Nombre FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND idconfig_detalle='$cbxTipoMonedaPP' AND estado='ACTI'");
    
    $consultar_tipocambio = mysqli_query($conection, "SELECT valor as tc FROM configuracion_valores WHERE descripcion='_TIPO_CAMBIO'");
    $respuesta_tipocambio = mysqli_fetch_assoc($consultar_tipocambio);
    $tipocambio = $respuesta_tipocambio['tc'];
    

    if ($query) {
        
        $respuesta = mysqli_fetch_assoc($query);
        $Nombre = $respuesta['Nombre'];
        
        $data['status'] = 'ok';
        $data['Nombre'] = $Nombre;
        
        if($Nombre=="PEN"){
            $totales = round(($txtImportePagoPP/$tipocambio),2);
        }else{
            $totales = $txtImportePagoPP;
            $tipocambio = "0.00";
        }
        
        $data['tipocambio'] = $tipocambio;
        $data['total'] = $totales;
        
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

if (isset($_POST['BuscarIdVenta'])) {

    $idLote = $_POST['idLote'];

    $query = mysqli_query($conection, "SELECT id_venta AS id FROM gp_venta WHERE id_lote='$idLote'");

    if ($query) {
        
        $respuesta = mysqli_fetch_assoc($query);
        $id_venta = $respuesta['id'];
        
        $data['status'] = 'ok';
        $data['data'] = $id_venta;
        
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

if(isset($_POST['btnVerificarVenta'])){
    
    $idcliente = $_POST['idcliente'];
    $idventa = $_POST['idventa'];

    if(!empty($idcliente) && !empty($idventa)){

        $consultar_idventa = mysqli_query($conection, "SELECT conformidad as conformidad FROM gp_venta WHERE  id_cliente='$idcliente' AND id_venta='$idventa'");
        $contar_idventa = mysqli_num_rows($consultar_idventa);
        if($contar_idventa>0){

            $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
            $id_conforme = $respuesta_idventa['conformidad'];

            if($id_conforme=="1"){
                $data['status'] = 'ok';
            }else{
                $data['status'] = 'bad';
            }
        }else{
            $data['status'] = 'bad';
        }
    }else{
        $data['status'] = 'bad';
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['btnMostrarVoucher'])){
    
    $dato_voucher = $_POST['dato_voucher'];
    $ruta = $_POST['ruta'];

    $nom_voucher = $dato_voucher;

    $cadena = explode(".", $nom_voucher);
    $formato = $cadena[1];
    
    $data['status'] = 'ok';
    $data['formato'] = $formato;
    $data['voucher'] = $nom_voucher;
    $data['ruta']=$ruta;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if (isset($_POST['btnCalcularTotal'])) {

    $documento = $_POST['documento'];
    $idlote = $_POST['$idlote'];

    $consultar_datos_cliente = mysqli_query($conection, "SELECT documento as doc FROM datos_cliente WHERE id='$idcliente'");
    $respuesta_datos_cliente = mysqli_fetch_assoc($consultar_datos_cliente);
    $doc_cliente = $respuesta_datos_cliente['doc'];

    $data['status'] = 'ok';
    $data['documento'] = $doc_cliente;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

if (isset($_POST['ReturnIrCliente'])) {

    $idcliente = $_POST['idRegistro'];
    $idcliente = decrypt($idcliente, "123");

    $consultar_datos_cliente = mysqli_query($conection, "SELECT documento as doc FROM datos_cliente WHERE id='$idcliente'");
    $respuesta_datos_cliente = mysqli_fetch_assoc($consultar_datos_cliente);
    $doc_cliente = $respuesta_datos_cliente['doc'];

    $data['status'] = 'ok';
    $data['documento'] = $doc_cliente;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

if(isset($_POST['btnCargarArchivo'])){

    $__ID_VENTA = $_POST['__ID_VENTA'];

    $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_venta='$__ID_VENTA'");
    $respuesta_idventa = mysqli_num_rows($consultar_idventa);

    if($respuesta_idventa>0){
        $__ID_VENTA = $__ID_VENTA;
    }else{
        $__ID_VENTA = decrypt($__ID_VENTA,"123");
    }    

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
    $ValidUsuario = $_POST['ValidUsuario'];
    $ValidUsuario = decrypt($ValidUsuario,"123");
    
    if(!empty($__ID_VENTA)){

        if(!empty($cbxTipoDocumentoAdjunto)){

            if(!empty($txtFechaSubidaAdjunto)){

                /*if(!empty($txtNotariaAdjunto)){*/

                    if(!empty($txtFechaFirmaAdjunto)){

                       /* if(!empty($txtTipoMonedaImporteInicial) && !empty($txtImporteInicialAdjunto)){

                            if(!empty($txtTipoMonedaValorCerrado) && !empty($txtValorCerradoAdjunto)){*/

                                if(!empty($fichero)){

                                   
                                    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$ValidUsuario'");
                                    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
                                    $id_user = $respuesta_idusuario['id'];
                                
                                    $consultar_archivo = mysqli_query($conection, "SELECT MAX(gpav.id) as id FROM gp_archivo_venta gpav");
                                    $respuesta_archivo = mysqli_num_rows($consultar_archivo);

                                    if($respuesta_archivo > 0){

                                        $conteo = mysqli_fetch_assoc($consultar_archivo);
                                        $max_conteo = $conteo['id'] + 1;
                                        $name_file = "file-".$max_conteo.".pdf";
                                        $nom_fichero = pathinfo($fichero, PATHINFO_FILENAME);
                                        $nom_fichero = str_replace(array("C:fakepath"), '', $fichero);
                                        $insertar_archivo = mysqli_query($conection, "INSERT INTO gp_archivo_venta(id_archivo, id_venta, id_tipo_documento, descripcion, fecha_adjunto, id_usuario_crea, notaria, fechafirma, tipomoneda_importeinicial, importe_inicial, tipomoneda_valorcerrado, valor_cerrado, nombre_adjunto,nombre_archivo) VALUES ('$max_conteo','$__ID_VENTA','$cbxTipoDocumentoAdjunto','$txtDescripcionAdjunto','$txtFechaSubidaAdjunto','$id_user','$txtNotariaAdjunto','$txtFechaFirmaAdjunto','$txtTipoMonedaImporteInicial','$txtImporteInicialAdjunto','$txtTipoMonedaValorCerrado','$txtValorCerradoAdjunto', '$name_file','$nom_fichero')");

                                        $data['status'] = 'ok';
                                        $data['valor'] = $__ID_VENTA;
                                        $data['mensaje'] = "Se registro el adjunto correctamente.";

                                    }else{

                                        $name_file = "file-1.pdf";
                                        $insertar_archivo = mysqli_query($conection, "INSERT INTO gp_archivo_venta(id_archivo, id_venta, id_tipo_documento, descripcion, fecha_adjunto, id_usuario_crea, notaria, fechafirma, tipomoneda_importeinicial, importe_inicial, tipomoneda_valorcerrado, valor_cerrado, nombre_adjunto,nombre_archivo) VALUES ('$max_conteo','$__ID_VENTA','$cbxTipoDocumentoAdjunto','$txtDescripcionAdjunto','$txtFechaSubidaAdjunto','$id_user','$txtNotariaAdjunto','$txtFechaFirmaAdjunto','$txtTipoMonedaImporteInicial','$txtImporteInicialAdjunto','$txtTipoMonedaValorCerrado','$txtValorCerradoAdjunto', '$name_file','$nom_fichero')");

                                        $data['status'] = 'ok';
                                        $data['valor'] = $__ID_VENTA;
                                        $data['mensaje'] = "Se registro el adjunto correctamente.";

                                    }
                                }else{
                                    $data['status'] = 'bad';
                                    $data['mensaje'] = "Seleccione el documento adjunto.";
                                }

                           /* }else{
                                $data['status'] = 'bad';
                                $data['mensaje'] = "Seleccione tipo de moneda e ingrese el monto con el que se concreto la venta de la propiedad.";
                            }


                        }else{
                            $data['status'] = 'bad';
                            $data['mensaje'] = "Seleccione tipo de moneda e ingrese el monto inicial de la propiedad.";
                        }*/


                    }else{
                        $data['status'] = 'bad';
                        $data['mensaje'] = "Ingrese fecha de la firma del documento adjunto.";
                    }


                /*}else{
                    $data['status'] = 'bad';
                    $data['mensaje'] = "Seleccione la notaria en relacion con el documento adjunto.";
                }*/

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
    $idcliente = $_POST['idcliente'];
    $dato_idreserva = $_POST['idReserva'];
    if($dato_idreserva!="ninguno"){
        $dato_idreserva = decrypt($dato_idreserva, "123");
    }
    $resul_query = "";
    $aplica1="";
    if(!empty($Documento) && empty($idlote)){

        $contar_idcliente=0;
        $idcliente = 0;
        //CONSULTAR ID CLIENTE
        $consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$Documento'");
        $respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
        $contar_idcliente = mysqli_num_rows($consultar_idcliente);

        if($contar_idcliente>0){

            $idcliente = $respuesta_idcliente['id'];            
            //CONSULTAR SI TIENE RESERVACION
            $consultar_reservacion = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_cliente='$idcliente' AND estado='2' AND esta_borrado='0'");
            $respuesta_reservacion = mysqli_num_rows($consultar_reservacion);
            
            if($respuesta_reservacion>0){
                $respuesta_reserva = mysqli_fetch_assoc($consultar_reservacion);
                $dato_idreserva=$respuesta_reserva['id_reservacion'];
                $aplica = "si";
                $aplica1 = $dato_idreserva;
                $resul_query = "valido";
                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.documento as documento,
                dc.email as correo,
                dc.celular_1 as celular,
                gpl.idlote as idlote,
                gpm.idmanzana as idmanzana,
                gpz.idzona as idzona,
                gpp.idproyecto as idproyecto,
                format(gpl.area, 2) as area,
                cd.nombre_corto as lote_tipo_moneda,
                format(gpl.valor_con_casa,2) as lote_valor_casa,
                format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                gpr.monto_reservado as monto_reserva,
                concat(dc.celular_1,' - ',dc.email) as contacto
                FROM datos_cliente dc
                INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.id_reservacion='$dato_idreserva' AND gpr.esta_borrado=0 AND gpr.estado='2'
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
                dc.documento as documento,
                dc.email as correo,
                concat(dc.celular_1,' - ',dc.email) as contacto
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
                dc.documento as documento,
                dc.celular_1 as celular,
                dc.email as correo
                FROM datos_cliente dc
                WHERE dc.esta_borrado=0 AND dc.id='0'");
        }
        
    }else{
        if(empty($Documento) && !empty($idlote)){
            
            //CONSULTAR SI TIENE RESERVACION
            $consultar_reservacion = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_lote='$idlote' AND estado='2' AND esta_borrado='0'");
            $respuesta_reservacion = mysqli_num_rows($consultar_reservacion);
            
            if($respuesta_reservacion>0){
                
                $aplica = "si";
                $aplica1 = "siNN";
                $resul_query = "valido";
                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.documento as documento,
                dc.email as correo,
                dc.celular_1 as celular,
                gpl.idlote as idlote,
                gpm.idmanzana as idmanzana,
                gpz.idzona as idzona,
                gpp.idproyecto as idproyecto,
                format(gpl.area, 2) as area,
                cd.nombre_corto as lote_tipo_moneda,
                format(gpl.valor_con_casa,2) as lote_valor_casa,
                format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                gpr.monto_reservado as monto_reserva,
                concat(dc.celular_1,' - ',dc.email) as contacto
                FROM datos_cliente dc
                INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                WHERE dc.esta_borrado=0 AND gpl.idlote='$idlote'");
                
                $my = "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.documento as documento,
                dc.email as correo,
                dc.celular_1 as celular,
                gpl.idlote as idlote,
                gpm.idmanzana as idmanzana,
                gpz.idzona as idzona,
                gpp.idproyecto as idproyecto,
                format(gpl.area, 2) as area,
                cd.nombre_corto as lote_tipo_moneda,
                format(gpl.valor_con_casa,2) as lote_valor_casa,
                format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                gpr.monto_reservado as monto_reserva,
                concat(dc.celular_1,' - ',dc.email) as contacto
                FROM datos_cliente dc
                INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                WHERE dc.esta_borrado=0 AND gpl.idlote='$idlote'";
                
            }else{
                //REQUIERE NUEVO REGISTRO DE CLIENTE
                $resul_query = "NewRegister";
            }
        
        }else{
            if(!empty($Documento) && !empty($idlote)){
                //CONSULTAR ID CLIENTE
                $consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$Documento'");
                $respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
                $idcliente = $respuesta_idcliente['id'];
                
                //CONSULTAR SI TIENE RESERVACION
                $consultar_reservacion = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_cliente='$idcliente' AND id_lote='$idlote' AND estado='2' AND esta_borrado='0'");
                $respuesta_reservacion = mysqli_num_rows($consultar_reservacion);
                
                if($respuesta_reservacion>0){
                    
                    $aplica = "si";
                    $aplica1 = "sixx";
                    $resul_query = "valido";
                    $query = mysqli_query($conection, "SELECT
                    dc.id as id,
                    dc.nombres as nombres,
                    dc.apellido_paterno as apellidoPaterno,
                    dc.apellido_materno as apellidoMaterno,
                    dc.documento as documento,
                    dc.email as correo,
                    dc.celular_1 as celular,
                    gpl.idlote as idlote,
                    gpm.idmanzana as idmanzana,
                    gpz.idzona as idzona,
                    gpp.idproyecto as idproyecto,
                    format(gpl.area, 2) as area,
                    cd.nombre_corto as lote_tipo_moneda,
                    format(gpl.valor_con_casa,2) as lote_valor_casa,
                    format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                    gpr.monto_reservado as monto_reserva,
                    concat(dc.celular_1,' - ',dc.email) as contacto
                    FROM datos_cliente dc
                    INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                    INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                    WHERE dc.esta_borrado=0 AND dc.id='$idcliente' AND gpl.idlote='$idlote'");
                    
                    $my = "SELECT
                    dc.id as id,
                    dc.nombres as nombres,
                    dc.apellido_paterno as apellidoPaterno,
                    dc.apellido_materno as apellidoMaterno,
                    dc.documento as documento,
                    dc.email as correo,
                    dc.celular_1 as celular,
                    gpl.idlote as idlote,
                    gpm.idmanzana as idmanzana,
                    gpz.idzona as idzona,
                    gpp.idproyecto as idproyecto,
                    format(gpl.area, 2) as area,
                    cd.nombre_corto as lote_tipo_moneda,
                    format(gpl.valor_con_casa,2) as lote_valor_casa,
                    format(gpl.valor_sin_Casa,2) as lote_valor_solo,
                    gpr.monto_reservado as monto_reserva,
                    concat(dc.celular_1,' - ',dc.email) as contacto
                    FROM datos_cliente dc
                    INNER JOIN gp_reservacion AS gpr ON gpr.id_cliente=dc.id AND gpr.esta_borrado=0 AND gpr.estado='2'
                    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpr.id_lote
                    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
                    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
                    INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
                    INNER JOIN configuracion_detalle AS cd ON cd.idconfig_detalle=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
                    WHERE dc.esta_borrado=0 AND dc.id='$idcliente' AND gpl.idlote='$idlote'";
                    
                }else{
                    
                    $aplica = "no";
                    $resul_query = "valido";
                    $query = mysqli_query($conection, "SELECT
                    dc.id as id,
                    dc.nombres as nombres,
                    dc.apellido_paterno as apellidoPaterno,
                    dc.apellido_materno as apellidoMaterno,
                    dc.documento as documento,
                    dc.email as correo,
                    dc.celular_1 as celular,
                    concat(dc.celular_1,' - ',dc.email) as contacto
                    FROM datos_cliente dc
                    WHERE dc.esta_borrado=0 AND dc.id='$idcliente'");
                    
                    $my = "SELECT
                    dc.id as id,
                    dc.nombres as nombres,
                    dc.apellido_paterno as apellidoPaterno,
                    dc.apellido_materno as apellidoMaterno,
                    dc.documento as documento,
                    dc.email as correo,
                    dc.celular_1 as celular,
                    concat(dc.celular_1,' - ',dc.email) as contacto
                    FROM datos_cliente dc
                    WHERE dc.esta_borrado=0 AND dc.id='$idcliente'";
                    
                }
            }else{

                $query = mysqli_query($conection, "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.documento as documento,
                dc.email as correo,
                dc.celular_1 as celular,
                concat(dc.celular_1,' - ',dc.email) as contacto
                FROM datos_cliente dc
                WHERE dc.esta_borrado=0 AND dc.id='0'");
                
                $my = "SELECT
                dc.id as id,
                dc.nombres as nombres,
                dc.apellido_paterno as apellidoPaterno,
                dc.apellido_materno as apellidoMaterno,
                dc.documento as documento,
                dc.email as correo,
                dc.celular_1 as celular,
                concat(dc.celular_1,' - ',dc.email) as contacto
                FROM datos_cliente dc
                WHERE dc.esta_borrado=0 AND dc.id='0'";
            }
        }
    }    
    $importe = 0;
    $monto=0;
    if($dato_idreserva!="ninguno"){
        $consultar_datos_reserva = mysqli_query($conection, "SELECT round(importe_precio,2) as importe, round(monto_reservado,2) as monto FROM gp_reservacion WHERE id_reservacion='$dato_idreserva'");
        $respuesta_datos_reserva = mysqli_fetch_assoc($consultar_datos_reserva);
        $importe = $respuesta_datos_reserva['importe'];
        $monto = $respuesta_datos_reserva['monto'];
    }
    
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['accion'] = $aplica;
        $data['valor'] = $aplica1;
        
        $data['importe'] = $importe;
        $data['monto'] = $monto;
    } else {
        $data['status'] = 'bad';
        $data['query'] = $my;
        $data['data'] = 'El lote no tiene asignado un cliente.';
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
    gpl.area as area, 
    cd.texto1 as moneda, 
    format(gpl.valor_con_casa,2) as valoLoteCasa, 
    format(gpl.valor_sin_casa,2) as valorLoteSolo 
    FROM gp_lote gpl
    INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
    where gpl.idlote='$IdeLote' LIMIT 1");

    //Consulta si existe registros de reserva
    $consultar_reserva = mysqli_query($conection, "SELECT count(id_reservacion) as total FROM gp_reservacion WHERE id_lote='$IdeLote' AND estado='2'");
    $respuesta_reserva = mysqli_fetch_assoc($consultar_reserva);
    $tota_reservas = $respuesta_reserva['total'];

    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['reservas'] = $tota_reservas;
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
    

    $query = mysqli_query($conection, "SELECT cd.codigo_item as valor, cd.nombre_corto as texto 
    FROM gp_manzana_tipocasa gpm
    INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpm.tipo_casa AND cd.codigo_tabla='_TIPO_CASA'
    WHERE gpm.idmanzana='$idmanzana'");

    if (($query->num_rows > 0) && ($propiedad=='1') ) {
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, [
                'valor' => $row['valor'],
                'texto' => $row['texto'],
            ]);}
        $data['data'] = $dataList;
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

    $IdCliente = isset($_POST['idCliente']) ? $_POST['idCliente'] : Null;
    $IdCliente = trim($IdCliente);   

    $IdLote = isset($_POST['idLote']) ? $_POST['idLote'] : Null;
    $IdLote = trim($IdLote);   

    $Descripcion = "";

    $TipoComprobante = isset($_POST['tipoComprobante']) ? $_POST['tipoComprobante'] : Null;
    $TipoComprobante = trim($TipoComprobante);   

    $Serie = isset($_POST['serie']) ? $_POST['serie'] : Null;
    $Serie = trim($Serie); 
    
    $Numero = isset($_POST['numero']) ? $_POST['numero'] : Null;
    $Numero = trim($Numero); 
    
    $FechaVenta = isset($_POST['fechaVenta']) ? $_POST['fechaVenta'] : Null;
    $FechaVenta = trim($FechaVenta); 
    
    $TipoInmobiliario = isset($_POST['tipoInmobiliario']) ? $_POST['tipoInmobiliario'] : Null;
    $TipoInmobiliario = trim($TipoInmobiliario); 
    
    $TipoCasa = isset($_POST['tipoCasa']) ? $_POST['tipoCasa'] : Null;
    $TipoCasa = trim($TipoCasa); 
    
    $Condicion = isset($_POST['condicion']) ? $_POST['condicion'] : Null;
    $Condicion = trim($Condicion); 
    
    $TipoMoneda = isset($_POST['tipoMoneda']) ? $_POST['tipoMoneda'] : Null;
    $TipoMoneda = trim($TipoMoneda); 
    
    $DescuentoMonto = isset($_POST['descuentoMonto']) ? $_POST['descuentoMonto'] : Null;
    $DescuentoMonto = trim($DescuentoMonto); 
    
    $total = isset($_POST['total']) ? $_POST['total'] : Null;
    $Total = trim($total);
    $Total = str_replace(',', '', $Total);
    
    $totalNegociado = isset($_POST['totalNegociado']) ? $_POST['totalNegociado'] : Null;
    $totalNegociado = trim($totalNegociado);
    $totalNegociado = str_replace(',', '', $totalNegociado);
    
     $TipoCredito = isset($_POST['tipoCredito']) ? $_POST['tipoCredito'] : Null;
    $TipoCredito = trim($TipoCredito); 


    $cantidadLetra = isset($_POST['cantidadLetra']) ? $_POST['cantidadLetra'] : Null;
    $CantidadLetra = trim($cantidadLetra); 

    $TEA = isset($_POST['tea']) ? $_POST['tea'] : Null;
    $TEA = trim($TEA); 

    $PrimeraFechaPago = isset($_POST['primeraFechaPago']) ? $_POST['primeraFechaPago'] : Null;
    $PrimeraFechaPago = trim($PrimeraFechaPago); 

    $CoutaInicial = $_POST['cuotaInicial'];
    $CoutaInicial = str_replace(',', '', $CoutaInicial);

    $MontoCuotaInical = isset($_POST['montoCuotaInicial']) ? $_POST['montoCuotaInicial'] : Null;
    $MontoCuotaInical = trim($MontoCuotaInical); 
    $MontoCuotaInical = str_replace(',', '', $MontoCuotaInical);

     $IdReservacion = isset($_POST['idReservacion']) ? $_POST['idReservacion'] : Null;
    $IdReservacion = trim($IdReservacion); 

    $ValidUsuario = isset($_POST['ValidUsuario']) ? $_POST['ValidUsuario'] : Null;
    $ValidUsuario = trim($ValidUsuario); 
    
    $txtFechaEntregaCasa = isset($_POST['txtFechaEntregaCasa']) ? $_POST['txtFechaEntregaCasa'] : Null;
    $txtFechaEntregaCasa = trim($txtFechaEntregaCasa); 
    
    $cbxTipoVenta = isset($_POST['cbxTipoVenta']) ? $_POST['cbxTipoVenta'] : Null;
    $cbxTipoVenta = trim($cbxTipoVenta); 

    $nom_user = decrypt($ValidUsuario, "123");
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$nom_user'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $IdUser = $respuesta_idusu['id'];
    
    $cbxTipoCronograma = $_POST['cbxTipoCronograma'];
    
    $bxFiltroVendedor = $_POST['bxFiltroVendedor'];
    
    //CONSULTAR EXISTENCIA DE REGISTRO DE VENTA
    $consultar_venta = mysqli_query($conection, "SELECT id_venta FROM gp_venta WHERE id_cliente='$IdCliente' AND id_lote='$IdLote'");
    $respuesta_venta = mysqli_num_rows($consultar_venta);
    
    if($respuesta_venta==0){

        $query = mysqli_query($conection, "call pa_gp_venta_insertar(
        '$IdCliente',
        '$IdLote',
        'Registro Venda de Lote',
        '$TipoComprobante',
        '$Serie',
        '$Numero',
        '$FechaVenta',
        '$TipoInmobiliario',
        '$TipoCasa',
        '$Condicion',
        '$TipoMoneda',
        '$DescuentoMonto',
        '0',
        '0',
        '$totalNegociado',
        '$TipoCredito',
        '$CantidadLetra',
        '$TEA',
        '$PrimeraFechaPago',
        '$CoutaInicial',
        '$MontoCuotaInical',
        '$IdReservacion',
        '$IdUser',
        '$txtFechaEntregaCasa',
        '$bxFiltroVendedor',
        '$cbxTipoVenta',
        '$cbxTipoCronograma'
        )");
        
        if($cbxTipoVenta=="canje"){
            //ACTUALIZAR ESTADO DE LOTE - MOTIVO
            $actualizar_estadolote = mysqli_query($conection, "UPDATE gp_lote SET motivo='8' WHERE idlote='$IdLote'");
        }
        
        
    /*
        if($Condicion=="2"){
        
            $txtTEAr = 0;
            $txtCuotasr = 0;
            $txtPrecioVentar = 0;
            $txtCuotaInicialr = 0;
           
            $txtTEAr = $TEA;
            $txtCuotasr = $CantidadLetra;
            $txtPrecioVentar = str_replace(',', '',$total);
            $txtCuotaInicialr = $MontoCuotaInical;
            
            $valor_TEA = 0;
            $valor_TEM = 0;
            
            //Consultar ID VENTA
            $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_cliente='$IdCliente' AND id_lote='$IdLote'");
            $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
            $IDVENTA = $respuesta_idventa['id'];
            
            //CONVERTIR DATOS TEM
            $valor_TEA= $txtTEAr/100;
            $valor_TEM = pow(( 1 + $valor_TEA),(30/360)) - 1;
            
           
           //fechas de pago
           $fecha_pago_cuota = $PrimeraFechaPago;
           
            //CALCULO CUOTA MENSUAL
            //$capital_vivo = $txtPrecioVentar - $txtCuotaInicialr;
            $capital_vivo = $txtPrecioVentar;
            $valor_cuota = (($capital_vivo * ($valor_TEM * pow((1 + $valor_TEM),$txtCuotasr)))/((pow((1+$valor_TEM),$txtCuotasr))-1));
            $amortizacion = 0;
            
            //INSERTAR PAGOS REALIZADOS , INICIALMENTE
            
            $consultar_pagos = mysqli_query($conection, "SELECT fecha_pago as fecha, importe as monto, tipo_moneda as tipo_moneda, importe as importe, descripcion as descripcion, voucher as voucher, correlativo as correlativo FROM gp_pagos_venta WHERE id_lote='$IdLote' AND id_cliente='$IdCliente'");
            $contar_pagos = mysqli_num_rows($consultar_pagos);
            if($contar_pagos>0){
                
                for($i=1; $i<=$contar_pagos; $i++){
                    
                    $respuesta_pagos = mysqli_fetch_assoc($consultar_pagos);
                    $fecha_p = $respuesta_pagos['fecha'];
                    $monto_p = $respuesta_pagos['monto'];
                    $tipo_moneda = $respuesta_pagos['tipo_moneda'];
                    $importe = $respuesta_pagos['importe'];
                    $descripcion = $respuesta_pagos['descripcion'];
                    $voucher = $respuesta_pagos['voucher'];
                    $correlativo = $respuesta_pagos['correlativo'];
                    $contador_valor=0;
                    $capital = str_replace(',', '',$totalNegociado);
                    
                    $insertar_fila0 = mysqli_query($conection,"INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado, id_usuario_crea) VALUES
                    ('$IDVENTA','C.I.','$i','$fecha_p', '$monto_p', '0.00', '0.00', '$capital', '1', '$IdUser')");
                    
                    $contador_valor = $contador_valor+1;  
                    $capital = $capital - $monto_p;

                    $insertar_comprobantes= mysqli_query($conection,"INSERT INTO gp_comprobante_venta(id_venta, fecha_pago, tipo_moneda, importe, descripcion, voucher, correlativo, estado) VALUES
                    ('$IDVENTA','$fecha_p','$tipo_moneda','$importe', '$descripcion', '$voucher', '$correlativo',  '1')");

                }
                
                $c= $contador_valor;
            }else{
                $c= 0;
            }
            //ACTUALIZAR ESTADOS 
            $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_venta SET estado_venta='2' WHERE id_lote='$IdLote' AND id_cliente='$IdCliente'");

            //INSERTAR LETRAS/CUOTAS
            for($cont=1; $cont<=$txtCuotasr; $cont++){
                    $capital_inicial = $capital_vivo;
                    $cuota = $valor_cuota;
                    $intereses = $capital_vivo * $valor_TEM;
                    $amortizacion = $cuota - $intereses;
                    $capital_vivo = $capital_vivo - $amortizacion;
                    $capital_amortizado = $amortizacion;
                    $total_pagado = $cuota;
                    $fecha_pago_cuota = date("Y-m-d",strtotime($fecha_pago_cuota."+ 1 month"));
                    $c = $c + 1;
                     $insertar_fila0 = mysqli_query($conection,"INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado, id_usuario_crea) VALUES
                    ('$IDVENTA','$cont','$c','$fecha_pago_cuota', '$cuota', '$intereses', '$capital_amortizado', '$capital_vivo', '1', '$IdUser')");
     
            }
        }
    */

        if ($query) {
            
            if($TipoInmobiliario=="1"){
                $actualizar_lote = mysqli_query($conection, "UPDATE gp_lote SET estado='6' WHERE idlote='$IdLote'");
            }else{
                $actualizar_lote = mysqli_query($conection, "UPDATE gp_lote SET estado='5' WHERE idlote='$IdLote'");
            }

            $data['status'] = 'ok';
            $data['data'] = 'Se guardo con exito';
            $data['idventa']= encrypt($IDVENTA, "123");
            
        } else {
            if (!$query) {
                $data['dataDB'] = mysqli_error($conection);
            }
            $data['status'] = 'bad';
            $data['res'] = $IDVENTA.','.$fecha_pago_cuota.', '.$cuota.', '.$intereses.', '.$capital_amortizado.', '.$capital_vivo.','.$IdUser;
            $data['data'] = 'Ocurrio un problema al guardar el registro.';
        }
        
    }else{
        
        $data['status'] = 'error';
        $data['data'] = 'Ya existe un registro de venta relacionado con el cliente y lote seleccionados.';
        
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnFinalizarVenta'])) {

    $idRegistro = $_POST['idRegistro'];

    $query = mysqli_query($conection, "SELECT 
    condicion as condicion,
    cantidad_letra as cantidad_letra,
    total as total,
    monto_cuota_inicial as cuota_inicial,
    primera_fecha as primera_fecha,
    tna as tea,
    id_cliente as idcliente,
    id_lote as idlote
    FROM gp_venta 
    WHERE id_venta='$idRegistro'");
    $respuesta = mysqli_fetch_assoc($query);

    $condicion = $respuesta['condicion'];
    $cantidadLetra = $respuesta['cantidad_letra'];
    $total = $respuesta['total'];
    $cuota_inicial = $respuesta['cuota_inicial'];
    $primera_fecha = $respuesta['primera_fecha'];
    $tea = $respuesta['tea'];
    $IdCliente = $respuesta['idcliente'];
    $IdLote = $respuesta['idlote'];
    $totalNegociado = $respuesta['total'];

    //consultar tipo cronograma de la venta
    $consultar_tipocronograma = mysqli_query($conection, "SELECT tipo_cronograma as tipo_cronograma FROM gp_venta WHERE id_venta='$idRegistro' AND esta_borrado='0'");
    $respuesta_tipocronograma = mysqli_fetch_assoc($consultar_tipocronograma);
    $tipo_cronograma = $respuesta_tipocronograma['tipo_cronograma'];

    if($tipo_cronograma=="1"){

        if($condicion=="2"){
        
            $txtTEAr = 0;
            $txtCuotasr = 0;
            $txtPrecioVentar = 0;
            $txtCuotaInicialr = 0;
           
            $txtTEAr = $tea;
            $txtCuotasr = $cantidadLetra;
            $txtPrecioVentar = $total;
            $txtCuotaInicialr = $cuota_inicial;
            
            $valor_TEA = 0;
            $valor_TEM = 0;
            
            //Consultar ID VENTA
            $consultar_idventa = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_cliente='$IdCliente' AND id_lote='$IdLote'");
            $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
            $IDVENTA = $respuesta_idventa['id'];
            
            //CONVERTIR DATOS TEM
            $valor_TEA= $txtTEAr/100;
            $valor_TEM = pow(( 1 + $valor_TEA),(30/360)) - 1;
            
           
           //fechas de pago
           $fecha_pago_cuota = $primera_fecha;
           
            //CALCULO CUOTA MENSUAL
            $capital_vivo = $txtPrecioVentar - $txtCuotaInicialr;
            //$capital_vivo = $txtPrecioVentar;
            $valor_cuota = (($capital_vivo * ($valor_TEM * pow((1 + $valor_TEM),$txtCuotasr)))/((pow((1+$valor_TEM),$txtCuotasr))-1));
            $amortizacion = 0;
            
            //INSERTAR PAGOS REALIZADOS , INICIALMENTE
            
            $consultar_pagos = mysqli_query($conection, "SELECT fecha_pago as fecha, importe as monto, tipo_moneda as tipo_moneda, importe as importe, descripcion as descripcion, voucher as voucher, correlativo as correlativo, realizo_pago as realizo_pago FROM gp_pagos_venta WHERE id_lote='$IdLote' AND id_cliente='$IdCliente' AND esta_borrado='0'");
            $contar_pagos = mysqli_num_rows($consultar_pagos);
            if($contar_pagos>0){
                $contador_valor=0;
            
                $capital = str_replace(',', '',$totalNegociado);
                
                for($i=1; $i<=$contar_pagos; $i++){
                    
                    if($i>1){
                        $capital = $capital - $monto_p;
                    }
                    
                    $respuesta_pagos = mysqli_fetch_assoc($consultar_pagos);
                    $fecha_p = $respuesta_pagos['fecha'];
                    $monto_p = $respuesta_pagos['monto'];
                    $tipo_moneda = $respuesta_pagos['tipo_moneda'];
                    $importe = $respuesta_pagos['importe'];
                    $descripcion = $respuesta_pagos['descripcion'];
                    $voucher = $respuesta_pagos['voucher'];
                    $correlativo = $respuesta_pagos['correlativo'];
                    $realizo_pago = $respuesta_pagos['realizo_pago'];
                    
                    
                    if($realizo_pago=="2"){ //NO REALIZO EL PAGO
                    
                        $insertar_fila0 = mysqli_query($conection,"INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado, es_cuota_inicial, pago_cubierto, id_usuario_crea) VALUES
                        ('$IDVENTA','C.I.','$i','$fecha_p', '$monto_p', '0.00', '0.00', '$capital', '1', '1','1', '$IdUser')");
                        $contador_valor = $contador_valor+1;  
                        
                    }else{ //SI REALIZO EL PAGO
                    
                        $insertar_fila0 = mysqli_query($conection,"INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado, es_cuota_inicial, pago_cubierto, id_usuario_crea) VALUES
                        ('$IDVENTA','C.I.','$i','$fecha_p', '$monto_p', '0.00', '0.00', '$capital', '2', '1','1', '$IdUser')");
                        
                        $contador_valor = $contador_valor+1;  
                        $capital = $capital - $monto_p;
        
                        $insertar_comprobantes= mysqli_query($conection,"INSERT INTO gp_comprobante_venta(id_venta, fecha_pago, tipo_moneda, importe, descripcion, voucher, correlativo, estado) VALUES
                        ('$IDVENTA','$fecha_p','$tipo_moneda','$importe', '$descripcion', '$voucher', '$correlativo',  '1')");
        
                        $insertar_fila1 = mysqli_query($conection,"INSERT INTO gp_pagos_cabecera(id_venta, id_cronograma, sede, fecha_pago, moneda_pago, glosa, importe_pago, operacion, pagado, estado, medio_pago, tipo_comprobante, visto_bueno, agencia_bancaria, tipo_pago) VALUES
                        ('$IDVENTA','$i','00001','$fecha_p', '15381', 'COBRANZA AL CLIENTE','$monto_p', '15551', '$monto_p', '2', '15466', '15470','1', '14877','1')");
        
                        $consultar_idpago = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$IDVENTA' AND id_cronograma='$i'");
                        $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
                        $idpago = $respuesta_idpago['id'];
        
                       $insertar_fila2 = mysqli_query($conection,"INSERT INTO gp_pagos_detalle(id_venta, idpago, tipo_comprobante, monto_dolares, importe_pago, moneda_pago, pagado, medio_pago, agencia_bancaria, fecha_pago, debe_haber, estado, voucher) VALUES
                        ('$IDVENTA','$idpago','15470', '$monto_p', '$monto_p', '15381', '$monto_p', '15466', '14877', '$fecha_p', 'H', '1','$voucher')");
                        
                    }
    
                }
                
                $c= $contador_valor;
            }else{
                $c= 0;
            }
            //ACTUALIZAR ESTADOS 
            $actualizar_pagos = mysqli_query($conection, "UPDATE gp_pagos_venta SET estado_venta='2' WHERE id_lote='$IdLote' AND id_cliente='$IdCliente'");
    
            //INSERTAR LETRAS/CUOTAS
            for($cont=1; $cont<=$txtCuotasr; $cont++){
                    $capital_inicial = $capital_vivo;
                    $cuota = $valor_cuota;
                    $intereses = $capital_vivo * $valor_TEM;
                    $amortizacion = $cuota - $intereses;
                    $capital_vivo = $capital_vivo - $amortizacion;
                    $capital_amortizado = $amortizacion;
                    $total_pagado = $cuota;
                    $fecha_pago_cuota = date("Y-m-d",strtotime($fecha_pago_cuota."+ 1 month"));
                    $c = $c + 1;
                     $insertar_fila0 = mysqli_query($conection,"INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado, id_usuario_crea) VALUES
                    ('$IDVENTA','$cont','$c','$fecha_pago_cuota', '$cuota', '$intereses', '$capital_amortizado', '$capital_vivo', '1', '$IdUser')");
     
            }
        }

    }else{
        
        //consultar cronograma temporal
        $consultar_cronograma = mysqli_query($conection, "SELECT 
        item_letra as item_letra,
        correlativo as correlativo,
        fecha_vencimiento as fecha,
        monto_letra as letra,
        interes_amortizado as interes,
        capital_amortizado as amortizado,
        capital_vivo as capital,
        es_cuota_inicial as cuota_inicial,
        pago_cubierto as pago_cubierto
        FROM gp_cronograma_temporal
        WHERE id_lote='$IdLote'");
        $contar_cronograma = mysqli_num_rows($consultar_cronograma);
        
        for($n=1; $n<=$contar_cronograma; $n++){
        
            $respuesta_cronograma = mysqli_fetch_assoc($consultar_cronograma);
            $item_letra = $respuesta_cronograma['item_letra'];
            $correlativo = $respuesta_cronograma['correlativo'];
            $fecha = $respuesta_cronograma['fecha'];
            $letra = $respuesta_cronograma['letra'];
            $interes = $respuesta_cronograma['interes'];
            $amortizado = $respuesta_cronograma['amortizado'];
            $capital = $respuesta_cronograma['capital'];
            $cuota_inicial = $respuesta_cronograma['cuota_inicial'];
            $pago_cubierto = $respuesta_cronograma['pago_cubierto'];
            
            $es_cuota_inicial=0;
            if($item_letra == "C.I."){
                $es_cuota_inicial = 1;
            }else{
                $es_cuota_inicial = 0;
            }
            
            $insertar_fila0 = mysqli_query($conection,"INSERT INTO gp_cronograma(id_venta, item_letra, correlativo, fecha_vencimiento, monto_letra, interes_amortizado, capital_amortizado, capital_vivo, estado, es_cuota_inicial, pago_cubierto, id_usuario_crea) VALUES
            ('$idRegistro','$item_letra','$correlativo','$fecha', '$letra', '$interes', '$amortizado', '$capital', '$es_cuota_inicial', '$cuota_inicial','$pago_cubierto', '$IdUser')");
        
        }
        
        $consultar_pagos = mysqli_query($conection, "SELECT fecha_pago as fecha, importe as monto, tipo_moneda as tipo_moneda, importe as importe, descripcion as descripcion, voucher as voucher, correlativo as correlativo, realizo_pago as realizo_pago FROM gp_pagos_venta WHERE id_lote='$IdLote' AND id_cliente='$IdCliente' AND esta_borrado='0' ORDER BY id_pagos_venta ASC");
        $contar_pagos = mysqli_num_rows($consultar_pagos);
        if($contar_pagos>0){
            
            $capital = str_replace(',', '',$totalNegociado);
            
            for($i=1; $i<=$contar_pagos; $i++){
                
                if($i>1){
                    $capital = $capital - $monto_p;
                }
                
                $respuesta_pagos = mysqli_fetch_assoc($consultar_pagos);
                $fecha_p = $respuesta_pagos['fecha'];
                $monto_p = $respuesta_pagos['monto'];
                $tipo_moneda = $respuesta_pagos['tipo_moneda'];
                $importe = $respuesta_pagos['importe'];
                $descripcion = $respuesta_pagos['descripcion'];
                $voucher = $respuesta_pagos['voucher'];
                $correlativo = $respuesta_pagos['correlativo'];
                $realizo_pago = $respuesta_pagos['realizo_pago'];
                
                 if($realizo_pago=="2"){
                    $insertar_fila0 = mysqli_query($conection,"UPDATE gp_cronograma SET estado='1' WHERE id_venta='$idRegistro' AND correlativo='$i'");
                    
                }else{
                
                    $insertar_fila0 = mysqli_query($conection,"UPDATE gp_cronograma SET estado='2', pago_cubierto='1' WHERE id_venta='$idRegistro' AND correlativo='$i'");
                    
                    $contador_valor = $contador_valor+1;  
                    $capital = $capital - $monto_p;
    
                    $insertar_comprobantes= mysqli_query($conection,"INSERT INTO gp_comprobante_venta(id_venta, fecha_pago, tipo_moneda, importe, descripcion, voucher, correlativo, estado) VALUES
                    ('$idRegistro','$fecha_p','$tipo_moneda','$importe', '$descripcion', '$voucher', '$correlativo',  '1')");
    
                    $insertar_fila1 = mysqli_query($conection,"INSERT INTO gp_pagos_cabecera(id_venta, id_cronograma, sede, fecha_pago, moneda_pago, glosa, importe_pago, operacion, pagado, estado, medio_pago, tipo_comprobante, visto_bueno, agencia_bancaria, tipo_pago) VALUES
                    ('$idRegistro','$i','00001','$fecha_p', '15381', 'COBRANZA AL CLIENTE','$monto_p', '15551', '$monto_p', '2', '15466', '15470','1', '14877','1')");
    
                    $consultar_idpago = mysqli_query($conection, "SELECT idpago as id FROM gp_pagos_cabecera WHERE id_venta='$idRegistro' AND id_cronograma='$i'");
                    $respuesta_idpago = mysqli_fetch_assoc($consultar_idpago);
                    $idpago = $respuesta_idpago['id'];
    
                   $insertar_fila2 = mysqli_query($conection,"INSERT INTO gp_pagos_detalle(id_venta, idpago, tipo_comprobante, monto_dolares, importe_pago, moneda_pago, pagado, medio_pago, agencia_bancaria, fecha_pago, debe_haber, estado, voucher) VALUES
                    ('$idRegistro','$idpago','15470', '$monto_p', '$monto_p', '15381', '$monto_p', '15466', '14877', '$fecha_p', 'H', '1','$voucher')");
                    
                }
            
            }
        }
        
    }

    $query = mysqli_query($conection, "UPDATE gp_venta SET conformidad='1' WHERE id_venta='$idRegistro'");

    if ($query) {
        $data['status'] = 'ok';
        $data['valores'] = $respuesta;
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se guardo el registro';
    }
            

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************ACTUALIZAR REGISTRO VENTA******************* */
if (isset($_POST['ReturnActualizarVenta'])) {

    $IdVenta = $_POST['idVenta'];

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
    $Total = str_replace(',', '', $Total);
    
    $totalNegociado = isset($_POST['totalNegociado']) ? $_POST['totalNegociado'] : Null;
    $totalNegociado = trim($totalNegociado);
    $totalNegociado = str_replace(',', '', $totalNegociado);

    $TipoCredito = $_POST['tipoCredito'];
    $TipoCredito = !empty($TipoCredito) ? "'$TipoCredito'" : "NULL";

    $CantidadLetra = $_POST['cantidadLetra'];
    $CantidadLetra = !empty($CantidadLetra) ? "'$CantidadLetra'" : "NULL";

    $TEA = $_POST['tea'];
    $TEA = !empty($TEA) ? "'$TEA'" : "NULL";

    $PrimeraFechaPago = $_POST['primeraFechaPago'];
    $PrimeraFechaPago = !empty($PrimeraFechaPago) ? "'$PrimeraFechaPago'" : "NULL";

    $CoutaInicial = $_POST['cuotaInicial'];
    $CoutaInicial = str_replace(',', '', $CoutaInicial);

    $MontoCuotaInical = $_POST['montoCuotaInicial'];
    $MontoCuotaInical = !empty($MontoCuotaInical) ? "'$MontoCuotaInical'" : "0";
    $MontoCuotaInical = str_replace(',', '', $MontoCuotaInical);

    $DATO_MONTO_INICIAL = $_POST['txtMontoInicial'];
    $DATO_MONTO_INICIAL = str_replace(',', '', $DATO_MONTO_INICIAL);

    $IdReservacion = $_POST['idReservacion'];
    $IdReservacion = !empty($IdReservacion) ? "'$IdReservacion'" : "NULL";
    
    $cbxTipoCronograma = $_POST['cbxTipoCronograma'];
    
    $bxFiltroVendedor = $_POST['bxFiltroVendedor'];
    
    $txtFechaEntregaCasa = $_POST['txtFechaEntregaCasa'];
    
    $txtUsr = $_POST['txtUsr'];
    $txtUsr = decrypt($txtUsr, "123");
    
    $IdUser="";
    $consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtUsr'");
    $respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
    $IdUser=$respuesta_idusu['id'];

    $consultar_venta = mysqli_query($conection, "SELECT id_venta as id FROM gp_venta WHERE id_venta='$IdVenta' AND esta_borrado='0'");
    $respuesta = mysqli_num_rows($consultar_venta);
    
    
    if($DATO_MONTO_INICIAL>0){
        $CoutaInicial = "1";
    }else{
        $CoutaInicial = "0";
    }
    
    if($respuesta == 1){
        
        $respuesta_venta = mysqli_fetch_assoc($consultar_venta);
        $id_venta = $respuesta_venta['id'];

        $query = mysqli_query($conection, "UPDATE gp_venta
        SET 
        descripcion=$Descripcion,
        tipo_comprobante=$TipoComprobante,
        id_vendedor='$bxFiltroVendedor',
        fecha_venta=$FechaVenta,
        tipo_inmobiliaria=$TipoInmobiliario,
        tipo_casa=$TipoCasa,
        condicion=$Condicion,
        tipo_moneda=$TipoMoneda,
        dscto_monto=$DescuentoMonto,
        total=$totalNegociado,
        tipo_credito=$TipoCredito,
        cantidad_letra=$CantidadLetra,
        tna=$TEA,
        primera_fecha=$PrimeraFechaPago,
        tiene_cuota_inicial=$CoutaInicial,
        monto_cuota_inicial='$DATO_MONTO_INICIAL',
        id_reserva=$IdReservacion,
        id_usuario_actualiza='$IdUser', 
        actualizado=now(), 
        tipo_cronograma='$cbxTipoCronograma', 
        fecha_entrega_casa='$txtFechaEntregaCasa'
        
        WHERE id_venta='$IdVenta'");
    
        if ($query) {
            
            $consultar_reg_reservas = mysqli_query($conection,"SELECT id_reservacion as id FROM gp_reservacion WHERE id_cliente=$IdCliente AND id_lote=$IdLote AND esta_borrado='0'");
            $respuesta_reg_reservas = mysqli_num_rows($consultar_reg_reservas);
            
            if($respuesta_reg_reservas>0){
                $actualizar_reserva = mysqli_query($conection,"UPDATE gp_reservacion SET importe_precio='$totalNegociado', actualizado=now(), id_usuario_actualiza='$IdUser' WHERE id_cliente=$IdCliente AND id_lote=$IdLote AND esta_borrado='0'");
            }
            
            $data['status'] = 'ok';
            $data['data'] = 'Se actualizo con exito';
        } else {
            if (!$query) {
                $data['dataDB'] = mysqli_error($conection);
            }
            $data['status'] = 'bad';
            $data['data'] = 'Ocurrio un problema al guardar el registro.';
            
        }
    
    }else{
        
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo completar la operación.';
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
    $txtNombreArchivo = $_POST['txtNombreArchivo'];
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
       descripcion='$txtDescripcionAdjunto',
       nombre_archivo='$txtNombreArchivo'
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
    //$IdVenta = decrypt($IdVenta, "123");

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
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
    gpav.nombre_archivo as nom_archivo
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
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
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
    gpav.descripcion as descripcion,
    gpav.nombre_archivo as nom_archivo
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
    $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idManzana' AND estado NOT IN (5,6,7) AND bloqueo_estado NOT IN (7)");

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

if (isset($_POST['btnIrDetalleVenta'])) {

    $idRegistro = $_POST['idRegistro'];

    $query = mysqli_query($conection, "SELECT
    gpv.id_venta as id,
    gpv.id_cliente as idCliente,
    dc.tipodocumento as tipo_documento,
    dc.documento as documento,
    dc.apellido_paterno as apePaterno,
    dc.apellido_materno as apeMaterno,
    dc.nombres as nombres,
    dc.celular_1 as celular,
    gpv.id_lote as lote,
    gpm.idmanzana as manzana,
    gpz.idzona as zona,
    gpy.idproyecto as proyecto,
    gpl.area as area,
    gpl.tipo_moneda as lote_moneda,
    format(gpl.valor_sin_casa,2) as lote_sin_casa,
    format(gpl.valor_con_casa,2) as lote_con_casa,
    gpv.tipo_comprobante as tipo_comprobante,
    gpv.fecha_venta as fecha_venta,
    gpv.tipo_inmobiliaria as tipo_inmueble,
    gpv.tipo_casa as tipo_casa,
    gpv.fecha_entrega_casa as fecha_entrega_casa,
    gpv.tipo_venta as tipo_venta,
    gpv.tipo_moneda as venta_moneda,
    gpv.tipo_cambio as tipo_cambio,
    gpv.tipo_dscto as tipo_dscto,
    gpv.dscto_monto as dscto_monto,
    gpv.conformidad as conformidad,
    if(gpv.tipo_inmobiliaria='1', format(gpl.valor_con_casa,2), format(gpl.valor_sin_casa,2)) as precio_venta_inicial,
    if((select count(id_reservacion) from gp_reservacion where id_cliente=gpv.id_cliente AND id_lote=gpv.id_lote)>0, 
    (select format(importe_precio,2) from gp_reservacion where id_cliente=gpv.id_cliente AND id_lote=gpv.id_lote), 
    if(gpv.tipo_inmobiliaria!='1', format(gpl.valor_sin_casa,2), format(gpl.valor_con_casa,2))) as precio_negociado,
    
    if((select count(id_pagos_venta) from gp_pagos_venta where id_cliente=gpv.id_cliente AND id_lote=gpv.id_lote AND esta_borrado='0')>0,
    (select format(sum(importe),2) from gp_pagos_venta where id_cliente=gpv.id_cliente AND id_lote=gpv.id_lote AND esta_borrado='0'), '0.00') as monto_inicial_pagad,
    
    format(gpv.total - gpv.monto_cuota_inicial,2) as capital_vivo_inicial,
    
    gpv.condicion as tipo_pago,
    gpv.tipo_credito as tipo_credito,
    gpv.cantidad_letra as letras,
    gpv.tna as tea,
    gpv.primera_fecha as primera_fecha,
    gpv.tipo_cronograma as tipo_cronograma,
    gpv.id_vendedor as id_vendedor,
    format(gpv.monto_cuota_inicial,2) as inicial
    FROM gp_venta gpv
    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
    WHERE gpv.id_venta='$idRegistro' AND gpv.esta_borrado='0'");

    if($query->num_rows > 0){
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'Ocurri贸 un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT);
}

/**************************LISTA DE PAGOS RELACIONADOS A LA VENTA********************************** */
if (isset($_POST['btnPagosPreviosVenta'])) {

    $idLote = $_POST['idLote'];
    $documento = $_POST['documento'];
    $idreserva = $_POST['idreserva'];
    $idVenta = $_POST['idVenta'];

    $idCliente="";
    if(!empty($idVenta)){
        $consultar_id_lote = mysqli_query($conection, "SELECT id_lote as idlote, id_cliente as idcliente FROM gp_venta WHERE id_venta='$idVenta'");
        $respuesta_id_lote = mysqli_fetch_assoc($consultar_id_lote);
        $idLote = $respuesta_id_lote['idlote'];
        $idCliente = $respuesta_id_lote['idcliente'];
    }
    
    if(empty($idreserva) || $idreserva=="ninguno"){
        
        if(empty($idVenta)){
            $consulta_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$documento'");
            $respuesta_idcliente = mysqli_fetch_assoc($consulta_idcliente);
            $idCliente = $respuesta_idcliente['id'];
            
            //consultar lote en reserva
            $consultar_idlote = mysqli_query($conection, "SELECT id_lote as idlote FROM gp_pagos_venta WHERE id_cliente='$idCliente' GROUP BY id_lote");
            $respuesta_idlote = mysqli_fetch_assoc($consultar_idlote);
            $idLote = $respuesta_idlote['idlote'];
        }
        
        $dato=1;
        $query = mysqli_query($conection, "SELECT
        gppv.id_pagos_venta as id,
        if(gppv.categoria='1', 'PAGO RESERVA', 'PAGO VENTA') as categoria,
        gppv.fecha_pago as fecha_pago,
        cdx.texto1 as tipo_moneda,
        format(gppv.importe,2) as importe,
        gppv.voucher as voucher,
        concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as dato_lote,
        concat(SUBSTRING_INDEX(dc.nombres,' ',1),' ',dc.apellido_paterno) as dato_cliente,
        if(gppv.categoria='1', 'PAGORESERVA', 'PAGOINICIAL') as dato_categoria
        FROM gp_pagos_venta gppv
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gppv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN datos_cliente AS dc ON dc.id=gppv.id_cliente
        INNER JOIN configuracion_detalle As cdx ON cdx.idconfig_detalle=gppv.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
        WHERE gppv.estado='1' AND gppv.esta_borrado='0' AND gppv.id_lote='$idLote' AND gppv.id_cliente='$idCliente'");
  
    }else{
        $idreserva = decrypt($idreserva, "123");
        $consulta_idcliente = mysqli_query($conection, "SELECT id_cliente as cliente, id_lote as lote FROM gp_reservacion WHERE id_reservacion='$idreserva'");
        $respuesta_idcliente = mysqli_fetch_assoc($consulta_idcliente);
        $idCliente = $respuesta_idcliente['cliente'];
        $idLote = $respuesta_idcliente['lote'];
        $dato=2;
        $query = mysqli_query($conection, "SELECT
        gppv.id_pagos_venta as id,
        if(gppv.categoria='1', 'PAGO RESERVA', 'PAGO VENTA') as categoria,
        gppv.fecha_pago as fecha_pago,
        cdx.texto1 as tipo_moneda,
        format(gppv.importe,2) as importe,
        gppv.voucher as voucher,
        concat(SUBSTRING(gpm.nombre,9,2), '-',SUBSTRING(gpl.nombre,6,2)) as dato_lote,
        concat(SUBSTRING_INDEX(dc.nombres,' ',1),' ',dc.apellido_paterno) as dato_cliente,
        if(gppv.categoria='1', 'PAGORESERVA', 'PAGOINICIAL') as dato_categoria
        FROM gp_pagos_venta gppv
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gppv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN datos_cliente AS dc ON dc.id=gppv.id_cliente
        INNER JOIN configuracion_detalle As cdx ON cdx.idconfig_detalle=gppv.tipo_moneda AND cdx.codigo_tabla='_TIPO_MONEDA'
        WHERE gppv.estado='1' AND gppv.id_lote='$idLote' AND gppv.id_cliente='$idCliente' AND gppv.esta_borrado='0'");
    }
    
    $conformidad="";
    if(!empty($idVenta)){
        $consultar_conformidad = mysqli_query($conection, "SELECT conformidad as conformidad FROM gp_venta WHERE id_venta='$idVenta'");
        $respuesta_conformidad = mysqli_fetch_assoc($consultar_conformidad);
        $conformidad = $respuesta_conformidad['conformidad'];
    }else{
        $conformidad=0;
    }
    
    if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            
            array_push($dataList,[
                'id' => $row['id'],
                'categoria' => $row['categoria'],
                'fecha_pago' => $row['fecha_pago'],
                'tipo_moneda' => $row['tipo_moneda'],
                'importe' => $row['importe'],
                'voucher' =>$row['voucher'],
                'dato_lote' =>$row['dato_lote'],
                'dato_cliente' =>$row['dato_cliente'],
                'dato_categoria' =>$row['dato_categoria'],
                'URLvoucher' =>$RUTA_ARCHIVOS_ADJUNTOS_3.$row['voucher'],
                'dato' =>'voucher_'.$row['fecha_pago'],
                'conformidad' => $conformidad
            ]);
        }
        
        $data['status'] = 'ok';
        $data['data'] = $dataList;
        $data['valor'] = $dato.'-'.$idLote.'-'.$documento.'-'.$idreserva.'- '.$idCliente.'- '.$idVenta;
        $data['conformidad']= $conformidad;
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = $dataList;
        $data['valor'] = $dato.'-'.$idLote.'-'.$documento.'-'.$idreserva.'- '.$idCliente.'- '.$idVenta;
        $data['conformidad']= $conformidad;
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/***********************CONSULTAR ID RESERVA CON DOCUMENTO ********************** */
if (isset($_POST['btnConsultarIdReserva'])) {

    $documento = $_POST['documento'];

    //CONSULTA ID CLIENTE
    $consultar_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$documento'");
    $respuesta_idcliente = mysqli_fetch_assoc($consultar_idcliente);
    $idcliente = $respuesta_idcliente['id'];
    
    $consultar_cant_reservas = mysqli_query($conection, "SELECT id_reservacion FROM gp_reservacion WHERE id_cliente='$idcliente'");
    $respuesta_cant_reservas = mysqli_num_rows($consultar_cant_reservas);
    
    if($respuesta_cant_reservas>1){
        
        $data['status'] = 'muchos';
        $data['idReserva'] = $idreserva;
        
    }else{
        
        if($respuesta_cant_reservas==1){
    
            $respuesta_idreserva = mysqli_fetch_assoc($consultar_cant_reservas);
            $idreserva = $respuesta_idreserva['id_reservacion'];
    
            $data['status'] = 'ok';
            $data['idReserva'] = $idreserva;
        
        }else{
            
            $data['status'] = 'ninguno';
            $data['idReserva'] = $idreserva;
            
        }
        
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

if (isset($_POST['btnIrListaVentas'])) {

    $ValidUsuario = $_POST['ValidUsuario'];
    
    $data['status'] = 'ok';
    $data['url'] = $NAME_SERVER."views/M03_Ventas/M03SM02_Venta/M03SM02_Venta?Vsr=".$ValidUsuario;
        
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);    

}

if (isset($_POST['btnRegistrarPagosPrevios'])) {

    $cbxTipoMonedaPP = $_POST['cbxTipoMonedaPP'];
    $txtImportePP = $_POST['txtImportePP'];
    $cbxTipoPagoPP = $_POST['cbxTipoPagoPP'];
    $txtFechaPagoPP = $_POST['txtFechaPagoPP'];
    $txtDescripcionPP = $_POST['txtDescripcionPP'];
    $cbxLote = $_POST['cbxLote'];
    $cbxPagoRealizado = $_POST['cbxPagoRealizado'];
    $txtTipoCambioPP = $_POST['txtTipoCambioPP'];
    $txtImportePagoPP = $_POST['txtImportePagoPP'];
    $txtDocumentoCliente = $_POST['txtDocumentoCliente'];

    $consulta_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$txtDocumentoCliente'");
    $respuesta_idcliente = mysqli_fetch_assoc($consulta_idcliente);
    $idCliente = $respuesta_idcliente['id'];
    
    $consultar_correlativo = mysqli_query($conection, "SELECT max(correlativo) as conteo FROM gp_pagos_venta");
    $respuesta_correlativo = mysqli_fetch_assoc($consultar_correlativo);
    $correlativo = $respuesta_correlativo['conteo'];
    $correlativo = $correlativo + 1;
    
    $nombre_Adjunto = "pago-".$correlativo.".pdf";

    $query = mysqli_query($conection, "INSERT INTO gp_pagos_venta(id_lote, id_cliente, tipo_moneda, importe, fecha_pago, estado, categoria, voucher, correlativo, descripcion, realizo_pago, tipo_cambio, importe_pago) VALUES
    ('$cbxLote','$idCliente','$cbxTipoMonedaPP','$txtImportePP','$txtFechaPagoPP','1','$cbxTipoPagoPP','$nombre_Adjunto','$correlativo', '$txtDescripcionPP','$cbxPagoRealizado','$txtTipoCambioPP','$txtImportePagoPP')");

    if ($query) {
        $data['status'] = 'ok';
        $data['data'] = "Se registro el pago correctamente.";
        
        $consultar_total = mysqli_query($conection, "SELECT SUM(importe) as total FROM gp_pagos_venta WHERE id_lote='$cbxLote' AND id_cliente='$idCliente' AND esta_borrado='0'");
        $respuesta_total = mysqli_fetch_assoc($consultar_total);
        
        $total = $respuesta_total['total'];
        $data['total'] = $total;
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = "No se pudo completar el registro. Contactar con soporte.";
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnTotal'])) {
    
    $cbxLote = $_POST['idlote'];
    $txtDocumentoCliente = $_POST['documento'];
    $precioVenta = $_POST['precioVenta'];
    $precioVentar = $_POST['precioVenta'];
    $montoInicial = $_POST['montoInicial'];
    $montoDscto = $_POST['montoDscto'];

    $idReserva = $_POST['idReserva'];
    $precioVentar = str_replace(',', '',$precioVentar);

    $montoDscto2 = 0;
    $montoInicial2 = 0;

    if(!empty($precioVenta) && !empty($montoInicial) && !empty($montoDscto)){
        $precioVenta = str_replace(',', '',$precioVenta);
        $montoDscto2 = str_replace(',', '',$montoDscto);
        $montoInicial2 = str_replace(',', '',$montoInicial);
    }
    if(empty($txtDocumentoCliente) &&  empty($cbxLote) && !empty($idReserva)){
        
        $idReserva = decrypt($idReserva,"123");
        $consultar_datos_reserv = mysqli_query($conection, "SELECT id_lote as idlote, id_cliente as idcliente, importe_precio as precio FROM gp_reservacion WHERE id_reservacion='$idReserva'");
        $respuesta_datos_reserv = mysqli_fetch_assoc($consultar_datos_reserv);
        $cbxLote = $respuesta_datos_reserv['idlote'];
        $idCliente = $respuesta_datos_reserv['idcliente'];
        $precioVenta = $respuesta_datos_reserv['precio'];
        
    }else{
        
        $consulta_idcliente = mysqli_query($conection, "SELECT id as id FROM datos_cliente WHERE documento='$txtDocumentoCliente'");
        $respuesta_idcliente = mysqli_fetch_assoc($consulta_idcliente);
        $idCliente = $respuesta_idcliente['id'];
        
        //consultar lote en reserva
        $consultar_idlote = mysqli_query($conection, "SELECT id_lote as idlote FROM gp_reservacion WHERE id_cliente='$idCliente'");
        $respuesta_idlote = mysqli_fetch_assoc($consultar_idlote);
        $idLote = $respuesta_idlote['idlote'];
        
        if(empty($cbxLote)){
            $cbxLote = $idLote;
        }
    }
    
    $consultar_total = mysqli_query($conection, "SELECT format(SUM(importe),2) as total, SUM(importe) as total2 FROM gp_pagos_venta WHERE id_lote='$cbxLote' AND id_cliente='$idCliente' AND esta_borrado='0'");
    $conteo = mysqli_num_rows($consultar_total);

    $total_pagos = 0;
    $total_pagos2 = 0;

    if($conteo>0){
        $respuesta_total = mysqli_fetch_assoc($consultar_total);
        $total = $respuesta_total['total'];
        $total2 = $respuesta_total['total2'];
        $data['total'] = $total;
        $data['total2'] = $total2;
        
        $total_pagos = $total;
        $total_pagos2 = $total2;
        
    
        if($total_pagos2>0){
            $inicial = $total_pagos; 
            $inicial2 = $total_pagos2;
        }else{
            $inicial = $montoInicial; 
            $inicial2 = $montoInicial2;
        }

        $total = $precioVenta - ($inicial2 + $montoDscto2);

        $data['precioVenta'] = number_format($total, 2, '.', ',');
        $data['montoInicial'] = $inicial;
        $data['montoDscto'] = $montoDscto;
        $data['DATOOS'] = $txtDocumentoCliente."-".$cbxLote."-".$precioVenta."-".$montoInicial."-".$montoDscto;   

    }else{
        $data['DATOOS'] = $txtDocumentoCliente."-".$cbxLote;   
        $data['total'] = 0;

        if($total_pagos2>0){
            $inicial = $total_pagos; 
            $inicial2 = $total_pagos2;
        }else{
            $inicial = $montoInicial; 
            $inicial2 = $montoInicial2;
        }

        $total = $precioVentar - ($inicial2 + $montoDscto2);

        $data['precioVenta'] = number_format($total, 2, '.', ',');
        $data['montoInicial'] = $inicial;
        $data['montoDscto'] = $montoDscto;

    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnDetallePagosPrevios'])) {
    
    $idRegistro = $_POST['idRegistro'];

    $query = mysqli_query($conection, "SELECT 
    tipo_moneda as tipo_moneda,
    importe as importe,
    categoria as tipo_pago,
    fecha_pago as fecha_pago,
    descripcion as descripcion
    FROM gp_pagos_venta 
    WHERE id_pagos_venta='$idRegistro' AND esta_borrado='0'");
    
    if($query){
        $respuesta_total = mysqli_fetch_assoc($query);
        $data['status'] = "ok";
        $data['tipo_moneda'] = $respuesta_total['tipo_moneda'];
        $data['importe'] = $respuesta_total['importe'];
        $data['tipo_pago'] = $respuesta_total['tipo_pago'];
        $data['fecha_pago'] = $respuesta_total['fecha_pago'];
        $data['descripcion'] = $respuesta_total['descripcion'];
    }else{
        $data['status'] = "bad";
    }
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnEliminarPagosPrevios'])) {
    
    $idRegistro = $_POST['idRegistro'];

    $consultar_pago = mysqli_query($conection, "SELECT estado_venta as estado FROM gp_pagos_venta WHERE id_pagos_venta='$idRegistro'");
    $respuesta_pago = mysqli_fetch_assoc($consultar_pago);
    $id_estado = $respuesta_pago['estado'];
    if($id_estado=='1'){   

        $query = mysqli_query($conection, "UPDATE gp_pagos_venta SET esta_borrado='1' WHERE id_pagos_venta='$idRegistro'");
        
        if($query){
            $data['status'] = "ok";
        }else{
            $data['status'] = "bad";
            $data['data'] = "No se pudo eliminar el pago, intente nuevamente o contactar con soporte.";
        }

    }else{
        $data['status'] = "bad";
        $data['data'] = "No se pudo eliminar el pago, debido a que el registro del pago fue derivado a 'carga de comprobantes'. Para anular el registro revise las opciones en 'Clientes -> Cronograma de Pagos'.";
    }
    
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*******************LISTA DE DOCUMENTOS  ADJUNTO DE VENTA************************** */
if (isset($_POST['ReturnListaVerAdjuntos'])) {
    
    $IdVenta = $_POST['idVenta'];
    //$IdVenta = decrypt($IdVenta, "123");

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
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
    gpav.nombre_archivo as nom_archivo
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
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}







