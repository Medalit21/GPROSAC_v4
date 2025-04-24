<?php
   session_start();
   
   include_once "../../../../config/configuracion.php";
   include_once "../../../../config/conexion_2.php";
   include_once "../../../../config/codificar.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   $anio = date('Y');
   


   $data = array();
   $dataList = array();

if(isset($_POST['btnIrReporte'])){
    
    $_TXTUSR = $_POST['_TXTUSR'];
    
    $cbxFiltroNumDocumento = $_POST['cbxFiltroNumDocumento'];
    $cbxFiltroNumDocumento = encrypt($cbxFiltroNumDocumento, "123");
    
    $txtDesdeFiltro = $_POST['txtDesdeFiltro'];
    $txtDesdeFiltro = encrypt($txtDesdeFiltro, "123");
    
    $txtHastaFiltro = $_POST['txtHastaFiltro'];
    $txtHastaFiltro = encrypt($txtHastaFiltro, "123");
    
    $bxFiltroProyecto = $_POST['bxFiltroProyecto'];
    $bxFiltroProyecto = encrypt($bxFiltroProyecto, "123");
    
    $bxFiltroZona = $_POST['bxFiltroZona'];
    $bxFiltroZona = encrypt($bxFiltroZona, "123");
    
    $bxFiltroManzana = $_POST['bxFiltroManzana'];
    $bxFiltroManzana = encrypt($bxFiltroManzana, "123");
    
    $bxFiltroLote = $_POST['bxFiltroLote'];
    $bxFiltroLote = encrypt($bxFiltroLote, "123");
      
    
    $data['status'] = 'ok';
    $data['usr'] = $_TXTUSR;
    $data['documento'] = $cbxFiltroNumDocumento;
    $data['fecini'] = $txtDesdeFiltro;
    $data['fecfin'] = $txtHastaFiltro;
    $data['idproyecto'] = $bxFiltroProyecto;
    $data['idzona'] = $bxFiltroZona;
    $data['idmanzana'] = $bxFiltroManzana;
    $data['idlote'] = $bxFiltroLote;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}

if(isset($_POST['btnValidarFechas'])){
    
    $idProyecto = $_POST['idProyecto'];
    
    $consultar_fec_ini = mysqli_query($conection, "SELECT inicio_proyecto as inicio FROM gp_proyecto WHERE idproyecto='$idProyecto' AND estado='1'");
    $respuesta_fec_ini = mysqli_fetch_assoc($consultar_fec_ini);
    $fec_ini = $respuesta_fec_ini['inicio'];
    
    $ultimo_dia = date('Y-m-d');  
    
    $data['status'] = 'ok';
    $data['primero'] = $fec_ini;
    $data['ultimo'] = $ultimo_dia;
    
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
    
}


if(isset($_POST['ReturnListaLetrasVencidas'])){

	$cbxPeriodo = isset($_POST['cbxPeriodo']) ? $_POST['cbxPeriodo'] : Null;
	$cbxPeriodo = trim($cbxPeriodo);
	
    for($i=1; $i<=12;$i++){

        //$i  = 1;
        $monthNum  = $i;
        switch($monthNum)
        {   
            case 1:
            $monthNameSpanish = "ENERO";
            break;

            case 2:
            $monthNameSpanish = "FEBRERO";
            break;

            case 3:
            $monthNameSpanish = "MARZO";
            break;

            case 4:
            $monthNameSpanish = "ABRIL";
            break;

            case 5:
            $monthNameSpanish = "MAYO";
            break;

            case 6:
            $monthNameSpanish = "JUNIO";
            break;

            case 7:
            $monthNameSpanish = "JULIO";
            break;

            case 8:
            $monthNameSpanish = "AGOSTO";
            break;

            case 9:
            $monthNameSpanish = "SEPTIEMBRE";
            break;

            case 10:
            $monthNameSpanish = "OCTUBRE";
            break;

            case 11:
            $monthNameSpanish = "NOVIEMBRE";
            break;

            case 12:
            $monthNameSpanish = "DICIEMBRE";
            break;
        }


        $query = mysqli_query($conection,"SELECT 
			CONCAT('INGRESOS EN EL MES',' ','$monthNameSpanish') AS descripcion,

			FORMAT(SUM(IFNULL((
				SELECT SUM(cro1.monto_letra)
				FROM gp_cronograma cro1
				WHERE cro1.id_venta = gpv.id_venta
				AND YEAR(cro1.fecha_vencimiento) = '$cbxPeriodo'
				AND MONTH(cro1.fecha_vencimiento) = '$i'
			), 0)), 2) AS a_cancelar,

			FORMAT(SUM(IFNULL((
				SELECT SUM(gpc.pagado)
				FROM gp_pagos_cabecera AS gpc
				INNER JOIN gp_cronograma AS cro2 ON cro2.correlativo = gpc.id_cronograma AND cro2.id_venta = gpc.id_venta
				WHERE gpc.id_venta = gpv.id_venta
				AND YEAR(cro2.fecha_vencimiento) = '$cbxPeriodo'
				AND MONTH(cro2.fecha_vencimiento) = '$i'
				AND gpc.esta_borrado = '0'
			), 0)), 2) AS cancelado,

			FORMAT(
				SUM(
					IFNULL((
						SELECT SUM(cro1.monto_letra)
						FROM gp_cronograma cro1
						WHERE cro1.id_venta = gpv.id_venta
						AND YEAR(cro1.fecha_vencimiento) = '$cbxPeriodo'
						AND MONTH(cro1.fecha_vencimiento) = '$i'
					), 0)
				) -
				SUM(
					IFNULL((
						SELECT SUM(gpc.pagado)
						FROM gp_pagos_cabecera AS gpc
						INNER JOIN gp_cronograma AS cro2 ON cro2.correlativo = gpc.id_cronograma AND cro2.id_venta = gpc.id_venta
						WHERE gpc.id_venta = gpv.id_venta
						AND YEAR(cro2.fecha_vencimiento) = '$cbxPeriodo'
						AND MONTH(cro2.fecha_vencimiento) = '$i'
						AND gpc.esta_borrado = '0'
					), 0)
				)
			, 2) AS por_cancelar

		FROM gp_venta gpv
		WHERE gpv.devolucion = '0'
		AND gpv.esta_borrado = '0'
		;"); 
        
        $row = mysqli_fetch_assoc($query);
        //Campos para llenar Tabla
        array_push($dataList,[
            'descripcion' => $row['descripcion'],
            'a_cancelar' => $row['a_cancelar'],
            'cancelado' => $row['cancelado'],
            'por_cancelar' => $row['por_cancelar']
        ]);

    }

    $data['data'] = $dataList;
    
    header('Content-type: text/javascript');
    echo json_encode($data,JSON_PRETTY_PRINT) ;
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
    $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idManzana' AND estado IN (5,6)");

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
