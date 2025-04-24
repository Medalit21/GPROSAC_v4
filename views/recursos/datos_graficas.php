<?php

//session_start();
include_once "../config/configuracion.php";
include_once "../config/conexion_2.php";
$hora = date("H:i:s", time());;
$fecha = date('Y-m-d'); 
$anio = date('Y'); 
$mes = date('m');
$num_mes = $mes + 1;
$control = $fecha." - ".$hora;

$nom_user = $_SESSION['variable_user'];
$consulta_idusu = mysqli_query($conection, "SELECT idusuario as id FROM usuario WHERE user='$nom_user'");
$respuesta_idusu = mysqli_fetch_assoc($consulta_idusu);
$IdUser = $respuesta_idusu['id'];
$idEmpresa = $_SESSION['id_empresa'];

//GRAFICA 01 : REMUNERACIONES

//PLANILLA
$consultar_montos = mysqli_query($conection, "SELECT
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10102' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as enero,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10103' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as febrero,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10104' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as marzo,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10105' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as abril,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10106' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as mayo,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10107' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as junio,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10108' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as julio,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10109' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as agosto,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10110' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as septiembre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10111' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as octubre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10112' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as noviembre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_historico WHERE mes='10113' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as diciembre
FROM planilla_historico
GROUP BY año");
$respuesta_montos = mysqli_fetch_assoc($consultar_montos);

//Valores
$enero = $respuesta_montos['enero'];
$febrero = $respuesta_montos['febrero'];
$marzo = $respuesta_montos['marzo'];
$abril = $respuesta_montos['abril'];
$mayo = $respuesta_montos['mayo'];
$junio = $respuesta_montos['junio'];
$julio = $respuesta_montos['julio'];
$agosto = $respuesta_montos['agosto'];
$septiembre = $respuesta_montos['septiembre'];
$octubre = $respuesta_montos['octubre'];
$noviembre = $respuesta_montos['noviembre'];
$diciembre = $respuesta_montos['diciembre'];
$total = $enero + $febrero + $marzo + $abril + $mayo + $junio + $julio + $agosto + $septiembre + $octubre + $noviembre + $diciembre;

//RH
$consultar_montos = mysqli_query($conection, "SELECT
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10102' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as enero,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10103' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as febrero,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10104' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as marzo,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10105' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as abril,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10106' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as mayo,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10107' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as junio,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10108' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as julio,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10109' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as agosto,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10110' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as septiembre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10111' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as octubre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10112' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as noviembre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_rh WHERE mes='10113' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as diciembre
FROM planilla_rh
GROUP BY año");
$respuesta_montos = mysqli_fetch_assoc($consultar_montos);

//Valores
$enero_2 = $respuesta_montos['enero'];
$febrero_2 = $respuesta_montos['febrero'];
$marzo_2 = $respuesta_montos['marzo'];
$abril_2 = $respuesta_montos['abril'];
$mayo_2 = $respuesta_montos['mayo'];
$junio_2 = $respuesta_montos['junio'];
$julio_2 = $respuesta_montos['julio'];
$agosto_2 = $respuesta_montos['agosto'];
$septiembre_2 = $respuesta_montos['septiembre'];
$octubre_2 = $respuesta_montos['octubre'];
$noviembre_2 = $respuesta_montos['noviembre'];
$diciembre_2 = $respuesta_montos['diciembre'];
$total_2 = $enero_2 + $febrero_2 + $marzo_2 + $abril_2 + $mayo_2 + $junio_2 + $julio_2 + $agosto_2 + $septiembre_2 + $octubre_2 + $noviembre_2 + $diciembre_2;


//TERCEROS
$consultar_montos = mysqli_query($conection, "SELECT
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10102' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as enero,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10103' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as febrero,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10104' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as marzo,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10105' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as abril,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10106' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as mayo,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10107' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as junio,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10108' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as julio,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10109' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as agosto,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10110' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as septiembre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10111' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as octubre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10112' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as noviembre,
(SELECT if(SUM(monto_concepto)>0,SUM(monto_concepto), 0.00) FROM planilla_tercero WHERE mes='10113' AND año='$anio' AND descripcion_concepto!='AFP' AND idempresa='$idEmpresa') as diciembre
FROM planilla_tercero
GROUP BY año");
$respuesta_montos = mysqli_fetch_assoc($consultar_montos);

//Valores
$enero_3 = $respuesta_montos['enero'];
$febrero_3 = $respuesta_montos['febrero'];
$marzo_3 = $respuesta_montos['marzo'];
$abril_3 = $respuesta_montos['abril'];
$mayo_3 = $respuesta_montos['mayo'];
$junio_3 = $respuesta_montos['junio'];
$julio_3 = $respuesta_montos['julio'];
$agosto_3 = $respuesta_montos['agosto'];
$septiembre_3 = $respuesta_montos['septiembre'];
$octubre_3 = $respuesta_montos['octubre'];
$noviembre_3 = $respuesta_montos['noviembre'];
$diciembre_3 = $respuesta_montos['diciembre'];
$total_3 = $enero_3 + $febrero_3 + $marzo_3 + $abril_3 + $mayo_3 + $junio_3 + $julio_3 + $agosto_3 + $septiembre_3 + $octubre_3 + $noviembre_3 + $diciembre_3;


if($enero>$enero_2){if($enero>$enero_3){$var_enero=$enero;}else{$var_enero=$enero_3;}}else{if($enero_2>$enero_3){$var_enero=$enero_2;}else{$var_enero=$enero_3;}}
if($febrero>$febrero_2){if($febrero>$febrero_3){$var_febrero=$febrero;}else{$var_febrero=$febrero_3;}}else{if($febrero_2>$febrero_3){$var_febrero=$febrero_2;}else{$var_febrero=$febrero_3;}}
if($marzo>$marzo_2){if($marzo>$marzo_3){$var_marzo=$marzo;}else{$var_marzo=$marzo_3;}}else{if($marzo_2>$marzo_3){$var_marzo=$marzo_2;}else{$var_marzo=$marzo_3;}}
if($abril>$abril_2){if($abril>$abril_3){$var_abril=$abril;}else{$var_abril=$abril_3;}}else{if($abril_2>$abril_3){$var_abril=$abril_2;}else{$var_abril=$abril_3;}}
if($mayo>$mayo_2){if($mayo>$mayo_3){$var_mayo=$mayo;}else{$var_mayo=$mayo_3;}}else{if($mayo_2>$mayo_3){$var_mayo=$mayo_2;}else{$var_mayo=$mayo_3;}}
if($junio>$junio_2){if($junio>$junio_3){$var_junio=$junio;}else{$var_junio=$junio_3;}}else{if($junio_2>$junio_3){$var_junio=$junio_2;}else{$var_junio=$junio_3;}}
if($julio>$julio_2){if($julio>$julio_3){$var_julio=$julio;}else{$var_julio=$julio_3;}}else{if($julio_2>$julio_3){$var_julio=$julio_2;}else{$var_julio=$julio_3;}}
if($agosto>$agosto_2){if($agosto>$agosto_3){$var_agosto=$agosto;}else{$var_agosto=$agosto_3;}}else{if($agosto_2>$agosto_3){$var_agosto=$agosto_2;}else{$var_agosto=$agosto_3;}}
if($septiembre>$septiembre_2){if($septiembre>$septiembre_3){$var_septiembre=$septiembre;}else{$var_septiembre=$septiembre_3;}}else{if($septiembre_2>$septiembre_3){$var_septiembre=$septiembre_2;}else{$var_septiembre=$septiembre_3;}}
if($octubre>$octubre_2){if($octubre>$octubre_3){$var_octubre=$octubre;}else{$var_octubre=$octubre_3;}}else{if($octubre_2>$octubre_3){$var_octubre=$octubre_2;}else{$var_octubre=$octubre_3;}}
if($noviembre>$noviembre_2){if($noviembre>$noviembre_3){$var_noviembre=$noviembre;}else{$var_noviembre=$noviembre_3;}}else{if($noviembre_2>$noviembre_3){$var_noviembre=$noviembre_2;}else{$var_noviembre=$noviembre_3;}}
if($diciembre>$diciembre_2){if($diciembre>$diciembre_3){$var_diciembre=$diciembre;}else{$var_diciembre=$diciembre_3;}}else{if($diciembre_2>$diciembre_3){$var_diciembre=$diciembre_2;}else{$var_diciembre=$diciembre_3;}}



//GRAFICA 02 : ESTADO DE CONTRATOS

//Contratos Activos

$consultar_contratos_activos = mysqli_query($conection, "SELECT
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>2 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_enero,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>3 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_febrero,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>4 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_marzo,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>5 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_abril,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>6 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_mayo,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>7 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_junio,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>8 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_julio,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>9 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_agosto,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>10 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_septiembre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>11 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_octubre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>12 AND YEAR(finContrato)=$anio+1 AND idempresa='$idEmpresa') as contrato_noviembre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)>1 AND YEAR(finContrato)=$anio+1 AND idempresa='$idEmpresa') as contrato_diciembre
FROM datos_laborales 
GROUP BY documento, tipodocumento, nacionalidad, idempresa");
$respuesta_contratos_activos = mysqli_fetch_assoc($consultar_contratos_activos);

$contrato_act_enero = $respuesta_contratos_activos['contrato_enero'];
$contrato_act_febrero = $respuesta_contratos_activos['contrato_febrero'];
$contrato_act_marzo = $respuesta_contratos_activos['contrato_marzo'];
$contrato_act_abril = $respuesta_contratos_activos['contrato_abril'];
$contrato_act_mayo = $respuesta_contratos_activos['contrato_mayo'];
$contrato_act_junio = $respuesta_contratos_activos['contrato_junio'];
$contrato_act_julio = $respuesta_contratos_activos['contrato_julio'];
$contrato_act_agosto = $respuesta_contratos_activos['contrato_agosto'];
$contrato_act_septiembre = $respuesta_contratos_activos['contrato_septiembre'];
$contrato_act_octubre = $respuesta_contratos_activos['contrato_octubre'];
$contrato_act_noviembre = $respuesta_contratos_activos['contrato_noviembre'];
$contrato_act_diciembre = $respuesta_contratos_activos['contrato_diciembre'];

//Contratos por vencer

$consultar_contratos_pv = mysqli_query($conection, "SELECT
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=2 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_enero,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=3 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_febrero,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=4 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_marzo,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=5 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_abril,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=6 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_mayo,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=7 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_junio,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=8 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_julio,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=9 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_agosto,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=10 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_septiembre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=11 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_octubre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=12 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_noviembre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)=1 AND YEAR(finContrato)=$anio+1 AND idempresa='$idEmpresa') as contrato_diciembre
FROM datos_laborales 
GROUP BY documento, tipodocumento, nacionalidad, idempresa");
$respuesta_contratos_pv = mysqli_fetch_assoc($consultar_contratos_pv);

