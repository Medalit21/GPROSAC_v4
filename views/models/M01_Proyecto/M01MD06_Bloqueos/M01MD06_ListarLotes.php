<?php
session_start();

include_once "../../../../config/configuracion.php";
include_once "../../../../config/conexion_2.php";
include_once "../../../../config/codificar.php";


$data = array();
$dataList = array();

if (isset($_POST['ReturnListaLotes'])) {

    //CONSULTAR ESTADO BLOQUEADO
    $consultar_idestado = mysqli_query($conection, "SELECT codigo_item as id FROM configuracion_detalle WHERE codigo_tabla='_ESTADO_LOTE' AND nombre_corto='BLOQUEADO'");
    $respuesta_idestado = mysqli_fetch_array($consultar_idestado);
    $idmotivo = $respuesta_idestado['id'];

    $IDPROYECTO = $_POST['bxFiltroProyectoBloqueos'];
    $IDZONA = $_POST['bxFiltroZonaBloqueos'];
    $IDMANZANA = $_POST['bxFiltroManzanaBloqueos'];
    $IDLOTE = $_POST['bxFiltroLoteBloqueos'];
    $IDESTADO = $_POST['bxFiltroEstadoBloqueos'];
    $IDMOTIVO = $_POST['bxFiltroMotivoBloqueos'];

    $query_motivo = "";
    $query_estado = "";
    $query_proyecto = "";
    $query_zona = "";
    $query_manzana = "";
    $query_lote = "";


    if(empty($IDMOTIVO)){
        $query_motivo = "AND gpl.bloqueo_estado = '$idmotivo'";         
    }else{
        if($IDMOTIVO == "todos"){
            $query_motivo = "AND gpl.bloqueo_estado!=0 AND cddd.texto2 = 'M'";
        }else{
            if($IDMOTIVO == "ninguno"){
                $query_motivo = "";
            }else{
                $query_motivo = "AND gpl.bloqueo_estado = '$IDMOTIVO'";
            }
        }
    }

    if(!empty($IDESTADO)){
        $query_estado = "AND gpl.estado='$IDESTADO'";
    }

    if(!empty($IDPROYECTO)){
        $query_proyecto = "AND gpp.idproyecto='$IDPROYECTO'";
    }

    if(!empty($IDZONA)){
        $query_proyecto = "AND gpz.idzona='$IDZONA'";
    }

    if(!empty($IDMANZANA)){
        $query_proyecto = "AND gpm.idmanzana='$IDMANZANA'";
    }

    if(!empty($IDLOTE)){
        $query_proyecto = "AND gpl.idlote='$IDLOTE'";
    }  

    $query = mysqli_query($conection, "SELECT
    gpl.idlote as id,
    gpz.nombre as zona,
    gpm.nombre as manzana,
    gpl.nombre as lote,
    format(gpl.area,2) as area,
    cd.texto1 as tipoMoneda,
    format(gpl.valor_sin_casa,2) as valorlotesolo,
    format(gpl.valor_con_casa,2) as valorlotecasa,
    gpl.estado as estado,
    gpl.bloqueo_estado as motivo,
    cdd.nombre_corto as descEstado,
    cddd.nombre_corto as descMotivo,
    cdd.texto1 as colorEstado,
    cddd.texto1 as colorMotivo
    FROM gp_lote gpl
    INNER JOIN gp_manzana AS gpm ON gpm.idmanzana=gpl.idmanzana
    INNER JOIN gp_zona AS gpz ON gpz.idzona=gpm.idzona
    INNER JOIN gp_proyecto AS gpp ON gpp.idproyecto=gpz.idproyecto
    INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpl.tipo_moneda AND cd.codigo_tabla='_TIPO_MONEDA'
    INNER JOIN configuracion_detalle AS cdd ON cdd.codigo_item=gpl.estado AND cdd.codigo_tabla='_ESTADO_LOTE'
    INNER JOIN configuracion_detalle AS cddd ON (cddd.codigo_item=gpl.bloqueo_estado OR gpl.bloqueo_estado=0) AND cddd.codigo_tabla='_ESTADO_LOTE' 
    WHERE gpl.esta_borrado=0
    $query_motivo
    $query_estado
    $query_proyecto
    $query_zona
    $query_manzana
    $query_lote
    GROUP BY gpl.idlote
    ORDER BY gpl.bloqueo_estado DESC, gpl.idmanzana ASC, gpl.correlativo ASC
    ");

    if($query->num_rows > 0){
     
        while($row = $query->fetch_assoc()) {
          
            array_push($dataList,[
                'id' => $row['id'],
                'zona' => $row['zona'],
                'manzana' => $row['manzana'],
                'lote' => $row['lote'],
                'area' => $row['area'],
                'tipoMoneda' => $row['tipoMoneda'],
                'valorlotesolo' => $row['valorlotesolo'],
                'valorlotecasa' => $row['valorlotecasa'],
                'estado' => $row['estado'],
                'descEstado' => $row['descEstado'],
                'colorEstado' => $row['colorEstado'],
                'descMotivo' => $row['descMotivo'],
                'colorMotivo' => $row['colorMotivo']
            ]);}
            
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



?>