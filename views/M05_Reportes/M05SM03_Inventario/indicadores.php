<?php
   //session_start();
   include_once "../../../config/configuracion.php";
   include_once "../../../config/conexion_2.php";
   $hora = date("H:i:s", time());;
   $fecha = date('Y-m-d'); 
   $mes = date('m');
   //$anio = date('Y');
   
   $disponible= 0;
	$nodisponible= 0;
	$libres= 0;
	$reservados= 0;
	$bloqueados= 0;
	$vendidos= 0;
	$canjes= 0;
	$lotesolo= 0;
	$lotecasa= 0;

	$query = mysqli_query($conection,"SELECT gpp.nombre,
    (SELECT count(gpz.idzona) FROM gp_zona gpz WHERE gpz.idproyecto=gpp.idproyecto) as totZonas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz WHERE gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totManzanas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLotes,
    (SELECT count(dc.id) FROM datos_cliente dc WHERE dc.esta_borrado=0) as totClientes,
    (SELECT count(gpv.id_venta) FROM gp_venta gpv WHERE esta_borrado=0) as totVentas,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='1' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLibres,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='2' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totReservados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='3' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totPorVencer,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='4' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVencidos,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='5' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosT,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.estado='5' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidoTBloqueados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.motivo='8' AND gpl.estado='5' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosTCanjes,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.estado='6' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosTC,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.estado='6' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidoTCBloqueados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.motivo='8' AND gpl.estado='6' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totVendidosTCCanjes,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.estado='1' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totLibresBloqueados,
    (SELECT count(gpm.idmanzana) FROM gp_manzana gpm, gp_zona gpz, gp_lote gpl WHERE gpl.bloqueo_estado='7' AND gpl.idmanzana=gpm.idmanzana AND gpm.idzona=gpz.idzona AND gpz.idproyecto=gpp.idproyecto) as totBloqueados,
    gpp.nombre as nombre_proyecto,
    format(gpp.area,2) as area_proyecto,
    gpp.inicio_proyecto as inicio_proyecto,
    gpp.direccion as direccion_proyecto,
    gpp.departamento as departamento_proyecto,
    gpp.provincia as provincia_proyecto,
    gpp.distrito as distrito_proyecto
    FROM gp_proyecto gpp
    WHERE gpp.idproyecto=1"); 

	$respuesta = mysqli_fetch_assoc($query);
	
	$disponible= $respuesta['totLibres'] - $respuesta['totLibresBloqueados'];
	$nodisponible= $respuesta['totVendidosT'] + $respuesta['totVendidosTC'] + $respuesta['totReservados'] + $respuesta['totLibresBloqueados'];
	$libres= $respuesta['totLibres'];
	$reservados= $respuesta['totReservados'];
	$bloqueados= $respuesta['totBloqueados'];
	$vendidos= (($respuesta['totVendidosT'] + $respuesta['totVendidosTC']) - ($respuesta['totVendidoTBloqueados'] + $respuesta['totVendidoTCBloqueados']));
	$canjes= ($respuesta['totVendidosTCanjes'] + $respuesta['totVendidosTCCanjes']);
	$lotesolo= ($respuesta['totVendidosT'] - ($respuesta['totVendidosTCanjes'] + $respuesta['totVendidoTBloqueados']));
	$lotecasa= ($respuesta['totVendidosTC'] - ($respuesta['totVendidosTCCanjes'] + $respuesta['totVendidoTCBloqueados']));
  