$contrato_pv_enero = $respuesta_contratos_pv['contrato_enero'];
$contrato_pv_febrero = $respuesta_contratos_pv['contrato_febrero'];
$contrato_pv_marzo = $respuesta_contratos_pv['contrato_marzo'];
$contrato_pv_abril = $respuesta_contratos_pv['contrato_abril'];
$contrato_pv_mayo = $respuesta_contratos_pv['contrato_mayo'];
$contrato_pv_junio = $respuesta_contratos_pv['contrato_junio'];
$contrato_pv_julio = $respuesta_contratos_pv['contrato_julio'];
$contrato_pv_agosto = $respuesta_contratos_pv['contrato_agosto'];
$contrato_pv_septiembre = $respuesta_contratos_pv['contrato_septiembre'];
$contrato_pv_octubre = $respuesta_contratos_pv['contrato_octubre'];
$contrato_pv_noviembre = $respuesta_contratos_pv['contrato_noviembre'];
$contrato_pv_diciembre = $respuesta_contratos_pv['contrato_diciembre'];


//Contratos vencidos

$consultar_contratos_v = mysqli_query($conection, "SELECT
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<2 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_enero,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<3 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_febrero,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<4 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_marzo,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<5 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_abril,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<6 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_mayo,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<7 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_junio,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<8 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_julio,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<9 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_agosto,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<10 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_septiembre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<11 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_octubre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<12 AND YEAR(finContrato)=$anio AND idempresa='$idEmpresa') as contrato_noviembre,
(SELECT count(*) FROM datos_laborales WHERE MONTH(finContrato)<1 AND YEAR(finContrato)=$anio+1 AND idempresa='$idEmpresa') as contrato_diciembre
FROM datos_laborales 
GROUP BY documento, tipodocumento, nacionalidad, idempresa");
$respuesta_contratos_v = mysqli_fetch_assoc($consultar_contratos_v);

