<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

$data = array();
$dataList = array();

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
    $query = mysqli_query($conection, "call pa_gp_venta_listar_paginado_admin($Start,$Length,'$ColumnaOrden',
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



?>