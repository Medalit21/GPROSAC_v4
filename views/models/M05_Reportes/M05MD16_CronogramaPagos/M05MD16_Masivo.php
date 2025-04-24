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
    $dataListDet = array();
    

    if (isset($_POST['btnIrCronograma'])) {

        $iduser = $_POST['iduser'];

        $data['status'] = 'ok';
        $data['valor'] = $iduser;
        $data['ruta'] = $NAME_SERVER."views/M02_Clientes/M02SM02_CronogramaPagos/M02SM02_CronogramaPagos?Vsr=".$iduser;
        
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);  

    }


    if (isset($_POST['btnConsultarTotalesGenerales'])) {

        //CONSULTA DE VALORES TOTALES
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
            $query_estado = "AND gpv.cancelado='$bxFiltroEstadoCP'";
        }

        $consultar_total = mysqli_query($conection, "SELECT
        COUNT(gpv.id_venta) AS conteo,
        format(SUM(gpv.total),2) AS total_venta,
        format(SUM((select SUM(gpc.monto_letra) from gp_cronograma gpc where gpc.id_venta=gpv.id_venta)-(gpv.total)),2) AS intereses,
        format(SUM((select SUM(gpc.monto_letra) from gp_cronograma gpc where gpc.id_venta=gpv.id_venta)),2) AS total_financiado,
        format(SUM((select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta)),2) AS total_pagado,
        format(SUM((select SUM(gcr.monto_letra) from gp_cronograma gcr where gcr.id_venta=gpv.id_venta)-(select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta)),2) AS total_pendiente
        FROM gp_venta gpv
        INNER JOIN datos_cliente AS cli ON cli.id=gpv.id_cliente
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
        WHERE gpv.estado='1' AND gpv.conformidad='1'
        $query_documento
        $query_proyecto
        $query_zona
        $query_manzana
        $query_lote
        $query_estado");

        if($consultar_total){

            $row = mysqli_fetch_assoc($consultar_total);

            $data['status'] = 'ok';
            $data['conteo'] = $row['conteo'];
            $data['total_venta'] = $row['total_venta'];
            $data['intereses'] = $row['intereses'];
            $data['total_financiado'] = $row['total_financiado'];
            $data['total_pagado'] = $row['total_pagado'];
            $data['total_pendiente'] = $row['total_pendiente'];

        }else{
            $data['status'] = 'bad';
            $data['valor'] = 'No se pudo completar la operación';
        }

        
        
        header('Content-type: text/javascript');
        echo json_encode($data, JSON_PRETTY_PRINT);  

    }


    
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
            $query_estado = "AND gpv.cancelado='$bxFiltroEstadoCP'";
        }


        $consultar_total = mysqli_query($conection, "SELECT
        gpv.id_venta AS id,
        gpv.fecha_venta AS fecha,
        CONCAT(cli.apellido_paterno,' ',cli.apellido_materno,' ',cli.nombres) as cliente,
        concat(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) as lote,
        format((gpv.total),2) AS total_venta,
        format(((select SUM(gpc.monto_letra) from gp_cronograma gpc where gpc.id_venta=gpv.id_venta)-(gpv.total)),2) AS intereses,
        format(((select SUM(gpc.monto_letra) from gp_cronograma gpc where gpc.id_venta=gpv.id_venta)),2) AS total_financiado,
        format(((select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta)),2) AS total_pagado,
        format(((select SUM(gcr.monto_letra) from gp_cronograma gcr where gcr.id_venta=gpv.id_venta)-(select SUM(gppd.pagado) from gp_pagos_detalle gppd where gppd.id_venta=gpv.id_venta)),2) AS total_pendiente,
        if(gpv.cancelado='1','CANCELADO','ACTIVO') as estado,
        if(gpv.cancelado='1','red','green') as color_estado
        FROM datos_cliente cli
        INNER JOIN gp_venta  AS gpv ON gpv.id_cliente=cli.id
        INNER JOIN gp_lote AS gpl ON gpl.idlote=gpv.id_lote
        INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
        INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
        INNER JOIN gp_proyecto AS gpy ON gpy.idproyecto=gpz.idproyecto
        WHERE gpv.estado='1' 
        AND gpv.conformidad='1' 
        AND cli.esta_borrado='0'
        $query_documento
        $query_proyecto
        $query_zona
        $query_manzana
        $query_lote
        $query_estado
        ORDER BY cli.apellido_paterno ASC");

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
        gpcp.id as id,
        date_format(gpcp.fecha_vencimiento, '%d/%m/%Y') as fecha,
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
        WHERE gpcp.esta_borrado=0 AND gpv.id_venta='$Codigo'
        ORDER BY gpcp.correlativo ASC");

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




?>