$contrato_v_enero = $respuesta_contratos_v['contrato_enero'];
$contrato_v_febrero = $respuesta_contratos_v['contrato_febrero'];
$contrato_v_marzo = $respuesta_contratos_v['contrato_marzo'];
$contrato_v_abril = $respuesta_contratos_v['contrato_abril'];
$contrato_v_mayo = $respuesta_contratos_v['contrato_mayo'];
$contrato_v_junio = $respuesta_contratos_v['contrato_junio'];
$contrato_v_julio = $respuesta_contratos_v['contrato_julio'];
$contrato_v_agosto = $respuesta_contratos_v['contrato_agosto'];
$contrato_v_septiembre = $respuesta_contratos_v['contrato_septiembre'];
$contrato_v_octubre = $respuesta_contratos_v['contrato_octubre'];
$contrato_v_noviembre = $respuesta_contratos_v['contrato_noviembre'];
$contrato_v_diciembre = $respuesta_contratos_v['contrato_diciembre'];


//ASISTENCIA

//Enero
$enero3=$anio."-01-01";
$tdias_enero = date('t', strtotime($enero3));
$enero1 = strtotime($enero3);
$enero2 = strtotime($enero3 . "+ " . $tdias_enero . " days");
$cont_enero = 0;
for ($enero1; $enero1 <= $enero2; $enero1 = strtotime('+1 day ' . date('Y-m-d', $enero1))) {
    if ((strcmp(date('D', $enero1), 'Sun') != 0) and (strcmp(date('D', $enero1), 'Sat') != 0)) {
        $cont_enero = $cont_enero + 1;
    }
}


