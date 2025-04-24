<?php
   session_start();
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";
   $hora = date("H:i:s", time());
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');

   $data = array();
   $dataList = array();

if(isset($_POST['btnMostrarLeyenda'])){   
    

    $consulta = mysqli_query($conection, "SELECT
    codigo_item as id,
    nombre_corto as nombre,
    texto1 as color,
    texto3 as descripcion
    FROM configuracion_detalle
    WHERE codigo_tabla='_ESTADO_VALIDA_DEVOLUCION' AND estado='ACTI'
    ORDER BY texto2 ASC");

    $total =0;

    while($row = $consulta->fetch_assoc()) {

        $total = mysqli_num_rows($consulta);
        array_push($dataList,[
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'color' => $row['color'],
            'descripcion' => $row['descripcion']
        ]);  
    }

    $data['data'] = $dataList;
    $data['status'] = "ok";
    $data['total'] = $total;
             
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT); 
}


if (isset($_POST['btnValidarDevolucion'])) {

    $txtIDUSR = $_POST['txtIDUSR'];
    $txtIDUSR = decrypt($txtIDUSR, "123");
    
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtIDUSR'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $val_idusuario = $respuesta_idusuario['id'];
    
    $control = $fecha.' '.$hora;
    
    $idVenta = $_POST['idVenta'];
    
    $query = mysqli_query($conection, "UPDATE gp_venta SET estado_devolucion='3', id_usuario_actualiza_devolucion='$val_idusuario', actualiza_devolucion='$control'  WHERE estado_devolucion='2' AND id_venta='$idVenta'");

    if ($query) {
        $data['status'] = 'ok';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo completar la validaci��n. Intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnRevertirValidacionDev'])) {

    $txtIDUSR = $_POST['txtIDUSR'];
    $txtIDUSR = decrypt($txtIDUSR, "123");
    
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='10010101'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $val_idusuario = $respuesta_idusuario['id'];
    
    $control = $fecha.' '.$hora;
    
    $idVenta = $_POST['idVenta'];
    
    $query = mysqli_query($conection, "UPDATE gp_venta SET estado_devolucion='2', id_usuario_actualiza_devolucion='$val_idusuario', actualiza_devolucion='$control'  WHERE estado_devolucion='3' AND id_venta='$idVenta'");

    if ($query) {
        $data['status'] = 'ok';
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo revertir la validaci��n. Intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['btnGuardarAdenda'])) {

    $txtIDVENTA_ = $_POST['txtIDVENTA_'];
    $txtIDADENDA_ = $_POST['txtIDADENDA_'];
    $txtContrato = $_POST['txtContrato'];
    $txtNroAdenda = $_POST['txtNroAdenda'];
    $bxEstadoAdenda = $_POST['bxEstadoAdenda'];
    $bxTipoAdenda = $_POST['bxTipoAdenda'];
    $txtImporteSolicitado = $_POST['txtImporteSolicitado'];
    $txtFechaInicio = $_POST['txtFechaInicio'];
    $txtDuracion = $_POST['txtDuracion'];
    $txtFechaTermino = $_POST['txtFechaTermino'];
    $txtReferencia = $_POST['txtReferencia'];
    $txtJustificacion = $_POST['txtJustificacion'];
    $txtObservacion = $_POST['txtObservacion'];
    $fichero = $_POST['fichero'];
    $txtFechaRegDevolucion = $_POST['txtFechaRegDevolucion'];
    
    $txtIDUSR = $_POST['txtIDUSR'];
    $txtIDUSR = decrypt($txtIDUSR, "123");
    
    $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='10010101'");
    $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
    $val_idusuario = $respuesta_idusuario['id'];
    
    $control = $fecha.' '.$hora;

   
    //CONSULTAR SI EXISTEN MAS ADJUNTOS
    $consultar_documentos = mysqli_query($conection, "SELECT count(id_adenda) as total FROM gp_adenda WHERE id_venta='$txtIDVENTA_'");
    $respuesta_documentos = mysqli_fetch_assoc($consultar_documentos);
    $total = $respuesta_documentos['total'];
    
    $total = $total + 1;

    if(!empty($fichero)){
        $path = $fichero;
        $file = new SplFileInfo($path);
        $extension  = $file->getExtension();
        $desc_codigo="adenda-";
        $name_file="";       
        if(!empty($fichero)){
            $name_file = $desc_codigo.$txtIDVENTA_."-".$total.".".$extension;
        }
    }

    $consultar_adenda = mysqli_query($conection, "SELECT id_adenda FROM gp_adenda WHERE id_venta='$txtIDVENTA_' AND nro_adenda='$txtNroAdenda' AND esta_borrado='0'");
    $respuesta_adenda = mysqli_num_rows($consultar_adenda);

    if($respuesta_adenda<=0){

        $query = mysqli_query($conection, "INSERT INTO gp_adenda(id_venta, nro_contrato,nro_adenda,estado,tipo,importe_solicitado,fecha_inicio,fecha_termino,duracion,referencia,justificacion,observacion,nombre_adjunto, creado, 	id_usuario_crea) VALUES ('$txtIDVENTA_', '$txtContrato','$txtNroAdenda','$bxEstadoAdenda','$bxTipoAdenda','$txtImporteSolicitado','$txtFechaInicio','$txtFechaTermino','$txtDuracion','$txtReferencia','$txtJustificacion','$txtObservacion','$name_file','$control','$val_idusuario')");

        if($query){
        
            $obtener_idlote = mysqli_query($conection, "SELECT id_lote as lote from gp_venta where id_venta='$txtIDVENTA_'");
            $respuesta_idlote = mysqli_fetch_assoc($obtener_idlote);
            $valor_idlote = $respuesta_idlote['lote'];
            $actualizar_venta = mysqli_query($conection, "UPDATE gp_venta SET devolucion='1', estado_devolucion='2', registro_devolucion='$txtFechaRegDevolucion', id_usuario_actualiza_devolucion='$val_idusuario' WHERE id_venta='$txtIDVENTA_'");
            $actualizar_lote = mysqli_query($conection, "UPDATE gp_lote SET estado='1', id_usuario_actualiza='$val_idusuario', actualizado='$control' WHERE idlote='$valor_idlote'");
        }
        
        $data['status'] = 'ok';
        $data['data'] = "Se registro la adenda de forma correcta.";
        $data['idventa'] = $txtIDVENTA_;
        $data['name'] = $name_file;
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'La adenda ya se encuentra registrada.';
    }
        
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnListaAdendas'])) {

    $idVenta = $_POST['idventa'];
    $query = mysqli_query($conection, "SELECT 
    gpa.id_adenda as id,
    gpa.nro_adenda as nro_adenda,
    gpa.nombre_adjunto as contrato,
    cdx.nombre_corto as tipo,
    format(gpa.importe_solicitado,2) as importe_solicitado,
    gpa.fecha_inicio as fecha_inicio,
    gpa.duracion as duracion,
    gpa.fecha_termino as fecha_termino,
    concat(dc.apellido_paterno,'_',dc.apellido_paterno,'_',dc.nombres) as cliente
    FROM gp_adenda gpa
    INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpa.id_venta
    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_item=gpa.tipo AND cdx.codigo_tabla='_TIPO_ADENDA'
    INNER JOIN configuracion_detalle AS cddx ON cddx.codigo_item=gpa.estado AND cddx.codigo_tabla='_ESTADO_ADENDA'
    where gpa.id_venta='$idVenta' AND gpa.estado='1'");

 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {           

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'nro_adenda' => $row['nro_adenda'],
                'contrato' => $row['contrato'],
                'tipo' => $row['tipo'],
                'importe_solicitado' => $row['importe_solicitado'],
                'fecha_inicio' => $row['fecha_inicio'],
                'duracion' => $row['duracion'],
                'fecha_termino' => $row['fecha_termino'],
                'cliente' => $row['cliente']
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

if (isset($_POST['CargarDatosVentas'])) {

    $idVenta = $_POST['idVenta'];
    $query = mysqli_query($conection, "SELECT 
    gpv.id_venta as id, 
    concat(dc.apellido_paterno,' ',dc.apellido_paterno,' ',dc.nombres) as datos,
    gpv.fecha_venta as fecha,
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
    format(gpv.total,2) as total,
    format((SELECT SUM(importe_pago) FROM gp_pagos_cabecera WHERE id_venta=gpv.id_venta),2) as total_pagado,
    gpv.cantidad_letra as letras,
    date_format(gpv.registro_devolucion, '%Y-%m-%d') as fec_devolucion
    FROM gp_venta gpv
    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    where gpv.id_venta='$idVenta'");

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

if (isset($_POST['btnEditarAdenda'])) {

    $idadenda = $_POST['idadenda'];
    $query = mysqli_query($conection, "SELECT 
    id_adenda as id,
    id_venta as id_venta,
    nro_adenda as nro_adenda,
    estado as estado,
    tipo as tipo,
    importe_solicitado as importe_solicitado,
    fecha_inicio as fecha_inicio,
    fecha_termino as fecha_termino,
    duracion as duracion,
    referencia as referencia,
    justificacion as justificacion,
    observacion as observacion
    FROM gp_adenda
    where id_adenda='$idadenda'");

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

if (isset($_POST['btnEliminarAdenda'])) {

    $idadenda = $_POST['idadenda'];
    $txtIDVENTA_ = $_POST['txtIDVENTA_'];
    
    $query = mysqli_query($conection, "UPDATE
    gp_adenda SET
    estado='0'
    where id_adenda='$idadenda'");

    if ($query) {
        
        $data['status'] = 'ok';
        $data['data'] = 'La adenda fue eliminada';
        $data['idVenta'] = $txtIDVENTA_;
        
    } else {
        $data['status'] = 'bad';
        $data['data'] = 'No se pudo completar la opereracion. Intente nuevamente.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnAprobarAdenda'])) {

    $idadenda = $_POST['idadenda'];
    $query = mysqli_query($conection, "UPDATE
    gp_adenda
    SET estado='2'
    where id_adenda='$idadenda'");
    if(!empty($idadenda)){
        $consultar_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_adenda WHERE id_adenda='$idadenda'");
        $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
        $id_venta = $respuesta_idventa['id_venta'];

        $consultar_lote = mysqli_query($conection, "SELECT id_lote as id FROM gp_venta WHERE id_venta='$id_venta'");
        $respuesta_lote = mysqli_fetch_assoc($consultar_lote);
        $id_lote = $respuesta_lote['id'];

        $actualizar_venta = mysqli_query($conection, "UPDATE gp_venta SET esta_borrado='1' WHERE id_venta='$id_venta'");

        $reestablecer_lote = mysqli_query($conection,"UPDATE gp_lote SET estado='1' WHERE idlote='$id_lote'");
    }
    if ($consultar_idventa->num_rows > 0) {
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

if (isset($_POST['btnRechazarAdenda'])) {

    $idadenda = $_POST['idadenda'];
    $query = mysqli_query($conection, "UPDATE
    gp_adenda
    SET estado='2'
    where id_adenda='$idadenda'");
    if(!empty($idadenda)){
        $consultar_idventa = mysqli_query($conection, "SELECT id_venta FROM gp_adenda WHERE id_adenda='$idadenda'");
        $respuesta_idventa = mysqli_fetch_assoc($consultar_idventa);
        $id_venta = $respuesta_idventa['id_venta'];

        $consultar_lote = mysqli_query($conection, "SELECT id_lote as id FROM gp_venta WHERE id_venta='$id_venta'");
        $respuesta_lote = mysqli_fetch_assoc($consultar_lote);
        $id_lote = $respuesta_lote['id'];

        $actualizar_venta = mysqli_query($conection, "UPDATE gp_venta SET esta_borrado='1' WHERE id_venta='$id_venta'");

        $reestablecer_lote = mysqli_query($conection,"UPDATE gp_lote SET estado='1' WHERE idlote='$id_lote'");
    }
    if ($consultar_idventa->num_rows > 0) {
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


/**************************MOSTRAR DOCUMENTO CLIENTE******************* */
if (isset($_POST['btnMostrarDocumento'])) {

    $idRegistro= $_POST['idRegistro'];

    $query = mysqli_query($conection, "SELECT 
    nombre_adjunto as documento
    FROM gp_adenda
    WHERE id_adenda=$idRegistro");

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


if (isset($_POST['btnActualizarAdenda'])) {

    $txtIDVENTA_ = $_POST['txtIDVENTA_'];
    $txtIDADENDA_ = $_POST['txtIDADENDA_'];
    $txtContrato = $_POST['txtContrato'];
    $txtNroAdenda = $_POST['txtNroAdenda'];
    $bxEstadoAdenda = $_POST['bxEstadoAdenda'];
    $bxTipoAdenda = $_POST['bxTipoAdenda'];
    $txtImporteSolicitado = $_POST['txtImporteSolicitado'];
    $txtFechaInicio = $_POST['txtFechaInicio'];
    $txtDuracion = $_POST['txtDuracion'];
    $txtFechaTermino = $_POST['txtFechaTermino'];
    $txtReferencia = $_POST['txtReferencia'];
    $txtJustificacion = $_POST['txtJustificacion'];
    $txtObservacion = $_POST['txtObservacion'];
    $fichero = $_POST['fichero'];

        $name_file = "ninguno";
        $query_adjunto = "";
        if(!empty($fichero)){
    
            //CONSULTAR SI EXISTEN MAS ADJUNTOS
            $consultar_documentos = mysqli_query($conection, "SELECT count(id_adenda) as total FROM gp_adenda WHERE id_venta='$txtIDVENTA_'");
            $respuesta_documentos = mysqli_fetch_assoc($consultar_documentos);
            $total = $respuesta_documentos['total'];
                                                
            $total = $total + 1;

            if(!empty($fichero)){
                $path = $fichero;
                $file = new SplFileInfo($path);
                $extension  = $file->getExtension();
                $desc_codigo="adenda-";
                $name_file="";       
               if(!empty($fichero)){
                    $name_file = $desc_codigo.$txtIDVENTA_."-".$total.".".$extension;
                }
            }
            
            $query_adjunto = ",nombre_adjunto='$name_file'";
              
        }
    
        $consultar_adenda = mysqli_query($conection, "SELECT id_adenda FROM gp_adenda WHERE id_adenda='$txtIDADENDA_' AND esta_borrado='0'");
        $respuesta_adenda = mysqli_num_rows($consultar_adenda);
    
        if($respuesta_adenda>0){
    
            $query = mysqli_query($conection, "UPDATE gp_adenda SET 
            nro_contrato='$txtContrato',
            nro_adenda='$txtNroAdenda',
            tipo='$bxTipoAdenda',
            importe_solicitado='$txtImporteSolicitado',
            fecha_inicio='$txtFechaInicio',
            fecha_termino='$txtFechaTermino',
            duracion='$txtDuracion',
            referencia='$txtReferencia',
            justificacion='$txtJustificacion',
            observacion='$txtObservacion'
            $query_adjunto WHERE id_adenda='$txtIDADENDA_'");
        
            if($query){
                
                //CONSULTAR SI EXISTEN MAS ADJUNTOS
                $consultar_reg = mysqli_query($conection, "SELECT count(id_adenda) as total FROM gp_adenda WHERE id_venta='$txtIDVENTA_'");
                $respuesta_reg = mysqli_fetch_assoc($consultar_reg);
                $tot_reg = $respuesta_reg['total'];
                
                if($tot_reg == 0){
                    //CONSULTAR ESTADO ACTUAL DEL LOTE
                   /* $obtener_idlote = mysqli_query($conection, "SELECT 
                    gpl.estado as estado,
                    gpl.idlote as id
                    FROM gp_venta gpv  
                    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
                    WHERE gpv.id_venta='$txtIDVENTA_'");
                    $respuesta_idlote = mysqli_fetch_assoc($obtener_idlote);
                    $valor_estado = $respuesta_idlote['estado'];
                    $valor_idlote = $respuesta_idlote['id'];
                    
                    if($valor_estado == '1'){
                        
                        $actualizar_venta = mysqli_query($conection, "UPDATE gp_venta SET devolucion='0' WHERE id_venta='$txtIDVENTA_'");
                        $actualizar_lote = mysqli_query($conection, "UPDATE gp_lote SET estado='6' WHERE idlote='$valor_idlote'");
                        
                    }*/
                
                }
            }
            
            $data['status'] = 'ok';
            $data['data'] = "Se ha actualizado la adenda de forma correcta.";
            $data['idventa'] = $txtIDVENTA_;
            $data['name'] = $name_file;
            
        }
    
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}



if (isset($_POST['ReturnListaNC'])) {

    $idVenta = intval($_POST['idVenta']);

    $consultar_idlote = mysqli_query($conection, "SELECT id_lote as idlote FROM gp_venta WHERE id_venta='$idVenta'");

    if($consultar_idlote->num_rows>0){

        $row = mysqli_fetch_assoc($consultar_idlote);
        $id_lote=$row['idlote'];

        $query = mysqli_query($conection, "SELECT 
        fcab.idcomprobante_cab as valor,
        concat(fcab.NUM_SERIE_CPE,'-',TRIM(LEADING '0' FROM fcab.NUM_CORRE_CPE),' - ',dc.apellido_paterno,' ',dc.apellido_materno,' ',SUBSTRING_INDEX(dc.nombres,' ',1),' - ',if(gcro.item_letra='C.I.',concat(gcro.item_letra,' Nro. ',gcro.correlativo),concat('Lt. ',gcro.item_letra))) as texto
        FROM fac_comprobante_cab fcab
        INNER JOIN gp_pagos_detalle_comprobante AS gpdc ON gpdc.serie=fcab.NUM_SERIE_CPE AND gpdc.numero=fcab.NUM_CORRE_CPE AND gpdc.tipo_comprobante_sunat=fcab.COD_TIP_CPE
        INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gpdc.idpago_detalle
        INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppd.id_venta
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        INNER JOIN gp_cronograma AS gcro ON gcro.id_venta=gpv.id_venta
        WHERE fcab.COD_TIP_CPE='07' AND gpv.id_venta='$idVenta' AND gpdc.esta_borrado='0' AND gpdc.asignado_devolucion='0'
        GROUP BY gpdc.serie, gpdc.numero, gpdc.tipo_comprobante_sunat");

        

        if ($query->num_rows > 0) {

            array_push($dataList, [
                'valor' => '',
                'texto' => 'Seleccionar',
            ]);

            while ($row = $query->fetch_assoc()) {
                array_push($dataList, [
                    'valor' => $row['valor'],
                    'texto' => $row['texto'],
                ]);
            }

            $data['status'] = "ok";
            $data['data'] = $dataList;

        } else {

            array_push($dataList, [
                'valor' => '',
                'texto' => 'No se encontraron NC',
            ]);

            $data['status'] = "bad";
            $data['data'] = $dataList;
           
        }
    }else {

        $data['status'] = "bad";
        $data['data'] = $dataList;
       
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


if (isset($_POST['btnVerReporteNC'])) {

    $idfac_cab = $_POST['idfac_cab'];

    $consultar_doc_impreso = mysqli_query($conection, "SELECT
    gpdc.comprobante_url as ruta
    FROM fac_comprobante_cab fcab
    INNER JOIN gp_pagos_detalle_comprobante AS gpdc ON gpdc.serie=fcab.NUM_SERIE_CPE AND gpdc.numero=fcab.NUM_CORRE_CPE AND gpdc.tipo_comprobante_sunat=fcab.COD_TIP_CPE
    WHERE gpdc.tipo_comprobante_sunat='07' AND gpdc.esta_borrado='0' AND fcab.idcomprobante_cab='$idfac_cab'
    GROUP BY gpdc.serie, gpdc.numero, gpdc.tipo_comprobante_sunat");

    if($consultar_doc_impreso->num_rows > 0){   

        $row = mysqli_fetch_assoc($consultar_doc_impreso);
        $ruta = $row['ruta'];
        
        $data['status'] = 'ok';
        $data['ruta'] = $ruta;
        
    }else{
        $data['status'] = 'bad';
    }
    
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

if (isset($_POST['ReturnListarNCDevolucion'])) {

    $idVenta = $_POST['idventa'];
    $query = mysqli_query($conection, "SELECT 
    gcdv.idcomprobante_devolucion as id,
    concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
    if(gcro.item_letra='C.I.',concat(gcro.item_letra,' Nro. ',gcro.correlativo),concat('Lt. ',gcro.item_letra)) as letra,
    fcab.NUM_SERIE_CPE as serie,
    fcab.NUM_CORRE_CPE as numero,
    gpdc.comprobante_url as ruta,
    format(fcab.MNT_TOT,2) as devuelto,
    fcab.COD_MND as moneda,
    fcab.NUM_SERIE_CPE_REF as serie_ref,
    fcab.NUM_CORRE_CPE_REF as numero_ref,
    cdx.nombre_corto as tipo_doc_ref,
    gpdcc.comprobante_url as ruta_ref
    FROM gp_comprobante_devolucion gcdv
    INNER JOIN fac_comprobante_cab AS fcab ON fcab.idcomprobante_cab=gcdv.idcomprobante_cab
    INNER JOIN gp_pagos_detalle_comprobante AS gpdc ON gpdc.serie=fcab.NUM_SERIE_CPE AND gpdc.numero=fcab.NUM_CORRE_CPE AND gpdc.tipo_comprobante_sunat=fcab.COD_TIP_CPE
    INNER JOIN gp_pagos_detalle_comprobante AS gpdcc ON gpdcc.serie=fcab.NUM_SERIE_CPE_REF AND gpdcc.numero=fcab.NUM_CORRE_CPE_REF AND gpdcc.tipo_comprobante_sunat=fcab.COD_TIP_DOC_REF
    INNER JOIN gp_pagos_detalle AS gppd ON gppd.idpago_detalle=gpdc.idpago_detalle
    INNER JOIN gp_venta AS gpv ON gpv.id_venta=gppd.id_venta
    INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
    INNER JOIN gp_cronograma AS gcro ON gcro.id_venta=gpv.id_venta
    INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_tabla='_TIPO_COMPROBANTE_SUNAT' AND cdx.codigo_sunat=fcab.COD_TIP_DOC_REF
    WHERE fcab.COD_TIP_CPE='07' AND gcdv.estado='1' AND gpv.id_venta='$idVenta'
    GROUP BY gpdc.serie, gpdc.numero, gpdc.tipo_comprobante_sunat");

 
    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {           

            //Campos para llenar Tabla
            array_push($dataList,[
                'id' => $row['id'],
                'lote' => $row['lote'],
                'letra' => $row['letra'],
                'serie' => $row['serie'],
                'numero' => $row['numero'],
                'ruta' => $row['ruta'],
                'devuelto' => $row['devuelto'],
                'moneda' => $row['moneda'],
                'serie_ref' => $row['serie_ref'],
                'numero_ref' => $row['numero_ref'],
                'tipo_doc_ref' => $row['tipo_doc_ref'],
                'ruta_ref' => $row['ruta_ref']
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

if (isset($_POST['btnAgregarNC'])) {

    $cbxNotasCreditoList = $_POST['cbxNotasCreditoList'];

    $txtIDUSR = $_POST['txtIDUSR'];

    if(!empty($txtIDUSR)){
        $txtIDUSR = decrypt($txtIDUSR, "123");    
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtIDUSR'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $val_idusuario = $respuesta_idusuario['id'];
    }else{
        $val_idusuario = '1'; 
    }

    $control = $fecha.' '.$hora;

    $consultar_doc_nc = mysqli_query($conection, "SELECT
    fcab.idcomprobante_cab as id
    FROM fac_comprobante_cab fcab
    WHERE fcab.idcomprobante_cab='$cbxNotasCreditoList'");

    if($consultar_doc_nc->num_rows > 0){   

        $insertar_registro = mysqli_query($conection, "INSERT INTO gp_comprobante_devolucion(idcomprobante_cab, control_usuario) VALUES ('$cbxNotasCreditoList','$val_idusuario')");
        if($insertar_registro){

            $actualizar_estado_dev = mysqli_query($conection, "UPDATE gp_pagos_detalle_comprobante gpdc
            INNER JOIN fac_comprobante_cab AS fcab ON fcab.NUM_SERIE_CPE=gpdc.serie AND fcab.NUM_CORRE_CPE=gpdc.numero AND fcab.COD_TIP_CPE=gpdc.tipo_comprobante_sunat
            SET gpdc.asignado_devolucion='1'
            WHERE fcab.idcomprobante_cab='$cbxNotasCreditoList'");

            if($actualizar_estado_dev ){
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

if (isset($_POST['btnEliminarNCDev'])) {

    $idregistro = $_POST['idregistro'];

    $txtIDUSR = $_POST['txtIDUSR'];

    if(!empty($txtIDUSR)){
        $txtIDUSR = decrypt($txtIDUSR, "123");    
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$txtIDUSR'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $val_idusuario = $respuesta_idusuario['id'];
    }else{
        $val_idusuario = '1'; 
    }

    $control = $fecha.' '.$hora;

    $consultar_doc_nc = mysqli_query($conection, "SELECT
    idcomprobante_devolucion as id
    FROM gp_comprobante_devolucion 
    WHERE idcomprobante_devolucion='$idregistro'");

    if($consultar_doc_nc->num_rows > 0){   

        $actualiza_registro = mysqli_query($conection, "UPDATE gp_comprobante_devolucion SET estado='0', actualiza_usuario='$val_idusuario', actualiza_registro='$control' WHERE idcomprobante_devolucion='$idregistro'");
        if($actualiza_registro){

            $actualizar_estado_dev = mysqli_query($conection, "UPDATE gp_pagos_detalle_comprobante gpdc
            INNER JOIN fac_comprobante_cab AS fcab ON fcab.NUM_SERIE_CPE=gpdc.serie AND fcab.NUM_CORRE_CPE=gpdc.numero AND fcab.COD_TIP_CPE=gpdc.tipo_comprobante_sunat
            INNER JOIN gp_comprobante_devolucion AS gpcd ON gpcd.idcomprobante_cab=fcab.idcomprobante_cab
            SET gpdc.asignado_devolucion='0'
            WHERE gpcd.idcomprobante_devolucion='$idregistro'");

            if($actualizar_estado_dev ){
                $data['status'] = 'ok';
            }else{
                $data['status'] = 'bad';
                $data['data'] = 'Error de actualizacion estado de devolucion.';
            }

        }else{
            $data['status'] = 'bad';
            $data['data'] = 'Error de actualizacion estado de NC.';
        }        
        
    }else{
        $data['status'] = 'bad';
        $data['data'] = 'Error al buscar registro NC.'.$idregistro;
    }
    
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}