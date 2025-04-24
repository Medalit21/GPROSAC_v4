<?php

    session_start();
    include_once "../../../../config/configuracion.php";
    include_once "../../../../config/conexion_2.php";
    include_once "../../../../config/codificar.php";
    $hora = date("H:i:s", time());;
    $fecha = date('Y-m-d');
    $fecha_hoy = date('Y-m-d');  

    $data = array();
    $dataList = array();
    
    if(isset($_POST['btnValidarFechasMes'])){
    
        $fecha = new DateTime();
        $fecha->modify('first day of this month');
        $primer_dia = $fecha->format('Y-m-d');
        
        $fecha = new DateTime();
        $fecha->modify('last day of this month');
        $ultimo_dia = $fecha->format('Y-m-d'); 
        
        $data['status'] = 'ok';
        $data['primero'] = $primer_dia;
        $data['ultimo'] = $ultimo_dia;
        
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
        
    }

    if(isset($_POST['btnListarTablaRecaudaciones'])){

    
        $txtDesdeFiltro = isset($_POST['txtDesdeFiltro']) ? $_POST['txtDesdeFiltro'] : Null;
        $txtDesdeFiltror = trim($txtDesdeFiltro);
        
        $txtHastaFiltro = isset($_POST['txtHastaFiltro']) ? $_POST['txtHastaFiltro'] : Null;
        $txtHastaFiltror = trim($txtHastaFiltro);
        
        $txtFiltroDocumento = isset($_POST['txtFiltroDocumento']) ? $_POST['txtFiltroDocumento'] : Null;
        $txtFiltroDocumentor = trim($txtFiltroDocumento);
        
        $__ID_USER = isset($_POST['__ID_USER']) ? $_POST['__ID_USER'] : Null;
        $__ID_USERr = trim($__ID_USER);
        
        $__ID_USERr = decrypt($__ID_USERr, "123");
        
        //CONSULTAR ID USUARIO
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USERr'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $idusuario = $respuesta_idusuario['id'];
        
        $query_documento = "";
        $query_fecha = "";
        
        if(!empty($txtFiltroDocumentor)){
            $query_documento = "AND temp.codigo_depo='$txtFiltroDocumentor'";
        }
        
        $anio = date('Y');
    
        if(!empty($txtDesdeFiltror) && !empty($txtHastaFiltror)){
            $query_fecha = "AND temp.fecha_emision BETWEEN '$txtDesdeFiltror' AND '$txtHastaFiltror'";
        }else{
            if(!empty($txtDesdeFiltror) && empty($txtHastaFiltror)){
                $query_fecha = "AND temp.fecha_emision='$txtDesdeFiltror'";
            }
        }
    
           $query = mysqli_query($conection,"SELECT 
            temp.idtemp_recauda as id,
            temp.codigo_depo as codigo_depo, 
            temp.nombre_depo as nombre_depo, 
            temp.info_retorno as info_retorno, 
            date_format(temp.fecha_emision, '%d/%m/%Y') as fecha_emision, 
            date_format(temp.fecha_vencimiento, '%d/%m/%Y') as fecha_vencimiento, 
            temp.monto_pagar as monto_pagar, 
            temp.mora as mora,
            temp.monto_min as monto_min,
            cdx.nombre_corto as tipo, 
            temp.documento_pago as documento_pago, 
            temp.nro_documento as nro_documento
            FROM temporal_recaudaciones temp
            INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_tabla='_TIPO_REGISTRO_RECAUDACION' AND cdx.texto1=tipo_registro
            WHERE temp.idusuario='$idusuario' AND temp.estado in ('1','2','3')
            $query_documento
            $query_fecha
            ORDER BY temp.fecha_emision, temp.nombre_depo ASC
            "); 
    
          
        if($query->num_rows > 0){

            $consulta = mysqli_query($conection,"SELECT             
            COUNT(temp.idtemp_recauda) as conteo,
            format(SUM(temp.monto_pagar), 2) as monto
            FROM temporal_recaudaciones temp
            WHERE temp.idusuario='$idusuario' AND temp.estado='1'
            $query_documento
            $query_fecha"); 
            $respuesta = mysqli_fetch_assoc($consulta);

            $total_registros = $respuesta['conteo'];
            $total_monto = $respuesta['monto'];
         
            while($row = $query->fetch_assoc()) {
                
                //Campos para llenar Tabla
                array_push($dataList,[
                    'id' => $row['id'],
                    'codigo_depo' => $row['codigo_depo'],
                    'nombre_depo' => eliminar_acentos($row['nombre_depo']),
                    'info_retorno' => $row['info_retorno'],
                    'fecha_emision' => $row['fecha_emision'],
                    'fecha_vencimiento' => $row['fecha_vencimiento'],
                    'monto_pagar' => $row['monto_pagar'],
                    'mora' => $row['mora'],
                    'monto_min' => $row['monto_min'],
                    'tipo' => $row['tipo'],
                    'documento_pago' => $row['documento_pago'],
                    'nro_documento' => $row['nro_documento'],
                ]);
            }
                
           $data['data'] = $dataList;
           $data['totalreg'] = $total_registros;
           $data['totalmonto'] = $total_monto;
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
    
    if(isset($_POST['btnCargarDatosTemporal'])){

    
        $txtFecIniREC = isset($_POST['txtFecIniREC']) ? $_POST['txtFecIniREC'] : Null;
        $txtFecIniRECr = trim($txtFecIniREC);
        
        $txtFecFinREC = isset($_POST['txtFecFinREC']) ? $_POST['txtFecFinREC'] : Null;
        $txtFecFinRECr = trim($txtFecFinREC);
        
        $txtDocumentoREC = isset($_POST['txtDocumentoREC']) ? $_POST['txtDocumentoREC'] : Null;
        $txtDocumentoRECr = trim($txtDocumentoREC);
        
        $__ID_USER = isset($_POST['__ID_USER']) ? $_POST['__ID_USER'] : Null;
        $__ID_USERr = trim($__ID_USER);
        
        $__ID_USERr = decrypt($__ID_USERr, "123");
        
        //CONSULTAR ID USUARIO
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USERr'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $idusuario = $respuesta_idusuario['id'];
        
        $query_documento = "";
        $query_fecha = "";
        
        if(!empty($txtDocumentoRECr)){
            $query_documento = "AND dc.documento='$txtDocumentoRECr'";
        }
        
        $anio = date('Y');
    
        if($txtFecIniRECr == '1' && !empty($txtFecFinRECr)){
            $query_fecha = "AND YEAR(gpcr.fecha_vencimiento)='$anio' AND MONTH(gpcr.fecha_vencimiento)>'1' AND MONTH(gpcr.fecha_vencimiento)<='$txtFecFinRECr'";
        }else{
            if($txtFecIniRECr == '2' && !empty($txtFecFinRECr)){
                $query_fecha = "AND YEAR(gpcr.fecha_vencimiento)='$anio' AND MONTH(gpcr.fecha_vencimiento)='$txtFecFinRECr'";
            }
        }
        
        
        //INSERTAR DATOS TEMPORAL
        $consultar = mysqli_query($conection,"SELECT 
            gpcr.id as id,
            dc.documento as documento,
            concat(dc.nombres,' ',dc.apellido_paterno,' ',dc.apellido_materno) as cliente,
            if(gpcr.item_letra='C.I.',concat('CI',gpcr.correlativo),if(gpcr.item_letra='CI',concat('CI',gpcr.correlativo),if(gpcr.item_letra='AMORTIZADO','CAM',if(gpcr.item_letra<10,concat('C0',gpcr.item_letra),concat('C',gpcr.item_letra))))) as codigo,
            gpcr.fecha_vencimiento as fecha,
            gpcr.monto_letra as monto,
            'A' as tipo_registro,
            '1' as estado
            FROM gp_cronograma gpcr
            INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcr.id_venta
            INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
            WHERE gpcr.esta_borrado='0'
            $query_documento
            $query_fecha"); 
            
            while($row = $consultar->fetch_assoc()) {
                
                //capturar datos
                $idcronograma = $row['id'];
                $documento = $row['documento'];
                $cliente = eliminar_acentos($row['cliente']);
                $codigo = $row['codigo'];
                $fecha = $row['fecha'];
                $monto = $row['monto'];
                $tipo_registro = $row['tipo_registro'];
                $estado = $row['estado'];
                
                //consultar si existe registro cronograma en temporal
                $consultar_temporal = mysqli_query($conection, "SELECT idcronograma FROM temporal_recaudaciones WHERE idcronograma='$idcronograma'");
                $respuesta_temporal = mysqli_num_rows($consultar_temporal);
                
                if($respuesta_temporal>0){
                     //LIMPIAR TABLA TEMPORAL
                    $eliminar_temporal = mysqli_query($conection, "DELETE FROM temporal_recaudaciones WHERE idusuario='$idusuario' AND idcronograma='$idcronograma'");
                }
                
                //CONSULTAR SI SE PAGO LA TOTALIDAD DE LA LETRA
                $consultar_letra = mysqli_query($conection, "SELECT 
                monto_letra as monto, 
                estado as estado, 
                pago_cubierto as pago_cubierto, 
                id_venta as id_venta,
                correlativo as correlativo
                FROM gp_cronograma 
                WHERE id='$idcronograma'");
                $respuesta_letra = mysqli_fetch_assoc($consultar_letra);
                
                $monto_letra = $respuesta_letra['monto'];
                $estado = $respuesta_letra['estado'];
                $pago_cubierto = $respuesta_letra['pago_cubierto'];
                $id_venta = $respuesta_letra['id_venta'];
                $correlativo = $respuesta_letra['correlativo'];
                
                if($estado=='2' && $pago_cubierto=='1'){
                    
                    //consultar total pagado
                    $consultar_pagado = mysqli_query($conection, "SELECT SUM(pagado) as total FROM gp_pagos_cabecera WHERE id_venta='$id_venta' AND id_cronograma='$correlativo'");
                    $respuesta_pagado = mysqli_fetch_assoc($consultar_pagado);
                    $pago_total = $respuesta_pagado['total'];
                    
                    $saldo = $monto_letra - $pago_total;
                    
                     $insertar = mysqli_query($conection,"INSERT INTO temporal_recaudaciones(idusuario,codigo_depo, nombre_depo, info_retorno, fecha_emision, fecha_vencimiento, monto_pagar, tipo_registro, documento_pago, nro_documento,estado,idcronograma) VALUES 
                            ('$idusuario','$documento','$cliente','$codigo','$fecha','$fecha','$saldo','$tipo_registro','$codigo','$documento','$estado','$idcronograma')");
                    
                }else{
                    
                    if($estado=='3' || $estado=='1'){
                
                         $insertar = mysqli_query($conection,"INSERT INTO temporal_recaudaciones(idusuario,codigo_depo, nombre_depo, info_retorno, fecha_emision, fecha_vencimiento, monto_pagar, tipo_registro, documento_pago, nro_documento,estado,idcronograma) VALUES 
                            ('$idusuario','$documento','$cliente','$codigo','$fecha','$fecha','$monto','$tipo_registro','$codigo','$documento','$estado','$idcronograma')");
                    
                    }
                }
                
            }
                
           $data['status'] = 'ok';
           $data['data'] = 'El proceso fue completado.';
            header('Content-type: text/javascript');
            echo json_encode($data,JSON_PRETTY_PRINT) ;
    
       
    }
    
    if(isset($_POST['btnLimpiarTemporal'])){

        
        $__ID_USER = isset($_POST['__ID_USER']) ? $_POST['__ID_USER'] : Null;
        $__ID_USERr = trim($__ID_USER);
        
        $__ID_USERr = decrypt($__ID_USERr, "123");
        
        //CONSULTAR ID USUARIO
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USERr'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $idusuario = $respuesta_idusuario['id'];
        
       
        //LIMPIAR TABLA TEMPORAL
        $eliminar_temporal = mysqli_query($conection, "DELETE FROM temporal_recaudaciones WHERE idusuario='$idusuario'");
            
                
        $data['status'] = 'ok';
        $data['data'] = 'Se limpiaron correctamente los registros anteriormente generados. Puede proceder a generar nuevas consultas.';
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    
       
    }
    
    if(isset($_POST['btnEliminarRegistro'])){

    
        $idRegistro = isset($_POST['idRegistro']) ? $_POST['idRegistro'] : Null;
        $idRegistror = trim($idRegistro);
        
        //LIMPIAR TABLA TEMPORAL
        $eliminar_temporal = mysqli_query($conection, "DELETE FROM temporal_recaudaciones WHERE idtemp_recauda='$idRegistror'");
        
        $data['status'] = 'ok';
        $data['data'] = 'Se ha eliminado el registro seleccionado.';
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    
       
    }
    
    if(isset($_POST['btnVerTipoReg'])){

    
        $idRegistro = isset($_POST['idRegistro']) ? $_POST['idRegistro'] : Null;
        $idRegistror = trim($idRegistro);
        
        //LIMPIAR TABLA TEMPORAL
        $consultar_tiporeg = mysqli_query($conection, "SELECT tipo_registro, idtemp_recauda FROM temporal_recaudaciones WHERE idtemp_recauda='$idRegistror'");
        $consultar_tiporeg = mysqli_fetch_assoc($consultar_tiporeg);
        $tipo_registro = $consultar_tiporeg['tipo_registro'];
        $idtemp = $consultar_tiporeg['idtemp_recauda'];
        
        $data['status'] = 'ok';
        $data['tipo_registro'] = $tipo_registro;
        $data['id'] = $idtemp;
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    
       
    }
    
     if(isset($_POST['btnModificarTipoReg'])){

    
        $_ID_TEMP_RECAUDA = isset($_POST['_ID_TEMP_RECAUDA']) ? $_POST['_ID_TEMP_RECAUDA'] : Null;
        $_ID_TEMP_RECAUDAr = trim($_ID_TEMP_RECAUDA);
        
        $cbxTipoRegistro = isset($_POST['cbxTipoRegistro']) ? $_POST['cbxTipoRegistro'] : Null;
        $cbxTipoRegistror = trim($cbxTipoRegistro);
        
        //LIMPIAR TABLA TEMPORAL
        $actualizar_temporal = mysqli_query($conection, "UPDATE temporal_recaudaciones SET tipo_registro='$cbxTipoRegistror' WHERE idtemp_recauda='$_ID_TEMP_RECAUDAr'");
        
        $data['status'] = 'ok';
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    
       
    }
    
    
    if(isset($_POST['btnModificarTodosTipoReg'])){

    
        $__ID_USER = isset($_POST['__ID_USER']) ? $_POST['__ID_USER'] : Null;
        $__ID_USERr = trim($__ID_USER);
        
        $__ID_USERr = decrypt($__ID_USERr, "123");
        
        //CONSULTAR ID USUARIO
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USERr'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $idusuario = $respuesta_idusuario['id'];
        
        $cbxFiltroTipoRegistro = isset($_POST['cbxFiltroTipoRegistro']) ? $_POST['cbxFiltroTipoRegistro'] : Null;
        $cbxFiltroTipoRegistror = trim($cbxFiltroTipoRegistro);
        
        //LIMPIAR TABLA TEMPORAL
        $actualizar_temporal = mysqli_query($conection, "UPDATE temporal_recaudaciones SET tipo_registro='$cbxFiltroTipoRegistror' WHERE idusuario='$idusuario'");
        
        $data['status'] = 'ok';
        $data['data'] = 'Se modificaron todos los registros en el campo Tipo Registro.';
        header('Content-type: text/javascript');
        echo json_encode($data,JSON_PRETTY_PRINT) ;
    
       
    }




    if(isset($_POST['btnValidarFechas'])){
        
        $fecha = new DateTime();
        $fecha->modify('first day of this month');
        $primer_dia = $fecha->format('Y-m-d');
        
       
        $ultimo_dia = date('m'); 
        
        $data['status'] = 'ok';
        $data['primero'] = $primer_dia;
        $data['ultimo'] = $ultimo_dia;
        
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
        
    }


    if(isset($_POST['btnGenerarTxt'])){
        
        $txtCuentaAfiliadoREC = $_POST['txtCuentaAfiliadoREC'];
        $txtNombreEmpresaREC = $_POST['txtNombreEmpresaREC'];
        $txtTotalRegistrosREC = $_POST['txtTotalRegistrosREC'];
        $txtMontoTotalREC = $_POST['txtMontoTotalREC'];
        $txtTipoArchivoREC = $_POST['txtTipoArchivoREC'];
        $txtCodigoServicioREC = $_POST['txtCodigoServicioREC'];

        $txtFecIniREC = isset($_POST['txtFecIniREC']) ? $_POST['txtFecIniREC'] : Null;
        $txtFecIniRECr = trim($txtFecIniREC);
        
        $txtFecFinREC = isset($_POST['txtFecFinREC']) ? $_POST['txtFecFinREC'] : Null;
        $txtFecFinRECr = trim($txtFecFinREC);
        
        $txtDocumentoREC = isset($_POST['txtDocumentoREC']) ? $_POST['txtDocumentoREC'] : Null;
        $txtDocumentoRECr = trim($txtDocumentoREC);
        
        $__ID_USER = isset($_POST['__ID_USER']) ? $_POST['__ID_USER'] : Null;
        $__ID_USERr = trim($__ID_USER);
        
        $__ID_USERr = decrypt($__ID_USERr, "123");
        
        //CONSULTAR ID USUARIO
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USERr'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $idusuario = $respuesta_idusuario['id'];
        
        
        $query_documento = "";
        $query_fecha = "";
        
        if(!empty($txtDocumentoRECr)){
            $query_documento = "AND dc.documento='$txtDocumentoRECr'";
        }

        $contar_cuenta = strlen($txtCuentaAfiliadoREC);

        $exist = file_exists("CREP.txt");
        if ($exist)
        {
            $borrado = unlink("CREP.txt");
        }

        if($contar_cuenta==16){
            
            //CONSULTAR MONEDA DE CUENTA
            $consultar_mod = mysqli_query($conection, "SELECT texto1 as codigo FROM configuracion_detalle WHERE codigo_tabla='_NRO_CUENTA_BANCO' AND estado='ACTI' AND nombre_corto='$txtCuentaAfiliadoREC'");
            $respuesta_mod = mysqli_fetch_assoc($consultar_mod);
            $codigo_moneda = $respuesta_mod['codigo'];
            $tip_moneda = "-";
            if($codigo_moneda=="PEN"){
                $tip_moneda = '0';
            }else{
                $tip_moneda = '1';
            }

            $parte_1_cuenta = substr($txtCuentaAfiliadoREC, 0, 3); //codigo sucursal
            $parte_2_cuenta = substr($txtCuentaAfiliadoREC, 4, 7);

            $file = fopen('CREP.txt','a+');

            $fecha = date('Ymd'); 
            $txtMontoTotalREC = str_replace(',','',$txtMontoTotalREC);

            $espacio = str_repeat(' ', 32);
            
            //Cantidad total de Registros enviados
            $cantidad = $txtTotalRegistrosREC;
            $length = 9;
            $cantidad_reg_env = substr(str_repeat(0, $length).$cantidad, - $length);
            
            //Monto Total Enviado
            $monto = $txtMontoTotalREC;
            $monto = str_replace ( ".", '', $monto);
            $length_2 = 15;
            $monto_tot_env = substr(str_repeat(0, $length_2).$monto, - $length_2);
            
            //Filler (Campo Libre)
            $espacio_libre = "";
            $length_3 = 157;
            $filler = substr(str_repeat(' ', $length_3).$espacio_libre, - $length_3);
            
            $cabecera = 'CC'.$parte_1_cuenta.$tip_moneda.$parte_2_cuenta.'C'.$txtNombreEmpresaREC.$espacio.$fecha.$cantidad_reg_env.$monto_tot_env.$txtTipoArchivoREC.$txtCodigoServicioREC.$filler;
            fwrite($file, $cabecera);
            
        
               $query = mysqli_query($conection,"SELECT 
                temp.idtemp_recauda as id,
                temp.codigo_depo as codigo_depo, 
                LTRIM(RTRIM(temp.nombre_depo)) as nombre_depo, 
                temp.info_retorno as info_retorno, 
                temp.fecha_emision as fecha_emision, 
                temp.fecha_vencimiento as fecha_vencimiento, 
                temp.monto_pagar as monto_pagar, 
                temp.mora as mora,
                temp.monto_min as monto_min,
                temp.tipo_registro as tipo, 
                temp.documento_pago as documento_pago, 
                temp.nro_documento as nro_documento
                FROM temporal_recaudaciones temp
                WHERE temp.idusuario='$idusuario' AND temp.estado='1'
                ORDER BY temp.fecha_emision, temp.nombre_depo ASC
                "); 
     
            while($row = $query->fetch_assoc()) {

                $id = $row['id'];
                $documento = $row['codigo_depo'];
                $datos = $row['nombre_depo'];
                $letra = $row['info_retorno'];
                $fecha = $row['fecha_emision'];
                $monto = $row['monto_pagar'];
                $mora = $row['mora'];
                $monto_min = $row['monto_min'];
                $valor = "0.00";
                $tipo = $row['tipo'];

                $newDate = date("Ymd", strtotime($fecha));
                $newMonto= str_replace(',','',$monto);
                $newMonto= str_replace('.','',$newMonto);

                $espacio2 = str_repeat(' ', 12);
                $espacio3 = str_repeat(' ', 27);
                $espacio4 = str_repeat(' ', 16);
                
                //Codigo de Identificacion del Depositante
                $codigo = $documento;
                $length_4 = 14;
                $documento = substr(str_repeat(0, $length_4).$codigo, - $length_4);
                
                //Nombre del Depositante
                $nombreCli = $datos;
                $cantidad_caracter = mb_strlen($nombreCli);
                $length_5 = 40 - $cantidad_caracter;
                if($length_5<0){
                    $nomcortada= mb_substr($nombreCli, 0, 40, "UTF-8");
                }else{
                    $nomcortada = $nombreCli;
                }
                $datos = $nomcortada.str_repeat(' ', $length_5);
                
                //Informacion de Retorno
                $informacion = $letra;
                $cantidad_caracterr = mb_strlen($informacion);
                $length_6 = 30 - $cantidad_caracterr;
                $letras = $informacion.str_repeat(' ', $length_6);
                
                //Monto del pago
                $montoPago = $newMonto;
                $length_7 = 15;
                $newMonto = substr(str_repeat(0, $length_7).$montoPago, - $length_7);
                
                //Monto de Mora
                if($mora=="0.00"){
                   $mora = 0;
                }
                $montoMora = $mora;
                $length_8 = 15;
                $mora = substr(str_repeat(0, $length_8).$montoMora, - $length_8);
                
                
                //Monto del pago
                $datoLetra = $letra;
                $length_9 = 20;
                $letraPago = substr(str_repeat(' ', $length_9).$datoLetra, - $length_9);
                
                
                //Documento
                $datoDoc = $documento;
                $length_10 = 16;
                $documentoIde = substr(str_repeat(0, $length_10).$datoDoc, - $length_10);
                
                //Filler (Campo Libre)
                $espacio_libre = "";
                $length_11 = 61;
                $fillerNew = substr(str_repeat(' ', $length_11).$espacio_libre, - $length_11);
                
                
                //Monto Minimo
                if($monto_min=="0.00"){
                   $monto_min = 0;
                }
                $montoMin = $monto_min;
                $length_12 = 9;
                $monto_min = substr(str_repeat(0, $length_12).$montoMin, - $length_12);
                
                
                $contenido = 'DD'.$parte_1_cuenta.$tip_moneda.$parte_2_cuenta.$documento.$datos.$letras.$newDate.$newDate.$newMonto.$mora.$monto_min.$tipo.$letraPago.$documentoIde.$fillerNew;
                fwrite($file, "\n");    
                fwrite($file, $contenido);
            }

            $data['status'] = 'ok';
            $data['data'] = 'archivo txt generado';

        }else{
            $data['status'] = 'bad';
            $data['data'] = 'Ingresar una un nro de cuenta de afiliado valido. Ejm: 191-2666620-1-63 (Longitud: 16)';

        }
        
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
        
    }
    
    
    
    if(isset($_POST['btnGenerarTxt2'])){
        
        $txtCuentaAfiliadoREC = $_POST['txtCuentaAfiliadoREC'];
        $txtNombreEmpresaREC = $_POST['txtNombreEmpresaREC'];
        $txtTotalRegistrosREC = $_POST['txtTotalRegistrosREC'];
        $txtMontoTotalREC = $_POST['txtMontoTotalREC'];
        $txtTipoArchivoREC = $_POST['txtTipoArchivoREC'];
        $txtCodigoServicioREC = $_POST['txtCodigoServicioREC'];

        $txtFecIniREC = isset($_POST['txtFecIniREC']) ? $_POST['txtFecIniREC'] : Null;
        $txtFecIniRECr = trim($txtFecIniREC);
        
        $txtFecFinREC = isset($_POST['txtFecFinREC']) ? $_POST['txtFecFinREC'] : Null;
        $txtFecFinRECr = trim($txtFecFinREC);
        
        $txtDocumentoREC = isset($_POST['txtDocumentoREC']) ? $_POST['txtDocumentoREC'] : Null;
        $txtDocumentoRECr = trim($txtDocumentoREC);
        
        $__ID_USER = isset($_POST['__ID_USER']) ? $_POST['__ID_USER'] : Null;
        $__ID_USERr = trim($__ID_USER);
        
        $__ID_USERr = decrypt($__ID_USERr, "123");
        
        //CONSULTAR ID USUARIO
        $consultar_idusuario = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE usuario='$__ID_USERr'");
        $respuesta_idusuario = mysqli_fetch_assoc($consultar_idusuario);
        $idusuario = $respuesta_idusuario['id'];
        
        
        $query_documento = "";
        $query_fecha = "";
        
        if(!empty($txtDocumentoRECr)){
            $query_documento = "AND dc.documento='$txtDocumentoRECr'";
        }

        $contar_cuenta = strlen($txtCuentaAfiliadoREC);

        $exist = file_exists("CREP.txt");
        if ($exist)
        {
            $borrado = unlink("CREP.txt");
        }

        if($contar_cuenta==16){
            
            //CONSULTAR MONEDA DE CUENTA
            $consultar_mod = mysqli_query($conection, "SELECT texto1 as codigo FROM configuracion_detalle WHERE codigo_tabla='_NRO_CUENTA_BANCO' AND estado='ACTI' AND nombre_corto='$txtCuentaAfiliadoREC'");
            $respuesta_mod = mysqli_fetch_assoc($consultar_mod);
            $codigo_moneda = $respuesta_mod['codigo'];
            $tip_moneda = "-";
            if($codigo_moneda=="PEN"){
                $tip_moneda = '0';
            }else{
                $tip_moneda = '1';
            }

            $parte_1_cuenta = substr($txtCuentaAfiliadoREC, 0, 3); //codigo sucursal
            $parte_2_cuenta = substr($txtCuentaAfiliadoREC, 4, 7);

            $file = fopen('CREP.txt','a+');

            $fecha = date('Ymd'); 
            $txtMontoTotalREC = str_replace(',','',$txtMontoTotalREC);

            $espacio = str_repeat(' ', 32);
            
            //Cantidad total de Registros enviados
            $cantidad = $txtTotalRegistrosREC;
            $length = 9;
            $cantidad_reg_env = substr(str_repeat(0, $length).$cantidad, - $length);
            
            //Monto Total Enviado
            $monto = $txtMontoTotalREC;
            $length_2 = 15;
            $monto_tot_env = substr(str_repeat(0, $length_2).$monto, - $length_2);
            
            //Filler (Campo Libre)
            $espacio_libre = "";
            $length_3 = 157;
            $filler = substr(str_repeat(' ', $length_3).$espacio_libre, - $length_3);
            
            $cabecera = 'CC'.'|'.$parte_1_cuenta.'|'.$tip_moneda.'|'.$parte_2_cuenta.'|'.'C'.'|'.$txtNombreEmpresaREC.'|'.$espacio.'|'.$fecha.'|'.$cantidad_reg_env.'|'.$monto_tot_env.'|'.$txtTipoArchivoREC.'|'.$txtCodigoServicioREC.'|'.$filler;
            fwrite($file, $cabecera);
            
        
               $query = mysqli_query($conection,"SELECT 
                temp.idtemp_recauda as id,
                temp.codigo_depo as codigo_depo, 
                LTRIM(RTRIM(temp.nombre_depo)) as nombre_depo, 
                temp.info_retorno as info_retorno, 
                temp.fecha_emision as fecha_emision, 
                temp.fecha_vencimiento as fecha_vencimiento, 
                temp.monto_pagar as monto_pagar, 
                temp.mora as mora,
                temp.monto_min as monto_min,
                temp.tipo_registro as tipo, 
                temp.documento_pago as documento_pago, 
                temp.nro_documento as nro_documento
                FROM temporal_recaudaciones temp
                WHERE temp.idusuario='$idusuario' AND temp.estado='1'
                ORDER BY temp.fecha_emision, temp.nombre_depo ASC
                "); 
     
            while($row = $query->fetch_assoc()) {

                $id = $row['id'];
                $documento = $row['codigo_depo'];
                $datos = $row['nombre_depo'];
                $letra = $row['info_retorno'];
                $fecha = $row['fecha_emision'];
                $monto = $row['monto_pagar'];
                $mora = $row['mora'];
                $monto_min = $row['monto_min'];
                $valor = "0.00";
                $tipo = $row['tipo'];

                $newDate = date("Ymd", strtotime($fecha));
                $newMonto= str_replace(',','',$monto);
                $newMonto= str_replace('.','',$newMonto);

                $espacio2 = str_repeat(' ', 12);
                $espacio3 = str_repeat(' ', 27);
                $espacio4 = str_repeat(' ', 16);
                
                //Codigo de Identificacion del Depositante
                $codigo = $documento;
                $length_4 = 14;
                $documento = substr(str_repeat(0, $length_4).$codigo, - $length_4);
                
                //Nombre del Depositante
                $nombreCli = $datos;
                $cantidad_caracter = mb_strlen($nombreCli);
                $length_5 = 40 - $cantidad_caracter;
                if($length_5<0){
                    $nomcortada= mb_substr($nombreCli, 0, 40, "UTF-8");
                }else{
                    $nomcortada = $nombreCli;
                }
                $datos = $nomcortada.str_repeat(' ', $length_5);
                
                //Informacion de Retorno
                $informacion = $letra;
                $cantidad_caracterr = mb_strlen($informacion);
                $length_6 = 30 - $cantidad_caracterr;
                $letras = $informacion.str_repeat(' ', $length_6);
                
                //Monto del pago
                $montoPago = $newMonto;
                $length_7 = 15;
                $newMonto = substr(str_repeat(0, $length_7).$montoPago, - $length_7);
                
                //Monto de Mora
                if($mora=="0.00"){
                   $mora = 0;
                }
                $montoMora = $mora;
                $length_8 = 15;
                $mora = substr(str_repeat(0, $length_8).$montoMora, - $length_8);
                
                
                //Monto del pago
                $datoLetra = $letra;
                $length_9 = 20;
                $letraPago = substr(str_repeat(' ', $length_9).$datoLetra, - $length_9);
                
                
                //Documento
                $datoDoc = $documento;
                $length_10 = 16;
                $documentoIde = substr(str_repeat(0, $length_10).$datoDoc, - $length_10);
                
                //Filler (Campo Libre)
                $espacio_libre = "";
                $length_11 = 61;
                $fillerNew = substr(str_repeat(' ', $length_11).$espacio_libre, - $length_11);
                
                
                //Monto Minimo
                if($monto_min=="0.00"){
                   $monto_min = 0;
                }
                $montoMin = $monto_min;
                $length_12 = 9;
                $monto_min = substr(str_repeat(0, $length_12).$montoMin, - $length_12);
                
                
                $contenido = 'DD'.'|'.$parte_1_cuenta.'|'.$tip_moneda.'|'.$parte_2_cuenta.'|'.$documento.'|'.$datos.'|'.$letras.'|'.$newDate.'|'.$newDate.'|'.$newMonto.'|'.$mora.'|'.$monto_min.'|'.$tipo.'|'.$letraPago.'|'.$documentoIde.'|'.$fillerNew;
                fwrite($file, "\n");    
                fwrite($file, $contenido);
            }

            $data['status'] = 'ok';
            $data['data'] = 'archivo txt generado';

        }else{
            $data['status'] = 'bad';
            $data['data'] = 'Ingresar una un nro de cuenta de afiliado valido. Ejm: 191-2666620-1-63 (Longitud: 16)';

        }
        
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);
        
    }

?>