//febrero
$febrero3=$anio."-02-01";
$tdias_febrero = date('t', strtotime($febrero3));
$febrero1 = strtotime($febrero3);
$febrero2 = strtotime($febrero3 . "+ " . $tdias_febrero . " days");
$cont_febrero = 0;
for ($febrero1; $febrero1 <= $febrero2; $febrero1 = strtotime('+1 day ' . date('Y-m-d', $febrero1))) {
    if ((strcmp(date('D', $febrero1), 'Sun') != 0) and (strcmp(date('D', $febrero1), 'Sat') != 0)) {
        $cont_febrero = $cont_febrero + 1;
    }
}

//marzo
$marzo3=$anio."-03-01";
$tdias_marzo = date('t', strtotime($marzo3));
$marzo1 = strtotime($marzo3);
$marzo2 = strtotime($marzo3 . "+ " . $tdias_marzo . " days");
$cont_marzo = 0;
for ($marzo1; $marzo1 <= $marzo2; $marzo1 = strtotime('+1 day ' . date('Y-m-d', $marzo1))) {
    if ((strcmp(date('D', $marzo1), 'Sun') != 0) and (strcmp(date('D', $marzo1), 'Sat') != 0)) {
        $cont_marzo = $cont_marzo + 1;
    }
}

//abril
$abril3=$anio."-04-01";
$tdias_abril = date('t', strtotime($abril3));
$abril1 = strtotime($abril3);
$abril2 = strtotime($abril3 . "+ " . $tdias_abril . " days");
$cont_abril = 0;
for ($abril1; $abril1 <= $abril2; $abril1 = strtotime('+1 day ' . date('Y-m-d', $abril1))) {
    if ((strcmp(date('D', $abril1), 'Sun') != 0) and (strcmp(date('D', $abril1), 'Sat') != 0)) {
        $cont_abril = $cont_abril + 1;
    }
}

//mayo
$mayo3=$anio."-05-01";
$tdias_mayo = date('t', strtotime($mayo3));
$mayo1 = strtotime($mayo3);
$mayo2 = strtotime($mayo3 . "+ " . $tdias_mayo . " days");
$cont_mayo = 0;
for ($mayo1; $mayo1 <= $mayo2; $mayo1 = strtotime('+1 day ' . date('Y-m-d', $mayo1))) {
    if ((strcmp(date('D', $mayo1), 'Sun') != 0) and (strcmp(date('D', $mayo1), 'Sat') != 0)) {
        $cont_mayo = $cont_mayo + 1;
    }
}

