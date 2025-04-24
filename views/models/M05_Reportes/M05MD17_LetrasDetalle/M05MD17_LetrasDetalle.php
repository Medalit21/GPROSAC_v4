<?php
    session_start();
    include_once "../../../../config/configuracion.php";
    include_once "../../../../config/conexion_2.php";
    include_once "../../../../config/codificar.php";
    $hora = date("H:i:s", time());;
    $fecha = date('Y-m-d'); 
    $mes = date('m');
    //$anio = date('Y');
    $data = array();
    $dataList = array();


    if (isset($_POST['btnInfoCronPagos'])) {

        //PARAMETROS
        $txtFiltroDocumentoCP = $_POST['txtFiltroDocumentoCP'];
        $bxFiltroProyectoCP = $_POST['bxFiltroProyectoCP'];
        $bxFiltroZonaCP = $_POST['bxFiltroZonaCP'];
        $bxFiltroManzanaCP = $_POST['bxFiltroManzanaCP'];
        $bxFiltroLoteCP = $_POST['bxFiltroLoteCP'];
        $bxFiltroEstadoCP = $_POST['bxFiltroEstadoCP'];

        $query_documento="";
        $query_proyecto="";
        $query_zona="";
        $query_manzana="";
        $query_lote="";
        $query_estado="";

        if(!empty($txtFiltroDocumentoCP)){
            $query_documento = "AND cli.documento='$txtFiltroDocumentoCP'";
        }

        if(!empty($bxFiltroProyectoCP)){
            $query_proyecto = "AND gpy.idproyecto='$bxFiltroProyectoCP'";
        }

        if(!empty($bxFiltroZonaCP)){
            $query_zona = "AND gpz.idzona='$bxFiltroZonaCP'";
        }

        if(!empty($bxFiltroManzanaCP)){
            $query_manzana = "AND gpm.idmanzana='$bxFiltroManzanaCP'";
        }

        if(!empty($bxFiltroLoteCP)){
            $query_lote = "AND gpl.idlote='$bxFiltroLoteCP'";
        }

        if(!empty($bxFiltroEstadoCP)){
            if($bxFiltroEstadoCP == '2'){
                $query_estado = "AND gpcp.estado='$bxFiltroEstadoCP' AND gpcp.pago_cubierto='2'";
            }else{
                if($bxFiltroEstadoCP == '4'){
                    $query_estado = "AND gpcp.estado='2' AND gpcp.pago_cubierto='1'";
                }else{
                    $query_estado = "AND gpcp.estado='$bxFiltroEstadoCP'";
                }
            }            
        }


        $consultar_total = mysqli_query($conection, "SELECT 
        gpcp.id as id,
        date_format(gpcp.fecha_vencimiento, '%d/%m/%Y') as fecha,
        CONCAT(dc.apellido_paterno,' ',dc.apellido_materno,' ',dc.nombres) as cliente,
        concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
        gpcp.item_letra as letra,
        format(gpcp.monto_letra,2) as monto,
        format(gpcp.interes_amortizado,2) as intereses,
        format(gpcp.capital_amortizado,2) as amortizacion,
        format(gpcp.capital_vivo,2) as capital_vivo,
        format(gpcp.monto_letra,2) as pagado,
        if(gpcp.pago_cubierto=1,'4',gpcp.estado) as estado,
        if(gpcp.pago_cubierto=1,'PAGO PARCIAL',cd.nombre_corto) as descEstado,
        if(gpcp.pago_cubierto=1,'#0033BA',cd.texto1) as color
        FROM gp_cronograma gpcp
        INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcp.estado AND cd.codigo_tabla='_ESTADO_EC'
        INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcp.id_venta
        INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
        WHERE gpcp.esta_borrado=0
        $query_documento
        $query_proyecto
        $query_zona
        $query_manzana
        $query_lote
        $query_estado
        ORDER BY dc.apellido_paterno ASC, gpcp.correlativo ASC, gpv.id_venta ASC");

        if ($consultar_total->num_rows > 0) {
            while ($row = $consultar_total->fetch_assoc()) {
                $data['status'] = 'ok';
                array_push($dataList, $row
                );}

            $data['data'] = $dataList;
        } else {
            $data['status'] = 'bad';
        }
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);

    }


    if (isset($_POST['btnConsultarCronogramaIndividual'])) {

        //PARAMETROS
        $Codigo = $_POST['Codigo'];

        $consultar_total = mysqli_query($conection, "SELECT 
        pcab.idpago as id,
        date_format(pcab.fecha_pago,'%d/%m/%Y') as fecha,
        cdx.nombre_corto as tipo_moneda,
        format(pcab.importe_pago,2) as importe,
        format(pcab.tipo_cambio,2) as tipo_cambio,
        format(pcab.pagado,2) as pagado,

        (SELECT if((gppd.importe_pago)>0, gppd.nro_operacion,'Ninguno') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd WHERE gppc.esta_borrado='0' AND gppc.idpago=gppd.idpago AND gppc.id_cronograma=gpc.correlativo AND gppc.id_venta=gpv.id_venta GROUP BY gppd.idpago) as nro_operacion,
        
        (SELECT if((gppd.importe_pago)>0, GROUP_CONCAT(DISTINCT gppdc.serie,'-',gppdc.numero),'Ninguno') FROM gp_pagos_cabecera gppc, gp_pagos_detalle gppd, gp_pagos_detalle_comprobante gppdc WHERE gppc.id_cronograma=gpc.correlativo AND gppc.id_venta=gpv.id_venta AND gppc.idpago=gppd.idpago AND gppd.idpago_detalle=gppdc.idpago_detalle AND gppd.esta_borrado='0' AND gppdc.esta_borrado='0') as boleta
        FROM gp_pagos_cabecera pcab
        INNER JOIN gp_venta AS gpv ON gpv.id_venta=pcab.id_venta
        INNER JOIN gp_cronograma AS gpc ON gpc.id_venta=pcab.id_venta AND gpc.correlativo=pcab.id_cronograma
        INNER JOIN configuracion_detalle AS cdx ON cdx.codigo_tabla='_TIPO_MONEDA' AND cdx.idconfig_detalle=pcab.moneda_pago
        WHERE gpc.id='$Codigo'
        ORDER BY pcab.fecha_pago ASC");

        if ($consultar_total->num_rows > 0) {
            while ($row = $consultar_total->fetch_assoc()) {
                $data['status'] = 'ok';
                array_push($dataList, $row
                );}

            $data['data'] = $dataList;
        } else {
            $data['status'] = 'bad';
        }
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);

    }
