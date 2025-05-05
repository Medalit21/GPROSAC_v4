<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";

/* $nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);*/
$IdUser = 1;

$data = array();
$dataList = array();


if (isset($_POST['ReturnListaCoordenada'])) {
    $idproy = $_SESSION['iproy'];
    $query = mysqli_query($conection, "SELECT latitud,longitud,nombre FROM gp_proyecto where estado='1' AND idproyecto='$idproy' AND esta_borrado=0");
    $resp = mysqli_fetch_assoc($query);

    if ($query->num_rows > 0) {      
        $data['status'] = 'ok';
        $data['lat'] = '-12.080468299740325';
        $data['long'] = '-77.00164279703625';
    } else {
        $data['status'] = 'bad';
        $data['data'] = "Error";
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/**************************BUSCAr LISTA DE ZONAS POR PROYECTO******************* */

if (isset($_POST['ReturnListaZona'])) {

    $Idproyecto = $_POST['idProyecto'];
    $Idmanzana = $_POST['idManzana'];
    $Idzona = $_POST['idZona'];
	$val_user = $_POST['val_user'];

    $query = mysqli_query($conection, "SELECT idzona as id, nombre as nombre FROM gp_zona
    where idproyecto=$Idproyecto AND estado='1'");

    if ($query->num_rows > 0) {

        $data['status'] = 'ok';
       /* while ($row = $query->fetch_assoc()) {

            $IdZona=$row['id'];*/
            $queryManzanas = mysqli_query($conection, "select idmanzana as id , nombre  from gp_manzana
            where idzona=$Idzona AND idmanzana='$Idmanzana'");
            $respuesta = mysqli_fetch_assoc($queryManzanas);

            $IdHeardCard=encrypt('heading00'.$Idzona,"123");
            $IdBodyCard=encrypt('collapse00'.$Idzona,"123");
        /*
            $ListaMazanas=array();
            if ($queryManzanas->num_rows > 0) {
                while ($row1 = $queryManzanas->fetch_assoc()) {
                    $IdEncriptado=encrypt($row1['id'],"123");
                    $NombreEncriptado=encrypt($row1['nombre'],"123");
                    $IdBoton=encrypt('btn00'.$row1['id'],"123");
                    
                    array_push($ListaMazanas, [
                        'id' => $row1['id'],
                        'nombre' => $row1['nombre'],
                        'nombreEncriptado' => $NombreEncriptado,
                        'url' => $NAME_SERVER."views/M01_Proyecto/M01SM05_Inventario/M01_SM06_Manzana.php?i=".$IdEncriptado."&n=".$NombreEncriptado."&b=".$IdBoton."&h=".$IdHeardCard."&c=".$IdBodyCard
                    ]);



                }
                }

                array_push($dataList, [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'manzanas' => $ListaMazanas
                ]);
            }

           
            $data['data'] = $dataList;
        */

        $IdEncriptado=encrypt($respuesta['id'],"123");
        $NombreEncriptado=encrypt($respuesta['nombre'],"123");
        $IdBoton=encrypt('btn00'.$respuesta['id'],"123");
		$usser = encrypt($val_user,'123');				

        $data['id'] = $respuesta['id'];
        $data['nombre'] = $respuesta['nombre'];
        $data['url'] = $NAME_SERVER."views/M01_Proyecto/M01SM05_Inventario/M01_SM06_Manzana.php?Vsr=".$usser."&i=".$IdEncriptado."&n=".$NombreEncriptado."&b=".$IdBoton."&h=".$IdHeardCard."&c=".$IdBodyCard;

        $data['idproyecto'] = $Idproyecto;
        $data['idzona'] = $Idzona;
        $data['idmanzana'] = $Idmanzana;

    } else {

        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';

    }

    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************BUSCAr LISTA DE MANZANAS POR ZONA******************* */

if (isset($_POST['ReturnListaManzana'])) {

    $IdZona = $_POST['idZona'];

    $query = mysqli_query($conection, "select idmanzana as id , nombre  from gp_manzana
    where idzona=$IdZona");
    if ($query->num_rows > 0) {
        $data['status'] = 'ok';
        while ($row = $query->fetch_assoc()) {
            array_push($dataList, $row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************BUSCAr LISTA DE LOTES POR MANZANA******************* */

if (isset($_POST['ReturnListaLote'])) {

    $IdManzana = $_POST['idManzana'];
    $Estado = $_POST['estado'];
	$val_user = $_POST['val_user'];
    $Estado = !empty($Estado) ? "'$Estado'" : "NULL";

    $query = mysqli_query($conection, "SELECT
    tbl1.idlote as id,
    tbl1.nombre,
    tbl1.area,
    format(tbl1.valor_con_casa,2) as valorConCasa,
    format(tbl1.valor_sin_casa,2) as valorSinCasa,
    tbl1.estado as idEstado,
    if(tbl1.bloqueo_estado!=0, tbl22.nombre_corto, tbl2.nombre_corto) as estado,
    if(tbl1.bloqueo_estado!=0, tbl22.texto1, tbl2.texto1) as color,
    tbl3.id_reservacion as idReservacion,
    tbl3.id_cliente as idCliente,
    tbl3.id_lote as idLote,
    IFNULL(concat(tbl4.apellido_paterno,' ',tbl4.apellido_materno,', ',tbl4.nombres),'') as cliente,
    IFNULL(tbl4.celular_1,'') as celular1,
    IFNULL(tbl4.celular_2,'') as celular2,
    IFNULL(ROUND(tbl3.monto_reservado, 2),'') as montoReserva,
    IFNULL(DATE_FORMAT(tbl3.fecha_inicio_reserva , '%d/%m/%Y') ,'') as inicioReserva,
    IFNULL(DATE_FORMAT(tbl3.fecha_fin_reserva , '%d/%m/%Y') ,'')  as finReserva,
    IFNULL(tbl5.texto1,'') as siglaMoneda,
    concat(tbl6.apellido, ' ', tbl6.nombre) as vendedor,
    tbl7.condicion as idCondicion,
    round(tbl7.total,2) as montTotalVenta,
    tbl7.id_venta as idVenta,
    IFNULL(concat(tbl8.apellido_paterno,' ',tbl8.apellido_materno,', ',tbl8.nombres),'') as clienteComprador,
    IFNULL(tbl8.celular_1,'') as celular1Comprador,
    IFNULL(tbl8.celular_2,'') as celular2Comprador,
    IFNULL(DATE_FORMAT( tbl7.fecha_venta, '%d/%m/%Y') ,'')  as fechaVenta,
    concat(tbl9.apellido, ' ', tbl9.nombre) as vendedorFinalizado,
    tbl10.nombre_corto as tipoInmueble,
    tbl11.nombre_corto as tipoCasa,
    tbl12.nombre_corto as condicion,
    (SELECT count(*) FROM gp_suceso_lote WHERE id_lote= tbl1.idlote) AS cantidadSuceso,
	tbl1.motivo AS Motivo,
	tbl1.bloqueo_estado AS Bloqueado,
    (SELECT tblp.idproyecto as idproy from gp_manzana tblm inner join gp_zona tblz on tblm.idzona=tblz.idzona
    inner join gp_proyecto tblp on tblz.idproyecto=tblp.idproyecto
    where idmanzana=$IdManzana) as idproy,
    (SELECT tblz.idzona as idzona from gp_manzana tblm 
        inner join gp_zona tblz on tblm.idzona=tblz.idzona
        where idmanzana=$IdManzana) as idzona
    FROM gp_lote tbl1
    INNER JOIN (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI' AND texto2='E') AS tbl2 on tbl1.estado=tbl2.codigo_item
    INNER JOIN configuracion_detalle AS tbl22 on (tbl1.bloqueo_estado=tbl22.codigo_item OR tbl1.bloqueo_estado=0) AND tbl22.codigo_tabla='_ESTADO_LOTE' AND tbl22.estado='ACTI' AND tbl22.texto2='M'
    LEFT JOIN (select * from gp_reservacion where esta_borrado=0 and estado = 2) AS tbl3 on tbl1.idlote=tbl3.id_lote
    LEFT JOIN datos_cliente AS tbl4 on tbl3.id_cliente=tbl4.id
    LEFT JOIN (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_MONEDA' AND estado='ACTI') tbl5 on tbl1.tipo_moneda=tbl5.idconfig_detalle 
    LEFT JOIN  (select * from gp_venta where esta_borrado=0 and devolucion='0')  as tbl7 on tbl1.idlote= tbl7.id_lote
	LEFT JOIN persona as tbl6 on tbl3.id_vendedor= tbl6.idusuario OR tbl7.id_vendedor= tbl6.idusuario
	LEFT JOIN persona as tbl9 on tbl7.id_vendedor= tbl9.idusuario
    LEFT JOIN datos_cliente AS tbl8 on tbl7.id_cliente=tbl8.id
    LEFT JOIN (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_INMUEBLE' AND estado='ACTI') tbl10 on tbl7.tipo_inmobiliaria=tbl10.codigo_item  
    INNER JOIN gp_manzana AS tbl13 on  tbl13.idmanzana = tbl1.idmanzana
    LEFT JOIN (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_TIPO_CASA' AND estado='ACTI') tbl11 on tbl13.tipo_casa=tbl11.codigo_item 
    LEFT JOIN (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_CONDICION_VENTA' AND estado='ACTI') tbl12 on tbl7.condicion=tbl12.codigo_item  
    where tbl1.idmanzana=$IdManzana 
    AND ($Estado IS NULL OR tbl1.estado=$Estado)
    GROUP BY tbl1.idlote
    ORDER BY tbl1.idlote ASC;
    ");
	$usseer = encrypt($val_user,'123');
	
    if ($query->num_rows > 0) {
        $data['status'] = 'ok';
			
        while ($row = $query->fetch_assoc()) {

            $IdEncriptado=encrypt($row['id'],"123");
            $IdReservaEncriptado=encrypt($row['idReservacion'],"123");
			
            array_push($dataList, 
            [
                'id' => $row['id'],
                'idEncriptado' => $IdEncriptado,
                'nombre' => $row['nombre'],
                'area' => $row['area'],
                'valorConCasa' => $row['valorConCasa'],
                'valorSinCasa' => $row['valorSinCasa'],
                'idEstado' => $row['idEstado'],
                'estado' => $row['estado'],
                'color' => $row['color'],
                'idReservacion' => $row['idReservacion'],
                'idCliente' => $row['idCliente'],
                'idLote' => $row['idLote'],
                'cliente' => $row['cliente'],
                'celular1' => $row['celular1'],
                'celular2' => $row['celular2'],
                'montoReserva' => $row['montoReserva'],
                'inicioReserva' => $row['inicioReserva'],
                'finReserva' => $row['finReserva'],
                'siglaMoneda' => $row['siglaMoneda'],
                'cantidadSuceso' => $row['cantidadSuceso'],
                'vendedor' => $row['vendedor'],
                //'urlReservacion' => $NAME_SERVER."views/M02_Clientes/M02SM01_RegistroCliente/M02SM01_RegistroCliente.php?Vsr=".$usseer,
                'urlReservacion' => $NAME_SERVER."views/M03_Ventas/M03SM01_Reservacion/M03SM01_Reservacion.php?Vsr=".$usseer,
                'clienteComprador' => $row['clienteComprador'],
                'celular1Comprador' => $row['celular1Comprador'],
                'celular2Comprador' => $row['celular2Comprador'],
                'tipoInmueble' => $row['tipoInmueble'],
                'tipoCasa' => $row['tipoCasa'],
                'idCondicion' => $row['idCondicion'],
                'condicion' => $row['condicion'],
                'montTotalVenta' => $row['montTotalVenta'],
                'fechaVenta' => $row['fechaVenta'],
                'vendedorFinalizado' => $row['vendedorFinalizado'],
                'idVenta'=> $row['idVenta'],
                'urlVenta' => $NAME_SERVER."views/M03_Ventas/M03SM02_Venta/M03SM02_Venta.php?Vsr=".$usseer."&l=".$IdEncriptado."&r=".$IdReservaEncriptado,
                'idProy'=> $row['idproy'],
                'idZona'=> $row['idzona'],
                'idBloqueado'=> $row['Bloqueado'],
            ]);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/**************************RETORNAR DETALLE DE ZONA******************* */
if (isset($_POST['ReturnZonaDetalle'])) {
    $idManzana = $_POST['idManzana'];
    $query = mysqli_query($conection, "SELECT 
        tbl1.idmanzana as id , 
        tbl1.nombre,
        tbl2.idzona as idzona,
        tbl2.nombre as zona, 
        tbl3.nombre as proyecto, 
        tbl3.idproyecto,
        tbl3.idproyecto as idproy
    from gp_manzana tbl1
    inner join gp_zona tbl2 on tbl1.idzona=tbl2.idzona
    inner join gp_proyecto tbl3 on tbl2.idproyecto=tbl3.idproyecto
    where idmanzana=$idManzana");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
    } else {
        if (!$query) {
            $data['status'] = 'bad';
            $data['data'] = mysqli_error($conection);
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'Ocurrio un problema, al buscar el trabajador por DNI.';
        }
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/**************************RETORNAR DETALLE CLIENTE******************* */
if (isset($_POST['ReturnDataCliente'])) {
    $idCliente = $_POST['idCliente'];
    $idLote = $_POST['idLote'];
    $idReservacion = $_POST['idReservacion'];

    $query = mysqli_query($conection, "SELECT id, documento,apellido_paterno as apellidoPaterno,apellido_materno as apellidoMaterno,nombres from datos_cliente
    where id=$idCliente");
    if ($query->num_rows > 0) {
        $resultado = $query->fetch_assoc();
        $data['status'] = 'ok';
        $data['data'] = $resultado;
        $data['idLote'] = $idLote;
        $data['idReservacion'] = $idReservacion;
    } else {
        if (!$query) {
            $data['status'] = 'bad';
            $data['data'] = mysqli_error($conection);
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'Ocurrio un problema, al buscar el cliente por ID.';
        }
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*************************LIBERAR LOTE ******************* */
if (isset($_POST['ReturnLiberarLote'])) {
    $Fecha = $_POST['fecha'];
    $Fecha = !empty($Fecha) ? "'$Fecha'" : "NULL";

    $Descripcion = $_POST['descricion'];
    $Descripcion = !empty($Descripcion) ? "'$Descripcion'" : "NULL";

    $idCliente = $_POST['idCliente'];
    $idCliente = !empty($idCliente) ? "'$idCliente'" : "NULL";

    $idLote = $_POST['idLote'];
    $idReservacion = $_POST['idReservacion'];

    $IdMotivo = $_POST['idMotivo'];
    $IdMotivo = !empty($IdMotivo) ? "'$IdMotivo'" : "NULL";

    $querySuceso = mysqli_query($conection, "call pa_gp_lote_liberar($Fecha,$Descripcion,$idCliente,'$idLote','$idReservacion',$IdMotivo)");

    if ($querySuceso) {
        $data['status'] = 'ok';
        $data['data'] = 'Se libero correctamente';
    } else {
        if (!$querySuceso) {
            $data['status'] = 'bad';
            $data['data'] = mysqli_error($conection);
        }else{
            $data['status'] = 'bad';
            $data['data'] = 'Ocurrio un problema, al liberar lote.';
        }
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

/*************************VER SUCESOS LOTE ******************* */
if (isset($_POST['ReturnSucesoLote'])) {

    $IdLote = $_POST['idLote'];

    $query = mysqli_query($conection, "SELECT 
    ifnull(tbl2.nombre_corto,'') as estadoAsignado,
    concat(tbl4.apellido_paterno,' ', tbl4.apellido_materno, ' ', tbl4.nombres) as clienteRelacionado,
    ifnull(tbl3.nombre_corto,'') as motivo,
    ifnull(DATE_FORMAT(tbl1.fecha , '%d/%m/%Y') ,'') as fecha
    FROM gp_suceso_lote tbl1
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI') tbl2 on tbl1.estado_fin=tbl2.codigo_item  
    left join (SELECT * FROM configuracion_detalle WHERE codigo_tabla='_MOTIVO_LIBERACION' AND estado='ACTI') tbl3 on tbl1.id_motivo=tbl3.idconfig_detalle  
    left join datos_cliente tbl4 on tbl1.id_cliente=tbl4.id
    where id_lote=$IdLote
    order by tbl1.id_suceso_lote desc");
    if ($query->num_rows > 0) {
        $data['status'] = 'ok';
        while ($row = $query->fetch_assoc()) {

            array_push($dataList,$row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/*************************VER estados LOTE ******************* */
if (isset($_POST['ReturnListaEstados'])) {
    $query = mysqli_query($conection, "SELECT 
    nombre_corto as descripcion,
    texto1 as color
     FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND estado='ACTI'");
    if ($query->num_rows > 0) {
        $data['status'] = 'ok';
        while ($row = $query->fetch_assoc()) {

            array_push($dataList,$row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
    }
    header('Content-type: text/javascript');
    echo json_encode($data, JSON_PRETTY_PRINT);
}


/*************************VER CRONOGRAMA PAGO ******************* */
if (isset($_POST['ReturnCronogramaPago'])) {

    $IdVenta = $_POST['idVenta'];

    $query = mysqli_query($conection, "SELECT 
    tbl1.item_letra as numeroCuota,
    tbl1.monto_letra as montoPagar,
    ifnull(DATE_FORMAT(tbl1.fecha_vencimiento, '%d/%m/%Y') ,'') as fechaVencimiento,
    tbl1.interes_amortizado as interesAmortizado,
    tbl1.capital_amortizado AS capitalAmortizado,
    (case when tbl1.capital_vivo< 1 then 0
    else tbl1.capital_vivo
    end ) as capitalVivo
    FROM gp_cronograma tbl1
    where tbl1.id_venta=$IdVenta and tbl1.item_letra<>0
    order by tbl1.item_letra asc");
    if ($query->num_rows > 0) {
        $data['status'] = 'ok';
        while ($row = $query->fetch_assoc()) {

            array_push($dataList,$row);
        }
        $data['data'] = $dataList;
    } else {
        $data['status'] = 'bad';
        if (!$query) {
            $data['dataDB'] = mysqli_error($conection);
        }
        $data['data'] = 'Ocurrio un problema, pongase en contacto con soporte por favor.';
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
    $query = mysqli_query($conection, "SELECT idlote as valor, nombre as texto FROM gp_lote WHERE idmanzana='$idManzana'");

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