//junio
$junio3=$anio."-06-01";
$tdias_junio = date('t', strtotime($junio3));
$junio1 = strtotime($junio3);
$junio2 = strtotime($junio3 . "+ " . $tdias_junio . " days");
$cont_junio = 0;
for ($junio1; $junio1 <= $junio2; $junio1 = strtotime('+1 day ' . date('Y-m-d', $junio1))) {
    if ((strcmp(date('D', $junio1), 'Sun') != 0) and (strcmp(date('D', $junio1), 'Sat') != 0)) {
        $cont_junio = $cont_junio + 1;
    }
}

//julio
$julio3=$anio."-07-01";
$tdias_julio = date('t', strtotime($julio3));
$julio1 = strtotime($julio3);
$julio2 = strtotime($julio3 . "+ " . $tdias_julio . " days");
$cont_julio = 0;
for ($julio1; $julio1 <= $julio2; $julio1 = strtotime('+1 day ' . date('Y-m-d', $julio1))) {
    if ((strcmp(date('D', $julio1), 'Sun') != 0) and (strcmp(date('D', $julio1), 'Sat') != 0)) {
        $cont_julio = $cont_julio + 1;
    }
}

//agosto
$agosto3=$anio."-08-01";
$tdias_agosto = date('t', strtotime($agosto3));
$agosto1 = strtotime($agosto3);
$agosto2 = strtotime($agosto3 . "+ " . $tdias_agosto . " days");
$cont_agosto = 0;
for ($agosto1; $agosto1 <= $agosto2; $agosto1 = strtotime('+1 day ' . date('Y-m-d', $agosto1))) {
    if ((strcmp(date('D', $agosto1), 'Sun') != 0) and (strcmp(date('D', $agosto1), 'Sat') != 0)) {
        $cont_agosto = $cont_agosto + 1;
    }
}

//septiembre
$septiembre3=$anio."-09-01";
$tdias_septiembre = date('t', strtotime($septiembre3));
$septiembre1 = strtotime($septiembre3);
$septiembre2 = strtotime($septiembre3 . "+ " . $tdias_septiembre . " days");
$cont_septiembre = 0;
for ($septiembre1; $septiembre1 <= $septiembre2; $septiembre1 = strtotime('+1 day ' . date('Y-m-d', $septiembre1))) {
    if ((strcmp(date('D', $septiembre1), 'Sun') != 0) and (strcmp(date('D', $septiembre1), 'Sat') != 0)) {
        $cont_septiembre = $cont_septiembre + 1;
    }
}

//octubre
$octubre3=$anio."-10-01";
$tdias_octubre = date('t', strtotime($octubre3));
$octubre1 = strtotime($octubre3);
$octubre2 = strtotime($octubre3 . "+ " . $tdias_octubre . " days");
$cont_octubre = 0;
for ($octubre1; $octubre1 <= $octubre2; $octubre1 = strtotime('+1 day ' . date('Y-m-d', $octubre1))) {
    if ((strcmp(date('D', $octubre1), 'Sun') != 0) and (strcmp(date('D', $octubre1), 'Sat') != 0)) {
        $cont_octubre = $cont_octubre + 1;
    }
}

//noviembre
$noviembre3=$anio."-11-01";
$tdias_noviembre = date('t', strtotime($noviembre3));
$noviembre1 = strtotime($noviembre3);
$noviembre2 = strtotime($noviembre3 . "+ " . $tdias_noviembre . " days");
$cont_noviembre = 0;
for ($noviembre1; $noviembre1 <= $noviembre2; $noviembre1 = strtotime('+1 day ' . date('Y-m-d', $noviembre1))) {
    if ((strcmp(date('D', $noviembre1), 'Sun') != 0) and (strcmp(date('D', $noviembre1), 'Sat') != 0)) {
        $cont_noviembre = $cont_noviembre + 1;
    }
}

//diciembre
$diciembre3=$anio."-12-01";
$tdias_diciembre = date('t', strtotime($diciembre3));
$diciembre1 = strtotime($diciembre3);
$diciembre2 = strtotime($diciembre3 . "+ " . $tdias_diciembre . " days");
$cont_diciembre = 0;
for ($diciembre1; $diciembre1 <= $diciembre2; $diciembre1 = strtotime('+1 day ' . date('Y-m-d', $diciembre1))) {
    if ((strcmp(date('D', $diciembre1), 'Sun') != 0) and (strcmp(date('D', $diciembre1), 'Sat') != 0)) {
        $cont_diciembre = $cont_diciembre + 1;
    }
}


$consultar_asistencias = mysqli_query($conection, "SELECT 
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='1' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_enero,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='2' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_febrero,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='3' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_marzo,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='4' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_abril,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='5' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_mayo,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='6' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_junio,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='7' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_julio,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='8' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_agosto,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='9' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_septiembre,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='10' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_octubre,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='11' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_noviembre,
(SELECT count(*) FROM asistencia WHERE MONTH(fregistro)='12' AND YEAR(fregistro)='$anio' AND idempresa='$idEmpresa') as mes_asist_diciembre
FROM asistencia WHERE idempresa='$idEmpresa' GROUP BY idempresa");
$respuesta_asistencias = mysqli_fetch_assoc($consultar_asistencias);
$cont_asistencias = mysqli_num_rows($consultar_asistencias);

$v_enero = 0;
$v_febrero = 0;
$v_marzo = 0;
$v_abril = 0;
$v_mayo = 0;
$v_junio = 0;
$v_julio = 0;
$v_agosto = 0;
$v_septiembre = 0;
$v_octubre = 0;
$v_noviembre = 0;
$v_diciembre = 0;

$asist_enero = 0;
$asist_febrero = 0;
$asist_marzo = 0;
$asist_abril = 0;
$asist_mayo = 0;
$asist_junio = 0;
$asist_julio = 0;
$asist_agosto = 0;
$asist_septiembre = 0;
$asist_octubre = 0;
$asist_noviembre = 0;
$asist_diciembre = 0;

if($cont_asistencias>0){
$v_enero = $respuesta_asistencias['mes_asist_enero'];
$v_febrero = $respuesta_asistencias['mes_asist_febrero'];
$v_marzo = $respuesta_asistencias['mes_asist_marzo'];
$v_abril = $respuesta_asistencias['mes_asist_abril'];
$v_mayo = $respuesta_asistencias['mes_asist_mayo'];
$v_junio = $respuesta_asistencias['mes_asist_junio'];
$v_julio = $respuesta_asistencias['mes_asist_julio'];
$v_agosto = $respuesta_asistencias['mes_asist_agosto'];
$v_septiembre = $respuesta_asistencias['mes_asist_septiembre'];
$v_octubre = $respuesta_asistencias['mes_asist_octubre'];
$v_noviembre = $respuesta_asistencias['mes_asist_noviembre'];
$v_diciembre = $respuesta_asistencias['mes_asist_diciembre'];


$asist_enero = ((($v_enero / $tdias_enero) * 100)/$cont_enero);
$asist_febrero = ((($v_febrero / $tdias_febrero) * 100)/$cont_febrero);
$asist_marzo = ((($v_marzo / $tdias_marzo) * 100)/$cont_marzo);
$asist_abril = ((($v_abril / $tdias_abril) * 100)/$cont_abril);
$asist_mayo = ((($v_mayo / $tdias_mayo) * 100)/$cont_mayo);
$asist_junio = ((($v_junio / $tdias_junio) * 100)/$cont_junio);
$asist_julio = ((($v_julio / $tdias_julio) * 100)/$cont_julio);
$asist_agosto = ((($v_agosto / $tdias_agosto) * 100)/$cont_agosto);
$asist_septiembre = ((($v_septiembre / $tdias_septiembre) * 100)/$cont_septiembre);
$asist_octubre = ((($v_octubre / $tdias_octubre) * 100)/$cont_octubre);
$asist_noviembre = ((($v_noviembre / $tdias_noviembre) * 100)/$cont_noviembre);
$asist_diciembre = ((($v_diciembre / $tdias_diciembre) * 100)/$cont_diciembre);

}


?>